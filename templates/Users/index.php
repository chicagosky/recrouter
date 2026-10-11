<?php
//Custom CSS
$this->Html->css('users.dashboard.recrouter', array('inline' => false));

//Custom Javascript
$this->Html->script('user.settings.recrouter', array('inline' => false, 'block' => 'scriptBottom'));

$this->assign('menuItem', 'users');
?>
<div class="row">
<?php echo $this->Flash->render(); ?>
	<table class="table table-hover">
		<thead>
			<tr>
				<th class="col-md-4">Name</th>
				<th class="col-md-3">Group</th>
				<th class="col-md-1" style="text-align: center;">Verified</th>
				<th class="col-md-1" style="text-align: center;">Edit</th>
				<th class="col-md-1" style="text-align: center;">Archive</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$currentUser = AuthComponent::user();
		foreach ($users as $user) {
			//set the verified icon based on the verified flag set on the user row
			$verifiedFlag = (0 == strcasecmp('Y', $user['User']['is_verified'])) ? 'glyphicon-ok' : 'glyphicon-remove';
		?>
			<tr data-row-id="<?php echo $user['User']['id']; ?>" data-first-name="<?php echo $user['User']['firstname']; ?>" 
								data-last-name="<?php echo $user['User']['lastname']; ?>" data-username="<?php echo $user['User']['username']; ?>" 
								data-group-id="<?php echo $user['Group']['id']; ?>">
				<td>
					<img src="http://placehold.it/45/#CDCDCD/FFF" class="img-responsive img-circle avatar" />
					<span class="name"><?php echo $user['User']['firstname'] . ' ' . $user['User']['lastname']; ?></span>
					<span class="username"><?php echo $user['User']['username']; ?></span>
				</td>
				<td>
					<?php echo $user['Group']['name']; ?>
				</td>
				<td style="text-align: center;">
					<i class="glyphicon <?php echo $verifiedFlag ?>"></i>
				</td>
				<td style="text-align: center;">
					<a href="#" data-toggle="modal" data-target="#edit-modal"><i class="glyphicon glyphicon-edit"></i></a>
				</td>
				<td style="text-align: center;">
					<a href="#" data-toggle="modal" data-target="#archive-modal"><i class="glyphicon glyphicon-trash"></i></a>
				</td>
			</tr>
		<?php 
		} 
		?>
		</tbody>
	</table>
</div>

<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" action="#" method="post" id="edit-form">
				<div class="modal-header blue-modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
					</button>
					<h4 class="modal-title" id="link-job-modal-label">Edit User</h4>
				</div> <!-- .modal-header -->
				<div class="modal-body recrouter-modal-body">
					<div class="form-group row-top-buffer-sm">
						<label class="sr-only" for="name">First Name</label>
						<input type="text" class="form-control input-lg" id="first-name" name="data[User][firstname]" placeholder="First name" />
					</div> <!-- .form-group -->
					<div class="form-group row-top-buffer-sm">
						<label class="sr-only" for="name">Last Name</label>
						<input type="text" class="form-control input-lg" id="last-name" name="data[User][lastname]" placeholder="Last name" />
					</div> <!-- .form-group -->
					<div class="form-group">
						<label class="sr-only" for="email">E-mail</label>
						<input type="email" class="form-control input-lg" id="email" name="data[User][username]" placeholder="e-mail" disabled />
					</div> <!-- .form-group -->
					<div class="form-group">
						<label class="sr-only" for="groups">Groups</label>
						<select class="form-control select-lg" id="groups" name="data[User][group_id]">
						<?php foreach ($groups as $groupId => $groupName) { ?>
							<option value="<?php echo $groupId ?>"><?php echo $groupName ?></option>
						<?php } ?>
						</select>
					</div> <!-- .form-group -->
				</div> <!-- .modal-body .recrouter-modal-body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="cancel-btn">
						Cancel
					</button>
					<button type="button" class="btn btn-primary" id="save-btn">
						Save
					</button>
				</div> <!-- .modal-footer -->
			</form> <!-- End of edit-user-form -->
		</div> <!-- .modal-content -->
	</div> <!-- .modal-dialog -->
</div> <!-- .modal .fade -->