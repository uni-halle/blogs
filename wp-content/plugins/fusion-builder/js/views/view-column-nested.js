/* global fusionHistoryManager, fusionBuilderText, fusionAllElements, FusionPageBuilderEvents, FusionPageBuilderViewManager, FusionPageBuilderApp, FusionPageBuilderElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function( $ ) {

	$( document ).ready( function() {

		// Nested Column View
		FusionPageBuilder.NestedColumnView = window.wp.Backbone.View.extend( {

			template: FusionPageBuilder.template( $( '#fusion-builder-inner-column-template' ).html() ),

			events: {
				'click .fusion-builder-add-element': 'addModule',
				'click .fusion-builder-settings-column': 'showSettings',
				'click .fusion-builder-resize-inner-column': 'columnSizeDialog',
				'click .column-size': 'columnSize',
				'click .fusion-builder-remove-inner-column': 'removeColumn',
				'click .fusion-builder-clone-inner-column': 'cloneColumn'
			},

			initialize: function() {
				this.$el.attr( 'data-cid', this.model.get( 'cid' ) );
				this.$el.attr( 'data-column-size', this.model.get( 'layout' ) );
			},

			render: function() {
				this.$el.html( this.template( this.model.toJSON() ) );
				this.sortableElements();

				return this;
			},

			sortableElements: function() {
				var thisEl = this;
				this.$el.sortable( {
					items: '.fusion_module_block',
					connectWith: '.fusion-builder-column-inner',
					cancel: '.fusion-builder-settings, .fusion-builder-clone, .fusion-builder-remove, .fusion-builder-add-element, .fusion-builder-insert-column, .fusion-builder-save-module-dialog',
					tolerance: 'pointer',

					update: function( event, ui ) {
						var $moduleBlock = $( ui.item ),
						    moduleCID    = ui.item.data( 'cid' ),
						    model        = thisEl.collection.find( function( model ) {
								return model.get( 'cid' ) === moduleCID;
						    } );

						// If column is empty add before "Add Element" button
						if ( $( ui.item ).closest( event.target ).length && 1 === $( event.target ).find( '.fusion_module_block' ).length ) {
							$moduleBlock.insertBefore( $( event.target ).find( '.fusion-builder-add-element' ) );
						}

						// Moved the element within the same column
						if ( model.get( 'parent' ) === thisEl.model.attributes.cid && $( ui.item ).closest( event.target ).length ) {

						// Moved the element to a different column
						} else {
							model.set( 'parent', thisEl.model.attributes.cid );
						}

						// Save history state
						fusionHistoryManager.turnOnTracking();
						window.fusionHistoryState = fusionBuilderText.moved + ' ' + fusionAllElements[ model.get( 'element_type' ) ].name + ' ' + fusionBuilderText.element;
						FusionPageBuilderEvents.trigger( 'fusion-element-sorted' );
					}

				} );
			},

			showSettings: function( event ) {
				var modalView,
				    viewSettings = {
						model: this.model,
						collection: this.collection,
						attributes: {
							'data-modal_view': 'element_settings'
						}
				    };

				if ( event ) {
					event.preventDefault();
				}

				modalView = new FusionPageBuilder.ModalView( viewSettings );

				$( 'body' ).append( modalView.render().el );
			},

			removeColumn: function( event ) {
				var modules,
				    parentCID;

				if ( event ) {
					event.preventDefault();
				}

				parentCID = this.model.get( 'parent' );

				modules = FusionPageBuilderViewManager.getChildViews( this.model.get( 'cid' ) );

				_.each( modules, function( module ) {
					module.removeElement();
				} );

				FusionPageBuilderViewManager.removeView( this.model.get( 'cid' ) );

				this.model.destroy();

				this.remove();

				// If the column is deleted manually
				if ( event ) {
					FusionPageBuilderEvents.trigger( 'fusion-element-removed' );
				}

				// Check for globals.
				setTimeout( FusionPageBuilderApp.checkGlobalParents, 500, parentCID );
			},

			cloneColumn: function( event ) {
				var columnAttributes = $.extend( true, {}, this.model.attributes ),
				    $thisColumn;

				if ( event ) {
					event.preventDefault();
				}

				columnAttributes.created       = 'manually';
				columnAttributes.cid           = FusionPageBuilderViewManager.generateCid();
				columnAttributes.targetElement = this.$el;
				columnAttributes.cloned        = true;

				FusionPageBuilderApp.collection.add( columnAttributes );

				// Parse column elements
				$thisColumn = this.$el;
				$thisColumn.find( '.fusion_builder_column_element' ).each( function() {
					var $thisModule,
					    moduleCID,
					    module,
					    elementAttributes;

					// Standard element
					if ( $( this ).hasClass( 'fusion_module_block' ) ) {
						$thisModule = $( this );
						moduleCID = 'undefined' === typeof $thisModule.data( 'cid' ) ? $thisModule.find( '.fusion-builder-data-cid' ).data( 'cid' ) : $thisModule.data( 'cid' );

						// Get model from collection by cid
						module = FusionPageBuilderElements.find( function( model ) {
							return model.get( 'cid' ) === moduleCID;
						} );

						// Clone model attritubes
						elementAttributes         = $.extend( true, {}, module.attributes );

						elementAttributes.created = 'manually';
						elementAttributes.cid     = FusionPageBuilderViewManager.generateCid();
						elementAttributes.parent  = columnAttributes.cid;
						elementAttributes.from    = 'fusion_builder_column';

						FusionPageBuilderApp.collection.add( elementAttributes );
					}
				} );

				// If column is cloned manually
				if ( event ) {

					// Save history state
					fusionHistoryManager.turnOnTracking();
					window.fusionHistoryState = fusionBuilderText.cloned_nested_columns;

					FusionPageBuilderEvents.trigger( 'fusion-element-cloned' );
				}
			},

			addModule: function( event ) {
				var view,
				    $eventTarget,
				    $addModuleButton;

				if ( event ) {
					event.preventDefault();
					event.stopPropagation();
				}

				FusionPageBuilderApp.innerColumn = 'true';
				FusionPageBuilderApp.parentColumnId = this.model.get( 'cid' );

				$eventTarget     = $( event.target );
				$addModuleButton = $eventTarget.is( 'span' ) ? $eventTarget.parent( '.fusion-builder-add-element' ) : $eventTarget;

				if ( ! $addModuleButton.parent().is( event.delegateTarget ) ) {
					return;
				}

				view = new FusionPageBuilder.ModalView( {
					model: this.model,
					collection: this.collection,
					attributes: {
						'data-modal_view': 'element_library'
					},
					view: this
				} );

				$( 'body' ).append( view.render().el );
			},

			columnSizeDialog: function( event ) {
				var leftPosition = this.$el.position().left,
				    modelWidth = this.$el.parents( '.fusion-builder-row-container-inner' ).width(),
				    columnSizeWidth = 240;

				if ( event ) {
					event.preventDefault();
				}

				if ( modelWidth - leftPosition < columnSizeWidth ) {
					this.$el.find( '.column-sizes' ).css( { left: 'auto', right: '0' } );
				}
				this.$el.find( '.column-sizes' ).toggle();
			},

			columnSize: function( event ) {
				var $thisEl = $( event.currentTarget ),

				    // Get current column size
				    size = this.model.get( 'layout' ),

				    // New column size
				    newSize = $thisEl.attr( 'data-column-size' ),

				    // Fraction size
				    fractionSize = '';

				if ( event ) {
					event.preventDefault();
				}

				if ( 'undefined' !== typeof ( newSize ) ) {

					// Set new size
					this.model.set( 'layout', newSize );
					this.$el.attr( 'data-column-size', newSize );

					// Change css size class
					this.$el.removeClass( 'fusion-builder-column-' + size );
					this.$el.addClass( 'fusion-builder-column-' + newSize );

					fractionSize = newSize.replace( '_', '/' );

					this.$el.find( '.fusion-builder-resize-inner-column' ).text( fractionSize );
					this.$el.find( '.column-sizes' ).hide();
					this.$el.find( '.column-sizes .column-size' ).removeClass( 'active-size' );
					this.$el.find( '.column-size-' + newSize ).addClass( 'active-size' );

					// Save history state
					fusionHistoryManager.turnOnTracking();
					window.fusionHistoryState = fusionBuilderText.resized_column + ' ' + fractionSize;

					FusionPageBuilderEvents.trigger( 'fusion-element-edited' );

				}
			}
		} );
	} );
} )( jQuery );
