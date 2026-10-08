$(function() {
	$(document).find('li#candidates-menu-item').addClass('active-top-menu');
	
	$(document).on("click", ".glyphicon-trash", function () { //change form-action to indicate object
		$('.modal-title').text('Archive Candidate');
		$('#archive-form').attr('action', www_root + 'candidates/delete/' + $(this).parent().data('row-id'));
	});
	
	$('#archive-btn').click(function() { //process archive button on arhive-modal
		$('#archive-form').submit();
	});
	
	/*
	 * Index View
	 */
	if ((typeof view !== 'undefined') && (view == 'index')) {
		$('.search-result-item').mouseenter(function() {
			$('#qv-title').html($(this).find('#candidate-name').html());
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
		
		$(document).on('click', '.star', function() {
			if ( $( this ).hasClass( 'checked' ) ) {
				$( this ).removeClass( 'checked' );
			} else {
				$( this ).addClass( 'checked' );
			}
			$.ajax({ 
				url:  www_root + 'users/toggleFavoriteCandidate/' + $(this).parents('div.search-result-item').data('row-id'),
				type: 'post',
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
	 * Edit View
	 */
	if ((typeof view !== 'undefined') && (view == 'edit')) {
		/*
		 * populate state dropdown list based on selected country using a JSON file
		 * The JSON file has an associative array of countries and states - the method below
		 * compares the selected country to identify list of states and then populates the 
		 * dropdown list after which the BootStrap multiselect plugin is applied for styling
		 */
		$.getJSON( www_root + 'countries.json', function(data) {
			var selectedCountry = $('#country').val();
			$.each( data, function( i, item ) {
			 	if (selectedCountry.toUpperCase() == item.Country.alpha_3.toUpperCase()) { //compare selected country
			 		var states = item.State;
			 		$.each( states, function( x, state ) { //iterate through states and populate list
			 			$('#state').append( '<option value="' + state.alpha_2 + '">' + state.name + '</option>' );
			 		});
			 	}
  			});
  			//apply the bootstrap multiselect plugin - 
  			//it is important to have this here to prevent multi-threading issues
  			$('#state').multiselect('select', $('.state').data('preselect'));
		});
		
		$('#contract-model').multiselect({
	    	nonSelectedText: 'Contract models',
	    	numberDisplayed: 3
	    });
	
	    $('#contract-model').multiselect('select', listToArray($('.contract-model').data('preselect').toString(), ','));
	    
		if (typeof cndTags !== 'undefined') {
			var tagsCount = cndTags.length; //jobTags is declared in edit.ctp
			for (var count=0; count<tagsCount; count++) {
				$('#edit-tags').tagsinput('add', { id: cndTags[count].id, name: cndTags[count].name });
			}
		}
		
		$('#edit-candidate-form').bootstrapValidator({
			feedbackIcons: {
				valid : 'glyphicon glyphicon-ok',
				invalid : 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			submitHandler: function(validator, form, submitButton) {
	        	validator.defaultSubmit();
	    	},
			fields: {
				name: {
					selector: '#candidate-name',
					threshold: 1,
					validators: {
						notEmpty: {
							message: 'Name cannot be empty'
						}
					}
				},
				phone: {
					selector: '#phone',
					threshold: 1,
					validators: {
						phone: {
							country: 'US',
							message: 'Phone number is invalid'
						}
					}
				},
				phone_alt: {
					selector: '#phone-alt',
					threshold: 1,
					validators: {
						phone: {
							country: 'US',
							message: 'Phone number is invalid'
						}
					}
				},
				email: {
					selector: '#email',
					threshold: 1,
					validators: {
						emailAddress: {
							message: 'Invalid email address'
						}
					}
				},
				city: {
					selector: '#city',
					threshold: 1,
					validators: {
						notEmpty: {
							message: 'City cannot be empty'
						}
					}
				}
			}
		});
		
		$('a.upload-link').on('click', function(e) {
			$('#file-upload').click();
			e.preventDefault();
		});
		
		$("#ajax_loader").ajaxStop(function() {
			$(this).hide();
        });
        
        $("#ajax_loader").ajaxStart(function() {
        	$(this).show();
        });   
		
		$( 'input:file' ).change(function() {
			var newFile = $( this ).val().split( '\\' ).pop();
			
			//$('#file-upload-form').submit(); // to debug ajax upload
			$('#ajax-loader').css('display', 'block');
			
			$('#file-upload-form').ajaxForm({
				success: function(response) {
					alert(response);
					$('#ajax-loader').css('display', 'none');
					var res = $.parseJSON(response); 
					if (res.success) {
						$('.upload-status').addClass('hidden');
						$('.upload-status > span').text(res.message);
						$('#file-name').val(res.filename);
						$('#file').attr('data', res.data);
						
						//reset form action to indicate a new file has been uploaded
						var action = $('#edit-candidate-form').attr('action'); 
						$('#edit-candidate-form').attr('action', action + '/Y');
						
						//populate form based on parsed resume
						if (res.parseSuccess) {
							$('#candidate-name').val(res.firstname + ' ' + res.lastname);
							$('#phone').val(res.phone);
							$('#phone-alt').val(res.altPhone);
							$('#email').val(res.email);
							$('#city').val(res.city);
							$('#state').multiselect('select', res.state);
						} else {
							$('.upload-status').removeClass('hidden');
							$.each(res.errors, function(idx, value) {
								$('.upload-status > span').text(value);
							});
						}
					} else {
						$('.upload-status').removeClass('hidden');
						$('.upload-status > span').text(res.message);
					}
				}
			}).submit();
		});
		
		$('#cancel-btn').click(function() {//when cancel button is clicked
			//window.location.replace('viewJob.php?jobId=' + $(document).getUrlParam('jobId'));
		});
	}
	
	/*
	 * Bulk Upload View
	 */
	if ((typeof view !== 'undefined') && (view == 'bulk-upload')) {
		$(document).on('change', '.btn-file :file', function() {
		  var input = $(this),
		      numFiles = input.get(0).files ? input.get(0).files.length : 1,
		      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		  input.trigger('fileselect', [numFiles, label]);
		  
		  $('#bulk-upload-candidate-form').submit();
		});
		
	    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
	        var input = $(this).parents('.input-group').find(':text'),
	            log = numFiles > 1 ? numFiles + ' files selected' : label;
	        
	        if( input.length ) {
	            input.val(log);
	        } else {
	            if( log ) alert(log);
	        }
	    });
	    
	    /*
	     * On clicking the process button, trigger an AJAX request to process all the selected files
	     * Also disable the process and cancel buttons - redirect the user to the index screen
	     */
	    $('#process-btn').on('click', function() {
	    	$('#cancel-btn').addClass('disabled');
	    	$(this).addClass('disabled');
	    	$.ajax({
	    		url: $('#bulk-process-form').attr('action'),
	    		type: 'post',
	    		data: $('#bulk-process-form').serialize(),
	    		success: function() {
	    			
	    		}
	    	});
	    });
	}
});