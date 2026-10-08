$(function() {
	$(document).on("click", ".glyphicon-remove", function () { //change form-action to indicate object
		$('.modal-title').text('Archive User');
		$('#archive-form').attr('action', www_root + 'users/delete/' + $(this).parents('tr').data('row-id'));
	});
	
	$('#archive-btn').click(function() {
		$('#archive-form').submit();
	});
});
