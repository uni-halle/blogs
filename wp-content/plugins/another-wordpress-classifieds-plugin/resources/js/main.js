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
