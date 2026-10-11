<!-- Begin search result item -->
<?php
$createdDate = parseCakeDate($candidate['Candidate']['created'] ?? null);
$tags = implode(' ', array_map(function($val) { return "<span class='tag hidden'>".$val['name'].'</span>'; }, $candidate['Tag'] ?? []));
$contractModels = implode(' ', array_map(function($val) { return "<span class='contract-model hidden'>".$val['name'].'</span>'; }, $candidate['ContractModel'] ?? []));
?>
<div class="row search-result-item" data-created="<?php echo $createdDate->format('jS \of F Y'); ?>"
									data-tags="<?php echo $tags ?>"
									data-contract-models="<?php echo $contractModels ?>"
									data-row-id="<?php echo $candidate['Candidate']['id']; ?>">
	<div class="col-md-1 hidden-sm hidden-xs"> <!-- favorite icon -->
		<div class="star"><i class="glyphicon glyphicon-star"></i></div>
	</div>
	<div class="col-md-8 col-xs-12"> <!-- Candidate name -->
		<div class="col-xs-12 search-result-title" id="candidate-name">
			<a href="<?php echo $this->request->getAttribute('webroot').'candidates/view/'.$candidate['Candidate']['id']; ?>">
				<?php echo $candidate['Candidate']['firstname'].' '.$candidate['Candidate']['lastname']; ?>
			</a>
		</div>
		<div class="col-xs-12 row-top-buffer-sm search-result-icons">
		<?php
		if (!empty($candidate['Candidate']['email'])) {
			echo '<i class="fa fa-envelope fa-fw"></i>&nbsp;';
		}
		if (!empty($candidate['Candidate']['phone'])) {
			echo '<i class="fa fa-phone fa-fw"></i>&nbsp;';
		}
		if (!empty($candidate['Candidate']['file_name'])) {
			echo '<i class="fa fa-file fa-fw"></i>&nbsp;';
		}
		//future - put other attributes such as relocation, travel, contracting etc
		?>
		</div>
	</div>
	<div class="col-md-3 col-xs-12 information" id="candidate-location"> <!-- location -->
		<div class="col-md-12 col-xs-6"> 
			<i class="glyphicon glyphicon-map-marker"></i> <?php echo $candidate['Candidate']['city'].', '.$candidate['Candidate']['state']; ?>
		</div>
		<div class="col-md-12 row-top-buffer-sm col-xs-6 link-indicator">
			<i class="glyphicon glyphicon-link"></i>&nbsp; <?php echo sizeof($candidate['CandidatesJob']); ?>
		</div>
	</div>
</div>
<!-- .row End of search result item -->