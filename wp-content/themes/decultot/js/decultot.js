/**
 * Created by BirdyBird on 20.01.16.
 */
/*
 * decultot JavaScript module for decultot.com
 *
 * Sebastian Vogel - svog at gmx dot de
*/

/*jslint         browser : true, continue : true,
 devel  : true, indent  : 2,    maxerr   : 50,
 newcap : true, nomen   : true, plusplus : true,
 regexp : true, sloppy  : true, vars     : false,
 white  : true, plusplus: true, unparam  : true, todo: true
 */

/*global jQuery */

var decultot = (function ($) {
	"use strict";

  //---------------- BEGIN MODULE SCOPE VARIABLES --------------
  var
    configMap = {
	    intro_interval    : 5000,
	    fade_time         : 1500,
	    scroll_top_time   : 800,
	    resize_interval		: 50,
	    mfp_gallery_opt   : {
		    type: 'image',
		    gallery:{
			    enabled:true,
			    arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button
			    tCounter: '<span>%curr% / %total%</span>' // markup of counter
		    },
		    image: {
			    titleSrc: 'alt'
	      },
		    overflowY: 'hidden',
		    //removalDelay: 150,
		    //mainClass: 'mfp-fade',
		    closeBtnInside: false,

		    mainClass: 'mfp-zoom-in',
		    tLoading: '',
		    removalDelay: 500, //delay removal by X to allow out-animation
		    callbacks: {
			    open: function() {
				    //jQuery( '.mfp-preloader' ).append( '<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>' );
			    },
			    change: function() {
				    if (this.isOpen) {
					    this.wrap.addClass('mfp-open');
				    }
			    },
			    imageLoadComplete: function() {
				    var self = this;
				    setTimeout(function() {
					    self.wrap.addClass('mfp-image-loaded');
				      $('.mfp-title').text(self.currItem.img.eq(0).attr('alt'));
				    }, 16);
			    },
			    beforeClose: function() {
				    if (this.isOpen) {
					    this.wrap.removeClass('mfp-open');
				    }
			    },
			    close: function() {
				    this.wrap.removeClass('mfp-image-loadedn');
			    }
		    }
	    },
	    mfp_single_opt    : {
		    type: 'image',
		    image: {
			    titleSrc: function(item) {
				    console.log(item);
				    return item.img[0].alt;
			    }
		    },
		    overflowY: 'hidden',
		    //removalDelay: 150,
		    //mainClass: 'mfp-fade',
		    closeBtnInside: false,

		    mainClass: 'mfp-zoom-in',
		    tLoading: '',
		    removalDelay: 500, //delay removal by X to allow out-animation
		    callbacks: {
			    open: function () {
				    jQuery('.mfp-preloader').append('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
			    },
			    change: function () {
				    if (this.isOpen) {
					    this.wrap.addClass('mfp-open');
				    }
			    },
			    imageLoadComplete: function () {
				    var self = this;
				    setTimeout(function () {
					    self.wrap.addClass('mfp-image-loaded');
				    }, 16);
			    },
			    beforeClose: function () {
				    if (this.isOpen) {
					    this.wrap.removeClass('mfp-open');
				    }
				    var $griditem = jQuery(this._lastFocusedEl).closest('.vc_grid-item-mini');
				    $griditem.addClass('vc_is-hover');
				    setTimeout(function () {
					    $griditem.removeClass('vc_is-hover');
				    }, 1000);
			    },
			    close: function () {
				    this.wrap.removeClass('mfp-image-loadedn');
			    }
		    }
	    },
	    html_mail         : '<a href="mailto:elisabeth.decultot@germanistik.uni-halle.de">elisabeth.decultot@germanistik.uni-halle.de</a>'
    },
    stateMap  = {
	    introloop_toid		: undefined,
	    home_pics_length  : 0,
	    current_home_pic  : 0,
	    resize_toid				: undefined
    },
    jqMap = {},

	  //toggleMenu, toggleMenuMobile,
	  onClickTop, onClickArtikelLink, onResize,
	  modifyGallery, alignGalleryImgs, hideVideoControls, showVideoControls, togglePlayVideo, initMFPLightbox,
    setjqMap, initModule;
  //----------------- END MODULE SCOPE VARIABLES ---------------

  //------------------- BEGIN UTILITY METHODS ------------------
  // example : getTrimmedString
  //-------------------- END UTILITY METHODS -------------------

  //--------------------- BEGIN DOM METHODS --------------------

  // Begin DOM method /setjqMap/
  setjqMap = function () {
    jqMap = {
	    $window         : $(window),
	    $htmlbody       : $('html, body'),
	    $menu_toggle    : $('#menu_toggle'),
	    $main_nav       : $('#site-navigation'),
	    $header         : $('header'),
	    $mfp_img_link   : $('a.mfp-img-link'),
	    $mail_me        : $('.mail-me'),
	    $top_link       : $('.scroll-top-link'),
	    $artikelmenu_link  : $('a', '.artikelmenu'),
	    $print_link     : $('.print-link'),
	    $gallery        : $('.gallery'),
	    $gallery_icon   : $('.gallery-icon'),
	    $gallery_link   : $('a', '.gallery'),
	    $gallery_capt   : $('.gallery-caption'),
	    $dct_video      : $('#video_decultot')
    };
  };
  // End DOM method /setjqMap/

	initMFPLightbox = function(){
		jqMap.$gallery_link.magnificPopup( configMap.mfp_gallery_opt );
	};

	modifyGallery = function(){
		initMFPLightbox();
		alignGalleryImgs();
	};

	// Begin DOM method /alignGalleryImgs/
	alignGalleryImgs = function(){
		jqMap.$gallery_icon.each(function(n){

			var
					$div = $(this),
					div_width = $div.width(),
					div_height = div_width * .75,
					//div_height = Math.floor( parseInt( $div.css('paddingBottom') ) ),
					div_ratio = div_width / div_height,
					$img = $div.find('img'),
					img_width = $img.width(),
					img_height = $img.height(),
					img_ratio = img_width / img_height,
					margin_top = ( div_height - img_height ) / 2,
					margin_left = ( div_width - img_width ) / 2;

			if ( $div.hasClass('landscape') ) {
				if ( img_ratio < div_ratio ) {
					$img.css({
						height: '100%',
						width: 'auto'
					});
					margin_left = ( div_width - $img.width() ) / 2;
					$img.css( 'marginLeft',  margin_left );
				}
				else if ( img_ratio > div_ratio ) {
					$img.css( 'marginTop', margin_top );
				}
			}
			else if ( $div.hasClass('portrait') ) {
				$img.css( 'marginLeft', margin_left );
			}

			$div.css('visibility', 'visible');

		}); //  end .each()
	};

	//alignGalleryImgs = function(){
	//	var icon_width = jqMap.$gallery_icon.eq(0).width();
	//
	//	jqMap.$gallery_icon.height( icon_width *.75 );
	//};

	// End DOM method /alignGalleryImgs/


	hideVideoControls = function () {
		var $video = $(this);
		if ( !!$video.attr('controls') ) {
			$video.removeAttr("controls")
		}
	};

	showVideoControls = function () {
		var $video = $(this);
		if ( !$video.attr('controls') ) {
			$video.attr("controls", "controls");
		}
	};

	togglePlayVideo = function () {
		var $video = $(this).get(0);
		console.log($video.paused);
		if ( $video.paused == false ) {
			$video.pause();
		}
		else {
			$video.play();
		}
	};


	// Begin event handler /onResize/
	onResize = function() {
		if ( stateMap.resize_toid ) { return true; }


		if (jqMap.$window.width() < 1000) {
			alignGalleryImgs();
		}


		stateMap.resize_toid = setTimeout(
			function(){ stateMap.resize_toid = undefined; },
			configMap.resize_interval
		);
		return true;
	};
	// End event handler /onResize/


	//---------------------- END DOM METHODS ---------------------

  //------------------- BEGIN EVENT HANDLERS -------------------
  // example: onClickButton = ...
  //-------------------- END EVENT HANDLERS --------------------

	//toggleMenu = function(){
	//	jqMap.$header.toggleClass('toggle-on');
	//};


	onClickTop = function(e){
		e.preventDefault();
		jqMap.$htmlbody.animate({
			scrollTop: 0
		}, configMap.scroll_top_time);
		return false;
	};

	onClickArtikelLink = function(e){
		e.preventDefault();
		var target = $(this).attr('href');
		jqMap.$htmlbody.animate({
			scrollTop: $(target).offset().top -35
		}, configMap.scroll_top_time);
		return false;
	};

  //------------------- BEGIN PUBLIC METHODS -------------------
  // Begin public method /initModule/
  // Purpose    : Initializes module
  // Arguments  :
  //  * $container the jquery element used by this feature
  // Returns    : true
  // Throws     : none
  //
  initModule = function () {
    setjqMap();

	  jqMap.$top_link.on('click', onClickTop);
	  jqMap.$artikelmenu_link.on('click', onClickArtikelLink);
	  jqMap.$print_link.on('click', function(){ window.print(); } );

	  if ( jqMap.$gallery_icon.length > 0 ) {
		  setTimeout(modifyGallery, 2000);
	    jqMap.$window.on('resize', onResize);
	  }

	  jqMap.$gallery_capt.on('click', function(){
		  $(this).prev('.gallery-icon').children('a').trigger('click');
	  });

	  jqMap.$mail_me.html(configMap.html_mail);


    return true;
  };
  // End public method /initModule/

  // return public methods
  return {
    initModule   : initModule
  };
  //------------------- END PUBLIC METHODS ---------------------
}(jQuery));

jQuery(function(){ decultot.initModule(); } );