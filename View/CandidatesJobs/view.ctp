<?php
$this->Html->css('/fonts/font-awesome-4.1.0/css/font-awesome.min.css', array('inline' => false)); //Font Awesome CSS
$this->Html->css('timeline', array('inline' => false)); //Timeline CSS
$this->Html->css('link.recrouter', array('inline' => false)); //Custom CSS
$this->Html->script('bootstrap.min', array('inline' => false)); //Bootstrap Javascript
$this->Html->script('link.recrouter', array('inline' => false, 'block' => 'scriptBottom')); //custom Javascript

$hired = !$this->Time->isFuture($candidatesJob['CandidatesJob']['hired']);
$rejected = !$this->Time->isFuture($candidatesJob['CandidatesJob']['rejected']);
?>
<?php echo $this->Session->flash(); ?>
<div class="panel panel-default view-link">
	<div class="panel-heading">
		<div> <!-- row to ensure panel heading height adjusts -->
			<h3 class="panel-title inline-panel-title bold-panel-title">
				<a href="<?php echo $this->webroot.'Jobs/view/'.$candidatesJob['Job']['id']; ?>" style="color:#337AB7;">
					<?php echo $candidatesJob['Job']['title']; ?>
				</a>
			</h3>&nbsp;&nbsp;<i class="glyphicon glyphicon-link"></i>&nbsp;&nbsp;
			<h3 class="panel-title inline-panel-title bold-panel-title text-right">
				<a href="<?php echo $this->webroot.'Candidates/view/'.$candidatesJob['Candidate']['id']; ?>" style="color:#337AB7;">
					<?php echo $candidatesJob['Candidate']['firstname'].' '.$candidatesJob['Candidate']['lastname']; ?>
				</a>
			</h3>&nbsp;&nbsp; 
			<span class="link-email">
				<i class="glyphicon glyphicon-envelope"></i> <?php echo $candidatesJob['CandidatesJob']['email']; ?>
			</span>
			<span class="pull-right">
			<?php if ($hired || $rejected) { ?>
				<a href="<?php echo $this->webroot.'CandidatesJobs/reopen/'.$candidatesJob['CandidatesJob']['id']; ?>" class="btn btn-primary btn-xs reopen">
					<i class="glyphicon glyphicon-eye-open"></i> Reopen 
				</a>
			<?php } elseif (!$rejected && !$hired) { ?>
				<a href="<?php echo $this->webroot.'CandidatesJobs/reject/'.$candidatesJob['CandidatesJob']['id']; ?>" class="btn btn-danger btn-xs reject">
					<i class="glyphicon glyphicon-thumbs-down"></i> Reject 
				</a> 
				<a href="<?php echo $this->webroot.'CandidatesJobs/hire/'.$candidatesJob['CandidatesJob']['id']; ?>" class="btn btn-success btn-xs hire">
					<i class="glyphicon glyphicon-thumbs-up"></i> Hire
				</a>
				<?php if ($hired) { ?>
					<h5 style="display:inline;"><span class="label label-success">Hired</span></h5>
				<?php } elseif ($rejected) { ?>
					<h5 style="display:inline;"><span class="label label-danger">Rejected</span></h5>
				<?php } ?>
			<?php } ?>
			</span>
		</div> <!-- .row -->
	</div> <!-- .panel-heading -->
	<div class="panel-body link-details">
		<ul class="timeline">
		<?php 
		foreach($stepsAndEvents as $count => $step) {
			$elementDirection = ($count & 1) ? 'timeline-inverted' : '';
			echo $this->element('candidates_jobs_event', array(
				'elementDirection' => $elementDirection,
				'event' => $step[0]
			));
		} 
		?>
		</ul>
	</div> <!-- .panel-body -->
	<div class="panel-footer">
		<a href="javascript://" class="footer-link unlink" data-url="<?php echo $this->webroot.'CandidatesJobs/delete/'.$candidatesJob['CandidatesJob']['id']; ?>" data-toggle="modal" data-target="#archive-modal">
			<i class="fa fa-unlink"></i> [Unlink]
		</a>
	</div>
</div> <!-- .panel .panel-default -->
<!-- end of page -->
<?php echo $this->element('archive_dialog'); ?>

<script type="text/javascript">
	var view = 'view';
	var linkId = <?php echo $candidatesJob['CandidatesJob']['id']; ?>;
</script>