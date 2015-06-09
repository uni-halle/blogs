/*global AWPCP*/
AWPCP.define( 'awpcp/pointers-manager', [ 'jquery' ],
function( $ ) {

    var PointersManager = function() {};

    $.extend( PointersManager.prototype, {
        createPointers: function( pointers ) {
            var self = this;

            $.each( pointers, function( index, model ) {
                self.createPointer( model );
            } );
        },

        createPointer: function( model ) {
            var self = this;

            model.element = $( '#wpadminbar' ).pointer( {
                pointerClass: 'wp-pointer awpcp-pointer',
                pointerWidth: 470,
                position: model.position,
                content: model.content,
                buttons: self.createPointerButtons( model )
            } ).pointer( 'open' );
        },

        createPointerButtons: function( model ) {
            var self = this;

            return function() {
                var buttons = $( '<div />' );

                $.each( model.buttons, function( index, button ) {
                    var element = $( '<a>' );

                    element.text( button.label );
                    element.addClass( button.elementClass );
                    element.css( button.elementCSS );

                    element.on( 'click', function( event ) {
                        self.onButtonClicked( event, button, model );
                    } );

                    buttons.append( element );
                } );

                return buttons;
            };
        },

        onButtonClicked: function( event, button, model ) {
            event.preventDefault();

            var data;

            if ( button.data && button.data.slice ) {
                data = button.data.slice();
            } else {
                data = [];
            }

            data.splice( 0, 0, button.event, model );

            $.publish( '/pointer/' + button.event, data );
        }
    } );

    return new PointersManager();
} );
