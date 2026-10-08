<div class="contractModels form">
<?php echo $this->Form->create('ContractModel'); ?>
	<fieldset>
		<legend><?php echo __('Add Contract Model'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('archived');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Contract Models'), array('action' => 'index')); ?></li>
	</ul>
</div>
