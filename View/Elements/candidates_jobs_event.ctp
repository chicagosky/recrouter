<?php
$eventId = isEmpty($event['event_id']) ? -1 : $event['event_id'];
$jobWorkflowStepId = isEmpty($event['job_workflow_step_id']) ? -1 : $event['job_workflow_step_id'];

$badgeColor = '';
$timestamp = 'Not yet started'; //default value
$disabledFlag = '';
if ($event['modified'] !== '9999-12-31 00:00:00') {
	if ($this->Time->isFuture($event['completed'])) {
		$badgeColor = 'warning'; //yellow if event is in progress, green if event is complete , gray (default)
		
		//if event has been marked as complete - then show completed timestamp and mark complete button as disabled
		$timestamp = $this->Time->nice($event['modified']); 
	} else {
		$badgeColor = 'success';
		$timestamp = $this->Time->nice($event['completed']);
		$disabledFlag = 'disabled';
	}
}

//determine icon, element used for display based on workflow step type
$eventType = WorkflowStepType::$STEP_TYPES[$event['type_id']];
$elementPath = 'events' . DS . ($eventType['name']) . '_event_panel';
$eventIcon = $eventType['icon'];
$title = isEmpty($event['title']) ? $eventType['name'] : $event['title'];
?>
<li class="event <?php echo $elementDirection; ?>" data-event-id="<?php echo $eventId ?>" 
		data-job-workflow-step-id="<?php echo $jobWorkflowStepId; ?>" data-modified="<?php echo $event['modified']; ?>">
	<div class="timeline-badge <?php echo $badgeColor; ?>">
		<i class="<?php echo $eventIcon; ?>"></i>
	</div>
	<div class="timeline-panel">
	 	<div class="timeline-heading">
	 		<h4 class="timeline-title inline-panel-title"><?php echo $title; ?></h4>
	 		<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php echo $timestamp; ?></small></p>
        </div>
        <div class="ajax-message alert hidden"></div>
        <div class="timeline-body">
            <?php 
            //display the unique element associated with this event type - for example, email event type will display 
            //content differently from a questionnaire / assessment event type
            //the data passed to the element will vary based on the event type
            $data = array();
            if (WorkflowStepType::$STEP_TYPE_LINK == $event['type_id']) {
            	$data['questions'] = $questions; //for initial link events, the element will display job questionnaire and answers
            } elseif (WorkflowStepType::$STEP_TYPE_NOTIFY == $event['type_id']) {
            	//event id can be empty if the current event is only a workflow step template and not an actual event instance
            	//in such a case we should just bypass this. Also if there are no message corresponding to the event id- due to some
            	//error in processing
            	if (!isEmpty($event['event_id']) && isset($messages[$event['event_id']])) {
            		$data['message'] = $messages[$event['event_id']]['CandidatesJobsEmail'];
				} else {
					$data['message'] = array();
				}
            }
            echo $this->element($elementPath, array('event' => $event, 'data' => $data, 'disabledFlag' => $disabledFlag)); 
            ?>
        </div>
	</div>
</li>