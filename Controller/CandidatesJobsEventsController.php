<?php
App::uses('AppController', 'Controller');
App::uses('WorkflowStepType', 'Model');
/**
 * CandidatesJobsEventsController Controller
 *
 * @property CandidatesJobsEventsController $CandidatesJobsEvent
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class CandidatesJobsEventsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Session');

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
		$response = array();
		if ($this->request->is('post')) {
			$this->CandidatesJobsEvent->create();
			if ($this->CandidatesJobsEvent->save($this->request->data)) {
				$response = array('success' => true, 'message' => 'The event has been succesfully updated');
			} else {
				$response = array('success' => false, 'message' => 'An error occurred while saving the event. Please try again');
			}
		}
		echo json_encode($response);
		exit;
	}
	
/**
 * addEmailEvent method
 *
 * @return void
 */
	public function addEmailEvent() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$this->log($data);
			$from = isset($data['from']) ? $data['from'] : '';
			$to = isset($data['to']) ? $data['to'] : '';
			$cc = isset($data['cc']) ? $data['cc'] : '';
			$subject = isset($data['subject']) ? $data['subject'] : '';
			$message = isset($data['message']) ? $data['message'] : '';
			$links = $this->getLinks($to, $cc);
			$this->log($links);
			foreach($links as $link) {
				$this->loadModel('CandidatesJobEvent');
				$this->CandidatesJobsEvent->create();
				$this->CandidatesJobsEvent->set('candidates_job_id', $link);
				$this->CandidatesJobsEvent->set('event_type_id', WorkflowStepType::$STEP_TYPE_NOTIFY);
				$this->CandidatesJobsEvent->set('completed', date('Y-m-d H:i:s'));
				if ($this->CandidatesJobsEvent->save()) {
					$eventId = $this->CandidatesJobsEvent->getID();
					
					$this->CandidatesJobsEvent->CandidatesJobsEmail->create();
					$this->CandidatesJobsEvent->CandidatesJobsEmail->set('candidates_job_id', $link);
					$this->CandidatesJobsEvent->CandidatesJobsEmail->set('candidates_jobs_event_id', $eventId);
					$this->CandidatesJobsEvent->CandidatesJobsEmail->set('from', $from);
					$this->CandidatesJobsEvent->CandidatesJobsEmail->set('to', implode(',', $to));
					$this->CandidatesJobsEvent->CandidatesJobsEmail->set('cc', implode(',', $cc));
					$this->CandidatesJobsEvent->CandidatesJobsEmail->set('subject', $subject);
					$this->CandidatesJobsEvent->CandidatesJobsEmail->set('message', $message);
					if ($this->CandidatesJobsEvent->CandidatesJobsEmail->save()) {
						//success
					} else {
						$this->log('Created event but unable to store email for link:'.$link.'::eventId:'.$eventId.':from:'.$from.':to:'.implode(',', $to));
					}
				} else {
					$this->log('Unable to create event for link:'.$link);
				}
			}
		}
		exit;
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->request->is(array('ajax', 'post'))) return;
		$this->autorender = false;
		$this->layout = 'ajax';
		$response = array();
		if (!$this->CandidatesJobsEvent->exists($id)) {
			$this->CandidatesJobsEvent->create();
		} else {
			$this->CandidatesJobsEvent->set('id', $id);
		}
		if ($this->CandidatesJobsEvent->save($this->request->data)) {
			$response = array('success' => true, 'id' => $id, 'message' => 'The event has been succesfully updated');
		} else {
			$response = array('success' => false, 'id' => $id, 'message' => 'An error occurred while saving the event. Please try again');
		}
		echo json_encode($response);
		exit;
	}
	
	protected function getLinks($to=array(), $cc=array()) {
		$links = array();
		foreach($to as $email) {
			$username = substr($email, 0, strpos($email, '@'));
			$this->log($username);
			if (strpos($username, 'recrouter_link') === false) {
				$this->log('continuing because = '.$username);
				continue;
			}
			$linkstr = explode("recrouter_link_", $username);
			$this->log($linkstr);
			array_push($links, $linkstr[1]);
		}
		foreach($cc as $email) {
			$username = substr($email, 0, strpos($email, '@'));
			if (strpos($username, 'recrouter_link') == false) {
				continue;
			}
			$linkstr = explode("recrouter_link_", $username);
			array_push($links, $linkstr[1]);
		}
		return $links;
	}
}