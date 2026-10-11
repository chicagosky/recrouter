<?php
//Bootstrap Multiselect CSS
$this->Html->css('bootstrap-multiselect', array('inline' => false));

//Bootstrap Datepicker CSS
$this->Html->css('datepicker', array('inline' => false));

//Custom CSS
$this->Html->css('job.recrouter', array('inline' => false));

//Bootstrap Javascript
$this -> Html -> script('bootstrap.min', array('inline' => false));

//Bootstrap DatePicker Javascript
$this->Html->script('bootstrap-datepicker', array('inline' => false, 'block' => 'scriptBottom'));
		
//Bootstrap Validator Javascript 
$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom'));

//Bootstrap Multiselect Javascript
$this -> Html -> script('bootstrap-multiselect', array('inline' => false, 'block' => 'scriptBottom'));

//custom Javascript
$this->Html->script('survey.recrouter', array('inline' => false, 'block' => 'scriptBottom'));
?>
<div class="row row-top-buffer-lg"> <!-- begin add survey page -->
	<form class="form-horizontal" role="form" id="add-survey-form" method="post" action="<?php echo $this->request->getAttribute('webroot').'surveys/add/'.$jobs['Job']['id']; ?>">
	<input type="hidden" name="data[Survey][indicator]" id="job-survey-indicator" value="Y" />
	<input type="hidden" name="data[Survey][questions]" id="survey-questions" />
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title">Add Survey</h3>
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body job-survey">
				<div class="form-group form-group-override job-title">
					<label for="job-title" class="col-md-2 control-label">Name</label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="job-title" name="data[Survey][name]" placeholder="Survey name" value="<?php echo $jobs['Job']['title'].' - Survey Template'; ?>">
					</div>
				</div>
			</div> <!-- .panel-body -->
			<div class="panel-footer">
				<a href="#" class="footer-link">[Reset]</a>
			</div> <!-- .panel-footer -->
		</div> <!-- .panel .panel-default -->
		<div class="text-right">
			<button type="button" id="cancel-btn" class="btn btn-default">Cancel</button>
			<button type="button" id="save-btn" class="btn btn-primary">Save</button>
		</div>
	</div> <!-- .col-md-8 -->
	<div class="col-md-4">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div> <!-- row to ensure panel heading height adjusts -->
							<h3 class="panel-title inline-panel-title bold-panel-title">Survey Options</h3>
						</div> <!-- .row -->
					</div> <!-- .panel-heading -->
					<div class="panel-body job-additional-options">
						<div class="row row-top-buffer-lg row-bottom-buffer-lg">
							<div class="col-md-5" style="vertical-align: middle;"><b>Include Survey:</b></div>
							<div class="slider-frame primary">
                        		<span data-on-text="ON" data-off-text="OFF" class="slider-button on">ON</span>
                    		</div>
						</div>
						<div class="row row-top-buffer-lg row-bottom-buffer-lg">
							<div class="col-md-12">
								<div class="btn-group">
		                            <button type="button" class="btn glow">Add Question Type</button>
		                            <button type="button" class="btn glow dropdown-toggle" data-toggle="dropdown">
		                                <span class="caret"></span>
		                            </button>
		                            <ul class="dropdown-menu">
		                                <li><a href="#" id="add-survey-text">Text</a></li>
		                                <!-- <li><a href="#" id="add-survey-yesno">Yes / No</a></li>
		                                <li><a href="#" id="add-survey-single-select">Single-select choice</a></li>
		                                <li><a href="#" id="add-survey-multi-select">Multi-select choice</a></li> -->
		                            </ul>
		                        </div>
	                        </div>
						</div>
					</div> <!-- .panel-body -->
				</div>
			</div>
		</div> <!-- .row -->		
	</div>
	</form>
</div> <!-- .row --> 
<!-- end of add survey page -->
<script id="textSurveyTemplate" type="text/x-tmpl">
<?php echo $this->element('survey_template_1'); ?>
</script>
<script type="text/javascript">
	var view='edit';
</script>