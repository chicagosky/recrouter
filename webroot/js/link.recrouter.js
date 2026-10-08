$(function() {
	$(document).find('li#jobs-menu-item').addClass('active-top-menu');
	
	/*
	 * View Link
	 */
	if ((typeof view !== 'undefined') && (view == 'view')) {
		$(document).on("click", "a.unlink", function () { //change form-action to indicate object
			$('.modal-title').text('Unlink Candidate');
			$('#archive-form').attr('action', $(this).data('url'));
		});
		
		$('#archive-btn').click(function() { //process archive button on arhive-modal
			$('#archive-form').submit();
		});
		
		/*
		 * When the user clicks the edit note button
		 */
		$(document).on('click', 'a#edit-note', function() {
			var eventId = $(this).parents('.event').data('event-id');
			var note = $(this).parents('div.note').children('span').text();
			$(this).parents('div.note').children('span').addClass('hidden');
			$('<textarea class="form-control" rows="3" id="note_' + eventId + '" name="data[CandidatesJobsWorkflowEvent][notes]">' + note + '</textarea>').insertBefore($(this).parent('div.note-links'));
			$(this).siblings('#cancel-edit-note').removeClass('hidden');
			$(this).siblings('#save-note').removeClass('hidden');
			$(this).addClass('hidden');
		});
		
		/*
		 * When the user clicks the save note button
		 */
		$(document).on('click', 'a#save-note', function() {
			//first do all the UI related changes
			var eventId = $(this).parents('.event').data('event-id');
			var note = $(this).parents('div.note').find('textarea#note_' + eventId  + '').val();
			$(this).parents('div.note').children('span').text(note);
			$(this).parents('div.note').children('span').removeClass('hidden');
			$(this).parents('div.note').find('textarea#note_' + eventId  + '').remove();
			$(this).siblings('#edit-note').removeClass('hidden');
			$(this).siblings('#cancel-edit-note').addClass('hidden');
			$(this).parents('.event').find('.timeline-badge').addClass('warning');
			//second make the AJAX call to save the note to the database
			$.ajax({
				url: $(this).data('url'),
				type: 'post',
				data: {
					'data[CandidatesJobsEvent][candidates_job_id]': linkId,
					'data[CandidatesJobsEvent][job_workflow_step_id]': $(this).parents('.event').data('job-workflow-step-id'),
					'data[CandidatesJobsEvent][notes]': note,
					'data[CandidatesJobsEvent][modified]': $(this).parents('.event').data('modified')
				},
				success: function(response) {
					var res = $.parseJSON(response);
					var eventPanel = $(document).find('li[data-event-id="' + res.id + '"]');
					showAjaxAlert(eventPanel, res.success, res.message);
				}
			});
			$(this).addClass('hidden');
		});
		
		/*
		 * When the user decides to cancel editing the note on the event
		 */
		$(document).on('click', 'a#cancel-edit-note', function() {
			var eventId = $(this).parents('.event').data('event-id');
			$(this).parents('div.note').find('textarea#note_' + eventId  + '').remove();
			$(this).parents('div.note').children('span').removeClass('hidden');
			$(this).siblings('#edit-note').removeClass('hidden');
			$(this).siblings('#save-note').addClass('hidden');
			$(this).addClass('hidden');
		});
		
		/*
		 * When the user clicks the complete button
		 */
		$(document).on('click', '.complete', function() {
			$(this).parents('.event').find('.timeline-badge').removeClass('warning');
			$(this).parents('.event').find('.timeline-badge').addClass('success');
			var note = $(this).parents('div.timeline-body').find('div.note > span').text();
			$.ajax({
				url: $(this).data('url'),
				type: 'post',
				data: {
					'data[CandidatesJobsEvent][candidates_job_id]': linkId,
					'data[CandidatesJobsEvent][job_workflow_step_id]': $(this).parents('.event').data('job-workflow-step-id'),
					'data[CandidatesJobsEvent][notes]': note,
					'data[CandidatesJobsEvent][completed]': new Date().toJSON().slice(0,19).replace('T',' ')
				},
				success: function(response) {
					var res = $.parseJSON(response);
					var eventPanel = $(document).find('li[data-event-id="' + res.id + '"]');
					showAjaxAlert(eventPanel, res.success, res.message);
				}
			});
			$(this).addClass('disabled');
		});
	}
});