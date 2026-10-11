<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class AnswersTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('answers');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Questions', [
            'foreignKey' => 'question_id',
        ]);
        $this->belongsTo('OptionChoices', [
            'foreignKey' => 'option_choice_id',
        ]);
        $this->belongsTo('CandidatesJobs', [
            'foreignKey' => 'candidates_job_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
