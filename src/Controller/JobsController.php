<?php
declare(strict_types=1);

namespace App\Controller;

use App\Utility\SearchUtil;
use Cake\I18n\DateTime;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

class JobsController extends AppController
{
    public array $paginate = ['limit' => 15];

    public function index(): void
    {
        $this->viewBuilder()->setLayout('search');
        $this->set('searchBarComponent', 'job_search_bar');
        $presetVars = ['keywords' => '', 'tags' => '', 'filter' => false];
        $query = $this->fetchTable('Jobs')->find('active')
            ->contain(['CandidatesJobs', 'ContractModels', 'Departments', 'Priorities', 'Tags', 'Users']);

        if ($this->request->is('post')) {
            $presetVars = array_merge($presetVars, $this->request->getData());
            $keywords = (string)$this->request->getData('keywords');
            if ($keywords !== '') {
                $ids = $this->fetchTable('Jobs')->filter([
                    'keywords' => SearchUtil::convertToQueryString($keywords),
                ]);
                $query->where(['Jobs.id IN' => array_column($ids, 'job_id') ?: [0]]);
            }
            $tags = $this->request->getData('tags');
            if (!empty($tags)) {
                $tagIds = is_array($tags) ? $tags : explode(',', (string)$tags);
                $query->matching('Tags', fn($q) => $q->where(['Tags.id IN' => $tagIds]));
            }
            $filter = $this->request->getData('filter');
            if ($filter !== null && (int)$filter > 0) {
                $query->where(['Jobs.user_id' => (int)$filter]);
                $presetVars['filter'] = true;
            } elseif ((string)$filter === '0') {
                $query->matching('Users.FavoriteJobs', function ($q) {
                    return $q->where(['FavoriteJobs.user_id' => $this->authUser('id')]);
                });
            }
        }

        $jobs = $this->paginate($query);
        $this->setCake2('jobs', $jobs);
        $favorites = [];
        $userId = $this->authUser('id');
        if ($userId) {
            $favorites = $this->fetchTable('FavoriteJobs')->find()
                ->select(['job_id'])
                ->where(['user_id' => $userId])
                ->all()
                ->extract('job_id')
                ->toList();
        }
        $this->set(compact('favorites', 'presetVars'));
    }

    public function view(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('search');
        $this->set('searchBarComponent', 'job_search_bar');
        $this->set('presetVars', ['keywords' => '', 'tags' => '', 'filter' => false]);
        $job = $this->fetchTable('Jobs')->get($id, contain: [
            'ContractModels', 'Departments', 'JobExtIds',
            'JobWorkflowSteps' => ['Users', 'WorkflowSteps' => ['WorkflowStepTypes']],
            'Priorities', 'Surveys', 'Tags', 'Users',
        ]);
        $this->setCake2('job', $job);
        $questions = $this->fetchTable('Questions')->find()
            ->matching('Surveys', fn($q) => $q->where(['Surveys.job_id' => $id]))
            ->all();
        $this->setCake2('questions', $questions);
        $preLinks = $this->fetchTable('CandidatesJobs')->find('active')
            ->contain(['Candidates'])
            ->where(['CandidatesJobs.job_id' => $id])
            ->all();
        $this->setCake2('preLinks', $preLinks);
        $hired = $rejected = $open = 0;
        foreach ($preLinks as $link) {
            if ($link->hired && DateTime::parse($link->hired)->isPast()) {
                $hired++;
                continue;
            }
            if ($link->rejected && DateTime::parse($link->rejected)->isPast()) {
                $rejected++;
                continue;
            }
            $open++;
        }
        $this->set('linkData', compact('hired', 'rejected', 'open'));
    }

