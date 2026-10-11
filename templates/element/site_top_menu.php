<!-- start of top nav bar -->
<nav class="navbar navbar-default navbar-fixed-top recrouter-navbar" role="navigation">
	<div class="container-fluid"> <!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand recrouter-site-title" href="#"><img src="<?php echo $this->request->getAttribute('webroot').'img/logo.png'; ?>" style="height:44px; margin-top: -5px;"/></a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="hidden-sm" id="home">
					<a href="<?php echo $this->request->getAttribute('webroot').'Dashboards' ?>">Home</a>
				</li>
				<?php if (AuthComponent::user()) { ?>
				<li class="hidden-sm" id="jobs-menu-item">
					<a href="<?php echo $this -> webroot.'Jobs'; ?>">Jobs</a>
				</li>
				<li class="hidden-sm" id="candidates-menu-item">
					<a href="<?php echo $this -> webroot.'Candidates'; ?>">Candidates</a>
				</li>
				<?php } ?>
			</ul>
			<ul class="nav navbar-nav navbar-right"> <!-- any items that need to be displayed on the right handside -->
				<?php 
				$user = AuthComponent::user();
				if ($user) { 
				?>
				<li>
					<a href="#"><i class="glyphicon glyphicon-user"></i> <?php echo $user['firstname']; ?></a> 
				</li>
				<li id="settings-menu-item">
					<a href="<?php echo $this->request->getAttribute('webroot') ?>settings"><i class="glyphicon glyphicon-cog"></i> Settings</a>
				</li>
				<li>
					<a href="<?php echo $this->request->getAttribute('webroot') ?>users/logout">
						<i class="glyphicon glyphicon-log-out"></i> Logout
					</a>
				</li>
				<?php } else { ?>
				<li>
					<a href="<?php echo $this->request->getAttribute('webroot') ?>users/login"><i class="glyphicon glyphicon-log-in"></i> Login</a>
				</li>	
				<?php } ?>		
			</ul>
		</div> <!-- /.navbar-collapse -->
	</div> <!-- /.container -->
</nav> 
<!-- end of top nav bar -->