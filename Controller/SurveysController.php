<?php
App::uses('AppController', 'Controller');
/**
 * Surveys Controller
 *
 * @property Survey $Survey
 * @property PaginatorComponent $Paginator
 */
class SurveysController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	
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
		$this->Survey->recursive = 0;
		$this->set('surveys', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Survey->exists($id)) {
			throw new NotFoundException(__('Invalid survey'));
		}
		$options = array('conditions' => array('Survey.' . $this->Survey->primaryKey => $id));
		$this->set('survey', $this->Survey->find('first', $options));
	}
	
/**
 * response method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function response($id = null, $linkId = null) {
		if (!$this->Survey->exists($id)) {
			throw new NotFoundException(__('Invalid survey'));
		}
		$this->set('responseComplete', false);
		if ($this->request->is('post')) {
			$this->request->data['Answer'] = json_decode($this->request->data['responses'], true, 3, JSON_BIGINT_AS_STRING);
			$this->loadModel('Answer');
			$this->Answer->create();
			if ($this->Answer->saveMany($this->request->data['Answer'])) {
				$this->set('responseComplete', true);
				$this->Session->setFlash(__('The responses have been successfully saved'), 'flash_success');
			} else {
				$this->Session->setFlash(__('An error occurred while trying to save the responses. Please, try again'), 'flash_failure');
			}
		}
		
		$options = array(
			'fields' => array(
				'id', 'instructions', 'Job.id', 'Job.title', 'Job.city', 'Job.state', 'Job.description', 'Job.skills'
			),
			'conditions' => array('Survey.' . $this->Survey->primaryKey => $id)
		);
		$survey = $this->Survey->find('first', $options);
		$this->set('survey', $survey);
		$this->set('linkId', $linkId);
	}

/**
 * add method
 *
 * @return void
 */
	public function add($id=null) { //$id is job id
		if ($this->request->is('post')) {
			$this->request->data['Question'] = json_decode($this->request->data['Survey']['questions'], true, 3, JSON_BIGINT_AS_STRING);
			$this->request->data['Survey']['job_id'] = $id;
			if ('N' == $this->request->data['Survey']['indicator']) { //survey not be included
				return $this->redirect(array('controller' => 'jobs',
											 'action' => 'view', $id));
			}
			$this->Survey->create();
			if ($this->Survey->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The changes have been successfully saved'), 'flash_success');
				return $this->redirect(array('controller' => 'jobs',
											 'action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('An error occurred while trying to save the changes. Please, try again'), 'flash_failure');
			}
		}
		$jobs = $this->Survey->Job->find('first', array('fields' => array('Job.id', 'Job.title'),'conditions' => array('Job.id = ' => $id)));
		$this->set(compact('jobs'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) { //$id is survey id
		if ($id && !$this->Survey->exists($id)) {
			throw new NotFoundException(__('Invalid survey'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ('N' == $this->request->data['Survey']['indicator']) { //survey not be included
				return $this->redirect(array('action' => 'delete', $id));
			}
			$this->request->data['Survey']['id'] = $id;
			$this->request->data['Question'] = json_decode($this->request->data['Survey']['questions'], true, 3, JSON_BIGINT_AS_STRING);
			if ($this->Survey->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The survey has been saved.'), 'flash_success');
				return $this->redirect(array('controller' => 'jobs','action' => 'view', $this->Survey->field('job_id')));
			} else {
				$this->Session->setFlash(__('The changes could not be saved. Please, try again'), 'flash_failure');
			}
		} else {
			$options = array('conditions' => array('Survey.' . $this->Survey->primaryKey => $id));
			$this->request->data = $this->Survey->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Survey->id = $id;
		if (!$this->Survey->exists()) {
			throw new NotFoundException(__('Invalid survey'));
		}
		$jobId = $this->Survey->field('job_id');
		$db = $this->Survey->getDataSource(); //make update value into literal string
		$today = $db->value(date('Y-m-d H:i:s'), 'string');
		$this->Survey->set('archived', date('Y-m-d H:i:s')); 
		if ($this->Survey->save()) {
			$this->Session->setFlash(__('The changes have been saved'), 'flash_success');
		} else {
			$this->Session->setFlash(__('An error occurred while saving the changes. Please, try again'), 'flash_failure');
		}
		return $this->redirect(array('controller' => 'jobs','action' => 'view', $jobId));
	}
}
