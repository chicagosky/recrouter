$(document).ready(function() {
	$('#login-form').bootstrapValidator({
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
				selector: '#username',
				threshold: 1,
				validators: {
					notEmpty: {
						message: 'Invalid username'
					},
					emailAddress: {
						message: 'Invalid username'
					}
				}
			},
			password: {
				threshold: 1,
				validators: {
					notEmpty: {
						message: 'Invalid password'
					},
					stringLength: {
						min: 6,
						message: 'Invalid password'
					}
				}
			}
		}
	});
	
	/* Registration Page */
	
	$('#register-form').bootstrapValidator({
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
			username: {
				selector: '#email',
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
						url: $('#register-form').data('does-exist-url')
					}
				}
			},
			password: {
				selector: '#password',
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
				selector: '#confirm-password',
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
				selector: '#password-hint',
				validators: {
					notEmpty: {
						message: 'Invalid password hint'
					}
				}
			},
			companyName: {
				selector: '#company-name',
				threshold: 1,
				validators: {
					notEmpty: {
						message: 'Invalid organization name'
					}
				}
			},
			firstName: {
				threshold: 1,
				validators: {
					notEmpty: {
						message: 'Invalid first name'
					}
				}
			},
			lastName: {
				threshold: 1,
				validators: {
					notEmpty: {
						message: 'Invalid last name'
					}
				}
			},
			agreement: {
				validators: {
					choice: {
						min: 1,
						message: 'You must accept the Terms & Conditions to proceed'
					}
				}
			}
		}
	});
}); 
