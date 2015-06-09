/*global AWPCP*/
AWPCP.run( 'awpcp/admin-pointers', [ 'jquery', 'awpcp/pointers-manager', 'awpcp/settings' ],
function( $, PointersManager, settings ) {
    $( function() {
        var pointers = settings.get( 'pointers' );

        if ( pointers ) {
            PointersManager.createPointers( pointers );
        }
    } );
} );
