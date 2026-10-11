<div class="optionChoices index">
	<h2><?php echo __('Option Choices'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('option_group_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($optionChoices as $optionChoice): ?>
	<tr>
		<td><?php echo h($optionChoice['OptionChoice']['id']); ?>&nbsp;</td>
		<td><?php echo h($optionChoice['OptionChoice']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($optionChoice['OptionGroup']['name'], array('controller' => 'option_groups', 'action' => 'view', $optionChoice['OptionGroup']['id'])); ?>
		</td>
		<td><?php echo h($optionChoice['OptionChoice']['created']); ?>&nbsp;</td>
		<td><?php echo h($optionChoice['OptionChoice']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $optionChoice['OptionChoice']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $optionChoice['OptionChoice']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $optionChoice['OptionChoice']['id']), array(), __('Are you sure you want to delete # %s?', $optionChoice['OptionChoice']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Option Choice'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Option Groups'), array('controller' => 'option_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Group'), array('controller' => 'option_groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Answers'), array('controller' => 'answers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Answer'), array('controller' => 'answers', 'action' => 'add')); ?> </li>
	</ul>
</div>
