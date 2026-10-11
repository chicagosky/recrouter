<?php
//Bootstrap Javascript
$this -> Html -> script('bootstrap.min', array('inline' => false));

//Bootstrap Validator Javascript 
$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom'));

//custom Javascript
$this->Html->script('survey.recrouter', array('inline' => false, 'block' => 'scriptBottom'));
?>
<div class="row row-top-buffer-lg"> <!-- begin survey response page -->
	<div class="col-md-offset-2 col-md-8 col-md-offset-2 col-xs-12">
		<!-- Begin Job details panel -->
		<?php echo $this->Flash->render(); ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title"><?php echo $survey['Job']['title'] ?? ''; ?></h3>&nbsp;&nbsp;
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body view-job-details">
				<div class="row row-top-buffer-lg bottom-border-dashed location">
					<label class="col-md-3">Location</label>
					<div class="col-md-9"> <?php echo ($survey['Job']['city'] ?? '').', '.($survey['Job']['state'] ?? ''); ?> </div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg">
					<label class="col-md-3">Responsibilities</label>
				</div>
				<div class="row  row-bottom-buffer-lg bottom-border-dashed">
					<div class="col-md-12"><?php echo $survey['Job']['description'] ?? ''; ?></div>
				</div>
				<div class="row row-top-buffer-lg">
					<label class="col-md-3">Skills</label>
				</div>
				<div class="row row-bottom-buffer-lg">
					<div class="col-md-12"><?php echo $survey['Job']['skills'] ?? ''; ?></div>
				</div>
			</div> <!-- .panel-body -->
		</div> <!-- .panel .panel-default -->
		<!-- End of Job details panel -->
		<!-- Begin Job Survey panel -->
		<?php if (!$responseComplete) { ?>
		<form class="form-horizontal" role="form" id="survey-response-form" method="post" 
				action="<?php echo $this->request->getAttribute('webroot').'surveys/response/'.$survey['Survey']['id'].'/'.$linkId; ?>"
				data-link-id="<?php echo $linkId; ?>">
			<input type="hidden" name="responses" id="survey-response" />
			<div class="panel panel-default">
				<div class="panel-heading">
					<div> <!-- row to ensure panel heading height adjusts -->
						<h3 class="panel-title inline-panel-title bold-panel-title">Survey</h3>
					</div> <!-- .row -->
				</div> <!-- .panel-heading -->
				<div class="panel-body job-survey">
					<div class="row-top-buffer-md row-bottom-buffer-md">
						<?php echo $survey['Survey']['instructions'] ?? ''; ?>
					</div>
					<?php
					foreach ($survey['Question'] ?? $survey['Questions'] ?? [] as $question) {
						$template = 'survey_element_' . $question['type'];
						//since we are collecting responses from the candidate - we will show the answers and also allow
						//candidate to edit answers
						$options = array('showAnswers' => true, 'editAnswers' => true, 'question' => $question);
						echo $this->element($template, $options); 
					}
					?>
				</div> <!-- .panel-body -->
				<div class="panel-footer text-right">
					<button type="button" id="cancel-btn" class="btn btn-xs btn-default">Decline</button>
					<button type="button" id="save-btn" class="btn btn-xs btn-primary">Respond</button>
				</div> <!-- .panel-footer -->
			</div> <!-- .panel .panel-default -->
		</form>
		<?php } ?>
	</div> <!-- .col-md-offset-2 .col-md-8 .col-md-offset-2 -->
</div> <!-- .row --> 
<!-- end of collectResponse survey page -->
<script type="text/javascript">
	var view='response';
</script>