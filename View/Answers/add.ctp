<div class="answers form">
<?php echo $this->Form->create('Answer'); ?>
	<fieldset>
		<legend><?php echo __('Add Answer'); ?></legend>
	<?php
		echo $this->Form->input('question_id');
		echo $this->Form->input('answer_numeric');
		echo $this->Form->input('answer_text');
		echo $this->Form->input('option_choice_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Answers'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Questions'), array('controller' => 'questions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Question'), array('controller' => 'questions', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Option Choices'), array('controller' => 'option_choices', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Choice'), array('controller' => 'option_choices', 'action' => 'add')); ?> </li>
	</ul>
</div>
