<?php $department = $data['Department']; ?>
<div class="btn-group" data-parent-id="<?php echo $department['parent_id']; ?>" data-node-id="<?php echo $department['id']; ?>">
	<button type="button" class="btn  btn-flat white" id="node-name"><?php echo $department['name']; ?></button>
	<button type="button" class="btn  btn-flat white dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu node-menu" role="menu">
		<li class="node-menu-item">
			<a href="javascript://" class="add-menu-item"><i class="glyphicon glyphicon-plus"></i> Add Child</a>
		</li>
		<li class="node-menu-item">
			<a href="javascript://" class="edit-menu-item"><i class="glyphicon glyphicon-edit"></i> Edit Node</a>
		</li>
		<?php if ($department['parent_id']) { //show delete option only if it is not root node ?>
		<li class="node-menu-item">
			<a href="javascript://" class="delete-menu-item" data-toggle="modal" data-target="#archive-modal"><i class="glyphicon glyphicon-trash"></i> Delete Node</a>
		</li>
		<?php } ?>
	</ul>
</div>
