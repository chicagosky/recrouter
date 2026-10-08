<?php
$this->Html->css('job-candidate.recrouter', array('inline' => false));//custom CSS

$this->Html-> script('bootstrap.min', array('inline' => false)); //Bootstrap Javascript
$this->Html->script('jquery.form.min', array('inline' => false, 'block' => 'scriptBottom')); //Bootstrap Multiselect Javascript
$this->Html->script('candidate.recrouter', array('inline' => false, 'block' => 'scriptBottom')); //custom Javascript
?>
<div class="row">
	<div class="col-md-9">
		<form class="form-horizontal" role="form" id="bulk-upload-candidate-form" method="post" enctype="multipart/form-data" action="<?php echo $this->webroot.'candidates/upload/true'; ?>">
			<div class="panel panel-default bulk-upload-panel"> <!-- Begin Bulk upload file input section -->
				<div class="panel-heading">
					<div> <!-- row to ensure panel heading height adjusts -->
						<h3 class="panel-title inline-panel-title bold-panel-title">Bulk Upload Candidates</h3>&nbsp;&nbsp;
					</div> <!-- .row -->
				</div> <!-- .panel-heading -->
				<div class="panel-body">
					<div class="input-group row-top-buffer-lg row-bottom-buffer-lg col-md-6">
		                <span class="input-group-btn">
		                    <span class="btn btn-primary btn-file">
		                        Browse&hellip; <input type="file" name="data[files][]" multiple>
		                    </span>
		                </span>
		                <input type="text" class="form-control" readonly>
		         	</div> <!-- .input-group -->  
				</div> <!-- .panel-body -->
			</div> <!-- .panel .panel-default .bulk-upload-panel --> <!-- end of bulk upload file input section -->
		</form>
		<?php if (!empty($status)) { ?>
		<form class="form-horizontal" id="bulk-process-form" role="form" method="post" enctype="multipart/form-data" action="<?php echo $this->webroot.'candidates/bulkAdd'; ?>">
		<div class="panel panel-default list-of-files"> <!-- Begin list of files uploaded section -->
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title">List of Files</h3>&nbsp;&nbsp;
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body">
				<table class="table table-hover">
					<thead>
						<tr>
							<th class="col-md-1">#</th>
							<th class="col-md-4">File</th>
							<th class="col-md-4 text-center">Status</th>
							<th class="col-md-1 text-right">Process</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$cnt = 1;
					foreach ($status as $s) {
						if ($s['success']) {
					?>
						<tr>
							<td><?php echo $cnt; ?></td>
							<td><?php echo $s['filename']; ?></td>
							<td class="text-center">OK</td>
							<td class="text-right"><input type="checkbox" name="data[files][]" value="<?php echo $s['path']; ?>" checked></td>
						</tr>
					<?php 
						} else {
					?>
						<tr>
							<td><?php echo $cnt; ?></td>
							<td style="color:red;"><?php echo $s['filename']; ?></td>
							<td class="text-center" style="color:red;"><?php echo $s['message']; ?></td>
							<td class="text-right"><input type="checkbox" name="" value="" disabled></td>
						</tr>
					<?php
						}
						$cnt++;
					} 
					?>
					</tbody>
				</table>
			</div> <!-- .panel-body -->
		</div> <!-- .panel .panel-default .list-of-files -->
		</form>
		<?php } ?>
		<div class="text-right">
			<button type="button" id="cancel-btn" class="btn btn-default">Cancel</button>
			<button type="submit" id="process-btn" class="btn btn-primary">Process</button>
		</div>
	</div> <!-- .col-md-9 -->
	<div class="col-md-3 side-text">
		<div class="panel panel-default">
			<div class="panel-body">
				<h6>Instructions</h6>
				<p>Some sample instructions. Some sample instructions. Some sample instructions. Some sample instructions
					Some sample instructions</p>
			</div> <!-- .panel-body -->
		</div> <!-- .panel .panel-default -->
	</div> <!-- .col-md-3 .side-text -->
</div> <!-- .row -->
<script type="text/javascript">
	var view = 'bulk-upload';
</script>