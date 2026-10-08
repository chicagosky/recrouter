<div class="optionChoices form">
<?php echo $this->Form->create('OptionChoice'); ?>
	<fieldset>
		<legend><?php echo __('Edit Option Choice'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('option_group_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OptionChoice.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('OptionChoice.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Option Choices'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Option Groups'), array('controller' => 'option_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Group'), array('controller' => 'option_groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Answers'), array('controller' => 'answers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Answer'), array('controller' => 'answers', 'action' => 'add')); ?> </li>
	</ul>
</div>
