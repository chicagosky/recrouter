<?php
App::uses('AppModel', 'Model');
/**
 * JobsTag Model
 *
 * @property Job $Job
 * @property Tag $Tag
 */
class JobsTag extends AppModel {

/**
 * Primary key field
 *
 * @var string
 */
	


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
		'Tag' => array(
			'className' => 'Tag',
			'foreignKey' => 'tag_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
