<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CandidatesJobsEventsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('candidates_jobs_events');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('CandidatesJobs', [
            'foreignKey' => 'candidates_job_id',
        ]);
        $this->belongsTo('JobWorkflowSteps', [
            'foreignKey' => 'job_workflow_step_id',
        ]);
        $this->hasOne('CandidatesJobsEmails', [
            'foreignKey' => 'candidates_jobs_event_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
