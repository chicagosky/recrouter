<?php
App::uses('AppModel', 'Model');
/**
 * CandidatesJobsEvent Model
 *
 * @property CandidatesJobsEmail $CandidatesJobsEmail
 * @property CandidatesJob $CandidatesJob
 * @property JobWorkflowStep $JobWorkflowStep
 */
class CandidatesJobsEvent extends AppModel {

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'CandidatesJobsEmail' => array(
			'className' => 'CandidatesJobsEmail',
			'foreignKey' => 'candidates_jobs_event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

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
		'JobWorkflowStep' => array(
			'className' => 'JobWorkflowStep',
			'foreignKey' => 'job_workflow_step_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
