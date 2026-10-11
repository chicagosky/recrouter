<!-- easyTree CSS -->
<?php $this->Html->css('easyTree', array('inline' => false)); ?>

<!-- Custom CSS -->
<?php $this->Html->css('departments.dashboard.recrouter', array('inline' => false)); ?>

<!-- custom Javascript -->
<?php $this->Html->script('department.settings.recrouter', array('inline' => false, 'block' => 'scriptBottom')); ?>

<?php $this->assign('menuItem', 'departments'); ?>

<div class="row">
	<div class="easy-tree col-md-9">
	<?php $treeOptions = array('id' => 'departments', 'model' => 'Department', 'element' => 'DepartmentTreeNode'); ?>
	<?php echo $this->Tree->generate($departments, $treeOptions); ?>
	</div>
	<div class="col-md-3 side-text">
		<?php echo $this->Flash->render(); ?>
		<h6>Instructions</h6>
		<p>Some sample instructions. Some sample instructions. Some sample instructions. Some sample instructions
			Some sample instructions</p>
	</div>
</div>

<!-- Add Department Popover -->
<div id="add-popover-content" class="hidden">
	<form role="form" method="post" action="<?php echo $this->request->getAttribute('webroot'); ?>departments/add" id="add-node-form" class="form-inline">
		<input type="hidden" name="data[Department][parent_id]" id="add-parent-id" value="" />
    	<input class="form-control" type="text" placeholder="Department name" name="data[Department][name]" id="department-name">
		<button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-ok"></i></button>
	</form>
	<div id="validation-msg"></div>
</div>
<!-- end of Add Department popover -->

<!-- Edit Department Popover -->
<div id="edit-popover-content" class="hidden">
	<form role="form" method="post" action="<?php echo $this->request->getAttribute('webroot'); ?>departments/edit" id="edit-node-form" class="form-inline">
		<div class="row">
			<div class="col-sm-9">
				<div class="form-group row-top-buffer-md">
					<label class="sr-only" for="dept-name">Department Name</label>
					<input type="text" class="form-control" id="dept-name" name="data[Department][name]" placeholder="Department name" />
				</div> <!-- .form-group -->
			</div>
			<div class="col-sm-3"></div>
		</div>
		<div class="row row-top-buffer-md">
			<div class="col-sm-9">
				<div class="form-group">
					<label class="sr-only" for="parent-dept">Parent Department</label>
					<select class="form-control" id="parent-dept" name="data[Department][parent_id]">
					<?php foreach ($allDepartments as $deptId => $deptName) { ?>
						<option value="<? echo $deptId ?>"><? echo $deptName ?></option>
					<?php } ?>
					</select>
				</div> <!-- .form-group -->
			</div>
			<div class="col-sm-3">
				<button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-ok"></i></button>
			</div>
		</div>
	</form>
</div>
<!-- end of Edit Department popover -->