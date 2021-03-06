jQuery(document).ready(function(){


	// W I N C K E L M A N N
/*
	jQuery('.pagelist__imgbreak').each(function(){
		jQuery(this).waypoint(function(direction) {
			var thumbnailsrc = "";
			if(direction === 'down'){
				if(jQuery(this.element).children('.pagelist__item:first').find('.pagelist__imgbreakimg').attr('src') != thumbnailsrc){
					var thumbnailsrc = jQuery(this.element).find('.pagelist__imgbreakimg').attr('src');
					//jQuery('.body__backgroundupper').css('background-image', 'url(' +  thumbnailsrc + ')');
					jQuery('.body__backgroundupper').children('img').remove();
					jQuery('.body__backgroundupper').prepend('<img class="body__backgroundupperimg" src="' +  thumbnailsrc + '" />');
				}
				offset = jQuery(this.element).offset().top;
				height = jQuery(this.element).height();
			}
		}, { offset: '0%' });
		jQuery('.pagelist__imgbreak').waypoint(function(direction) {
			var thumbnailsrc = "";
			if(direction === 'up'){
				if(jQuery(this.element).children('.pagelist__item:first').find('.pagelist__imgbreakimg').attr('src') != thumbnailsrc){
					var thumbnailsrc = jQuery(this.element).find('.pagelist__imgbreakimg').attr('src');
					//jQuery('.body__backgroundupper').css('background-image', 'url(' +  thumbnailsrc + ')');
					jQuery('.body__backgroundupper').children('img').remove();
					jQuery('.body__backgroundupper').prepend('<img class="body__backgroundupperimg" src="' +  thumbnailsrc + '" />');
				}
				offset = jQuery(this.element).offset().top;
				height = jQuery(this.element).height();
			}
		}, { offset: '-100%' });
	});
*/

	jQuery('.pagelist__item').each(function(){
		var thumbnailsrc = "";
		jQuery(this).waypoint(function(direction) {
			var thumbnailsrc = "";
			if(direction === 'down'){
				if(jQuery(this.element).find('.pagelist__imgbreakimg').attr('src') != thumbnailsrc){
					var thumbnailsrc = jQuery(this.element).find('.pagelist__imgbreakimg').attr('src');
					jQuery('.body__backgroundupperinner').css('background-image', 'url(' +  thumbnailsrc + ')');
				}
			}
		}, { offset: '25%' });
		jQuery(this).waypoint(function(direction) {
			var thumbnailsrc = "";
			if(direction === 'up'){
				if(jQuery(this.element).find('.pagelist__imgbreakimg').attr('src') != thumbnailsrc){
					var thumbnailsrc = jQuery(this.element).find('.pagelist__imgbreakimg').attr('src');
					jQuery('.body__backgroundupperinner').css('background-image', 'url(' +  thumbnailsrc + ')');
				}
			}
		}, { offset: '-75%' });
	});



/*
	jQuery('.pagelist__imgbreak').each(function(){
		jQuery(this).waypoint(function(direction) {
			if(direction === 'down'){
				offset = jQuery(this.element).offset().top;
				height = jQuery(this.element).height();
			}
		}, { offset: '25%' });
		jQuery(this).waypoint(function(direction) {
			if(direction === 'up'){
				offset = jQuery(this.element).offset().top;
				height = jQuery(this.element).height();
			}
		}, { offset: '-75%' });
	});

	jQuery(window).scroll(function () {
		var scrolled = jQuery(window).scrollTop() - offset;
		if(scrolled > 0){
			var scrolledpercent = scrolled / (height / 100);
			console.log(scrolledpercent);
		}
		var scrolledpercent = scrolled / (height / 100);
		console.log(scrolledpercent);
		if(scrolledpercent > 0){
			jQuery('.body__backgroundupperinner').css('transform', 'scale(' + (1.0 + (scrolledpercent / 1000)) + ')');
		}
	});
*/






	



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
		before:function() {},
		after:function() {},
		afterResize:function() {},
		afterRender:function() {}
	  });




















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
		jQuery('.navlist__list .page_item_has_children > a').next("ul").slideUp();
		event.preventDefault();
		jQuery(this).next("ul").slideDown();
		jQuery(this).unbind(event);
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