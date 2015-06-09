/* global AWPCP */

AWPCP.define('awpcp/jquery-messages', [ 'jquery', 'knockout', 'awpcp/messages', 'awpcp/settings' ],
function( $, ko, Messages, settings ) {
    $.fn.AWPCPMessages = function() {
        return $(this).each( function() {
            var options = settings.get( 'messages-data-for-' + $(this).attr( 'data-component-id' ) );
            ko.applyBindings( new Messages( options ), $(this).get(0) );
        } );
    };
} );
