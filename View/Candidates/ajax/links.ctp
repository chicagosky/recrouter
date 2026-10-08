<?php //$this->Paginator->options(array('update' => '#link-list')); ?>
<?php foreach($candidates as $candidate) { ?>
<div class="row row-top-buffer-md row-bottom-buffer-md bottom-border-dashed" id="link-row" 
										data-candidate-id="<?php echo $candidate['Candidate']['id']; ?>" 
										data-created="<?php echo $candidate['Candidate']['created']; ?>" 
										data-modified="<?php echo $candidate['Candidate']['modified']; ?>" 
										data-contract-models="<?php echo implode(' ', array_map(function($row) { return $row['name']; }, $candidate['ContractModel'])); ?>" 
										data-tags="<?php echo implode(' ', array_map(function($row) { return "<span class='tag hidden'>".$row['name']."</span>"; }, $candidate['Tag'])); ?>">
	<span class="col-md-1 col-xs-1 link">
		<a href="javascript://"><i class="glyphicon glyphicon-link"></i></a>
	</span>
	<span class="col-md-6 col-xs-11" id="name">
		<?php echo $candidate['Candidate']['firstname'].' '.$candidate['Candidate']['lastname']; ?>
	</span>
	<span class="col-xs-1 visible-xs-*"></span> <!-- to line up columns on mobile devices -->
	<span class="col-md-5 col-xs-11" id="location">
		<i class="glyphicon glyphicon-map-marker"></i>
		<?php echo $candidate['Candidate']['city'].', '.$candidate['Candidate']['state']; ?>
	</span>
</div>
<?php } ?>
<div class="col-md-12 text-right pagination">
<?php echo $this->element('Tools.pagination', array('separator' => ' | ')); ?>
<?php //echo $this->Paginator->numbers(); ?>
</div>
<?php //echo $this->Js->writeBuffer(); ?>