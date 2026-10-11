<?php
$this->assign('title', 'Invite User - Recrouter.com');

/* Custom CSS */
$this->Html->css('login-register.recrouter', array('inline' => false));

/* Bootstrap Validator Javascript */
$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom'));

/* Custom Javascript */
$this->Html->script('invite.user.recrouter', array('inline' => false, 'block' => 'scriptBottom'));

$currentUser = AuthComponent::user();
?>
<div class="row">
	<div class="col-md-offset-2 col-sm-offset-2 col-md-5 col-sm-5 register-form">
		<form class="form-horizontal" id="invite-form" method="post" action="<?php echo $this->request->getAttribute('webroot') ?>users/add/invite">
			<input type="hidden" name="from_username" value="<?php echo $currentUser['username']; ?>" />
			<input type="hidden" name="data[User][password]" value="" /> <!-- dummy value -->
			<div class="row">
				<h2>Invite User</h2>
				<div class="line"></div>
			</div>
			<div class="form-group row-top-buffer-lg">
				<div class="col-md-12 col-sm-12">
					<input type="text" class="form-control input-lg" id="username" name="data[User][username]" placeholder="Email" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group">
				<div class="col-md-12 col-sm-12">
					<input type="text" class="form-control input-lg" id="first-name" name="data[User][firstname]" placeholder="First name" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group">
				<div class="col-md-12 col-sm-12">
					<input type="text" class="form-control input-lg" id="last-name" name="data[User][lastname]" placeholder="Last name" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group">
				<div class="col-md-12 col-sm-12">
					<label class="sr-only" for="groups">Groups</label>
					<select class="form-control select-lg" id="groups" name="data[User][group_id]">
					<?php foreach ($groups as $groupId => $groupName) { ?>
						<option value="<?php echo $groupId ?>"><?php echo $groupName ?></option>
					<?php } ?>
					</select>
				</div>
			</div> <!-- .form-group -->
			<div class="row row-bottom-buffer-lg">
				<div class="col-md-12 col-sm-12">
					<button id="register-btn" type="submit" class="btn btn-primary pull-right">Invite</button>
				</div>
			</div> <!-- .row -->
	    </form>
	</div> <!-- .col-md-offset-1 .col-md-5 -->
	<div class="col-md-offset-2 col-md-3 with-left-side-bar">
		<h6>Instructions</h6>
		<p>Some sample instructions. Some sample instructions. Some sample instructions. Some sample instructions
			Some sample instructions</p>
	</div>
</div> <!-- .row -->