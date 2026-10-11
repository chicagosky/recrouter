<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class JobWorkflowStepsUsersTable extends AppTable
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('job_workflow_steps_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->belongsTo('JobWorkflowSteps', [
            'foreignKey' => 'job_workflow_step_id',
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
