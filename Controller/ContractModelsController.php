<?php
App::uses('AppController', 'Controller');
/**
 * ContractModels Controller
 *
 * @property ContractModel $ContractModel
 * @property PaginatorComponent $Paginator
 */
class ContractModelsController extends AppController {

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
		$this->ContractModel->recursive = 0;
		$this->set('contractModels', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ContractModel->exists($id)) {
			throw new NotFoundException(__('Invalid contract model'));
		}
		$options = array('conditions' => array('ContractModel.' . $this->ContractModel->primaryKey => $id));
		$this->set('contractModel', $this->ContractModel->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ContractModel->create();
			if ($this->ContractModel->save($this->request->data)) {
				$this->Session->setFlash(__('The contract model has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The contract model could not be saved. Please, try again.'));
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
		if (!$this->ContractModel->exists($id)) {
			throw new NotFoundException(__('Invalid contract model'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->ContractModel->save($this->request->data)) {
				$this->Session->setFlash(__('The contract model has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The contract model could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ContractModel.' . $this->ContractModel->primaryKey => $id));
			$this->request->data = $this->ContractModel->find('first', $options);
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
		$this->ContractModel->id = $id;
		if (!$this->ContractModel->exists()) {
			throw new NotFoundException(__('Invalid contract model'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->ContractModel->delete()) {
			$this->Session->setFlash(__('The contract model has been deleted.'));
		} else {
			$this->Session->setFlash(__('The contract model could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}
}
