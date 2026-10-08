$(document).ready(function() {
	$('#forgot-form').bootstrapValidator({
		feedbackIcons: {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		submitHandler: function(validator, form, submitButton) {
        	validator.defaultSubmit();
    	},
		fields: {
			email: {
				threshold: 1,
				validators: {
					notEmpty: {
						message: 'Invalid username'
					},
					emailAddress: {
						message: 'Invalid username'
					}
				}
			}
		}
	});
	
	$('#password-reset-form').bootstrapValidator({
		live: 'enabled',
		feedbackIcons: {
			valid : 'glyphicon glyphicon-ok',
			invalid : 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		submitHandler: function(validator, form, submitButton) {
        	validator.defaultSubmit();
    	},
		fields: {
			password: {
				validators: {
					notEmpty: {
						message: 'Invalid password'
					},
					stringLength: {
						min: 6,
						message: 'Invalid password'
					}
				}
			},
			confirmPassword: {
				validators: {
					notEmpty: {
						message: 'Invalid password'
					},
					identical: {
						field: 'password',
						message: 'Passwords do not match'
					}
				}
			},
			passwordHint: {
				validators: {
					notEmpty: {
						message: 'Invalid password hint'
					}
				}
			}
		}
	});
}); 
