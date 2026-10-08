<div class="candidatesContractModels view">
<h2><?php echo __('Candidates Contract Model'); ?></h2>
	<dl>
		<dt><?php echo __('Candidate'); ?></dt>
		<dd>
			<?php echo $this->Html->link($candidatesContractModel['Candidate']['id'], array('controller' => 'candidates', 'action' => 'view', $candidatesContractModel['Candidate']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Contract Model'); ?></dt>
		<dd>
			<?php echo $this->Html->link($candidatesContractModel['ContractModel']['name'], array('controller' => 'contract_models', 'action' => 'view', $candidatesContractModel['ContractModel']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Candidates Contract Model'), array('action' => 'edit', $candidatesContractModel['CandidatesContractModel']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Candidates Contract Model'), array('action' => 'delete', $candidatesContractModel['CandidatesContractModel']['id']), array(), __('Are you sure you want to delete # %s?', $candidatesContractModel['CandidatesContractModel']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Candidates Contract Models'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Candidates Contract Model'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Candidates'), array('controller' => 'candidates', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Candidate'), array('controller' => 'candidates', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contract Models'), array('controller' => 'contract_models', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contract Model'), array('controller' => 'contract_models', 'action' => 'add')); ?> </li>
	</ul>
</div>
