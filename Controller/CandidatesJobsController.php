<?php
App::uses('AppController', 'Controller');
App::uses('WorkflowStepType', 'Model');
App::uses('Emailer', 'Lib');
App::uses('CPanelUtil', 'Lib');

/**
 * CandidatesJobs Controller
 *
 * @property CandidatesJob $CandidatesJob
 * @property PaginatorComponent $Paginator
 */
class CandidatesJobsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler');
	
	public $helpers = array('Js');
		
/*
 * beforeFilter method
 *
 * @return void
 */	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}
	
	public function index($id) {
		$this->viewClass = 'Tools.Ajax';
		$linkOptions = array();
		$listFor = 'job';
		if ('job' === $listFor) {
			$linkOptions['conditions'] = array('CandidatesJob.job_id' => $id, 'CandidatesJob.archived > NOW()');
			$linkOptions['fields'] = array('Candidate.id', 'Candidate.firstname', 'Candidate.lastname', 'CandidatesJob.created');
			$linkOptions['order'] = array('Candidate.firstname' => 'asc');
		} elseif ('candidate' === $listFor) {
			$linkOptions['conditions'] = array('CandidatesJob.candidate_id' => $id);
			$linkOptions['fields'] = array('Job.id', 'Job.title', 'CandidatesJob.created');
			$linkOptions['order'] = array('Job.title' => 'asc');
		}
		$linkOptions['limit'] = 5;
		$this->Paginator->settings = $linkOptions;
		$links = $this->Paginator->paginate('CandidatesJob');
		$this->set('links', $links);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CandidatesJob->exists($id)) {
			throw new NotFoundException(__('Invalid candidates job'));
		}
		
		//load all the details related to the link - Candidate, Job, JobWorkflow template, step icons
		$this->CandidatesJob->Behaviors->load('Containable');
		$options = array('conditions' => array('CandidatesJob.' . $this->CandidatesJob->primaryKey => $id));
		$options['contain'] = array(
			'Candidate.id', 'Candidate.firstname', 'Candidate.lastname',
			'Job.id', 'Job.title', 'Job.workflow_id'
		);
		$candidatesJob = $this->CandidatesJob->find('first', $options);
		$jobId = $candidatesJob['Job']['id'];
		$this->set('candidatesJob', $candidatesJob);
		
		//load all workflow steps associated with job
		$this->loadModel('JobWorkflowStep');
		$this->set('stepsAndEvents', $this->JobWorkflowStep->findStepsAndEventsForLink($id, $jobId));
		
		//load survey questions associated with the job - if any exist - these will be displayed along with answers available
		//on the link step of the workflow
		$this->loadModel('Survey');
		$this->Survey->Question->recursive = -1;
		$options = array(
			'fields' => array(
				'id', 'name', 'subtext', 'required', 'type', 'Answer.id', 'Answer.answer_text'
			)
		);
		$options['joins'] = array(
			array(
				'table' => 'surveys',
				'alias' => 'Survey',
				'type' => 'inner',
				'conditions' => array(
					'Question.survey_id = Survey.id',
					'Survey.job_id = '.$jobId
				)
			),
			array(
				'table' => 'answers',
				'alias' => 'Answer',
				'type' => 'left',
				'conditions' => array(
					'Answer.question_id = Question.id',
					'Answer.candidates_job_id' => $id
				)
			)
		);
		$questions = $this->Survey->Question->find('all', $options);
		$this->set('questions', $questions);
		
		//load relevant notification messages as well
		$this->loadModel('CandidatesJobsEmail');
		$this->CandidatesJobsEmail->recursive = -1;
		$options = array(
			'fields' => array(
				'id', 'candidates_jobs_event_id', 'from', 'to', 'cc', 'subject', 'message', 'modified'
			),
			'conditions' => array('candidates_job_id' => $id)
		);
		$messages = $this->CandidatesJobsEmail->find('all', $options);
		$this->set('messages', Hash::combine($messages, '{n}.CandidatesJobsEmail.candidates_jobs_event_id', '{n}'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($action = null, $id = null) {
		$this->CandidatesJob->id = $id;
		if (!$this->CandidatesJob->exists()) {
			throw new NotFoundException(__('Invalid candidates job'));
		}

		$this->CandidatesJob->set('id', $id);
		if (0 == strcasecmp('hire', $action)) {
			$this->CandidatesJob->set('hired', date('Y-m-d H:i:s'));
			if ($this->CandidatesJob->save()) {
				$this->CandidatesJob->CandidatesJobsEvent->create();
				$this->CandidatesJob->CandidatesJobsEvent->set('candidates_job_id', $id);
				$this->CandidatesJob->CandidatesJobsEvent->set('event_type_id', WorkflowStepType::$STEP_TYPE_HIRE);
				$this->CandidatesJob->CandidatesJobsEvent->set('completed', date('Y-m-d H:i:s'));
				if ($this->CandidatesJob->CandidatesJobsEvent->save()) {
					$this->Session->setFlash(__('The candidate has been successfully marked as hired'), 'flash_success');
				} else {
					$this->Session->setFlash(__('An error occurred while trying to hire the candidate. Please try again'), 'flash_failure');
				}
			} else {
				$this->Session->setFlash(__('An error occurred while trying to hire the candidate. Please try again'), 'flash_failure');
			}
		} elseif (0 == strcasecmp('reject', $action)) {
			$this->CandidatesJob->set('rejected', date('Y-m-d H:i:s'));
			if ($this->CandidatesJob->save()) {
				$this->CandidatesJob->CandidatesJobsEvent->create();
				$this->CandidatesJob->CandidatesJobsEvent->set('candidates_job_id', $id);
				$this->CandidatesJob->CandidatesJobsEvent->set('event_type_id', WorkflowStepType::$STEP_TYPE_REJECT);
				$this->CandidatesJob->CandidatesJobsEvent->set('completed', date('Y-m-d H:i:s'));
				if ($this->CandidatesJob->CandidatesJobsEvent->save()) {
					$this->Session->setFlash(__('The candidate has been successfully marked as rejected'), 'flash_success');
				} else {
					$this->Session->setFlash(__('An error occurred while trying to reject the candidate. Please try again'), 'flash_failure');
				}
			} else {
				$this->Session->setFlash(__('An error occurred while trying to reject the candidate. Please try again'), 'flash_failure');
			}
		} elseif (0 == strcasecmp('reopen', $action)) {
			$this->CandidatesJob->set('hired', '9999-12-31 00:00:00');
			$this->CandidatesJob->set('rejected', '9999-12-31 00:00:00');
			if ($this->CandidatesJob->save()) {
				$this->CandidatesJob->CandidatesJobsEvent->deleteAll(array(
					'CandidatesJobsEvent.candidates_job_id' => $id,
					'CandidatesJobsEvent.event_type_id' => WorkflowStepType::$STEP_TYPE_HIRE
				), false);
				$this->CandidatesJob->CandidatesJobsEvent->deleteAll(array(
					'CandidatesJobsEvent.candidates_job_id' => $id,
					'CandidatesJobsEvent.event_type_id' => WorkflowStepType::$STEP_TYPE_REJECT
				), false);
				$this->Session->setFlash(__('The link has been successfully reopened'), 'flash_success');
			} else {
				$this->Session->setFlash(__('An error occurred while trying to reopen the link. Please try again'), 'flash_failure');
			}
		}
		return $this->redirect(array('action' => 'view', $id));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CandidatesJob->id = $id;
		if (!$this->CandidatesJob->exists()) {
			throw new NotFoundException(__('Invalid candidates job'));
		}
		$jobId = $this->CandidatesJob->field('job_id');
		$this->CandidatesJob->set('archived', date('Y-m-d H:i:s')); 
		if ($this->CandidatesJob->save()) {
			$this->Session->setFlash(__('The link has been archived'), 'flash_success');
		} else {
			$this->Session->setFlash(__('The link could not be archived. Please, try again'), 'flash_failure');
		}
		return $this->redirect(array('controller' => 'jobs','action' => 'view', $jobId));
	}
	
/**
 * link method
 *
 * @return void
 */
	public function link($candidateId=null, $jobId=null) {
		$this->autoRender = false;
		$this->layout = 'ajax';
		$response = array();
		if ($this->request->is(array('post', 'get'))) {
			$link = $this->CandidatesJob->find('first', array(
				'conditions' => array(
					'CandidatesJob.candidate_id' => $candidateId,
					'CandidatesJob.job_id' => $jobId,
					'CandidatesJob.archived > NOW()'
				)
			));
			if (!isEmpty($link)) {
				$link['CandidatesJob']['archived'] = date('Y-m-d H:i:s');
				if ($this->CandidatesJob->save($link)) {
					$response = array('success' => true, 'message' => 'The link was successfully archived');
				} else {
					$response = array('success' => false, 'message' => 'An error occurred while trying to unlink. Please try again');
				}
				echo json_encode($response);
				exit;				
			}
			
			//find if there is a link workflow step id
			$this->loadModel('JobWorkflowStep');
			$this->JobWorkflowStep->Behaviors->load('Containable');
			$this->JobWorkflowStep->contain();
			$options = array();
			$options['fields'] = array('id', 'workflow_step_id');
			$options['conditions'] = array('job_id' => $jobId);
			$options['joins'] = array(
			    array('table' => 'workflow_steps',
			        'alias' => 'WorkflowStep',
			        'type' => 'inner',
			        'conditions' => array(
			            'WorkflowStep.workflow_step_type_id = '.WorkflowStepType::$STEP_TYPE_LINK,
			            'WorkflowStep.id = JobWorkflowStep.workflow_step_id'
			        )
			    )
			);
			$jobWorkflowStep = $this->JobWorkflowStep->find('first', $options);
			
			if (!isEmpty($jobWorkflowStep)) {
				$this->CandidatesJob->set('job_id', $jobId);
				$this->CandidatesJob->set('candidate_id', $candidateId);
				if ($this->CandidatesJob->save()) {
					if ($this->saveWorkflowEventForLink($this->CandidatesJob->getID(), $jobWorkflowStep['JobWorkflowStep']['id'], true)) {
						//create email forwarding address for link
						$result = CPanelUtil::createEmailForwarder('recrouter_link_'.$this->CandidatesJob->getID());
						if (isset($result['success']) && $result['success']) {
							//store forwarding email address in database
							$this->CandidatesJob->saveField('email', $result['email']);
							//send email to candidate with survey questions
							$this->sendSurveyToCandidateForJob(
								array(
									'candidateId' => $candidateId,
									'jobId' => $jobId,
									'linkId' => $this->CandidatesJob->getID(),
									'linkEmail' => $result['email']
								)
							);
							$response = array('success' => true, 'message' => 'The link has been successfully created and the survey has been sent to the candidate');
						} else {
							$response = array('success' => false, 'message' => 'The link has been created successfully but an error occurred while trying to send the survey to the candidate');
						}
					} else {
						//rollback the link since the workflow event was not created
						$this->CandidatesJob->deleteAll(array('conditions' => array('id' => $this->CandidatesJob->getID())));
						$response = array('success' => false, 'message' => 'An error occurred while trying to link. Please try again');
					}
				} else {
					$response = array('success' => false, 'message' => 'An error occurred while saving the link. Please try again');
				}
			} else {
				$response = array('success' => false, 'message' => 'Invalid or corrupt data. Please try again');
			}
			echo json_encode($response);
			exit;
		}
	}

	protected function saveWorkflowEventForLink($linkId=null, $jobWorkflowStepId=null, $markCompleted=false) {
		$this->loadModel('CandidatesJobsEvent');
		$this->CandidatesJobsEvent->create();
		$this->CandidatesJobsEvent->set('candidates_job_id', $linkId);
		$this->CandidatesJobsEvent->set('job_workflow_step_id', $jobWorkflowStepId);
		if ($markCompleted) {
			$this->CandidatesJobsEvent->set('completed', date('Y-m-d H:i:s'));
		}
		return $this->CandidatesJobsEvent->save();
	}
	
	protected function sendSurveyToCandidateForJob($data = array()) {
		//find survey questions attached to job
		$this->loadModel('Survey');
		$this->Survey->recursive = -1;
		$options = array('fields' => array('id', 'name'));
		$options['conditions'] = array('Survey.job_id' => $data['jobId']);
		$survey = $this->Survey->find('first', $options);
		if (!$survey) return; //if there is no survey attached to job - exit
		
		//load Job details 
		$this->loadModel('Job');
		$this->Job->recursive = -1;
		$options = array();
		$options['fields'] = array('id', 'title', 'city', 'state','country', 'description', 'skills');
		$options['conditions'] = array('Job.id' => $data['jobId']);
		$job = $this->Job->find('first', $options);		
		
		//load candidate details to find email id and name
		$this->loadModel('Candidate');
		$this->Candidate->recursive = -1;
		$options = array('fields' => array('id', 'firstname', 'lastname', 'email'));
		$options['conditions'] = array('Candidate.id' => $data['candidateId']);
		$candidate = $this->Candidate->find('first', $options);
		
		//send email
		//testing - replace to field with $candidate['Candidate']['email'] for actual production
		Emailer::sendEmail(array(
			'config' => 'registration',
			'template' => 'SurveyToCandidate',
			'to' => 'sriraj.rajaram@outlook.com',
			'vars' => array(
				'candidate' => $candidate['Candidate'],
				'job' => $job['Job'],
				'linkId' => $data['linkId'],
				'survey' => $survey
			)
		));
		
		return true;
	}
}