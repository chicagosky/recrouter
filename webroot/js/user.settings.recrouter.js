$(function() {
	
	$(document).on("click", ".glyphicon-edit", function () {
		var parentElement = $(this).parents('tr');
		$('.modal-body #first-name').val( parentElement.data('first-name') );
		$('.modal-body #last-name').val( parentElement.data('last-name') );
		$('.modal-body #email').val( parentElement.data('username') );
		$('.modal-body #groups').val(parentElement.data('group-id'));
		$('#edit-form').attr('action', www_root + 'users/edit/' + parentElement.data('row-id'));
	});
	
	$(document).on("click", ".glyphicon-trash", function () { //change form-action to indicate object
		$('.modal-title').text('Archive User');
		$('#archive-form').attr('action', www_root + 'users/delete/' + $(this).parents('tr').data('row-id'));
	});
	
	$('#archive-btn').click(function() {
		$('#archive-form').submit();
	});
	
	$('#save-btn').click(function(evt) {
		$('#edit-form').submit();
	});
});