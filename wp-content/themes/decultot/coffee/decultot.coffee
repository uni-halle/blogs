###*
# Created by BirdyBird on 20.01.16.
###

###
# decultot JavaScript module for decultot.com
#
# Sebastian Vogel - svog at gmx dot de
###

###jslint         browser : true, continue : true,
 devel  : true, indent  : 2,    maxerr   : 50,
 newcap : true, nomen   : true, plusplus : true,
 regexp : true, sloppy  : true, vars     : false,
 white  : true, plusplus: true, unparam  : true, todo: true
###

###global jQuery ###

decultot = (($) ->
  'use strict'
  #---------------- BEGIN MODULE SCOPE VARIABLES --------------
  configMap =
    intro_interval: 5000
    fade_time: 1500
    scroll_top_time: 800
    resize_interval: 50
    mfp_gallery_opt:
      type: 'image'
      gallery:
        enabled: true
        arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>'
        tCounter: '<span>%curr% / %total%</span>'
      image: titleSrc: 'alt'
      overflowY: 'hidden'
      closeBtnInside: false
      mainClass: 'mfp-zoom-in'
      tLoading: ''
      removalDelay: 500
      callbacks:
        open: ->
#jQuery( '.mfp-preloader' ).append( '<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>' );
          return
        change: ->
          if @isOpen
            @wrap.addClass 'mfp-open'
          return
        imageLoadComplete: ->
          self = this
          setTimeout (->
            self.wrap.addClass 'mfp-image-loaded'
            $('.mfp-title').text self.currItem.img.eq(0).attr('alt')
            return
          ), 16
          return
        beforeClose: ->
          if @isOpen
            @wrap.removeClass 'mfp-open'
          return
        close: ->
          @wrap.removeClass 'mfp-image-loadedn'
          return
    mfp_single_opt:
      type: 'image'
      image: titleSrc: (item) ->
        console.log item
        item.img[0].alt
      overflowY: 'hidden'
      closeBtnInside: false
      mainClass: 'mfp-zoom-in'
      tLoading: ''
      removalDelay: 500
      callbacks:
        open: ->
          jQuery('.mfp-preloader').append '<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>'
          return
        change: ->
          if @isOpen
            @wrap.addClass 'mfp-open'
          return
        imageLoadComplete: ->
          self = this
          setTimeout (->
            self.wrap.addClass 'mfp-image-loaded'
            return
          ), 16
          return
        beforeClose: ->
          if @isOpen
            @wrap.removeClass 'mfp-open'
          $griditem = jQuery(@_lastFocusedEl).closest('.vc_grid-item-mini')
          $griditem.addClass 'vc_is-hover'
          setTimeout (->
            $griditem.removeClass 'vc_is-hover'
            return
          ), 1000
          return
        close: ->
          @wrap.removeClass 'mfp-image-loadedn'
          return
    html_mail: '<a href="mailto:elisabeth.decultot@germanistik.uni-halle.de">elisabeth.decultot@germanistik.uni-halle.de</a>'
  stateMap =
    introloop_toid: undefined
    home_pics_length: 0
    current_home_pic: 0
    resize_toid: undefined
  jM = {}

  #----------------- END MODULE SCOPE VARIABLES ---------------
  #------------------- BEGIN UTILITY METHODS ------------------
  # example : getTrimmedString
  #-------------------- END UTILITY METHODS -------------------
  #--------------------- BEGIN DOM METHODS --------------------
  # Begin DOM method /setjM/

  setjM = ->
    jM =
      $window: $(window)
      $htmlbody: $('html, body')
      $menu_toggle: $('#menu_toggle')
      $main_nav: $('#site-navigation')
      $header: $('header')

      $mfp_img_link: $('a.mfp-img-link')
      $mail_me: $('.mail-me')
      $top_link: $('.scroll-top-link')

      $ehemalige: $ '#ehemalige'
      $ehemalige_posts: $ '#ehemalige_posts'
      $ehemalige_toggle: $ '.ehemalige-toggle'

      $artikelmenu_link: $('a', '.artikelmenu')
      $print_link: $('.print-link')
      $gallery: $('.gallery')
      $gallery_icon: $('.gallery-icon')
      $gallery_link: $('a', '.gallery')
      $gallery_capt: $('.gallery-caption')
      $dct_video: $('#video_decultot')
    return

  # End DOM method /setjM/

  initMFPLightbox = ->
    jM.$gallery_link.magnificPopup configMap.mfp_gallery_opt
    return

  modifyGallery = ->
    initMFPLightbox()
    alignGalleryImgs()
    return

  # Begin DOM method /alignGalleryImgs/

  alignGalleryImgs = ->
    jM.$gallery_icon.each (n) ->
      $div = $(this)
      div_width = $div.width()
      div_height = div_width * .75
      div_ratio = div_width / div_height
      $img = $div.find('img')
      img_width = $img.width()
      img_height = $img.height()
      img_ratio = img_width / img_height
      margin_top = (div_height - img_height) / 2
      margin_left = (div_width - img_width) / 2
      if $div.hasClass('landscape')
        if img_ratio < div_ratio
          $img.css
            height: '100%'
            width: 'auto'
          margin_left = (div_width - $img.width()) / 2
          $img.css 'marginLeft', margin_left
        else if img_ratio > div_ratio
          $img.css 'marginTop', margin_top
      else if $div.hasClass('portrait')
        $img.css 'marginLeft', margin_left
      $div.css 'visibility', 'visible'
      return
    #  end .each()
    return

  #alignGalleryImgs = function(){
  #	var icon_width = jM.$gallery_icon.eq(0).width();
  #
  #	jM.$gallery_icon.height( icon_width *.75 );
  #};
  # End DOM method /alignGalleryImgs/

  hideVideoControls = ->
    $video = $(this)
    if ! !$video.attr('controls')
      $video.removeAttr 'controls'
    return

  showVideoControls = ->
    $video = $(this)
    if !$video.attr('controls')
      $video.attr 'controls', 'controls'
    return

  togglePlayVideo = ->
    $video = $(this).get(0)
    console.log $video.paused
    if $video.paused == false
      $video.pause()
    else
      $video.play()
    return

  toggleEhemalige = (e) ->
    e.preventDefault()
    if !jM.$ehemalige.hasClass 'open'
      jM.$ehemalige_posts.slideDown()
      jM.$ehemalige.addClass 'open'
    else
      jM.$ehemalige_posts.slideUp()
      jM.$ehemalige.removeClass 'open'



  # Begin event handler /onResize/

  onResize = ->
    if stateMap.resize_toid
      return true
    if jM.$window.width() < 1000
      alignGalleryImgs()
    stateMap.resize_toid = setTimeout((->
      stateMap.resize_toid = undefined
      return
    ), configMap.resize_interval)
    true

  # End event handler /onResize/
  #---------------------- END DOM METHODS ---------------------
  #------------------- BEGIN EVENT HANDLERS -------------------
  # example: onClickButton = ...
  #-------------------- END EVENT HANDLERS --------------------
  #toggleMenu = function(){
  #	jM.$header.toggleClass('toggle-on');
  #};

  onClickTop = (e) ->
    e.preventDefault()
    jM.$htmlbody.animate { scrollTop: 0 }, configMap.scroll_top_time
    false

  onClickArtikelLink = (e) ->
    e.preventDefault()
    target = $(this).attr('href')
    jM.$htmlbody.animate { scrollTop: $(target).offset().top - 35 }, configMap.scroll_top_time
    false

  #------------------- BEGIN PUBLIC METHODS -------------------
  # Begin public method /initModule/
  # Purpose    : Initializes module
  # Arguments  :
  #  * $container the jquery element used by this feature
  # Returns    : true
  # Throws     : none
  #

  initModule = ->

    setjM()

    jM.$top_link.on 'click', onClickTop

    jM.$artikelmenu_link.on 'click', onClickArtikelLink

    jM.$print_link.on 'click', ->
      window.print()
      return

    if jM.$gallery_icon.length > 0
      jM.$window.on 'load', ->
        modifyGallery()
        jM.$window.on 'resize', onResize

    jM.$gallery_capt.on 'click', ->
      $(this).prev('.gallery-icon').children('a').trigger 'click'
      return

    jM.$ehemalige_toggle.on 'click', toggleEhemalige


    jM.$mail_me.html configMap.html_mail

    true

  # End public method /initModule/
  # return public methods
  { initModule: initModule }
#------------------- END PUBLIC METHODS ---------------------
)(jQuery)

jQuery ->
  decultot.initModule()
  return

# ---
# generated by js2coffee 2.2.0