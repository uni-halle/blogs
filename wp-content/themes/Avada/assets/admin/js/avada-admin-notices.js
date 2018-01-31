/* global avadaAdminNotices, ajaxurl */
( function() {

	// Shorthand for ready event.
	jQuery( function() {

		// Dimiss notice.
		jQuery( 'div[avada-data-dismissible] button.notice-dismiss' ).click(function( event ) {
			var $optionName,
			    $data,
			    $this = jQuery( this );

			event.preventDefault();

			$optionName = $this.parent().attr( 'avada-data-dismissible' );

			$data = {
				'action': 'avada_dismiss_admin_notice',
				'option_name': $optionName,
				'nonce': avadaAdminNotices.nonce
			};

			// Make ajax request.
			jQuery.post( ajaxurl, $data );
		} );

		// Show deprecated template notice on page load.
		if ( 0 < jQuery( '#publish' ).length && 0 < jQuery( '#page_template' ).length ) {
			if ( 'side-navigation.php' === jQuery( '#page_template' ).val() ) {
				jQuery( '<div class="notice notice-error is-dismissible side_nav_deprecated_notice"><p>' + avadaAdminNotices.deprecated_side_nav_teamplate + '</p></div>' ).insertAfter( '#lost-connection-notice' );
			}
		}

		// Show deprecated template notice on template change.
		jQuery( '#page_template' ).change( function() {
			if ( 'side-navigation.php' === jQuery( '#page_template' ).val() && 1 > jQuery( '.side_nav_deprecated_notice' ).length ) {
				jQuery( '<div class="notice notice-error is-dismissible side_nav_deprecated_notice"><p>' + avadaAdminNotices.deprecated_side_nav_teamplate + '</p></div>' ).insertAfter( '#lost-connection-notice' );
			} else {
				jQuery( '.side_nav_deprecated_notice' ).remove();
			}
		} );
	} );

}( jQuery ) );
