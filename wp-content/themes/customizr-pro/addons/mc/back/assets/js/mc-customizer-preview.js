/*! Menu Customizer preview by Nicolas Guillaume - Rocco Aliberti, GPL2+ licensed */
( function( $ ) {
  var OptionPrefix      = PCMCPreviewParams.OptionPrefix,
      $_body            = $('body'),
      api               = wp.customize,
      $_bmenu = $('.tc-header .btn-toggle-nav');

  api( OptionPrefix + '[tc_mc_effect]' , function( value ) {
    value.bind( function( to ) {
        /*
        * change the current on open effect
        * this means change the sidenav class sn-left|right(-EFFECT)
        * If already open, before the replacement takes place, we close the sidenav,
        * and simulate a click(touchstart) to re-open it afterwards
        */
          var _refresh            = false,
              _current_effect     = $_body.attr('class').match(/sn-(left|right)-(mc_\w+)($|\s)/);

          if ( ! ( _current_effect && _current_effect.length > 2 ) )
            return;

          if ( $_body.hasClass('tc-sn-visible') ) {
              $_body.removeClass('tc-sn-visible');
              _refresh = true;
          }
          $_body.removeClass( _current_effect[0] ).
                 addClass( _current_effect[0].replace( _current_effect[2] , to ) );
          if ( _refresh ) {
            setTimeout( function(){
                $_bmenu.trigger('click').trigger('touchstart');
            }, 200);
          }

    });
  });
})( jQuery );
