<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;
use Cake\I18n\DateTime;

class SurveysController extends AppController
{
    public function index(): Response
    {
        return $this->redirect(['controller' => 'Jobs', 'action' => 'index']);
    }

    public function view(?string $id = null): void
    {
        $this->setCake2('survey', $this->fetchTable('Surveys')->get($id, contain: ['Jobs', 'Questions']));
    }

    public function response(?string $id = null, ?string $linkId = null): void
    {
        $this->set('responseComplete', false);
        $this->set('linkId', $linkId);
        if ($this->request->is('post')) {
            $answers = json_decode((string)$this->request->getData('responses'), true) ?: [];
            $table = $this->fetchTable('Answers');
            $table->saveMany($table->newEntities($answers));
            $this->set('responseComplete', true);
            $this->Flash->success(__('The responses have been successfully saved'));
        }
        $survey = $this->fetchTable('Surveys')->get($id, contain: ['Jobs', 'Questions']);
        $this->setCake2('survey', $survey);
    }

    public function add(?string $id = null): ?Response
    {
        if ($this->request->is('post')) {
            $indicator = $this->request->getData('Survey.indicator') ?? $this->request->getData('indicator');
            if ($indicator === 'N') {
                return $this->redirect(['controller' => 'Jobs', 'action' => 'view', $id]);
            }
            $data = $this->request->getData('Survey') ?? $this->request->getData();
            $data['job_id'] = $id;
            $questions = json_decode((string)($data['questions'] ?? '[]'), true) ?: [];
            unset($data['questions'], $data['indicator']);
            $table = $this->fetchTable('Surveys');
            $survey = $table->newEntity($data);
            if ($table->save($survey)) {
                $qTable = $this->fetchTable('Questions');
                foreach ($questions as $q) {
                    $q['survey_id'] = $survey->id;
                    $qTable->save($qTable->newEntity($q));
                }
                $this->Flash->success(__('The changes have been successfully saved'));
                return $this->redirect(['controller' => 'Jobs', 'action' => 'view', $id]);
            }
        }
        $job = $this->fetchTable('Jobs')->get($id);
        $this->set('jobs', \App\Utility\Cake2Compat::toArray($job));
        return null;
    }

    public function edit(?string $id = null): ?Response
    {
        $table = $this->fetchTable('Surveys');
        $survey = $table->get($id, contain: ['Questions']);
        if ($this->request->is(['post', 'put'])) {
            if (($this->request->getData('Survey.indicator') ?? '') === 'N') {
                return $this->redirect(['action' => 'delete', $id]);
            }
            $this->Flash->success(__('The survey has been saved.'));
            return $this->redirect(['controller' => 'Jobs', 'action' => 'view', $survey->job_id]);
        }
        $this->request = $this->request->withParsedBody(\App\Utility\Cake2Compat::toArray($survey));
        return null;
    }

    public function delete(?string $id = null): Response
    {
        $table = $this->fetchTable('Surveys');
        $survey = $table->get($id);
        $jobId = $survey->job_id;
        $table->archive((int)$id);
        return $this->redirect(['controller' => 'Jobs', 'action' => 'view', $jobId]);
    }
}
