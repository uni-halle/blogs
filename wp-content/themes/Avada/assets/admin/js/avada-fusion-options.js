/* global noUiSlider, wNumb, avadaPOMessages, ajaxurl */
jQuery( document ).ready( function() {

	var $rangeSlider,
	    $i,
	    fusionPageOptions;

	jQuery( '.pyre_field select:not(.hidden-sidebar)' ).select2({
		minimumResultsForSearch: 10,
		dropdownCssClass: 'avada-select2'
	});

	jQuery( '.pyre_field.avada-buttonset a' ).on( 'click', function( e ) {
		var $radiosetcontainer;

		e.preventDefault();
		$radiosetcontainer = jQuery( this ).parents( '.fusion-form-radio-button-set' );
		$radiosetcontainer.find( '.ui-state-active' ).removeClass( 'ui-state-active' );
		jQuery( this ).addClass( 'ui-state-active' );
		$radiosetcontainer.find( '.button-set-value' ).val( $radiosetcontainer.find( '.ui-state-active' ).data( 'value' ) ).trigger( 'change' );
	});

	jQuery( '.pyre_field.avada-color input' ).each( function() {
		var self = jQuery( this ),
		    $defaultReset = self.parents( '.pyre_metabox_field' ).find( '.pyre-default-reset' );

		// Picker with default.
		if ( jQuery( this ).data( 'default' ) &&  jQuery( this ).data( 'default' ).length ) {
			 jQuery( this ).wpColorPicker( {
				change: function( event, ui ) {
					colorChange( ui.color.toString(), self, $defaultReset );
				},
				clear: function( event ) {
					colorClear( event, self );
				},
				palettes: ['#000000', '#ffffff', '#f44336', '#E91E63', '#03A9F4', '#00BCD4', '#8BC34A', '#FFEB3B', '#FFC107', '#FF9800', '#607D8B']
			} );

			// Make it so the reset link also clears color.
			$defaultReset.on( 'click', 'a', function( event ) {
				event.preventDefault();
				colorClear( event, self );
			});

		// Picker without default.
		} else {
			jQuery( this ).wpColorPicker( {
				palettes: ['#000000', '#ffffff', '#f44336', '#E91E63', '#03A9F4', '#00BCD4', '#8BC34A', '#FFEB3B', '#FFC107', '#FF9800', '#607D8B']
			} );
		}

		// For some reason non alpha are not triggered straight away.
		if ( true !== jQuery( this ).data( 'alpha' ) ) {
			jQuery( this ).wpColorPicker().change();
		}
	});

	function avadaCheckDependency( $currentValue, $desiredValue, $comparison ) {
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
	}

	function avadaLoopDependencies( $container ) {
		var $passed = false;

		$container.find( 'span' ).each( function() {

			var $value = jQuery( this ).data( 'value' ),
			    $comparison = jQuery( this ).data( 'comparison' ),
			    $field = jQuery( this ).data( 'field' );

			$passed = avadaCheckDependency( jQuery( '#pyre_' + $field ).val(), $value, $comparison );
			return $passed;
		});
		if ( $passed ) {
			 $container.parents( '.pyre_metabox_field' ).fadeIn( 300 );
		} else {
			 $container.parents( '.pyre_metabox_field' ).hide();
		}
	}

	jQuery( '.avada-dependency' ).each( function() {
		avadaLoopDependencies( jQuery( this ) );
	});
	jQuery( '[id*="pyre"]' ).on( 'change', function() {
		var $id = jQuery( this ).attr( 'id' ),
		    $field = $id.replace( 'pyre_', '' );
		jQuery( 'span[data-field="' + $field + '"]' ).each( function() {
			avadaLoopDependencies( jQuery( this ).parents( '.avada-dependency' ) );
		});
	});

	function createSlider( $slide, $targetId, $rangeInput, $min, $max, $step, $value, $decimals, $rangeDefault, $hiddenValue, $defaultValue, $direction ) {

		// Create slider with values passed on in data attributes.
		var $slider = noUiSlider.create( $rangeSlider[ $slide ], {
				start: [ $value ],
				step: $step,
				direction: $direction,
				range: {
					'min': $min,
					'max': $max
				},
				format: wNumb({
					decimals: $decimals
				})
		    }),
		    $notFirst = false;

		// Check if default is currently set.
		if ( $rangeDefault && '' === $hiddenValue.val() ) {
			$rangeDefault.parent().addClass( 'checked' );
		}

		// If this range has a default option then if checked set slider value to data-value.
		if ( $rangeDefault ) {
			$rangeDefault.on( 'click', function( e ) {
				e.preventDefault();
				$rangeSlider[$slide].noUiSlider.set( $defaultValue );
				$hiddenValue.val( '' );
				jQuery( this ).parent().addClass( 'checked' );
			});
		}

		// On slider move, update input
		$slider.on( 'update', function( values, handle ) {
			if ( $rangeDefault && $notFirst ) {
				$rangeDefault.parent().removeClass( 'checked' );
				$hiddenValue.val( values[handle] );
			}
			$notFirst = true;
			jQuery( this.target ).closest( '.fusion-slider-container' ).prev().val( values[handle] );
			jQuery( '#' + $targetId ).trigger( 'change' );
			if ( jQuery( '#' + $targetId ).length ) {
				jQuery( '#' + $targetId ).trigger( 'fusion-changed' );
			} else {
				jQuery( '#slider' + $targetId ).trigger( 'fusion-changed' );
			}
		});

		// On manual input change, update slider position
		$rangeInput.on( 'keyup', function( values, handle ) {
			if ( $rangeDefault ) {
				$rangeDefault.parent().removeClass( 'checked' );
				$hiddenValue.val( values[handle] );
			}

			if ( this.value !== $rangeSlider[$slide].noUiSlider.get() ) {
				$rangeSlider[$slide].noUiSlider.set( this.value );
			}
		});
	}

	$rangeSlider = jQuery( '.pyre_field.avada-range .fusion-slider-container' );

	if ( $rangeSlider.length ) {

		// Counter variable for sliders
		$i = 0;

		// Method for retreiving decimal places from step
		Number.prototype.countDecimals = function() {
			if ( Math.floor( this.valueOf() ) === this.valueOf() ) {
				return 0;
			}
			return this.toString().split( '.' )[1].length || 0;
		};

		// Each slider on page, determine settings and create slider
		$rangeSlider.each( function() {

			var $targetId     = jQuery( this ).data( 'id' ),
			    $rangeInput   = jQuery( this ).prev( '.fusion-slider-input' ),
			    $min          = jQuery( this ).data( 'min' ),
			    $max          = jQuery( this ).data( 'max' ),
			    $step         = jQuery( this ).data( 'step' ),
			    $direction    = jQuery( this ).data( 'direction' ),
			    $value        = $rangeInput.val(),
			    $decimals     = $step.countDecimals(),
			    $rangeDefault = ( jQuery( this ).parents( '.pyre_metabox_field' ).find( '.fusion-range-default' ).length ) ? jQuery( this ).parents( '.pyre_metabox_field' ).find( '.fusion-range-default' ) : false,
			    $hiddenValue  = ( $rangeDefault ) ? jQuery( this ).parent().find( '.fusion-hidden-value' ) : false,
			    $defaultValue = ( $rangeDefault ) ? jQuery( this ).parents( '.pyre_metabox_field' ).find( '.fusion-range-default' ).data( 'default' ) : false;

			createSlider( $i, $targetId, $rangeInput, $min, $max, $step, $value, $decimals, $rangeDefault, $hiddenValue, $defaultValue, $direction );

			$i++;
		});

	}

	function colorChange( value, self, defaultReset ) {
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
	}

	function colorClear( event, self ) {
		var defaultColor = self.data( 'default' );

		if ( null !== defaultColor ) {
			self.val( defaultColor );
			self.change();
			self.val( '' );
			self.parent().parent().find( '.wp-color-result' ).css( 'background-color', defaultColor );
		}

		self.trigger( 'fusion-changed' );
	}

	/* PO export / import tab */

	fusionPageOptions = {

		init: function() {

			var self =  this;

			jQuery( '#fusion-page-options-save' ).on( 'click', self.saveOptions );
			jQuery( '#fusion-page-options-import-saved' ).on( 'click', self.importSavedOptions );
			jQuery( '#fusion-page-options-delete-saved' ).on( 'click', self.deleteSaved );

			jQuery( '#fusion-saved-page-options-select' ).on( 'change', self.showHideButtons );

			jQuery( '#fusion-page-options-import' ).on( 'click', self.importOptions );
			jQuery( '#fusion-page-options-file-input' ).on( 'change', self.prepareUpload );

			jQuery( '#fusion-page-options-export' ).on( 'click', self.exportOptions );

		},

		exportOptions: function() {
			var tempBeforeUnload = jQuery._data( window, 'events' ).beforeunload;
			jQuery._data( window, 'events' ).beforeunload = null;

			setTimeout( function() {
				jQuery._data( window, 'events' ).beforeunload = tempBeforeUnload;
			}, 350 );
		},

		saveOptions: function( e ) {
			var data = {
					action: 'fusion_page_options_save',
					fusion_po_nonce: jQuery( '#fusion-page-options-nonce' ).val(),
					post_id: jQuery( this ).data( 'post_id' ),
					post_type: jQuery( this ).data( 'post_type' ),
					options_title: jQuery( '#fusion-new-page-options-name' ).val()
			    },
			    poDialog = jQuery( '#avada-po-dialog' );

			e.preventDefault();

			if ( '' === jQuery( '#fusion-new-page-options-name' ).val().trim() ) {
				poDialog.html( avadaPOMessages.saveTitleWarning );

				jQuery( '#' + poDialog.attr( 'id' ) ).dialog({
					dialogClass: 'avada-po-dialog',
					resizable: false,
					draggable: false,
					height: 'auto',
					width: 400,
					modal: true,
					buttons: {
						'OK': function() {
							poDialog.html( '' );
							jQuery( this ).dialog( 'close' );
						}
					}
				});

				return;
			}

			jQuery( '#fusion-page-options-loader' ).show();

			jQuery.get( {
				url: ajaxurl,
				data: data,
				dataType: 'json',
				success: function( response ) {
					var html;

					html  = '<option value="' + response.saved_post_id + '">';
					html += response.saved_post_title;
					html += '</option>';

					jQuery( '#fusion-saved-page-options-select' ).append( html );

					jQuery( '#fusion-new-page-options-name' ).val( '' );

					jQuery( '#fusion-page-options-loader' ).hide();

				}
			} );
		},

		importSavedOptions: function( e ) {
			var data = {
					action: 'fusion_page_options_import_saved',
					fusion_po_nonce: jQuery( '#fusion-page-options-nonce' ).val(),
					post_id: jQuery( '#fusion-saved-page-options-select' ).data( 'post_id' ),
					saved_post_id: jQuery( '#fusion-saved-page-options-select' ).val()
			    };

			e.preventDefault();

			jQuery( '#fusion-page-options-loader' ).show();

			jQuery.get( {
				url: ajaxurl,
				data: data,
				dataType: 'json',
				success: function( response ) {
					updatePOPanel( response.custom_fields );
					jQuery( '#fusion-page-options-loader' ).hide();
				}
			} );

		},

		deleteSaved: function( e ) {
			var savedPostID = jQuery( '#fusion-saved-page-options-select' ).val(),
			    data        = {
					action: 'fusion_page_options_delete',
					fusion_po_nonce: jQuery( '#fusion-page-options-nonce' ).val(),
					saved_post_id: savedPostID
			    };

			e.preventDefault();

			jQuery( '#fusion-page-options-loader' ).show();

			jQuery.get( {
				url: ajaxurl,
				data: data,
				success: function() {
					jQuery( '#fusion-saved-page-options-select option[value="' +  savedPostID + '"]' ).remove();
					jQuery( '#fusion-page-options-loader' ).hide();

					jQuery( '#fusion-page-options-buttons-wrap' ).fadeOut();
				}
			} );

		},

		prepareUpload: function( e ) {
			var file = e.target.files,
			    data = new FormData(),
			    poDialog = jQuery( '#avada-po-dialog' );

			jQuery( '#fusion-page-options-loader' ).show();

			data.append( 'action', 'fusion_page_options_import' );
			data.append( 'fusion_po_nonce', jQuery( '#fusion-page-options-nonce' ).val() );
			data.append( 'post_id', jQuery( '#fusion-page-options-import' ).data( 'post_id' ) );

			jQuery.each( file, function( key, value ) {

				if ( 'json' !== value.name.substr( value.name.lastIndexOf( '.' ) + 1 ) ) {
					poDialog.html( avadaPOMessages.importJSONWarning );

					jQuery( '#' + poDialog.attr( 'id' ) ).dialog({
						dialogClass: 'avada-po-dialog',
						resizable: false,
						draggable: false,
						height: 'auto',
						width: 400,
						modal: true,
						buttons: {
							'OK': function() {
								poDialog.html( '' );
								jQuery( this ).dialog( 'close' );
							}
						}
					});
					return false;
				}
				data.append( 'po_file_upload', value );
			});

			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: data,
				cache: false,
				dataType: 'json',
				processData: false, // Don't process the files
				contentType: false, // Set content type to false as jQuery will tell the server its a query string request
				success: function( response ) {
					updatePOPanel( response.custom_fields );
					jQuery( '#fusion-page-options-loader' ).hide();
				}

			});
		},

		showHideButtons: function() {

			if ( '' !== jQuery( this ).val() ) {
				jQuery( '#fusion-page-options-buttons-wrap' ).fadeIn();
			} else {
				jQuery( '#fusion-page-options-buttons-wrap' ).fadeOut();
			}

		},

		importOptions: function( e ) {
			e.preventDefault();
			jQuery( '#fusion-page-options-file-input' ).trigger( 'click' );
		}

	};

	function updatePOPanel ( customFields ) {

		jQuery.each( customFields, function( id, value ) {
			var $el;

			/**
			 * Multiple sidebar plugin exception.
			 */
			if ( 'sbg_selected_sidebar' === id || 'sbg_selected_sidebar_replacement' === id || 'sbg_selected_sidebar_2' === id || 'sbg_selected_sidebar_2_replacement' === id ) {
				$el = jQuery( 'select[data-fusion_id="' + id + '"]' );

				$el.attr( 'value', value ).trigger( 'change' );

				// Continue.
				return true;
			}

			$el = jQuery( '#' + id );

			if ( $el.hasClass( 'button-set-value' ) ) {

				$el.siblings( '[data-value="' + value + '"]' ).trigger( 'click' );

				// Continue.
				return true;
			}

			$el.val( value );

			// Range field.
			if ( $el.is( ':hidden' ) && $el.parent( '.pyre_field' ).hasClass( 'avada-range' ) ) {
				$el.siblings( '.fusion-slider-input' ).attr( 'value', value ).trigger( 'keyup' );
			} else {
				$el.trigger( 'change' );
			}

		});
	}

	fusionPageOptions.init();

	jQuery( '.pyre_metabox_tab:not(#pyre_tab_avada_page_options)' ).on( 'change fusion-changed',
		'input, textarea, select, radio, input[type=checkbox], input[type=hidden]',
		function() {
			jQuery( '.avada-po-warning' ).slideDown();
			jQuery( '#pyre_tab_avada_page_options' ).addClass( 'fusion-options-changed' );
		}
	);
});
