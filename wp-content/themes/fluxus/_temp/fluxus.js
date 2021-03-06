jQuery(document).ready(function(){


	// W I N C K E L M A N N


	// cycle thumbnails
/*
	thumbnailsrc = "";
	addimgsrc = "";
	jQuery('.pagelist__item').each(function(){
		jQuery(this).waypoint(function(direction) {
			if(direction === 'down'){
				if(jQuery(this.element).attr('thumbnail') != thumbnailsrc){
					thumbnailsrc = jQuery(this.element).attr('thumbnail');
					if(!thumbnailsrc){
						jQuery('.body__backgroundupperinner--current').removeClass('body__backgroundupperinner--current').fadeOut("slow");
					} else {
						if(jQuery('.body__backgroundupperinner--current').attr('thumbnail') != thumbnailsrc){
							jQuery('.body__backgroundupperinner--current').removeClass('body__backgroundupperinner--current').addClass('body__backgroundupperinner--hidden').fadeOut("slow");
							jQuery('.body__backgroundupperinner[thumbnail="' +  thumbnailsrc + '"]').removeClass('body__backgroundupperinner--hidden').addClass('body__backgroundupperinner--current').fadeIn("slow");
						}
					}
				}
				if(jQuery(this.element).attr('addimg') != addimgsrc){
					addimgsrc = jQuery(this.element).attr('addimg');
					if(!addimgsrc){
						jQuery('.body__backgroundlowerinner--current').removeClass('body__backgroundlowerinner--current').fadeOut("slow");
					} else {
						if(jQuery('.body__backgroundlowerinner--current').attr('addimg') != addimgsrc){
							jQuery('.body__backgroundlowerinner--current').removeClass('body__backgroundlowerinner--current').addClass('body__backgroundlowerinner--hidden').fadeOut("slow");
							jQuery('.body__backgroundlowerinner[addimg="' +  addimgsrc + '"]').removeClass('body__backgroundlowerinner--hidden').addClass('body__backgroundlowerinner--current').fadeIn("slow");
						}
					}
				}
			}
		}, { offset: '25%' });
		jQuery(this).waypoint(function(direction) {
			if(direction === 'up'){
				if(jQuery(this.element).attr('thumbnail') != thumbnailsrc){
					thumbnailsrc = jQuery(this.element).attr('thumbnail');
					if(!thumbnailsrc){
						jQuery('.body__backgroundupperinner--current').removeClass('body__backgroundupperinner--current').fadeOut("slow");
					} else {
						if(jQuery('.body__backgroundupperinner--current').attr('thumbnail') != thumbnailsrc){
							jQuery('.body__backgroundupperinner--current').removeClass('body__backgroundupperinner--current').addClass('body__backgroundupperinner--hidden').fadeOut("slow");
							jQuery('.body__backgroundupperinner[thumbnail="' +  thumbnailsrc + '"]').removeClass('body__backgroundupperinner--hidden').addClass('body__backgroundupperinner--current').fadeIn("slow");
						}
					}
				}
				if(jQuery(this.element).attr('addimg') != addimgsrc){
					addimgsrc = jQuery(this.element).attr('addimg');
					if(!addimgsrc){
						jQuery('.body__backgroundlowerinner--current').removeClass('body__backgroundlowerinner--current').fadeOut("slow");
					} else {
						if(jQuery('.body__backgroundlowerinner--current').attr('addimg') != addimgsrc){
							jQuery('.body__backgroundlowerinner--current').removeClass('body__backgroundlowerinner--current').addClass('body__backgroundlowerinner--hidden').fadeOut("slow");
							jQuery('.body__backgroundlowerinner[addimg="' +  addimgsrc + '"]').removeClass('body__backgroundlowerinner--hidden').addClass('body__backgroundlowerinner--current').fadeIn("slow");
						}
					}
				}
			}
		}, { offset: '-75%' });
	});
*/






	//jQuery('.pagelist').fullpage();



	//jQuery('img').removeAttr('width').removeAttr('height');
	


	var i = 1;
	var thumbnailurl;
	var oldthumbnailurl;
	var key = 1;
	var oldkey = 0;
	var scale = 1.05;

	// scrollify
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
			
			// hier weitermachen – waypoints brauchen wir nicht mehr			
			
			jQuery('.body__backgroundupperinner[thumbnail="' + jQuery.scrollify.current().attr('thumbnail') + '"]').css('transform', 'scale(1)').fadeIn("slow", function(){
				jQuery('.body__backgroundupperinner').not('[thumbnail="' + jQuery.scrollify.current().attr('thumbnail') + '"]').fadeOut("slow", function(){
					jQuery(this).css('transform', 'scale(1)');
				});
			});
			jQuery('.body__backgroundlowerinner[addimg="' + jQuery.scrollify.current().attr('addimg') + '"]').css('transform', 'scale(1)').fadeIn("slow", function(){
				jQuery('.body__backgroundlowerinner').not('[addimg="' + jQuery.scrollify.current().attr('addimg') + '"]').fadeOut("slow", function(){
					jQuery(this).css('transform', 'scale(1)');
				});
			});
			
			var zoom = 1 + parseInt(jQuery.scrollify.current().attr('zoomcount')) / parseInt(20);
			
			jQuery('.body__backgroundupperinner').css('transform', 'scale(' + zoom + ')');
			
			if(parseInt(jQuery.scrollify.current().attr('key')) > parseInt(oldkey)) {
				console.log('down');
			} else {
				console.log('up');
			}
			oldkey = jQuery.scrollify.current().attr('key');


		},
		after:function() {},
		afterResize:function() {},
		afterRender:function() {}
	});

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
	jQuery('.navlist__list a').on('click touchstart', function(event) {
		event.preventDefault();
	});
	jQuery('.navlist__list .page_item_has_children').on('click touchstart', function() {
		jQuery(this).parent('ul').parent().children('ul').children('li').not(this).find('ul').slideUp();
		jQuery(this).children('ul').slideDown();
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