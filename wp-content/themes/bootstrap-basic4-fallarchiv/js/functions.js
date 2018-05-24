/*
 (function($) {
 function faq() {
 var hide = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQBAMAAADt3eJSAAAAD1BMVEX////d3d2ampqxsbF5eXmCtCYvAAAAAXRSTlMAQObYZgAAADBJREFUeF6dzNEJACAMA1HdINQJCp1Ebv+ZlLYLaD4f4cbnDNi6MAO8KCHJ+7X02j3mzgMQe93HcQAAAABJRU5ErkJggg==';
 var show = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAgMAAABinRfyAAAADFBMVEX///95eXnd3d2dnZ3aAo3QAAAAAXRSTlMAQObYZgAAADFJREFUeF5dyzEKACAMA0CXolNe2Id09Kl5igZahWY4AiGjZwmIuS9GEcJfY63Ix88Bol4EYP1O7JMAAAAASUVORK5CYII=';
 $('div.faq.unit').each( function() {
 var $that = $(this);
 var icon = $that.hasClass('preselection') ? show : hide;
 $(this).children('.faq.question').each( function() {
 $(this).prepend('<span class="faq icon"><a><img src="'+icon+'" /></a></span>').click( function() {
 var icon = $that.toggleClass('preselection').hasClass('preselection') ? show : hide;
 $(this).find('span.faq.icon img').attr('src', icon);
 if ( icon == show ) {
 $(this).siblings(':not(.faq.question)').stop(true, true).fadeOut('fast');
 } else {
 $(this).siblings(':not(.faq.question)').stop(true, true).fadeIn('fast');
 }
 }).click();
 });
 $(this).children('.faq.answer').each( function() {
 $(this).append('<div class="faq close"><a href="#">Tab schließen</a></div>').on("click", ".faq.close", function() {
 var icon = $that.toggleClass('preselection').hasClass('preselection') ? show : hide;
 $that.children('.faq.question').find('span.faq.icon img').attr('src', icon);
 if ( icon == show ) {
 $that.children('.faq.question').siblings(':not(.faq.question)').stop(true, true).fadeOut('fast');
 } else {
 $that.children('.faq.question').siblings(':not(.faq.question)').stop(true, true).fadeIn('fast');
 }    
 });
 });
 });
 }
 $(document).ready(function() {
 faq();
 });
 })( jQuery );
 */

jQuery(document).ready(function ($) {
  var toggle_speed = 100;

  $('.menu > li > a[href="#"]').click(function () {
    if ($(this).attr('class') != 'active') {
      $('.menu li ul').slideUp(toggle_speed);
      $(this).next().slideToggle(toggle_speed);
      $('.menu li a').removeClass('active');
      $(this).addClass('active');
      return false;
    }
  });

  $('.sub-menu > li > a[href="#"]').click(function () {
    if ($(this).attr('class') != 'active') {
      $('.sub-menu li ul').slideUp(toggle_speed);
      $(this).next().slideToggle(toggle_speed);
      $('.menu li a').removeClass('active');
      $(this).addClass('active');
      return false;
    }
  });

  $(".current-menu-item").parents(".sub-menu").slideDown(toggle_speed);
  $(".current-menu-item").children(".sub-menu").slideDown(toggle_speed);
});

jQuery(document).ready(function () {
  jQuery('.lesen_titel_klasse').
          click(function () {
            jQuery(this).
                    parent().
                    children('.lesen_text_klasse').
                    slideToggle();
          });
});

jQuery.collapseAlleArbeitsfelder = function () {
  jQuery('#collapseSchuleUnterricht').collapse('hide');
  jQuery('#collapseSchuleAusserUnterricht').collapse('hide');
  jQuery('#collapseAusserschulisch').collapse('hide');
  jQuery('#collapseErwachsenenbildung').collapse('hide');
};

jQuery.resetFormField = function( form_id, field_id) {
  var $form = jQuery('#'+form_id);
  var $li_field = jQuery('li[data-sf-field-name='+field_id+']','#'+form_id);
  var field_input_type = $li_field.data('sf-field-input-type');
  var $field_input = jQuery(field_input_type, $li_field );
  
  switch (field_input_type) {
    case 'select':
      $field_input.prop('selectedIndex',0);
      break;
    case 'range-slider':
      jQuery('input.sf-range-min', $li_field).prop('value',1);
      jQuery('input.sf-range-max', $li_field).prop('value',13);
      break;
  }
  $form.submit();
};

jQuery(document).ready(function ($) {
  var $range_inputs = $('.sf-meta-range-slider input.sf-input-number').prop('disabled', true);

  $('#headingSchuleUnterricht, #headingSchuleAusserUnterricht, #headingAusserschulisch, #headingErwachsenenbildung, #headingWeitereFilter, #headingInterpretationen').on('click', function (ev) {
    var $form = $('form', $(this).parent());
    $form[0].reset();
    $form.submit();
  });

  $('#collapseArbeitsfelder').on('hidden.bs.collapse', function () {
    $.collapseAlleArbeitsfelder();
  });

  $('#collapseFilter').on('hidden.bs.collapse', function () {
    $.collapseAlleArbeitsfelder();
    $('#collapseArbeitsfelder').collapse('show');
  });

  $('#headingFilter').on('click', function () {
    if (!$('#collapseFilter').hasClass('show')) {
      $('#search-filter-form-2096').submit();
    }
  });
  $('#headingTags').on('click', function () {
    if (!$('#collapseTags').hasClass('show')) {
      $('#search-filter-form-2096').submit();
    }
  });
  $('#headingArbeitsfelder').on('click', function () {
    if (!$('#collapseArbeitsfelder').hasClass('show')) {
      $('#search-filter-form-2097').submit();
    }
  });
});