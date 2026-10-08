<?php
App::uses('AppModel', 'Model');
/**
 * Answer Model
 *
 * @property Question $Question
 * @property OptionChoice $OptionChoice
 */
class Answer extends AppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Question' => array(
			'className' => 'Question',
			'foreignKey' => 'question_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'OptionChoice' => array(
			'className' => 'OptionChoice',
			'foreignKey' => 'option_choice_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
