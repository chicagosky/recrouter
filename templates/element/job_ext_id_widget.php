<div class="panel panel-default">
	<div class="panel-heading">
		<div> <!-- row to ensure panel heading height adjusts -->
			<h3 class="panel-title inline-panel-title search-results-panel-title">External Identifiers</h3>
		</div> <!-- .row -->
	</div> <!-- .panel-heading -->
	<div class="panel-body job-external-ids">
	<?php foreach ($jobExtIds as $row) { ?>
		<div class="row row-top-buffer-md row-bottom-buffer-md job-ext-id-row">
			<div class="col-md-4"><b><?php echo $row['ext_name']; ?></b></div> <!-- .col-md-4 -->
			<div class="col-md-8"><?php echo $row['ext_id']; ?></div> <!-- .col-md-8 -->
		</div> <!-- .row .job-ex-id-row -->
	<?php } ?>
	</div> <!-- .panel-body -->
</div>