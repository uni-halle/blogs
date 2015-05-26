/*
 * WordPress Font Customizer front end scripts
 * copyright (c) 2014-2015 Nicolas GUILLAUME (nikeo), Press Customizr.
 * GPL2+ Licensed
 */
( function( $ ) {
	//gets the localized params
  var SavedSettings	= FrontParams.SavedSelectorsSettings,
		DefaultSettings = FrontParams.DefaultSettings,
		Families		= [],
		Subsets		= [];

	function UgetBrowser() {
          var browser = {},
              ua,
              match,
              matched;

          ua = navigator.userAgent.toLowerCase();

          match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
              /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
              /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
              /(msie) ([\w.]+)/.exec( ua ) ||
              ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
              [];

          matched = {
              browser: match[ 1 ] || "",
              version: match[ 2 ] || "0"
          };

          if ( matched.browser ) {
              browser[ matched.browser ] = true;
              browser.version = matched.version;
          }

          // Chrome is Webkit, but Webkit is also Safari.
          if ( browser.chrome ) {
              browser.webkit = true;
          } else if ( browser.webkit ) {
              browser.safari = true;
          }

          return browser;
	}//end of UgetBrowser

	var CurrentBrowser  = UgetBrowser();
	var CurrentBrowserName = '';

	//ADDS BROWSER CLASS TO BODY
	var i = 0;
	for (var browserkey in CurrentBrowser ) {
		if (i > 0)
			continue;
      CurrentBrowserName = browserkey;
     i++;
  }
	$('body').addClass( CurrentBrowserName || '' );


	//Applies effect and icons classes if any
	for ( var key in SavedSettings ){
		//"Not" handling
    var excluded			= SavedSettings[key].not || '';

		if ( SavedSettings[key]['static-effect'] && 'none' != SavedSettings[key]['static-effect'] ) {
			//inset effect can not be applied to Mozilla. @todo Check next versions
			if ( 'inset' == SavedSettings[key]['static-effect'] && true === CurrentBrowser.mozilla )
				continue;

			$( SavedSettings[key].selector ).not(excluded).addClass( 'font-effect-' + SavedSettings[key]['static-effect'] );
		}

		//icons
		if ( SavedSettings[key].icon && 'hide' == SavedSettings[key].icon ) {
			$( DefaultSettings[key].icon ).addClass( 'tc-hide-icon' );
		}
	}

} )( jQuery );


//GOOGLE FONTS STUFFS
//gets the localized params
// var Gfonts      = WebFontsParams.Gfonts,
//   Families    = [],
//   Subsets     = [];

// for ( var key in Gfonts ){
//   //Creates the subsets array
//   //if several subsets are defined for the same fonts > adds them and makes a subset array of unique subset values
//   var FontSubsets = Gfonts[key];
//   for ( var subkey in FontSubsets ) {
//     if ( 'all-subsets' == FontSubsets[subkey] )
//       continue;
//     if ( FontSubsets[subkey] && ! $.inArray( FontSubsets[subkey] , FontSubsets ) ) {
//       Subsets.push(Gfonts[key])
//     }
//   }
//   //fill the families array and add the subsets to the last family (Google Syntax)
//   Families.push( key );
// }

// //are subsets defined?
// if ( Subsets && Subsets.join(',') ) {
//   Families.push('&subset=' +  Subsets.join(',') );
// }

// if ( 0 != Gfonts.length ) {
//   //Loads the fonts
//   WebFont.load({
//       google: {
//         families: Families
//       },
//       // loading: function() {console.log('loading')},
//     // active: function() {},
//     // inactive: function() {},
//     // fontloading: function(familyName, fvd) {},
//     // fontactive: function(familyName, fvd) {},
//     // fontinactive: function(familyName, fvd) {}
//   });
// }