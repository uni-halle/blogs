// JavaScript Document
jQuery( document ).ready( function() {
	var active = 0;
	if( jQuery.cookie( 'catchbox_ad_tab' ) ) {
		active = jQuery.cookie( 'catchbox_ad_tab' );
		jQuery.cookie( 'catchbox_ad_tab', null );
	}
	
	var tabs = jQuery( '#catchbox_ad_tabs' ).tabs( { active: active } );
	
	jQuery( '#wpbody-content form' ).submit( function() {
		var active = tabs.tabs( 'option', 'active' );
		jQuery.cookie( 'catchbox_ad_tab', active );
	} );
	
	jQuery( '.sortable' ).sortable( {
		handle: 'label',
		update: function( event, ui ) {
			var index = 1;
			var attrname = jQuery( this ).find( 'input:first' ).attr( 'name' );
			var attrbase = attrname.substring( 0, attrname.indexOf( '][' ) + 1 );
			
			jQuery( this ).find( 'tr' ).each( function() {
				jQuery( this ).find( '.count' ).html( index );
				jQuery( this ).find( 'input' ).attr( 'name', attrbase + '[' + index + ']' );
				index++;
			} );
		}
	} );
} );

// Show Hide Toggle Box
jQuery(document).ready(function ($){
	
	$(".option-content").hide();

	$("h3.option-toggle").click(function(){
	$(this).toggleClass("option-active").next().slideToggle("fast");
		return false; 
	});

});

jQuery(document).ready(function ($) {
    setTimeout(function () {
        $(".fade").fadeOut("slow", function () {
            $(".fade").remove();
        });
    }, 2000);

    //For Color picker
    $('.catchbox_color_picker').wpColorPicker();
});