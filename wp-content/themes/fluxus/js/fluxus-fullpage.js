jQuery(document).ready(function(){

	// W I N C K E L M A N N




	var oldkey;
	
	// load first image of sidebar
	jQuery('.body__backgroundupperinner').first().css('background-image', 'url(' + jQuery('.body__backgroundupperinner').first().attr('thumbnail') + ')').fadeIn("slow");

	// scrollify
/*
	jQuery.scrollify({
		section : ".pagelist__item",
		sectionName : "section-name",
		interstitialSection : "",
		easing: "easeOutExpo",
		scrollSpeed: 1100,
		offset : 0,
		scrollbars: true,
		standardScrollElements: "",
		setHeights: true,
		overflowScroll: true,
		updateHash: true,
		touchScroll:true,
		before:function() {
			
			var zoom = 1 + parseInt(jQuery.scrollify.current().attr('zoomcount')) / parseInt(20);
			
			// load current image of sidebar
			jQuery('.body__backgroundupperinner[thumbnail="' + jQuery.scrollify.current().attr('thumbnail') + '"]').css('background-image', 'url(' + jQuery.scrollify.current().attr('thumbnail') + ')');
			
			
			if(parseInt(jQuery.scrollify.current().attr('key')) > parseInt(oldkey)) {
				// down
				jQuery('.body__backgroundupperinner[thumbnail="' + jQuery.scrollify.current().attr('thumbnail') + '"]').fadeIn("slow", function(){
					jQuery(this).css('transform', 'scale(' + zoom + ')');
					jQuery('.body__backgroundupperinner').not('[thumbnail="' + jQuery.scrollify.current().attr('thumbnail') + '"]').fadeOut("slow");
				});
				jQuery('.body__backgroundlowerinner[addimg="' + jQuery.scrollify.current().attr('addimg') + '"]').fadeIn("slow", function(){
					jQuery('.body__backgroundlowerinner').not('[addimg="' + jQuery.scrollify.current().attr('addimg') + '"]').fadeOut("slow");
				});
			} else {
				// up
				jQuery('.body__backgroundupperinner[thumbnail="' + jQuery.scrollify.current().attr('thumbnail') + '"]').fadeIn("slow", function(){
					jQuery(this).css('transform', 'scale(' + zoom + ')');
					jQuery('.body__backgroundupperinner').not('[thumbnail="' + jQuery.scrollify.current().attr('thumbnail') + '"]').fadeOut("slow");
				});
				jQuery('.body__backgroundlowerinner[addimg="' + jQuery.scrollify.current().attr('addimg') + '"]').fadeIn("slow", function(){
					jQuery('.body__backgroundlowerinner').not('[addimg="' + jQuery.scrollify.current().attr('addimg') + '"]').fadeOut("slow");
				});
			}
			oldkey = jQuery.scrollify.current().attr('key');
		},
		after:function() {},
		afterResize:function() {},
		afterRender:function() {}
	});
*/
	jQuery('.pagelist').fullpage();
	
	//jQuery('img').lazyload();

	// force resize to calculate correctly
	jQuery(window).trigger('resize');




















	// solve problem with navbar on touch devices - make #navbar touchable
	if ('ontouchstart' in document.documentElement) { // detects touch devices
		jQuery('#navbar li.page_item_has_children > a').click(function() {
			if(jQuery(this).parent('li').hasClass( "touched" )) {
				return true;
			} else {
				jQuery(this).parent('li').addClass( "touched" );
				return false
			}
		});
	}



	// navlist
	
	jQuery(".navlist__list").hide();
	
	jQuery('.navlist__icon').on('click touchstart', function() {
		jQuery('.navlist__list').slideToggle();
	});
	jQuery('.navlist__list .page_item_has_children > a').on('click touchstart', function(event) {
		if(jQuery(this).hasClass('active')){
			jQuery('.navlist__list a').removeClass("active");
		} else {
			jQuery(this).parent('li').parent('ul').parent().children('ul').children('li').children('a').not(this).parent('li').find("ul").slideUp();
			jQuery(this).next("ul").slideDown();
			jQuery(this).addClass("active");
			return false;
		}
	});
	jQuery(window).bind( 'hashchange', function(event) {
		jQuery('.navlist__list').slideUp();
	});




	// enable masonry for class »masonry« and childrens
	var jQuerycontainer = jQuery('.masonry'); // init masonry
	jQuerycontainer.imagesLoaded( function() { jQuerycontainer.masonry(); }); // initialize Masonry after all images have loaded



});





// help internet explorer handling those new HTML5 elements
document.createElement('header');
document.createElement('footer');
document.createElement('section');
document.createElement('aside');
document.createElement('nav');
document.createElement('article');
document.createElement('figure');
document.createElement('figcaption');
document.createElement('hgroup');
document.createElement('menu');
document.createElement('summary');
document.createElement('command');
document.createElement('datalist');
document.createElement('source');
document.createElement('audio');
document.createElement('video');