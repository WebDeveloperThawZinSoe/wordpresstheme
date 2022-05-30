jQuery(document).ready(function(){	
	jQuery('body').on('click', '.iheg-linkto .dashicons-admin-generic', function(e) {		
		jQuery(this).closest('.ihe-block-control').find('.link-attributes').toggleClass('visible');
	});
	
	jQuery('body').on('click', '.css-switch label', function() {
		var  $switch = jQuery(this).closest('.css-link');
		if($switch.hasClass('checked')){			
			$switch.removeClass('checked');
		} else {
			$switch.addClass('checked');
		}		
	});
});