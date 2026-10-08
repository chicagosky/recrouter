<?php
App::uses('AppController', 'Controller');
/**
 * Dashboards Controller
 *
 * @property Dashboard $Dashboard
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class DashboardsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	
	public $uses = array();
	
/*
 * beforeFilter method
 *
 * @return void
 */	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->loadModel('Job');
		$this->Job->recursive = -1;
		$options = array('fields' => array('id', 'department_id', 'positions', 'created'));
		$options['conditions'] = array(
			'Job.archived > NOW()'
		);
		$jobs = $this->Job->find('all', $options);
		$this->set('jobs', $jobs);
		
		$this->loadModel('Candidate');
		$this->Candidate->recursive = -1;
		$this->Candidate->Behaviors->load('Containable');
		$options = array('fields' => array('id'));
		$options['contain'] = array(
			'CandidatesJob.id', 'CandidatesJob.hired', 'CandidatesJob.rejected'
		);
		/*$options['joins'] = array(
		    array('table' => 'candidates_jobs',
		        'alias' => 'CandidatesJob',
		        'type' => 'inner',
		        'conditions' => array(
		            'FavoriteJob.user_id = '.$this->Auth->user('id'),
		            'FavoriteJob.job_id = Job.id'
		        )
		    )
		);*/
		$options['conditions'] = array(
			'Candidate.archived > NOW()'
		);
		
		$candidates = $this->Candidate->find('all', $options);
		$this->set('candidates', $candidates);
	}

}
