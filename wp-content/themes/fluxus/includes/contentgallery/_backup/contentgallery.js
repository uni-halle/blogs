jQuery(document).ready(function(){
	
	
	
	//slider
	jQuery(".contentgalleryslider").each(function(){

		var count = 1;
		var num = jQuery(".contentgalleryslider__item").size();
		var gallery = jQuery(this);
		var galleryslider__items = gallery.find(".contentgalleryslider__item");
		var galleryslider__thumbs = gallery.find(".contentgalleryslider__thumb");
		var	galleryslider__controls = gallery.find(".contentgalleryslider__control");
		var galleryslider__captions = gallery.find(".contentgalleryslider__caption");
		var galleryslider__controlcaptionmixitems = jQuery(this).find(".contentgalleryslider__controlcaptionmixitem");
	
	
		galleryslider__items.hide();
		galleryslider__controls.hide();
		galleryslider__captions.hide();
		galleryslider__controlcaptionmixitems.hide();
	
	
		gallery.find('.contentgalleryslider__controlprev, .contentgalleryslider__controlnext, .contentgalleryslider__thumb').bind('click', function() {
	
			curr = count;
			
			if (jQuery(this).hasClass('contentgalleryslider__controlprev')) {
				count--;
			}
			if (jQuery(this).hasClass('contentgalleryslider__controlnext')) {
				count++;
			}
			if (jQuery(this).hasClass('contentgalleryslider__thumb')) {
				count = jQuery(this).attr('data');
			}
			
			//stack
			gallery.find('.contentgalleryslider__item--'+curr).fadeOut('fast', function() {
				gallery.find('.contentgalleryslider__item--'+count).fadeIn('slow');
			});
			
			// thumbs
			gallery.find('.contentgalleryslider__thumb--'+curr).removeClass( "contentgalleryslider__thumb--current" );
			gallery.find('.contentgalleryslider__thumb--'+count).addClass( "contentgalleryslider__thumb--current" );
			
			// controls
			gallery.find('.contentgalleryslider__control--'+curr).fadeOut('fast', function() {
				gallery.find('.contentgalleryslider__control--'+count).fadeIn('slow');
			});
			
			// captions
			gallery.find('.contentgalleryslider__caption--'+curr).fadeOut('fast', function() {
				gallery.find('.contentgalleryslider__caption--'+count).fadeIn('slow');
			});
			
			// controlcaptionmix
			gallery.find('.contentgalleryslider__controlcaptionmixitem--'+curr).fadeOut('fast', function() {
				gallery.find('.contentgalleryslider__controlcaptionmixitem--'+count).fadeIn('slow');
			});
	
		});
	
		if(count == 1) { 
			gallery.find('.contentgalleryslider__item--1').show();
			gallery.find('.contentgalleryslider__control--1').show();
			gallery.find('.contentgalleryslider__caption--1').show();
			gallery.find('.contentgalleryslider__controlcaptionmixitem--1').show(); 
			gallery.find('.contentgalleryslider__thumb--1').addClass( "contentgalleryslider__thumb--current" ); 
			}

	});
});
