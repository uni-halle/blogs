/*global AWPCP, plupload*/
AWPCP.run( 'awpcp/plupload-queue-translation', [ 'jquery', 'awpcp/settings' ],
function( $, settings ) {
    $( function() {
        plupload.addI18n( settings.l10n( 'plupload-queue' ) );
    } );
} );