    public function add(): ?Response
    {
        $jobs = $this->fetchTable('Jobs');
        if ($this->request->is('post')) {
            $payload = $this->request->getData('Job') ?? $this->request->getData();
            $entity = $jobs->newEntity($payload);
            $entity->user_id = $this->authUser('id');
            $this->associateTagsAndContracts($entity);
            if ($jobs->save($entity, ['associated' => ['Tags', 'ContractModels', 'JobExtIds']])) {
                $this->fetchTable('JobWorkflowSteps')->copyWorkflowForJob((int)$entity->id, (int)$entity->workflow_id);
                $this->Flash->success(__('The job has been saved.'));
                $survey = $this->request->getData('Survey') ?? [];
                if (($survey['indicator'] ?? 'N') === 'Y') {
                    return $this->redirect(['controller' => 'Surveys', 'action' => 'add', $entity->id]);
                }

                return $this->redirect(['action' => 'view', $entity->id]);
            }
            $this->Flash->error(__('The job could not be saved. Please, try again.'));
        }
        $this->setFormLists();

        return null;
    }

    public function edit(?string $id = null): ?Response
    {
        $jobs = $this->fetchTable('Jobs');
        $job = $jobs->get($id, contain: ['Tags', 'ContractModels', 'JobExtIds', 'Surveys']);
        if ($this->request->is(['post', 'put'])) {
            $payload = $this->request->getData('Job') ?? $this->request->getData();
            $job = $jobs->patchEntity($job, $payload);
            $this->associateTagsAndContracts($job);
            if ($jobs->save($job, ['associated' => ['Tags', 'ContractModels', 'JobExtIds']])) {
                $this->Flash->success(__('The changes has been saved.'));

                return $this->redirect(['action' => 'view', $id]);
            }
            $this->Flash->error(__('An error occurred while saving the changes. Please, try again.'));
        } else {
            $this->request = $this->request->withParsedBody(
                \App\Utility\Cake2Compat::toArray($job)
            );
        }
        $this->setFormLists();

        return null;
    }

    public function delete(?string $id = null): Response
    {
        if ($this->fetchTable('Jobs')->archive((int)$id)) {
            $this->Flash->success(__('The job has been archived.'));
        } else {
            $this->Flash->error(__('The job could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    protected function associateTagsAndContracts(object $entity): void
    {
        $tagNames = explode(',', (string)($this->request->getData('Job.tags') ?? $this->request->getData('tags') ?? ''));
        $tagNames = array_filter(array_map('trim', $tagNames));
        if ($tagNames) {
            $tagsTable = $this->fetchTable('Tags');
            $tags = [];
            foreach ($tagNames as $name) {
                $tag = $tagsTable->find()->where(['name' => $name])->first();
                if (!$tag && ctype_digit($name)) {
                    $tag = $tagsTable->find()->where(['id' => (int)$name])->first();
                }
                if (!$tag) {
                    $tag = $tagsTable->newEntity(['name' => $name]);
                    $tagsTable->save($tag);
                }
                $tags[] = $tag;
            }
            $entity->tags = $tags;
        }
        $contractIds = $this->request->getData('ContractModel') ?? [];
        if ($contractIds) {
            if (isset($contractIds['ContractModel'])) {
                $contractIds = $contractIds['ContractModel'];
            }
            $entity->contract_models = $this->fetchTable('ContractModels')->find()
                ->where(['id IN' => (array)$contractIds])->all()->toList();
        }
        $ext = $this->request->getData('JobExtId');
        if (is_string($ext)) {
            $decoded = json_decode($ext, true);
            if (is_array($decoded)) {
                $entity->job_ext_ids = $this->fetchTable('JobExtIds')->newEntities($decoded);
            }
        }
    }

    protected function setFormLists(): void
    {
        $this->set('contractModels', $this->fetchTable('ContractModels')->find('list')->toArray());
        $this->set('departments', $this->fetchTable('Departments')->find('list')->toArray());
        $this->set('priorities', $this->fetchTable('Priorities')->find('list')->toArray());
        $this->set('states', $this->fetchTable('States')->find('list', keyField: 'alpha_2', valueField: 'name')->toArray());
        $this->set('tags', $this->fetchTable('Tags')->find('list')->toArray());
        $this->set('users', $this->fetchTable('Users')->find('list')->toArray());
        $this->set('workflows', $this->fetchTable('Workflows')->find('list')->find('active')->toArray());
    }
}
