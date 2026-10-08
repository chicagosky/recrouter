<?php
App::uses('AppModel', 'Model');
/**
 * JobWorkflowStepsUser Model
 *
 * @property JobWorkflowStep $JobWorkflowStep
 * @property User $User
 */
class JobWorkflowStepsUser extends AppModel {
	
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'JobWorkflowStep' => array(
			'className' => 'JobWorkflowStep',
			'foreignKey' => 'job_workflow_step_id',
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
