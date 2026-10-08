$(function() {
	$('#invite-form').bootstrapValidator({
		live: 'enabled',
		feedbackIcons: {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		submitHandler: function(validator, form, submitButton) {
        	//validator.defaultSubmit();
    	},
		fields: {
			username: {
				selector: '#username',
				threshold: 1,
				validators: {
					notEmpty: {
						message: 'Invalid email'
					},
					emailAddress: {
						message: 'Invalid email'
					},
					remote: {
						message: 'This email is already registered',
						url: www_root + 'users/doesExist'
					}
				}
			},
			firstName: {
				selector: '#first-name',
				threshold: 1,
				validators: {
					notEmpty: {
						message: 'Invalid first name'
					}
				}
			},
			lastName: {
				selector: '#last-name',
				threshold: 1,
				validators: {
					notEmpty: {
						message: 'Invalid last name'
					}
				}
			}
		}
	});
});
