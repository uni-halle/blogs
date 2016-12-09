/*global AWPCP*/
AWPCP.define('awpcp/knockout-progress', [ 'jquery', 'knockout' ],
function( $, ko ) {
    ko.bindingHandlers.progress = {
        init: function(element, accessor) {
            var observable = accessor();
            $( element ).animate( { width: observable() } );
        },

        update: function(element, accessor) {
            var observable = accessor();
            $( element ).animate( { width: observable() } );
        }
    };
} );
