<?php
//Custom CSS
$this->Html->css('users.dashboard.recrouter', array('inline' => false));

//Custom Javascript
$this->Html->script('user.settings.recrouter', array('inline' => false, 'block' => 'scriptBottom'));

$this->assign('menuItem', 'info');
?>
<div class="row">
<?php $currentUser = AuthComponent::user(); ?>
	<div class="col-md-offset-1 col-md-3 avatar-box">
		<img src="http://placehold.it/113" class="img-circle" />
		<p>Upload a different image...</p>
		<input type="file" />
	</div>
	<div class="col-md-7 left-border personal-info">
		<?php echo $this->Flash->render(); ?>
		<form class="form-horizontal" role="form" method="post" action="<?php echo $this->request->getAttribute('webroot').'users/edit/'.$currentUser['id'] ?>">
	        <div class="form-group">
	            <label class="col-md-2 control-label">First name:</label>
	            <div class="col-md-8 col-xs-8">
	                <input class="form-control" type="text" name="data[User][firstname]" value="<?php echo $currentUser['firstname']; ?>">
	            </div>
	        </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Last name:</label>
                <div class="col-md-8 col-xs-8">
                    <input class="form-control" type="text" name="data[User][lastname]" value="<?php echo $currentUser['lastname']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Username:</label>
                <div class="col-md-8 col-xs-8">
                    <input class="form-control" type="text" value="<?php echo $currentUser['username']; ?>" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Password:</label>
                <div class="col-md-8 col-xs-8">
                    <input class="form-control" name="data[User][password]" type="password" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Confirm password:</label>
                <div class="col-md-8 col-xs-8">
                    <input class="form-control" type="password" value="">
                </div>
            </div>
            <div class="actions">
                <input type="submit" class="btn btn-primary" value="Save Changes">
                <input type="reset" class="btn btn-default" value="Cancel">
            </div>
        </form>
	</div>
</div>