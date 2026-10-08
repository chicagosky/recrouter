<!-- Custom CSS -->
<?php $this->Html->css('groups.dashboard.recrouter', array('inline' => false)); ?>

<!-- Custom Javascript -->
<?php $this->Html->script('groups.dashboard.recrouter', array('inline' => false, 'block' => 'scriptBottom')); ?>

<?php $this->assign('menuItem', 'groups'); ?>

<div class="row">
<?php echo $this->Session->flash(); ?>

<?php 
$currentUser = AuthComponent::user();
foreach ($groups as $group) {
?>
	<div class="col-sm-3">
    	<div class="tile-stats tile-white-blue" data-row-id="<?php echo sizeof($group['Group']['id']); ?>">
    		<div class="icon"><i class="fa fa-5x fa-users"></i></div>
    		<div class="num"><?php echo sizeof($group['User']); ?></div>
    		<a href="<?php echo $this->webroot.'groups/view/'.$group['Group']['id']; ?>"><h3><?php echo $group['Group']['name']; ?></h3></a>
    		<!-- <p>Group details here</p> -->
      	</div>
    </div> 
<?php 
} 
?>
</div>

<div id="popover-content" class="hidden">
	<form role="form" method="post" action="<?php echo $this->webroot; ?>groups/add" id="add-form" class="form-inline" data-does-exist-url="<?php echo $this->webroot.'groups/doesExist'; ?>">
    	<input class="form-control" type="text" placeholder="Group name" name="data[Group][name]" id="group-name">
		<button type="submit" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-ok"></i></button>
		<!-- <button type="button" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-remove"></i></button> -->
	</form>
	<div id="validation-msg"></div>
</div>