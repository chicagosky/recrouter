<?php
App::uses('AppModel', 'Model');
/**
 * JobExtId Model
 *
 * @property Job $Job
 * @property Ext $Ext
 */
class JobExtId extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Job' => array(
			'className' => 'Job',
			'foreignKey' => 'job_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Ext' => array(
			'className' => 'Ext',
			'foreignKey' => 'ext_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
