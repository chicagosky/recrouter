<?php
$this->assign('title', 'Password Reset - Recrouter.com');

$this->Html->css('login-register.recrouter', array('inline' => false));

$this->Html->script('password-reset.recrouter', array('inline' => false, 'block' => 'scriptBottom'));
$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom'));
?>
<div class="row">
	<div class="col-md-offset-4 col-sm-offset-4 col-md-4 col-sm-4 login-form">
		<form class="form-horizontal" id="password-reset-form" action="<?php echo $this->webroot ?>users/reset/<?php echo $userId ?>" method="post">
			<div class="row">
				<h2>Password Reset</h2>
				<div class="line"></div>
			</div>
			<div class="form-group row-top-buffer">
				<div class="col-md-12 col-sm-12">
					<input type="text" name="username" class="form-control input-lg" id="username" placeholder="Email" value="<?php echo $username; ?>" disabled/>
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer">
				<div class="col-md-12 col-sm-12">
					<input type="password" name="password" class="form-control input-lg" id="password" placeholder="Password" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-12 col-sm-12">
					<input type="password" name="confirmPassword" class="form-control input-lg" id="confirmPassword" placeholder="Retype Password" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-12 col-sm-12">
					<input type="text" name="hint" class="form-control input-lg" id="passwordHint" placeholder="Hint" />
				</div>
			</div> <!-- .form-group -->
			<div class="row row-top-buffer-md">
				<div class="col-md-3 col-sm-3">
					<a href="<?php echo $this->webroot ?>users/login">Sign In</a>
				</div>
				<div class="col-md-4 col-sm-4">
					<button id="pwd-reset-btn" type="submit" class="btn btn-primary pull-right">Reset Password</button>
				</div>
			</div> <!-- .row -->
			<div class="row row-top-buffer-lg">
				<div class="line"></div>
			</div>
	    </form>
	</div> <!-- .col-md-offset-3 .col-md-5 -->
</div> <!-- .row -->