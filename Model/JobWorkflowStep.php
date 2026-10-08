<?php
App::uses('AppModel', 'Model');
/**
 * JobWorkflowStep Model
 *
 * @property Job $Job
 * @property Workflow $Workflow
 * @property WorkflowStep $WorkflowStep
 * @property CandidatesJobsWorkflowEvent $CandidatesJobsWorkflowEvent
 * @property User $User
 */
class JobWorkflowStep extends AppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Job' => array(
			'className' => 'Job',
			'foreignKey' => 'job_id',
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
		'WorkflowStep' => array(
			'className' => 'WorkflowStep',
			'foreignKey' => 'workflow_step_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasOne associations
 * In theory, a workflow step can be associated with many events - but in the context of a link between job and candidate
 * there exists a 1-to-1 mapping between a workflow step and link. In other words, a workflow step takes place only once
 * in the context of a single association between candidate and job. The same workflow step can exist in several associations, however.
 *
 * @var array
 */
	public $hasOne = array(
		'CandidatesJobsEvent' => array(
			'className' => 'CandidatesJobsEvent',
			'foreignKey' => 'job_workflow_step_id',
			'dependent' => false,
			'conditions' => '',
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
		'User' => array(
			'className' => 'User',
			'joinTable' => 'job_workflow_steps_users',
			'foreignKey' => 'job_workflow_step_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
	
	public function copyWorkflowForJob($jobId=null, $workflowId=null) {
		$conn = $this->getDataSource()->getConnection();
		$stmt = $conn->prepare("INSERT INTO job_workflow_steps (job_id, workflow_id, workflow_step_id, sequence, created)
									SELECT :job_id, :workflow_id, id, sequence, NOW()
									FROM workflow_steps
									WHERE workflow_id = :workflow_id
									AND archived > NOW()");
		$stmt->bindParam(':job_id', $jobId, PDO::PARAM_INT);
		$stmt->bindParam(':workflow_id', $workflowId, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt = $conn->prepare("INSERT INTO job_workflow_steps_users (job_workflow_step_id, user_id)
									SELECT a.id, b.user_id
									FROM job_workflow_steps a, 
										 workflow_step_users b
									WHERE a.workflow_id = b.workflow_id
									AND a.workflow_step_id = b.workflow_step_id
									AND a.job_id = :job_id");
		$stmt->bindParam(':job_id', $jobId, PDO::PARAM_INT);
		$stmt->execute();
	}
	
	public function findStepsAndEventsForLink($linkId=null, $jobId=null) {
		if (!$linkId || !$jobId) {
			throw new NotFoundException(__('Invalid candidate or job'));
		}
		
		$joins = array(
			array(  
				'table' => 'workflow_steps',
				'alias' => 'WorkflowStep',
				'type' => 'left',
				'conditions' => array(
					'WorkflowStep.id = JobWorkflowStep.workflow_step_id'
				)
			),
			array(
				'table' => 'candidates_jobs_events',
				'alias' => 'CandidatesJobsEvent',
				'type' => 'left',
				'conditions' => array(
					'CandidatesJobsEvent.job_workflow_step_id = JobWorkflowStep.id',
					'CandidatesJobsEvent.candidates_job_id' => $linkId
				)
			)
		);
			
		$dbo = $this->getDataSource();
		
		$subQuery = $dbo->buildStatement(
			array(
				'fields' => array(
					'JobWorkflowStep.id AS job_workflow_step_id',
					'CandidatesJobsEvent.id AS event_id',
					'JobWorkflowStep.sequence',
					'WorkflowStep.name AS title',
					'WorkflowStep.workflow_step_type_id AS type_id',
					"IFNULL(CandidatesJobsEvent.notes, '') AS notes",
					"IFNULL(CandidatesJobsEvent.modified, '9999-12-31 00:00:00') AS modified",
					"IFNULL(CandidatesJobsEvent.completed, '9999-12-31 00:00:00') AS completed"
				),
				'table' => $dbo->fullTableName($this),
				'alias' => 'JobWorkflowStep',
				'limit' => null,
				'offset' => null,
				'joins' => $joins,
				'conditions' => array(
					'job_id' => $jobId
				),
				'order' => null,
				'group' => null
			),
			$this
		);
		
		$query = $subQuery . ' UNION ALL ';
		
		$subQuery = $dbo->buildStatement(
			array(
				'fields' => array(
					'CandidatesJobsEvent.job_workflow_step_id',
					'CandidatesJobsEvent.id AS event_id',
					'0 AS sequence',
					"'' AS title",
					'CandidatesJobsEvent.event_type_id AS type_id',
					'CandidatesJobsEvent.notes',
					'CandidatesJobsEvent.modified',
					'CandidatesJobsEvent.completed'
				),
				'table' => 'candidates_jobs_events',
				'alias' => 'CandidatesJobsEvent',
				'limit' => null,
				'offset' => null,
				'joins' => array(),
				'conditions' => array(
					'candidates_job_id' => $linkId,
					'(job_workflow_step_id IS NULL OR job_workflow_step_id = 0)'
				),
				'order' => null,
				'group' => null
			),
			$this->CandidatesJobsEvent
		);
				
		$query .= $subQuery . ' ORDER BY modified,sequence ';
		
		$this->log($query);
		$results = $this->query($query);
		
		return $results;
	}

}