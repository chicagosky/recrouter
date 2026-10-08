<div class="contractModels form">
<?php echo $this->Form->create('ContractModel'); ?>
	<fieldset>
		<legend><?php echo __('Edit Contract Model'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('archived');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('ContractModel.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('ContractModel.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Contract Models'), array('action' => 'index')); ?></li>
	</ul>
</div>
