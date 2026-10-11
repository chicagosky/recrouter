<?php
$this->assign('title', 'Password Reset - Recrouter.com');

$this->Html->css('login-register.recrouter', array('inline' => false));

$this->Html->script('password-reset.recrouter', array('inline' => false, 'block' => 'scriptBottom'));
$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom'));
?>
<div class="row">
	<div class="col-md-offset-4 col-sm-offset-4 col-md-4 col-sm-4 login-form">
		<form class="form-horizontal" id="forgot-form" action="<?php echo $this->request->getAttribute('webroot') ?>users/forgot" method="post">
			<div class="row">
				<h2>Sign In</h2>
				<div class="line"></div>
			</div>
			<div class="form-group row-top-buffer-lg">
				<div class="col-md-12 col-sm-12">
					<input type="text" name="username" class="form-control input-lg" id="username" placeholder="Email" />
				</div>
			</div> <!-- .form-group -->
			<div class="row row-top-buffer-md">
				<div class="col-md-3 col-sm-3">
					<a href="<?php echo $this->request->getAttribute('webroot') ?>users/register">Sign up</a>
				</div>
				<div class="col-md-4 col-sm-4">
					<button id="btn-login" type="submit" class="btn btn-primary pull-right">Reset Password</button>
				</div>
			</div> <!-- .row -->
			<div class="row row-top-buffer-lg">
				<div class="line"></div>
			</div>
	    </form>
	</div> <!-- .col-md-offset-3 .col-md-5 -->
</div> <!-- .row -->