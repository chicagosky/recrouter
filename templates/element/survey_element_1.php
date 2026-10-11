<!-- Text Question survey element -->
<div class="row row-top-buffer-md row-bottom-buffer-md bottom-border-dashed">
	<div class="col-md-1">
		<b <?php echo ('Y' == ($question['required'] ?? '')) ? 'style="color:red"' : ''; //mark required ?>>Q: </b>
	</div>
	<div class="col-md-11">
		<?php echo $question['name']; ?>
	</div>
	<?php 
	//below section is visible only if candidate is responding to survey or if user is viewing link event panel
	//this section is not visible under job view or add/edit survey view
	if ($showAnswers) { 
	?>
	<div class="col-md-1 row-top-buffer-sm">
		<b>A:</b>
	</div>
	<?php
		if ($showAnswers && $editAnswers) {
			//if the candidate is trying to respond to the survey then we show the input element as appropriate
	?>
	<div class="col-md-11 row-top-buffer-sm">
		<input type="text" class="form-control survey-response response-text" data-question-id="<?php echo $question['id']; ?>" />
	</div>
	<?php
		} elseif ($showAnswers && !$editAnswers) {
			//if the user is viewing link's event history then we just need to show an answer if the candidate has already responded
			//if the candidate has not responded - then we show a generic text
			if (isset($answer['answer_text']) && !isEmpty($answer['answer_text'])) {
	?>
	<div class="col-md-11 row-top-buffer-sm">
		<?php echo $answer['answer_text']; ?>
	</div>
	<?php
			} else {
	?>
	<div class="col-md-11 row-top-buffer-sm">Not answered yet</div>
	<?php
			}
		}
	}
	?>
</div>