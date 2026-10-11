<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="shortcut icon" href="<?php echo $this->request->getAttribute('webroot').'img/favicon.ico'; ?>" type="image/x-icon">
		<link rel="icon" href="img/favicon.ico" type="image/x-icon">
		
		<!-- Bootstrap Core CSS -->
		<?php echo $this->Html->css('bootstrap.min.css'); ?>

		<!-- Custom CSS -->
		<?php echo $this->Html->css('common.recrouter.css'); ?>
		
		<!-- JQuery Javascript -->
		<?php echo $this->Html->script('jquery-2.1.1.min.js'); ?>
		<?php echo $this->element('csrf_jquery'); ?>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<?php
		echo $this->fetch('css');
		echo $this->fetch('script');
		?>
		<script type="text/javascript">
			var www_root = <?= json_encode($this->request->getAttribute('webroot')) ?>;
		</script>
		<title><?php echo $this->fetch('title'); ?></title>
	</head>
	<body>
		<?php echo $this -> element('site_top_menu'); ?> <!-- top navigation menu element -->
		<div id="wrap"> 
			<div id="main" class="container-fluid">
				<div id="ajax-loader" style="display: none;">
				    <img id="loading" src="<?php echo $this->request->getAttribute('webroot').'img/loading2.gif'; ?>"></img>
				</div>

				<!-- start of main body -->
				<div id="content"> 
					<?php echo $this->fetch('content'); ?>
				</div> 
				<!-- end of main body -->
				
			</div> <!-- #container -->
		</div> <!-- #wrap --> 
		
		<?php echo $this -> element('site_footer'); ?> <!-- bottom navigation menu element -->
				
		<!-- Custom Javascript -->
		<?php echo $this->Html->script('common.recrouter'); ?>
		
		<!-- JS elements designated as Script Bottom -->
		<?php echo $this->fetch('scriptBottom'); ?>
	</body>
</html>