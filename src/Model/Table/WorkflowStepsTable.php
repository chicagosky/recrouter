<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class WorkflowStepsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('workflow_steps');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Workflows', [
            'foreignKey' => 'workflow_id',
        ]);
        $this->belongsTo('WorkflowStepTypes', [
            'foreignKey' => 'workflow_step_type_id',
        ]);
        $this->hasMany('WorkflowStepUsers', [
            'foreignKey' => 'workflow_step_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
