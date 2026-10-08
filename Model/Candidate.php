<?php
App::uses('AppModel', 'Model');
/**
 * Candidate Model
 *
 * @property User $User
 * @property ContractModel $ContractModel
 * @property Job $Job
 * @property Tag $Tag
 */
class Candidate extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'email';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
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
		'CandidatesJob' => array(
			'className' => 'CandidatesJob',
			'foreignKey' => 'candidate_id',
			'dependent' => false,
			'conditions' => 'CandidatesJob.archived > NOW()',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'ContractModel' => array(
			'className' => 'ContractModel',
			'joinTable' => 'candidates_contract_models',
			'foreignKey' => 'candidate_id',
			'associationForeignKey' => 'contract_model_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Tag' => array(
			'className' => 'Tag',
			'joinTable' => 'candidates_tags',
			'foreignKey' => 'candidate_id',
			'associationForeignKey' => 'tag_id',
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
