/*! Footer Customizer plugin by Nicolas Guillaume, GPL2+ licensed */
jQuery(function ($) {

  var fcSettingMap  = {},
      api           = wp.customize;

  fcSettingMap["tc_theme_options[fc_show_footer_credits]"] = {
      controls: [
        'tc_theme_options[fc_copyright_text]',
        'tc_theme_options[fc_site_name]',
        'tc_theme_options[fc_site_link]',
        'tc_theme_options[fc_show_designer_credits]',
        'tc_theme_options[fc_designer_name]',
        'tc_theme_options[fc_designer_link]'
      ],
      callback: function( to ) { return 1 == to; }
  };

  fcSettingMap["tc_theme_options[fc_show_designer_credits]"] = {
      controls: [
        'tc_theme_options[fc_credit_text]',
        'tc_theme_options[fc_designer_name]',
        'tc_theme_options[fc_designer_link]'
      ],
      callback: function( to ) { return 1 == to; }
  };

  $.each(fcSettingMap, function( settingId, o ) {
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