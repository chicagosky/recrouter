<?php
//Font Awesome CSS
$this -> Html -> css('/fonts/font-awesome-4.1.0/css/font-awesome.min.css', array('inline' => false));

//Flot Javascript
$this->Html->script('jquery.flot.min', array('inline' => false, 'block' => 'scriptBottom'));
$this->Html->script('jquery.flot.pie.min', array('inline' => false, 'block' => 'scriptBottom'));

//Custom Javascript
$this->Html->script('dashboard.recrouter', array('inline' => false, 'block' => 'scriptBottom'));
?>
<div class="row row-top-buffer-lg"> <!-- begin search results section -->
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title">Dashboard</h3>&nbsp;&nbsp;
				</div> <!-- .row -->
			</div>
			<div class="panel-body dashboard">
				<div id="job-chart" style="height:200px; margin: 10px 0;" class="col-md-4">
					
				</div>
			</div>
			</div> <!-- .panel-heading -->
		</div>
	</div> <!-- .col-md-12 -->
</div> <!-- .row --> 
<!-- end of search results -->
<script type="text/javascript">
	var view = 'index';
	var data = [
		{color: '#81BD82', label: ' &nbsp;&nbsp;Jobs', data: <?php echo sizeof($jobs); ?>},
	];
</script> 