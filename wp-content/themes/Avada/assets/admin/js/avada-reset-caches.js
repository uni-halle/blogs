/* global avadaReduxResetCaches */
function fusionResetCaches( e ) { // jshint ignore:line
	var data = {
			action: 'avada_reset_all_caches'
	    },
	    confirm = window.confirm( avadaReduxResetCaches.confirm );

	e.preventDefault();

	if ( true === confirm ) {
		jQuery( '.spinner.fusion-spinner' ).addClass( 'is-active' );
		jQuery.post( avadaReduxResetCaches.ajaxurl, data, function() {
			jQuery( '.spinner.fusion-spinner' ).removeClass( 'is-active' );
			window.confirm( avadaReduxResetCaches.success );
		} );
	}
}
