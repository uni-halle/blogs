/* global FusionPageBuilderEvents, fusionBuilderGetContent, FusionPageBuilderApp, FusionPageBuilderViewManager, FusionPageBuilderElements, fusionHistoryManager, fusionBuilderText, alert */
var FusionPageBuilder = FusionPageBuilder || {};

( function( $ ) {

	$( document ).ready( function() {

		// Builder Inner Row View
		FusionPageBuilder.InnerRowView = window.wp.Backbone.View.extend( {

			className: 'fusion_builder_row_inner fusion_builder_column_element',

			template: FusionPageBuilder.template( $( '#fusion-builder-row-inner-template' ).html() ),

			events: {
				'click .fusion-builder-remove-inner-row': 'removeRow',
				'click .fusion-builder-save-inner-row-dialog-button': 'saveElementDialog',
				'click .fusion-builder-clone-inner-row': 'cloneNestedRow',
				'click .fusion-builder-inner-row-overlay': 'showInnerRowDialog',
				'click .fusion-builder-inner-row-close': 'hideInnerRowDialog',
				'click .fusion-builder-inner-row-close-icon': 'hideInnerRowDialog',
				'click .fusion-builder-modal-save': 'saveInnerRowSettings',
				'click .fusion-builder-insert-inner-column': 'displayInnerColumn'
			},

			initialize: function() {
				this.$el.attr( 'data-cid', this.model.get( 'cid' ) );

				// Close modal view
				this.listenTo( FusionPageBuilderEvents, 'fusion-close-inner-modal', this.hideInnerRowDialog );
			},

			showInnerRowDialog: function( event ) {

				var thisEl = this.$el;

				if ( event ) {
					event.preventDefault();
				}

				FusionPageBuilderApp.allContent = fusionBuilderGetContent( 'content', true );
				FusionPageBuilderApp.pauseBuilder = true;
				thisEl.find( '.fusion-builder-row-content' ).show();

				$( 'body' ).addClass( 'fusion_builder_inner_row_no_scroll' ).append( '<div class="fusion_builder_modal_inner_row_overlay"></div>' );
			},

			hideInnerRowDialog: function( event ) {

				var thisEl             = this.$el,
				    innerColumnsString = '';

				if ( event ) {
					event.preventDefault();
				}

				// If builder is paused, resume it.
				if ( FusionPageBuilderApp.pauseBuilder ) {
					FusionPageBuilderApp.pauseBuilder = false;

					// Reset models with previous data.
					FusionPageBuilderApp.clearBuilderLayout();
					FusionPageBuilderApp.$el.find( '.fusion_builder_container' ).remove();
					FusionPageBuilderApp.createBuilderLayout( FusionPageBuilderApp.allContent );
				}

				thisEl.find( '.fusion-builder-row-content' ).hide();

				$( 'body' ).removeClass( 'fusion_builder_inner_row_no_scroll' );
				$( '.fusion_builder_modal_inner_row_overlay' ).remove();

				this.$el.find( '.fusion-builder-column-inner' ).each( function() {
					if ( 'undefined' !== typeof jQuery( this )[0].dataset ) {
						innerColumnsString += jQuery( this )[0].dataset.columnSize.replace( '_', '/' ) + ' + ';
					} else {
						innerColumnsString += jQuery( this ).data( 'column-size' ).replace( '_', '/' ) + ' + ';
					}
				});

				this.$el.find( '> p' ).html( innerColumnsString.slice( 0, innerColumnsString.length - 3 ) );
			},

			saveInnerRowSettings: function() {
				var innerColumnsString = '',
					element,
					thisEl;

				FusionPageBuilderApp.pauseBuilder = false;
				thisEl = this.$el;

				element = FusionPageBuilderElements.find( function( model ) {
					return model.get( 'cid' ) === thisEl.data( 'cid' );
				} );

				element.set( { 'chnaged': { 'changed':true } } );

				FusionPageBuilderEvents.trigger( 'fusion-element-edited' );

				this.$el.find( '.fusion-builder-column-inner' ).each( function() {
					if ( 'undefined' !== typeof jQuery( this )[0].dataset ) {
						innerColumnsString += jQuery( this )[0].dataset.columnSize.replace( '_', '/' ) + ' + ';
					} else {
						innerColumnsString += jQuery( this ).data( 'column-size' ).replace( '_', '/' ) + ' + ';
					}
				});

				this.$el.find( '> p' ).html( innerColumnsString.slice( 0, innerColumnsString.length - 3 ) );

				this.$el.find( '.fusion-builder-row-content' ).hide();
				$( 'body' ).removeClass( 'fusion_builder_inner_row_no_scroll' );
				$( '.fusion_builder_modal_inner_row_overlay' ).remove();
				element.set( { 'chnaged': {} } );

				// Save history state
				fusionHistoryManager.turnOnTracking();
				window.fusionHistoryState = fusionBuilderText.edited_nested_columns;
				FusionPageBuilderEvents.trigger( 'fusion-element-edited' );
			},

			displayInnerColumn: function( event ) {
				var view;

				if ( event ) {
					event.preventDefault();
				}

				FusionPageBuilderApp.parentRowId = this.model.get( 'cid' );

				view = new FusionPageBuilder.NestedColumnLibraryView( {
					model: this.model,
					collection: this.collection,
					attributes: {
						'data-modal_view': 'nested_column_library'
					},
					view: this
				} );

				$( this.$el ).find( '.fusion-builder-row-content' ).append( view.render().el );
			},

			render: function() {
				var innerColumnsWrapper = this.$el,
				    innerColumnsString  = '';

				this.$el.html( this.template( this.model.toJSON() ) );

				this.sortableColumns();

				setTimeout( function() {
					innerColumnsWrapper.find( '.fusion-builder-column-inner' ).each( function() {
						innerColumnsString += jQuery( this ).data( 'column-size' ).replace( '_', '/' ) + ' + ';
					});

					innerColumnsWrapper.find( '> h4' ).after( '<p>' + innerColumnsString.slice( 0, innerColumnsString.length - 3 ) + '</p>' );
				}, 100 );

				// If global, make it.
				if ( 'undefined' !== typeof ( this.model.attributes.params ) && 'undefined' !== typeof ( this.model.attributes.params.fusion_global ) ) {
					FusionPageBuilderApp.addClassToElement( this.$el, 'fusion-global-element', this.model.attributes.params.fusion_global );
				}

				return this;
			},

			cloneNestedRow: function( event, parentCID ) {
				var innerRowAttributes,
				    thisInnerRow;

				if ( event ) {
					event.preventDefault();
				}

				innerRowAttributes         = $.extend( true, {}, this.model.attributes );
				innerRowAttributes.created = 'manually';
				innerRowAttributes.cid     = FusionPageBuilderViewManager.generateCid();

				if ( event ) {
					innerRowAttributes.appendAfter = this.$el;
				}

				if ( parentCID ) {
					innerRowAttributes.parent = parentCID;
				}

				FusionPageBuilderApp.collection.add( innerRowAttributes );

				// Parse inner columns
				thisInnerRow = this.$el;
				thisInnerRow.find( '.fusion-builder-column-inner' ).each( function() {
					var $thisColumnInner  = $( this ),
					    columnInnerCID    = $thisColumnInner.data( 'cid' ),
					    innerColumnModule = FusionPageBuilderElements.findWhere( { cid: columnInnerCID } ),

					    // Clone model attritubes
					    innerColAttributes = $.extend( true, {}, innerColumnModule.attributes );

					innerColAttributes.created = 'manually';
					innerColAttributes.cid     = FusionPageBuilderViewManager.generateCid();
					innerColAttributes.parent  = innerRowAttributes.cid;

					FusionPageBuilderApp.collection.add( innerColAttributes );

					// Parse elements inside inner col
					$thisColumnInner.find( '.fusion_module_block' ).each( function() {
						var thisModule = $( this ),
						    moduleCID  = 'undefined' === typeof thisModule.data( 'cid' ) ? thisModule.find( '.fusion-builder-data-cid' ).data( 'cid' ) : thisModule.data( 'cid' ),

						    // Get model from collection by cid
						    module = FusionPageBuilderElements.find( function( model ) {
								return model.get( 'cid' ) === moduleCID;
						    } ),

						    // Clone model attritubes
						    innerElementAttributes = $.extend( true, {}, module.attributes );

						innerElementAttributes.created = 'manually';
						innerElementAttributes.cid     = FusionPageBuilderViewManager.generateCid();
						innerElementAttributes.parent  = innerColAttributes.cid;
						innerElementAttributes.from    = 'fusion_builder_row_inner';

						FusionPageBuilderApp.collection.add( innerElementAttributes );
					} );

				} );

				if ( ! parentCID ) {

					// Save history state
					fusionHistoryManager.turnOnTracking();
					window.fusionHistoryState = fusionBuilderText.cloned_nested_columns;

					FusionPageBuilderEvents.trigger( 'fusion-element-cloned' );
				}

				// Check for globals.
				if ( innerRowAttributes.parent ) {
					setTimeout( FusionPageBuilderApp.checkGlobalParents, 500, innerRowAttributes.parent );
				}

			},

			saveElementDialog: function( event ) {
				if ( event ) {
					event.preventDefault();
				}
				FusionPageBuilderApp.showLibrary();

				$( '#fusion-builder-layouts-elements-trigger' ).click();

				$( '#fusion-builder-layouts-elements .fusion-builder-layouts-header-element-fields' ).append( '<div class="fusion-save-element-fields"><input type="text" value="" id="fusion-builder-save-element-input" class="fusion-builder-save-element-input" placeholder="' + fusionBuilderText.enter_name + '" /><div class="save-as-global"><label><input type="checkbox" id="fusion_save_global" name="fusion_save_global">' + fusionBuilderText.save_global + '</label><a href="#" class="fusion-builder-save-column fusion-builder-element-button-save" data-element-cid="' + this.model.get( 'cid' ) + '">' + fusionBuilderText.save_element + '</a></div></div>' );

			},

			saveElement: function( event ) {

				var elementContent   = this.getInnerRowContent(),
				    $mainContainer   = $( '#fusion_builder_main_container' ),
				    elementName      = $( '#fusion-builder-save-element-input' ).val(),
				    saveGlobal       = $( '#fusion_save_global' ).is( ':checked' ),
				    layoutsContainer = $( '#fusion-builder-layouts-elements .fusion-page-layouts' ),
				    emptyMessage     = $( '#fusion-builder-layouts-elements .fusion-page-layouts .fusion-empty-library-message' ),
				    thisModel        = this.model,
				    oldGLobalID      = null,
				    params           = {};

				if ( event ) {
					event.preventDefault();
				}

				if ( 'undefined' !== typeof ( this.model.attributes.params ) && 'undefined' !== typeof ( this.model.attributes.params.fusion_global ) &&  0 < $mainContainer.find( '[fusion-global-layout="' + this.model.attributes.params.fusion_global + '"]' ).length ) {

					// Make a copy.
					oldGLobalID     = this.model.attributes.params.fusion_global;
					params          = this.model.get( 'params' );

					// Remove temporarily and update model
					delete params.fusion_global;
					this.model.set( 'params', params );

					// Get content.
					elementContent   = this.getInnerRowContent();

					// Add it back.
					params.fusion_global = oldGLobalID;
					this.model.set( 'params', params );
				}

				if ( '' !== elementName ) {
					$.ajax( {
						type: 'POST',
						url: FusionPageBuilderApp.ajaxurl,
						dataType: 'json',
						data: {
							action: 'fusion_builder_save_layout',
							fusion_load_nonce: FusionPageBuilderApp.fusion_load_nonce,
							fusion_layout_name: elementName,
							fusion_save_global: saveGlobal,
							fusion_layout_content: elementContent,
							fusion_layout_post_type: 'fusion_element',
							fusion_layout_new_cat: 'elements',
							fusion_layout_element_type: 'nested'
						},
						complete: function( data ) {
							layoutsContainer.prepend( data.responseText );
							$( '.fusion-save-element-fields' ).remove();
							emptyMessage.hide();

							// If global, make it.
							if ( saveGlobal ) {
								thisModel.attributes.params = { fusion_global: $( data.responseText ).attr( 'data-layout_id' ) };
								$( 'div.fusion_builder_column_element[data-cid="' + thisModel.get( 'cid' ) + '"]' ).addClass( 'fusion-global-element' );
								$( 'div.fusion_builder_column_element[data-cid="' + thisModel.get( 'cid' ) + '"]' ).attr( 'fusion-global-layout', $( data.responseText ).attr( 'data-layout_id' )  );
								$( 'div.fusion_builder_column_element[data-cid="' + thisModel.get( 'cid' ) + '"]' ).append( '<div class="fusion-builder-global-tooltip"><span>' + fusionBuilderText.global_column + '</span></div>' );
								FusionPageBuilderEvents.trigger( 'fusion-element-added' );
								FusionPageBuilderApp.saveGlobal = true;

								// Check for globals.
								setTimeout( FusionPageBuilderApp.checkGlobalParents, 500, thisModel.get( 'parent' ) );
							}
						}
					} );

				} else {
					alert( fusionBuilderText.please_enter_element_name );
				}
			},

			getInnerRowContent: function() {
				var shortcode       = '',
				    $thisRowInner   = this.$el,
				    thisRowInnerCID = $thisRowInner.data( 'cid' ),
				    module          = FusionPageBuilderElements.findWhere( { cid: thisRowInnerCID } ); // jshint ignore:line

				shortcode += '[fusion_builder_row_inner]';

				// Find nested columns in this row
				$thisRowInner.find( '.fusion-builder-column-inner' ).each( function() {
					var $thisColumnInner = $( this ),
					    columnInnerCID   = $thisColumnInner.data( 'cid' ),
					    module           = FusionPageBuilderElements.findWhere( { cid: columnInnerCID } ),
					    columnParams     = {},
					    columnAttributesCheck;

					_.each( module.get( 'params' ), function( value, name ) {

						if ( 'undefined' === value ) {
							columnParams[name] = '';
						} else {
							columnParams[name] = value;
						}

					} );

					// Legacy support for new column options
					columnAttributesCheck = {
						min_height: '',
						last: 'no',
						hover_type: 'none',
						link: '',
						border_position: 'all'
					};

					_.each( columnAttributesCheck, function( value, name ) {

						if ( 'undefined' === typeof columnParams[ name ] ) {
							columnParams[name] = value;
						}

					} );

					// Build column shortcdoe
					shortcode += '[fusion_builder_column_inner type="' + module.get( 'layout' ) + '" background_position="' + columnParams.background_position + '" background_color="' + columnParams.background_color + '" border_size="' + columnParams.border_size + '" border_color="' + columnParams.border_color + '" border_style="' + columnParams.border_style + '" spacing="' + columnParams.spacing + '" background_image="' + columnParams.background_image + '" background_repeat="' + columnParams.background_repeat + '" padding="' + columnParams.padding + '" margin_top="' + columnParams.margin_top + '" margin_bottom="' + columnParams.margin_bottom + '" class="' + columnParams['class'] + '" id="' + columnParams.id + '" animation_type="' + columnParams.animation_type + '" animation_speed="' + columnParams.animation_speed + '" animation_direction="' + columnParams.animation_direction + '" hide_on_mobile="' + columnParams.hide_on_mobile + '" center_content="' + columnParams.center_content + '" last="' + columnParams.last + '" min_height="' + columnParams.min_height + '" hover_type="' + columnParams.hover_type + '" link="' + columnParams.link + '"]';

						// Find elements in this column
						$thisColumnInner.find( '.fusion_module_block' ).each( function() {
							shortcode += FusionPageBuilderApp.generateElementShortcode( $( this ), false );
						} );

					shortcode += '[/fusion_builder_column_inner]';

				} );

				shortcode += '[/fusion_builder_row_inner]';

				return shortcode;
			},

			sortableColumns: function() {
				var thisEl     = this,
				    selectedEl = thisEl.$el.find( '.fusion-builder-row-container-inner' );

				selectedEl.sortable( {
					items: '.fusion-builder-column-inner',
					helper: 'clone',
					cancel: '.fusion-builder-settings, .fusion-builder-clone, .fusion-builder-remove, .fusion-builder-section-add, .fusion-builder-add-element, .fusion-builder-insert-column, #fusion_builder_controls, .fusion-builder-save-column, .fusion-builder-resize-column, .column-sizes, .fusion-builder-save-column-dialog',
					tolerance: 'pointer',

					update: function( event, ui ) {
						var moduleCID = ui.item.data( 'cid' ),
						    model     = thisEl.collection.find( function( model ) {
								return model.get( 'cid' ) === moduleCID;
						    } );

						// Moved the column within the same row
						if ( model.get( 'parent' ) === thisEl.model.attributes.cid && $( ui.item ).closest( event.target ).length ) {

						// Moved the column to a different row
						} else {
							model.set( 'parent', thisEl.model.attributes.cid );
						}

						// Save history state
						fusionHistoryManager.turnOnTracking();
						window.fusionHistoryState = fusionBuilderText.moved_nested_column;

						FusionPageBuilderEvents.trigger( 'fusion-element-sorted' );
					}

				} ).disableSelection();
			},

			removeRow: function( event, force ) { // jshint ignore:line

				var columns,
				    parentCID;

				if ( event ) {
					event.preventDefault();
				}

				parentCID = this.model.get( 'parent' );

				columns = FusionPageBuilderViewManager.getChildViews( this.model.get( 'cid' ) );

				// Remove columns
				_.each( columns, function( column ) {
					column.removeColumn();
				} );

				FusionPageBuilderViewManager.removeView( this.model.get( 'cid' ) );

				this.model.destroy();

				this.remove();

				// If row ( nested columns ) is removed manually
				if ( event ) {

					// Save history state
					fusionHistoryManager.turnOnTracking();
					window.fusionHistoryState = fusionBuilderText.deleted_nested_columns;

					FusionPageBuilderEvents.trigger( 'fusion-element-removed' );
				}

				// Check for globals.
				setTimeout( FusionPageBuilderApp.checkGlobalParents, 500, parentCID );
			}
		} );
	} );
} )( jQuery );
