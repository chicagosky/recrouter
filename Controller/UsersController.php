<?php
App::uses('AppController', 'Controller');
App::uses('Emailer', 'Lib');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

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
		$this->layout = "dashboard";
		$this->set('users', $this->User->find('all', 
												array('conditions' => array('User.archived >' => date('Y-m-d H:i:s')))));
		$this->set('viewTitle', 'Users');
		$this->set('addButtonTitle', 'Invite Users');
		$this->set('addButtonLink', Router::url('/') . 'users/invite');
		$groups = $this->User->Group->find('list', array(
													'fields' => array('Group.id', 'Group.name')));
		$this->set('groups', $groups);
		//$this->set('users', $this->Paginator->paginate());
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
		$this->set('viewTitle', 'My Info');
		if (null == $id) {
			return;
		}
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		} 
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($invite = false) {
		if ($invite) { //turn off view-autorendering for ajax
			$this->autoRender = false;
		}
		if ($this->request->is('post')) {
			$this->User->create();
			$this->User->set('is_verified', 'N'); //future - replace hardcoding		
			$verificationCode = md5(uniqid(rand(), true));
			$this->User->set('verify_code', $verificationCode);
			$this->User->set('group_id', 1); //future - replace hardcoding
			if (!$invite) {
				$this->User->set('hint', $this->request->data['User']['hint']);
			}
			
			if ($this->User->save($this->request->data)) {
				$userId = $this->User->getID();
				$baseUrl = ($invite) ? '/users/invite/' : '/users/verify/'; //path based on inbound action
				$verifyLink = Router::url($baseUrl.$userId.'/'.$verificationCode, true);
				Emailer::sendEmail(array('config' => 'registration',
											'to' => $this->request->data['User']['username'],
											'template' => ($invite ? 'VerifyInvite' : 'VerifyRegistration'),
											'vars' => array('firstname' => $this->request->data['User']['firstname'],
															'lastname' => $this->request->data['User']['lastname'],
															'verifyLink' => $verifyLink,
															'fromUsername' => ($invite ? $this->request->data['from_username']:''))));
				if ($invite) { 
					$this->Session->setFlash(__('An invitation has been sent to the user'), 'flash_success');
					return $this->redirect(array('action' => 'index'));
				} else { 
					$this->Session->setFlash(__('The user has been saved'));
					return $this->redirect(array('action' => 'login'));
				}
				
			} else { //error in adding user
				if ($invite) {
					$this->Session->setFlash(__('An error occurred while trying to invite the user. Please try again'), 'flash_failure');
					return $this->redirect(array('action' => 'login'));
				} else { 
					$this->Session->setFlash(__('There was an error creating the user account. Please try again'));
					return $this->redirect(array('action' => 'add'));
				}
			}
		}
	}

/**
 * invite method
 *
 * @throws NotFoundException
 * @return void
 */
	public function invite() {
		$this->layout = "dashboard";
		$this->set('viewTitle', 'Users');
		$groups = $this->User->Group->find('list', array(
													'fields' => array('Group.id', 'Group.name')));
		$this->set('groups', $groups);
	}

/**
 * verify method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function verify($userId, $code) {
		$this->User->id = $userId;
		if (0 == strcasecmp($code, $this->User->field('verify_code'))) {
			$this->User->saveField('is_verified', 'Y');
			$this->Session->setFlash(__('Your verification is complete and your account is now active'), 'flash_success');
			return $this->redirect(array('action' => 'login'));
		} else {
			//redirect to error page
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The changes have been saved'), 'flash_success');
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('An error occurred while trying to save the changes. Please try again'), 'flash_failure');
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->User->set('archived', date('Y-m-d H:i:s')); //set current datetime
		if ($this->User->save()) {
			$this->Session->setFlash(__('The user has been deleted'), 'flash_success');
		} else {
			$this->Session->setFlash(__('An error occurred while deleting the user. Please try again'), 'flash_failure');
		}
		return $this->redirect(array('action' => 'index'));
	}
	
/**
 * forgot method
 *
 * @throws NotFoundException
 * @return void
 */	
	public function forgot() {
		if ( $this->request->is('post') ) {
			$user = $this->User->findByUsername($this->request->data['username']);
			if (!$user) { //if user record is not found
				$this->Session->setFlash(__('Username not found'));
				$this->redirect($this->Auth->loginRedirect);
			}
			
			$resetCode = md5(uniqid(rand(), true));
			$user['User']['verify_code'] = $resetCode;
			$this->User->save($user);
			
			$resetLink = Router::url('/users/reset/'.$user['User']['id'].'/'.$resetCode, true);
			$this->Session->setFlash(__('Your password has been reset and an email has been sent.'));
			Emailer::sendEmail(array('config' => 'registration',
											'template' => 'ResetPassword',
											'to' => $this->request->data['username'],
											'vars' => array('firstname' => $user['User']['firstname'],
															'lastname' => $user['User']['lastname'],
															'resetLink' => $resetLink)));
			return $this->redirect(array('action' => 'login'));
		}
	}

/**
 * reset method
 *
 * @throws NotFoundException
 * @param string $id
 * @param string $code
 * @return void
 */	
	public function reset($userId, $code = null) {
		$this->User->id = $userId;
		
		if ( $this->request->is('post') ) {
			$this->User->set('is_verified', 'Y');
			$this->User->save($this->request->data);
			$this->Session->setFlash(__('Your password has been reset successfully. Please login with your new password'));
			$this->redirect($this->Auth->loginRedirect);
		} else {
			if (0 == strcasecmp($code, $this->User->field('verify_code'))) { //display reset page if verification code is valid
				$this->set('userId', $userId);
				$this->set('username', $this->User->field('username'));
			} else {
				//redirect to error page
			}
		}
	}

