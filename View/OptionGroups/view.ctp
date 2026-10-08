<div class="optionGroups view">
<h2><?php echo __('Option Group'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($optionGroup['OptionGroup']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($optionGroup['OptionGroup']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($optionGroup['OptionGroup']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($optionGroup['OptionGroup']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Option Group'), array('action' => 'edit', $optionGroup['OptionGroup']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Option Group'), array('action' => 'delete', $optionGroup['OptionGroup']['id']), array(), __('Are you sure you want to delete # %s?', $optionGroup['OptionGroup']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Option Groups'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Group'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Option Choices'), array('controller' => 'option_choices', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Choice'), array('controller' => 'option_choices', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Questions'), array('controller' => 'questions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Question'), array('controller' => 'questions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Option Choices'); ?></h3>
	<?php if (!empty($optionGroup['OptionChoice'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Option Group Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($optionGroup['OptionChoice'] as $optionChoice): ?>
		<tr>
			<td><?php echo $optionChoice['id']; ?></td>
			<td><?php echo $optionChoice['name']; ?></td>
			<td><?php echo $optionChoice['option_group_id']; ?></td>
			<td><?php echo $optionChoice['created']; ?></td>
			<td><?php echo $optionChoice['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'option_choices', 'action' => 'view', $optionChoice['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'option_choices', 'action' => 'edit', $optionChoice['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'option_choices', 'action' => 'delete', $optionChoice['id']), array(), __('Are you sure you want to delete # %s?', $optionChoice['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Option Choice'), array('controller' => 'option_choices', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Questions'); ?></h3>
	<?php if (!empty($optionGroup['Question'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Subtext'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Required'); ?></th>
		<th><?php echo __('Option Group Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modifed'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($optionGroup['Question'] as $question): ?>
		<tr>
			<td><?php echo $question['id']; ?></td>
			<td><?php echo $question['name']; ?></td>
			<td><?php echo $question['subtext']; ?></td>
			<td><?php echo $question['type']; ?></td>
			<td><?php echo $question['required']; ?></td>
			<td><?php echo $question['option_group_id']; ?></td>
			<td><?php echo $question['created']; ?></td>
			<td><?php echo $question['modifed']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'questions', 'action' => 'view', $question['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'questions', 'action' => 'edit', $question['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'questions', 'action' => 'delete', $question['id']), array(), __('Are you sure you want to delete # %s?', $question['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Question'), array('controller' => 'questions', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
