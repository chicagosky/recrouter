<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Group $Group
 * @property PaginatorComponent $Paginator
 */
class GroupsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->layout = "dashboard";
		$this->set('groups', $this->Group->find('all', 
												array('conditions' => array('Group.archived >' => date('Y-m-d H:i:s')))));
		$this->set('viewTitle', 'Groups');
		$this->set('addButtonTitle', '+ Group');
		$this->set('addButtonLink', '#');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout = "dashboard";
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
		$this->set('group', $this->Group->find('first', $options));
		$this->Group->id = $id;
		$this->set('viewTitle', $this->Group->field('name'));
		$this->set('addButtonTitle', '<i class="glyphicon glyphicon-trash"></i>');
		$this->set('addButtonModal', '#archive-modal');
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The new group was created'), 'flash_success');
			} else {
				$this->Session->setFlash(__('An error occurred while trying to create the group. Please try again'), 'flash_failure');
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
		$this->autoRender = false;
		$this->layout = 'ajax';
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->Group->id = $id;
			$this->Group->set('name', $this->request->data['value']);
			if ($this->Group->save()) {
				echo json_encode(array('success' => true));
			} else {
				echo json_encode(array('success' => false));
			}
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
		$this->Group->id = $id;
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Invalid group'));
		}
		$this->Group->set('archived', date('Y-m-d H:i:s')); //set current datetime
		if ($this->Group->save()) {
			$this->Session->setFlash(__('The group has been deleted.'), 'flash_success');
		} else {
			$this->Session->setFlash(__('The group could not be deleted. Please, try again.'), 'flash_failure');
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('doesExist', 'index', 'add');
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
			$groupName = $this->request->data['groupname']; 
			$group = $this->Group->findByName($groupName); //find if group name exists in database
			if ($group) {
				$isAvailable = false;
			}
		}
		//output response as JSON array
		echo json_encode(array('valid' => $isAvailable));
	}
}
