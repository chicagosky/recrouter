<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\WorkflowStepType;
use Cake\Http\Response;
use Cake\I18n\DateTime;

class CandidatesJobsEventsController extends AppController
{
    public function add(): Response
    {
        $data = $this->request->getData('CandidatesJobsEvent') ?? $this->request->getData();
        $table = $this->fetchTable('CandidatesJobsEvents');
        $ok = (bool)$table->save($table->newEntity($data));

        return $this->jsonResponse([
            'success' => $ok,
            'message' => $ok ? 'The event has been succesfully updated' : 'An error occurred while saving the event. Please try again',
        ]);
    }

    public function addEmailEvent(): Response
    {
        $events = $this->fetchTable('CandidatesJobsEvents');
        $emails = $this->fetchTable('CandidatesJobsEmails');
        $event = $events->newEntity([
            'candidates_job_id' => $this->request->getData('candidates_job_id'),
            'event_type_id' => WorkflowStepType::STEP_TYPE_NOTIFY,
            'completed' => DateTime::now(),
        ]);
        $events->save($event);
        $emails->save($emails->newEntity([
            'candidates_job_id' => $event->candidates_job_id,
            'candidates_jobs_event_id' => $event->id,
            'from' => $this->request->getData('from'),
            'to' => $this->request->getData('to'),
            'cc' => $this->request->getData('cc'),
            'subject' => $this->request->getData('subject'),
            'message' => $this->request->getData('message'),
        ]));

        return $this->jsonResponse(['success' => true]);
    }
}
