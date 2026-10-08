<div class="note">
	<span><?php echo $event['notes']; ?></span>
	<div class="note-links row-top-buffer-sm text-right">
		<a href="javascript://" id="edit-note"><i class="glyphicon glyphicon-edit"></i> [Edit Note]</a> 
		<a href="javascript://" id="save-note" class="hidden" data-url="<?php echo $this -> webroot . 'CandidatesJobsEvents/edit/' . $event['event_id']; ?>">
			<i class="glyphicon glyphicon-save"></i> [Save]
		</a> &nbsp;&nbsp;
		<a href="javascript://" id="cancel-edit-note" class="hidden"><i class="glyphicon glyphicon-remove"></i> [Cancel]</a>
	</div>
</div>