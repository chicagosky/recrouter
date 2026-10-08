<div class="questions form">
<?php echo $this->Form->create('Question'); ?>
	<fieldset>
		<legend><?php echo __('Add Question'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('subtext');
		echo $this->Form->input('type');
		echo $this->Form->input('required');
		echo $this->Form->input('option_group_id');
		echo $this->Form->input('modifed');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Questions'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Option Groups'), array('controller' => 'option_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Group'), array('controller' => 'option_groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Answers'), array('controller' => 'answers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Answer'), array('controller' => 'answers', 'action' => 'add')); ?> </li>
	</ul>
</div>
