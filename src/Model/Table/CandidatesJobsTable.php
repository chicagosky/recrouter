<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Validation\Validator;

class CandidatesJobsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('candidates_jobs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Candidates', ['foreignKey' => 'candidate_id']);
        $this->belongsTo('Jobs', ['foreignKey' => 'job_id']);
        $this->hasMany('Answers', ['foreignKey' => 'candidates_job_id']);
        $this->hasMany('CandidatesJobsEmails', ['foreignKey' => 'candidates_job_id']);
        $this->hasMany('CandidatesJobsEvents', ['foreignKey' => 'candidates_job_id']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');

        return $validator;
    }
}
