<?php
App::uses('AppController', 'Controller');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('Xml', 'Utility');
App::uses('RchilliParser', 'Lib');
App::uses('RecruiterPlusParser', 'Lib');
App::uses('SovrenParser', 'Lib');
App::uses('SearchUtil', 'Lib');
App::uses('UploadUtil', 'Lib');
App::import('Vendor', 'Fpdf', array('file' => 'fpdf/fpdf.php'));
App::import('Vendor', 'Parser', array('file' => 'hnp/Parser.php'));
App::import('Vendor', 'Name', array('file' => 'hnp/Name.php'));

/**
 * Candidates Controller
 *
 * @property Candidate $Candidate
 * @property PaginatorComponent $Paginator
 */
class CandidatesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler');
	public $helpers = array('Js');
	public $presetVars = array('keywords' => '', 'tags' => '', 'filter' => false);
	
	//public $helpers = array('fpdf');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->layout = 'search';
		$this->set('searchBarComponent', 'candidate_search_bar');
		$options = array();
		$conditions = array();
		if ($this -> request -> is('post')) {
			$this->presetVars = array_merge($this->presetVars, $this->request->data);
			if (!empty($this->request->data['keywords'])) {
				$filters = array('keywords' => SearchUtil::convertToQueryString($this->request->data['keywords'], '%', '%'));
				$filteredCandidateIds = $this->Candidate->filter($filters);
				$conditions['Candidate.id'] = array_map(function($row) { return $row['job_id']; }, $filteredCandidateIds);
			}
			
			if (!empty($this->request->data['tags'])) {
				$options['joins'] = array(
				    array('table' => 'candidates_tags',
				        'alias' => 'CandidatesTag',
				        'type' => 'inner',
				        'conditions' => array(
				            'CandidatesTag.tag_id IN ('.$this->request->data['tags'].')',
				            'CandidatesTag.job_id = Job.id'
				        )
				    )
				);
			}								

			if (0 < $this->request->data['filter']) {
				$conditions['Candidate.user_id'] = $this->request->data['filter'];
				$this->presetVars['filter'] = true;
			} elseif (0 == $this->request->data['filter']) {
				$this->loadModel('FavoriteCandidate');
				$options['joins'] = array(
				    array('table' => 'favorite_candidates',
				        'alias' => 'FavoriteCandidate',
				        'type' => 'inner',
				        'conditions' => array(
				            'FavoriteCandidate.user_id = '.$this->Auth->user('id'),
				            'FavoriteCandidate.candidate_id = Candidate.id'
				        )
				    )
				);
			}			
		}
		$options['fields'] = array('Candidate.id', 'Candidate.firstname', 'Candidate.lastname', 'Candidate.email', 'Candidate.city', 'Candidate.state', 
								   'Candidate.phone', 'Candidate.file_name', 'Candidate.user_id', 'Candidate.created',
								   'User.id', 'User.username', 'User.firstname', 'User.lastname');
		$conditions['Candidate.archived >'] = date('Y-m-d H:i:s');
		$options['conditions'] = $conditions;
		$this->Candidate->Behaviors->load('Containable');
		$this->Candidate->contain(array('CandidatesJob.id', 'User', 'Tag.id', 'Tag.name', 'ContractModel.id', 'ContractModel.name'));
		
		$options['limit'] = 15;
		$this->Paginator->settings = $options;
		$candidates = $this->Paginator->paginate('Candidate');
		$this->set('candidates', $candidates);
			
		$this->loadModel('FavoriteCandidate');
		$favorites = $this->FavoriteCandidate->find('list', array('fields' => array('FavoriteCandidate.candidate_id'),
														   		  'conditions' => array('FavoriteCandidate.user_id' => $this->Auth->user('id'))));
		$this->set(compact('favorites'));
		$this->set('presetVars', $this->presetVars);
	}

