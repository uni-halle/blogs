// Generated by CoffeeScript 1.12.2

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

(function() {
  var decultot;

  decultot = (function($) {
    'use strict';
    var alignGalleryImgs, configMap, hideVideoControls, initMFPLightbox, initModule, jM, modifyGallery, onClickArtikelLink, onClickTop, onResize, setjM, showVideoControls, stateMap, toggleEhemalige, togglePlayVideo;
    configMap = {
      intro_interval: 5000,
      fade_time: 1500,
      scroll_top_time: 800,
      resize_interval: 50,
      mfp_gallery_opt: {
        type: 'image',
        gallery: {
          enabled: true,
          arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
          tCounter: '<span>%curr% / %total%</span>'
        },
        image: {
          titleSrc: 'alt'
        },
        overflowY: 'hidden',
        closeBtnInside: false,
        mainClass: 'mfp-zoom-in',
        tLoading: '',
        removalDelay: 500,
        callbacks: {
          open: function() {},
          change: function() {
            if (this.isOpen) {
              this.wrap.addClass('mfp-open');
            }
          },
          imageLoadComplete: function() {
            var self;
            self = this;
            setTimeout((function() {
              self.wrap.addClass('mfp-image-loaded');
              $('.mfp-title').text(self.currItem.img.eq(0).attr('alt'));
            }), 16);
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
      mfp_single_opt: {
        type: 'image',
        image: {
          titleSrc: function(item) {
            console.log(item);
            return item.img[0].alt;
          }
        },
        overflowY: 'hidden',
        closeBtnInside: false,
        mainClass: 'mfp-zoom-in',
        tLoading: '',
        removalDelay: 500,
        callbacks: {
          open: function() {
            jQuery('.mfp-preloader').append('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
          },
          change: function() {
            if (this.isOpen) {
              this.wrap.addClass('mfp-open');
            }
          },
          imageLoadComplete: function() {
            var self;
            self = this;
            setTimeout((function() {
              self.wrap.addClass('mfp-image-loaded');
            }), 16);
          },
          beforeClose: function() {
            var $griditem;
            if (this.isOpen) {
              this.wrap.removeClass('mfp-open');
            }
            $griditem = jQuery(this._lastFocusedEl).closest('.vc_grid-item-mini');
            $griditem.addClass('vc_is-hover');
            setTimeout((function() {
              $griditem.removeClass('vc_is-hover');
            }), 1000);
          },
          close: function() {
            this.wrap.removeClass('mfp-image-loadedn');
          }
        }
      },
      html_mail: '<a href="mailto:elisabeth.decultot@germanistik.uni-halle.de">elisabeth.decultot@germanistik.uni-halle.de</a>'
    };
    stateMap = {
      introloop_toid: void 0,
      home_pics_length: 0,
      current_home_pic: 0,
      resize_toid: void 0
    };
    jM = {};
    setjM = function() {
      jM = {
        $window: $(window),
        $htmlbody: $('html, body'),
        $menu_toggle: $('#menu_toggle'),
        $main_nav: $('#site-navigation'),
        $header: $('header'),
        $mfp_img_link: $('a.mfp-img-link'),
        $mail_me: $('.mail-me'),
        $top_link: $('.scroll-top-link'),
        $ehemalige: $('#ehemalige'),
        $ehemalige_posts: $('#ehemalige_posts'),
        $ehemalige_toggle: $('.ehemalige-toggle'),
        $artikelmenu_link: $('a', '.artikelmenu'),
        $print_link: $('.print-link'),
        $gallery: $('.gallery'),
        $gallery_icon: $('.gallery-icon'),
        $gallery_link: $('a', '.gallery'),
        $gallery_capt: $('.gallery-caption'),
        $dct_video: $('#video_decultot')
      };
    };
    initMFPLightbox = function() {
      jM.$gallery_link.magnificPopup(configMap.mfp_gallery_opt);
    };
    modifyGallery = function() {
      initMFPLightbox();
      alignGalleryImgs();
    };
    alignGalleryImgs = function() {
      jM.$gallery_icon.each(function(n) {
        var $div, $img, div_height, div_ratio, div_width, img_height, img_ratio, img_width, margin_left, margin_top;
        $div = $(this);
        div_width = $div.width();
        div_height = div_width * .75;
        div_ratio = div_width / div_height;
        $img = $div.find('img');
        img_width = $img.width();
        img_height = $img.height();
        img_ratio = img_width / img_height;
        margin_top = (div_height - img_height) / 2;
        margin_left = (div_width - img_width) / 2;
        if ($div.hasClass('landscape')) {
          if (img_ratio < div_ratio) {
            $img.css({
              height: '100%',
              width: 'auto'
            });
            margin_left = (div_width - $img.width()) / 2;
            $img.css('marginLeft', margin_left);
          } else if (img_ratio > div_ratio) {
            $img.css('marginTop', margin_top);
          }
        } else if ($div.hasClass('portrait')) {
          $img.css('marginLeft', margin_left);
        }
        $div.css('visibility', 'visible');
      });
    };
    hideVideoControls = function() {
      var $video;
      $video = $(this);
      if (!!$video.attr('controls')) {
        $video.removeAttr('controls');
      }
    };
    showVideoControls = function() {
      var $video;
      $video = $(this);
      if (!$video.attr('controls')) {
        $video.attr('controls', 'controls');
      }
    };
    togglePlayVideo = function() {
      var $video;
      $video = $(this).get(0);
      console.log($video.paused);
      if ($video.paused === false) {
        $video.pause();
      } else {
        $video.play();
      }
    };
    toggleEhemalige = function(e) {
      e.preventDefault();
      if (!jM.$ehemalige.hasClass('open')) {
        jM.$ehemalige_posts.slideDown();
        return jM.$ehemalige.addClass('open');
      } else {
        jM.$ehemalige_posts.slideUp();
        return jM.$ehemalige.removeClass('open');
      }
    };
    onResize = function() {
      if (stateMap.resize_toid) {
        return true;
      }
      if (jM.$window.width() < 1000) {
        alignGalleryImgs();
      }
      stateMap.resize_toid = setTimeout((function() {
        stateMap.resize_toid = void 0;
      }), configMap.resize_interval);
      return true;
    };
    onClickTop = function(e) {
      e.preventDefault();
      jM.$htmlbody.animate({
        scrollTop: 0
      }, configMap.scroll_top_time);
      return false;
    };
    onClickArtikelLink = function(e) {
      var target;
      e.preventDefault();
      target = $(this).attr('href');
      jM.$htmlbody.animate({
        scrollTop: $(target).offset().top - 35
      }, configMap.scroll_top_time);
      return false;
    };
    initModule = function() {
      setjM();
      jM.$top_link.on('click', onClickTop);
      jM.$artikelmenu_link.on('click', onClickArtikelLink);
      jM.$print_link.on('click', function() {
        window.print();
      });
      if (jM.$gallery_icon.length > 0) {
        jM.$window.on('load', function() {
          modifyGallery();
          return jM.$window.on('resize', onResize);
        });
      }
      jM.$gallery_capt.on('click', function() {
        $(this).prev('.gallery-icon').children('a').trigger('click');
      });
      jM.$ehemalige_toggle.on('click', toggleEhemalige);
      jM.$mail_me.html(configMap.html_mail);
      return true;
    };
    return {
      initModule: initModule
    };
  })(jQuery);

  jQuery(function() {
    decultot.initModule();
  });

}).call(this);

//# sourceMappingURL=decultot.js.map
