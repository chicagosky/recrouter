<div class="candidatesContractModels form">
<?php echo $this->Form->create('CandidatesContractModel'); ?>
	<fieldset>
		<legend><?php echo __('Edit Candidates Contract Model'); ?></legend>
	<?php
		echo $this->Form->input('candidate_id');
		echo $this->Form->input('contract_model_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('CandidatesContractModel.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('CandidatesContractModel.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Candidates Contract Models'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Candidates'), array('controller' => 'candidates', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Candidate'), array('controller' => 'candidates', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contract Models'), array('controller' => 'contract_models', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contract Model'), array('controller' => 'contract_models', 'action' => 'add')); ?> </li>
	</ul>
</div>
