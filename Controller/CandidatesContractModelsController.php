<?php
App::uses('AppController', 'Controller');
/**
 * CandidatesContractModels Controller
 *
 * @property CandidatesContractModel $CandidatesContractModel
 * @property PaginatorComponent $Paginator
 */
class CandidatesContractModelsController extends AppController {

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
		$this->CandidatesContractModel->recursive = 0;
		$this->set('candidatesContractModels', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CandidatesContractModel->exists($id)) {
			throw new NotFoundException(__('Invalid candidates contract model'));
		}
		$options = array('conditions' => array('CandidatesContractModel.' . $this->CandidatesContractModel->primaryKey => $id));
		$this->set('candidatesContractModel', $this->CandidatesContractModel->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->CandidatesContractModel->create();
			if ($this->CandidatesContractModel->save($this->request->data)) {
				$this->Session->setFlash(__('The candidates contract model has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The candidates contract model could not be saved. Please, try again.'));
			}
		}
		$candidates = $this->CandidatesContractModel->Candidate->find('list');
		$contractModels = $this->CandidatesContractModel->ContractModel->find('list');
		$this->set(compact('candidates', 'contractModels'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CandidatesContractModel->exists($id)) {
			throw new NotFoundException(__('Invalid candidates contract model'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CandidatesContractModel->save($this->request->data)) {
				$this->Session->setFlash(__('The candidates contract model has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The candidates contract model could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CandidatesContractModel.' . $this->CandidatesContractModel->primaryKey => $id));
			$this->request->data = $this->CandidatesContractModel->find('first', $options);
		}
		$candidates = $this->CandidatesContractModel->Candidate->find('list');
		$contractModels = $this->CandidatesContractModel->ContractModel->find('list');
		$this->set(compact('candidates', 'contractModels'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CandidatesContractModel->id = $id;
		if (!$this->CandidatesContractModel->exists()) {
			throw new NotFoundException(__('Invalid candidates contract model'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->CandidatesContractModel->delete()) {
			$this->Session->setFlash(__('The candidates contract model has been deleted.'));
		} else {
			$this->Session->setFlash(__('The candidates contract model could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
