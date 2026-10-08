$(function() {
	$('#search-form input:radio').on('click', function() {
		$('#search-form').submit();
	});

	$('#search-btn').click(function() {
		$('#search-form').submit();
	});
	
	if (typeof favorites !== 'undefined') {
		$('div.search-result-item').each(function() {
			var currentRowId = $(this).data('row-id');
			if (0 <= favorites.indexOf(currentRowId)) {
				$(this).find('.star').addClass('checked');
			}
		});
	}
});