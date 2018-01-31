/*global AWPCP*/
AWPCP.run('awpcp/init-collapsible-elements', ['jquery', 'awpcp/jquery-collapsible'],
function($) {
    $(function(){
        $('.awpcp-categories-list .top-level-category').closest('li').collapsible();
    });
});

AWPCP.run( 'awpcp/init-categories-dropdown', [ 'jquery', 'awpcp/category-dropdown' ],
function( $ ) {
    $( function() {
        $( '.awpcp-category-dropdown' ).categorydropdown();
    } );
} );

AWPCP.run( 'awpcp/init-category-selector', [ 'jquery' ],
function( $ ) {
    $( function() {
        var $selectors = $( '.awpcp-category-selector .awpcp-category-dropdown' );

        $.subscribe( '/category/updated', function( event, $dropdown, category_id ) {
            if ( -1 === $selectors.index( $dropdown ) ) {
                return;
            }

            if ( $dropdown.closest( '.awpcp-category-dropdown-container' ).is( '.awpcp-multiple-dropdown-category-selector-container' ) ) {
                return;
            }

            // TODO: This is a hack. We should be able to detect only real change
            // events, not artificial events fired to let other parts of the UI
            // know what's the current value when the page loads.
            var previous_value = $dropdown.data( 'awpcp-category-selector-previous-value' );
            $dropdown.data( 'awpcp-category-selector-previous-value', category_id );

            if ( typeof previous_value === 'undefined' ) {
                return;
            }

            if ( category_id === previous_value ) {
                return;
            }

            $dropdown.closest( 'form' ).submit();
        } );
    } );
} );

AWPCP.run( 'awpcp/reload-payment-completed-page', ['jquery'],
function( $ ) {
    $( function() {
        var $form = $( '#awpcp-payment-completed-form' );

        if ( 0 === $form.length ) {
            return;
        }

        var payment_status = $form.find( ':hidden[name="payment_status"]' ).val();

        if ( 'Not Verified' !== payment_status ) {
            return;
        }

        setTimeout( function() {
            location.reload();
        }, 5000 );
    } );
} );
