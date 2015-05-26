/*! Grid Customizer preview by Nicolas Guillaume - Rocco Aliberti, GPL2+ licensed */
( function( $ ) {
  var OptionPrefix  = TCGCPreviewParams.OptionPrefix,
      $_figures     = $('figure', '.tc-gc'),
      api           = wp.customize;


  api( OptionPrefix + '[tc_gc_transp_bg]' , function( value ) {
    value.bind( function( to ) {
      var _classes = ['gc-title-dark-bg', 'gc-title-light-bg', 'gc-dark-bg' , 'gc-light-bg', 'gc-no-bg'],
          _to_add = 'gc-' + to;

      $( '.tc-gc' ).addClass( _to_add );
      _classes.map( function( _c ) {
        if ( _to_add != _c)
          $( '.tc-gc' ).removeClass(_c);
      });
    } );
  } );

  api( OptionPrefix + '[tc_gc_title_color]' , function( value ) {
    value.bind( function( to ) {
      var _classes = ['gc-white-title', 'gc-custom-title', 'gc-skin-title'],
          _to_add = 'gc-'+ to + '-title';

      $( '.tc-gc' ).addClass( _to_add );
      _classes.map( function( _c ) {
        if ( _to_add != _c)
          $( '.tc-gc' ).removeClass(_c);
      });
      //reset / update possible inline color style
      if ( 'custom' == to )
        $( '.tc-gc figure .entry-title a').css('color', api( 'tc_theme_options[tc_gc_title_custom_color]' ).get() );
      else
        $( '.tc-gc figure .entry-title a').css('color', '' );
    } );
  } );

  api( OptionPrefix +'[tc_gc_title_custom_color]' , function( value ) {
    value.bind( function( to ) {
      $('.gc-custom-title figure .entry-title a').css('color', to ).addClass('previewing');
    } );
  } );

  api( OptionPrefix + '[tc_gc_title_caps]' , function( value ) {
    value.bind( function( to ) {
      if ( to )
          $( '.tc-gc' ).addClass( 'gc-title-caps');
      else
          $( '.tc-gc' ).removeClass( 'gc-title-caps' );
    } );
  } );
  //show image
  api( OptionPrefix + '[tc_gc_effect]' , function( value ) {
    value.bind( function( to ) {
      var _new_effect = to;

      /* _current_effect retrieved in the loop because of the further option Randomize effects */
      /* Replace effect class with the new one */
      $_figures.each( function(){
        var _current_effect = ($(this).attr('class').match(/effect-[0-9]+/))[0];

        $(this).addClass(_new_effect).removeClass(_current_effect);

        if ( 'effect-4' == _current_effect || 'effect-4' == _new_effect ){
          var _excerpt_content = $(this).find('.tc-g-cont'),
              _p_array = $(_excerpt_content).children('p'),
              _new_content = '';

          if ( _p_array.length > 0 ){
            _new_content = ( 'effect-4' == _current_effect ) ? _merge_p(_p_array) : _split_p(_p_array, 3);
            _excerpt_content.html(_new_content);
          }
        }

      });

    });
  });

  function _merge_p( _p_array ){
    var _new_p = '';

    _p_array.each( function( _idx){
      _new_p += $(this).text();
      if ( _idx < ( _p_array.length - 1 ) )
        _new_p += ' ';
      });
      return "<p>" + _new_p + "</p>";
  }

  function _split_p( _p, _chunks ){
    var _p_words_array = _p.text().split(" "),
        _chunk_size = Math.ceil(_p_words_array.length / _chunks ),
        /* split our array in chunks*/
        _p_array = _.chain(_p_words_array).groupBy(
            function(_el, _idx){
              return Math.floor(_idx/_chunk_size);
            }).toArray().value(),
        /* join our chunks and wrap into p tags*/
        _new_p = _.map( _.map( _p_array, function( set ){ return set.join(' '); } ),
            function( chunk ) { return '<p>'+ chunk + '</p>'; } ).join('');

    return _new_p;
  }

})( jQuery );
