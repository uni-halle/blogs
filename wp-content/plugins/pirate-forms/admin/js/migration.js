/* global jQuery, console, ajaxurl */

var PirateForms = window.PirateForms || {};
PirateForms.Admin = PirateForms.Admin || {};

PirateForms.Admin.Migration = PirateForms.Admin.Migration || (function ( document, window, $ ) {
	'use strict';

	/**
	 * Public functions and properties.
	 *
	 * @since 2.4.5
	 *
	 * @type {Object}
	 */
	var app = {

		/**
		 * Start the engine. DOM is not ready yet, use only to init something.
		 *
		 * @since 2.4.5
		 */
		init: function () {
			// Do that when DOM is ready.
			$( document ).ready( app.ready );
		},

		/**
		 * DOM is fully loaded.
		 *
		 * @since 2.4.5
		 */
		ready: function () {
			app.wpf_btn_action = $( '.pf-migration-action' );
			app.wpf_btn_import = $( '.js-pf-migration-import' );
			app.wpf_btn_log = $( '.pf-migration-step-message' );

			app.bindActions();
		},

		/**
		 * Bind DOM elements to certain events and vice versa.
		 *
		 * @since 2.4.5
		 */
		bindActions: function () {
			$( '.js-pf-migration-install' ).on( 'click', function ( e ) {
				e.preventDefault();

				if ( $( this ).hasClass( 'disabled' ) ) {
					return false;
				}

				app.processAction( 'install' );
			} );

			$( '.js-pf-migration-activate' ).on( 'click', function ( e ) {
				e.preventDefault();

				if ( $( this ).hasClass( 'disabled' ) ) {
					return false;
				}

				app.processAction( 'activate' );
			} );

			$( '.js-pf-migration-import' ).on( 'click', function ( e ) {
				if ( $( this ).hasClass( 'disabled' ) ) {
					e.preventDefault();

					return false;
				}

				return true;
			} );
		},

		/**
		 * Send a request to install or activate the WPForms plugin.
		 *
		 * @since 2.4.5
		 */
		processAction: function ( type ) {

			var action = 'pirateforms_migration_install';
			if ( type === 'activate' ) {
				action = 'pirateforms_migration_activate';
			}

			$.ajax( {
				 url: ajaxurl,
				 type: 'post',
				 dataType: 'json',
				 data: {
					 action: action,
					 nonce: $( '#pirateforms_migration_nonce' ).val()
				 },
				 beforeSend: function () {
					 app.wpf_btn_log.show();
					 console.log( app.wpf_btn_action );

					 app.wpf_btn_action.addClass( 'disabled' );
				 }
			 } )
			 .done( function ( response ) {

				 if ( ! response.success ) {
					 app.wpf_btn_log
						.addClass( 'error' )
						.text( response.data );

					 return;
				 }

				 app.wpf_btn_log
					.addClass( 'success' )
					.text( response.data );
			 } )
			 .fail( function ( jqXHR, textStatus ) {
				 /*
				  * Right now we are logging into browser console.
				  * In future that might be something better.
				  */
				 console.error( jqXHR );
				 console.error( textStatus );
			 } )
			 .always( function ( response ) {
				 if ( ! response.hasOwnProperty( 'success' ) ) {
					 return;
				 }

				 if ( ! response.success ) {
					 app.wpf_btn_action.removeClass( 'disabled' );
				 }
				 else {
					 app.wpf_btn_import.removeClass( 'disabled' );
				 }
			 } );

		}

	};

	// Provide access to public functions/properties.
	return app;

})( document, window, jQuery );

// Initialize.
PirateForms.Admin.Migration.init();
