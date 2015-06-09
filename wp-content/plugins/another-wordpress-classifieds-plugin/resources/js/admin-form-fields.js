/*global AWPCP, ajaxurl*/
AWPCP.run( 'awpcp/listing-admin-page', [
    'jquery'
], function( $, settings ) {
    $( function() {
        $( '#the-list .awpcp-sortable-handle' ).mousedown( setColumnStyleExplicitely );

        $( '#the-list' )
            .sortable( {
                axis: 'y',
                containment: 'parent',
                cursor: 'move',
                handle: '.awpcp-sortable-handle',
                opacity: 0.6
            } )
            .disableSelection()
            .on( 'sortstop', removeExplicitStylesFromColumn )
            .on( 'sortstop', saveFormFieldsOrder );

        function setColumnStyleExplicitely( event ) {
            var row, column, backgroundColor;

            $( this ).closest( 'tr' ).find( 'td, th' ).each( function() {
                column = $(this);
                row = column.closest( 'tr' );

                if ( row.hasClass( 'alternate' ) ) {
                    backgroundColor = row.css( 'background-color' );
                } else {
                    backgroundColor = 'white';
                }

                column.data( 'originalStyle', column.get(0).style.cssText );
                column.css( { width: column.width(), backgroundColor: backgroundColor } );
            } );
        }

        function removeExplicitStylesFromColumn( event, ui ) {
            ui.item.find( 'td, th' ).each( function() {
                $( this ).get(0).style.cssText = $( this ).data( 'originalStyle' );
            } );
        }

        function saveFormFieldsOrder( event, ui ) {
            showProgressIndicator( ui.item );

            $.ajax( ajaxurl, {
                data: {
                    selected: ui.item.attr( 'id' ),
                    action: 'awpcp-update-form-fields-order',
                    'awpcp-form-fields-order': $( event.target ).sortable( 'toArray' )
                },
                dataType: 'json',
                success: processResponse,
                type: 'POST'
            } );
        }

        function showProgressIndicator( row ) {
            row.find( '.awpcp-spinner' ).show();
        }

        function processResponse( response, status, xhr ) {
            var row;

            if ( response.status == 'ok'  ) {
                row = $( '#' + response.selected ).effect( 'highlight' );

                hideProgressIndicator( row );
                updateRowsBackgroundColor( row );
            }
        }

        function hideProgressIndicator( row ) {
            row.find( '.awpcp-spinner' ).hide();
        }

        function updateRowsBackgroundColor( row ) {
            row.closest( 'tbody' ).find( 'tr' ).each( function( index, current ) {
                $( current )[ index % 2 == 0 ? 'addClass' : 'removeClass' ]( 'alternate' );
            } );
        }
    } );
} );
