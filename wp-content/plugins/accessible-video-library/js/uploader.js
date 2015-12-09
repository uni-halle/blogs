var mediaPopup = '';
(function ($) {
	"use strict";
	$(function () {
		/**
		 * Clears any existing Media Manager instances
		 * 
		 * @author Gabe Shackle <gabe@hereswhatidid.com>
		 * @modified Joe Dolson <plugins@joedolson.com>
		 * @return void
		 */
		function clear_existing() {
			if ( typeof mediaPopup !== 'string' ) {
				mediaPopup.detach();
				mediaPopup = '';
			}
		}
		$('.avl_post_fields')
				.on( 'click', '.textfield-field', function(e) {
					e.preventDefault();
					var $self = $(this),
						$inpField = $self.parent('.field-holder').find('input.textfield');
					clear_existing();
					mediaPopup = wp.media({
						multiple: false, // add, reset, false
						title: 'Choose an Uploaded Document',
						button: {
							text: 'Select'
						}
					});

					mediaPopup.on( 'select', function() {
						var selection = mediaPopup.state().get('selection'),
							id 		= '',
							fUrl	= '',
							img 	= '',
							height 	= '',
							width 	= '';
						if( selection ) {
							id 		= selection.first().attributes.id;
							fUrl = selection.first().attributes.url;
							height 	= 80;
							width 	= ( ( selection.first().attributes.width )/( selection.first().attributes.height ) )*80;
							$inpField.val( fUrl );
						}
					});
					mediaPopup.open();
				})
		});
	})(jQuery);