// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.
(function( $ ) {
	 
    $.fn.changeFontsize = function( action ) {
 
    	var pageFontsize = Cookies.get('page-fontsize');
    	var fontsize = ( typeof pageFontsize !== 'undefined' ) 
    		? parseInt( pageFontsize )
    		: parseInt( this.css("font-size") );

    	if ( action !== 'undefined' ) {
	        if ( action === "increase") {
	            fontsize = fontsize + 2;
	        }
	 
	        if ( action === "decrease" ) {
	        	fontsize = fontsize - 2;
	        }

	        Cookies.set('page-fontsize', fontsize, { expires: 1 });       
    	}
    	
        this.css( 'font-size', fontsize );
        return this;
    };

    jQuery(document).ready(function(){
    	if ( typeof Cookies.get('page-fontsize') !== 'undefined' ) {
    		jQuery('body').changeFontsize();
    	}
    });
}( jQuery ));

// init jquery.rwdImageMaps.min
jQuery(document).ready(function(e) {
	jQuery('img[usemap]').rwdImageMaps();
});



    
