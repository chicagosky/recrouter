<?php 
/*
 * This is the modal dialog to link candidates with jobs. This modal is invoked from View Jobs screen and
 * displays a list of available candidates for the user to associate with the current job. The modal has two tabs
 * (future), one which displays all available candidates and the other (future) which shows a list of recommended
 * and ranked candidates
 */
?>
<!-- Modal -->
<div class="modal fade" id="link-candidate" tabindex="-1" role="dialog" aria-labelledby="link-candidate-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header blue-modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="link-candidate-label">Link Candidate(s)</h4>
			</div>
			<div class="modal-body recrouter-modal-body container-fluid">
				<div id="div-loader" style="display: none;">
				    <img id="loading" src="<?php echo $this->webroot.'img/loading2.gif'; ?>"></img>
				</div>
				<div class="col-md-8" style="max-height: 380px; overflow-y: scroll;">
					<div id="link-list">
						<!-- list of candidates -->
					</div> 
				</div> <!-- .col-md-6 -->
				<div class="col-md-4" id="quickview"> 
					<!-- Quickview section -->
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="row-top-buffer-xl quickview-title" id="qv-title">Candidate Name</div>
							<div class="row-top-buffer-xl quickview-row-heading">Contract Models</div>
							<div class="row-top-buffer-md" id="qv-contract-models"></div>
							<div class="row-top-buffer-lg quickview-row-heading">Created on</div>
							<div class="row-top-buffer-md" id="qv-created"></div>
							<div class="row-top-buffer-lg quickview-row-heading">Tags</div>
							<div class="row-top-buffer-md row-bottom-buffer-md" id="qv-tags"></div>
						</div>
					</div>
					<div class="ajax-message alert hidden"></div>
				</div> <!-- .col-md-6 -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>