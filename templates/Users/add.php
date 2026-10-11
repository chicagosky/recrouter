<?php
$this->assign('title', 'Register - Recrouter.com');

$this->Html->css('login-register.recrouter', array('inline' => false)); //Custom CSS

$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom')); // Bootstrap Validator Javascript
$this->Html->script('login-register.recrouter', array('inline' => false, 'block' => 'scriptBottom')); // Custom JS
?>
<div class="row">
	<div class="col-md-offset-3 col-sm-offset-3 col-md-5 col-sm-5 register-form">
		<form class="form-horizontal" id="register-form" method="post" action="<?php echo $this->request->getAttribute('webroot') ?>users/register" data-does-exist-url="<?php echo $this->request->getAttribute('webroot').'users/doesExist'; ?>">
			<div class="row">
				<h2>Register</h2>
				<div class="line"></div>
			</div>
			<div class="form-group row-top-buffer-md">
				<div class="col-md-12 col-sm-12">
					<input type="text" class="form-control input-lg" id="email" name="data[User][username]" placeholder="Email" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-12 col-sm-12">
					<input type="password" class="form-control input-lg" id="password" name="data[User][password]" placeholder="Password" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-12 col-sm-12">
					<input type="password" class="form-control input-lg" id="confirm-password" name="confirmPassword" placeholder="Confirm Password" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-12 col-sm-12">
					<input type="text" class="form-control input-lg" id="password-hint" name="data[User][hint]" placeholder="Password hint" />
				</div>
			</div> <!-- .form-group -->
			<div class="row row-bottom-buffer">
				<div class="line"></div>
			</div>
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-12 col-sm-12">
					<input type="text" class="form-control input-lg" id="company-name" name="data[User][companyName]" placeholder="Organization" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-12 col-sm-12">
					<input type="text" class="form-control input-lg" id="firstname" name="data[User][firstname]" placeholder="First name" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-12 col-sm-12">
					<input type="text" class="form-control input-lg" id="lastname" name="data[User][lastname]" placeholder="Last name" />
				</div>
			</div> <!-- .form-group -->
			<div class="form-group row-top-buffer-sm">
				<div class="col-md-offset-2 col-sm-offset-2 col-md-10 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="agreement" value=""> I have read and agree to <a href="#">Terms &amp; Conditions</a>
						</label>
					</div>
				</div>
			</div> <!-- .form-group -->
			<div class="row row-top-buffer-sm row-bottom-buffer-lg pull-right">
				<div class="col-md-12 col-sm-12">
					<button id="register-btn" type="submit" class="btn btn-primary pull-right">Register</button>
				</div>
			</div> <!-- .row -->
	    </form>
	</div> <!-- .col-md-offset-3 .col-md-5 -->
</div> <!-- .row -->