/*! Grid Customizer controls by Nicolas Guillaume, GPL2+ licensed */
(function (wp, $) {
  var api = wp.customize,
      OptionPrefix    = TCGCControlParams.OptionPrefix,
      GCEnable        = OptionPrefix+'[tc_gc_enabled]',
      GCTitleLocation = OptionPrefix+'[tc_gc_title_location]',
      GCTitleColor    = OptionPrefix+'[tc_gc_title_color]',
      settingMap      = {};

  var _is_in_list = function( controlId, list ) {
    controlId = controlId.replace(/\[(.*?)\]/g, "$1").replace('tc_theme_options', '');
    var _filtered =  list.filter( function(_it) {
      return -1 != _it.indexOf(controlId);
    });
    return 0 !== _filtered.length;
  };

  //includes a simple dependency management with tc_gc_title_location
  settingMap[ GCEnable ] = {
    controls: [ '[tc_gc_limit_excerpt_length]', '[tc_gc_effect]' ,'[tc_gc_title_caps]','[tc_gc_transp_bg]', '[tc_gc_random]', '[tc_gc_title_location]', '[tc_gc_title_color]', '[tc_gc_title_custom_color]'].map(function( _name ) { return OptionPrefix + _name; } ),
    callback: function( to, controlId ) {
      if ( ! _is_in_list ( controlId , ['tc_gc_title_color', 'tc_gc_title_custom_color', 'tc_gc_title_caps' ] ) )
        return to == 1;
      return 'over' == api( 'tc_theme_options[tc_gc_title_location]' ).get() && to == 1;
    }
  };

  settingMap[ GCTitleColor ] = {
    controls: [ OptionPrefix+'[tc_gc_title_custom_color]' ],
    callback: function( to ) { return to == 'custom'; }
  };

  settingMap[ GCTitleLocation ] = {
    controls: [ '[tc_gc_title_caps]', '[tc_gc_title_color]', '[tc_gc_title_custom_color]' ].map(function( _name ) { return OptionPrefix + _name; }),
    callback: function( to, controlId ) {
      $( api.control( controlId ).container ).addClass('tc-sub-control');
      if ( ! _is_in_list ( controlId , ['tc_gc_title_custom_color'] ) )
        return to == 'over';
      return 'custom' == api( 'tc_theme_options[tc_gc_title_color]' ).get() && to == 'over';
    }
  };

  /* Is this loop on 1 element worthwhile? */
  $.each(settingMap, function( settingId, o ) {
    api( settingId, function( setting ) {
      $.each( o.controls, function( i, controlId ) {
        api.control( controlId, function( control ) {
          var visibility = function( to ) {
            control.container.toggle( o.callback( to, controlId ) );
          };
          visibility( setting.get() );
          setting.bind( visibility );
        });
      });
    });
  });

  //Change title of content panel and section to add 'grid'
  var _add_grid_to_section_titles = function() {
    //section title
    var _section_container,
        _old_html,
        _old_panel,
        _old_panel_html;

    if ( 'function' != typeof api.section )
      _section_container = $('li#accordion-section-tc_post_list_settings');
    else
      _section_container = api.section('tc_post_list_settings').container;

    _old_html = $( '.accordion-section-title' , _section_container ).html();

    $( '.accordion-section-title' , _section_container ).html( _old_html.replace('...' , 'grid, ...') );

    //panel title
    _old_panel = $( '.accordion-section-title', '#accordion-panel-tc-content-panel').first();
    if ( ! _old_panel.length )
      return;
    _old_panel_html = _old_panel.html();

    _old_panel.html( _old_panel_html.replace('...' , 'grid, ...') );
  };


  /* HANDLES SETTING INTERDEPENDENCIES */
  var _handles_set_dependencies = function() {
    var _update_selecter_setting = function( _set_id , _val ) {
      api.instance( _set_id ).set(_val);
      $('select' , api.control(_set_id).container).selecter('destroy');
      $('select' , api.control(_set_id).container).selecter();
    };

    //apply visibility on ready
    var _is_grid_enabled = api.instance('tc_theme_options[tc_post_list_grid]').get() == 'grid';
    api.control( 'tc_theme_options[tc_gc_enabled]' ).container.toggle( _is_grid_enabled );

    //bind visibility on setting changes
    api.instance('tc_theme_options[tc_post_list_grid]').bind( function(to) {
      _update_selecter_setting('tc_theme_options[tc_gc_enabled]' , 'grid' == to ? 1 : 0 );
      api.control( 'tc_theme_options[tc_gc_enabled]' ).container.toggle( 'grid' == to ? 1 : 0 );
    } );
  };

  //FIRES EVERYTHING
  api.bind( 'ready' , function() {
    _add_grid_to_section_titles();
    _handles_set_dependencies();
  });

})( wp, jQuery );
