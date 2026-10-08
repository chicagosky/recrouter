<div class="optionGroups index">
	<h2><?php echo __('Option Groups'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($optionGroups as $optionGroup): ?>
	<tr>
		<td><?php echo h($optionGroup['OptionGroup']['id']); ?>&nbsp;</td>
		<td><?php echo h($optionGroup['OptionGroup']['name']); ?>&nbsp;</td>
		<td><?php echo h($optionGroup['OptionGroup']['created']); ?>&nbsp;</td>
		<td><?php echo h($optionGroup['OptionGroup']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $optionGroup['OptionGroup']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $optionGroup['OptionGroup']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $optionGroup['OptionGroup']['id']), array(), __('Are you sure you want to delete # %s?', $optionGroup['OptionGroup']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Option Group'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Option Choices'), array('controller' => 'option_choices', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Choice'), array('controller' => 'option_choices', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Questions'), array('controller' => 'questions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Question'), array('controller' => 'questions', 'action' => 'add')); ?> </li>
	</ul>
</div>
