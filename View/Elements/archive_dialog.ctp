<!-- Archive Dialog -->
<div class="modal fade" id="archive-modal" tabindex="-1" role="dialog" aria-labelledby="archive-modal-label" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<form role="form" action="#" method="post" id="archive-form">
				<!-- common entity id across all setting views to store the id of the object being archived -->
				<input type="hidden" id="row-id" name="id" value="" />
				<div class="modal-header danger-modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="archive-modal-label"></h4>
				</div> <!-- .modal-header -->
				<div class="modal-body recrouter-modal-body">
					Are you sure you want to archive the selected row? This action cannot be undone.
				</div> <!-- .modal-body .recrouter-modal-body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="cancel-btn">
						Cancel
					</button>
					<button type="button" class="btn btn-danger" id="archive-btn">
						Archive
					</button>
				</div> <!-- .modal-footer -->
			</form> <!-- End of edit-user-form -->			
		</div> <!-- .modal-content -->
	</div> <!-- .modal-dialog -->
</div> <!-- .modal .fade -->
<!-- end of Archive dialog -->