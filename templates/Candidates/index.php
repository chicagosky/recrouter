<?php
//Font Awesome CSS
$this -> Html -> css('/fonts/font-awesome-4.1.0/css/font-awesome.min.css', array('inline' => false));

//custom CSS
$this->Html->css('quickview.recrouter', array('inline' => false));

//custom Javascript
$this->Html->script('candidate.recrouter', array('inline' => false, 'block' => 'scriptBottom'));
?>
<div class="row row-top-buffer-lg"> <!-- begin search results section -->
	<div class="col-md-9">
		<?php echo $this->Flash->render(); ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row"> <!-- row to ensure panel heading height adjusts -->
					<div class="col-md-4">
						<h3 class="panel-title inline-panel-title bold-panel-title">Search Results</h3>
					</div> <!-- .col-md-4 -->
					<div class="col-md-4 search-count">
						<? echo sizeof($candidates) ?> candidate(s) in search results
					</div> <!-- .col-md-4 -->
					<div style="display:none;">
					<?php 
					echo $this->Paginator->sort('firstname', '<span id="sort-by-firstname">firstname</span>', array('escape' => false));
					echo $this->Paginator->sort('state', '<span id="sort-by-state">state</span>', array('escape' => false));
					?>
					</div>
					<div class="col-md-4 text-right hidden-sm hidden-xs">
						<select id="sort-order">	
							<option value="firstname">by name</option>
							<option value="state">by location</option>
						</select>
					</div> <!-- .col-md-4 .text-right -->
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body">
			<?php 
			foreach ($candidates as $candidate) { 
				echo $this->element('candidate_search_item', array('candidate' => $candidate)); 
			} ?>
			</div> <!-- .panel-body -->
			<div class="panel-footer text-right">
				<?php echo $this->Paginator->numbers(); ?>
			</div>
		</div> <!-- .panel .panel-default -->
	</div> <!-- .col-md-9 -->
	<!-- begin quick view section -->
	<div class="col-md-3 quickview"> 
		<a href="<?php echo $this->request->getAttribute('webroot'); ?>candidates/add" class="btn-flat success">[+] Add Candidate(s)</a>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title panel-heading-quickview">Quick View</h3>
			</div> <!-- .panel-heading -->
			<div class="panel-body">
				<div class="quickview-title" id="qv-title">Candidate Name</div>
				<div class="row-top-buffer-xl quickview-row-heading">Contract Models</div>
				<div class="row-top-buffer-md" id="qv-contract-models"></div>
				<div class="row-top-buffer-lg quickview-row-heading">Created on</div>
				<div class="row-top-buffer-md" id="qv-created"></div>
				<div class="row-top-buffer-lg quickview-row-heading">Tags</div>
				<div class="row-top-buffer-md row-bottom-buffer-md" id="qv-tags"></div>
			</div> <!-- .panel-body -->
		</div> <!-- .panel .panel-default -->
	</div> <!-- .recrouter-30px-left-gutter .col-md-3 .section --> 
	<!-- end of quick view section -->
</div> <!-- .row --> 
<!-- end of search results -->
<script type="text/javascript">
	var view = 'index';
	var favorites = [<?php echo implode(',',$favorites); ?>];
	var sortKey = '<?php echo $this->Paginator->sortKey(); ?>';
</script>