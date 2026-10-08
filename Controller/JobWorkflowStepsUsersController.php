<?php
App::uses('AppController', 'Controller');
/**
 * JobWorkflowStepsUsers Controller
 *
 * @property JobWorkflowStepsUser $JobWorkflowStepsUser
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class JobWorkflowStepsUsersController extends AppController {

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
 * add method
 *
 * @return void
 */
	public function add() {
		$this->autorender = false;
		$this->layout = 'ajax';
		if ($this->request->is('post')) {
			$this->JobWorkflowStepsUser->create();
			$this->JobWorkflowStepsUser->set('user_id', $this->request->data['JobWorkflowStepsUser']['user_id']);
			$this->JobWorkflowStepsUser->set('job_workflow_step_id', $this->request->data['JobWorkflowStepsUser']['job_workflow_step_id']);
			if ($this->JobWorkflowStepsUser->save()) {
				echo json_encode(array('success' => true,
								   		'message' => 'The edit has been saved'));
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
 * @return void
 */
	public function delete() {
		$this->autorender = false;
		$this->layout = 'ajax';
		$conditions = array('JobWorkflowStepsUser.user_id = ' => $this->request->data['JobWorkflowStepsUser']['user_id'],
							'JobWorkflowStepsUser.job_workflow_step_id = ' => $this->request->data['JobWorkflowStepsUser']['job_workflow_step_id']);
		if ($this->JobWorkflowStepsUser->deleteAll($conditions, false, false)) {
			echo json_encode(array('success' => true,
								   	'message' => 'The edit has been saved'));
		} else {
			echo json_encode(array('success' => false,
								   	'message' => 'The edit has not been saved'));
		}
		exit;
	}
}