<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<!-- Bootstrap Core CSS -->
		<?php echo $this -> Html -> css('bootstrap.min.css'); ?>
		
		<!-- Font Awesome CSS -->
		<?php echo $this -> Html -> css('/fonts/font-awesome-4.1.0/css/font-awesome.min.css'); ?>

		<!-- Custom CSS -->
		<?php echo $this -> Html -> css('common.recrouter.css'); ?>
		<?php echo $this -> Html -> css('dashboard.recrouter.css'); ?>
		
		<!-- JQuery Javascript -->
		<?php echo $this -> Html -> script('jquery-2.1.1.min.js'); ?>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<?php 
		echo $this -> fetch('css');
		echo $this -> fetch('script');
		?>
		<script type="text/javascript">
			var www_root = <?php echo $this->webroot; ?>;
		</script>
		<title><?php echo $this -> fetch('title'); ?></title>
	</head>
	<body>
		<?php echo $this -> element('site_top_menu'); ?> <!-- top navigation menu element -->
		<div id="wrap">
			<div class="container-fluid">
	
				<div class="row row-offcanvas row-offcanvas-left">
					<div class="col-sm-3 col-md-2 sidebar-offcanvas" id="sidebar" role="navigation">
						<?php echo $this->element('dashboard_side_menu'); ?> <!-- side menu element -->
					</div> <!-- .col-sm-3 .col-md-2 .sidebar-offcanvas -->
	
					<div class="col-sm-9 col-md-10 main">
						<!--toggle sidebar button-->
						<p class="visible-xs">
							<button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">
								<i class="glyphicon glyphicon-chevron-left"></i>
							</button>
						</p>
	
						<h1 class="page-header inline-page-title"><?php echo $viewTitle; ?></h1>
						<?php 
						if (isset($addButtonTitle)) {
							if (isset($addButtonLink)) {	 
						?>
						<a class="btn btn-sm btn-info inline-title-button pull-right" href="<?php echo $addButtonLink; ?>" id="add-btn">
							<?php echo $addButtonTitle; ?>
						</a>
						<?php 
							} else {
						?>
						<a class="btn btn-sm btn-info inline-title-button pull-right" data-toggle="modal" data-target="<?php echo $addButtonModal; ?>" id="add-btn">
							<?php echo $addButtonTitle; ?>
						</a>
						<?php
							}
						} 
						?>
						
						<?php echo $this->fetch('content'); ?>
					
					</div><!-- .col-sm-9 .col-md-10 .main -->
				</div> <!-- .row .row-offcanvas .row-offcanvas-left -->
			</div><!-- .container-fluid -->
		</div> <!-- #wrap -->
		
		<?php echo $this -> element('archive_dialog'); ?> <!-- archive dialog element -->
		
		<?php echo $this -> element('site_footer'); ?> <!-- bottom navigation menu element -->
		
		<!-- Bootstrap Javascript -->
		<?php echo $this -> Html -> script('bootstrap'); ?>

		<!-- Bootstrap Validator Javascript -->
		<?php echo $this -> Html -> script('bootstrapValidator.min'); ?>
								
		<!-- Custom Javascript -->
		<?php echo $this -> Html -> script('common.recrouter'); ?>
		
		<!-- Adds the CSS class 'active' to side menu item based on the PHP variable 'menuItem' -->
		<script type="text/javascript">
			$(document).ready(function() {
				$(document).find('li#settings-menu-item').addClass('active-top-menu');
				
				selector = '<?php echo $this -> fetch('menuItem'); ?>';
				$("#"+selector).addClass('active');
				$("#"+selector).append('<div class="pointer"><div class="arrow"></div><div class="arrow_border"></div></div>');
			});
		</script>
		
		<!-- JS elements designated as Script Bottom -->
		<?php echo $this -> fetch('scriptBottom'); ?>
	</body>
</html>