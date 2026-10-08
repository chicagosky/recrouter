$(function() {
	
	/*
	 * Dashboard Index
	 */
	if ((typeof view !== 'undefined') && (view == 'index')) {
		
		$.plot('#job-chart', data, {
		    series: {
		        pie: {
		            innerRadius: 0.65,
		            show: true,
		        }
		    },
		    legend: {
		    	show: true
		    }
		});
	}  
});