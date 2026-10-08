<?php
//custom CSS

//custom Javascript
$this->Html->script('candidate.recrouter', array('inline' => false, 'block' => 'scriptBottom'));

$createdDate = DateTime::createFromFormat('Y-m-d H:i:s', $candidate['Candidate']['created']);
$tags = implode(' ', array_map(function($val) { return "<span class='tag'>".$val['name'].'</span>'; }, $candidate['Tag']));
$contractModels = implode(' ', array_map(function($val) { return "<span class='contract-model'>".$val['name'].'</span>'; }, $candidate['ContractModel']));
?>
<div class="row row-top-buffer-lg"> <!-- begin view candidate page -->
	<div class="col-md-8">
		<?php echo $this->Session->flash(); ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title"><?php echo $candidate['Candidate']['firstname'].' '.$candidate['Candidate']['lastname']; ?></h3>&nbsp;&nbsp;
					<?php if (!empty($candidate['Candidate']['phone'])) { ?><i class="glyphicon glyphicon-phone-alt"></i>&nbsp; <?php echo $candidate['Candidate']['phone']; } ?>&nbsp;&nbsp;
					<?php if (!empty($candidate['Candidate']['phone_alt'])) { ?><i class="glyphicon glyphicon-phone"></i>&nbsp; <?php echo $candidate['Candidate']['phone_alt']; } ?>&nbsp;&nbsp; 
					<?php if (!empty($candidate['Candidate']['email'])) { ?><i class="glyphicon glyphicon-envelope"></i>&nbsp; <?php echo $candidate['Candidate']['email']; } ?>
					<a href="javascript://" class="pull-right" data-row-id="<?php echo $candidate['Candidate']['id']; ?>" data-toggle="modal" data-target="#archive-modal"><i class="glyphicon glyphicon-trash"></i></a>
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body view-job-details">
				<div class="row row-top-buffer-lg bottom-border-dashed job-department">
					<label class="col-md-3">Location</label>
					<div class="col-md-9"> <?php echo $candidate['Candidate']['city'].', '.$candidate['Candidate']['state']; ?> </div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg bottom-border-dashed created-by">
					<label class="col-md-3">Created by</label>
					<div class="col-md-9"><a href="mailto:<?php echo $candidate['User']['username']; ?>"><?php echo $candidate['User']['lastname'].', '.$candidate['User']['firstname']; ?>, <?php echo $this->Time->timeAgoInWords($candidate['Candidate']['created']); ?></a></div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg bottom-border-dashed contract-models">
					<label class="col-md-3">Contract Models</label>
					<div class="col-md-9 contract-models"> <?php echo $contractModels ?> </div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg bottom-border-dashed tags">
					<label class="col-md-3">Tags</label>
					<div class="col-md-9 tags"> <?php echo $tags ?> </div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg notes">
					<label class="col-md-3">Notes</label>
					<div class="col-md-9 tags"> <?php echo $candidate['Candidate']['notes']; ?> </div> <!-- .col-md-9 -->
				</div>
			</div> <!-- .panel-body -->
			<div class="panel-footer">
				<a href="#" class="footer-link"><i class="glyphicon glyphicon-print"></i> [Print]</a>
				<a href="<?php echo $this->webroot.'candidates/edit/'.$candidate['Candidate']['id']; ?>" class="footer-link"><i class="glyphicon glyphicon-edit"></i> [Edit]</a>
			</div>
		</div> <!-- .panel .panel-default -->
		<?php if (!empty($candidate['Candidate']['file_name'])) { ?>
		<div class="panel panel-default"> <!-- Begin candidate file section -->
			<div class="panel-heading">
				<h3 class="panel-title inline-panel-title bold-panel-title">Resume</h3>
			</div>
			<div class="panel-body resume-panel-body">
				<div class="row" style="height:inherit;">
				<object id="pdf" data="<?php echo $this->webroot.'candidates/getFileFor/'.$candidate['Candidate']['id'].'/'.$candidate['Candidate']['file_name']; ?>" type="application/pdf" width="100%" height="100%" >
					<p>
						It appears you don't have a PDF plugin for this browser. You can 
						<a href="<?php echo $candidate['Candidate']['file_name']; ?>">click here to download the PDF file.</a>
					</p>
				</object>
				</div>
			</div>
			<div class="panel-footer">
				<a href="#" class="footer-link"><i class="glyphicon glyphicon-print"></i> [Print]</a>
				<a href="#" class="footer-link"><i class="glyphicon glyphicon-download"></i> [Download]</a>
			</div>
		</div> <!-- end of candidate file section -->
		<?php } ?>
	</div> <!-- .col-md-8 -->
	<div class="col-md-4">
		<div class="row">
			<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div> <!-- row to ensure panel heading height adjusts -->
						<h3 class="panel-title inline-panel-title search-results-panel-title">Linked Jobs</h3>&nbsp; 
						<a href="#"><i class="glyphicon glyphicon-plus"></i>&nbsp; [Add]</a>
					</div> <!-- .row -->
				</div> <!-- .panel-heading -->
				<div class="panel-body linked-jobs">
				</div> <!-- .panel-body -->
			</div>
			</div>
		</div> <!-- .row -->
	</div>
</div> <!-- .row --> 
<!-- end of view candidate page -->
<?php echo $this -> element('archive_dialog'); ?> <!-- archive dialog element -->
<script type="text/javascript">
	var view = 'view';
</script>