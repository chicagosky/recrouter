<div class="panel panel-default"> 
	<div class="panel-heading">
		<div> <!-- row to ensure panel heading height adjusts -->
			<h3 class="panel-title inline-panel-title bold-panel-title">Assigned Workflow Steps &amp; Users</h3>&nbsp;&nbsp;
		</div> <!-- .row -->
	</div> <!-- .panel-heading -->
	<div class="panel-body view-job-workflow-users">
		<table class="table table-hover" data-workflow-id="">
			<thead>
				<tr>
					<th class="col-md-1">#</th>
					<th class="col-md-2">Step</th>
					<th class="col-md-7">Users</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($jobWorkflowSteps as $step) {
				echo $this->element('job_workflow_user_element', array('step' => $step)); 
			}
			?>
			</tbody>
		</table>
	</div> <!-- .panel-body -->
</div> <!-- .panel .panel-default --> 