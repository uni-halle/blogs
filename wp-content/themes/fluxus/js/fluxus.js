jQuery(document).ready(function(){





	// zoom for expoimages
/*
	var zoomfw = 1 + (parseInt(jQuery('.body__backgroundupperinner').attr('count')) + 1) / parseInt(10);
	var zoomrw = 1 + (parseInt(jQuery('.body__backgroundupperinner').attr('count')) - 1) / parseInt(10);
	jQuery('.fw .body__backgroundupperinner').css('transform', 'scale(' + zoomfw + ')');
	jQuery('.rw .body__backgroundupperinner').css('transform', 'scale(' + zoomrw + ')');
*/





	// navlist	
	jQuery(".navlist__list").hide();
	
	jQuery('.navlist__icon--navicon').on('click', function() {
		jQuery('.navlist__list').slideToggle();
	});
	jQuery('.navlist__list .page_item_has_children > a').on('click touchstart', function(event) {
		if(jQuery(this).hasClass('active')){
			jQuery('.navlist__list a').removeClass("active");
		} else {
			jQuery(this).parent('li').parent('ul').parent().children('ul').children('li').children('a').not(this).removeClass("active").parent('li').find("ul").slideUp();
			jQuery(this).next("ul").slideDown();
			jQuery(this).addClass("active");
			return false;
		}
	});
	jQuery(window).bind( 'hashchange', function(event) {
		jQuery('.navlist__list').slideUp();
	});


/*
	jQuery(".navlist__searchbox").hide();
	jQuery('.navlist__icon--search').on('click', function() {
		jQuery('.navlist__searchbox').slideToggle();
	});
*/




	// enable masonry for class »masonry« and childrens
	var jQuerycontainer = jQuery('.masonry'); // init masonry
	jQuerycontainer.imagesLoaded( function() { jQuerycontainer.masonry(); }); // initialize Masonry after all images have loaded



	// sonderfälle bilder
	jQuery("a[href$='187_Kat118_Olympia.jpg']").parent(".contentgallerymasonry__item").addClass("triplewidth");




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