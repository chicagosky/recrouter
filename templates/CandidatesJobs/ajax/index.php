<div class="panel panel-default">
	<div class="panel-heading">
		<div> <!-- row to ensure panel heading height adjusts -->
			<h3 class="panel-title inline-panel-title search-results-panel-title">Linked Candidates</h3>&nbsp; 
			<a href="javascript://" data-toggle="modal" data-target="#link-candidate" data-url="<?php echo $this->request->getAttribute('webroot').'Candidates/listForLink'; ?>">
				<i class="glyphicon glyphicon-plus"></i>&nbsp; [Link]
			</a>
		</div> <!-- .row -->
	</div> <!-- .panel-heading -->
	<div class="panel-body">
	<?php foreach ($links as $link) { ?>
		<div class="row row-top-buffer-md row-bottom-buffer-md job-link-row">
			<div class="col-md-8">
				<a href="<?php echo $this->request->getAttribute('webroot').'CandidatesJobs/view/'.$link['CandidatesJob']['id']; ?>">
					<i class="glyphicon glyphicon-link"></i> 
					<b><?php echo $link['Candidate']['firstname'].' '.$link['Candidate']['lastname']; ?></b>
				</a>
			</div> <!-- .col-md-8 -->
			<div class="col-md-4">
				<?php echo $this->Time->timeAgoInWords($link['CandidatesJob']['created'], array('accuracy' => array('day' => 'day', 'hour' => 'hour'))); ?>
			</div> <!-- .col-md-4 -->
		</div> <!-- .row .job-ex-id-row -->
	<?php } ?>	
	</div> <!-- .panel-body -->
	<div class="panel-footer text-right links-pagination">
		<?php echo $this->element('pagination', array('separator' => ' | ')); ?>
	</div>
</div>