$(function() {
	if ((typeof view !== 'undefined') && (view == 'edit')) {
		$(document).on("click", ".glyphicon-trash", function () { //change form-action to indicate object
			$('.modal-title').text('Delete Question');
			$('#archive-form').attr('action', www_root + 'questions/delete/' + $(this).parent().data('row-id'));
		});
		
		$('#archive-btn').click(function() { //process archive button on arhive-modal
			$('#archive-form').submit();
		});
		
		//add new text question to survey template
		$('#add-survey-text').on('click', function() {
			if ($(this).parents('li').hasClass('disabled')) {
				return;
			}
			$('.job-survey').append($('#textSurveyTemplate').text());
		});
		
		//on/off slider used to indicate inclusion of survey
		$('.slider-button').click(function() {
	        if ($(this).hasClass("on")) {
	            $(this).removeClass('on').html($(this).data("off-text"));   
	            $('input#job-survey-indicator').attr('value', 'N');
	            $('#add-survey-text').parents('li').addClass('disabled');
	        } else {
	            $(this).addClass('on').html($(this).data("on-text"));
	            $('#add-survey-text').parents('li').removeClass('disabled');
	            $('input#job-survey-indicator').attr('value', 'Y');
	        }
	    });
	    
	    //checking if questions are pre-loaded - difference between add and edit
	    if (typeof questions !== 'undefined') {
	    	for (var key in questions) { //questions is defined in edit.ctp
				question = questions[key];
				var questionTemplate = $('#textSurveyTemplate').html();
				var curr = $(questionTemplate).appendTo($('.job-survey'));
				curr.find('input#question-name').attr('value', question.name);
				//curr.find('input#question-id').attr('value', question.id);
				$(curr[0]).data('question-id', question.id);
				if ('Y' == question.required) {
					curr.find('.btn-toggle').click();
				}
			}
	    }
		
		//delete a particular text question
		$(document).on('click', '.delete-text-question', function() { //if this is a newly added question
			if ('' == $(this).parents('.text-question').data('question-id')) {
				$(this).parents('.text-question').remove();
			} else { //if this is a pre-existing question
				$(this).parents('.text-question').data('delete', 'Y');
				$(this).parents('.text-question').addClass('hidden');
			}
		});
	
	
		//save survey template
		$('#save-btn').click(function(evt) {
			evt.preventDefault();
			var questions = [];
			$('.text-question').each(function() {
				var question = {};
				question.id = $(this).data('question-id');
				question.name = $(this).find('#question-name').val();
				question.type = '1';
				question.required = $(this).find('.btn-toggle > .active').text();
				question.subtext = '';
				question.option_group_id = 0;
				if ('Y' == $(this).data('delete')) {
					question.archived = getSQLDate();
				}
				question.delete = $(this).data('delete');
				questions.push(question);
			});
			$('input#survey-questions').attr('value', JSON.stringify(questions));
			$('#add-survey-form').submit();
		});
	}
	
	/*
	 * Survey Response view
	 */
	if ((typeof view !== 'undefined') && (view == 'response')) {
		/*
		 * When candidate submits responses to survey questions
		 */
		$('#save-btn').click(function(evt) {
			responses= [];
			$('#survey-response-form').find('.survey-response.response-text').each(function() {
				var response = {};
				response.question_id = $(this).data('question-id');
				response.candidates_job_id = $('#survey-response-form').data('link-id');
				response.answer_text = $(this).val();
				responses.push(response);
			});
			$('input#survey-response').attr('value', JSON.stringify(responses));
			$('#survey-response-form').submit();
		});
	}
});
