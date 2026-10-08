<?php
App::uses('AppModel', 'Model');
/**
 * WorkflowStepUser Model
 *
 * @property Workflow $Workflow
 * @property WorkflowStep $WorkflowStep
 * @property User $User
 */
class WorkflowStepUser extends AppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Workflow' => array(
			'className' => 'Workflow',
			'foreignKey' => 'workflow_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'WorkflowStep' => array(
			'className' => 'WorkflowStep',
			'foreignKey' => 'workflow_step_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
