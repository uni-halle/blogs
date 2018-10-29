/*! elementor-pro - v2.1.6 - 28-08-2018 */
(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
var EditorModule = function() {
	var self = this;

	this.init = function() {
		jQuery( window ).on( 'elementor:init', this.onElementorReady.bind( this ) );
	};

	this.getView = function( name ) {
		var editor = elementor.getPanelView().getCurrentPageView();
		return editor.children.findByModelCid( this.getControl( name ).cid );
	};

	this.getControl = function( name ) {
		var editor = elementor.getPanelView().getCurrentPageView();
		return editor.collection.findWhere( { name: name } );
	};

	this.onElementorReady = function() {
		self.onElementorInit();

		elementor.on( 'frontend:init', function() {
			self.onElementorFrontendInit();
		} );

		elementor.on( 'preview:loaded', function() {
			self.onElementorPreviewLoaded();
		} );
	};

	this.init();
};

EditorModule.prototype.onElementorInit = function() {};

EditorModule.prototype.onElementorPreviewLoaded = function() {};

EditorModule.prototype.onElementorFrontendInit = function() {};

EditorModule.extend = Backbone.View.extend;

module.exports = EditorModule;

},{}],2:[function(require,module,exports){
var ElementorPro = Marionette.Application.extend( {
	config: {},

	modules: {},

	initModules: function() {
		var QueryControl = require( 'modules/query-control/assets/js/editor' ),
			Forms = require( 'modules/forms/assets/js/editor' ),
			Library = require( 'modules/library/assets/js/editor' ),
			CustomCSS = require( 'modules/custom-css/assets/js/editor' ),
			GlobalWidget = require( 'modules/global-widget/assets/js/editor/editor' ),
			FlipBox = require( 'modules/flip-box/assets/js/editor/editor' ),
			ShareButtons = require( 'modules/share-buttons/assets/js/editor/editor' ),
			AssetsManager = require( 'modules/assets-manager/assets/js/editor/editor' ),
			ThemeElements =  require( 'modules/theme-elements/assets/js/editor/editor' ),
			ThemeBuilder =  require( 'modules/theme-builder/assets/js/editor/editor' );

		this.modules = {
			queryControl: new QueryControl(),
			forms: new Forms(),
			library: new Library(),
			customCSS: new CustomCSS(),
			globalWidget: new GlobalWidget(),
			flipBox: new FlipBox(),
			shareButtons: new ShareButtons(),
			assetsManager: new AssetsManager(),
			themeElements: new ThemeElements(),
			themeBuilder: new ThemeBuilder()
		};
	},

	ajax: {
		send: function() {
			var args = arguments;

			args[0] = 'pro_' + args[0];

			return elementor.ajax.send.apply( elementor.ajax, args );
		}
	},

	translate: function( stringKey, templateArgs ) {
		return elementor.translate( stringKey, templateArgs, this.config.i18n );
	},

	onStart: function() {
		this.config = ElementorProConfig;

		this.initModules();

		jQuery( window ).on( 'elementor:init', this.onElementorInit );
	},

	onElementorInit: function() {
		elementorPro.libraryRemoveGetProButtons();

		elementor.debug.addURLToWatch( 'elementor-pro/assets' );
	},

	libraryRemoveGetProButtons: function() {
		elementor.hooks.addFilter( 'elementor/editor/template-library/template/action-button', function( viewID, templateData ) {
			return templateData.isPro && ! elementorPro.config.isActive ? '#tmpl-elementor-pro-template-library-activate-license-button' : '#tmpl-elementor-template-library-insert-button';
		} );
	}
} );

window.elementorPro = new ElementorPro();

elementorPro.start();

},{"modules/assets-manager/assets/js/editor/editor":5,"modules/custom-css/assets/js/editor":7,"modules/flip-box/assets/js/editor/editor":9,"modules/forms/assets/js/editor":10,"modules/global-widget/assets/js/editor/editor":25,"modules/library/assets/js/editor":31,"modules/query-control/assets/js/editor":33,"modules/share-buttons/assets/js/editor/editor":35,"modules/theme-builder/assets/js/editor/editor":39,"modules/theme-elements/assets/js/editor/editor":43}],3:[function(require,module,exports){
module.exports = elementor.modules.Module.extend( {
	elementType: null,

	__construct: function( elementType ) {
		this.elementType = elementType;

		this.addEditorListener();
	},

	addEditorListener: function() {
		var self = this;

		if ( self.onElementChange ) {
			var eventName = 'change';

			if ( 'global' !== self.elementType ) {
				eventName += ':' + self.elementType;
			}

			elementor.channels.editor.on( eventName, function( controlView, elementView ) {
				self.onElementChange( controlView.model.get( 'name' ),  controlView, elementView );
			} );
		}
	},

	getView: function( name ) {
		var editor = elementor.getPanelView().getCurrentPageView();
		return editor.children.findByModelCid( this.getControl( name ).cid );
	},

	getControl: function( name ) {
		var editor = elementor.getPanelView().getCurrentPageView();
		return editor.collection.findWhere( { name: name } );
	},

	addControlSpinner: function( name ) {
		this.getView( name ).$el.find( ':input' ).attr( 'disabled', true );
		this.getView( name ).$el.find( '.elementor-control-title' ).after( '<span class="elementor-control-spinner"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>' );
	},

	removeControlSpinner: function( name ) {
		this.getView( name ).$el.find( ':input' ).attr( 'disabled', false );
		this.getView( name ).$el.find( 'elementor-control-spinner' ).remove();
	},

	addSectionListener: function( section, callback ) {
		var self = this;

		elementor.channels.editor.on( 'section:activated', function( sectionName, editor ) {
			var model = editor.getOption( 'editedElementView' ).getEditModel(),
				currentElementType = model.get( 'elType' ),
				_arguments = arguments;

			if ( 'widget' === currentElementType ) {
				currentElementType = model.get( 'widgetType' );
			}

			if ( self.elementType === currentElementType && section === sectionName ) {
				setTimeout( function() {
					callback.apply( self, _arguments );
				}, 10 );
			}
		} );
	}
} );

},{}],4:[function(require,module,exports){
module.exports = elementor.modules.views.ControlsStack.extend( {
	activeTab: 'content',

	activeSection: 'settings',

	initialize: function() {
		this.collection = new Backbone.Collection( _.values( this.options.controls ) );
	},

	filter: function( model ) {
		if ( 'section' === model.get( 'type' ) ) {
			return true;
		}

		var section = model.get( 'section' );

		return ! section || section === this.activeSection;
	},

	childViewOptions: function() {
		return {
			elementSettingsModel: this.model
		};
	}
} );

},{}],5:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorInit: function() {
		var FontsManager = require( './font-manager' );

		this.assets = {
			font: new FontsManager()
		};
	}
} );

},{"./font-manager":6,"elementor-pro/editor/editor-module":1}],6:[function(require,module,exports){
module.exports =  elementor.modules.Module.extend( {

	_enqueuedFonts: [],
	_enqueuedTypekit: false,

	onFontChange: function( fontType, font ) {
		if ( 'custom' !== fontType && 'typekit' !== fontType ) {
			return;
		}

		if ( -1 !== this._enqueuedFonts.indexOf( font ) ) {
			return;
		}

		if ( 'typekit' === fontType && this._enqueuedTypekit ) {
			return;
		}

		this.getCustomFont( fontType, font );
	},

	getCustomFont: function( fontType, font ) {
		elementorPro.ajax.send( 'assets_manager_panel_action_data', {
			data: {
				service: 'font',
				type: fontType,
				font: font
			},
			success: function( data ) {
				if ( data.font_face ) {
					elementor.$previewContents.find( 'style:last' ).after( '<style type="text/css">' + data.font_face + '</style>' );
				}

				if ( data.font_url ) {
					elementor.$previewContents.find( 'link:last' ).after( '<link href="' + data.font_url + '" rel="stylesheet" type="text/css">' );
				}
			}
		} );

		this._enqueuedFonts.push( font );
		if ( 'typekit' === fontType ) {
			this._enqueuedTypekit = true;
		}
	},

	onInit: function() {
		elementor.channels.editor.on( 'font:insertion', this.onFontChange.bind( this ) );
	}
} );

},{}],7:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorInit: function() {
		var CustomCss = require( './editor/custom-css' );
		this.customCss = new CustomCss();
	}
} );

},{"./editor/custom-css":8,"elementor-pro/editor/editor-module":1}],8:[function(require,module,exports){
module.exports = function() {
	var self = this;

	self.init = function() {
		elementor.hooks.addFilter( 'editor/style/styleText', self.addCustomCss );

		elementor.settings.page.model.on( 'change', self.addPageCustomCss );

		elementor.on( 'preview:loaded', self.addPageCustomCss );
	};

	self.addPageCustomCss = function() {
		var customCSS = elementor.settings.page.model.get( 'custom_css' );

		if ( customCSS ) {
			customCSS = customCSS.replace( /selector/g, '.elementor-page-' + elementor.config.document.id );
			elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append( customCSS );
		}
	};

	self.addCustomCss = function( css, view ) {
		var model = view.getEditModel(),
			customCSS = model.get( 'settings' ).get( 'custom_css' );

		if ( customCSS ) {
			css += customCSS.replace( /selector/g, '.elementor-element.elementor-element-' + view.model.id );
		}

		return css;
	};

	self.init();
};

},{}],9:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorInit: function() {
		elementor.channels.editor.on( 'section:activated', this.onSectionActivated );
	},

	onSectionActivated: function( sectionName, editor ) {
		var editedElement = editor.getOption( 'editedElementView' );

		if ( 'flip-box' !== editedElement.model.get( 'widgetType' ) ) {
			return;
		}

		var isSideBSection = -1 !== [ 'section_side_b_content', 'section_style_b' ].indexOf( sectionName );

		editedElement.$el.toggleClass( 'elementor-flip-box--flipped', isSideBSection );

		var $backLayer = editedElement.$el.find( '.elementor-flip-box__back' );

		if ( isSideBSection ) {
            $backLayer.css( 'transition', 'none' );
		}

		if ( ! isSideBSection ) {
			setTimeout( function() {
				$backLayer.css( 'transition', '' );
			}, 10 );
		}
	}
} );

},{"elementor-pro/editor/editor-module":1}],10:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorInit: function() {
		var ReplyToField = require( './editor/reply-to-field' ),
			Mailchimp = require( './editor/mailchimp' ),
			Recaptcha = require( './editor/recaptcha' ),
			Shortcode = require( './editor/shortcode' ),
			Drip = require( './editor/drip' ),
			ActiveCampaign = require( './editor/activecampaign' ),
			GetResponse = require( './editor/getresponse' ),
			ConvertKit = require( './editor/convertkit' );

		this.replyToField = new ReplyToField();
		this.mailchimp = new Mailchimp( 'form' );
		this.shortcode = new Shortcode( 'form' );
		this.recaptcha = new Recaptcha( 'form' );
		this.drip = new Drip( 'form' );
		this.activecampaign = new ActiveCampaign( 'form' );
		this.getresponse = new GetResponse( 'form' );
		this.convertkit = new ConvertKit( 'form' );

		// Form fields
		var TimeField = require( './editor/fields/time' ),
			DateField = require( './editor/fields/date' ),
			AcceptanceField = require( './editor/fields/acceptance' ),
			UploadField = require( './editor/fields/upload' ),
			TelField = require( './editor/fields/tel' );

		this.Fields = {
			time: new TimeField( 'form' ),
			date: new DateField( 'form' ),
			tel: new TelField( 'form' ),
			acceptance: new AcceptanceField( 'form' ),
			upload: new UploadField( 'form' )
		};

		elementor.addControlView( 'Fields_map', require( './editor/fields-map-control' ) );
	}
} );

},{"./editor/activecampaign":11,"./editor/convertkit":12,"./editor/drip":13,"./editor/fields-map-control":14,"./editor/fields/acceptance":15,"./editor/fields/date":16,"./editor/fields/tel":17,"./editor/fields/time":18,"./editor/fields/upload":19,"./editor/getresponse":20,"./editor/mailchimp":21,"./editor/recaptcha":22,"./editor/reply-to-field":23,"./editor/shortcode":24,"elementor-pro/editor/editor-module":1}],11:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {
	cache: {},
	fields: {},

	onElementChange: function( setting ) {
		switch ( setting ) {
			case 'activecampaign_api_credentials_source':
			case 'activecampaign_api_key':
			case 'activecampaign_api_url':
				this.onActiveCampaignApiUpdate();
				break;
			case 'activecampaign_list':
				this.onListUpdate();
				break;
		}
	},

	onActiveCampaignApiUpdate: function() {
		var self = this,
			apikeyControlView = self.getView( 'activecampaign_api_key' ),
			apiUrlControlView = self.getView( 'activecampaign_api_url' ),
			apiCredControlView = self.getView( 'activecampaign_api_credentials_source' );

		if ( 'default' !== apiCredControlView.getControlValue() && ( '' === apikeyControlView.getControlValue() || '' === apiUrlControlView.getControlValue() ) ) {
			self.updateOptions( 'activecampaign_list', [] );
			self.getView( 'activecampaign_list' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'activecampaign_list' );

		self.getActiveCampaignCache( 'lists', 'activecampaign_list', apiCredControlView.getControlValue() ).done( function( json ) {
			self.updateOptions( 'activecampaign_list', json.data.lists );
			self.fields = json.data.fields;
		} );
	},

	onListUpdate: function() {
		this.updateFieldsMapping();
	},

	updateFieldsMapping: function() {
		var controlView = this.getView( 'activecampaign_list' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		var remoteFields = [
			{
				remote_label: elementor.translate( 'Email' ),
				remote_type: 'email',
				remote_id: 'email',
				remote_required: true
			},
			{
				remote_label: elementor.translate( 'First Name' ),
				remote_type: 'text',
				remote_id: 'first_name',
				remote_required: false
			},
			{
				remote_label: elementor.translate( 'Last Name' ),
				remote_type: 'text',
				remote_id: 'last_name',
				remote_required: false
			},
			{
				remote_label: elementor.translate( 'Phone' ),
				remote_type: 'text',
				remote_id: 'phone',
				remote_required: false
			},
			{
				remote_label: elementor.translate( 'Organization name' ),
				remote_type: 'text',
				remote_id: 'orgname',
				remote_required: false
			}
		];

		for ( var field in this.fields ) {
			if ( this.fields.hasOwnProperty( field ) ) {
				remoteFields.push( this.fields[ field ] );
			}
		}

		this.getView( 'activecampaign_fields_map' ).updateMap( remoteFields );
	},

	getActiveCampaignCache: function( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type][ cacheKey ];
			return jQuery.Deferred().resolve( {
				data: data
			} );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'activecampaign',
			activecampaign_action: action,
			api_key: this.getView( 'activecampaign_api_key' ).getControlValue(),
			api_url: this.getView( 'activecampaign_api_url' ).getControlValue(),
			api_cred: this.getView( 'activecampaign_api_credentials_source' ).getControlValue()
		} );

		var self = this;

		return elementorPro.ajax.send( 'forms_panel_action_data', {
			data: requestArgs,
			success: function( data ) {
				self.cache[ type ] = _.extend( {}, self.cache[ type ] );
				self.cache[ type ][ cacheKey ] = data[ type ];
			}
		} );
	},

	updateOptions: function( name, options ) {
		if ( this.getView( name ) ) {
			this.getControl( name ).set( 'options', options );
			this.getView( name ).render();
		}
	},

	onSectionActive: function() {
		this.onActiveCampaignApiUpdate();
	},

	onInit: function() {
		this.addSectionListener( 'section_activecampaign', this.onSectionActive );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],12:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {
	cache: {},

	onElementChange: function( setting ) {
		switch ( setting ) {
			case 'convertkit_api_key_source':
			case 'convertkit_custom_api_key':
				this.onConvertKitApiUpdate();
				break;
			case 'convertkit_form':
				this.onListUpdate();
				break;
		}
	},

	onConvertKitApiUpdate: function() {
		var self = this,
			apiKeyControlView = self.getView( 'convertkit_api_key_source' ),
			customApikeyControlView = self.getView( 'convertkit_custom_api_key' );

		if ( 'default' !== apiKeyControlView.getControlValue() && '' === customApikeyControlView.getControlValue() ) {
			self.updateOptions( 'convertkit_form', [] );
			self.getView( 'convertkit_form' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'convertkit_form' );

		self.getConvertKitCache( 'data', 'convertkit_get_forms', apiKeyControlView.getControlValue() ).done( function( json ) {
			self.updateOptions( 'convertkit_form', json.data.data.forms );
			self.updateOptions( 'convertkit_tags', json.data.data.tags );
		} );
	},

	onListUpdate: function() {
		this.updateFieldsMapping();
	},

	updateFieldsMapping: function() {
		var controlView = this.getView( 'convertkit_form' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		var remoteFields = [
			{
				remote_label: elementor.translate( 'Email' ),
				remote_type: 'email',
				remote_id: 'email',
				remote_required: true
			},
			{
				remote_label: elementor.translate( 'First Name' ),
				remote_type: 'text',
				remote_id: 'first_name',
				remote_required: false
			}
		];
		this.getView( 'convertkit_fields_map' ).updateMap( remoteFields );
	},

	getConvertKitCache: function( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type][ cacheKey ];
			return jQuery.Deferred().resolve( {
				data: data
			} );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'convertkit',
			convertkit_action: action,
			api_key: this.getView( 'convertkit_api_key_source' ).getControlValue(),
			custom_api_key: this.getView( 'convertkit_custom_api_key' ).getControlValue()
		} );

		var self = this;

		return elementorPro.ajax.send( 'forms_panel_action_data', {
			data: requestArgs,
			success: function( data ) {
				self.cache[ type ] = _.extend( {}, self.cache[ type ] );
				self.cache[ type ][ cacheKey ] = data[ type ];
			}
		} );
	},

	updateOptions: function( name, options ) {
		if ( this.getView( name ) ) {
			this.getControl( name ).set( 'options', options );
			this.getView( name ).render();
		}
	},

	onSectionActive: function() {
		this.onConvertKitApiUpdate();
	},

	onInit: function() {
		this.addSectionListener( 'section_convertkit', this.onSectionActive );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],13:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {
	cache: {},

	onElementChange: function( setting ) {
		switch ( setting ) {
			case 'drip_api_token_source':
			case 'drip_custom_api_token':
				this.onDripApiTokenUpdate();
				break;
			case 'drip_account':
				this.onDripAccountsUpdate();
				break;
		}
	},

	onDripApiTokenUpdate: function() {
		var self = this,
			controlView = self.getView( 'drip_api_token_source' ),
			customControlView = self.getView( 'drip_custom_api_token' );

		if ( 'default' !== controlView.getControlValue() && '' === customControlView.getControlValue() ) {
			self.updateOptions( 'drip_account', [] );
			self.getView( 'drip_account' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'drip_account' );

		self.getDripCache( 'accounts', 'accounts', controlView.getControlValue() ).done( function( json ) {
			self.updateOptions( 'drip_account', json.data.accounts );
		} );
	},

	onDripAccountsUpdate: function() {
		this.updateFieldsMapping();
	},

	updateFieldsMapping: function() {
		var controlView = this.getView( 'drip_account' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		var remoteFields = {
			remote_label: elementor.translate( 'Email' ),
			remote_type: 'email',
			remote_id: 'email',
			remote_required: true
		};

		this.getView( 'drip_fields_map' ).updateMap( [remoteFields] );
	},

	getDripCache: function( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type][ cacheKey ];
			return jQuery.Deferred().resolve( {
				data: data
			} );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'drip',
			drip_action: action,
			api_token: this.getView( 'drip_api_token_source' ).getControlValue(),
			custom_api_token: this.getView( 'drip_custom_api_token' ).getControlValue()
		} );

		var self = this;

		return elementorPro.ajax.send( 'forms_panel_action_data', {
			data: requestArgs,
			success: function( data ) {
				self.cache[ type ] = _.extend( {}, self.cache[ type ] );
				self.cache[ type ][ cacheKey ] = data[ type ];
			}
		} );
	},

	updateOptions: function( name, options ) {
		if ( this.getView( name ) ) {
			this.getControl( name ).set( 'options', options );
			this.getView( name ).render();
		}
	},

	onSectionActive: function() {
		this.onDripApiTokenUpdate();
	},

	onInit: function() {
		this.addSectionListener( 'section_drip', this.onSectionActive );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],14:[function(require,module,exports){
module.exports = elementor.modules.controls.Repeater.extend( {
	onBeforeRender: function() {
		 this.$el.hide();
	},

	updateMap: function( fields ) {
		var self = this,
			savedMapObject = {};

		self.collection.each( function( model ) {
			savedMapObject[ model.get( 'remote_id' ) ] = model.get( 'local_id' );
		} );

		self.collection.reset();

		_.each( fields, function( field ) {
			var model = {
				remote_id: field.remote_id,
				remote_label: field.remote_label,
				remote_type: field.remote_type ? field.remote_type : '',
				remote_required: field.remote_required ? field.remote_required : false,
				local_id: savedMapObject[field.remote_id] ? savedMapObject[field.remote_id] : ''
			};

			self.collection.add( model );
		} );

		self.render();
	},

	onRender: function() {
		elementor.modules.controls.Base.prototype.onRender.apply( this, arguments );

		var self = this;

		self.children.each( function( view ) {
			var localFieldsControl = view.children.last(),
				options = {
					'': '- ' + elementor.translate( 'None' ) + ' -'
				},
				label = view.model.get( 'remote_label' );

			if ( view.model.get( 'remote_required' ) ) {
				label += '<span class="elementor-required">*</span>';
			}

			_.each( self.elementSettingsModel.get( 'form_fields' ).models, function( model, index ) {

				// If it's an email field, add only email fields from thr form
				var remoteType = view.model.get( 'remote_type' );

				if ( 'text' !== remoteType && remoteType !== model.get( 'field_type' ) ) {
					return;
				}

				options[ model.get( '_id' ) ] = model.get( 'field_label' ) || 'Field #' + ( index + 1 );
			} );

			localFieldsControl.model.set( 'label', label );
			localFieldsControl.model.set( 'options', options );
			localFieldsControl.render();

			view.$el.find( '.elementor-repeater-row-tools' ).hide();
			view.$el.find( '.elementor-repeater-row-controls' )
				.removeClass( 'elementor-repeater-row-controls' )
				.find( '.elementor-control' )
				.css( {
					paddingBottom: 0
				} );
		} );

		self.$el.find( '.elementor-button-wrapper' ).remove();

		if ( self.children.length ) {
			self.$el.show();
		}
	}
} );

},{}],15:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {

	renderField: function( inputField, item, i, settings ) {
		var itemClasses = _.escape( item.css_classes ),
			required = '',
			label = '',
			checked = '';

		var escAttr = function( str ) {
			var replacements  = {
				'&': '&amp;',
				'<': '&lt;',
				'>': '&gt;',
				'"': '&quot;',
				'\'': '&#x27;',
				'`': '&#x60;'
			};
			for ( var search in replacements ) {
				str = str.replace( new RegExp( search, 'g' ), replacements[ search ] );
			}
			return str;
		};

		if ( item.required ) {
			required = 'required';
		}

		if ( item.acceptance_text ) {
			label = '<label for="form_field_' + i + '">' + item.acceptance_text + '</label>';
		}

		if ( item.checked_by_default ) {
			checked = ' checked="checked"';
		}

		return '<div class="elementor-field-subgroup">' +
			'<span class="elementor-field-option">' +
			'<input size="1" type="checkbox"' + checked + ' class="elementor-acceptance-field elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' > ' + label + '</span></div>';
	},

	onInit: function() {
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/acceptance', this.renderField, 10, 4 );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],16:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {

	renderField: function( inputField, item, i, settings ) {
		var itemClasses = _.escape( item.css_classes ),
			required = '',
			min = '',
			max = '',
			placeholder = '';

		if ( item.required ) {
			required = 'required';
		}

		if ( item.min_date ) {
			min = ' min="' + item.min_date + '"';
		}

		if ( item.max_date ) {
			max = ' max="' + item.max_date + '"';
		}

		if ( item.placeholder ) {
			placeholder = ' placeholder="' + item.placeholder + '"';
		}

		if ( 'yes' === item.use_native_date ) {
			itemClasses += ' elementor-use-native';
		}

		return '<input size="1"' + min + max + placeholder + ' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" type="date" class="elementor-field-textual elementor-date-field elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' >';
	},

	onInit: function() {
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/date', this.renderField, 10, 4 );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],17:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {

	renderField: function( inputField, item, i, settings ) {
		var itemClasses = _.escape( item.css_classes ),
			required = '',
			placeholder = '';

		if ( item.required ) {
			required = 'required';
		}

		if ( item.placeholder ) {
			placeholder = ' placeholder="' + item.placeholder + '"';
		}

		itemClasses = 'elementor-field-textual ' + itemClasses;

		return '<input size="1" type="' + item.field_type + '" class="elementor-field-textual elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' ' + placeholder + ' pattern="[0-9()-]" >';
	},

	onInit: function() {
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/tel', this.renderField, 10, 4 );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],18:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {

	renderField: function( inputField, item, i, settings ) {
		var itemClasses = _.escape( item.css_classes ),
			required = '',
			placeholder = '';

		if ( item.required ) {
			required = 'required';
		}

		if ( item.placeholder ) {
			placeholder = ' placeholder="' + item.placeholder + '"';
		}

		if ( 'yes' === item.use_native_time ) {
			itemClasses += ' elementor-use-native';
		}

		return '<input size="1" type="time"' + placeholder + ' class="elementor-field-textual elementor-time-field elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' >';
	},

	onInit: function() {
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/time', this.renderField, 10, 4 );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],19:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {

	renderField: function( inputField, item, i, settings ) {
		var itemClasses = _.escape( item.css_classes ),
			required = '',
			multiple = '',
			fieldName = 'form_field_';

		if ( item.required ) {
			required = 'required';
		}
		if ( item.allow_multiple_upload ) {
			multiple = ' multiple="multiple"';
			fieldName += '[]';
		}

		return '<input size="1"  type="file" class="elementor-file-field elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="' + fieldName + '" id="form_field_' + i + '" ' + required + multiple + ' >';
	},

	onInit: function() {
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/upload', this.renderField, 10, 4 );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],20:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {
	cache: {},

	onElementChange: function( setting ) {
		switch ( setting ) {
			case 'getresponse_custom_api_key':
			case 'getresponse_api_key_source':
				this.onGetResonseApiKeyUpdate();
				break;
			case 'getresponse_list':
				this.onGetResonseListUpdate();
				break;
		}
	},

	onGetResonseApiKeyUpdate: function() {
		var self = this,
			controlView = self.getView( 'getresponse_api_key_source' ),
			customControlView = self.getView( 'getresponse_custom_api_key' );

		if ( 'default' !== controlView.getControlValue() && '' === customControlView.getControlValue() ) {
			self.updateOptions( 'getresponse_list', [] );
			self.getView( 'getresponse_list' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'getresponse_list' );

		self.getGetResonseCache( 'lists', 'lists', controlView.getControlValue() ).done( function( json ) {
			self.updateOptions( 'getresponse_list', json.data.lists );
		} );
	},

	onGetResonseListUpdate: function() {
		var self = this;
		self.updatGetResonseList();
	},

	updatGetResonseList: function() {
		var self = this,
		controlView = self.getView( 'getresponse_list' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		self.addControlSpinner( 'getresponse_fields_map' );

		self.getGetResonseCache( 'fields', 'get_fields', controlView.getControlValue(), {
			getresponse_list: controlView.getControlValue()
		} ).done( function( json ) {
			self.getView( 'getresponse_fields_map' ).updateMap( json.data.fields );
		} );
	},

	getGetResonseCache: function( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type][ cacheKey ];
			return jQuery.Deferred().resolve( {
				data: data
			} );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'getresponse',
			getresponse_action: action,
			api_key: this.getView( 'getresponse_api_key_source' ).getControlValue(),
			custom_api_key: this.getView( 'getresponse_custom_api_key' ).getControlValue()
		} );

		var self = this;

		return elementorPro.ajax.send( 'forms_panel_action_data', {
			data: requestArgs,
			success: function( data ) {
				self.cache[ type ] = _.extend( {}, self.cache[ type ] );
				self.cache[ type ][ cacheKey ] = data[ type ];
			}
		} );
	},

	updateOptions: function( name, options ) {
		if ( this.getView( name ) ) {
			this.getControl( name ).set( 'options', options );
			this.getView( name ).render();
		}
	},

	onSectionActive: function() {
		this.onGetResonseApiKeyUpdate();
		this.updatGetResonseList();
	},

	onInit: function() {
		this.addSectionListener( 'section_getresponse', this.onSectionActive );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],21:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {
	cache: {},

	onElementChange: function( setting ) {
		switch ( setting ) {
			case 'mailchimp_api_key_source':
			case 'mailchimp_api_key':
				this.onMailchimpApiKeyUpdate();
				break;
			case 'mailchimp_list':
				this.onMailchimpListUpdate();
				break;
		}
	},

	onMailchimpApiKeyUpdate: function() {
		var self = this,
			controlView = self.getView( 'mailchimp_api_key' ),
			GlobalApiKeycontrolView = self.getView( 'mailchimp_api_key_source' );

		if ( 'default' !== GlobalApiKeycontrolView.getControlValue() && '' === controlView.getControlValue() ) {
			self.updateOptions( 'mailchimp_list', [] );
			self.getView( 'mailchimp_list' ).setValue( '' );
			return;
		}

		self.addControlSpinner( 'mailchimp_list' );

		self.getMailchimpCache( 'lists', 'lists', GlobalApiKeycontrolView.getControlValue() ).done( function( json ) {
			self.updateOptions( 'mailchimp_list', json.data.lists );
		} );
	},

	onMailchimpListUpdate: function() {
		var self = this;

		self.updateOptions( 'mailchimp_groups', [] );
		self.getView( 'mailchimp_groups' ).setValue( '' );
		self.updatMailchimpList();
	},

	updatMailchimpList: function() {
		var self = this,
		controlView = self.getView( 'mailchimp_list' );

		if ( ! controlView.getControlValue() ) {
			return;
		}

		self.addControlSpinner( 'mailchimp_groups' );

		self.getMailchimpCache( 'list_details', 'list_details', controlView.getControlValue(), {
			mailchimp_list: controlView.getControlValue()
		} ).done( function( json ) {
			self.updateOptions( 'mailchimp_groups', json.data.list_details.groups );
			self.getView( 'mailchimp_fields_map' ).updateMap( json.data.list_details.fields );
		} );
	},

	getMailchimpCache: function( type, action, cacheKey, requestArgs ) {
		if ( _.has( this.cache[ type ], cacheKey ) ) {
			var data = {};
			data[ type ] = this.cache[ type][ cacheKey ];
			return jQuery.Deferred().resolve( {
				data: data
			} );
		}

		requestArgs = _.extend( {}, requestArgs, {
			service: 'mailchimp',
			mailchimp_action: action,
			api_key: this.getView( 'mailchimp_api_key' ).getControlValue(),
			use_global_api_key: this.getView( 'mailchimp_api_key_source' ).getControlValue()
		} );

		var self = this;

		return elementorPro.ajax.send( 'forms_panel_action_data', {
			data: requestArgs,
			success: function( data ) {
				self.cache[ type ] = _.extend( {}, self.cache[ type ] );
				self.cache[ type ][ cacheKey ] = data[ type ];
			}
		} );
	},

	updateOptions: function( name, options ) {
		if ( this.getView( name ) ) {
			this.getControl( name ).set( 'options', options );
			this.getView( name ).render();
		}
	},

	onSectionActive: function() {
		this.onMailchimpApiKeyUpdate();
		this.updatMailchimpList();
	},

	onInit: function() {
		this.addSectionListener( 'section_mailchimp', this.onSectionActive );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],22:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {

	renderField: function( inputField, item ) {
		var config = elementorPro.config.forms.recaptcha;
		inputField += '<div class="elementor-field">';

		if ( config.enabled ) {
			inputField += '<div class="elementor-g-recaptcha' + _.escape( item.css_classes ) + '" data-sitekey="' + config.site_key + '" data-theme="' + item.recaptcha_style + '" data-size="' + item.recaptcha_size + '"></div>';
		} else {
			inputField += '<div class="elementor-alert">' + config.setup_message + '</div>';
		}

		inputField += '</div>';

		return inputField;

	},

	filterItem: function( item ) {
		if ( 'recaptcha' === item.field_type ) {
			item.field_label = false;
		}

		return item;
	},

	onInit: function() {
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/item', this.filterItem );
		elementor.hooks.addFilter( 'elementor_pro/forms/content_template/field/recaptcha', this.renderField, 10, 2 );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],23:[function(require,module,exports){
module.exports = function() {
	var editor,
		editedModel,
		replyToControl;

	var setReplyToControl = function() {
		replyToControl = editor.collection.findWhere( { name: 'email_reply_to' } );
	};

	var getReplyToView = function() {
		return editor.children.findByModelCid( replyToControl.cid );
	};

	var refreshReplyToElement = function() {
		var replyToView = getReplyToView();

		if ( replyToView ) {
			replyToView.render();
		}
	};

	var updateReplyToOptions = function() {
		var settingsModel = editedModel.get( 'settings' ),
			emailModels = settingsModel.get( 'form_fields' ).where( { field_type: 'email' } ),
			emailFields;

		emailModels = _.reject( emailModels, { field_label: '' } );

		emailFields = _.map( emailModels, function( model ) {
			return {
				id: model.get( '_id' ),
				label: elementorPro.translate( 'x_field', [ model.get( 'field_label' ) ] )
			};
		} );

		replyToControl.set( 'options', { '': replyToControl.get( 'options' )[''] } );

		_.each( emailFields, function( emailField ) {
			replyToControl.get( 'options' )[ emailField.id ] = emailField.label;
		} );

		refreshReplyToElement();
	};

	var updateDefaultReplyTo = function( settingsModel ) {
		replyToControl.get( 'options' )[ '' ] = settingsModel.get( 'email_from' );

		refreshReplyToElement();
	};

	var onFormFieldsChange = function( changedModel ) {
		// If it's repeater field
		if ( changedModel.get( '_id' ) ) {
			if ( 'email' === changedModel.get( 'field_type' ) ) {
				updateReplyToOptions();
			}
		}

		if ( changedModel.changed.email_from ) {
			updateDefaultReplyTo( changedModel );
		}
	};

	var onPanelShow = function( panel, model ) {
		editor = panel.getCurrentPageView();

		editedModel = model;

		setReplyToControl();

		var settingsModel = editedModel.get( 'settings' );

		settingsModel.on( 'change', onFormFieldsChange );

		updateDefaultReplyTo( settingsModel );

		updateReplyToOptions();
	};

	var init = function() {
		elementor.hooks.addAction( 'panel/open_editor/widget/form', onPanelShow );
	};

	init();
};

},{}],24:[function(require,module,exports){
var ElementEditorModule = require( 'elementor-pro/editor/element-editor-module' );

module.exports = ElementEditorModule.extend( {
	getExistId: function( id ) {
		var exist = this.getView( 'form_fields' ).collection.filter( function( model ) {
			return id === model.get( '_id' );
		} );

		return exist.length > 1;
	},

	onFieldChanged: function( model, collection, eventArgs ) {
		var self = this;

		_.defer( function() {
			var view = self.getView( 'form_fields' ).children.findByModel( model );

			// Capture remove item event.
			if ( collection.changes.removed ) {
				self.getView( 'form_fields' ).children.each( self.updateShortcode );
			} else {
				self.updateId( view, eventArgs && eventArgs.add );
				self.updateShortcode( view );
			}
		} );
	},

	updateId: function( view, isNewItem ) {
		var id = view.model.get( '_id' ),
			sanitizedId = id.replace( /[^\w]/, '_' ),
			fieldIndex = 1,
			IdView = view.children.filter( function( view ) {
				return '_id' === view.model.get( 'name' );
			});

		while ( sanitizedId !== id || isNewItem || ! id || this.getExistId( id ) ) {
			if ( sanitizedId !== id ) {
				id = sanitizedId;
			} else {
				id = 'field_' + fieldIndex;
				sanitizedId = id;
			}

			view.model.attributes._id = id;
			IdView[0].render();
			IdView[0].$el.find( 'input' ).focus();
			fieldIndex++;
			isNewItem = false;
		}
	},

	updateShortcode: function( view ) {
		var template = _.template( '[field id="<%= id %>"]' )( {
			title: view.model.get( 'field_label' ),
			id: view.model.get( '_id' )
		} );

		view.$el.find( '.elementor-form-field-shortcode' ).focus( function() {
			this.select();
		} ).val( template );
	},

	onSectionActive: function() {
		var controlView = this.getView( 'form_fields' );

		controlView.children.each( this.updateShortcode );

		if ( ! this.collectionEventsAttached ) {
			controlView.collection.on( 'update', this.onFieldChanged );
			this.collectionEventsAttached = true;
		}
	},

	onInit: function() {
		this.addSectionListener( 'section_form_fields', this.onSectionActive );
	}
} );

},{"elementor-pro/editor/element-editor-module":3}],25:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports =  EditorModule.extend( {
	globalModels: {},

	panelWidgets: null,

	templatesAreSaved: true,

	addGlobalWidget: function( id, args ) {
		args = _.extend( {}, args, {
			categories: [],
			icon: elementor.config.widgets[ args.widgetType ].icon,
			widgetType: args.widgetType,
			custom: {
				templateID: id
			}
		} );

		var globalModel = this.createGlobalModel( id, args );

		return this.panelWidgets.add( globalModel );
	},

	createGlobalModel: function( id, modelArgs ) {
		var globalModel = new elementor.modules.elements.models.Element( modelArgs ),
			settingsModel = globalModel.get( 'settings' );

		globalModel.set( 'id', id );

		settingsModel.on( 'change', _.bind( this.onGlobalModelChange, this ) );

		return this.globalModels[ id ] = globalModel;
	},

	onGlobalModelChange: function() {
		this.templatesAreSaved = false;
	},

	setWidgetType: function() {
		elementor.hooks.addFilter( 'element/view', function( DefaultView, model ) {
			if ( model.get( 'templateID' ) ) {
				return require( './widget-view' );
			}

			return DefaultView;
		} );

		elementor.hooks.addFilter( 'element/model', function( DefaultModel, attrs ) {
			if ( attrs.templateID ) {
				return require( './widget-model' );
			}

			return DefaultModel;
		} );
	},

	registerTemplateType: function() {
		elementor.templates.registerTemplateType( 'widget', {
			showInLibrary: false,
			saveDialog: {
				title: elementorPro.translate( 'global_widget_save_title' ),
				description: elementorPro.translate( 'global_widget_save_description' )
			},
			prepareSavedData: function( data ) {
				data.widgetType = data.content[0].widgetType;

				return data;
			},
			ajaxParams: {
				success: this.onWidgetTemplateSaved.bind( this )
			}
		} );
	},

	addSavedWidgetsToPanel: function() {
		var self = this;

		self.panelWidgets = new Backbone.Collection();

		_.each( elementorPro.config.widget_templates, function( templateArgs, id ) {
			self.addGlobalWidget( id, templateArgs );
		} );

		elementor.hooks.addFilter( 'panel/elements/regionViews', function( regionViews ) {
			_.extend( regionViews.global, {
				view: require( './global-templates-view' ),
				options: {
					collection: self.panelWidgets
				}
			} );

			return regionViews;
		} );
	},

	addPanelPage: function() {
		elementor.getPanelView().addPage( 'globalWidget', {
			view: require( './panel-page' )
		} );
	},

	getGlobalModels: function( id ) {
		if ( ! id ) {
			return this.globalModels;
		}

		return this.globalModels[ id ];
	},

	saveTemplates: function() {
		if ( ! Object.keys( this.globalModels ).length ) {
			return;
		}

		var templatesData = [],
			self = this;

		_.each( this.globalModels, function( templateModel, id ) {
			if ( 'loaded' !== templateModel.get( 'settingsLoadedStatus' ) ) {
				return;
			}

			var data = {
				content: JSON.stringify( [ templateModel.toJSON( { removeDefault: true } ) ] ),
				source: 'local',
				type: 'widget',
				id: id
			};

			templatesData.push( data );
		} );

		if ( ! templatesData.length ) {
			return;
		}

		elementor.ajax.send( 'update_templates', {
			data: {
				templates: templatesData
			},
			success: function() {
				self.templatesAreSaved = true;
			}
		} );
	},

	setSaveButton: function() {
		if ( elementor.saver ) {
			elementor.saver.on( 'before:save:publish', _.bind( this.saveTemplates, this ) );
			elementor.saver.on( 'before:save:private', _.bind( this.saveTemplates, this ) );
		} else {
			// TODO: remove. Backward Compatibility for Elementor < 1.8
			elementor.getPanelView().footer.currentView.ui.buttonSave.on( 'click', this.saveTemplates.bind( this ) );
		}
	},

	requestGlobalModelSettings: function( globalModel, callback ) {
		var self = this;
		elementor.templates.requestTemplateContent( 'local', globalModel.get( 'id' ), {
			success: function( data ) {
				globalModel.set( 'settingsLoadedStatus', 'loaded' ).trigger( 'settings:loaded' );

				var settings = data.content[0].settings,
					settingsModel = globalModel.get( 'settings' );

				// Don't track it in History
				if ( elementor.history ) {
					elementor.history.history.setActive( false );
				}

				settingsModel.handleRepeaterData( settings );

				settingsModel.set( settings );

				if ( callback ) {
					callback( globalModel );
				}

				if ( elementor.history ) {
					elementor.history.history.setActive( true );
				}
			}
		} );
	},

	setWidgetContextMenuSaveAction: function() {
		elementor.hooks.addFilter( 'elements/widget/contextMenuGroups', function( groups, widget ) {
			var saveGroup = _.findWhere( groups, { name: 'save' } ),
				saveAction = _.findWhere( saveGroup.actions, { name: 'save' } );

			saveAction.callback = widget.save.bind( widget );

			delete saveAction.shortcut;

			return groups;
		} );
	},

	onElementorInit: function() {
		this.setWidgetType();

		this.registerTemplateType();

		this.setWidgetContextMenuSaveAction();
	},

	onElementorFrontendInit: function() {
		this.addSavedWidgetsToPanel();
	},

	onElementorPreviewLoaded: function() {
		this.addPanelPage();
		this.setSaveButton();
	},

	onWidgetTemplateSaved: function( data ) {
		if ( elementor.history ) {
			elementor.history.history.startItem( {
				title: elementor.config.widgets[ data.widgetType ].title,
				type: elementorPro.translate( 'linked_to_global' )
			} );
		}

		var widgetModel = elementor.templates.getLayout().modalContent.currentView.model,
			widgetModelIndex = widgetModel.collection.indexOf( widgetModel );

		elementor.templates.closeModal();

		data.elType = data.type;
		data.settings = widgetModel.get( 'settings' ).attributes;

		var globalModel = this.addGlobalWidget( data.template_id, data ),
			globalModelAttributes = globalModel.attributes;

		widgetModel.collection.add( {
			id: elementor.helpers.getUniqueID(),
			elType: globalModelAttributes.type,
			templateID: globalModelAttributes.template_id,
			widgetType: 'global'
		}, { at: widgetModelIndex }, true );

		widgetModel.destroy();

		var panel = elementor.getPanelView();

		panel.setPage( 'elements' );

		panel.getCurrentPageView().activateTab( 'global' );

		if ( elementor.history ) {
			elementor.history.history.endItem();
		}
	}
} );

},{"./global-templates-view":26,"./panel-page":28,"./widget-model":29,"./widget-view":30,"elementor-pro/editor/editor-module":1}],26:[function(require,module,exports){
module.exports = elementor.modules.layouts.panel.pages.elements.views.Elements.extend( {
	id: 'elementor-global-templates',

	getEmptyView: function() {
		if ( this.collection.length ) {
			return null;
		}

		return require( './no-templates' );
	},

	onFilterEmpty: function() {}
} );

},{"./no-templates":27}],27:[function(require,module,exports){
var GlobalWidgetsView = elementor.modules.layouts.panel.pages.elements.views.Global;

module.exports = GlobalWidgetsView.extend( {
	template: '#tmpl-elementor-panel-global-widget-no-templates',

	id: 'elementor-panel-global-widget-no-templates',

	className: 'elementor-nerd-box elementor-panel-nerd-box'
} );

},{}],28:[function(require,module,exports){

module.exports = Marionette.ItemView.extend( {
	id: 'elementor-panel-global-widget',

	template: '#tmpl-elementor-panel-global-widget',

	ui: {
		editButton: '#elementor-global-widget-locked-edit .elementor-button',
		unlinkButton: '#elementor-global-widget-locked-unlink .elementor-button',
		loading: '#elementor-global-widget-loading'
	},

	events: {
		'click @ui.editButton': 'onEditButtonClick',
		'click @ui.unlinkButton': 'onUnlinkButtonClick'
	},

	initialize: function() {
		this.initUnlinkDialog();
	},

	buildUnlinkDialog: function() {
		var self = this;

		return elementor.dialogsManager.createWidget( 'confirm', {
			id: 'elementor-global-widget-unlink-dialog',
			headerMessage: elementorPro.translate( 'unlink_widget' ),
			message: elementorPro.translate( 'dialog_confirm_unlink' ),
			position: {
				my: 'center center',
				at: 'center center'
			},
			strings: {
				confirm: elementorPro.translate( 'unlink' ),
				cancel: elementorPro.translate( 'cancel' )
			},
			onConfirm: function() {
				self.getOption( 'editedView' ).unlink();
			}
		} );
	},

	initUnlinkDialog: function() {
		var dialog;

		this.getUnlinkDialog = function() {
			if ( ! dialog ) {
				dialog = this.buildUnlinkDialog();
			}

			return dialog;
		};
	},

	editGlobalModel: function() {
		var editedView = this.getOption( 'editedView' );

		elementor.getPanelView().openEditor( editedView.getEditModel(), editedView );
	},

	onEditButtonClick: function() {
		var self = this,
			editedView = self.getOption( 'editedView' ),
			editedModel = editedView.getEditModel();

		if ( 'loaded' === editedModel.get( 'settingsLoadedStatus' ) ) {
			self.editGlobalModel();

			return;
		}

		self.ui.loading.removeClass( 'elementor-hidden' );

		elementorPro.modules.globalWidget.requestGlobalModelSettings( editedModel, function() {
			self.ui.loading.addClass( 'elementor-hidden' );

			self.editGlobalModel();
		} );
	},

	onUnlinkButtonClick: function() {
		this.getUnlinkDialog().show();
	}
} );

},{}],29:[function(require,module,exports){
module.exports = elementor.modules.elements.models.Element.extend( {
	initialize: function() {
		this.set( { widgetType: 'global' }, { silent: true } );

		elementor.modules.elements.models.Element.prototype.initialize.apply( this, arguments );

		elementorFrontend.config.elements.data[ this.cid ].on( 'change', this.onSettingsChange.bind( this ) );
	},

	initSettings: function() {
		var globalModel = this.getGlobalModel();

		this.set( 'settings', new elementor.modules.elements.models.BaseSettings( {}, {} ) );

		elementorFrontend.config.elements.data[ this.cid ] = globalModel.get( 'settings' );

		elementorFrontend.config.elements.editSettings[ this.cid ] = globalModel.get( 'editSettings' );
	},

	initEditSettings: function() {},

	getGlobalModel: function() {
		var templateID = this.get( 'templateID' );

		return elementorPro.modules.globalWidget.getGlobalModels( templateID );
	},

	getTitle: function() {
		return this.getGlobalModel().getTitle() + ' (' + elementorPro.translate( 'global' ) + ')';
	},

	getIcon: function() {
		return this.getGlobalModel().getIcon();
	},

	onSettingsChange: function( model ) {
		if ( ! model.changed.elements ) {
			this.set( 'previewSettings', model.toJSON( { removeDefault: true } ), { silent: true } );
		}
	},

	onDestroy: function() {
		var panel = elementor.getPanelView(),
			currentPageName = panel.getCurrentPageName();

		if ( -1 !== [ 'editor', 'globalWidget' ].indexOf( currentPageName ) ) {
			panel.setPage( 'elements' );
		}
	}
} );

},{}],30:[function(require,module,exports){
var WidgetView = elementor.modules.elements.views.Widget,
	GlobalWidgetView;

GlobalWidgetView = WidgetView.extend( {

	globalModel: null,

	className: function() {
		return WidgetView.prototype.className.apply( this, arguments ) + ' elementor-global-widget elementor-global-' + this.model.get( 'templateID' );
	},

	initialize: function() {
		var self = this,
			previewSettings = self.model.get( 'previewSettings' ),
			globalModel = self.getGlobalModel();

		if ( previewSettings ) {
			globalModel.set( 'settingsLoadedStatus', 'loaded' ).trigger( 'settings:loaded' );

			var settingsModel = globalModel.get( 'settings' );

			settingsModel.handleRepeaterData( previewSettings );

			settingsModel.set( previewSettings, { silent: true } );
		} else {
			var globalSettingsLoadedStatus = globalModel.get( 'settingsLoadedStatus' );

			if ( ! globalSettingsLoadedStatus ) {
				globalModel.set( 'settingsLoadedStatus', 'pending' );

				elementorPro.modules.globalWidget.requestGlobalModelSettings( globalModel );
			}

			if ( 'loaded' !== globalSettingsLoadedStatus ) {
				self.$el.addClass( 'elementor-loading' );
			}

			globalModel.on( 'settings:loaded', function() {
				self.$el.removeClass( 'elementor-loading' );

				self.render();
			} );
		}

		WidgetView.prototype.initialize.apply( self, arguments );
	},

	getGlobalModel: function() {
		if ( ! this.globalModel ) {
			this.globalModel = elementorPro.modules.globalWidget.getGlobalModels( this.model.get( 'templateID' ) );
		}

		return this.globalModel;
	},

	getEditModel: function() {
		return this.getGlobalModel();
	},

	getHTMLContent: function( html ) {
		if ( 'loaded' === this.getGlobalModel().get( 'settingsLoadedStatus' ) ) {
			return WidgetView.prototype.getHTMLContent.call( this, html );
		}

		return '';
	},

	serializeModel: function() {
		var globalModel = this.getGlobalModel();

		return globalModel.toJSON.apply( globalModel, _.rest( arguments ) );
	},

	edit: function() {
		elementor.getPanelView().setPage( 'globalWidget', 'Global Editing', { editedView: this } );
	},

	unlink: function() {
		var globalModel = this.getGlobalModel();

		if ( elementor.history ) {
			elementor.history.history.startItem( {
				title: globalModel.getTitle(),
				type: elementorPro.translate( 'unlink_widget' )
			} );
		}

		var newModel = new elementor.modules.elements.models.Element( {
			elType: 'widget',
			widgetType: globalModel.get( 'widgetType' ),
			id: elementor.helpers.getUniqueID(),
			settings: elementor.helpers.cloneObject( globalModel.get( 'settings' ).attributes ),
			defaultEditSettings: elementor.helpers.cloneObject( globalModel.get( 'editSettings' ).attributes )
		} );

		this._parent.addChildModel( newModel, { at: this.model.collection.indexOf( this.model ) } );

		var newWidget = this._parent.children.findByModelCid( newModel.cid );

		this.model.destroy();

		if ( elementor.history ) {
			elementor.history.history.endItem();
		}

		if ( newWidget.edit ) {
			newWidget.edit();
		}

		newModel.trigger( 'request:edit' );
	},

	onEditRequest: function() {
		elementor.getPanelView().setPage( 'globalWidget', 'Global Editing', { editedView: this } );
	}
} );

module.exports = GlobalWidgetView;

},{}],31:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorPreviewLoaded: function() {
		var EditButton = require( './editor/edit-button' );
		this.editButton = new EditButton();
	}
} );

},{"./editor/edit-button":32,"elementor-pro/editor/editor-module":1}],32:[function(require,module,exports){
module.exports = function() {
	var self = this;

	self.onPanelShow = function(  panel ) {
		var templateIdControl = panel.content.currentView.collection.findWhere( { name: 'template_id' } );

		if ( ! templateIdControl || 'template_id' !== templateIdControl.get( 'name' ) ) {
			return; // No templates
		}

		var templateIdInput = panel.content.currentView.children.findByModelCid( templateIdControl.cid );

		// Order templates by name.
		var $controlOptions = templateIdInput.$el.find( 'option' ),
			$select = templateIdInput.$el.find( 'select' ),
			// Keep the first option ( - select -) without order.
			$optionsSelect = $controlOptions.eq( 0 ),
			currentValue = panel.content.currentView.model.get( 'settings' ).get( 'template_id' );

		delete $controlOptions[0];

		$controlOptions.sort( function( a, b ) {
			var c = a.text.toLowerCase(),
				d = b.text.toLowerCase();
			if ( c > d ) {
				return 1;
			} else if ( c < d ) {
				return -1;
			}
			return 0;
		} );

		$select.empty().append( $optionsSelect ).append( $controlOptions );

		// Reset value, because the 'selected option' is changed while play with the options.
		$select.val( currentValue );

		// Change Edit link on change template.
		templateIdInput.on( 'input:change', self.onTemplateIdChange ).trigger( 'input:change' );
	};

	self.onTemplateIdChange = function() {
		var templateID = this.options.elementSettingsModel.attributes.template_id,
			type = this.options.model.attributes.types[ templateID ],
			$editButton = this.$el.find( '.elementor-edit-template' );

		if ( '0' === templateID || ! templateID || 'widget' === type ) { // '0' = first option, 'widget' is editable only from Elementor page
			if ( $editButton.length ) {
				$editButton.remove();
			}

			return;
		}

		var editUrl = ElementorConfig.home_url + '?p=' + templateID + '&elementor';

		if ( $editButton.length ) {
			$editButton.prop( 'href', editUrl );
		} else {
			$editButton = jQuery( '<a />', {
				target: '_blank',
				'class': 'elementor-button elementor-button-default elementor-edit-template',
				href: editUrl,
				html: '<i class="fa fa-pencil" /> ' + ElementorProConfig.i18n.edit_template
		} );

			this.$el.find( '.elementor-control-input-wrapper' ).after( $editButton );
		}
	};

	self.init = function() {
		elementor.hooks.addAction( 'panel/open_editor/widget/template', self.onPanelShow );
	};

	self.init();
};

},{}],33:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorPreviewLoaded: function() {
		elementor.addControlView( 'Query', require( './editor/query-control' ) );
	}
} );

},{"./editor/query-control":34,"elementor-pro/editor/editor-module":1}],34:[function(require,module,exports){
module.exports = elementor.modules.controls.Select2.extend( {

	cache: null,

	isTitlesReceived: false,

	getSelect2Placeholder: function() {
		return {
			id: '',
			text: elementorPro.translate( 'all' )
		};
	},

	getSelect2DefaultOptions: function() {
			var self = this;

			return jQuery.extend( elementor.modules.controls.Select2.prototype.getSelect2DefaultOptions.apply( this, arguments ), {
				ajax: {
					transport: function( params, success, failure ) {

						var data = {
							q: params.data.q,
							filter_type: self.model.get( 'filter_type' ),
							object_type: self.model.get( 'object_type' ),
							include_type: self.model.get( 'include_type' )
						};

						return elementorPro.ajax.send( 'panel_posts_control_filter_autocomplete', {
							data: data,
							success: success,
							error: failure
						} );
					},
					data: function( params ) {
						return {
							q: params.term,
							page: params.page
						};
					},
					cache: true
				},
				escapeMarkup: function( markup ) {
					return markup;
				},
				minimumInputLength: 1
			} );
		},

	getValueTitles: function() {
		var self = this,
			ids = this.getControlValue(),
			filterType = this.model.get( 'filter_type' );

		if ( ! ids || ! filterType ) {
			return;
		}

		if ( ! _.isArray( ids ) ) {
			ids = [ ids ];
		}

		elementor.ajax.loadObjects( {
			action: 'query_control_value_titles',
			ids: ids,
			data: {
				filter_type: filterType,
				object_type: self.model.get( 'object_type' ),
				unique_id: '' + self.cid + filterType
			},
			before: function() {
				self.addControlSpinner();
			},
			success: function( data ) {
				self.isTitlesReceived = true;

				self.model.set( 'options', data );

				self.render();
			}
		} );
	},

	addControlSpinner: function() {
		this.ui.select.prop( 'disabled', true );
		this.$el.find( '.elementor-control-title' ).after( '<span class="elementor-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>' );
	},

	onReady: function() {
		// Safari takes it's time to get the original select width
		setTimeout( elementor.modules.controls.Select2.prototype.onReady.bind( this ) );

		if ( ! this.isTitlesReceived ) {
			this.getValueTitles();
		}

	}
} );


},{}],35:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports = EditorModule.extend( {
	config: elementorPro.config.shareButtonsNetworks,

	networksClassDictionary: {
		google: 'fa fa-google-plus',
		pocket: 'fa fa-get-pocket',
		email: 'fa fa-envelope'
	},

	getNetworkClass: function( networkName ) {
		return this.networksClassDictionary[ networkName ] || 'fa fa-' + networkName;
	},

	getNetworkTitle: function( buttonSettings ) {
		return buttonSettings.text || this.config[ buttonSettings.button ].title;
	},

	hasCounter: function( networkName, settings ) {
		return 'icon' !== settings.view && 'yes' === settings.show_counter && this.config[ networkName ].has_counter;
	}
} );

},{"elementor-pro/editor/editor-module":1}],36:[function(require,module,exports){
var SaverBehavior = elementor.modules.components.saver.behaviors.FooterSaver;

module.exports = SaverBehavior.extend( {
	ui: function() {
		var ui = SaverBehavior.prototype.ui.apply( this, arguments );

		ui.menuConditions = '#elementor-pro-panel-saver-conditions';
		ui.buttonPreviewSettings = '#elementor-panel-footer-theme-builder-button-preview-settings';
		ui.buttonOpenPreview = '#elementor-panel-footer-theme-builder-button-open-preview';

		return ui;
	},

	events: function() {
		var events = SaverBehavior.prototype.events.apply( this, arguments );

		delete events[ 'click @ui.buttonPreview' ];

		events[ 'click @ui.menuConditions' ] = 'onClickMenuConditions';
		events[ 'click @ui.buttonPreviewSettings' ] = 'onClickButtonPreviewSettings';
		events[ 'click @ui.buttonOpenPreview' ] = 'onClickButtonPreview';

		return events;
	},

	initialize: function() {
		SaverBehavior.prototype.initialize.apply( this, arguments );

		elementor.settings.page.model.on( 'change', this.onChangeLocation.bind( this ) );
	},

	onRender: function() {
		SaverBehavior.prototype.onRender.apply( this, arguments );

		var $menuConditions = jQuery( '<div>', {
			id: 'elementor-pro-panel-saver-conditions',
			'class': 'elementor-panel-footer-sub-menu-item',
			html: '<i class="elementor-icon fa fa-paper-plane"></i>' +
			'<span class="elementor-title">' +
			elementorPro.translate( 'display_conditions' ) +
			'</span>'
		} );

		this.ui.menuConditions = $menuConditions;

		this.toggleMenuConditions();

		this.ui.saveTemplate.before( $menuConditions );

		this.ui.buttonPreview
		    .tipsy( 'disable' )
		    .html( jQuery( '#tmpl-elementor-theme-builder-button-preview' ).html() )
			.addClass( 'elementor-panel-footer-theme-builder-buttons-wrapper elementor-toggle-state' );
	},

	toggleMenuConditions: function() {
		this.ui.menuConditions.toggle( ! ! elementorPro.config.theme_builder.settings.location );
	},

	onChangeLocation: function( settings ) {
		if ( ! _.isUndefined( settings.changed.location ) ) {
			elementorPro.config.theme_builder.settings.location = settings.changed.location;
			this.toggleMenuConditions();
		}
	},

	onClickMenuConditions: function() {
		elementorPro.modules.themeBuilder.showConditionsModal();
	},

	onClickButtonPublish: function() {
		var hasConditions = elementorPro.config.theme_builder.settings.conditions.length,
			hasLocation = elementorPro.config.theme_builder.settings.location,
			isDraft = 'draft' === elementor.settings.page.model.get( 'post_status' );
		if ( ( hasConditions && ! isDraft ) || ! hasLocation ) {
			SaverBehavior.prototype.onClickButtonPublish.apply( this, arguments );
		} else {
			elementorPro.modules.themeBuilder.showConditionsModal();
		}
	},

	onClickButtonPreviewSettings: function() {
		var panel = elementor.getPanelView();
		panel.setPage( 'page_settings' );
		panel.getCurrentPageView().activateSection( 'preview_settings' );
		panel.getCurrentPageView()._renderChildren();
	}
} );

},{}],37:[function(require,module,exports){
var RepeaterRowView = require( './conditions-repeater-row' );

module.exports = elementor.modules.controls.Repeater.extend( {

	childView: RepeaterRowView,

	updateActiveRow: function() {},

	initialize: function( options ) {
		elementor.modules.controls.Repeater.prototype.initialize.apply( this, arguments );

		this.config = elementorPro.config.theme_builder;

		this.updateConditionsOptions( this.config.settings.template_type );
	},

	checkConflicts:function( model ) {
		var modelId = model.get( '_id' ),
			rowId = 'elementor-condition-id-' + modelId,
			errorMessageId = 'elementor-conditions-conflict-message-' + modelId,
			$error = jQuery( '#' + errorMessageId );

		// On render - the row isn't exist, so don't cache it.
		jQuery( '#' + rowId ).removeClass( 'elementor-error' );

		$error.remove();

		elementor.ajax.addRequest( 'theme_builder_conditions_check_conflicts', {
			unique_id: rowId,
			data: {
				condition: model.toJSON( { removeDefaults: true } )
			},
			success: function( data ) {
				if ( ! _.isEmpty( data ) ) {
					jQuery( '#' + rowId )
						.addClass( 'elementor-error' )
						.after( '<div id="' + errorMessageId + '" class="elementor-conditions-conflict-message">' + data + '</div>' );

				}
			}
		} );
	},

	updateConditionsOptions: function( templateType ) {
		var self = this,
			conditionType = self.config.types[ templateType ].condition_type,
			options = {};

		_( [ conditionType ] ).each( function( conditionId, conditionIndex ) {
			var conditionConfig = self.config.conditions[ conditionId ],
				group = {
					label: conditionConfig.label,
					options: {}
				};

			group.options[ conditionId ] = conditionConfig.all_label;

			_( conditionConfig.sub_conditions ).each( function( subConditionId ) {
				group.options[ subConditionId ] = self.config.conditions[ subConditionId ].label;
			} );

			options[ conditionIndex ] = group;
		} );

		var fields = this.model.get( 'fields' );

		fields[1]['default'] = conditionType;

		if ( 'general' === conditionType ) {
			fields[1].groups = options;
		} else {
			fields[2].groups = options;
		}
	},

	togglePublishButtonState: function() {
		var conditionsModalUI = elementorPro.modules.themeBuilder.conditionsLayout.modalContent.currentView.ui,
			$publishButton = conditionsModalUI.publishButton,
			$publishButtonTitle = conditionsModalUI.publishButtonTitle;

		if ( this.collection.length ) {
			$publishButton.addClass( 'elementor-button-success' );

			$publishButtonTitle.text( elementor.translate( 'publish' ) );
		} else {
			$publishButton.removeClass( 'elementor-button-success' );

			$publishButtonTitle.text( elementorPro.translate( 'save_without_conditions' ) );
		}
	},

	onRender: function() {
		this.ui.btnAddRow.text( elementorPro.translate( 'add_condition' ) );

		var self = this;

		this.collection.each( function( model ) {
			self.checkConflicts( model );
		});

		_.defer( this.togglePublishButtonState.bind( this ) );
	},

	// Overwrite thr original + checkConflicts.
	onRowControlChange: function( model ) {
		this.checkConflicts( model );
	},

	onRowUpdate: function() {
		elementor.modules.controls.Repeater.prototype.onRowUpdate.apply( this, arguments );

		this.togglePublishButtonState();
	}
} );

},{"./conditions-repeater-row":38}],38:[function(require,module,exports){
module.exports = elementor.modules.controls.RepeaterRow.extend( {

	template: '#tmpl-elementor-theme-builder-conditions-repeater-row',

	childViewContainer: '.elementor-theme-builder-conditions-repeater-row-controls',

	id: function() {
		return 'elementor-condition-id-' + this.model.get( '_id' );
	},

	onBeforeRender: function() {
		var	subNameModel = this.collection.findWhere( {
				name: 'sub_name'
			} ),
			subIdModel = this.collection.findWhere( {
				name: 'sub_id'
			} ),
			subConditionConfig = this.config.conditions[ this.model.attributes.sub_name ];

		subNameModel.attributes.groups = this.getOptions();

		if ( subConditionConfig && subConditionConfig.controls ) {
			_( subConditionConfig.controls ).each( function( control ) {
				subIdModel.set( control );
				subIdModel.set( 'name', 'sub_id' );
			} );
		}
	},

	initialize: function( options ) {
		elementor.modules.controls.RepeaterRow.prototype.initialize.apply( this, arguments );

		this.config = elementorPro.config.theme_builder;
	},

	updateOptions:function() {
		if ( this.model.changed.name ) {
			this.model.set({
				sub_name: '',
				sub_id: ''
			} );
		}

		if ( this.model.changed.name || this.model.changed.sub_name ) {
			this.model.set( 'sub_id', '' );

			var subIdModel = this.collection.findWhere( {
				name: 'sub_id'
			} );

			subIdModel.set( {
				type: 'select',
				options: {
					'': 'All'
				}
			} );

			this.render();
		}

		if ( this.model.changed.type ) {
			this.setTypeAttribute();
		}
	},

	getOptions: function() {
		var self = this,
			conditionConfig = self.config.conditions[ this.model.get( 'name' ) ];

		if ( ! conditionConfig ) {
			return;
		}

		var options = {
			'': conditionConfig.all_label
		};

		_( conditionConfig.sub_conditions ).each( function( conditionId, conditionIndex ) {
			var subConditionConfig = self.config.conditions[ conditionId ],
				group;

			if ( ! subConditionConfig ) {
				return;
			}

			if ( subConditionConfig.sub_conditions.length ) {
				group = {
					label: subConditionConfig.label,
					options: {}
				};
				group.options[ conditionId ]	= subConditionConfig.all_label;

				_( subConditionConfig.sub_conditions ).each( function( subConditionId ) {
					group.options[ subConditionId ] = self.config.conditions[ subConditionId ].label;
				} );

				// Use a sting key - to keep order
				options[ 'key' + conditionIndex ] = group;
			} else {
				options[ conditionId ] = subConditionConfig.label;
			}
		} );

		return options;
	},

	setTypeAttribute: function() {
		var typeView = this.children.findByModel( this.collection.findWhere( { name: 'type' } ) );

		typeView.$el.attr( 'data-elementor-condition-type', typeView.getControlValue() );
	},

	onRender: function() {
		var nameModel = this.collection.findWhere( {
				name: 'name'
			} ),
			subNameModel = this.collection.findWhere( {
				name: 'sub_name'
			} ),
			subIdModel = this.collection.findWhere( {
				name: 'sub_id'
			} ),
			nameView = this.children.findByModel( nameModel ),
			subNameView = this.children.findByModel( subNameModel ),
			subIdView = this.children.findByModel( subIdModel ),
			conditionConfig = this.config.conditions[ this.model.attributes.name ],
			subConditionConfig = this.config.conditions[ this.model.attributes.sub_name ],
			typeConfig = this.config.types[ this.config.settings.template_type ];

		if ( typeConfig.condition_type === nameView.getControlValue() && 'general' !== nameView.getControlValue() && ! _.isEmpty( conditionConfig.sub_conditions ) ) {
			nameView.$el.hide();
		}

		if ( ! conditionConfig || ( _.isEmpty( conditionConfig.sub_conditions ) && _.isEmpty( conditionConfig.controls ) ) || ! nameView.getControlValue() || 'general' === nameView.getControlValue() ) {
			subNameView.$el.hide();
		}

		if ( ! subConditionConfig || ( _.isEmpty( subConditionConfig.controls ) ) || ! subNameView.getControlValue() ) {
			subIdView.$el.hide();
		}

		// Avoid set a `single` for a-l-l singular types. (conflicted with 404 & custom cpt like Shops and Events plugins).
		if ( 'singular' === typeConfig.condition_type ) {
			if ( '' === subNameView.getControlValue() ) {
				subNameView.setValue( 'post' );
			}
		}

		this.setTypeAttribute();
	},

	onModelChange: function() {
		elementor.modules.controls.RepeaterRow.prototype.onModelChange.apply( this, arguments );

		this.updateOptions();
	}
} );

},{}],39:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports = EditorModule.extend( {

	onElementorInit: function() {
		elementor.addControlView( 'Conditions_repeater', require( './conditions-repeater-control' ) );

		elementor.hooks.addFilter( 'panel/footer/behaviors', this.addFooterBehavior );

		this.initConditionsLayout();
	},

	addFooterBehavior: function( behaviors ) {
		if ( elementorPro.config.theme_builder ) {
			var ProSaverBehavior = require( './behaviors/pro-saver-behavior' );
			behaviors.saver = {
				behaviorClass: ProSaverBehavior
			};
		}

		return behaviors;
	},

	saveAndReload: function() {
		elementor.saver.saveAutoSave( {
			onSuccess: function() {
				elementor.dynamicTags.cleanCache();
				elementor.reloadPreview();
			}
		} );
	},

	onApplyPreview: function() {
		this.saveAndReload();
	},

	onPageSettingsChange: function( model ) {
		if ( model.changed.preview_type ) {
			model.set( {
				preview_id: '',
				preview_search_term: ''
			} );
			this.updatePreviewIdOptions( true );
		}

		if ( ! _.isUndefined( model.changed.page_template ) ) {
			elementor.saver.saveAutoSave( {
				onSuccess: function() {
					elementor.reloadPreview();

					elementor.once( 'preview:loaded', function() {
						elementor.getPanelView().setPage( 'page_settings' );
					} );
				}
			} );
		}
	},

	updatePreviewIdOptions: function( render ) {
		var previewType = elementor.settings.page.model.get( 'preview_type' );
		if ( ! previewType ) {
			return;
		}
		previewType = previewType.split( '/' );

		var	currentView = elementor.getPanelView().getCurrentPageView(),
			controlModel = currentView.collection.findWhere( {
				name:'preview_id'
			} );

		if ( 'author' === previewType[1] ) {
			controlModel.set( {
				filter_type: 'author',
				object_type: 'author'
			} );
		} else if ( 'taxonomy' === previewType[0] ) {
			controlModel.set( {
				filter_type: 'taxonomy',
				object_type: previewType[1]
			} );
		} else if ( 'single' === previewType[0] ) {
			controlModel.set( {
				filter_type: 'post',
				object_type: previewType[1]
			} );
		} else {
			controlModel.set( {
				filter_type: '',
				object_type: ''
			} );
		}

		if ( true === render ) { // Can be model.

			var controlView = currentView.children.findByModel( controlModel );

			controlView.render();

			controlView.$el.toggle( ! ! controlModel.get( 'filter_type' ) );
		}
	},

	onElementorPreviewLoaded: function() {
		if ( ! elementorPro.config.theme_builder ) {
			return;
		}

		elementor.getPanelView().on( 'set:page:page_settings', this.updatePreviewIdOptions );

		elementor.settings.page.model.on( 'change', this.onPageSettingsChange.bind( this ) );

		elementor.channels.editor.on( 'elementorThemeBuilder:ApplyPreview', this.onApplyPreview.bind( this ) );

		// Scroll to Editor. Timeout according to preview resize css animation duration.
		setTimeout( function() {
			elementor.$previewContents.find( 'html, body' ).animate( {
				scrollTop: elementor.$previewContents.find( '#elementor' ).offset().top - elementor.$preview[0].contentWindow.innerHeight / 2
			} );
		}, 500 );
	},

	showConditionsModal: function() {
		var ThemeTemplateConditionsView = require( './views/conditions-view' ),
			themeBuilderModule = elementorPro.config.theme_builder,
			settings = themeBuilderModule.settings;

		var model = new elementor.modules.elements.models.BaseSettings( settings, {
			controls: themeBuilderModule.template_conditions.controls
		} );

		this.conditionsLayout.modalContent.show( new ThemeTemplateConditionsView( {
			model: model,
			controls: model.controls
		} ) );

		this.conditionsLayout.modal.show();
	},

	initConditionsLayout: function() {
		var ConditionsLayout = require( './views/conditions-layout' );

		this.conditionsLayout = new ConditionsLayout();
	}
} );

},{"./behaviors/pro-saver-behavior":36,"./conditions-repeater-control":37,"./views/conditions-layout":40,"./views/conditions-view":41,"elementor-pro/editor/editor-module":1}],40:[function(require,module,exports){
var BaseModalLayout = elementor.modules.components.templateLibrary.views.BaseModalLayout;

module.exports = BaseModalLayout.extend( {

	getModalOptions: function() {
		return {
			id: 'elementor-conditions-modal'
		};
	},

	getLogoOptions: function() {
		return {
			title: elementorPro.translate( 'display_conditions' )
		};
	},

	initialize: function() {
		BaseModalLayout.prototype.initialize.apply( this, arguments );

		this.showLogo();
	}
} );

},{}],41:[function(require,module,exports){
var inlineControlsStack = require( 'elementor-pro/editor/inline-controls-stack.js' );

module.exports = inlineControlsStack.extend( {
	id: 'elementor-theme-builder-conditions-view',

	template: '#tmpl-elementor-theme-builder-conditions-view',

	childViewContainer: '#elementor-theme-builder-conditions-controls',

	ui: function() {
		var ui = inlineControlsStack.prototype.ui.apply( this, arguments );

		ui.publishButton = '#elementor-theme-builder-conditions__publish';

		ui.publishButtonTitle = '#elementor-theme-builder-conditions__publish__title';

		return ui;
	},

	events: {
		'click @ui.publishButton': 'onClickPublish'
	},

	templateHelpers: function() {
		return {
			title: elementorPro.translate( 'conditions_title' ),
			description: elementorPro.translate( 'conditions_description' )
		};
	},

	childViewOptions: function() {
		return {
			elementSettingsModel: this.model
		};
	},

	onClickPublish: function( event ) {
		var self = this,
			$button = jQuery( event.currentTarget ),
			data = this.model.toJSON( { removeDefault: true } );

		event.stopPropagation();

		$button
			.attr( 'disabled', true )
			.addClass( 'elementor-button-state' );

		// Publish.
		elementor.ajax.addRequest( 'theme_builder_save_conditions', {
			data: data,
			success: function() {
				elementorPro.config.theme_builder.settings.conditions = self.model.get( 'conditions' );
				elementor.saver.publish();
			},
			complete: function() {
				self.afterAjax( $button );
			}
		} );
	},

	afterAjax: function( $button ) {
		$button
			.attr( 'disabled', false )
			.removeClass( 'elementor-button-state' );

		elementorPro.modules.themeBuilder.conditionsLayout.modal.hide();
	}
} );

},{"elementor-pro/editor/inline-controls-stack.js":4}],42:[function(require,module,exports){
module.exports = function() {
	var self = this;

	self.onPanelShow = function( panel, model ) {
		var settingsModel = model.get( 'settings' );

		// If no skins - set the skin to `theme_comments`.
		if ( ! settingsModel.controls._skin['default'] ) {
			settingsModel.set( '_skin', 'theme_comments' );
		}
	};

	self.init = function() {
		elementor.hooks.addAction( 'panel/open_editor/widget/post-comments', self.onPanelShow );
	};

	self.init();
};

},{}],43:[function(require,module,exports){
var EditorModule = require( 'elementor-pro/editor/editor-module' );

module.exports = EditorModule.extend( {
	onElementorPreviewLoaded: function() {
		var CommentsSkin = require( './comments-skin' );
		this.commentsSkin = new CommentsSkin();
	}
} );

},{"./comments-skin":42,"elementor-pro/editor/editor-module":1}]},{},[2])
//# sourceMappingURL=editor.js.map
