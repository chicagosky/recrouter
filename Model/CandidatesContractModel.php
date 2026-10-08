<?php
App::uses('AppModel', 'Model');
/**
 * CandidatesContractModel Model
 *
 * @property Candidate $Candidate
 * @property ContractModel $ContractModel
 */
class CandidatesContractModel extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Candidate' => array(
			'className' => 'Candidate',
			'foreignKey' => 'candidate_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ContractModel' => array(
			'className' => 'ContractModel',
			'foreignKey' => 'contract_model_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
