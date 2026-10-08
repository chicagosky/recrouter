$(function() {
	/*
	 * -------------------
	 * SECTION: Workflow
	 * -------------------
	 */
	$('#add-btn').popover({ //Add Workflow
	    html : true,
	    placement: 'left',
	    title: 'Add New Workflow',
	    content: function() {
	      return $("#popover-content").html();
	    }
	});
	
	$('#add-btn').popover().on('shown.bs.popover', function() { //Validation for add workflow
		$('#add-form').bootstrapValidator({
			container: '#validation-msg',
			live: 'enabled',
			excluded: [':disabled'],
			feedbackIcons: {
				valid : 'glyphicon glyphicon-ok',
				invalid : 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			submitHandler: function(validator, form, submitButton) {
	        	//validator.defaultSubmit();
	    	},
			fields: {
				workflowname: {
					selector: '#workflow-name',
					threshold: 1,
					validators: {
						notEmpty: {
							message: 'Invalid workflow name'
						},
						remote: {
							message: 'This workflow name already exists',
							url: www_root + 'Workflows/doesExist'
						}
					}
				}
			}
		});
	});
	
	$('.inline-panel-title').editable({ //Edit workflow
		mode: 'inline',
		name: 'data[Workflow][name]',
		showbuttons: false,
		title: 'Edit workflow name'
	});	
	
	$(document).on("click", ".glyphicon-trash", function () { //Archive Workflow
		$('.modal-title').text('Archive Workflow');
		$('#archive-form').attr('action', www_root + 'Workflows/delete/' + $(this).parents('.panel-heading').find('.panel-title').data('pk'));
	});
	
	$('#archive-btn').click(function() { //process archive button on arhive-modal
		$('#archive-form').submit();
	});
	
	/*
	 * ---------------------
	 * SECTION:Workflow Step
	 * ---------------------
	 */
	$('.add-workflow-step').popover({ //Add Workflow step popover
	    html : true,
	    placement: function () {
	    	var width = $(window).width();
	    	return (width > 975) ? 'left' : (width < 600 ? 'top' : 'right' );
	    },
	    title: 'Add New Step',
	    content: function() {
	      return $("#add-step-popover-content").html();
	    }
	});
	
	$('.add-workflow-step').on('show.bs.popover', function() { //close other popovers besides the active one
		$('.add-workflow-step').not(this).popover('hide');
	});
	
	$('.add-workflow-step').on('shown.bs.popover', function() { //populate active workflow details in popover
		$('#add-step-form #workflow-id').val($(this).data('workflow-id'));
		$('#add-step-form #sequence').val($(this).data('max-sequence'));
	});

	$('.step-name').editable({ //Edit workflow step popover
		html: true,
		name: 'name',
		params: function(params) {
			params.pk = params.pk;
			params.name = params.name;
			params.value = params.value;
			params.modified = $(this).data('modified');
			return params;
		},
		showbuttons: false,
		success: function(response, newValue) {
			var link = $(this).parents('.panel').find('a#view-steps');
			if (link.hasClass('on')) {
				link.removeClass('on');
				$(this).parents('.panel').find('div#workflow-users').find('tbody').empty();
				link.click();
			}
		},
		validate: function(value) {
		    if($.trim(value) == '') {
		        return 'Step name cannot be blank';
		    }
		}
	});
	
	//setup sortable steps
	$('.workflow-steps a:first-child').addClass('not-sortable');
	$('.workflow-steps a:last-child').addClass('not-sortable');

    $( ".workflow-steps" ).sortable({ //make the workflow steps sortable
    	axis: 'x',
    	items: 'a:not(.not-sortable)',
    	update: function(evt, ui) {
    		var workflowId = $(this).parents('.panel').find('.panel-title').data('pk');    	
    		$('.workflow-steps a:first-child').removeClass('not-sortable'); //add elements back into sortable array
			$('.workflow-steps a:last-child').removeClass('not-sortable');	
    		var order = $(this).sortable('toArray');
    		$.ajax({
				url: www_root + 'WorkflowSteps/reorder/' + workflowId,
				data: 'reorderSeq=' + order,
				type: 'post',
				cache: false,
				success: function(response) {
					var res = $.parseJSON(response);
					if (res.success) { //remove sort on first and last element after successful save
						$('.workflow-steps a:first-child').addClass('not-sortable');
						$('.workflow-steps a:last-child').addClass('not-sortable');
					}
				}
			});
    	}
    });
    $( ".workflow-steps" ).disableSelection();
    
    /*
	 * ---------------------------
	 * SECTION:Workflow Step Users
	 * ---------------------------
	 */
    var usersForWorkflowStep = function() { //cache list of users in DOM
    	var dynHTML = null;
    	$.ajax({
			url: www_root + 'users/listOfUsers/',
			type: 'post',
			cache: false,
			success: function(response) {
				dynHTML = '<select class="form-control list-of-users" name="data[WorkflowStepUser][user_id]">'; //begin building select element
				var users = $.parseJSON(response); 
				for (var key in users) { //loop through and build option values
		    		dynHTML += '<option value="' + users[key].id + '">' + users[key].firstname + ' ' + users[key].lastname + '</option>';
		    	}
		    	dynHTML += '</select>'; //end of constructing select element
		    	$('#list-of-users').html(dynHTML);
			}
		});
    }();
    
    var addUserPopoverOptions = { //add workflow step user popover
	    html: true,
	    placement: 'top',
	    content: function() { return $('#list-of-users').html(); },
	    title: 'Add User <a class="save-user"><i class="glyphicon glyphicon-ok-sign pull-right text-success"></i></a>'
    }; 
    
    $('a#view-steps').on('click', function() { //meeds cleanup
    	var usersDiv = $(this).parents('.panel').find('div#workflow-users');
    	if ($(this).hasClass('on')) {
    		$(this).removeClass('on');
    		usersDiv.find('tbody').empty();
    		usersDiv.slideUp();
    		return;
    	} else {
    		$(this).addClass('on');
    		usersDiv.removeClass('hidden');
    		usersDiv.slideDown();
    	}
    	var workflowId = $(this).parent().find('h3').data('pk');
    	usersDiv.find('.table').data('workflow-id', workflowId);
    	$.ajax({
    		url: www_root + 'WorkflowSteps/index/' + workflowId,
    		type: 'post',
    		cache: false,
    		success: function(response) {
    			var steps = $.parseJSON(response);
    			for (var key in steps) {
    				var rowTemplate = $('#user-row-template').html();
    				var curr = $(rowTemplate).appendTo(usersDiv.find('tbody')); //reference to current row added dynamically
    				$(curr[0]).attr('step-id', steps[key].WorkflowStep.id); //set workflow step id
    				curr.find('.step-sequence').text(steps[key].WorkflowStep.sequence); //set workflow step sequence 
    				curr.find('.workflow-step-name').text(steps[key].WorkflowStep.name); //set workflow step name 
    				curr.find('.add-user').popover(addUserPopoverOptions);
    				var users = steps[key].WorkflowStepUser;
    				for (var i=0; i<users.length; i++) { //add users associated with this step
    					var name = users[i].User.firstname + ' ' + users[i].User.lastname;
    					curr.find('.step-user').prepend('<span class="workflow-user" data-pk="' + users[i].id + '" data-user-id="' + users[i].User.id 
    														+ '"><i class="glyphicon glyphicon-minus-sign text-danger"></i>&nbsp;' + name + '</span>&nbsp;&nbsp;');
    				}
    			}
    		}
    	});
    });
    
    $(document).on('show.bs.popover', '.add-user', function() { //close other popovers besides the active one
		$('.add-user').not(this).popover('hide');
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
		$(this).parents('.step-user').prepend('<span id="' + tmpID + '" class="workflow-user" data-pk="" data-user-id="'+ id +'"><a><i class="glyphicon glyphicon-minus-sign text-danger"></i></a>&nbsp;' + name + '</span>&nbsp;&nbsp;');
		$(this).parents('.step-user').find('.add-user').popover('hide');
		$.ajax({
			url: www_root + 'WorkflowStepUsers/add/'+id,
			type: 'post',
			cache: false,
			data: 'data[workflow_step_id]=' + $(this).parents('tr.step-row').attr('step-id') + 
					'&data[workflow_id]=' + $(this).parents('.table').data('workflow-id'),
			success: function(response) {
				var res = $.parseJSON(response);
				if (res.success) {
					$(document).find('span#'+tmpID).attr('data-pk', res.workflowStepUserId);
				}
			}
		});
	});
	
   $(document).on('click', '.workflow-user > .glyphicon-minus-sign', function() { //remove workflow step user
		$.ajax({
			url: www_root + 'WorkflowStepUsers/delete/' + $(this).parent().data('pk'),
			type: 'post'
		});
		$(this).parent().remove();
	});
});