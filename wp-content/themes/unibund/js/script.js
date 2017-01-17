
jQuery(document).ready(function( $ ) {
  
	
    $('.gallery-item .gallery-icon a').fancybox();
 
    
	$(".qtranxs_text_de span").text("De");
	$(".qtranxs_text_en span").text("En");



	$( 'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".gif"], a[href$=".png"]' ).each( function () {
		var imageTitle = '';
		if ( jQuery( this ).next().hasClass( 'wp-caption-text' ) ) {
			imageTitle = jQuery( this ).next().text();
		} else if ( jQuery( this ).parent().next().hasClass( 'wp-caption-text' ) ) {
			imageTitle = jQuery( this ).parent().next().text();
		}
		jQuery( this ).attr( 'title', imageTitle );
	});



	
});


