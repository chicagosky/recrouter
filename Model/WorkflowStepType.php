<?php
App::uses('AppModel', 'Model');
/**
 * WorkflowStepType Model
 *
 * @property WorkflowStep $WorkflowStep
 */
class WorkflowStepType extends AppModel {
	
	public static $STEP_TYPES = 
		array(
			'1' => array('name' => 'link', 
							'icon' => 'glyphicon glyphicon-link'),
			'2' => array('name' => 'hire', 
							'icon' => 'glyphicon glyphicon-thumbs-up'),
			'3' => array('name' => 'reject', 
							'icon' => 'glyphicon glyphicon-thumbs-down'),
			'4' => array('name' => 'notify', 
							'icon' => 'glyphicon glyphicon-envelope'),
			'5' => array('name' => 'assess', 
							'icon' => 'glyphicon glyphicon-list-alt'),
			'7' => array('name' => 'verify', 
							'icon' => 'glyphicon glyphicon-search'),
			'8' => array('name' => 'process', 
							'icon' => 'glyphicon glyphicon-cog')
		);
	
	public static $STEP_TYPE_LINK = 1;
	public static $STEP_TYPE_HIRE = 2;
	public static $STEP_TYPE_REJECT = 3;
	public static $STEP_TYPE_NOTIFY = 4;
	public static $STEP_TYPE_ASSESS = 5;
	public static $STEP_TYPE_VERIFY = 7;
	public static $STEP_TYPE_PROCESS = 8;

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'WorkflowStep' => array(
			'className' => 'WorkflowStep',
			'foreignKey' => 'workflow_step_type_id',
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
