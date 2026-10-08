<?php
App::uses('AppController', 'Controller');
/**
 * CandidatesTags Controller
 *
 * @property CandidatesTag $CandidatesTag
 * @property PaginatorComponent $Paginator
 */
class CandidatesTagsController extends AppController {

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
		$this->CandidatesTag->recursive = 0;
		$this->set('candidatesTags', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CandidatesTag->exists($id)) {
			throw new NotFoundException(__('Invalid candidates tag'));
		}
		$options = array('conditions' => array('CandidatesTag.' . $this->CandidatesTag->primaryKey => $id));
		$this->set('candidatesTag', $this->CandidatesTag->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->CandidatesTag->create();
			if ($this->CandidatesTag->save($this->request->data)) {
				$this->Session->setFlash(__('The candidates tag has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The candidates tag could not be saved. Please, try again.'));
			}
		}
		$candidates = $this->CandidatesTag->Candidate->find('list');
		$tags = $this->CandidatesTag->Tag->find('list');
		$this->set(compact('candidates', 'tags'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CandidatesTag->exists($id)) {
			throw new NotFoundException(__('Invalid candidates tag'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CandidatesTag->save($this->request->data)) {
				$this->Session->setFlash(__('The candidates tag has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The candidates tag could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CandidatesTag.' . $this->CandidatesTag->primaryKey => $id));
			$this->request->data = $this->CandidatesTag->find('first', $options);
		}
		$candidates = $this->CandidatesTag->Candidate->find('list');
		$tags = $this->CandidatesTag->Tag->find('list');
		$this->set(compact('candidates', 'tags'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CandidatesTag->id = $id;
		if (!$this->CandidatesTag->exists()) {
			throw new NotFoundException(__('Invalid candidates tag'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->CandidatesTag->delete()) {
			$this->Session->setFlash(__('The candidates tag has been deleted.'));
		} else {
			$this->Session->setFlash(__('The candidates tag could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
