/* global AWPCP */

AWPCP.define( 'awpcp/messages', [ 'jquery', 'knockout' ],
function( $, ko ) {
    var Messages = function( options ) {
        var self = this;

        self.messages = ko.observableArray( [] );

        $.each( options.channels, function( index, channel ) {
            $.subscribe( '/messages/' + channel, onNewMessage );
        } );

        function onNewMessage( event, message ) {
            self.messages.push( message );
            scheduleMessageQueueCleanUp();
        }

        function scheduleMessageQueueCleanUp() {
            setTimeout( function() {
                var messagesCount = self.messages().length;
                if ( messagesCount > 10 ) {
                    self.messages.splice( 0, messagesCount - 10 );
                }
            }, 20000 );
        }
    };

    return Messages;
} );
