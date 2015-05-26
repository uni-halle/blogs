/*! Featured Pages Unlimited Customizer controls by Nicolas Guillaume, GPL2+ licensed */
jQuery(function ($) {
  //prevents js conflicts
  "use strict";
  var $Selects   = $('select' , '#accordion-section-tc_fpu'),
      $Checks    = $('input[type=checkbox]' , '#accordion-section-tc_fpu'),
      $Numbers   = $('input[type="number"]', '#accordion-section-tc_fpu');

  //init selecter
  $Selects.selecter();

 /* //Force --Select-- if no default
  $("li[id*='customize-control-tc_unlimited_featured_pages-tc_featured_page_'] > label > select").each( function () {
    console.log( $(this).val() );
    if ( ! $(this).val() ) {
      var text =  '— Select —';
      $(this).siblings('.selecter').find('.selecter-selected').text(text);
    }
  });*/

  //init icheck only if not already initiated
  $Checks.each( function() {
    if ( 0 === $(this).closest('div[class^="icheckbox"]').length ) {
      $(this).iCheck({
          checkboxClass: 'icheckbox_flat-grey',
          radioClass: 'iradio_flat-grey'
      })
      .on('ifChanged', function(event) {
        $(this).trigger('change');
      });
    }
  });


  //init stepper for number input
  $Numbers.stepper();

  var api         = wp.customize,
      ShowFP      = TCFPCControlParams.ShowFP,
      ShowExcerpt = TCFPCControlParams.ShowExcerpt,
      ShowButton  = TCFPCControlParams.ShowButton,
      ShowImg     = TCFPCControlParams.ShowImg,
      CustomHook  = TCFPCControlParams.CustomHook,
      RandomEnabled = TCFPCControlParams.RandomEnabled,
      FPbyline = TCFPCControlParams.FPbyline,
      OptionPrefix = TCFPCControlParams.OptionPrefix,
      settingMap = {};

  settingMap[OptionPrefix + "[tc_show_fp]"] = {
      controls: ShowFP,
      callback: function( to ) { return 1 == to; }
  };
  settingMap[OptionPrefix + "[tc_show_fp_text]"] = {
      controls: ShowExcerpt,
      callback: function( to ) { return 1 == to; }
  };
  settingMap[OptionPrefix + "[tc_show_fp_button]"] = {
      controls: ShowButton,
      callback: function( to ) { return 1 == to; }
  };
  settingMap[OptionPrefix + "[tc_show_fp_img]"] = {
      controls: ShowImg,
      callback: function( to ) { return 1 == to; }
  };
  settingMap[OptionPrefix + "[tc_fp_position]"] = {
      controls: CustomHook,
      callback: function( to ) { return 'custom_hook' == to; }
  };
  settingMap[OptionPrefix + "[tc_random_colors]"] = {
      controls: RandomEnabled,
      callback: function( to ) { return 1 == to; }
  };
  settingMap[OptionPrefix + "[tc_fp_number_per_line]"] = {
      controls: FPbyline,
      callback: function( to ) { return 'four' == to; }
  };

  $.each(settingMap, function( settingId, o ) {
    api( settingId, function( setting ) {
      $.each( o.controls, function( i, controlId ) {
        api.control( controlId, function( control ) {
          var visibility = function( to ) {
            control.container.toggle( o.callback( to ) );
          };
          visibility( setting.get() );
          setting.bind( visibility );
        });
      });
    });
  });

} );