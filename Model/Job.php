<?php
App::uses('AppModel', 'Model');
/**
 * Job Model
 *
 * @property Survey $Survey
 * @property Department $Department
 * @property Priority $Priority
 * @property Workflow $Workflow
 * @property User $User
 * @property JobExtId $JobExtId
 * @property JobWorkflowStep $JobWorkflowStep
 * @property Candidate $Candidate
 * @property ContractModel $ContractModel
 * @property Tag $Tag
 */
class Job extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasOne associations
 *
 * @var array
 */
	public $hasOne = array(
		'Survey' => array(
			'className' => 'Survey',
			'foreignKey' => 'job_id',
			'conditions' => 'Survey.archived > NOW()',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'department_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Priority' => array(
			'className' => 'Priority',
			'foreignKey' => 'priority_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Workflow' => array(
			'className' => 'Workflow',
			'foreignKey' => 'workflow_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'JobExtId' => array(
			'className' => 'JobExtId',
			'foreignKey' => 'job_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'JobWorkflowStep' => array(
			'className' => 'JobWorkflowStep',
			'foreignKey' => 'job_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'CandidatesJob' => array(
			'className' => 'CandidatesJob',
			'foreignKey' => 'job_id',
			'dependent' => false,
			'conditions' => 'CandidatesJob.archived > NOW()',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'ContractModel' => array(
			'className' => 'ContractModel',
			'joinTable' => 'contract_models_jobs',
			'foreignKey' => 'job_id',
			'associationForeignKey' => 'contract_model_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Tag' => array(
			'className' => 'Tag',
			'joinTable' => 'jobs_tags',
			'foreignKey' => 'job_id',
			'associationForeignKey' => 'tag_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
	
	public function filter($filters) {
		$conn = $this->getDataSource()->getConnection();
		
		//prepare statement and bind parameters
		$stmt = $conn->prepare("CALL SEARCH_JOBS(:keywords)");
		$stmt->bindParam(':keywords', $filters['keywords'], PDO::PARAM_STR);
		$stmt->execute();
		
		$results = $stmt->fetchAll();
		return $results;
	}

}
