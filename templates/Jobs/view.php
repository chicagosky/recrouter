<?php
//custom CSS
$this->Html->css('quickview.recrouter', array('inline' => false));

//Flot Javascript
$this->Html->script('jquery.flot.min', array('inline' => false, 'block' => 'scriptBottom'));
$this->Html->script('jquery.flot.pie.min', array('inline' => false, 'block' => 'scriptBottom'));

//custom Javascript
$this->Html->script('job.recrouter', array('inline' => false, 'block' => 'scriptBottom'));

$dueDate = parseCakeDate($job['Job']['fill_by'] ?? null, 'Y-m-d');
$dueTime = $dueDate->diff(new DateTime('now'));
$tags = implode(' ', array_map(function($val) { return "<span class='tag'>".$val['name'].'</span>'; }, $job['Tag'] ?? []));
$contractModels = implode(' ', array_map(function($val) { return "<span class='contract-model'>".$val['name'].'</span>'; }, $job['ContractModel'] ?? []));
?>
<div class="row row-top-buffer-lg view-job-page"> <!-- begin view job page -->
	<div class="col-md-8">
		<?php echo $this->Flash->render(); ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title"><?php echo $job['Job']['title']; ?></h3>&nbsp;&nbsp;
					<a href="javascript://" data-row-id="<?php echo $job['Job']['id']; ?>" data-toggle="modal" data-target="#archive-modal" class="delete-job"><i class="glyphicon glyphicon-trash"></i></a>
					<?php echo '&nbsp;&nbsp; '.$this->DueDate->dueDateLabel($dueDate, array('class' => 'label-override pull-right')); ?>
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body view-job-details">
				<div class="row row-top-buffer-lg bottom-border-dashed location">
					<label class="col-md-3">Location</label>
					<div class="col-md-9"> <?php echo $job['Job']['city'].', '.$job['Job']['state']; ?> </div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg bottom-border-dashed department">
					<label class="col-md-3">Department</label>
					<div class="col-md-9"> <?php echo $job['Department']['name'] ?? ''; ?> </div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg bottom-border-dashed created-by">
					<label class="col-md-3">Created by</label>
					<div class="col-md-9"><a href="mailto:<?php echo $job['User']['username'] ?? ''; ?>"><?php echo ($job['User']['lastname'] ?? '').', '.($job['User']['firstname'] ?? '').', '.$this->Time->timeAgoInWords($job['Job']['created']); ?></a></div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg bottom-border-dashed fill-by">
					<label class="col-md-3">Fill by</label>
					<div class="col-md-9"><?php echo $this->Time->nice($job['Job']['fill_by']); ?></div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg bottom-border-dashed contract-models">
					<label class="col-md-3">Contract Models</label>
					<div class="col-md-9 contract-models"> <?php echo $contractModels ?> </div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg bottom-border-dashed tags">
					<label class="col-md-3">Tags</label>
					<div class="col-md-9 tags"> <?php echo $tags ?> </div> <!-- .col-md-9 -->
				</div>
				<div class="row row-top-buffer-lg">
					<label class="col-md-3">Responsibilities</label>
				</div>
				<div class="row  row-bottom-buffer-lg bottom-border-dashed">
					<div class="col-md-12"><?php echo $job['Job']['description']; ?></div>
				</div>
				<div class="row row-top-buffer-lg">
					<label class="col-md-3">Skills</label>
				</div>
				<div class="row row-bottom-buffer-lg">
					<div class="col-md-12"><?php echo $job['Job']['skills']; ?></div>
				</div>
			</div> <!-- .panel-body -->
			<div class="panel-footer">
				<a href="<?php echo $this->request->getAttribute('webroot').'jobs/clone/'.$job['Job']['id']; ?>" class="footer-link"><i class="glyphicon glyphicon-file"></i> [Clone]</a>
				<a href="#" class="footer-link"><i class="glyphicon glyphicon-print"></i> [Print]</a>
				<a href="<?php echo $this->request->getAttribute('webroot').'jobs/edit/'.$job['Job']['id']; ?>" class="footer-link"><i class="glyphicon glyphicon-edit"></i> [Edit]</a>
			</div>
		</div> <!-- .panel .panel-default -->
		<?php if (!empty($job['Survey']['id'])) { //begin survey block ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div> <!-- row to ensure panel heading height adjusts -->
					<h3 class="panel-title inline-panel-title bold-panel-title"><?php echo $job['Survey']['name']; ?></h3>&nbsp;&nbsp;
				</div> <!-- .row -->
			</div> <!-- .panel-heading -->
			<div class="panel-body view-job-survey">
			<?php
			foreach($questions as $question) {
				$surveyElement = 'survey_element_'.$question['Question']['type']; //dynamically choose display element based on type
				$options = array('showAnswers' => false, 'editAnswers' => false, 'question' => $question['Question']);
				echo $this->element($surveyElement, $options); 
			}
			?>
			</div> <!-- .panel-body -->
		</div> <!-- .panel .panel-default -->
		<?php } //end of survey block ?>
		<!-- //begin workflow step user block -->
		<?php echo $this->element('job_workflow_widget', array('jobWorkflowSteps' => $job['JobWorkflowStep'] ?? [])); ?>
		<!-- //end of workflow step user block -->
	</div> <!-- .col-md-8 -->
	<div class="col-md-4">
		<!-- Begin External Identifier -->
		<div class="row">
			<div class="col-md-12">
				<?php echo $this->element('job_ext_id_widget', array('jobExtIds' => $job['JobExtId'] ?? [])); ?>
			</div>
		</div> <!-- .row -->
		<!-- End External Identifier -->
		<!-- Begin Links -->
		<div class="row">
			<div class="col-md-12 links">
			<!-- list of existing links populated by AJAX -->
			</div>
		</div> <!-- .row -->
		<!-- End Links -->
		<!-- Begin Graph -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div> <!-- row to ensure panel heading height adjusts -->
							<h3 class="panel-title inline-panel-title search-results-panel-title">Link Statistics</h3>
						</div> <!-- .row -->
					</div> <!-- .panel-heading -->
					<div class="panel-body">
						<div id="link-chart" style="height:200px; margin: 10px 0;">
						</div>
					</div> <!-- .panel-body -->
				</div>
			</div>
		</div> <!-- .row -->
		<!-- End Graph -->
	</div>
</div> <!-- .row --> 
<!-- end of search results -->
<?php echo $this->element('archive_dialog'); ?> <!-- archive dialog element -->
<?php echo $this->element('link_candidates_to_job_modal'); ?> <!-- Link Candidate dialog element -->
<div class="hidden" id="list-of-users"></div>

<script type="text/javascript">
	var view = 'view';
	var jobId = <?php echo $job['Job']['id']; ?>;
	var preLinked = [<?php echo implode(',', array_map(function($row) { return $row['Candidate']['id']; }, $preLinks ?? [])); ?>];
	var data = [
		{color: '#81BD82', label: ' &nbsp;&nbsp;Hired', data: <?php echo $linkData['hired']; ?>},
		{color: '#F0AD4E', label: ' &nbsp;&nbsp;Rejected', data: <?php echo $linkData['rejected']; ?>},
		{color: '#68A3D5', label: ' &nbsp;&nbsp;Open', data: <?php echo $linkData['open']; ?>}
	];
</script>