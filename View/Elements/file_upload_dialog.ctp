<!-- File Upload Dialog -->
<div class="modal fade" id="file-upload-modal" tabindex="-1" role="dialog" aria-labelledby="file=upload-modal-label" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form role="form" action="#" method="post" id="file-upload-form">
				<div class="modal-header primary-modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="file-upload-modal-label">Upload File</h4>
				</div> <!-- .modal-header -->
				<div class="modal-body recrouter-modal-body">
					Please select file to upload 
					<span class="btn btn-default btn-file">
					    Browse <input type="file">
					</span>
				</div> <!-- .modal-body .recrouter-modal-body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="cancel-btn">
						Cancel
					</button>
					<button type="button" class="btn btn-primary" id="upload-btn">
						Upload
					</button>
				</div> <!-- .modal-footer -->
			</form> <!-- End of file-upload-form -->			
		</div> <!-- .modal-content -->
	</div> <!-- .modal-dialog -->
</div> <!-- .modal .fade -->
<!-- end of File Upload dialog -->