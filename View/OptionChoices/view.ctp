<div class="optionChoices view">
<h2><?php echo __('Option Choice'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($optionChoice['OptionChoice']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($optionChoice['OptionChoice']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Option Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($optionChoice['OptionGroup']['name'], array('controller' => 'option_groups', 'action' => 'view', $optionChoice['OptionGroup']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($optionChoice['OptionChoice']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($optionChoice['OptionChoice']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Option Choice'), array('action' => 'edit', $optionChoice['OptionChoice']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Option Choice'), array('action' => 'delete', $optionChoice['OptionChoice']['id']), array(), __('Are you sure you want to delete # %s?', $optionChoice['OptionChoice']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Option Choices'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Choice'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Option Groups'), array('controller' => 'option_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Option Group'), array('controller' => 'option_groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Answers'), array('controller' => 'answers', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Answer'), array('controller' => 'answers', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Answers'); ?></h3>
	<?php if (!empty($optionChoice['Answer'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Question Id'); ?></th>
		<th><?php echo __('Answer Numeric'); ?></th>
		<th><?php echo __('Answer Text'); ?></th>
		<th><?php echo __('Option Choice Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($optionChoice['Answer'] as $answer): ?>
		<tr>
			<td><?php echo $answer['id']; ?></td>
			<td><?php echo $answer['question_id']; ?></td>
			<td><?php echo $answer['answer_numeric']; ?></td>
			<td><?php echo $answer['answer_text']; ?></td>
			<td><?php echo $answer['option_choice_id']; ?></td>
			<td><?php echo $answer['created']; ?></td>
			<td><?php echo $answer['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'answers', 'action' => 'view', $answer['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'answers', 'action' => 'edit', $answer['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'answers', 'action' => 'delete', $answer['id']), array(), __('Are you sure you want to delete # %s?', $answer['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Answer'), array('controller' => 'answers', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
