<?php
App::uses('AppModel', 'Model');
/**
 * Country Model
 *
 * @property State $State
 */
class Country extends AppModel {

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
		'State' => array(
			'className' => 'State',
			'foreignKey' => 'country_id',
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
