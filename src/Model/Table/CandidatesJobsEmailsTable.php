<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CandidatesJobsEmailsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('candidates_jobs_emails');
        $this->setDisplayField('subject');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('CandidatesJobs', [
            'foreignKey' => 'candidates_job_id',
        ]);
        $this->belongsTo('CandidatesJobsEvents', [
            'foreignKey' => 'candidates_jobs_event_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
