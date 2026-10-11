<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class QuestionsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('questions');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->belongsTo('Surveys', [
            'foreignKey' => 'survey_id',
        ]);
        $this->belongsTo('OptionGroups', [
            'foreignKey' => 'option_group_id',
        ]);
        $this->hasMany('Answers', [
            'foreignKey' => 'question_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
