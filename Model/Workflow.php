<?php
App::uses('AppModel', 'Model');
/**
 * Workflow Model
 *
 * @property Job $Job
 * @property WorkflowStepUser $WorkflowStepUser
 * @property WorkflowStep $WorkflowStep
 */
class Workflow extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Job' => array(
			'className' => 'Job',
			'foreignKey' => 'workflow_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'WorkflowStepUser' => array(
			'className' => 'WorkflowStepUser',
			'foreignKey' => 'workflow_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'WorkflowStep' => array(
			'className' => 'WorkflowStep',
			'foreignKey' => 'workflow_id',
			'dependent' => false,
			'conditions' => 'WorkflowStep.archived > NOW()',
			'fields' => '',
			'order' => 'WorkflowStep.sequence ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}