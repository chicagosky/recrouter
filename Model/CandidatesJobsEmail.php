<?php
App::uses('AppModel', 'Model');
/**
 * CandidatesJobsEmail Model
 *
 * @property CandidatesJob $CandidatesJob
 * @property CandidatesJobsEvent $CandidatesJobsEvent
 */
class CandidatesJobsEmail extends AppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'CandidatesJob' => array(
			'className' => 'CandidatesJob',
			'foreignKey' => 'candidates_job_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CandidatesJobsEvent' => array(
			'className' => 'CandidatesJobsEvent',
			'foreignKey' => 'candidates_jobs_event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
