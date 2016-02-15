
jQuery(document).ready(function(){
	jQuery("#main-options #accordion h2").each(function(){
		jQuery(this).replaceWith("<h3>" + jQuery(this).html() + "</h3>");
	});
})