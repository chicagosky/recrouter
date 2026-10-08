<?php
App::uses('AppController', 'Controller');
/**
 * WorkflowStepUsers Controller
 *
 * @property WorkflowStepUser $WorkflowStepUser
 * @property PaginatorComponent $Paginator
 */
class WorkflowStepUsersController extends AppController {

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
 * add method
 *
 * @return void
 */
	public function add($userId=null) {
		$this->autorender = false;
		$this->layout = 'ajax';
		if ($this->request->is('post')) {
			$this->WorkflowStepUser->create();
			$this->WorkflowStepUser->set('workflow_id', $this->request->data('workflow_id'));
			$this->WorkflowStepUser->set('workflow_step_id', $this->request->data('workflow_step_id'));
			$this->WorkflowStepUser->set('user_id', $userId);
			if ($this->WorkflowStepUser->save()) {
				echo json_encode(array('success' => true,
								   		'message' => 'The edit has been saved',
										'workflowStepUserId' => $this->WorkflowStepUser->getID()));
			} else {
				echo json_encode(array('success' => false,
								   		'message' => 'The edit could not be saved. Please try again'));
			}
		}
		exit;
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->autorender = false;
		$this->layout = 'ajax';
		$this->WorkflowStepUser->id = $id;
		if (!$this->WorkflowStepUser->exists()) {
			throw new NotFoundException(__('Invalid workflow step user'));
		}
		//$this->request->allowMethod('post', 'delete');
		if ($this->WorkflowStepUser->delete()) {
			echo json_encode(array('success' => true,
								   	'message' => 'The edit has been saved'));
		} else {
			echo json_encode(array('success' => false,
								   	'message' => 'The edit has not been saved'));
		}
		exit;
	}
}