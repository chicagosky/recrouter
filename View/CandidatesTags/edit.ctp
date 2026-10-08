<div class="candidatesTags form">
<?php echo $this->Form->create('CandidatesTag'); ?>
	<fieldset>
		<legend><?php echo __('Edit Candidates Tag'); ?></legend>
	<?php
		echo $this->Form->input('candidate_id');
		echo $this->Form->input('tag_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('CandidatesTag.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('CandidatesTag.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Candidates Tags'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Candidates'), array('controller' => 'candidates', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Candidate'), array('controller' => 'candidates', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tags'), array('controller' => 'tags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('controller' => 'tags', 'action' => 'add')); ?> </li>
	</ul>
</div>
