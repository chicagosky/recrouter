<?php echo $this->element('event_note', array('event' => $event)); ?>
<div class="row-top-buffer-md">
	<button type="button" class="btn btn-success btn-xs pull-right complete" <?php echo $disabledFlag; ?> data-url="<?php echo $this -> webroot . 'candidatesjobsevents/edit/' . $event['event_id']; ?>">
		<i class="glyphicon glyphicon-ok"></i>
	</button>
</div>