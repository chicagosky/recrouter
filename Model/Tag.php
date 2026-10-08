<?php
App::uses('AppModel', 'Model');
/**
 * Tag Model
 *
 * @property Candidate $Candidate
 * @property Job $Job
 */
class Tag extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Candidate' => array(
			'className' => 'Candidate',
			'joinTable' => 'candidates_tags',
			'foreignKey' => 'tag_id',
			'associationForeignKey' => 'candidate_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Job' => array(
			'className' => 'Job',
			'joinTable' => 'jobs_tags',
			'foreignKey' => 'tag_id',
			'associationForeignKey' => 'job_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

}
