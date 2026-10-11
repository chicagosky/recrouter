<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class WorkflowStepUsersTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('workflow_step_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->belongsTo('Workflows', [
            'foreignKey' => 'workflow_id',
        ]);
        $this->belongsTo('WorkflowSteps', [
            'foreignKey' => 'workflow_step_id',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);

    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator->integer('id')->allowEmptyString('id', null, 'create');
        return $validator;
    }
}
