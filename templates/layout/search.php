<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<?php 
		echo $this->Html->css('bootstrap.min.css'); //Bootstrap core CSS
		echo $this->Html->css('common.recrouter.css'); // Custom CSS 
		echo $this->Html->css('search.recrouter', array('inline' => false));
		echo $this->Html->css('typeahead', array('inline' => false)); //Typeahead CSS
		echo $this->Html->css('bootstrap-tagsinput.min', array('inline' => false)); //Bootstrap Tagsinput CSS 
		echo $this->Html->script('jquery-2.1.1.min.js'); //JQuery Javascript
		echo $this->element('csrf_jquery');
		echo $this->Html->script('bootstrap.min', array('inline' => false)); //Bootstrap Javascript
		echo $this->Html->script('bootstrap-tagsinput.min', array('inline' => false)); //Bootstrap Tagsinput Javascript
		echo $this->Html->script('typeahead.bundle.min', array('inline' => false)); //Typeahead Javascript
		echo $this->Html->script('search.recrouter', array('inline' => false, 'block' => 'scriptBottom')); //Custom Javascript
		?>

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
				<!-- start of main body -->
				<div id="content"> 
					<?php echo $this->element($searchBarComponent); ?>
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