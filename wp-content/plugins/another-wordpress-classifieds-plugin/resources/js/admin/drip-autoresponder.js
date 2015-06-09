/*global AWPCP*/
/*jshint latedef: false*/
AWPCP.run( 'awpcp/drip-autoresponder', [ 'jquery', 'awpcp/pointers-manager', 'awpcp/settings' ],
function( $, PointersManager, settings ) {
    $.subscribe( '/pointer/awpcp-autoresponder-user-subscribed', onUserSubscribed );
    $.subscribe( '/pointer/awpcp-autoresponder-dismissed', onAutoresponderDismissed );
    $.subscribe( '/pointer/awpcp-autoresponder-confirmation-dismissed', onAutoresponderConfirmationDismissed );

    function onUserSubscribed( event, action, model, nonce ) {
        var widget = model.element.pointer( 'widget' ),
            spinner = widget.find( '.awpcp-spinner' ).show(),
            errors = widget.find( '.awpcp-message' ).empty().addClass( 'is-hidden' );

        $.post( settings.get( 'ajaxurl' ), {
            action: action,
            email: widget.find( '[name="awpcp-user-email"]' ).val(),
            nonce: nonce
        }, function( data ) {
            if ( data.status === 'ok' ) {
                model.element.pointer( 'destroy' );
                PointersManager.createPointer( data.pointer );
            } else if ( data.status === 'error' && typeof data.error !== 'undefined' && data.error.length > 0 ) {
                errors.removeClass( 'is-hidden' ).append( $('<p>' + data.error + '</p>') );
                spinner.hide();
            } else {
                model.element.pointer( 'destroy' );
            }
        } );
    }

    function onAutoresponderDismissed( event, action, model, nonce ) {
        $.post( settings.get( 'ajaxurl' ), {
            action: action,
            nonce: nonce
        });

        model.element.pointer( 'destroy' );
    }

    function onAutoresponderConfirmationDismissed( event, action, model ) {
        model.element.pointer( 'destroy' );
    }
} );
