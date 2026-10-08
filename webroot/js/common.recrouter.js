$(document).ready(function() {
	$('[data-toggle=offcanvas]').click(function() {
		$('.row-offcanvas').toggleClass('active');
	});
	
	//Y/N button toggle function - used in survey template
	$(document).on('click', '.btn-toggle', function() {
		 $(this).find('.btn').toggleClass('active');
	    
	    if ($(this).find('.btn-primary').size()>0) {
	    	$(this).find('.btn').toggleClass('btn-primary');
	    }
	    
	    $(this).find('.btn').toggleClass('btn-default');
	});
});

function listToArray(str, separator) {
	var arr = [];
	if (str !== undefined) {
		if (str.indexOf(separator) == -1) {
			arr.push(str);
		} else {
			arr = str.split(separator);
		}
	}
	return arr;
}

$.urlParam = function(name) {
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if (results == null) {
		return null;
	} else {
		return results[1] || 0;
	}
};

/*
 * Get current date time in SQL format
 */
function getSQLDate() {
	var date;
    date = new Date();
    date = date.getUTCFullYear() + '-' +
            ('00' + (date.getUTCMonth() + 1)).slice(-2) + '-' +
            ('00' + date.getUTCDate()).slice(-2) + ' ' +
            ('00' + date.getUTCHours()).slice(-2) + ':' +
            ('00' + date.getUTCMinutes()).slice(-2) + ':' +
            ('00' + date.getUTCSeconds()).slice(-2);
    return date;
}

/*
 * Search for a value within an array, returns true if value and object type are same
 */
function isInArray(needle, haystack) {
    var count = haystack.length;
    for (var i=0; i<count; i++) {
        if (haystack[i] === needle) {
        	return true;
        }
    }
    return false;
}

/*
 * Renders JSON content of links into the specified DIV container.
 * @param content string representation of HTML content that needs to be rendered
 * @param preLinkSelector selector to identify pre-links
 *
function showLinkList(content, preLinkSelector) {
	$('#link-list').html(content);
	$('#link-list > #link-row').each(function() {
		if (isInArray(parseInt($(this).data(preLinkSelector)), preLinked)) {
			$(this).find('span.link').addClass('linked');
		}
	});
}*/

function showAjaxAlert(container, success, message) {
	var alertContainer = container.find('div.ajax-message');
	alertContainer.removeClass('hidden');
	if (success) {
		alertContainer.addClass('alert-success');
		alertContainer.html('<i class="glyphicon glyphicon-ok"></i>&nbsp; ');
	} else {
		alertContainer.addClass('alert-warning');
		alertContainer.html('<i class="glyphicon glyphicon-remove"></i>&nbsp; ');
	}
	alertContainer.append(message);
}
