jQuery(document).ready(function() {
	
	jQuery('.feature-box .subheading a').parents('.subheading').siblings('.options-box').hide();

	jQuery('.toggle_box .dependent').hide();
	
	// Toggle-only behaviors
	jQuery('.toggle_box .switch').click(function() {
		jQuery(this).parents('p').siblings('.dependent').toggle();
		return false;
	});
	
	jQuery('.feature-box .subheading a').click(function() {
		jQuery(this).parents('.subheading').siblings('.options-box:first').toggle();
		return false;
	});
	
	

});