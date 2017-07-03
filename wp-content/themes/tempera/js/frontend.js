/*
 * Tempera Theme custom frontend scripting
 * http://www.cryoutcreations.eu/
 *
 * Copyright 2013-16, Cryout Creations
 * Free to use and abuse under the GPL v3 license.
 */

jQuery(document).ready(function() {

	// responsiveness check
	if (tempera_settings['mobile'] == 1) {
		tempera_mobilemenu_init();

		if (tempera_settings['fitvids'] == 1) jQuery(".entry-content").fitVids();
	};

	/* Standard menu touch support for tablets */
	var custom_event = ('ontouchstart' in window) ? 'touchstart' : 'click'; // check touch support
	var ios = /iPhone|iPad|iPod/i.test(navigator.userAgent);
		jQuery('#access .menu > ul > li a').on('click', function(e){
			var $link_id = jQuery(this).attr('href');
			if (jQuery(this).parent().data('clicked') == $link_id) { // second touch
				jQuery(this).parent().data('clicked', null);
			}
			else { // first touch
				if (custom_event != 'click' && !ios && (jQuery(this).parent().children('ul').length >0)) {e.preventDefault();}
				jQuery(this).parent().data('clicked', $link_id);
			}
		});

	/* Back to top button animation */
	var offset = 500;
    var duration = 500;
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
			jQuery('#toTop').css({'margin-left':''+cryout_toTop_offset+'px','opacity':1});
			jQuery('#toTop').css({'margin-right':''+cryout_toTop_offset+'px','opacity':1});
        } else {
			jQuery('#toTop').css({'margin-left':''+(cryout_toTop_offset+150)+'px','opacity':0});
			jQuery('#toTop').css({'margin-right':''+(cryout_toTop_offset+150)+'px','opacity':0});
        }
    });
    jQuery('#toTop').click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    });


	/* Menu animation */
	jQuery("#access ul ul").css({display: "none"}); /* Opera Fix */
	jQuery("#access > .menu ul li > a:not(:only-child)").attr("aria-haspopup","true");/* IE10 mobile Fix */

	jQuery("#access li").hover(
		function(){
			jQuery(this).find('ul:first').stop();
			jQuery(this).find('ul:first').css({opacity: "0",marginTop:"50px"}).css({visibility: "visible",display: "block",overflow:"visible"}).animate({"opacity":"1",marginTop:"-=50"},{queue:false});
		}, function(){
			jQuery(this).find('ul:first').css({visibility: "visible",display: "block",overflow:"visible"}).animate({marginTop:"+=50"}, {queue:false}).fadeOut();
		}
	);

	/* Social Icons Animation */
	jQuery(".socialicons").append('<div class="socials-hover"></div>');

	/* Detect and apply custom class for Safari */
	if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
		jQuery('body').addClass('safari');
	}

});
/* end document.ready */

/* Mobile Menu v2 */
function tempera_mobilemenu_init() {
	var state = false;
	jQuery("#nav-toggle").click(function(){
		jQuery("#access").slideToggle(function(){ if (state) {jQuery(this).removeAttr( 'style' )}; state = ! state; } );
	});
}

/* FitVids 1.1*/
;(function( $ ){

  'use strict';

  $.fn.fitVids = function( options ) {
    var settings = {
      customSelector: null,
      ignore: null
    };

    if(!document.getElementById('fit-vids-style')) {
      // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
      var head = document.head || document.getElementsByTagName('head')[0];
      var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
      var div = document.createElement("div");
      div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
      head.appendChild(div.childNodes[1]);
    }

    if ( options ) {
      $.extend( settings, options );
    }

    return this.each(function(){
      var selectors = [
        'iframe[src*="player.vimeo.com"]',
        'iframe[src*="youtube.com"]',
        'iframe[src*="youtube-nocookie.com"]',
        'iframe[src*="kickstarter.com"][src*="video.html"]',
        'object',
        'embed'
      ];

      if (settings.customSelector) {
        selectors.push(settings.customSelector);
      }

      var ignoreList = '.fitvidsignore';

      if(settings.ignore) {
        ignoreList = ignoreList + ', ' + settings.ignore;
      }

      var $allVideos = $(this).find(selectors.join(','));
      $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
      $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

      $allVideos.each(function(){
        var $this = $(this);
        if($this.parents(ignoreList).length > 0) {
          return; // Disable FitVids on this video.
        }
        if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
        if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
        {
          $this.attr('height', 9);
          $this.attr('width', 16);
        }
        var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
            width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
            aspectRatio = height / width;
        if(!$this.attr('name')){
          var videoName = 'fitvid' + $.fn.fitVids._count;
          $this.attr('name', videoName);
          $.fn.fitVids._count++;
        }
        $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
        $this.removeAttr('height').removeAttr('width');
      });
    });
  };

  // Internal counter for unique video names.
  $.fn.fitVids._count = 0;

// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );


/* Returns the version of Internet Explorer or a -1
  (indicating the use of another browser). */
function getInternetExplorerVersion()
{
  var rv = -1; /* assume not IE. */
  if (navigator.appName == 'Microsoft Internet Explorer')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  return rv;
}
