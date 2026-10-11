<?php
declare(strict_types=1);

namespace App\Controller;

use App\Resume\SovrenParser;
use App\Utility\SearchUtil;
use App\Utility\UploadUtil;
use Cake\Http\Response;
use Cake\I18n\DateTime;

class CandidatesController extends AppController
{
    public array $paginate = ['limit' => 15];

    public function index(): void
    {
        $this->viewBuilder()->setLayout('search');
        $this->set('searchBarComponent', 'candidate_search_bar');
        $presetVars = ['keywords' => '', 'tags' => '', 'filter' => false];
        $query = $this->fetchTable('Candidates')->find('active')
            ->contain(['CandidatesJobs', 'Users', 'Tags', 'ContractModels']);

        if ($this->request->is('post')) {
            $presetVars = array_merge($presetVars, $this->request->getData());
            $keywords = (string)$this->request->getData('keywords');
            if ($keywords !== '') {
                $ids = $this->fetchTable('Candidates')->filter([
                    'keywords' => SearchUtil::convertToQueryString($keywords),
                ]);
                $query->where(['Candidates.id IN' => array_column($ids, 'job_id') ?: [0]]);
            }
            $filter = $this->request->getData('filter');
            if ($filter !== null && (int)$filter > 0) {
                $query->where(['Candidates.user_id' => (int)$filter]);
                $presetVars['filter'] = true;
            }
        }
        $this->setCake2('candidates', $this->paginate($query));
        $favorites = [];
        $userId = $this->authUser('id');
        if ($userId) {
            $favorites = $this->fetchTable('FavoriteCandidates')->find()
                ->where(['user_id' => $userId])
                ->all()->extract('candidate_id')->toList();
        }
        $this->set(compact('favorites', 'presetVars'));
    }

    public function listForLink(): void
    {
        $this->viewBuilder()->setLayout('ajax')->setTemplate('ajax/links');
        $query = $this->fetchTable('Candidates')->find()
            ->contain(['Tags', 'ContractModels']);
        $this->setCake2('candidates', $this->paginate($query));
    }

    public function view(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('search');
        $this->set('searchBarComponent', 'candidate_search_bar');
        $this->set('presetVars', ['keywords' => '', 'tags' => '', 'filter' => false]);
        $candidate = $this->fetchTable('Candidates')->get($id, contain: ['Users', 'Tags', 'ContractModels', 'CandidatesJobs']);
        $this->setCake2('candidate', $candidate);
    }

    public function add(): ?Response
    {
        if ($this->request->is(['post', 'put'])) {
            return $this->saveCandidate();
        }
        $this->set('contractModels', $this->fetchTable('ContractModels')->find('list')->toArray());
        $this->set('tags', $this->fetchTable('Tags')->find('list')->toArray());

        return null;
    }

    public function edit(?string $id = null, ?string $newUpload = null): ?Response
    {
        $candidate = $this->fetchTable('Candidates')->get($id, contain: ['Tags', 'ContractModels']);
        if ($this->request->is(['post', 'put'])) {
            return $this->saveCandidate((int)$id, $newUpload === 'Y');
        }
        $this->request = $this->request->withParsedBody(\App\Utility\Cake2Compat::toArray($candidate));
        $this->set('contractModels', $this->fetchTable('ContractModels')->find('list')->toArray());
        $this->set('tags', $this->fetchTable('Tags')->find('list')->toArray());

        return null;
    }

    public function delete(?string $id = null): Response
    {
        if ($this->fetchTable('Candidates')->archive((int)$id)) {
            $this->Flash->success(__('The candidate has been archived'));
        } else {
            $this->Flash->error(__('The candidate could not be archived. Please, try again'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getFileFor(?string $id = null, ?string $name = null): Response
    {
        if ($name) {
            $fullPath = ((string)$id === '-1')
                ? ROOT . DS . 'uploads' . DS . 'tmp' . DS . $name
                : ROOT . DS . 'uploads' . DS . 'candidate' . DS . $id . DS . $name;
            if (is_file($fullPath)) {
                return $this->response->withFile($fullPath);
            }
        }
        require_once ROOT . DS . 'src' . DS . 'Lib' . DS . 'Legacy' . DS . 'fpdf' . DS . 'fpdf.php';
        $fpdf = new \FPDF('P', 'mm', 'A4');
        $fpdf->AddPage();
        $fpdf->SetFont('Arial', 'B', 16);
        $fpdf->Cell(40, 10, 'No File found');

        return $this->response->withType('pdf')->withStringBody($fpdf->Output('S'));
    }

    public function upload($bulk = false): ?Response
    {
        if ($this->request->is(['post', 'put'])) {
            $files = $this->request->getData('files') ?? [];
            $util = new UploadUtil();
            $status = [];
            foreach ((array)$files as $file) {
                $response = $util->uploadFile($file, true, (bool)$bulk);
                if (!$bulk && ($response['success'] ?? false)) {
                    $parser = new SovrenParser();
                    $hrxml = $parser->parse($response['path'] ?? null);
                    $response = $parser->getCandidateFromParsedXml($hrxml, $response);

                    return $this->jsonResponse($response);
                }
                $status[] = $response;
            }
            $this->set('status', $status);
        }
        $this->viewBuilder()->setTemplate('bulk_upload');

        return null;
    }

    public function bulkAdd(): Response
    {
        return $this->jsonResponse(['success' => true, 'message' => 'Bulk add is available after files are uploaded.']);
    }

    public function removeFileFor(?string $id = null): Response
    {
        $candidate = $this->fetchTable('Candidates')->get($id);
        $candidate->file_name = null;
        $this->fetchTable('Candidates')->save($candidate);

        return $this->jsonResponse(['success' => true]);
    }

    protected function saveCandidate(?int $id = null, bool $moveUpload = true): Response
    {
        require_once ROOT . DS . 'src' . DS . 'Lib' . DS . 'Legacy' . DS . 'hnp' . DS . 'Name.php';
        require_once ROOT . DS . 'src' . DS . 'Lib' . DS . 'Legacy' . DS . 'hnp' . DS . 'Parser.php';
        $table = $this->fetchTable('Candidates');
        $data = $this->request->getData('Candidate') ?? $this->request->getData();
        $parser = new \HumanNameParser_Parser($data['name'] ?? '');
        $data['firstname'] = $parser->getFirst();
        $data['lastname'] = trim($parser->getLast() . ' ' . $parser->getSuffix());
        $data['user_id'] = $this->authUser('id');
        $entity = $id ? $table->patchEntity($table->get($id), $data) : $table->newEntity($data);
        if ($table->save($entity)) {
            if ($moveUpload && !empty($data['file_name'])) {
                $dir = ROOT . DS . 'uploads' . DS . 'candidate' . DS . $entity->id;
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $tmp = ROOT . DS . 'uploads' . DS . 'tmp' . DS . $data['file_name'];
                if (is_file($tmp)) {
                    rename($tmp, $dir . DS . $data['file_name']);
                }
            }
            $this->Flash->success($id ? __('The changes has been saved') : __('The candidate has been created'));

            return $this->redirect(['action' => 'view', $entity->id]);
        }
        $this->Flash->error(__('An error occurred while saving the candidate. Please, try again.'));

        return $this->redirect(['action' => $id ? 'edit' : 'add', $id]);
    }
}
