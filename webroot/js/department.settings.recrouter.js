$(function() {
    
    /*
	 * --------------------------
	 * SECTION: Setup node icons
	 * --------------------------
	 */
    $.each($('.easy-tree').find('ul > li'), function() {
        if($(this).is('li:has(ul)')) {
            $(this).find(' > span > i').addClass('fa-sitemap');
        }
        else {
            $(this).find(' > span > i').addClass('fa-ellipsis-v');
        }
    });
	
	/*
	 * -----------------------
	 * SECTION: Add Department
	 * -----------------------
	 */
	$('.add-menu-item').popover({ 
	    html : true,
	    placement: 'right',
	    title: 'Add New Node',
	    content: function() {
	      return $("#add-popover-content").html();
	    },
	    container: 'body'
	});
	
	$(document).on('click', '.add-menu-item', function() {
		$('.add-menu-item').not(this).popover('hide');
		$('.edit-menu-item').not(this).popover('hide');
		var nodeId = $(this).parents('div').data('node-id');
		$(document).find('.popover').find('#add-parent-id').val(nodeId);
	});
	
	/*
	 * ------------------------
	 * SECTION: Edit Department
	 * ------------------------
	 */
	$('.edit-menu-item').popover({ 
	    html : true,
	    placement: 'right',
	    title: 'Edit Node',
	    content: function() {
	      return $("#edit-popover-content").html();
	    },
	    container: 'body'
	});
	
	$(document).on('click', '.edit-menu-item', function() {
		$('.edit-menu-item').not(this).popover('hide'); //hide all other popovers
		$('.add-menu-item').not(this).popover('hide');
		var node = $(this).parents('div');
		var nodeId = node.data('node-id');
		var nodeName = node.children('#node-name').text();
		var parentId = node.data('parent-id');
		var popover = $(document).find('.popover');
		popover.find('#edit-node-form').attr('action', www_root + 'departments/edit/' + nodeId);
		popover.find('#dept-name').val(nodeName); //set name
		popover.find('#parent-dept').children('.hidden').removeClass('hidden'); //unhide previously hidden values
		popover.find('#parent-dept option[value="' + nodeId + '"]').addClass('hidden'); //hide current node in parent list
		popover.find('#parent-dept').val(parentId);
	});
	
	/*
	 * ---------------------------
	 * SECTION: Archive Department
	 * ---------------------------
	 */
	$(document).on('click', '.delete-menu-item', function() {
		$('.add-menu-item').not(this).popover('destroy');
		$('.edit-menu-item').not(this).popover('destroy');
		var nodeId = $(this).parents('div').data('node-id');
		$('#archive-modal-label').text('Archive Department');
		$('#archive-form').attr('action', www_root + 'departments/delete/' + nodeId);
	});
		
	$('#archive-btn').on('click', function() {
		$('#archive-form').submit();
	});
});