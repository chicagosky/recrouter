<?php
$this -> Html -> css('typeahead', array('inline' => false)); //Typeahead CSS
$this->Html->css('bootstrap-tagsinput.min', array('inline' => false)); //Bootstrap Tagsinput CSS
$this->Html->css('bootstrap-multiselect', array('inline' => false)); //Bootstrap Multiselect CSS
$this->Html->css('job-candidate.recrouter', array('inline' => false));//custom CSS

$this -> Html -> script('bootstrap.min', array('inline' => false)); //Bootstrap Javascript
$this->Html->script('bootstrap-tagsinput.min', array('inline' => false)); //Bootstrap Tagsinput Javascript
$this->Html->script('typeahead.bundle.min', array('inline' => false)); //Tyepahead Javascript
$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom')); //Bootstrap Validator Javascript 
$this->Html->script('bootstrap-multiselect', array('inline' => false, 'block' => 'scriptBottom')); //Bootstrap Multiselect Javascript
$this->Html->script('jquery.form.min', array('inline' => false, 'block' => 'scriptBottom')); //Bootstrap Multiselect Javascript
$this->Html->script('candidate.recrouter', array('inline' => false, 'block' => 'scriptBottom')); //custom Javascript

$candidate = $this->request->data['Candidate'];
$createdDate = DateTime::createFromFormat('Y-m-d H:i:s', $candidate['created']);
$tags = implode(' ', array_map(function($val) { return "<span class='tag'>".$val['name'].'</span>'; }, $this->request->data['Tag']));
?>
<div class="row row-top-buffer-lg"> <!-- begin edit candidate page -->
	<form class="form-horizontal" role="form" id="edit-candidate-form" method="post" action="<?php echo $this->webroot.'candidates/edit/'.$candidate['id']; ?>">
	<input type="hidden" id="country" name="data[Candidate][country]" value="USA" />
	<input type="hidden" id="country" name="data[Candidate][modified]" value="<?php echo $candidate['modified']; ?>" />
	<input type="hidden" id="file-name" name="data[Candidate][file_name]" value="<?php echo $candidate['file_name']; ?>" />
	<div class="col-md-8">
		<?php echo $this->Session->flash(); ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title">Edit Candidate</h3>&nbsp;&nbsp;
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body view-candidate-details">
				<div class="form-group form-group-override candidate-name">
					<label for="candidate-name" class="col-md-2 control-label">Name</label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="candidate-name" name="data[Candidate][name]" placeholder="First name Last name" value="<?php echo $candidate['firstname'].' '.$candidate['lastname']; ?>">
					</div>
				</div> <!-- .form-group .candidate-name -->
				<div class="form-group form-group-override phone">
					<label for="phone" class="col-md-2 control-label">Phone</label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="phone" name="data[Candidate][phone]" placeholder="Phone number" value="<?php echo $candidate['phone']; ?>">
					</div>
				</div> <!-- .form-group .phone -->
				<div class="form-group form-group-override phone-alt">
					<label for="phone-alt" class="col-md-2 control-label">Alt. Phone</label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="phone-alt" name="data[Candidate][phone_alt]" placeholder="Alt phone number" value="<?php echo $candidate['phone_alt']; ?>">
					</div>
				</div> <!-- .form-group .phone -->
				<div class="form-group form-group-override email">
					<label for="email" class="col-md-2 control-label">E-mail</label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="email" name="data[Candidate][email]" placeholder="E-mail" value="<?php echo $candidate['email']; ?>">
					</div>
				</div> <!-- .form-group .phone -->
				<div class="form-group form-group-override location">
					<label for="location" class="col-md-2 control-label">Location</label>
					<div class="col-md-4">
						<input type="text" class="form-control" id="city" name="data[Candidate][city]" placeholder="City" value="<?php echo $candidate['city']; ?>">
					</div>
					<div class="col-md-3 state" data-preselect="<?php echo $candidate['state']; ?>">
						<select class="form-control" id="state" name="data[Candidate][state]">
						</select>
					</div>
				</div> <!-- .form-group .location -->
				<div class="row row-top-buffer-lg row-bottom-buffer-lg bottom-border-dashed contract-model" data-preselect="<?php echo implode(',', array_map(function($val) { return $val['id']; }, $this->request->data['ContractModel'])); ?>">
					<label for="contract-model" class="col-md-2 control-label">Contract Models</label>
					<div class="col-md-5">
						<select class="form-control" id="contract-model" name="ContractModel[]" multiple="multiple">
							<?php foreach ($contractModels as $id => $name) { ?>
							<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group form-group-override notes">
					<label for="notes" class="col-md-2 control-label">Notes</label>
					<div class="col-md-9">
						<input type="text" class="form-control" id="notes" name="data[Candidate][notes]" placeholder="Notes..." value="<?php echo $candidate['notes']; ?>">
					</div>
				</div> <!-- .form-group .notes -->
			</div> <!-- .panel-body -->
			<div class="panel-footer">
				<a href="<?php echo $this->webroot.'/candidates/edit/'.$candidate['id']; ?>" class="footer-link">[Reset]</a>
			</div> <!-- .panel-footer -->
		</div> <!-- .panel .panel-default -->
		<div class="panel panel-default"> <!-- Begin candidate file section -->
			<div class="panel-heading">
				<h3 class="panel-title inline-panel-title bold-panel-title">Resume</h3>
			</div>
			<div class="panel-body resume-panel-body">
				<div class="row" id="file-container" style="height:inherit;">
					<object id="file" data="<?php echo $this->webroot.'candidates/getFileFor/'.$candidate['id'].'/'.$candidate['file_name']; ?>" width="100%" height="100%" >
						<p>
							It appears you don't have a PDF plugin for this browser. You can 
							<a href="<?php echo $candidate['file_name']; ?>">click here to download the PDF file.</a>
						</p>
					</object>
				</div> <!-- .row -->
			</div> <!-- .panel-body .resume-panel-body -->
			<div class="panel-footer">
				<a href="#" class="footer-link upload-link"><i class="glyphicon glyphicon-upload"></i> [Upload]</a>
				<?php if (!empty($candidate['file_name'])) { ?>
				<a href="<?php echo $this->webroot.'candidates/removeFileFor/'.$candidate['id']; ?>" class="footer-link"><i class="glyphicon glyphicon-remove"></i> [Remove]</a>
				<?php } ?>
			</div>
		</div> <!-- end of candidate file section -->
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
						<h3 class="panel-title inline-panel-title bold-panel-title">Edit Tags</h3>
					</div> <!-- .row -->
				</div> <!-- .panel-heading -->
				<div class="panel-body candidate-tags">
					<input type="text" name="data[Candidate][tags]" id="edit-tags" placeholder="enter tag" data-role="tagsinput">
				</div> <!-- .panel-body -->
			</div>
			</div>
		</div> <!-- .row -->
	</div> <!-- .col-md-4 -->
	</form>
</div> <!-- .row --> 
<form id="file-upload-form" method="post" enctype="multipart/form-data" action="<?php echo $this->webroot.'candidates/upload'; ?>">
	<input type="file" name="data[files][]" id="file-upload" style="opacity: 0;" />
</form>
<!-- end of edit candidate page -->
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
	
	var cndTags = <?php echo json_encode($this->request->data['Tag']); ?>;
</script>