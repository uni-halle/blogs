/* global FusionPageBuilderEvents, FusionPageBuilderViewManager, fusionAllElements, FusionPageBuilderApp, fusionHistoryManager, fusionBuilderText */
var FusionPageBuilder = FusionPageBuilder || {};

( function( $ ) {

	$( document ).ready( function() {

		// Builder Elements View
		FusionPageBuilder.NestedColumnLibraryView = window.wp.Backbone.View.extend( {

			className: 'fusion-builder-modal-settings-container',

			template: FusionPageBuilder.template( $( '#fusion-builder-nested-column-library-template' ).html() ),

			events: {
				'click .fusion-builder-all-modules .fusion-builder-element': 'addModule',
				'click .fusion_builder_custom_elements_load': 'addCustomModule',
				'click .fusion-builder-column-layouts li': 'addNestedColumns',
				'click .fusion-builder-modal-close': 'closeModal'
			},

			initialize: function( attributes ) {
				this.options = attributes;
				this.listenTo( FusionPageBuilderEvents, 'fusion-modal-view-removed', this.remove );
			},

			render: function() {
				this.$el.html( this.template( FusionPageBuilderViewManager.toJSON() ) );

				this.$el.addClass( 'fusion-add-to-nested' );

				return this;
			},

			addNestedColumns: function( event ) {
				var moduleID,
				    that,
				    $layoutEl,
				    layout,
				    layoutElementsNum,
				    thisView,
				    defaultParams,
				    params,
				    value,
				    appendAfter;

				if ( event ) {
					event.preventDefault();
				}

				moduleID = FusionPageBuilderViewManager.generateCid();
				appendAfter = ( this.$el ).parents( '.fusion-builder-row-content' ).find( '.fusion-builder-row-container-inner' );

				that              = this;
				$layoutEl         = $( event.target ).is( 'li' ) ? $( event.target ) : $( event.target ).closest( 'li' );
				layout            = $layoutEl.data( 'layout' ).split( ',' );
				layoutElementsNum = _.size( layout );
				thisView          = this.options.view;

				// Get default options
				defaultParams = fusionAllElements.fusion_builder_column_inner.params;
				params = {};

				// Process default parameters from shortcode
				_.each( defaultParams, function( param )  {
					if ( _.isObject( param.value ) ) {
						value = param['default'];
					} else {
						value = param.value;
					}
					params[param.param_name] = value;
				} );

				_.each( layout, function( element, index ) { // jshint ignore:line
					var columnAttributes = {
							type: 'fusion_builder_column_inner',
							element_type: 'fusion_builder_column_inner',
							cid: FusionPageBuilderViewManager.generateCid(),
							parent: FusionPageBuilderApp.parentRowId,
							layout: element,
							view: thisView,
							params: params,
							appendAfter: appendAfter
					    };

					that.collection.add( [ columnAttributes ] );

				} );

				this.remove();

				FusionPageBuilderEvents.trigger( 'fusion-columns-added' );

				if ( event ) {

					// Save history state
					fusionHistoryManager.turnOnTracking();
					window.fusionHistoryState = fusionBuilderText.added_nested_columns;

					FusionPageBuilderEvents.trigger( 'fusion-element-cloned' );
				}
			},

			closeModal: function( event ) {
				event.preventDefault();

				this.remove();
			}
		} );
	} );
} )( jQuery );
