(function( $ ) {
	'use strict';

	/**
	 * Manage the behaviour of the Skip Media checkbox
	 */
	function fgj2wp_hide_unhide_media()  {
	    $("#media_import_box").toggle(!$("#skip_media").is(':checked'));
	}
	
	/**
	 * Security question before deleting WordPress content
	 */
	function fgj2wp_check_empty_content_option () {
	    var confirm_message;
	    var action = $('input:radio[name=empty_action]:checked').val();
	    switch ( action ) {
		case 'newposts':
		    confirm_message = objectL10n.delete_new_posts_confirmation_message;
		    break;
		case 'all':
		    confirm_message = objectL10n.delete_all_confirmation_message;
		    break;
		default:
		    alert(objectL10n.delete_no_answer_message);
		    return false;
		    break;
	    }
	    return confirm(confirm_message);
	}

	/**
	 * Actions to run when the DOM is ready
	 */
	$(function() {
	    // Skip media checkbox
	    $("#skip_media").bind('click', fgj2wp_hide_unhide_media);
	    fgj2wp_hide_unhide_media();
	    
	    // Empty WordPress content confirmation
	    $("#form_empty_wordpress_content").bind('submit', fgj2wp_check_empty_content_option);
	});

	/**
	 * Actions to run when the window is loaded
	 */
	$( window ).load(function() {
	    
	});

})( jQuery );
