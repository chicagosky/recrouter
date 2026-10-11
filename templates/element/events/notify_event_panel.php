<?php $message = $data['message']; ?>
<?php if (!isEmpty($message)) { ?>
<div class="message" data-message-id="<?php echo $message['id']; ?>" style="max-height:200px; overflow-y:auto;">
	<div class="row row-top-buffer-sm row-bottom-buffer-sm">
		<span class="col-md-2 col-xs-12 message-title">From:</span>
		<span class="col-md-10 col-xs-12"><?php echo $message['from']; ?></span>
	</div>
	<div class="row row-top-buffer-lg row-bottom-buffer-sm">
		<span class="col-md-2 col-xs-12 message-title">To:</span>
		<span class="col-md-10 col-xs-12"><?php echo $message['to']; ?></span>
	</div>
	<div class="row row-top-buffer-lg row-bottom-buffer-sm">
		<span class="col-md-2 col-xs-12 message-title">Cc:</span>
		<span class="col-md-10 col-xs-12"><?php echo $message['cc']; ?></span>
	</div>
	<div class="row row-top-buffer-lg row-bottom-buffer-sm">
		<span class="col-md-2 col-xs-12 message-title">Subject:</span>
		<span class="col-md-10 col-xs-12"><?php echo $message['subject']; ?></span>
	</div>
	<div class="row row-top-buffer-lg row-bottom-buffer-sm">
		<span class="col-md-2 col-xs-12 message-title">Message:</span>
		<span class="col-md-10 col-xs-12"><?php echo $message['message']; ?></span>
	</div>
</div>
<?php } ?>