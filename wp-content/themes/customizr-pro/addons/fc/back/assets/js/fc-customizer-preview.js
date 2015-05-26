/*! Footer Customizer plugin by Nicolas Guillaume, GPL2+ licensed */
( function( $ ) {
	//featured page button text
	wp.customize( 'tc_theme_options[fc_copyright_text]' , function( value ) {
		value.bind( function( to ) {
			$( '.fc-copyright-text' ).html( to );
		} );
	} );
  wp.customize( 'tc_theme_options[fc_site_name]' , function( value ) {
    value.bind( function( to ) {
      $( '.fc-copyright-link' ).html( to );
    } );
  } );
  wp.customize( 'tc_theme_options[fc_site_link]' , function( value ) {
    value.bind( function( to ) {
      $( '.fc-copyright-link' ).attr( 'href' , to );
    } );
  } );
  wp.customize( 'tc_theme_options[fc_credit_text]' , function( value ) {
    value.bind( function( to ) {
      $( '.fc-credits-text' ).html( to );
    } );
  } );
  wp.customize( 'tc_theme_options[fc_designer_name]' , function( value ) {
    value.bind( function( to ) {
      $( '.fc-credits-link' ).html( to );
    } );
  } );
  wp.customize( 'tc_theme_options[fc_designer_link]' , function( value ) {
    value.bind( function( to ) {
      $( '.fc-credits-link' ).attr( 'href' , to );
    } );
  } );
} )( jQuery );

