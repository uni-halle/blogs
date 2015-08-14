/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Update the site title in real time...
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '.main-title a' ).html( newval );
		} );
	} );
	
	//Update the site description in real time...
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.site-description' ).html( newval );
		} );
	} );
	
	// background color
	wp.customize( 'generate_settings[background_color]', function( value ) {
		value.bind( function( newval ) {
			$( 'body' ).css('background-color', newval );
		} );
	} );
	
	// Text color
	wp.customize( 'generate_settings[text_color]', function( value ) {
		value.bind( function( newval ) {
			$( '.inside-article, .comments-area, .page-header, .one-container .container, .paging-navigation, .inside-page-header' ).css('color', newval );
		} );
	} );
	
	// Link color
	wp.customize( 'generate_settings[link_color]', function( value ) {
		value.bind( function( newval ) {
			$( '.site-content a, .site-content a:visited' ).css('color', newval );
		} );
	} );
	
	// Container width
	wp.customize( 'generate_settings[container_width]', function( value ) {
		value.bind( function( newval ) {
			$( 'body .grid-container' ).css('max-width', newval + 'px' );
		} );
	} );
	
} )( jQuery );