/**
 * listForLink method
 *
 * @return void
 */
	public function listForLink() {
		$this->request->onlyAllow('ajax');
		
		$this->viewClass = 'Tools.Ajax';
		$options = array();
		$options['fields'] = array('id', 'firstname', 'lastname', 'city', 'state', 'created', 'modified');
		$options['limit'] = 15;
		$this->Paginator->settings = $options;
		$this->Candidate->Behaviors->load('Containable');
		$this->Candidate->contain(
			array(
				'Tag.id', 'Tag.name', 'ContractModel.id', 'ContractModel.name'
			)
		);
		$candidates = $this->Paginator->paginate('Candidate');
		$this->set('candidates', $candidates);
		$this->render('links');
	}


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout = 'search';
		$this->set('searchBarComponent', 'candidate_search_bar');
		$this->set('presetVars', $this->presetVars);
		if (!$this->Candidate->exists($id)) {
			throw new NotFoundException(__('Invalid candidate'));
		}
		$options = array('conditions' => array('Candidate.' . $this->Candidate->primaryKey => $id));
		$this->set('candidate', $this->Candidate->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is(array('post', 'put'))) {
			$parser = new HumanNameParser_Parser($this->request->data['Candidate']['name']);
			$this->request->data['Candidate']['firstname'] = $parser->getFirst();
			$this->request->data['Candidate']['lastname'] = $parser->getLast();
			if (isset($this->request->data['ContractModel'])) {
				$this->request->data['ContractModel'] = array('ContractModel' => $this->request->data['ContractModel']);
			}
			$tags = explode(',', $this->request->data['Candidate']['tags']);
			$this->request->data['Tag'] = array('Tag' => $tags);
			$this->request->data['Candidate']['user_id'] = $this->Auth->user('id');
			$this->Candidate->create();
			if ($this->Candidate->saveAll($this->request->data)) {
				$id = $this->Candidate->getID();
				if (!empty($this->request->data['Candidate']['file_name'])) {
					//Check if folder exists - if not create it with permission 777
					//make ready the paths - current path in temporary folder and new path to candidate upload folder
					$f = new Folder(APP. 'Upload' . DS . 'candidate' . DS . $id, true, 0777); 
					$tmpPath = APP . 'Upload' . DS . 'tmp' . DS . $this->request->data['Candidate']['file_name'];
					$movePath = APP. 'Upload' . DS . 'candidate' . DS . $id . DS . $this->request->data['Candidate']['file_name'];

					//move the file from temporary upload folder to a permanent location
					//check if parsed xml file exists, if it does save in DB
					try {
						rename($tmpPath, $movePath);
						$xmlFile = new File($tmpPath.'.xml', false); 
						if ($xmlFile->exists()) {
							$hrxml = Xml::build($tmpPath.'.xml');
							$this->Candidate->saveField('xml', $hrxml->asXML());
						}
					} catch (Exception $e) {
						$this->log($e->getMessage());
						$this->Session->setFlash(__('An error occurred while moving the candidate file'), 'flash_failure');
						return $this->redirect(array('action' => 'view', $id));
					}
				}
				$this->Session->setFlash(__('The candidate has been created'), 'flash_success');
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('An error occurred while saving the candidate. Please, try again.'), 'flash_failure');
			}
		}
		$contractModels = $this->Candidate->ContractModel->find('list');
		$tags = $this->Candidate->Tag->find('list');
		$this->set(compact('contractModels', 'tags'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null, $newUpload=null) {
		if (!$this->Candidate->exists($id)) {
			throw new NotFoundException(__('Invalid candidate'));
		}
		
		if ($this->request->is(array('post', 'put'))) {
			$parser = new HumanNameParser_Parser($this->request->data['Candidate']['name']);
			$this->request->data['Candidate']['firstname'] = $parser->getFirst();
			$this->request->data['Candidate']['lastname'] = $parser->getLast().' '.$parser->getSuffix();
			if (isset($this->request->data['ContractModel'])) {
				$this->request->data['ContractModel'] = array('ContractModel' => $this->request->data['ContractModel']);
			}
			$tags = explode(',', $this->request->data['Candidate']['tags']);
			$this->request->data['Tag'] = array('Tag' => $tags);
			$this->request->data['Candidate']['id'] = $id;
			if ($this->Candidate->saveAll($this->request->data)) {			
				// if the upload flag has been set move the uplaoded file and then also move the parsed XML file
				// finally save the XML file in the database
				if ('Y' === $newUpload) {
					//Check if folder exists - if not create it with permission 777
					//make ready the paths - current path in temporary folder and new path to candidate upload folder
					$f = new Folder(APP. 'Upload' . DS . 'candidate' . DS . $id, true, 0777); 
					$tmpPath = APP . 'Upload' . DS . 'tmp' . DS . $this->request->data['Candidate']['file_name'];
					$movePath = APP. 'Upload' . DS . 'candidate' . DS . $id . DS . $this->request->data['Candidate']['file_name'];
					
					try {
						rename($tmpPath, $movePath); //move the file
						$xmlFile = new File($tmpPath.'.xml', false); //check if parsed xml file exists, if it does save in DB
						if ($xmlFile->exists()) {
							$hrxml = Xml::build($tmpPath.'.xml');
							$this->Candidate->saveField('xml', $hrxml->asXML());
						}
					} catch (Exception $e) {
						$this->log($e->getMessage());
						$this->Session->setFlash(__('An error occurred while moving the uploaded file. Please, try again'), 'flash_failure');
						return $this->redirect(array('action' => 'view', $id));
					}
				}				
				$this->Session->setFlash(__('The changes has been saved'), 'flash_success');
			} else {
				$this->Session->setFlash(__('An error occurred while saving the changes. Please, try again'), 'flash_failure');
			}
			return $this->redirect(array('action' => 'view', $id));
		}
		
		$options = array('conditions' => array('Candidate.' . $this->Candidate->primaryKey => $id));
		$this->request->data = $this->Candidate->find('first', $options);
		$contractModels = $this->Candidate->ContractModel->find('list', array('fields' => array('ContractModel.id', 'ContractModel.name')));
		$tags = $this->Candidate->Tag->find('list');
		$this->set(compact('contractModels', 'states', 'tags'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Candidate->id = $id;
		if (!$this->Candidate->exists()) {
			throw new NotFoundException(__('Invalid candidate'));
		}
		$this->Candidate->set('archived', date('Y-m-d H:i:s')); 
		if ($this->Candidate->save()) {
			$this->Session->setFlash(__('The candidate has been archived'), 'flash_success');
		} else {
			$this->Session->setFlash(__('The candidate could not be archived. Please, try again'), 'flash_failure');
		}
		return $this->redirect(array('action' => 'index'));
	}
	
/**
 * getFileFor method
 *
 * @param string $id
 * @param string $name
 */
	public function getFileFor($id=null, $name=null) {
		if ($name) {
			//if id is -1 then this is for a newly uploaded document - hence it will search in tmp folder
			//if id is greater than 0 then this is an existing uploaded document - so the search will be in candidates folder
			$fullPath = ($id == -1) ? 'Upload/tmp/'.$name : 'Upload/candidate/'.$id.'/'.$name;
			$file = new File(APP.$fullPath, false);
			if ($file->exists()) {
				$this->response->file($fullPath);
				return $this->response;
			} 
		}
		//generate blank PDF
    	$fpdf = new FPDF('P','mm','A4');
	    $fpdf->AddPage();
	    $fpdf->SetFont('Arial','B',16);
	    $fpdf->Cell(40,10,'No File found');
		$this->response->type('application/pdf');
    	$this->response->body($fpdf->Output('doc.pdf', 'I'));
	    return $this->response;		
	}

/**
 * upload method
 *
 * @param string $bulk Bulk upload indicator
 */
	public function upload($bulk=false) {
		$this->autorender = false;
		if ($this->request->is(array('post', 'put'))) {
			ignore_user_abort(true);
			set_time_limit(90);
			$status = array();
			$files = $this->request->data['files'];
			$util = new UploadUtil();
			for ($i=0; $i<sizeof($files); $i++) {
				$file = $files[$i];
				
				//Validate the file name, size and extension and move it to the TMP folder
				$response = $util->uploadFile($file, true, (bool) $bulk);
				
				$extension = substr($response['filename'], strrpos($response['filename'], '.')+1); //file extension
				
				//if the file is a zip archive then it will have multiple files and upload responses - check each of them
				if ($response['success'] && (0 == strcasecmp('zip', $extension))) {
					//process the zip archive first
					$response = $util->processZipArchive($response['path']);
					foreach($response as $row) {
						//the response returned for zip file is an array of responses by file
						array_push($status, $row);
					}
					continue; //move on to the next file 
				}
				
				// if this is not a bulk upload but rather an edit / add then set ajax layout for function
				if (!$bulk) {
					$this->layout = 'ajax';
					
					// if the upload is not a bulk upload then also parse the file, if it is a bulk upload then
					// parsing doesnt happen until user selects files and clicks process - triggering bulkAdd method
					 
					// For testing purposes make the following changes as necessary
					// 1. To use RChilli - $parser = new RchilliParser()
					// 2. To use Sovren - $parser = new SovrenParser()
					// 3. To use RecruitPlus - $parser = new RecruiterPlusParser()
					 
					// To use a local XML file instead of online credit - 
					// 1. Comment the line - $hrxml = $parser->parse($response['path']);
					// 2. Insert the line - $hrxml = Xml::build('xml-filename.xml')
					 
					if ($response['success']) {
						$parser = new SovrenParser();
						$hrxml = $parser->parse($response['path']);
						
						// in the case of non-bulk uploads we have to send in the response array as well 
						// since the javascript populates form action and other attributes based on upload response
						$response = $parser->getCandidateFromParsedXml($hrxml, $response);
						/*if ($response['parseSuccess']) {
							if (!$parser->saveXml($hrxml, $response['path'].'.xml')) {
								$response['errors']['saveAndConvertToArray'] = 'Unable to save parsed XML string - '.$response['path'].'.xml';
							}
						}*/
						
					}
					//output the response array in json encoded format and exit
					$this->log(json_encode($response));
					echo json_encode($response); 
					exit;
				}
				
				//if this is a bulk upload and the file being examined is not a zip file then push the status of
				//parseing the file into the status array and continue onto the next file
				array_push($status, $response);
			}
			$this->set('status', $status);
		}
		$this->render('bulk_upload'); //if the bulk flag is set then function will render appropriate view
	}

/**
 * bulkAdd method
 *
 * @return void
 */
	public function bulkAdd() {
		$this->autorender = false;
		$this->layout = 'ajax';
		$util = new UploadUtil();
		// bulk upload is triggered after the user clicks process on the bulk upload screen, that is after the files have
		// been uploaded and moved into the respective folders, converted to PDF but not yet parsed or stored in the database
		if ($this->request->is(array('post', 'put'))) {
			$status = array();
			
			// get the array of selected file names that will be processed
			$filepaths = $this->request->data['files'];
			for ($i=0; $i<sizeof($filepaths); $i++) {
				$filepath = $filepaths[$i];
				$file = new File($filepath, false);
				
				// if the specified file is not found in the location then log error 
				// and continue onto next file
				if (!$file->exists()) { 
					continue;
				}
				// first step is to convert file to PDF if it isnt PDF already
				$conversion = $util->convertToPDF($filepath);
				if ($conversion['success']) {
					$filepath = $conversion['path'];
				}
				
				// second step is to parse the file and extract all the candidate information so that we can
				// store the candidate in the database and generate an ID for further processing
				
				// For testing purposes make the following changes as necessary
				// 1. To use RChilli - $parser = new RchilliParser()
				// 2. To use Sovren - $parser = new SovrenParser()
				// 3. To use RecruitPlus - $parser = new RecruiterPlusParser()
				 
				// To use a local XML file instead of online credit - 
				// 1. Comment the line - $hrxml = $parser->parse($response['path']);
				// 2. Insert the line - $hrxml = Xml::build('xml-filename.xml')
				$parser = new SovrenParser();
				$hrxml = $parser->parse($filepath);
				$response = $parser->getCandidateFromParsedXml($hrxml);
				
				// check if parse was successfull, if it was successful then save all the elements in the database
				if ($response['parseSuccess']) {
					$this->Candidate->create();
					$this->Candidate->set('firstname', $response['firstname']);
					$this->Candidate->set('lastname', $response['lastname']);
					$this->Candidate->set('email', $response['email']);
					$this->Candidate->set('city', $response['city']);
					$this->Candidate->set('state', $response['state']);
					$this->Candidate->set('country', $response['country']);
					$this->Candidate->set('phone', $response['phone']);
					$this->Candidate->set('phone_alt', $response['altPhone']);
					$this->Candidate->set('user_id', 0);
					
					$simpleXml = Xml::build($hrxml);
					$this->Candidate->set('xml', $simpleXml->asXML());
					if ($this->Candidate->save()) {
						
						// once the candidate has been saved, get the generated id and move the files into 
						// the appropriate folder
						$id = $this->Candidate->getID();
						$f = new Folder(APP. 'Upload' . DS . 'candidate' . DS . $id, true, 0777); 
						$movePath = APP. 'Upload' . DS . 'candidate' . DS . $id . DS . basename($filepath);
						
						// try moving the file from temp upload folder to candidate folder, if an error occurs then 
						// log error and continue onto next file
						if (!rename($filepath, $movePath)) {
							$response['errors']['moveFile'] = 'An error occurred while moving the uploaded file - '.basename($filepath);
						} else {
							$this->Candidate->saveField('file_name', basename($filepath));
						}
					} else {
						$response['errors']['saveCandidate'] = 'An error occurred while saving the uploaded file - '.basename($filepath);
					}			
				}
				
				array_push($status, $response);
			}
			
			//future - send email with status array
		}
		exit;
	}
	
/**
 * removeFileFor method
 *
 * @param string $id Candidate identifier
 */
	public function removeFileFor($id=null) {
		if ($id) {
			//find the folder associated with the candidate id and then iterate through all the files
			//within that folder - renaming them to archive with a timestamp
			$fullPath = 'Upload' . DS . 'candidate' . DS . $id;
			$folder = new Folder(APP.$fullPath, false);
			$files = $folder->find('.*\.*');
			foreach($files as $file) {
				$file = new File($folder->pwd() . DS . $file);
				$originalFileName = APP . $fullPath . DS . $file->name;
				$newFileName = APP . $fullPath . DS . $file->name() . '_archive_' . time() . '.' . $file->ext();
				rename($originalFileName, $newFileName);
			}
			//finally empty the file_name attribute in the database to indicate no active files are associated
			$this->Candidate->id = $id;
			$this->Candidate->saveField('file_name', null);
		}
		return $this->redirect(array('action' => 'view', $id));	
	}
}