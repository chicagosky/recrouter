<!-- Bootstrap X-editable -->
<?php $this->Html->css('bootstrap-editable', array('inline' => false)); ?>

<!-- Custom CSS -->
<?php $this->Html->css('users.dashboard.recrouter', array('inline' => false)); ?>
<?php $this->Html->css('groups.dashboard.recrouter', array('inline' => false)); ?>

<!-- Bootstrap X-editable -->
<?php $this->Html->script('bootstrap-editable', array('inline' => false, 'block' => 'scriptBottom')); ?>


<!-- Custom Javascript -->

<?php $this->assign('menuItem', 'groups'); ?>

<div class="hidden" id="group-id" data-group-id="<?php echo $group['Group']['id']; ?>"></div>

<div class="row">
<?php echo $this->Flash->render(); ?>
	<div class="col-md-9 user-box">
		<h6>List of Users</h6>
		<br />
		<table class="table table-hover">
			<thead>
				<tr>
					<th class="col-md-6">User</th>
					<th class="col-md-3" style="text-align: center;">Joined on</th>
					<!-- <th class="col-md-1" style="text-align: center;">Remove</th> -->
				</tr>
			</thead>
			<tbody>
			<?php 
			$users = $group['User'];
			foreach ($users as $user) {
			?>
				<tr data-row-id="<?php echo $user['id']; ?>">
					<td>
						<img src="http://placehold.it/45/#CDCDCD/FFF" class="img-responsive img-circle avatar" />
						<span class="name"><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></span>
						<span class="username"><?php echo $user['username']; ?></span>
					</td>
					<td style="text-align: center;"><?php echo $user['created']; ?></td>
					<!-- <td style="text-align: center;">
						<a href="#" data-toggle="modal" data-target="#archive-modal"><i class="glyphicon glyphicon-remove"></i></a>
					</td> -->
				</tr>
			<?php 
			} 
			?>
			</tbody>
		</table>
	</div>
	<div class="col-md-3 side-text">
		<h6>Instructions</h6>
		<p>Some sample instructions. Some sample instructions. Some sample instructions. Some sample instructions
			Some sample instructions</p>
	</div>
</div>

<script type="text/javascript">
	$(function() {
			$('.inline-page-title').editable({
				type: 'text',
				placement: 'bottom',
				title: 'Edit Group name',
				pk: $('#group-id').data('group-id'),
				url: www_root + 'groups/edit/' + $('#group-id').data('group-id'),
				
			});
			
			$(document).on("click", "#add-btn", function () { //change form-action to indicate object
				$('.modal-title').text('Archive Group');
				$('#archive-form').attr('action', www_root + 'groups/delete/' + $('#group-id').data('group-id'));
			});
			
			$(document).on('click', '#archive-btn', function() {
				$('#archive-form').submit();
			});
	});
</script>