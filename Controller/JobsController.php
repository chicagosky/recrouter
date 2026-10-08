<?php
App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');
App::uses('SearchUtil', 'Lib');
/**
 * Jobs Controller
 *
 * @property Job $Job
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class JobsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');
	public $helpers = array('Time');
	public $presetVars = array('keywords' => '', 'tags' => '', 'filter' => false);
	
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
		$this->layout = 'search';
		$this->set('searchBarComponent', 'job_search_bar');
		$options = array();
		$conditions = array();
		$this->Job->Behaviors->load('Containable');
		
		if ($this -> request -> is('post')) {
			$this->presetVars = array_merge($this->presetVars, $this->request->data);
			if (!empty($this->request->data['keywords'])) {
				$filters = array('keywords' => SearchUtil::convertToQueryString($this->request->data['keywords'], '%', '%'));
				$filteredJobIds = $this->Job->filter($filters);
				$conditions['Job.id'] = array_map(function($row) {
													return $row['job_id'];
												  }, $filteredJobIds);
			}
			
			if (!empty($this->request->data['tags'])) {
				$options['joins'] = array(
				    array('table' => 'jobs_tags',
				        'alias' => 'JobsTag',
				        'type' => 'inner',
				        'conditions' => array(
				            'JobsTag.tag_id IN ('.$this->request->data['tags'].')',
				            'JobsTag.job_id = Job.id'
				        )
				    )
				);
			}								

			if (0 < $this->request->data['filter']) {
				$conditions['Job.user_id'] = $this->request->data['filter'];
				$this->presetVars['filter'] = true;
			} elseif (0 == $this->request->data['filter']) {
				$this->loadModel('FavoriteJob');
				$options['joins'] = array(
				    array('table' => 'favorite_jobs',
				        'alias' => 'FavoriteJob',
				        'type' => 'inner',
				        'conditions' => array(
				            'FavoriteJob.user_id = '.$this->Auth->user('id'),
				            'FavoriteJob.job_id = Job.id'
				        )
				    )
				);
			}			
		}

		$conditions['Job.archived >'] = date('Y-m-d H:i:s');
		$options['conditions'] = $conditions;
		$options['limit'] = 15;
		$options['fields'] = array('id', 'title', 'positions', 'fill_by', 'city', 'state', 'user_id', 'created');
		$options['contain'] = array(
									'CandidatesJob.id',
									'ContractModel.id', 'ContractModel.name',
									'Department.id', 'Department.name',
									'Priority.id', 'Priority.name',
									'Tag.id', 'Tag.name',
									'User.id', 'User.username', 'User.firstname', 'User.lastname');

		$this->Paginator->settings = $options;
		$jobs = $this->Paginator->paginate('Job');
		$this->set('jobs', $jobs);
		
		$this->loadModel('FavoriteJob');
		$favorites = $this->FavoriteJob->find('list', array('fields' => array('FavoriteJob.job_id'),
														   'conditions' => array('FavoriteJob.user_id' => $this->Auth->user('id'))));
		$this->set(compact('favorites'));
		$this->set('presetVars', $this->presetVars); 
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Job->exists($id)) {
			throw new NotFoundException(__('Invalid job'));
		}

		$this->layout = 'search';
		$this->set('searchBarComponent', 'job_search_bar');
		$this->set('presetVars', $this->presetVars);
		$this->Job->Behaviors->load('Containable');
		
		$options = array();
		$options['conditions'] = array('Job.id' => $id);
		$options['contain'] = array(
									'ContractModel.id', 'ContractModel.name',
									'Department.id', 'Department.name',
									'JobExtId.id', 'JobExtId.ext_id', 'JobExtId.ext_name',
									'JobWorkflowStep.id' => array(
																'User.id', 'User.firstname', 'User.lastname',
																'WorkflowStep.name', 'WorkflowStep.workflow_step_type_id' => array(
																																'WorkflowStepType.icon')),
									'JobWorkflowStep.workflow_step_id','JobWorkflowStep.sequence', 'JobWorkflowStep.sequence ASC',
									'Priority.id', 'Priority.name',
									'Survey',
									'Tag.id', 'Tag.name',
									'User.id', 'User.username', 'User.firstname', 'User.lastname');

		$job = $this->Job->find('first', $options);
		$this->set('job', $job);
		
		$this->Job->Survey->Question->recursive = -1;
		$joins = array(
				    array('table' => 'surveys',
				        'alias' => 'Survey',
				        'type' => 'inner',
				        'conditions' => array(
				            'Question.survey_id = Survey.id',
				            'Survey.job_id = '.$id
				        )
				    )
				);
		$questions = $this->Job->Survey->Question->find('all', array('joins' => $joins));
		$this->set('questions', $questions);
		
		//to get list of pre-selected candidates-jobs link - this is duplicate of the query in CandidatesJobController 
		//but there is no pagination here and this is simply to pre-select the existing links
		$linkOptions['contain'] = array('Candidate.id');
		$linkOptions['conditions'] = array('CandidatesJob.job_id' => $id, 'CandidatesJob.archived > NOW()');
		$linkOptions['fields'] = array('Candidate.id', 'CandidatesJob.hired', 'CandidatesJob.rejected');
		$linkOptions['order'] = array('Candidate.firstname' => 'asc');
		$this->Job->CandidatesJob->Behaviors->load('Containable');
		$preLinks = $this->Job->CandidatesJob->find('all', $linkOptions);
		$this->set('preLinks', $preLinks);
		
		$hired = 0;
		$rejected = 0;
		$open=0;
		foreach($preLinks as $link) {
			if (CakeTime::isPast($link['CandidatesJob']['hired'])) {
				$hired++;
				continue;
			}
			if (CakeTime::isPast($link['CandidatesJob']['rejected'])) {
				$rejected++;
				continue;
			}
			$open++;
		}
		$this->set('linkData', array('hired' => $hired, 'rejected' => $rejected, 'open' => $open));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Job->create();
			$this->request->data['ContractModel'] = array('ContractModel' => $this->request->data['ContractModel']);
			$tags = explode(',', $this->request->data['Job']['tags']);
			$this->request->data['Tag'] = array('Tag' => $tags);
			$this->request->data['Job']['fill_by'] = CakeTime::format($this->request->data['Job']['fill_by'], '%Y-%m-%d %H:%i:%s');
			$this->request->data['JobExtId'] = json_decode($this->request->data['JobExtId'], true, 3, JSON_BIGINT_AS_STRING);
			$this->request->data['Job']['user_id'] = $this->Auth->user('id');
			$survey = $this->request->data['Survey'];
			unset($this->request->data['Survey']);
			if ($this->Job->saveAll($this->request->data)) {
				$id = $this->Job->getID();
				$this->Job->JobWorkflowStep->copyWorkflowForJob($id, $this->request->data['Job']['workflow_id']);
				$this->Session->setFlash(__('The job has been saved.'), 'flash_success');
				if ( 'Y' == $survey['indicator']) {
					return $this->redirect(array('controller' => 'surveys',
												 'action' => 'add', $id));
				}
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('The job could not be saved. Please, try again.'), 'flash_failure');
			}
		}
		$this->loadModel('State');
		$contractModels = $this->Job->ContractModel->find('list', array('fields' => array('ContractModel.id', 'ContractModel.name')));
		$departments = $this->Job->Department->find('list');
		$priorities = $this->Job->Priority->find('list');
		$states = $this->State->find('list', array('fields' => array('State.alpha_2', 'State.name')));
		$tags = $this->Job->Tag->find('list');
		$users = $this->Job->User->find('list');
		$workflows = $this->Job->Workflow->find('list', array('conditions' => array('Workflow.archived > NOW()')));
		$this->set(compact('contractModels', 'departments', 'priorities', 'states', 'tags', 'users', 'workflows'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Job->exists($id)) {
			throw new NotFoundException(__('Invalid job'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['ContractModel'] = array('ContractModel' => $this->request->data['ContractModel']);
			$tags = explode(',', $this->request->data['Job']['tags']);
			$this->request->data['Tag'] = array('Tag' => $tags);
			$this->request->data['Job']['fill_by'] = CakeTime::format($this->request->data['Job']['fill_by'], '%Y-%m-%d %H:%i:%s');
			$this->request->data['Job']['id'] = $id;
			$this->request->data['JobExtId'] = json_decode($this->request->data['JobExtId'], true, 3, JSON_BIGINT_AS_STRING);
			$survey = $this->request->data['Survey'];
			unset($this->request->data['Survey']);
			if ($this->Job->saveAll($this->request->data)) {
				if (isset($survey['indicator'])) { //survey include indicator present
					if (('N' == $survey['indicator'])) { //delete survey and return to view
						return $this->redirect(array('controller' => 'surveys', 
												 	 'action' => 'delete', $survey['id']));
					} elseif (('Y' == $survey['indicator'])) { //survey present & included - redirect to edit
						if (empty($survey['id'])) {
							return $this->redirect(array('controller' => 'surveys', 
												 		 'action' => 'add', $this->request->data['Job']['id']));
						} else {
							return $this->redirect(array('controller' => 'surveys', 
												 		 'action' => 'edit', $survey['id']));
						}
					} 
				}
				//no survey in the first place & no survey included during edit
				//save job edits and return to view
				$this->Session->setFlash(__('The changes has been saved.'), 'flash_success');
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('An error occurred while saving the changes. Please, try again.'), 'flash_failure');
			}
		} else {
			$options = array('conditions' => array('Job.' . $this->Job->primaryKey => $id));
			$this->request->data = $this->Job->find('first', $options);
		}
		$this->loadModel('State');
		$contractModels = $this->Job->ContractModel->find('list', array('fields' => array('ContractModel.id', 'ContractModel.name')));
		$departments = $this->Job->Department->find('list');
		$priorities = $this->Job->Priority->find('list');
		$states = $this->State->find('list', array('fields' => array('State.alpha_2', 'State.name')));
		$tags = $this->Job->Tag->find('list');
		$users = $this->Job->User->find('list');
		$workflows = $this->Job->Workflow->find('list');
		$this->set(compact('contractModels', 'departments', 'priorities', 'states', 'tags', 'users', 'workflows'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Job->id = $id;
		if (!$this->Job->exists()) {
			throw new NotFoundException(__('Invalid job'));
		}
		
		$this->Job->set('archived', date('Y-m-d H:i:s')); 
		if ($this->Job->save()) {
			$this->Session->setFlash(__('The job has been archived.'), 'flash_success');
		} else {
			$this->Session->setFlash(__('The job could not be deleted. Please, try again.'), 'flash_failure');
		}
		return $this->redirect(array('action' => 'index'));
	}
}