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
