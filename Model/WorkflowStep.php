<?php
App::uses('AppModel', 'Model');
/**
 * WorkflowStep Model
 *
 * @property Workflow $Workflow
 * @property WorkflowStepType $WorkflowStepType
 * @property WorkflowStepUser $WorkflowStepUser
 */
class WorkflowStep extends AppModel {

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
		'WorkflowStepType' => array(
			'className' => 'WorkflowStepType',
			'foreignKey' => 'workflow_step_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'WorkflowStepUser' => array(
			'className' => 'WorkflowStepUser',
			'foreignKey' => 'workflow_step_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
