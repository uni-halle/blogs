/*global AWPCP, grecaptcha*/
AWPCP.run('awpcp/init-recaptcha', ['jquery'],
function($) {
    window['AWPCPreCAPTCHAonLoadCallback'] = function() {
        $( '.awpcp-recaptcha' ).each( function() {
            var element = $( this ), widget;

            widget = grecaptcha.render( this, {
              'sitekey' : element.attr( 'data-sitekey' ),
              'theme' : 'light'
            } );
        } );
    };
});
