<div class="contractModels view">
<h2><?php echo __('Contract Model'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($contractModel['ContractModel']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($contractModel['ContractModel']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($contractModel['ContractModel']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Archived'); ?></dt>
		<dd>
			<?php echo h($contractModel['ContractModel']['archived']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Contract Model'), array('action' => 'edit', $contractModel['ContractModel']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Contract Model'), array('action' => 'delete', $contractModel['ContractModel']['id']), array(), __('Are you sure you want to delete # %s?', $contractModel['ContractModel']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Contract Models'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Contract Model'), array('action' => 'add')); ?> </li>
	</ul>
</div>
