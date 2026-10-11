<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\DateTime;
use Cake\Validation\Validator;

class JobWorkflowStepsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('job_workflow_steps');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->belongsTo('Jobs', ['foreignKey' => 'job_id']);
        $this->belongsTo('Workflows', ['foreignKey' => 'workflow_id']);
        $this->belongsTo('WorkflowSteps', ['foreignKey' => 'workflow_step_id']);
        $this->hasOne('CandidatesJobsEvents', ['foreignKey' => 'job_workflow_step_id']);
        $this->belongsToMany('Users', [
            'joinTable' => 'job_workflow_steps_users',
            'foreignKey' => 'job_workflow_step_id',
            'targetForeignKey' => 'user_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');

        return $validator;
    }

    public function copyWorkflowForJob(int $jobId, int $workflowId): void
    {
        $connection = $this->getConnection();
        $now = DateTime::now()->format('Y-m-d H:i:s');
        $connection->execute(
            'INSERT INTO job_workflow_steps (job_id, workflow_id, workflow_step_id, sequence, created)
             SELECT ?, ?, id, sequence, ? FROM workflow_steps
             WHERE workflow_id = ? AND archived > ?',
            [$jobId, $workflowId, $now, $workflowId, $now]
        );
        $connection->execute(
            'INSERT INTO job_workflow_steps_users (job_workflow_step_id, user_id)
             SELECT a.id, b.user_id
             FROM job_workflow_steps a, workflow_step_users b
             WHERE a.workflow_id = b.workflow_id
             AND a.workflow_step_id = b.workflow_step_id
             AND a.job_id = ?',
            [$jobId]
        );
    }

    public function findStepsAndEventsForLink(int $linkId, int $jobId): array
    {
        $connection = $this->getConnection();
        $sql = <<<SQL
SELECT JobWorkflowStep.id AS job_workflow_step_id,
       CandidatesJobsEvent.id AS event_id,
       JobWorkflowStep.sequence AS sequence,
       WorkflowStep.name AS title,
       WorkflowStep.workflow_step_type_id AS type_id,
       COALESCE(CandidatesJobsEvent.notes, '') AS notes,
       COALESCE(CandidatesJobsEvent.modified, '9999-12-31 00:00:00') AS modified,
       COALESCE(CandidatesJobsEvent.completed, '9999-12-31 00:00:00') AS completed
FROM job_workflow_steps JobWorkflowStep
LEFT JOIN workflow_steps WorkflowStep ON WorkflowStep.id = JobWorkflowStep.workflow_step_id
LEFT JOIN candidates_jobs_events CandidatesJobsEvent
    ON CandidatesJobsEvent.job_workflow_step_id = JobWorkflowStep.id
    AND CandidatesJobsEvent.candidates_job_id = ?
WHERE JobWorkflowStep.job_id = ?
UNION ALL
SELECT CandidatesJobsEvent.job_workflow_step_id,
       CandidatesJobsEvent.id AS event_id,
       0 AS sequence,
       '' AS title,
       CandidatesJobsEvent.event_type_id AS type_id,
       CandidatesJobsEvent.notes,
       CandidatesJobsEvent.modified,
       CandidatesJobsEvent.completed
FROM candidates_jobs_events CandidatesJobsEvent
WHERE candidates_job_id = ?
AND (job_workflow_step_id IS NULL OR job_workflow_step_id = 0)
ORDER BY modified, sequence
SQL;

        // Cake 2 Model::query() wrapped each unaliased row as [0 => $row]; the view reads $step[0].
        $rows = $connection->execute($sql, [$linkId, $jobId, $linkId])->fetchAll('assoc');

        return array_map(static fn(array $row) => [0 => $row], $rows);
    }
}
