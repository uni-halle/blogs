jQuery( document ).ready( function() {

	jQuery( '.fusion-builder-admin-toggle-heading' ).on( 'click', function() {
		jQuery( this ).parent().find( '.fusion-builder-admin-toggle-content' ).slideToggle( 300 );

		if ( jQuery( this ).find( '.fusion-builder-admin-toggle-icon' ).hasClass( 'fusion-plus' ) ) {
			jQuery( this ).find( '.fusion-builder-admin-toggle-icon' ).removeClass( 'fusion-plus' ).addClass( 'fusion-minus' );
		} else {
			jQuery( this ).find( '.fusion-builder-admin-toggle-icon' ).removeClass( 'fusion-minus' ).addClass( 'fusion-plus' );
		}

	});

	jQuery( '.enable-builder-ui .ui-button' ).on( 'click', function( e ) {
		e.preventDefault();

		jQuery( this ).parent().find( '#enable_builder_ui_by_default' ).val( jQuery( this ).data( 'value' ) );
		jQuery( this ).parent().find( '#enable_builder_sticky_publish_buttons' ).val( jQuery( this ).data( 'value' ) );
		jQuery( this ).parent().find( '.ui-button' ).removeClass( 'ui-state-active' );
		jQuery( this ).addClass( 'ui-state-active' );
	});

	jQuery( '.fusion-check-all' ).click( function( e ) {
		e.preventDefault();
		jQuery( this ).parents( '.fusion-builder-option' ).find( '.fusion-builder-option-field input' ).prop( 'checked', true );
	});

	jQuery( '.fusion-uncheck-all' ).click( function( e ) {
		e.preventDefault();
		jQuery( this ).parents( '.fusion-builder-option' ).find( '.fusion-builder-option-field input' ).prop( 'checked', false );
	});

});
