<meta name="csrfToken" content="<?= h($this->request->getAttribute('csrfToken')) ?>">
<script>
if (window.jQuery) {
	var csrfToken = $('meta[name="csrfToken"]').attr('content');
	$.ajaxSetup({
		headers: {
			'X-CSRF-Token': csrfToken
		}
	});
	$(function () {
		$('form').each(function () {
			if ((this.method || 'get').toLowerCase() === 'get') {
				return;
			}
			if (!$(this).find('input[name="_csrfToken"]').length) {
				$(this).append($('<input>', { type: 'hidden', name: '_csrfToken', value: csrfToken }));
			}
		});
	});
}
</script>
