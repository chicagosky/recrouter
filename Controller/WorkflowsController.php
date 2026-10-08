<?php
App::uses('AppController', 'Controller');
/**
 * Workflows Controller
 *
 * @property Workflow $Workflow
 * @property PaginatorComponent $Paginator
 */
class WorkflowsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	
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
		$this->layout = "dashboard";
		$this->set('viewTitle', 'Workflows');
		$this->Workflow->Behaviors->load('Containable');
		$this->Workflow->contain(
			array(
				'WorkflowStep' => array(
					'fields' => array('id', 'name', 'workflow_id', 'sequence', 'modified'),
					'WorkflowStepType' => array(
						'fields' => array('id', 'name', 'icon')
					)
				)
			)
		);
		$this->set('workflows', $this->Workflow->find('all', array('conditions' => array('Workflow.archived > NOW()'))));
		$this->set('allStepTypes', $this->Workflow->WorkflowStep->WorkflowStepType->find('list'));
		$this->set('addButtonTitle', '+ Workflow');
		$this->set('addButtonModal', '#');
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Workflow->create();
			if ($this->Workflow->save($this->request->data)) {
				$this->Workflow->WorkflowStep->create();
				$this->Workflow->WorkflowStep->set('workflow_id', $this->Workflow->getID());
				$this->Workflow->WorkflowStep->set('name', 'Linked'); //future - replace hard coding with constant
				$this->Workflow->WorkflowStep->set('workflow_step_type_id', 1); //future - replace hard coding with constant
				$this->Workflow->WorkflowStep->set('sequence', 0);
				$this->Workflow->WorkflowStep->save();
				$this->Workflow->WorkflowStep->create();
				$this->Workflow->WorkflowStep->set('workflow_id', $this->Workflow->getID());
				$this->Workflow->WorkflowStep->set('name', 'Closed'); //future - replace hard coding with constant
				$this->Workflow->WorkflowStep->set('workflow_step_type_id', 2); //future - replace hard coding with constant
				$this->Workflow->WorkflowStep->set('sequence', 1);
				$this->Workflow->WorkflowStep->save();
				$this->Session->setFlash(__('The workflow has been saved'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('An error occurred while saving the workflow. Please, try again'), 'flash_failure');
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Workflow->id = $id;
		if (!$this->Workflow->exists()) {
			throw new NotFoundException(__('Invalid workflow'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->Workflow->set('name', $this->request->data['value']);
			if ($this->Workflow->save()) {
				$this->Session->setFlash(__('The edit has been saved'), 'flash_success');
			} else {
				$this->Session->setFlash(__('An error occurred while saving the changes. Please, try again'), 'flash_failure');
			}
			return $this->redirect(array('action' => 'index'));
		} else {
			$options = array('conditions' => array('Workflow.' . $this->Workflow->primaryKey => $id));
			$this->request->data = $this->Workflow->find('first', $options);
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
		$this->Workflow->id = $id;
		if (!$this->Workflow->exists()) {
			throw new NotFoundException(__('Invalid workflow'));
		}
		
		//archive workflow step users, workflow steps followed by workflow
		$this->Workflow->WorkflowStepUser->unbindAll(); //unbind associations temporarily		
		$this->Workflow->WorkflowStepUser->updateAll(array('archived ' => 'NOW()'),
													 array('workflow_id =' => $id));
		$this->Workflow->WorkflowStep->unbindAll();
		$this->Workflow->WorkflowStep->updateAll(array('archived' => 'NOW()'),
												 array('workflow_id =' => $id));
		$this->Workflow->set('archived', date('Y-m-d H:i:s')); 
		if ($this->Workflow->save()) {
			$this->Session->setFlash(__('The workflow has been archived'), 'flash_success');
		} else {
			$this->Session->setFlash(__('The workflow could not be archived. Please, try again'), 'flash_failure');
		}
		return $this->redirect(array('action' => 'index'));
	}
	
/**
 * doesExist method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */	
	public function doesExist() {
		$this->autoRender = false; 
		$isAvailable = true;
		if ($this->request->is('post')) {
			$workflowName = $this->request->data['workflowname']; 
			$workflow = $this->Workflow->findByName($workflowName); //find if group name exists in database
			if ($workflow) {
				$isAvailable = false;
			}
		}
		echo json_encode(array('valid' => $isAvailable));
	}
}
