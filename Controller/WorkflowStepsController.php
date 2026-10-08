<?php
App::uses('AppController', 'Controller');
/**
 * WorkflowSteps Controller
 *
 * @property WorkflowStep $WorkflowStep
 * @property PaginatorComponent $Paginator
 */
class WorkflowStepsController extends AppController {

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
	public function index($workflowId=null) {
		$this->autorender = false;
		$this->layout = 'ajax';
		$this->WorkflowStep->Behaviors->load('Containable');
		$this->WorkflowStep->contain(array('WorkflowStepUser.id' => 
																array('User.id', 'User.username', 'User.firstname', 'User.lastname')));
		$options = array('conditions' => array('WorkflowStep.workflow_id' => $workflowId, 'WorkflowStep.archived > NOW()'));
		$options['fields'] = array('WorkflowStep.id', 'WorkflowStep.name', 'WorkflowStep.sequence', 'WorkflowStep.modified');
		$options['order'] = array('WorkflowStep.sequence ASC');
		echo json_encode($this->WorkflowStep->find('all', $options));
		exit;
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$newSequence = $this->request->data['WorkflowStep']['sequence'];
			$this->WorkflowStep->unbindAll();
			$this->WorkflowStep->updateAll(array('sequence' => 'sequence + 1'),
											array('sequence >= ' => $newSequence,
											   	  'workflow_id = ' => $this->request->data['WorkflowStep']['workflow_id']));
			$this->WorkflowStep->create();
			if ($this->WorkflowStep->save($this->request->data)) {
				$this->Session->setFlash(__('The workflow step has been saved'), 'flash_success');
			} else {
				$this->Session->setFlash(__('The workflow step could not be saved. Please, try again.'), 'flash_failure');
			}
		}
		return $this->redirect(array('controller' => 'workflows', 'action' => 'index'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->autorender = false;
		$this->layout = 'ajax';
		if (!$this->WorkflowStep->exists($id)) {
			throw new NotFoundException(__('Invalid workflow step'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->WorkflowStep->set('id', $id);
			$this->WorkflowStep->set('name', $this->request->data['value']);
			$this->WorkflowStep->set('modified', $this->request->data['modified']);
			if (empty($this->request->data['value'])) {
				return $this->redirect(array('action' => 'delete', $id));
			}
			if ($this->WorkflowStep->save()) {
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
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->WorkflowStep->id = $id;
		if (!$this->WorkflowStep->exists()) {
			throw new NotFoundException(__('Invalid workflow step'));
		}
		//$this->request->allowMethod('post', 'delete');
		$this->WorkflowStep->set('archived', date('Y-m-d H:i:s'));
		if ($this->WorkflowStep->save()) {
			$this->Session->setFlash(__('The edits have been saved'), 'flash_success');
		} else {
			$this->Session->setFlash(__('An error occurred while saving the edit. Please try again'), 'flash_failure');
		}
		return $this->redirect(array('controller' => 'workflows', 'action' => 'index'));
	}

/**
 * reorder method
 *
 * @throws NotFoundException
 * @param string $workflowId
 * @return void
 */	
	public function reorder($workflowId = null) {
		$this->autorender = false;
		$this->layout = 'ajax';
		if (!$this->WorkflowStep->Workflow->exists($workflowId)) { //check if workflow exists
			throw new NotFoundException(__('Invalid workflow'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->WorkflowStep->Behaviors->load('Containable');
			$this->WorkflowStep->contain();
			//original sequence of steps
			$steps = $this->WorkflowStep->findAllByWorkflowId($workflowId, 
												array('id'), 
												array('WorkflowStep.sequence' => 'ASC'));
			$newSequence = explode(',', $this->request->data['reorderSeq']); //new sequence
			for ($i=0; $i<sizeof($newSequence); $i++) {
				$step = $steps[$i];
				if ($newSequence[$i] != $step['WorkflowStep']['id']) { //mismatch in sequence
					//update sequence where step id in DB is same as step id in new sequence array
					$this->WorkflowStep->updateAll(array('WorkflowStep.sequence' => $i),
													array('WorkflowStep.id = ' => $newSequence[$i]));
				}
			}
			echo json_encode(array('success' => true,
								   'message' => 'The workflow steps have been re-ordered'));
		}
		exit; //exit to prevent missing view error
	}
}