<? // Job Workflow Step User row ?>
<tr class="step-row" job-step-id="<?php echo $step['id']; ?>" step-id="<?php echo $step['workflow_step_id']; ?>">
	<td class="step-sequence"><?php echo $step['sequence']; ?></td>
	<td class="workflow-step-name">
		<i class="<?php echo WorkflowStepType::$STEP_TYPES[$step['WorkflowStep']['workflow_step_type_id']]['icon']; ?>"></i>&nbsp;
		<?php echo $step['WorkflowStep']['name']; ?>
	</td>
	<td class="step-user">
		<?php foreach ($step['User'] as $user) { ?>
		<span class="workflow-user" data-user-id="<?php echo $user['id']; ?>">
			<i class="glyphicon glyphicon-minus-sign text-danger"></i>&nbsp;<?php echo $user['firstname'].' '.$user['lastname']; ?>
		</span>&nbsp;&nbsp;
		<?php } ?>
		<a href="javascript://" class="add-user"><i class="glyphicon glyphicon-plus-sign"></i></a>
	</td>
</tr>