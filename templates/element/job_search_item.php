<!-- Begin search result item -->
<?php
$createdDate = parseCakeDate($job['Job']['created'] ?? null);
$dueDate = parseCakeDate($job['Job']['fill_by'] ?? null, 'Y-m-d');
$dueTime = $dueDate->diff(new DateTime('now'));
$tags = implode(' ', array_map(function($val) { return "<span class='tag hidden'>".$val['name'].'</span>'; }, $job['Tag'] ?? []));
$contractModels = implode(' ', array_map(function($val) { return "<span class='contract-model hidden'>".$val['name'].'</span>'; }, $job['ContractModel'] ?? []));
?>
<div class="row search-result-item" data-created="<?php echo $createdDate->format('jS \of F Y'); ?>"
									data-fill-by="<?php echo $dueDate->format('jS \of F Y'); ?>"
									data-tags="<?php echo $tags ?>"
									data-contract-models="<?php echo $contractModels ?>"
									data-row-id="<?php echo $job['Job']['id']; ?>">
	<div class="col-md-1 hidden-sm hidden-xs"> <!-- favorite icon -->
		<div class="star"><i class="glyphicon glyphicon-star"></i></div>
	</div>
	<div class="col-md-6 col-xs-12"> <!-- Job title -->
		<div class="col-md-12 search-result-title" id="job-title">
			<a href="<?php echo $this->request->getAttribute('webroot').'jobs/view/'.$job['Job']['id']; ?>"><?php echo $job['Job']['title'] ?></a>
		</div>
	</div>
	<div class="col-md-2 col-xs-12"> <!-- indicators -->
		<div class="col-md-12 col-xs-6">
			<i class="glyphicon glyphicon-time"></i>&nbsp; <?php echo $dueTime->format('%a'); ?>
		</div>
		<div class="col-md-12 col-xs-6 link-indicator">
			<i class="glyphicon glyphicon-link"></i>&nbsp; <?php echo sizeof($job['CandidatesJob'] ?? []); ?>
		</div>
	</div>
	<div class="col-md-3 col-xs-12 information" id="job-location"> <!-- location -->
		<div class="col-md-12 col-xs-6" id="job-department"> 
			<i class="glyphicon glyphicon-map-marker"></i> <?php echo $job['Job']['city'].', '.$job['Job']['state']; ?>
		</div>
		<div class="col-md-12 col-xs-6 department-name" id="job-department"> 
			<i class="fa fa-sitemap fa-fw"></i> <?php echo $job['Department']['name'] ?? ''; ?>
		</div>
	</div>
</div>
<!-- .row End of search result item -->