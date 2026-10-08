$(function() {
	
	$('#add-btn').popover({ 
	    html : true,
	    placement: 'left',
	    title: 'Add New Group',
	    content: function() {
	      return $("#popover-content").html();
	    }
	});
	
	$('#add-btn').popover().on('shown.bs.popover', function() {
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
				groupname: {
					selector: '#group-name',
					threshold: 1,
					validators: {
						notEmpty: {
							message: 'Invalid group name'
						},
						remote: {
							message: 'This group name already exists',
							url: $('#add-form').data('does-exist-url')
						}
					}
				}
			}
		});
	});
});