<div class="optionGroups form">
<?php echo $this->Form->create('OptionGroup'); ?>
	<fieldset>
		<legend><?php echo __('Edit Option Group'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OptionGroup.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('OptionGroup.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Option Groups'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Option Choices'), array('controller' => 'option_choices', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Choice'), array('controller' => 'option_choices', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Questions'), array('controller' => 'questions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Question'), array('controller' => 'questions', 'action' => 'add')); ?> </li>
	</ul>
</div>
