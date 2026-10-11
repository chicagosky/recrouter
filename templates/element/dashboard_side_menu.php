<ul class="nav nav-sidebar">
	<li id="departments">
		<a href="<?php echo $this->request->getAttribute('webroot'); ?>departments"> <i class="fa fa-fw fa-sitemap"></i> <span>Departments</span> </a>
	</li>
	<li id="permissions">
		<a href="#"> <i class="fa fa-fw fa-key"></i> <span>Permissions</span> </a>
	</li>
	<li id="preferences">
		<a href="#"> <i class="fa fa-fw fa-sliders"></i> <span>Preferences</span> </a>
	</li>
	<li id="groups">
		<a href="<?php echo $this->request->getAttribute('webroot'); ?>groups"> <i class="fa fa-fw fa-users"></i> <span>Teams</span> </a>
	</li>
	<li id="users">
		<a href="<?php echo $this->request->getAttribute('webroot'); ?>users"> <i class="fa fa-fw fa-user"></i> <span>Users</span> </a>
	</li>
	<li id="workflows">
		<a href="<?php echo $this->request->getAttribute('webroot'); ?>workflows"> <i class="fa fa-fw fa-random"></i> <span>Workflows</span> </a>
	</li>
	<li id="info">
		<a href="<?php echo $this->request->getAttribute('webroot'); ?>users/view"> <i class="fa fa-fw fa-cog"></i> <span>My Info</span> </a>
	</li>
</ul>