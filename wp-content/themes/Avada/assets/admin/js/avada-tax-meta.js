/*
 * Handler for Taxonomy meta fields.
 */
( function() {
	var avadaTaxMeta = {};

	/**
	 * Initialize all color pickers.
	 *
	 * @returns {void}
	 */
	avadaTaxMeta.initColorPickers = function() {
		jQuery( '.avada-tax-color' ).each( function() {
			var self = jQuery( this ),
				$defaultReset = self.parents( '.avada-tax-meta-field' ).find( '.tax-meta-default-reset' );

			// Picker with default.
			if ( jQuery( this ).data( 'default' ) &&  jQuery( this ).data( 'default' ).length ) {
				 jQuery( this ).wpColorPicker( {
					change: function( event, ui ) {
						avadaTaxMeta.colorChange( ui.color.toString(), self, $defaultReset );
					},
					clear: function( event ) {
						avadaTaxMeta.colorClear( event, self );
					},
					palettes: ['#000000', '#ffffff', '#f44336', '#E91E63', '#03A9F4', '#00BCD4', '#8BC34A', '#FFEB3B', '#FFC107', '#FF9800', '#607D8B']
				} );

				// Make it so the reset link also clears color.
				$defaultReset.on( 'click', 'a', function( event ) {
					event.preventDefault();
					avadaTaxMeta.colorClear( event, self );
				});

			// Picker without default.
			} else {
				jQuery( this ).wpColorPicker( {
					palettes: ['#000000', '#ffffff', '#f44336', '#E91E63', '#03A9F4', '#00BCD4', '#8BC34A', '#FFEB3B', '#FFC107', '#FF9800', '#607D8B']
				} );
			}
		});
	};

	/**
	 * Color change event handler.
	 *
	 * @param {string} [value]        The current value.
	 * @param {object} [self]         The $this object.
	 * @param {object} [defaultReset] The reset element.
	 * @returns {void}
	 */
	avadaTaxMeta.colorChange = function( value, self, defaultReset ) {
		var defaultColor = self.data( 'default' );

		if ( value === defaultColor ) {
			defaultReset.addClass( 'checked' );
		} else {
			defaultReset.removeClass( 'checked' );
		}

		if ( '' === value && null !== defaultColor ) {
			self.val( defaultColor );
			self.change();
			self.val( '' );
		}

		self.trigger( 'fusion-changed' );
	};

	/**
	 * Color clear event handler.
	 *
	 * @param {object} [event] Event refrence.
	 * @param {object} [self]  Reference to the element.
	 * @returns {void}
	 */
	avadaTaxMeta.colorClear = function( event, self ) {
		var defaultColor = self.data( 'default' );

		if ( null !== defaultColor ) {
			self.val( defaultColor );
			self.change();
			self.val( '' );
			self.parent().parent().find( '.wp-color-result' ).css( 'background-color', defaultColor );
		}

		self.trigger( 'fusion-changed' );
	};

	/**
	 * Initialize media frame popups and their actions.
	 *
	 * @returns {void}
	 */
	avadaTaxMeta.initMediaFrames = function() {
		var frame,
		    metaBox      = jQuery( '.avada-tax-img-field' ),
		    addImgLink   = metaBox.find( '.avada-tax-image-upload' ),
		    delImgLink   = metaBox.find( '.avada-tax-image-upload-clear' ),
		    imgContainer = metaBox.find( '.avada-tax-image-preview' ),
		    imgIdInput   = metaBox.find( '.avada-tax-image-url' );
			pContainer   = '';

		// On image link click.
		addImgLink.on( 'click', function( event ) {
			event.preventDefault();

			pContainer = jQuery( this ).parent();

			// If the media frame already exists, reopen it.
			if ( frame ) {
				frame.open();
				return;
			}

			// Create a new media frame.
			frame = wp.media({
				button: {
					text: 'Use this media'
				},
				multiple: false
			});

			// When an image is selected in the media frame.
			frame.on( 'select', function() {

				// Get media attachment details from the frame state.
				var attachment = frame.state().get( 'selection' ).first().toJSON();

				// Send the attachment id to our hidden input.
				pContainer.find( imgIdInput ).val( attachment.url ).trigger( 'change' );

				// Hide the add image link.
				pContainer.find( addImgLink ).addClass( 'hidden' );

				// Unhide the remove image link.
				pContainer.find( delImgLink ).removeClass( 'hidden' );
			});

			// Finally, open the modal on click.
			frame.open();
		});

		// Delete image link.
		delImgLink.on( 'click', function( event ) {
			event.preventDefault();

			pContainer = jQuery( this ).parent();

			// Clear out the preview image.
			pContainer.find( imgContainer ).html( '' );

			// Un-hide the add image link.
			pContainer.find( addImgLink ).removeClass( 'hidden' );

			// Hide the delete image link.
			pContainer.find( delImgLink ).addClass( 'hidden' );

			// Delete the image id from the hidden input.
			pContainer.find( imgIdInput ).val( '' ).trigger( 'change' );
		});
	};

	/**
	 * Perform clear form operations on ajax complete.
	 *
	 * @param {object} [event]
	 * @param {object} [xhr]
	 * @returns {void}
	 */
	avadaTaxMeta.onAjaxComplete = function( event, xhr ) {
		var $response;

		try {
			$response = jQuery.parseXML( xhr.responseText );

			// Exit on error.
			if ( jQuery( $response ).find( 'wp_error' ).length ) {
				return;
			}

			// Verify response.
			jQuery( $response ).find( 'response' ).each( function( i, e ) {
				var termID;
				if ( jQuery( e ).attr( 'action' ).indexOf( 'add-tag' ) > -1 ) {

					// If new term added.
					termID = jQuery( e ).find( 'term_id' );
					if ( termID ) {
						avadaTaxMeta.clearFormFields();
					}
				}
			});
		} catch ( err ) {}
	};

	/**
	 * Clears avada taxonomy meta form fields.
	 *
	 * @returns {void}
	 */
	avadaTaxMeta.clearFormFields = function() {

		// Clear all fields.
		jQuery( '.avada-tax-meta-field input[type="text"], .avada-tax-meta-field textarea' ).val( '' );
		jQuery( '.avada-tax-meta-field .tax-meta-default-reset a' ).trigger( 'click' );
		jQuery( '.avada-tax-meta-field .avada-tax-image-upload' ).removeClass( 'hidden' );
		jQuery( '.avada-tax-meta-field .avada-tax-image-upload-clear' ).addClass( 'hidden' );
		jQuery( '.avada-tax-meta-field select' ).val( '' );
		jQuery( '.avada-tax-meta-field input[type="radio"]' ).prop( 'checked', false );

		// Update select values.
		jQuery.each( jQuery( '.avada-tax-meta-field select' ), function( i, item ) {
			var $item = jQuery( item );
			$item.val( $item.find( 'option:first' ).val() ).trigger( 'change' );
		});

		// Update button set.
		jQuery.each( jQuery( '.avada-tax-button-set a' ), function( i, item ) { // jshint ignore:line
			var $radiosetcontainer;
			$radiosetcontainer = jQuery( this ).parents( '.avada-tax-button-set' );
			$radiosetcontainer.find( '.ui-state-active' ).removeClass( 'ui-state-active' );
			$radiosetcontainer.find( '.ui-button' ).first().addClass( 'ui-state-active' );
			$radiosetcontainer.find( '.button-set-value' ).val( 'default' ).trigger( 'change' );
		});
	};

	/**
	 * Enable dependencies.
	 *
	 * @returns {void}
	 */
	avadaTaxMeta.enableDependencies = function() {
		jQuery( '.avada-tax-dependency' ).each( function() {
			avadaTaxMeta.avadaTaxLoopDependencies( jQuery( this ) );
		});
	};

	/**
	 * Loop through dependencies and show/hide.
	 *
	 * @returns {void}
	 */
	avadaTaxMeta.avadaTaxLoopDependencies = function( $container ) {
		var $passed = false;

		$container.find( 'span' ).each( function() {

			var $value      = jQuery( this ).data( 'value' ),
			    $comparison = jQuery( this ).data( 'comparison' ),
			    $field      = jQuery( this ).data( 'field' );

			$passed = avadaTaxMeta.avadaTaxCheckDependency( jQuery( '#' + $field ).val(), $value, $comparison );
			return $passed;
		});
		if ( $passed ) {
			 $container.parents( '.avada-tax-meta-field' ).show();
		} else {
			 $container.parents( '.avada-tax-meta-field' ).hide();
		}
	};

	/**
	 * Check if dependency active or not.
	 *
	 * @returns {boolean}
	 */
	avadaTaxMeta.avadaTaxCheckDependency = function( $currentValue, $desiredValue, $comparison ) {
		if ( '==' === $comparison || '=' === $comparison ) {
			if ( $currentValue == $desiredValue ) { // jshint ignore:line
				return true;
			}
		} else if ( '>=' === $comparison ) {
			if ( $currentValue >= $desiredValue ) {
				return true;
			}
		} else if ( '<=' === $comparison ) {
			if ( $currentValue <= $desiredValue ) {
				return true;
			}
		} else if ( '>' === $comparison ) {
			if ( $currentValue > $desiredValue ) {
				return true;
			}
		} else if ( '<' === $comparison ) {
			if ( $currentValue < $desiredValue ) {
				return true;
			}
		} else if ( '!=' === $comparison ) {
			if ( $currentValue != $desiredValue ) { // jshint ignore:line
				return true;
			}
		}

		return false;
	};

  jQuery( '.avada-tax-button-set a' ).on( 'click', function( e ) {
		var $radiosetcontainer;

		e.preventDefault();
		$radiosetcontainer = jQuery( this ).parents( '.avada-tax-button-set' );
		$radiosetcontainer.find( '.ui-state-active' ).removeClass( 'ui-state-active' );
		jQuery( this ).addClass( 'ui-state-active' );
		$radiosetcontainer.find( '.button-set-value' ).val( $radiosetcontainer.find( '.ui-state-active' ).data( 'value' ) ).trigger( 'change' );
	});

	jQuery( 'div.avada-tax-header, tr.avada-tax-heading-edit' ).on( 'click', function() {
		if ( jQuery( this ).find( 'span' ).hasClass( 'close' ) ) {
			jQuery( '.avada-tax-meta-field' ).not( '.avada-tax-heading, .avada-tax-header' ).show();
			avadaTaxMeta.enableDependencies();
		} else {
			jQuery( '.avada-tax-meta-field' ).not( '.avada-tax-heading, .avada-tax-header' ).hide();
			jQuery( '.avada-sliders-group' ).hide();
		}

		jQuery( this ).find( '.toggle-indicator' ).toggleClass( 'close' );
	});

	// INIT stuff.
	avadaTaxMeta.initColorPickers();
	avadaTaxMeta.initMediaFrames();
	avadaTaxMeta.enableDependencies();

	jQuery( '.avada-tax-meta-field' ).find( 'select, input' ).on( 'change', function() {
		avadaTaxMeta.enableDependencies();
	});

	jQuery( '.avada-tax-meta-field select:not(.hidden-sidebar)' ).select2({
		minimumResultsForSearch: 10,
		dropdownCssClass: 'avada-select2',
		allowClear: true
	});

	jQuery( document ).ajaxComplete(function( event, xhr, settings ) {
		avadaTaxMeta.onAjaxComplete( event, xhr, settings );
	});
})( jQuery );
