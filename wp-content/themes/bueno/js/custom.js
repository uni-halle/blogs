jQuery(function() {
 
	jQuery('#toTop').click(function() {
		jQuery('body,html').animate({scrollTop:0},800);
	});	

	// Lightbox Initalize
	jQuery(".post_format_image a").append('<span class="hover_overlay"><i class="icon-plus-sign"></i></span>');
	jQuery(".post_format_image a").magnificPopup({
		type: 'image',
		removalDelay: 500, //delay removal by X to allow out-animation
		callbacks: {
		    beforeOpen: function() {
		      // just a hack that adds mfp-anim class to markup 
		       this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
		       this.st.mainClass = this.st.el.attr('data-effect');
		    }
		}
	});
	jQuery(".lightbox_img").append('<span class="hover_overlay"><i class="icon-plus-sign"></i></span>');
	jQuery(".lightbox_img").magnificPopup({
		type: 'image',
		removalDelay: 500, //delay removal by X to allow out-animation
		callbacks: {
		    beforeOpen: function() {
		      // just a hack that adds mfp-anim class to markup 
		       this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
		       this.st.mainClass = this.st.el.attr('data-effect');
		    }
		}
	});
	jQuery(".format_image_wrap a").magnificPopup({
		type: 'image',
		removalDelay: 500, //delay removal by X to allow out-animation
		callbacks: {
		    beforeOpen: function() {
		      // just a hack that adds mfp-anim class to markup 
		       this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
		       this.st.mainClass = this.st.el.attr('data-effect');
		    }
		}
	});
	jQuery(".lightbox_img").live({
	    mouseenter: function () {
	    	jQuery(this).find('.hover_overlay').stop().animate({opacity:0.7}, 400)
			jQuery(this).find('.icon-plus-sign').stop().animate({opacity: 0.9, top: '50%' }, 300, "easeOutBack")
	    },
	    mouseleave: function () {
	    	jQuery(this).find('.hover_overlay').stop().animate({opacity:0}, 300)
			jQuery(this).find('.icon-plus-sign').stop().animate({opacity: 0, top: '-5%' }, 300, "easeInBack")
	    }
	});

	jQuery(".search-trigger.tr-hidden").click(
		function(e){
			var par_el = jQuery(this).parent();
			par_el.addClass('opened');
			jQuery(this).css('visibility', 'hidden').removeClass('tr-hidden');
			e.stopPropagation();
		}
	);

	jQuery(document).click(function() {
		var par_el = jQuery("#header_search_form")
		if ( par_el.hasClass("opened") ) {
			par_el.removeClass("opened");
			par_el.find('.search-trigger').css('visibility', 'visible').addClass('tr-hidden');
		}
	});
	jQuery("#header_search_form").click(
		function(e){
			if ( jQuery(this).hasClass("opened") ) {
				e.stopPropagation();
			}
		}
	)


	jQuery('.post_comment').each(
		function(){
			if ( !jQuery(this).find('.comments-link').length ) {
				jQuery(this).wrapInner('<b />').prepend('<i class="icon-comments"></i>').wrapInner('<span class="comments-link" />');
			} 
		}
	)

	jQuery('.navbar_inner > ul > li > a, .navbar_inner > div > ul > li > a').append('<span class="hover_mask"></span>');


	jQuery(function(){
		if (!(jQuery.browser.msie && jQuery.browser.version == '8.0')) {
			if (jQuery('.two_column_wrapper').length) {

				if (jQuery(window).width() < 768) {
					jQuery('.two_column_wrapper').attr("data-resize", "false");
					var page_content = jQuery('.two_column_wrapper').clone();
					jQuery('.two_column_wrapper').empty();
					var count = page_content.children().children().length;
					for (var i = 0; i < count; i++) {
						var postnum = i + 1;
						page_content.find('.two_column_item').each(function(){
							if (jQuery(this).attr('data-count') == postnum) {
								jQuery('.two_column_wrapper').append(jQuery(this));
							}
						})
					}
				}

				jQuery(window).on("debouncedresize", function( event, bueno_resized ) {

					if (jQuery(window).width() < 768) {
						jQuery('.two_column_wrapper').attr("data-resize", "false");
						var page_content = jQuery('.two_column_wrapper').clone();
						jQuery('.two_column_wrapper').empty();
						var count = page_content.children().children().length;
						for (var i = 0; i < count; i++) {
							var postnum = i + 1;
							page_content.find('.two_column_item').each(function(){
								if (jQuery(this).attr('data-count') == postnum) {
									jQuery('.two_column_wrapper').append(jQuery(this));
								}
							})
						}
						jQuery(".lightbox_img").magnificPopup({
							type: 'image',
							removalDelay: 500, //delay removal by X to allow out-animation
							callbacks: {
							    beforeOpen: function() {
							      // just a hack that adds mfp-anim class to markup 
							       this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
							       this.st.mainClass = this.st.el.attr('data-effect');
							    }
							}
						});

					} else {
						if ( jQuery('.two_column_wrapper').attr("data-resize") == "false" ) {
							jQuery('.two_column_wrapper').attr("data-resize", "true");
							var page_content = jQuery('.two_column_wrapper').clone();
							jQuery('.two_column_wrapper').empty();
							jQuery('.two_column_wrapper').append('<div class="span4 column_1"></div><div class="span4 column_2"></div>');
							var count = page_content.children().length;
							page_content.find('.two_column_item').each(function(){
								var postnum = jQuery(this).attr('data-count');
								if ( postnum%2 == 1 ) {
									jQuery('.two_column_wrapper .column_1').append(jQuery(this));
								} else {
									jQuery('.two_column_wrapper .column_2').append(jQuery(this));
								}
							})
							jQuery(".lightbox_img").magnificPopup({
								type: 'image',
								removalDelay: 500, //delay removal by X to allow out-animation
								callbacks: {
								    beforeOpen: function() {
								      // just a hack that adds mfp-anim class to markup 
								       this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
								       this.st.mainClass = this.st.el.attr('data-effect');
								    }
								}
							});
						}
					}
				});

			}
		}
	})
	
});