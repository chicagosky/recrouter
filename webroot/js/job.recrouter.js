$(function() {
	$(document).find('li#jobs-menu-item').addClass('active-top-menu');
	
	/*
	 * Search Job
	 */
	if ((typeof view !== 'undefined') && (view == 'index')) {
		$('.search-result-item').mouseenter(function() {
			$('#qv-title').html($(this).find('#job-title').html());
			$('#qv-created').text($(this).data('created'));
			$('#qv-fill-by').text($(this).data('fill-by'));
			$('#qv-contract-models').html($(this).data('contract-models'));
			$('#qv-contract-models').children('.contract-model').each(function () {
				$(this).removeClass('hidden');
			});
			$('#qv-tags').html($(this).data('tags'));
			$('#qv-tags').children('.tag').each(function () {
				$(this).removeClass('hidden');
			});
		});
		
		$(document).on('click', '.star', function() {
			if ( $( this ).hasClass( 'checked' ) ) {
				$( this ).removeClass( 'checked' );
			} else {
				$( this ).addClass( 'checked' );
			}
			$.ajax({ 
				url: www_root + 'Users/toggleFavoriteJob/' + $(this).parents('div.search-result-item').data('row-id'),
				type: 'POST',
				cache: false,
				success: function(response) {
					var res = $.parseJSON(response);
					if (res.success) {
						//success
					} else {
						//fail
					}
				}
			});
		});
		
		if (typeof sortKey !== undefined) {
			$('#sort-order').val(sortKey);
		}
		
		$('#sort-order').on('change', function() {
			var sortOrder = $('#sort-order option:selected').val();
			if (typeof sortOrder !== undefined) {
				var url = $('#sort-by-' + sortOrder).parent().attr('href');
				window.location = url;
				//$('#sort-by-' + sortOrder).parent().trigger('click');
			}
		});
	}
	
	/*
	 * View Job
	 */
	if ((typeof view !== 'undefined') && (view == 'view')) {
		$(document).on("click", ".glyphicon-trash", function () { //change form-action to indicate object
			$('.modal-title').text('Archive Job');
			$('#archive-form').attr('action', www_root + 'Jobs/delete/' + $(this).parent().data('row-id'));
		});
		
		$('#archive-btn').click(function() { //process archive button on arhive-modal
			$('#archive-form').submit();
		});
		
		$.plot('#link-chart', data, {
		    series: {
		        pie: {
		            innerRadius: 0.65,
		            show: true,
		        }
		    },
		    legend: {
		    	show: true
		    }
		});
		
		var usersForWorkflowStep = function() { //cache list of users in DOM
	    	var dynHTML = null;
	    	$.ajax({
				url: www_root + 'Users/listOfUsers/',
				type: 'post',
				cache: false,
				success: function(response) {
					dynHTML = '<select class="form-control list-of-users" name="data[JobWorkflowStepsUser][user_id]">'; //begin building select element
					var users = $.parseJSON(response); 
					for (var key in users) { //loop through and build option values
			    		dynHTML += '<option value="' + users[key].id + '">' + users[key].firstname + ' ' + users[key].lastname + '</option>';
			    	}
			    	dynHTML += '</select>'; //end of constructing select element
			    	$('#list-of-users').html(dynHTML);
				}
			});
	    }();
	    
	    $('a.add-user').popover({ //add workflow step user popover
		    html: true,
		    placement: 'top',
		    content: function() { return $('#list-of-users').html(); },
		    title: 'Add User <a class="save-user"><i class="glyphicon glyphicon-save pull-right text-success"></i></a>'
	    });
	    
	    $(document).on('show.bs.popover', 'a.add-user', function() { //close other popovers besides the active one
			$('a.add-user').not(this).popover('hide');
		});
		
		$(document).on('click', '.save-user', function() {
			var selectedOption = $(this).parents('.step-user').find('.list-of-users').find(':selected');
			var id = selectedOption.val();
			var userExists = false;
			$(this).parents('.step-user').children('span.workflow-user').each(function() {
				if (id == $(this).data('user-id')) {
					userExists = true;
					$(this).fadeTo(100, 0.1).fadeTo(200, 1.0);
					return;
				}
			});
			if (userExists) return; //exit function if user already exists
			var name = selectedOption.text();
			var tmpID = 'user-'+$.now();
			$(this).parents('.step-user').prepend('<span id="' + tmpID + '" class="workflow-user" data-user-id="'+ id +'"><a><i class="glyphicon glyphicon-minus-sign text-danger"></i></a>&nbsp;' + name + '</span>&nbsp;&nbsp;');
			$(this).parents('.step-user').find('.add-user').popover('hide');
			$.ajax({
				url: www_root + 'JobWorkflowStepUsers/add',
				type: 'post',
				cache: false,
				data: 'data[JobWorkflowStepsUser][user_id]=' + id + 
						'&data[JobWorkflowStepsUser][job_workflow_step_id]=' + $(this).parents('.step-row').attr('job-step-id')
			});
		});
		
		$(document).on('click', '.workflow-user > .glyphicon-minus-sign', function() { //remove workflow step user
			$.ajax({
				url: www_root + 'JobWorkflowStepUsers/delete',
				type: 'post',
				cache: false,
				data: 'data[JobWorkflowStepsUser][user_id]=' + $(this).parents('.workflow-user').data('user-id') +
						'&data[JobWorkflowStepsUser][job_workflow_step_id]=' + $(this).parents('.step-row').attr('job-step-id'),
				success: function(response) {
					var res = $.parseJSON(response);
				}
			});
			$(this).parents('.workflow-user').remove();
		});
		
		/*
		 * This first AJAX call is automatically called when the document is loaded - it retrieves the list of existing
		 * links between jobs and candidates and displays the information in a paginated manner. It relies on CandidatesJobs
		 * controller to provide the information and pagination support
		 */
		$.ajax({
			url: www_root + 'CandidatesJobs/index/' + jobId,
			type: 'ajax',
			success: function(data) {
				$('.links').html(data.content);
			}
		});
		
		/*
		 * When the list of existing links between jobs and candidates is displayed, if pagination exists then this block
		 * handles the pagination commands. Since AJAX pagination is tricky in CakePHP, we need to intercept every pagination
		 * link click and route those calls through AJAX. Also we link the corresponding DIV where the results need to be dusplayed
		 * via the 'load' command and finally parse the JSON object that is sent back from the server side
		 */
		$(document).on('click', 
						'div.links-pagination a', 
						function(evt) {
							evt.preventDefault();
							$('.links').load($(this).attr('href'), 
													function(data) { 
														var res = $.parseJSON(data); 
														$('.links').html(res.content);
							});
		});
		
		/*
		 * displays a list of available candidates to be linked along with already linked candidates
		 * the function compares candidates id's in the list alongside pre-linked candidates and
		 * preselects them for visual reference
		 */
		function showCandidateList(content) {
			$('#link-list').html(content);
			$('#link-list > #link-row').each(function() {
				if (isInArray(parseInt($(this).data('candidate-id')), preLinked)) {
					$(this).find('span.link').addClass('linked');
				}
			});
		}
		
		/*
		 * when the link candidate modal is shown, trigger an AJAX call to retrieve available candidate information
		 * and populate the modal dialog with the same. Also show the div-loader to block user interaction while the information
		 * is loading
		 */
		$(document).on('show.bs.modal', '#link-candidate', function (event) {
			$(this).find('div#div-loader').css('display', 'block'); //block screen with animation
			var button = $(event.relatedTarget); // Button that triggered the modal
			var linkList = $(this).find('div#link-list');
			linkList.html('');
			$.ajax({
				url: button.data('url'),
				type: 'ajax',
				success: function(data) {
					$('#link-candidate').find('div#div-loader').css('display', 'none'); //unblock
					showCandidateList(data.content);
				}
			});				
		});
		
		/*
		 * when a page number or page reference is clicked on the link list modal, the first step is to 
		 * load the data corresponding to that page via an AJAX call. Then to parse JSON object from the response
		 * and then render the content into the appropriate DIV
		 */
		$(document).on('click', 
						'div.pagination a', 
						function(evt) {
							evt.preventDefault();
							$('#link-list').load($(this).attr('href'), 
													function(data) { 
														var res = $.parseJSON(data); 
														showCandidateList(res.content);
							});
		});
		
		//show quickview information of link rows within the link candidate modal
		$(document).on('mouseenter', '#link-row', function() {
			$('#qv-title').text($(this).find('#name').text());
			$('#qv-created').text($(this).data('created'));
			$('#qv-contract-models').html($(this).data('contract-models'));
			$('#qv-contract-models').children('.contract-model').each(function () {
				$(this).removeClass('hidden');
			});
			$('#qv-tags').html($(this).data('tags'));
			$('#qv-tags').children('.tag').each(function () {
				$(this).removeClass('hidden');
			});
		});
		
		/*
		 * Link and unlink candidates & jobs
		 * When the user links or unlinks a candidate & job - the first AJAX call is made to do the actual linking / unlinking
		 * Upon successful completion of this, a second AJAX call is made to refresh the contents of the Link DIV - this is
		 * again reliant on AJAX / CakePHP pagination so a GET call is made via AJAX to the CandidatesJobs controller with jobID
		 * as a parameter and the returned view is loaded into the DIV - this way even page numbers are auto-corrected
		 * Also remove / add the corresponding CandidateId from the prelinked array to synch up display of pre-linked candidates in
		 * the link modal dialog
		 */
		$(document).on('click', '.link', function() {
			var candidateId = $(this).parents('div#link-row').data('candidate-id');
			if ( $( this ).hasClass( 'linked' ) ) {
				$( this ).removeClass( 'linked' );
				var index = $.inArray(candidateId, preLinked);
				preLinked.splice(index, 1); //remove the candidate ID from the prelink array
			} else {
				$( this ).addClass( 'linked' );
				preLinked.push(candidateId); //add candidate ID to prelink array
			}
			$.ajax({ 
				url: www_root + 'CandidatesJobs/link/' + candidateId + '/' + jobId,
				type: 'POST',
				data: {
					'data[CandidatesJob][job_id]': jobId,
					'data[CandidatesJob][candidate_id]': candidateId,
					'data[CandidatesJob][modified]': $(this).parents('div#link-row').data('modified')
				},
				cache: false,
				success: function(response) {
					alert(response);
					var res = $.parseJSON(response);
					var container = $(document).find('div.modal').find('div#quickview');
					showAjaxAlert(container, res.success, res.message);
					if (res.success) {
						//refresh the list of existing links in the view jobs page
						$('.links').load(www_root + 'CandidatesJobs/index/' + jobId, function(data) {
							var res = $.parseJSON(data);
							$('.links').html(res.content);
						});
					} else {
						//fail
					}
				}
			});
		});
	}  
	
	/*
	 * Edit Job
	 */
	if ((typeof view !== 'undefined') && (view == 'edit')) {
		$('#job-state').multiselect({
	    	checkboxName: 'state[]',
	    	nonSelectedText: 'State',
	    	numberDisplayed: 1
	    });
	    
	    $('#job-state').multiselect('select', $('.job-state').data('preselect'));
	    
	    $('#job-department').val($('.job-department').data('preselect'));
	    
	    $('#job-fill-by').datepicker();
	    
	    $('#job-contract-model').multiselect({
	    	nonSelectedText: 'Contract models',
	    	numberDisplayed: 3
	    });
	
	    $('#job-contract-model').multiselect('select', listToArray($('.job-contract-model').data('preselect').toString(), ','));
	    
	    $('#job-workflow').multiselect({
	    	nonSelectedText: 'Workflow Template',
	    	numberDisplayed: 1
	    });
	    
	    $('#job-workflow').multiselect('select', $('.job-workflow').data('preselect')); 
	    
	    $('#editor').wysiwyg();
	    
		$('#editor2').wysiwyg({
			toolbarSelector : '[data-role=editor2-toolbar]'
		});
		
		var surveyPreselect = $('.job-survey-option').data('preselect');
		// Switch slide buttons
	    $('.slider-button').click(function() {
	        if ($(this).hasClass("on")) {
	            $(this).removeClass('on').html($(this).data("off-text"));   
	            $('#save-btn').text('Save');
	            $('input#job-survey-indicator').attr('value', 'N');
	        } else {
	            $(this).addClass('on').html($(this).data("on-text"));
	            $('#save-btn').text('Next');
	            $('input#job-survey-indicator').attr('value', 'Y');
	        }
	    });
		
		if (surveyPreselect == "ON") {
	    	$(this).find('.slider-button').click();
	    }
		
		if (typeof jobTags !== 'undefined') {
			var tagsCount = jobTags.length; //jobTags is declared in edit.ctp
			for (var count=0; count<tagsCount; count++) {
				$('#edit-tags').tagsinput('add', { id: jobTags[count].id, name: jobTags[count].name });
			}
		}
		
		$('#add-ext-id-btn').on('click', function() {
			addHTML = '<div class="form-group row-top-buffer-xs ext-id-row">';
			addHTML = addHTML + '<div class="col-md-6"><input type="text" class="form-control" id="ext_name" placeholder="description" /></div>';
			addHTML = addHTML + '<div class="col-md-6"><input type="text" class="form-control" id="ext_id" placeholder="value" /></div>';	
			addHTML = addHTML + '</div> <!-- .form-group .ext-id-row -->';		
			$('.job-external-ids').append(addHTML);
		});
		
		$('#edit-job-form').bootstrapValidator({
			feedbackIcons: {
				valid : 'glyphicon glyphicon-ok',
				invalid : 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			submitHandler: function(validator, form, submitButton) {
	        	validator.defaultSubmit();
	    	},
			fields: {
				title: {
					selector: '#job-title',
					threshold: 1,
					validators: {
						notEmpty: {
							message: 'Job title cannot be empty'
						}
					}
				},
				city: {
					selector: '#job-city',
					threshold: 1,
					validators: {
						notEmpty: {
							message: 'Job city cannot be empty'
						}
					}
				}
			}
		});
		
		$('#save-btn').click(function(evt) {
			evt.preventDefault();
			var extIdArr = [];
			$('.ext-id-row').each(function() {
				var obj = {};
				obj.ext_name = $(this).find('#ext_name').val();
				obj.ext_id = $(this).find('#ext_id').val();
				extIdArr.push(obj);
			});
			$('input#job-ext-id').attr('value', JSON.stringify(extIdArr));
			$('input#job-description').attr('value', $('#editor').html());
			$('input#job-skills').attr('value', $('#editor2').html());
			$(this).parents('form').submit();
		});
		
		$('#cancel-btn').click(function() {//when cancel button is clicked
			//window.location.replace('viewJob.php?jobId=' + $(document).getUrlParam('jobId'));
		});
	}
});