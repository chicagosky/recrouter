<?php
App::uses('AppModel', 'Model');
/**
 * FavoriteCandidate Model
 *
 * @property User $User
 * @property Candidate $Candidate
 */
class FavoriteCandidate extends AppModel {

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
		),
		'Candidate' => array(
			'className' => 'Candidate',
			'foreignKey' => 'candidate_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
