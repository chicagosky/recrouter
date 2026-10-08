<div class="candidatesTags view">
<h2><?php echo __('Candidates Tag'); ?></h2>
	<dl>
		<dt><?php echo __('Candidate'); ?></dt>
		<dd>
			<?php echo $this->Html->link($candidatesTag['Candidate']['id'], array('controller' => 'candidates', 'action' => 'view', $candidatesTag['Candidate']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tag'); ?></dt>
		<dd>
			<?php echo $this->Html->link($candidatesTag['Tag']['name'], array('controller' => 'tags', 'action' => 'view', $candidatesTag['Tag']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Candidates Tag'), array('action' => 'edit', $candidatesTag['CandidatesTag']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Candidates Tag'), array('action' => 'delete', $candidatesTag['CandidatesTag']['id']), array(), __('Are you sure you want to delete # %s?', $candidatesTag['CandidatesTag']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Candidates Tags'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Candidates Tag'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Candidates'), array('controller' => 'candidates', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Candidate'), array('controller' => 'candidates', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tags'), array('controller' => 'tags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('controller' => 'tags', 'action' => 'add')); ?> </li>
	</ul>
</div>
