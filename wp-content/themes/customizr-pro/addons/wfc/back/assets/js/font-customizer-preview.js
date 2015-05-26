/*
 * WordPress Font Customizer Preview
 * copyright (c) 2014-2015 Nicolas GUILLAUME (nikeo), Press Customizr.
 * GPL2+ Licensed
*/
( function( $ ) {
  var DefaultSettings = TCFontPreview.DefaultSettings,
    DBSettings      = TCFontPreview.DBSettings,
    SetPrefix     = TCFontPreview.SettingPrefix,
    SkinColors      = TCFontPreview.SkinColors,
    CurrentSkin   = TCFontPreview.CurrentSkin || 'grey.css',
    Selectors     = [],
    POST       = TCFontPreview.POST,
    TempSettings   = {};

  $.each( DefaultSettings , function( key, value ) {
    //initialize the temp setting object
    TempSettings[key] = TempSettings[key] || {};
    TempSettings[key]['has-tried-inset'] = false;

        wp.customize( SetPrefix + '[' + key + ']' , function( value ) {
            value.bind( function ( Sets ) {
              Selector         = DefaultSettings[key]['selector'] || '';
              //Encode HTML Entities in selector
              //This sets the innerHTML of a new element (not appended to the page), causing jQuery to decode it into HTML, which is then pulled back out with .text().
              //http://stackoverflow.com/questions/10715801/javascript-decoding-html-entities
              //originally var decoded = $('<div/>').html(text).text();
              //$('<div/>') is too dangerous for use. More like $('<textarea/>') - it more safety.
              Selector         = $('<textarea/>').html(Selector).text();
              //RESET CASE
        if ( ! Sets ) {
          //Cleans style attributes
          $(Selector).attr('style' , '');

          //Resets the Sets to default
          Sets = DefaultSettings[key];
          //return;
        } else {
          Sets = JSON.parse( Sets || {} );
        }

        var Not           = Not || [],
          ColorHover         = ColorHover || [],
          DefaultColor       = DefaultColor || [],
          Color           = Color || [];

        //"Not" handling
          Not[Selector]         = DefaultSettings[key]['not'] || '';

        if ( Sets['color-hover'] ) {
          ColorHover[Selector]   = Sets['color-hover'] || false,
          DefaultColor[Selector]   = ('main' == DefaultSettings[key]['color']) ? ( SkinColors[CurrentSkin][0] || '#08c' ) : ( DefaultSettings[key]['color'] || '#777' ),
          SavedColor         = ('main' == DBSettings[key]['color']) ? DefaultColor[Selector] : DBSettings[key]['color'],
          Color[Selector]     = Sets['color'] || SavedColor || DefaultColor[Selector];

              $(Selector).not(Not[Selector]).hover(function() {
                  $(this).css('color', ColorHover[Selector]);
              }, function() {
                   $(this).css('color', Color[Selector]);
              }
            );
          }//end if color-hover

              //Other settings
            tcPreview ( key, Selector , Sets , Not[Selector] );

            //if users has check the 'override other style' checkbox, then change the style attribute of the selector
            // => flag all properties (but Font family because handle separately) with !important
            if ( ! Sets['important'] )
              return;
            else
              SetPropImportant( 'all' , $( Selector ) );
          } )
        });//end of wp.customize
  });


  function tcPreview ( SettingName , selector , Sets , Excluded ) {
    //Since v1.19 => MENU TIEMS adds a class name to the body tag to remove the first letter default customizr style
    //Check if the theme is Customizr with the .tc-header length check
    if ( 'menu_items' == SettingName && 0 != $('.tc-header').length )
      $('body').addClass('wfc-reset-menu-item-first-letter');

    //Sets = JSON.parse( Sets || {} );
    for ( var key in  Sets ) {
            switch( key ) {
                case 'font-family' :
                    var font_family = Sets[key],
            subset     = Sets['subset'] ? Sets['subset'] : 0;
                    //clean font
          clean_font_family = RemoveFontType(font_family);

          //adds the gfont link to parent frames only if font contains gfont
          if ( -1 != font_family.indexOf('gfont') ) {
            tcAddFontLink ( selector , clean_font_family, subset);
          }
          //Reset font-family property with $.css('font-family' ,'') because jQuery does not handle the !important flag on css() set mode.
          //@since v1.20
          $( selector ).css('font-family' , '');
          $( selector ).not(Excluded).css( toStyle(clean_font_family) );
          SetPropImportant( 'font-family' , $( selector ) );

                break;

                case 'static-effect' :
                  //Get an array of css classes
          var ClassesList = $( selector ).attr('class');

          //checks if we have a list of class to parse first
          if ( ClassesList ) {
            var ClassesList = $( selector ).attr('class').split(' ');
            //Loop over array and check if font-effect exists anywhere in the class name
            for(var i = 0; i < ClassesList.length; i++)
            {
                //Checks if font-effect exists in the class name
                if(ClassesList[i].indexOf('font-effect') != -1)
                {
                    //font-effect Exists, remove the class
                    $(selector).removeClass(ClassesList[i]);
                }
            }
          }//end if classeslist?

          //Add class
                    $( selector ).not(Excluded).addClass( 'font-effect-' + Sets[key] );

                    //adds the effect to temp setting object
                    TempSettings[SettingName]['static-effect'] = Sets[key];
                    TempSettings[SettingName]['has-tried-inset'] = ( 'inset' == TempSettings[SettingName]['static-effect'] ) ? true : TempSettings[SettingName]['has-tried-inset'];
                break;

                case 'icon' :
            var IconSelector = DefaultSettings[SettingName]['icon'];
            if ( 'hide' == Sets['icon'] ) {
              $( IconSelector ).addClass( 'tc-hide-icon');
            } else {
              $( IconSelector ).removeClass( 'tc-hide-icon');
            }
                break;

                //the color case handle the specific case where inset effect is set
                case 'color' :
                  HandleColor( SettingName , selector , Excluded , key , Sets[key] );
                break;

                default :
                  //reset css() for this property to avoid issues with !important flag if set
                  //@since v1.20
                  $( selector ).not(Excluded).css( key , '');
                    $( selector ).not(Excluded).css( key ,  Sets[key] );
                break;
          }//end switch
        }
  }

  //@return void
  //flag properties with !important
  //Font family because handle separately)
  //!!!After adding !important : Always reset css() before setting a new property with $ function css()
  function SetPropImportant( Prop, Selector ) {
    if ( ! Selector.attr('style') )
      return;

    var StyleItem =  Selector.attr('style').split(';');
    for ( var property in StyleItem ) {
      if( StyleItem[property].indexOf(Prop) != -1 ) {
        //remove !important
        (StyleItem[property]).replace( '!important' , '');
        //Check if !important is not already set first
        StyleItem[property] = StyleItem[property] + '!important';
      }
      //if important is checked, then apply !important to all but font-family (handled separately)
      if( 'all' == Prop && -1 == (StyleItem[property]).indexOf('font-family') && StyleItem[property] )
        StyleItem[property] = StyleItem[property] + '!important';
    }
    StyleItem = StyleItem.join(';');
    Selector.attr( 'style' , StyleItem );
  }


  function HandleColor( SettingName , selector , Excluded ,  setting , value ) {
    //do we have a current effect set ?(either from DB or set in the current customization session)
      var CurrentEffect = TempSettings[SettingName]['static-effect'] ? TempSettings[SettingName]['static-effect'] : '';


      if ( CurrentEffect != 'inset' && 'inset' == DBSettings[SettingName]['static-effect'] ) {
        $( selector ).css('background-color' , 'transparent');
      }
      $( selector ).not(Excluded).css( setting ,  value );
  }


  function RemoveFontType(font){
        return font ? font.replace('[cfont]' , '').replace('[gfont]' , '') : false;
    };

    function toReadable(font){
        return font ? font.replace(/[\+|:]/g, ' ') : false;
    };

    function removeChar(expression) {
        return expression ? expression.replace( /[^0-9\.]+/g , '') : false;
    }

    function CleanSelector(selector) {
      selector = selector.replace(/[\.|\#]/g, '');
      return selector.replace(/\s+/g, '-');
    }


  function toStyle(font){
         var split         = font.split(':'),
            font_family, font_weight, font_style = '';

        font_family       = split[0];
        //removes all characters
        font_weight       = split[1] ? removeChar(split[1]) : '';
        font_style        = ( split[1] && -1 != split[1].indexOf('italic') ) ? 'italic' : '';

        return {'font-family': toReadable(font_family), 'font-weight': ( font_weight || 400 ) , 'font-style': ( font_style || '' ) };
    };



  function tcAddFontLink (selector , font , subset) {
    Selectors[selector] = font;
         var apiUrl        = ['//fonts.googleapis.com/css?family='];
        apiUrl.push(font);

        //adds the subset parameter if specified
        if ( subset && 'all-subsets' != subset ) {
           apiUrl.push('&subset=' + subset );
        }

        //add font links
        if ($('link#' + CleanSelector(selector) ).length === 0) {
            $('link:last').after('<link class="gfont" id="' + CleanSelector(selector) + '" href="' + apiUrl.join('') + '" rel="stylesheet" type="text/css">');
        }
        else {
          $('link#' + CleanSelector(selector)).attr('href', apiUrl.join('') );
        }
    }

} )( jQuery );