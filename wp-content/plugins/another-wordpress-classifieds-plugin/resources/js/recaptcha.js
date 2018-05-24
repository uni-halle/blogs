/*global AWPCP, grecaptcha*/
AWPCP.run('awpcp/init-recaptcha', ['jquery'],
function($) {
    var maxAttempts = 45,
        attempts = 0,
        maxDelay = 1500,
        timeout = false;

    var renderReCaptchaWidgets = function() {
        $( '.awpcp-recaptcha' ).each( function() {
            var element = $( this );

            if ( ! element.data( 'awpcp-recaptcha' ) ) {
                grecaptcha.render( this, {
                  'sitekey' : element.attr( 'data-sitekey' ),
                  'theme' : 'light'
                } );

                element.data( 'awpcp-recaptcha', true );
            }
        } );
    };

    var waitForReCaptchaToBeReady = function() {
        attempts = attempts + 1;

        if ( typeof grecaptcha !== 'undefined' && typeof grecaptcha.render !== 'undefined' ) {
            renderReCaptchaWidgets();
        } else if ( attempts <= maxAttempts ) {
            timeout = setTimeout( waitForReCaptchaToBeReady, maxDelay * Math.pow( attempts / maxAttempts, 2 ) );
        }
    };

    window['AWPCPreCAPTCHAonLoadCallback'] = function() {
        if ( timeout ) {
            clearTimeout( timeout );
        }

        renderReCaptchaWidgets();
    };

    $( waitForReCaptchaToBeReady );
});
