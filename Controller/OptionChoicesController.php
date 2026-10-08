<?php
App::uses('AppController', 'Controller');
/**
 * OptionChoices Controller
 *
 * @property OptionChoice $OptionChoice
 * @property PaginatorComponent $Paginator
 */
class OptionChoicesController extends AppController {

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
		$this->OptionChoice->recursive = 0;
		$this->set('optionChoices', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OptionChoice->exists($id)) {
			throw new NotFoundException(__('Invalid option choice'));
		}
		$options = array('conditions' => array('OptionChoice.' . $this->OptionChoice->primaryKey => $id));
		$this->set('optionChoice', $this->OptionChoice->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OptionChoice->create();
			if ($this->OptionChoice->save($this->request->data)) {
				$this->Session->setFlash(__('The option choice has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The option choice could not be saved. Please, try again.'));
			}
		}
		$optionGroups = $this->OptionChoice->OptionGroup->find('list');
		$this->set(compact('optionGroups'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OptionChoice->exists($id)) {
			throw new NotFoundException(__('Invalid option choice'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OptionChoice->save($this->request->data)) {
				$this->Session->setFlash(__('The option choice has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The option choice could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OptionChoice.' . $this->OptionChoice->primaryKey => $id));
			$this->request->data = $this->OptionChoice->find('first', $options);
		}
		$optionGroups = $this->OptionChoice->OptionGroup->find('list');
		$this->set(compact('optionGroups'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OptionChoice->id = $id;
		if (!$this->OptionChoice->exists()) {
			throw new NotFoundException(__('Invalid option choice'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OptionChoice->delete()) {
			$this->Session->setFlash(__('The option choice has been deleted.'));
		} else {
			$this->Session->setFlash(__('The option choice could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
