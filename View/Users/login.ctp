<?php
$this->assign('title', 'Login - Recrouter.com');

$this->Html->css('login-register.recrouter', array('inline' => false));

$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom'));
$this->Html->script('login-register.recrouter', array('inline' => false, 'block' => 'scriptBottom'));
?>
<div class="row">
	<div class="col-md-offset-4 col-sm-offset-4 col-md-4 col-sm-4 login-form">
		<form class="form-horizontal" id="login-form" action="<?php echo $this->webroot.'users/login' ?>" method="post">
			<div class="row">
				<h2>Sign In</h2>
				<div class="line"></div>
			</div>
			<div class="form-group row-top-buffer-lg">
				<div class="col-md-12 col-sm-12">
					<input type="text" name="data[User][username]" class="form-control input-lg" id="username" placeholder="Email" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-12 col-sm-12">
					<input type="password" name="data[User][password]" class="form-control input-lg" id="password" placeholder="Password" />
				</div>
			</div> <!-- .form-group -->
<?php if ($this->Session->check('Message.auth')) { ?>
			<div class="alert alert-danger row-bottom-buffer-md" id="loginFail">
				<?php echo $this->Session->flash('auth'); ?>
			</div>
<?php } ?>
			<div class="row row-top-buffer-md">
				<div class="col-md-5 col-sm-5">
					<a class="forgotten-password-link" href="<?php echo $this->webroot ?>users/forgot">Forgot password?</a>
				</div>
				<div class="col-md-3 col-sm-3">
					<a href="<?php echo $this->webroot ?>users/register">Sign up</a>
				</div>
				<div class="col-md-4 col-sm-4">
					<button id="btn-login" type="submit" class="btn btn-primary pull-right">Sign in</button>
				</div>
			</div> <!-- .row -->
			<div class="row row-top-buffer-lg">
				<div class="line"></div>
			</div>
			<div class="row row-top-buffer-lg row-bottom-buffer-md social pull-right">
				<div class="col-md-12">
					<a class="btn btn-info" href="#">
						<i class="glyphicon glyphicon-info-sign"></i> LinkedIn
					</a>
				</div>
			</div>
			<input type="hidden" name="action" value="login" />
	    </form>
	</div> <!-- .col-md-offset-3 .col-md-5 -->
</div> <!-- .row -->