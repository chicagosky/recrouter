<?php
//Jquery UI
$this->Html->css('jquery-ui.custom.min', array('inline' => false));

//Bootstrap X-editable
$this->Html->css('bootstrap-editable', array('inline' => false));

//custom CSS
$this->Html->css('workflow.settings.recrouter', array('inline' => false)); 

//Jquery UI
$this->Html->script('jquery-ui.custom.min', array('inline' => false, 'block' => 'scriptBottom'));

//Bootstrap X-editable
$this->Html->script('bootstrap-editable', array('inline' => false, 'block' => 'scriptBottom'));

//custom Javascipt
$this->Html->script('workflows.dashboard.recrouter', array('inline' => false, 'block' => 'scriptBottom'));

$this->assign('menuItem', 'workflows');
?>

<?php echo $this->Session->flash(); ?>

<?php foreach ($workflows as $workflow) { ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title inline-panel-title" 
			data-type="text" 
			data-pk="<?php echo $workflow['Workflow']['id']; ?>" 
			data-url="<?php echo $this->webroot.'Workflows/edit/'.$workflow['Workflow']['id']; ?>">
			<?php echo $workflow['Workflow']['name']; ?>
		</h3>&nbsp; 
		<a href="#" data-toggle="modal" data-target="#archive-modal"><i class="glyphicon glyphicon-trash"></i></a>&nbsp;&nbsp; 
		<a href="#" id="view-steps"><i class="glyphicon glyphicon-user"></i></a>
	</div>
	<div class="panel-body">
		<div class="row-top-buffer-md">
			<?php echo $workflow['Workflow']['description']; ?>
		</div>
		<div class="btn-group btn-breadcrumb row-top-buffer-md row-bottom-buffer-md workflow-steps">
    	<?php 
    	$steps = $workflow['WorkflowStep'];
    	foreach ($steps as $step) {
    		$tmpTitle = 'Edit Step <a href=&quot;'.$this->webroot.'WorkflowSteps/delete/'.$step['id'].'&quot;><i class=&quot;glyphicon glyphicon-minus-sign pull-right text-danger&quot;></i></a>';
		?>
			<a href="#" class="btn btn-info workflow-step" id="<?php echo $step['id']; ?>">
				<i class="<?php echo WorkflowStepType::$STEP_TYPES[$step['workflow_step_type_id']]['icon']; ?>"></i>
				<span class="step-name" data-type="text" 
										data-pk="<?php echo $step['id']; ?>" 
										data-url="<?php echo $this->webroot.'WorkflowSteps/edit/'.$step['id']; ?>" 
										data-modified="<?php echo $step['modified']; ?>" 
										data-title="<?php echo $tmpTitle; ?>">
					<?php echo $step['name']; ?>
				</span>
			</a>
		<?php
		}
    	?>
		</div>
		<?php if (sizeof($steps) < 8) { ?>
		<a href="#" class="add-workflow-step" data-workflow-id="<?php echo $workflow['Workflow']['id']; ?>" 
												data-max-sequence="<?php echo (sizeof($steps) - 1); ?>">
			<i class="glyphicon glyphicon-plus-sign"></i>
		</a>
		<?php } ?>
		</a>
		<div id="workflow-users" class="hidden">
			<table class="table table-hover" data-workflow-id="">
				<thead>
					<tr>
						<th class="col-md-1">#</th>
						<th class="col-md-2">Step</th>
						<th class="col-md-7">Users</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php } ?>

<div id="popover-content" class="hidden">
	<form role="form" method="post" action="<?php echo $this->webroot.'Workflows/add'; ?>" id="add-form" class="form-inline">
    	<input class="form-control" type="text" placeholder="Workflow name" name="data[Workflow][name]" id="workflow-name">
		<button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-ok"></i></button>
	</form>
	<div id="validation-msg"></div>
</div>

<div id="add-step-popover-content" class="hidden">
	<form role="form" method="post" action="<?php echo $this->webroot.'WorkflowSteps/add'; ?>" id="add-step-form" class="form-inline">
		<input type="hidden" id="workflow-id" name="data[WorkflowStep][workflow_id]" value="" />
		<input type="hidden" id="sequence" name="data[WorkflowStep][sequence]" value="" />
    	<div class="form-group row-top-buffer-md">
			<label class="sr-only" for="workflow-step-name">Workflow Step Name</label>
			<input type="text" class="form-control" id="workflow-step-name" name="data[WorkflowStep][name]" placeholder="Step name" />
		</div> <!-- .form-group -->
    	<div class="form-group row-top-buffer-lg">
			<label class="sr-only" for="step-type">Step Type</label>
			<select class="form-control" id="step-type" name="data[WorkflowStep][workflow_step_type_id]">
			<?php foreach (WorkflowStepType::$STEP_TYPES as $stepTypeId => $stepType) { ?>
				<option value="<?php echo $stepTypeId; ?>"><?php echo $stepType['name']; ?></option>
			<?php } ?>
			</select>
			<button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-ok"></i></button>
		</div> <!-- .form-group -->
	</form>
	<div id="validation-msg-2"></div>
</div>

<script id="user-row-template" type="text/x-tmpl">
	<tr class="step-row" step-id="">
		<td class="step-sequence"></td>
		<td class="workflow-step-name"></td>
		<td class="step-user"><a href="#" class="add-user"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
	</tr>
</script>

<div class="hidden" id="list-of-users"></div>