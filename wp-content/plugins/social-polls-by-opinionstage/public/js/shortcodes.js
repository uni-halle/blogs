jQuery(function($) {
  $('[data-opinionstage-embed-url]').each(function( ) {
    var $widgetPlacement = $(this);
    var url = $widgetPlacement.data('opinionstage-embed-url');

    $.getJSON( url ).
      done( function( data ) {
        $widgetPlacement.after(data.code);
      })
      .fail(function(jqxhr, textStatus, error) {
        console.error( "[social-polls-by-opinionstage] can't load widget embed code" );
      });
  });
});
