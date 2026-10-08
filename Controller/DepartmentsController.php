<?php
App::uses('AppController', 'Controller');
/**
 * Departments Controller
 *
 * @property Department $Department
 * @property PaginatorComponent $Paginator
 */
class DepartmentsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	
	public $helpers = array('Tree');
	
	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->layout = 'dashboard';
		$this->set('viewTitle', 'Departments');
		$this->set('departments', $this->Department->find('threaded'));
		$this->set('allDepartments', $this->Department->find('list', 
													array('fields' => array('Department.id', 'Department.name'))));
    }

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Department->create();
			if ($this->request->data['Department']['parent_id'] < 1)
				$this->request->data['Department']['parent_id'] = 1; //set default parent id
				
			if ($this->Department->save($this->request->data)) {
				$this->Session->setFlash(__('The new department has been saved'), 'flash_success');
			} else {
				$this->Session->setFlash(__('There was a problem in saving the department. Please try again'), 'flash_failure');
			}
		}
		return $this->redirect(array('action' => 'index'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Department->exists($id)) {
			throw new NotFoundException(__('Invalid department'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->Department->set('id', $id);
			if ($this->Department->save($this->request->data)) {
				$this->Session->setFlash(__('The changes have been saved'), 'flash_success');
			} else {
				$this->Session->setFlash(__('There was a problem in saving the changes. Please try again'), 'flash_failure');
			}
		}
		return $this->redirect(array('action' => 'index'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Department->id = $id;
		if (!$this->Department->exists()) {
			throw new NotFoundException(__('Invalid department'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Department->delete()) {
			$this->Session->setFlash(__('The department node hierarchy has been deleted'), 'flash_success');
		} else {
			$this->Session->setFlash(__('An error occurred while trying to delete the node hierarchy. Please, try again'), 'flash_failure');
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function install() {
		// pseudo controller code
		$data['Department']['parent_id'] = NULL;
		$data['Department']['name'] = 'Parent';
		$this->Department->save($data);
		$data['Department']['parent_id'] = 1;
		$data['Department']['name'] = 'Child';
		$this->Department->save($data);
		CakeLog::debug('Department Installed');
		exit;
	}
}
