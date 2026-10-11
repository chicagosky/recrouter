<?php
declare(strict_types=1);

namespace App\Controller;

use App\Mailer\RecrouterMailer;
use App\Model\Entity\WorkflowStepType;
use App\Model\Table\AppTable;
use App\Utility\CPanelUtil;
use Cake\Http\Response;
use Cake\I18n\DateTime;
use Cake\Utility\Hash;

class CandidatesJobsController extends AppController
{
    public array $paginate = ['limit' => 5];

    public function index(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('ajax')->setTemplate('ajax/index');
        $query = $this->fetchTable('CandidatesJobs')->find('active')
            ->contain(['Candidates'])
            ->where(['CandidatesJobs.job_id' => $id])
            ->orderBy(['Candidates.firstname' => 'ASC']);
        $this->setCake2('links', $this->paginate($query));
    }

    public function view(?string $id = null): void
    {
        $link = $this->fetchTable('CandidatesJobs')->get($id, contain: ['Candidates', 'Jobs']);
        $this->setCake2('candidatesJob', $link);
        $this->set(
            'stepsAndEvents',
            $this->fetchTable('JobWorkflowSteps')->findStepsAndEventsForLink((int)$id, (int)$link->job_id)
        );
        $questions = $this->fetchTable('Questions')->find()
            ->matching('Surveys', fn($q) => $q->where(['Surveys.job_id' => $link->job_id]))
            ->contain(['Answers' => fn($q) => $q->where(['Answers.candidates_job_id' => $id])])
            ->all();
        $this->setCake2('questions', $questions);
        $messages = $this->fetchTable('CandidatesJobsEmails')->find()
            ->where(['candidates_job_id' => $id])
            ->all();
        $combined = [];
        foreach (\App\Utility\Cake2Compat::toArray($messages) as $row) {
            $eventId = $row['CandidatesJobsEmail']['candidates_jobs_event_id'] ?? null;
            if ($eventId) {
                $combined[$eventId] = $row;
            }
        }
        $this->set('messages', $combined);
    }

    public function hire(?string $id = null): Response
    {
        return $this->edit('hire', $id);
    }

    public function reject(?string $id = null): Response
    {
        return $this->edit('reject', $id);
    }

    public function reopen(?string $id = null): Response
    {
        return $this->edit('reopen', $id);
    }

    public function edit(?string $action = null, ?string $id = null): Response
    {
        $table = $this->fetchTable('CandidatesJobs');
        $link = $table->get($id);
        $events = $this->fetchTable('CandidatesJobsEvents');
        if (strcasecmp((string)$action, 'hire') === 0) {
            $link->hired = DateTime::now();
            $table->save($link);
            $events->save($events->newEntity([
                'candidates_job_id' => $link->id,
                'event_type_id' => WorkflowStepType::STEP_TYPE_HIRE,
                'completed' => DateTime::now(),
            ]));
            $this->Flash->success(__('The candidate has been successfully marked as hired'));
        } elseif (strcasecmp((string)$action, 'reject') === 0) {
            $link->rejected = DateTime::now();
            $table->save($link);
            $events->save($events->newEntity([
                'candidates_job_id' => $link->id,
                'event_type_id' => WorkflowStepType::STEP_TYPE_REJECT,
                'completed' => DateTime::now(),
            ]));
            $this->Flash->success(__('The candidate has been successfully marked as rejected'));
        } elseif (strcasecmp((string)$action, 'reopen') === 0) {
            $link->hired = AppTable::SENTINEL;
            $link->rejected = AppTable::SENTINEL;
            $table->save($link);
            $events->deleteAll([
                'candidates_job_id' => $link->id,
                'event_type_id IN' => [WorkflowStepType::STEP_TYPE_HIRE, WorkflowStepType::STEP_TYPE_REJECT],
            ]);
            $this->Flash->success(__('The link has been successfully reopened'));
        }

        return $this->redirect(['action' => 'view', $id]);
    }

    public function delete(?string $id = null): Response
    {
        $table = $this->fetchTable('CandidatesJobs');
        $link = $table->get($id);
        $jobId = $link->job_id;
        $table->archive((int)$id);

        return $this->redirect(['controller' => 'Jobs', 'action' => 'view', $jobId]);
    }

    public function link(?string $candidateId = null, ?string $jobId = null): Response
    {
        $table = $this->fetchTable('CandidatesJobs');
        $existing = $table->find('active')->where([
            'candidate_id' => $candidateId,
            'job_id' => $jobId,
        ])->first();
        if ($existing) {
            $table->archive((int)$existing->id);

            return $this->jsonResponse(['success' => true, 'message' => 'The link was successfully archived']);
        }
        $step = $this->fetchTable('JobWorkflowSteps')->find()
            ->matching('WorkflowSteps', fn($q) => $q->where([
                'WorkflowSteps.workflow_step_type_id' => WorkflowStepType::STEP_TYPE_LINK,
            ]))
            ->where(['JobWorkflowSteps.job_id' => $jobId])
            ->first();
        if (!$step) {
            return $this->jsonResponse(['success' => false, 'message' => 'Invalid or corrupt data. Please try again']);
        }
        $link = $table->newEntity(['job_id' => $jobId, 'candidate_id' => $candidateId]);
        if (!$table->save($link)) {
            return $this->jsonResponse(['success' => false, 'message' => 'An error occurred while saving the link. Please try again']);
        }
        $this->fetchTable('CandidatesJobsEvents')->save($this->fetchTable('CandidatesJobsEvents')->newEntity([
            'candidates_job_id' => $link->id,
            'job_workflow_step_id' => $step->id,
            'event_type_id' => WorkflowStepType::STEP_TYPE_LINK,
            'completed' => DateTime::now(),
        ]));
        $result = CPanelUtil::createEmailForwarder('recrouter_link_' . $link->id);
        if (!empty($result['success'])) {
            $link->email = $result['email'] ?? null;
            $table->save($link);
        }
        $this->sendSurveyToCandidateForJob([
            'candidateId' => (int)$candidateId,
            'jobId' => (int)$jobId,
            'linkId' => (int)$link->id,
            'linkEmail' => $link->email,
        ]);

        return $this->jsonResponse(['success' => true, 'message' => 'The link has been successfully created']);
    }

    protected function sendSurveyToCandidateForJob(array $data): void
    {
        $survey = $this->fetchTable('Surveys')->find('active')->where(['job_id' => $data['jobId']])->first();
        if (!$survey) {
            return;
        }
        $job = $this->fetchTable('Jobs')->get($data['jobId']);
        $candidate = $this->fetchTable('Candidates')->get($data['candidateId']);
        if (!$candidate->email) {
            return;
        }
        (new RecrouterMailer())->sendTemplated('registration', (string)$candidate->email, 'survey_to_candidate', [
            'candidate' => $candidate->toArray(),
            'job' => $job->toArray(),
            'linkId' => $data['linkId'],
            'survey' => $survey->toArray(),
        ]);
    }
}
