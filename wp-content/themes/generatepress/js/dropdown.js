(function ( $ ) {
	$.fn.GenerateDropdownMenu = function( options ) {
		// Set the default settings
		var settings = $.extend({
			transition: 'slide',
			transition_speed: 150,
			open_delay: 300,
			close_delay: 300
		}, options );
		
		var $dropdowns, mobile;
		
		$dropdowns = $( this );
		mobile = $( '.menu-toggle' );
		
		$dropdowns.children( 'ul' ).css( 'display','none' );
		
		$dropdowns.on('mouseover', function() {
			
			if ( mobile.is( ':visible' ) )
				return;
			
			var $this = $(this);

			if ($this.prop('hoverTimeout')) {
				$this.prop('hoverTimeout', clearTimeout($this.prop('hoverTimeout')));
			}

			$this.prop('hoverIntent', setTimeout(function() {
				if ( 'slide' == settings.transition ) {
					$this.children( 'ul' ).slideDown( settings.transition_speed ).css( 'display','block' );
				} else {
					$this.children( 'ul' ).fadeIn( settings.transition_speed ).css( 'display','block' );
				}
				$this.addClass('sfHover');
			}, settings.open_delay));
			
		}).on('mouseleave', function() {
			
			if ( mobile.is( ':visible' ) )
				return;
			
			var $this = $(this);

			if ($this.prop('hoverIntent')) {
				$this.prop('hoverIntent', clearTimeout($this.prop('hoverIntent')));
			}

			$this.prop('hoverTimeout', setTimeout(function() {
				$this.children( 'ul' ).fadeOut( settings.transition_speed );
				$this.removeClass('sfHover');
			}, settings.close_delay));
			
		});
		
		if ('ontouchstart' in document.documentElement) {
			if ( mobile.is( ':visible' ) )
				return;
			
			$dropdowns.each(function() {
				var $this = $(this);

				this.addEventListener('touchstart', function(e) {
					if (e.touches.length === 1) {
						// Prevent touch events within dropdown bubbling down to document
						e.stopPropagation();

						// Toggle hover
						if (!$this.hasClass('sfHover')) {
							// Prevent link on first touch
							if (e.target === this || e.target.parentNode === this) {
								e.preventDefault();
							}

							// Hide other open dropdowns
							$dropdowns.removeClass('sfHover');
							$this.addClass('sfHover');
							$this.children( 'ul' ).css( 'display','block' );

							// Hide dropdown on touch outside
							document.addEventListener('touchstart', closeDropdown = function(e) {
								e.stopPropagation();

								$this.removeClass('sfHover');
								$this.children( 'ul' ).css( 'display','none' );
								document.removeEventListener('touchstart', closeDropdown);
							});
						}
					}
				}, false);
			});
		}
		
		$.fn.GenerateDropdownMenu.destroy = function() {
			$dropdowns.children( 'ul' ).css( 'display','' );
			$dropdowns.unbind('mouseover mouseleave');
        }
	};
}( jQuery ));

jQuery( document ).ready( function( $ ) {
	// Initiate our dropdown menu
   $( '.sf-menu .menu-item-has-children' ).GenerateDropdownMenu();
});

// Nullify superfish function if it's being called in an old version of GP Premium
// This can be removed after a while (GP Premium 1.2.78)
;(function ($) {
	$.fn.superfish = function () {
		return false;
	};
})(jQuery, window);