<div class="candidatesContractModels index">
	<h2><?php echo __('Candidates Contract Models'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('candidate_id'); ?></th>
			<th><?php echo $this->Paginator->sort('contract_model_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($candidatesContractModels as $candidatesContractModel): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($candidatesContractModel['Candidate']['id'], array('controller' => 'candidates', 'action' => 'view', $candidatesContractModel['Candidate']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($candidatesContractModel['ContractModel']['name'], array('controller' => 'contract_models', 'action' => 'view', $candidatesContractModel['ContractModel']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $candidatesContractModel['CandidatesContractModel']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $candidatesContractModel['CandidatesContractModel']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $candidatesContractModel['CandidatesContractModel']['id']), array(), __('Are you sure you want to delete # %s?', $candidatesContractModel['CandidatesContractModel']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Candidates Contract Model'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Candidates'), array('controller' => 'candidates', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Candidate'), array('controller' => 'candidates', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Contract Models'), array('controller' => 'contract_models', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contract Model'), array('controller' => 'contract_models', 'action' => 'add')); ?> </li>
	</ul>
</div>
