<?php echo $this->element('event_note', array('event' => $event)); ?>
<div class="link-survey">
<?php 
foreach ($data['questions'] as $question) {
	//for every question we display the element corresponding to the question type - for example a different element for
	//text based questions vs questions with multiple choices etc.
	//We pass three variables to the element for display, the question, the answer (if available) and the edit flag
	//Since this is a read-only view, we will only allow the user to read the answers, not edit
	$element = 'survey_element_' . $question['Question']['type']; 
	echo $this->element($element, array(
		'question' => $question['Question'],
		'answer' => $question['Answer'],
		'showAnswers' => true,
		'editAnswers' => false
	));
}
?>
</div>