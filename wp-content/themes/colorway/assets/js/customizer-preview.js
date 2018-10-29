/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

( function( $ ) {

	/*
	 * Site Identity Logo Width
	 */
	wp.customize( 'colorway-settings[cwy-header-responsive-logo-width]', function( setting ) {
		setting.bind( function( logo_width ) {
			if ( logo_width['desktop'] != '' || logo_width['tablet'] != '' || logo_width['mobile'] != '' ) {
				var dynamicStyle = '#masthead .site-logo-img .custom-logo-link img { max-width: ' + logo_width['desktop'] + 'px;} .colorway-logo-svg{width: ' + logo_width['desktop'] + 'px;} @media( max-width: 768px ) { #masthead .site-logo-img .custom-logo-link img { max-width: ' + logo_width['tablet'] + 'px;} .colorway-logo-svg{width: ' + logo_width['tablet'] + 'px; } } @media( max-width: 544px ) { .cwy-header-break-point .site-branding img, .cwy-header-break-point #masthead .site-logo-img .custom-logo-link img { max-width: ' + logo_width['mobile'] + 'px;} .colorway-logo-svg{width: ' + logo_width['mobile'] + 'px; } }';
				colorway_add_dynamic_css( 'cwy-header-responsive-logo-width', dynamicStyle );
			}
			else{
				wp.customize.preview.send( 'refresh' );
			}
		} );
	} );






} )( jQuery );
