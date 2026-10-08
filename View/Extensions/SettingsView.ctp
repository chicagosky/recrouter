<?php
/* Font Awesome CSS */
$this->Html->css('/fonts/font-awesome-4.1.0/css/font-awesome.min', array('inline' => false));

/* Custom CSS */
$this->Html->css('quickview.recrouter', array('inline' => false));
$this->Html->css('settings.recrouter', array('inline' => false));

/* Bootstrap Javascript */
$this->Html->script('bootstrap.min', array('inline' => false, 'block' => 'scriptBottom'));

/* Bootstrap Validator Javascript */
$this->Html->script('bootstrapValidator.min', array('inline' => false, 'block' => 'scriptBottom'));

/* Custom Javascript */
$this->Html->script('settings.recrouter', array('inline' => false, 'block' => 'scriptBottom'));
?>
<div class="row content-no-top-margin">
	<?php echo $this->element('settings_side_menu'); ?> <!-- side menu element -->
	
	<div class="col-sm-10"> <!-- begin main view content -->
		<div class="h3 inline-page-title"><?php echo $viewTitle; ?></div>&nbsp;&nbsp;&nbsp; 
		<?php if (isset($addButtonTitle)) { ?>
		<a class="btn btn-sm btn-info inline-title-button" data-toggle="modal" data-target="<?php echo $addButtonModal; ?>">
			<?php echo $addButtonTitle; ?>
		</a>
		<?php } ?>
		<div class="row">
			<div class="col-sm-9">
				<?php echo $this->fetch('mainView'); ?> <!-- view for selected menu item -->
			</div>
			<div class="col-sm-3">
				<?php echo $this->fetch('quickview'); ?> <!-- quickview for selected menu item -->
			</div>
		</div> <!-- .row title row -->
	</div> <!-- .col-sm-10 --> <!-- end main view content -->
</div> <!-- .row .content-no-top-margin -->

<?php echo $this->fetch('editModal'); ?> <!-- Edit Dialog -->

<?php echo $this->element('archive_dialog'); ?> <!-- archive dialog element -->

<!-- Enable highlighting of menu selection -->
<!-- Adds the CSS class 'active' to side menu item based on the PHP variable 'menuItem' -->
<script type="text/javascript">
	$(document).ready(function() {
		selector = '<?php echo $this->fetch('menuItem'); ?>' + '-menu-item';
		$("#"+selector).addClass('active');
	});
</script>