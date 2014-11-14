jQuery(document).ready(function() {
	
	// Kommentare aus- und einklappen
	jQuery('.comments-link a').click(function(event) {
		event.preventDefault();
		jQuery(this).parents('.item-main').children('.item-comments').toggle();
		$container = jQuery	('.masonry'); 
		$container.masonry()
	});
	
	// Schlagworte aus- und einklappen
	jQuery('.tags-toggle').click(function(event) {
		event.preventDefault();
		jQuery('#sidebar').toggle();
	});

});

