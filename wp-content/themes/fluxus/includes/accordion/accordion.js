jQuery(function(){


	jQuery(".typo .accordionsection").each( function( index ) {
		if( jQuery(this).children(":first").is("h1, h2, h3, h4, h5, h6") ){
			jQuery(this).children(":first").addClass("accordionsection__tab");
			jQuery(this).children(":first").wrapInner("<a></a>");
			jQuery(this).children().not(":first").wrapAll("<section class='accordionsection__content'></section>");
		}
	});


	var top = jQuery('body').scrollTop();
	var em = parseInt(jQuery('body').css('font-size'));

	jQuery('.accordionsection__tab').on('click touchstart', function(){
		jQuery('.accordionsection--current .accordionsection__content').slideUp(function(){
			jQuery(this).parent('.accordionsection').removeClass('accordionsection--current');
		});
		if(jQuery(this).parent('.accordionsection').children('.accordionsection__content').is(':hidden')){
			jQuery(this).parent('.accordionsection').children('.accordionsection__content').slideDown(function(){
				jQuery(this).parent('.accordionsection').addClass('accordionsection--current');
				jQuery('html, body').animate({scrollTop: jQuery(this).parent('.accordionsection').offset().top - (5 * em)}, '', '');
			});
		}
	});	



})