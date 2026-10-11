<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class WorkflowsTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('workflows');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->hasMany('Jobs', [
            'foreignKey' => 'workflow_id',
        ]);
        $this->hasMany('WorkflowSteps', [
            'foreignKey' => 'workflow_id',
        ]);
        $this->hasMany('WorkflowStepUsers', [
            'foreignKey' => 'workflow_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
