<form id="search-form" action="<?php echo $this->webroot ?>candidates" method="post">
	<!-- begin search menu -->
	<div class="row recrouter-search search-criterias row-top-buffer-lg">
		<!-- Search criteria -->
		<div class="col-md-offset-1 col-sm-offset-1 col-md-5 col-sm-5 col-xs-12 search-criteria">
			<label for="keyword-txt" class="recrouter-search-label">Keyword</label>
			<input type="text" class="form-control" id="keywords" name="data[keywords]" placeholder="search text..." 
				value="<?php echo str_replace ( '"', '&quot;', $presetVars['keywords'] ) ; ?>" data-toggle="tooltip" data-placement="bottom" title="use space or comma to separate keywords and quotes to specify a multi-word search" />
		</div>
		<!--<div class="col-md-3 col-sm-3 col-xs-12 search-criteria">
			<label for="search-location" class="recrouter-search-label">Location</label>
			<input type="text" class="form-control" id="search-location" name="data[location]" placeholder="city, state" value="<?php echo $presetVars['location']; ?>" />
		</div>-->
		<div class="col-md-4 col-sm-4 col-xs-12 search-criteria">
			<label for="search-tags" class="recrouter-search-label">Tags</label>
			<input type="text" data-role="tagsinput" class="form-control" id="search-tags" name="data[tags]" 
				value="" data-toggle="tooltip" data-placement="bottom" title="multiple tags will narrow search results, remove tags to expand result set" />
		</div>
		<div class="col-md-1 col-sm-1 col-xs-1 search-btn">
			<button type="button" class="btn btn-warning" id="search-btn">
				<i class="glyphicon glyphicon-search"></i>
			</button>
		</div>
	</div>
	<!-- .row .recrouter-search-criteria -->
	<div class="row search-filters row-top-buffer-md">
		<!-- View filters -->
		<div class="col-sm-offset-7 col-sm-5 col-xs-12 search-filter-favorite">
			<label class="col-xs-3 radio-inline"> <?php $currentUser = AuthComponent::user(); ?>
				<input type="radio" name="filter" value="0" <?php echo (0 == $presetVars['filter']) ? 'checked' : ''; ?>>
				Favorites 
			</label>
			<label class="col-xs-3 radio-inline"> <?php $currentUser = AuthComponent::user(); ?>
				<input type="radio" name="filter" value="<?php echo $currentUser['id']; ?>" <?php echo ($presetVars['filter'] > 0) ? 'checked' : ''; ?>>
				My Candidates 
			</label>
			<label class="col-xs-3 radio-inline">
				<input type="radio" name="filter" value="-1" <?php echo ($presetVars['filter'] < 0) ? 'checked' : ''; ?>>
				All Candidates 
			</label>
		</div>
	</div>
	<!-- .row .recrouter-search-filter -->
</form>
<!-- end of search menu -->

<script type="text/javascript">
	var tags = new Bloodhound({
		datumTokenizer : Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer : Bloodhound.tokenizers.whitespace,
		prefetch : 'tags.json',
		remote : {
			url : 'http://localhost/cake/tags/search/%QUERY',
			filter : function(jsonArray) {
				return $.map(jsonArray, function(obj) {
					return {
						name : obj.Tag.name,
						id : obj.Tag.id
					};
					//each jsonArray object is obj.Tag.text & obj.Tag.value
				});
			}
		}
	});

	tags.initialize();

	$('#search-tags').tagsinput({
		maxTags : 4,
		itemValue : 'id',
		itemText : 'name',
		typeaheadjs : {
			name : 'tags',
			displayKey : 'name',
			source : tags.ttAdapter()
		}
	}); 
</script>