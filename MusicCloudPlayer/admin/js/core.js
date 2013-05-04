var Core = {
	init: function() {
		$('.confirm').bind('click', function() {
			if ( ! confirm('Are you sure execute this operation?')) {
				return false;
			}
		});
		
		Core.multiple_change_checked('#content .ids-control', '#content .ids');
	},
	
	multiple_change_checked: function(control, group) {
		$(control).bind('change', function() {
			if ($(this).is(':checked')) {
				$(group).attr('checked', 'checked');
			} else {
				$(group).removeAttr('checked', 'checked');
			}
		});
	}
}

$(document).ready( function() {
	Core.init();
});