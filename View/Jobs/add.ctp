<?php
//Typeahead CSS
$this -> Html -> css('typeahead', array('inline' => false));

//Bootstrap Tagsinput CSS
$this->Html->css('bootstrap-tagsinput.min', array('inline' => false));

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
		
//Bootstrap WYSIWYG Editor Javascript
$this->Html->script('jquery.hotkeys', array('inline' => false, 'block' => 'scriptBottom'));
$this->Html->script('bootstrap-wysiwyg', array('inline' => false, 'block' => 'scriptBottom'));

//Bootstrap Tagsinput Javascript
$this->Html->script('bootstrap-tagsinput.min', array('inline' => false));

//Tyepahead Javascript
$this->Html->script('typeahead.bundle.min', array('inline' => false));

//Bootstrap Validator Javascript 
$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom'));

//Bootstrap Multiselect Javascript
$this -> Html -> script('bootstrap-multiselect', array('inline' => false, 'block' => 'scriptBottom'));

//custom Javascript
$this->Html->script('job.recrouter', array('inline' => false, 'block' => 'scriptBottom'));
?>
<div class="row row-top-buffer-lg"> <!-- begin edit job page -->
	<form class="form-horizontal" role="form" id="add-job-form" method="post" action="<?php echo $this->webroot.'jobs/add'; ?>">
	<input type="hidden" name="data[Job][country]" value="USA" />
	<input type="hidden" name="data[JobExtId]" id="job-ext-id" />
	<input type="hidden" name="data[Job][description]" id="job-description" />
	<input type="hidden" name="data[Job][skills]" id="job-skills" />
	<input type="hidden" name="data[Survey][indicator]" id="job-survey-indicator" value="N" />
	<div class="col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title">Add Job</h3>
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body view-job-details">
				<div class="form-group form-group-override job-title">
					<label for="job-title" class="col-md-2 control-label">Title</label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="job-title" name="data[Job][title]" placeholder="Title" value="">
					</div>
				</div>
				<div class="form-group form-group-override job-location">
					<label for="job-location" class="col-md-2 control-label">Location</label>
					<div class="col-md-4">
						<input type="text" class="form-control" id="job-city" name="data[Job][city]" placeholder="City" value="">
					</div>
					<div class="col-md-3 job-state" data-preselect="">
						<select class="form-control" id="job-state" name="data[Job][state]">
							<?php foreach ($states as $alpha_2 => $name) { ?>
							<option value="<?php echo $alpha_2; ?>"><?php echo $name; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group form-group-override job-department" data-preselect="">
					<label for="job-department" class="col-md-2 control-label">Department</label>
					<div class="col-md-3">
						<select class="form-control" id="job-department" name="data[Job][department_id]">
							<?php foreach ($departments as $id => $name) { ?>
							<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group form-group-override job-fill-by">
					<label for="job-fill-by" class="col-md-2 control-label">Fill By</label>
					<div class="col-md-3">
						<div class="input-group" id="job-fill-byDiv" data-date-format="mm/dd/yyyy" data-date="01-01-2014">
							<input type="text" class="form-control" id="job-fill-by" name="data[Job][fill_by]" placeholder="mm/dd/yyyy" value="">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						</div>
					</div>
				</div>
				<div class="row row-top-buffer-lg row-bottom-buffer-lg bottom-border-dashed job-contract-model" data-preselect="">
					<label for="job-title" class="col-md-2 control-label">Contract Models</label>
					<div class="col-md-5">
						<select class="form-control" id="job-contract-model" name="ContractModel[]" multiple="multiple">
							<?php foreach ($contractModels as $id => $name) { ?>
							<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="row row-top-buffer-lg row-bottom-buffer-lg job-workflow" data-preselect="">
					<label for="job-title" class="col-md-2 control-label">Workflow</label>
					<div class="col-md-5">
						<select class="form-control" id="job-workflow" name="data[Job][workflow_id]">
							<?php foreach ($workflows as $id => $name) { ?>
							<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div> <!-- .panel-body -->
			<div class="panel-footer">
				<a href="#" class="footer-link">[Reset]</a>
			</div> <!-- .panel-footer -->
		</div> <!-- .panel .panel-default -->
		<div class="panel panel-default"> <!-- Begin responsibilities panel -->
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title">Responsibilities</h3>
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body">
				<?php echo $this -> element('editor_toolbar', array('dataTarget' => '#editor', 'dataRole' => 'editor-toolbar')); ?> <!-- editor toolbar element -->
				<div class="description-editor" id="editor" contenteditable="true">
				</div> <!-- .edit-area -->
			</div> <!-- .panel-body -->
		</div> <!-- .panel .panel-default --> <!-- End of responsibilities panel -->
		<div class="panel panel-default"> <!-- Begin skills panel -->
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title">Skills</h3>
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body">
				<?php echo $this -> element('editor_toolbar', array('dataTarget' => '#editor2', 'dataRole' => 'editor2-toolbar')); ?> <!-- editor toolbar element -->
				<div class="skills-editor" id="editor2" contenteditable="true">
				</div> <!-- .edit-area -->
			</div> <!-- .panel-body -->
		</div> <!-- .panel .panel-default --> <!-- End of skills panel -->
		<div class="text-right">
			<button type="button" id="cancel-btn" class="btn btn-default">Cancel</button>
			<button type="submit" id="save-btn" class="btn btn-primary">Save</button>
		</div>
	</div> <!-- .col-md-8 -->
	<div class="col-md-4">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div> <!-- row to ensure panel heading height adjusts -->
							<h3 class="panel-title inline-panel-title bold-panel-title">Additional Options</h3>
						</div> <!-- .row -->
					</div> <!-- .panel-heading -->
					<div class="panel-body job-additional-options">
						<div class="row row-top-buffer-lg row-bottom-buffer-lg">
							<div class="col-md-5" style="vertical-align: middle;"><b>Include Survey:</b></div>
							<div class="slider-frame primary">
                        		<span data-on-text="ON" data-off-text="OFF" class="slider-button">OFF</span>
                    		</div>
						</div>
					</div> <!-- .panel-body -->
				</div>
			</div>
		</div> <!-- .row -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div> <!-- row to ensure panel heading height adjusts -->
							<h3 class="panel-title inline-panel-title bold-panel-title">Edit Tags</h3>
						</div> <!-- .row -->
					</div> <!-- .panel-heading -->
					<div class="panel-body job-tags">
						<input type="text" name="data[Job][tags]" id="edit-tags" placeholder="enter tag" data-role="tagsinput">
					</div> <!-- .panel-body -->
				</div>
			</div>
		</div> <!-- .row -->
		<div class="row job-ext-id">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div> <!-- row to ensure panel heading height adjusts -->
							<h3 class="panel-title inline-panel-title bold-panel-title">External Identifiers</h3>&nbsp; 
							<a href="#" id="add-ext-id-btn"><i class="glyphicon glyphicon-plus"></i>&nbsp; [Add]</a>
						</div> <!-- .row -->
					</div> <!-- .panel-heading -->
					<div class="panel-body job-external-ids">
					</div> <!-- .panel-body -->
				</div>
			</div>
		</div> <!-- .row -->
	</div>
	</form>
</div> <!-- .row --> 
<!-- end of edit job page -->
<script type="text/javascript">
	var view = 'edit';
	
	var tags = new Bloodhound({
		datumTokenizer : Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer : Bloodhound.tokenizers.whitespace,
		prefetch : 'tags.json',
		remote : {
			url : 'http://localhost/cake/tags/search/%QUERY',
			filter : function(jsonArray) {
				return $.map(jsonArray, function(obj) {
					return {
						name : obj.Tag.name,
						id : obj.Tag.id
					};
					//each jsonArray object is obj.Tag.text & obj.Tag.value
				});
			}
		}
	});

	tags.initialize();

	$('#edit-tags').tagsinput({
		itemValue : 'id',
		itemText : 'name',
		typeaheadjs : {
			name : 'tags',
			displayKey : 'name',
			source : tags.ttAdapter()
		}
	});
	
	var jobTags = '';
</script>