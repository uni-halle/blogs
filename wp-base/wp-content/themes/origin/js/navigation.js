/**
 * Handles toggling the navigation menu for small screens.
 */

(function ($) {

    var nav, button, menu;
    
    nav = $(".site-navigation");

    if (!nav) 
        return;

    button = $(".menu-toggle:first", nav);  
    menu = $("ul:first", nav);

    if (!button) 
        return;
    if (!menu.length || !menu.children().length) {
	    button.hide();
	    return;
    }

    button.click(function() {
	    menu.toggleClass('nav-menu');
        
        if(!button.hasClass('toggled-on')){
	        button.removeClass('toggled-on');
	        menu.removeClass('toggled-on');
        } else {
	        menu.addClass('toggled-on');
	        button.addClass('toggled-on');
        }
    });

})(jQuery);