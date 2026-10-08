<?php
App::uses('AppController', 'Controller');
/**
 * WorkflowStepTypes Controller
 *
 * @property WorkflowStepType $WorkflowStepType
 * @property PaginatorComponent $Paginator
 */
class WorkflowStepTypesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->WorkflowStepType->recursive = 0;
		$this->set('workflowStepTypes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->WorkflowStepType->exists($id)) {
			throw new NotFoundException(__('Invalid workflow step type'));
		}
		$options = array('conditions' => array('WorkflowStepType.' . $this->WorkflowStepType->primaryKey => $id));
		$this->set('workflowStepType', $this->WorkflowStepType->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->WorkflowStepType->create();
			if ($this->WorkflowStepType->save($this->request->data)) {
				$this->Session->setFlash(__('The workflow step type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The workflow step type could not be saved. Please, try again.'));
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
		if (!$this->WorkflowStepType->exists($id)) {
			throw new NotFoundException(__('Invalid workflow step type'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->WorkflowStepType->save($this->request->data)) {
				$this->Session->setFlash(__('The workflow step type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The workflow step type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('WorkflowStepType.' . $this->WorkflowStepType->primaryKey => $id));
			$this->request->data = $this->WorkflowStepType->find('first', $options);
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
		$this->WorkflowStepType->id = $id;
		if (!$this->WorkflowStepType->exists()) {
			throw new NotFoundException(__('Invalid workflow step type'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->WorkflowStepType->delete()) {
			$this->Session->setFlash(__('The workflow step type has been deleted.'));
		} else {
			$this->Session->setFlash(__('The workflow step type could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}
}