/**
 * login method
 *
 * @return void
 */		
	public function login() {
		if ($this->Session->read('Auth.User')) {
			return $this->redirect($this->Auth->redirect()); //takes to page being accessed previously
		}
		
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->loginRedirect);
			}
			$this->Auth->flash(__('The username or password you entered was incorrect. Please try again'));
		}
	}
	
/**
 * beforeFilter method
 *
 * @return void
 */	
	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Auth->allow('add', 'doesExist','verify', 'forgot', 'reset', 'logout', 'index', 'invite');
		$this->Auth->allow();
	}

/**
 * logout method
 *
 * @return void
 */
	public function logout() {
		$this->Auth->flash(__('You have been successfully logged out'));
		$this->redirect($this->Auth->logout());
	}

/*
 *-----------------------
 * SECTION: AJAX METHODS
 * ---------------------- 
 */

/**
 * doesExist method
 *
 * @throws NotFoundException
 * @return void
 */	
	public function doesExist() {
		$this->autoRender = false; 
		$isAvailable = true;
		if ($_POST) {
			$username = $this->request->data['username'];
			//find if user exists in database
			$user = $this->User->findByUsername($username);
			if ($user) {
				$isAvailable = false;
			}
		}
		//output response as JSON array
		echo json_encode(array('valid' => $isAvailable));
	}
	
/**
 * toggleFavoriteJob method
 *
 * @return void
 */
	public function toggleFavoriteJob($jobId) {
		$this->autoRender = false;
		$this->layout = 'ajax';
		if ($this->request->is('post')) {
			$favorite = $this->User->FavoriteJob->findByUserIdAndJobId($this->Auth->user('id'), $jobId);
			if ($favorite) {
				$this->User->FavoriteJob->delete($favorite['FavoriteJob']['id']);	
				echo json_encode(array('success' => true));
				return;				
			}
			$this->User->FavoriteJob->set('job_id', $jobId);
			$this->User->FavoriteJob->set('user_id', $this->Auth->user('id'));
			if ($this->User->FavoriteJob->save()) {
				echo json_encode(array('success' => true));
			} else {
				echo json_encode(array('success' => false));
			}
		}
	}
	
/**
 * toggleFavoriteCandidate method
 *
 * @return void
 */
	public function toggleFavoriteCandidate($candidateId) {
		$this->autoRender = false;
		$this->layout = 'ajax';
		if ($this->request->is('post')) {
			$favorite = $this->User->FavoriteCandidate->findByUserIdAndCandidateId($this->Auth->user('id'), $candidateId);
			if ($favorite) {
				$this->User->FavoriteCandidate->delete($favorite['FavoriteCandidate']['id']);	
				echo json_encode(array('success' => true));
				return;				
			}
			$this->User->FavoriteCandidate->set('candidate_id', $candidateId);
			$this->User->FavoriteCandidate->set('user_id', $this->Auth->user('id'));
			if ($this->User->FavoriteCandidate->save()) {
				echo json_encode(array('success' => true));
			} else {
				echo json_encode(array('success' => false));
			}
		}
		exit;
	}
	
/**
 * listOfUsers method
 * 
 * @return void
 */
	public function listOfUsers() {
		$this->autorender = false;
		$this->layout = 'ajax';
		$users = $this->User->find('all', array('fields' => array('User.id AS id', 'User.firstname AS firstname', 'User.lastname AS lastname'),
										        'recursive' => -1
										    ));
		//use array_map to retrieve individual rows within nested associative array
		echo json_encode(array_map(function($row) { return $row['User']; }, $users));
		exit;
	}

/*	
 * Installation method - used only during inital application setup
	public function initDB() {
		$group = $this->User->Group;

		// Allow admins to everything
		$group->id = 1;
		$this->Acl->allow($group, 'controllers');

		// allow managers to all controllers except admin module and limited actions on the users controller
		$group->id = 2;
		$this->Acl->deny($group, 'controllers');
		$this->Acl->allow($group, 'controllers/Candidates');
		$this->Acl->allow($group, 'controllers/CandidatesJobs');
		$this->Acl->allow($group, 'controllers/CandidatesTags');
		$this->Acl->allow($group, 'controllers/Jobs');
		$this->Acl->allow($group, 'controllers/JobWorkflowStepsUsers');
		$this->Acl->allow($group, 'controllers/Surveys');
		$this->Acl->allow($group, 'controllers/Tags');
		$this->Acl->allow($group, 'controllers/Workflows');
		$this->Acl->allow($group, 'controllers/WorkflowSteps');
		$this->Acl->allow($group, 'controllers/WorkflowStepTypes');
		$this->Acl->allow($group, 'controllers/WorkflowStepUsers');
		$this->Acl->allow($group, 'controllers/users/index');
		$this->Acl->allow($group, 'controllers/users/invite');
		$this->Acl->allow($group, 'controllers/users/view');
		
		$this->Acl->allow($group, 'controllers/users/logout');
		

		// allow users to only add and edit on posts and widgets
		$group->id = 3;
		$this->Acl->deny($group, 'controllers');
		$this->Acl->allow($group, 'controllers/Candidates/add');
		$this->Acl->allow($group, 'controllers/Candidates/edit');
		$this->Acl->allow($group, 'controllers/Candidates/view');
		$this->Acl->allow($group, 'controllers/Jobs/add');
		$this->Acl->allow($group, 'controllers/Jobs/edit');
		$this->Acl->allow($group, 'controllers/Jobs/view');

		// allow basic users to view their profile and log out
		$this->Acl->allow($group, 'controllers/users/view');
		$this->Acl->allow($group, 'controllers/users/logout');

		// we add an exit to avoid an ugly "missing views" error message
		echo "all done";
		exit;
	}
*/
}