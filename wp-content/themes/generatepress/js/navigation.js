/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens and enables tab
 * support for dropdown menus.
 */
( function() {
	var container, button, menu, links, subMenus;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.querySelector('.menu-toggle');
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );
	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
	};

	// Get all the link elements within the menu.
	links    = menu.getElementsByTagName( 'a' );
	subMenus = menu.getElementsByTagName( 'ul' );

	// Set menu items with submenus to aria-haspopup="true".
	for ( var i = 0, len = subMenus.length; i < len; i++ ) {
		subMenus[i].parentNode.setAttribute( 'aria-haspopup', 'true' );
	}

	// Each time a menu link is focused or blurred, toggle focus.
	for ( i = 0, len = links.length; i < len; i++ ) {
		links[i].addEventListener( 'focus', toggleFocus, true );
		links[i].addEventListener( 'blur', toggleFocus, true );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( -1 !== self.className.indexOf( 'focus' ) ) {
					self.className = self.className.replace( ' focus', '' );
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}
} )();

jQuery(window).load(function($) {
   
	var resizeTimer, sf, mobile;
    sf = jQuery('ul.sf-menu');
	mobile = jQuery( '.menu-toggle' );
	
	// Build a function that disables and enables superfish when needed
	function generateResizeNavigation() {
        if( ! mobile.is( ':visible' ) && !sf.hasClass('sf-js-enabled') ) {
			if (typeof jQuery.fn.superfish !== 'undefined' && jQuery.isFunction(jQuery.fn.superfish)) {
				// you only want SuperFish to be re-enabled once (sf.hasClass)
				sf.superfish({
					delay:       500,                            // one second delay on mouseout
					animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation
					speed:       'fast',                          // faster animation speed
					cssArrows:   false
				});
			}
        } else if ( mobile.is( ':visible' ) ) {
			if (typeof jQuery.fn.superfish !== 'undefined' && jQuery.isFunction(jQuery.fn.superfish)) {
				// smaller screen, disable SuperFish
				sf.superfish('destroy');
			}
        }
    };
	
	// Add dropdown toggle that display child menu items.
	jQuery( '.main-navigation .page_item_has_children > a, .main-navigation .menu-item-has-children > a' ).after( '<a href="#" class="dropdown-toggle" aria-expanded="false"><i class="fa fa-caret-down"></i></a>' );
	
	// When we resize the browser, check to see which dropdown type we should use
    jQuery(window).resize(function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(generateResizeNavigation, 250);
    });
	
	// Check to see which dropdown type we should use
	if ( mobile.is( ':visible' ) ) {
		generateResizeNavigation();
	}
	
	// Build the mobile button that displays the dropdown menu
	jQuery( '.dropdown-toggle' ).click( function( e ) {
		var _this = jQuery( this );
		e.preventDefault();
		_this.toggleClass( 'toggle-on' );
		_this.next( '.children, .sub-menu' ).toggleClass( 'toggled-on' );
		_this.attr( 'aria-expanded', _this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
		_this.html( _this.html() === '<i class="fa fa-caret-down"></i>' ? '<i class="fa fa-caret-up"></i>' : '<i class="fa fa-caret-down"></i>' );
			return false;
	} );
});