/*global AWPCP*/
AWPCP.define( 'awpcp/settings-validator', [
    'jquery',
    'awpcp/jquery-validate-methods'
], function( $ ) {
    var SettingsValidator = function() {
    };

    $.extend( SettingsValidator.prototype, {
        setup: function( form ) {
            var self = this, options = { rules: {}, messages: {} };

            form.find( '[awpcp-setting]' ).each( function() {
                var field = $(this),
                    fieldName = field.attr( 'name' ),
                    setting = $.parseJSON( field.attr( 'awpcp-setting' ) );

                options.messages[ fieldName ] = setting.validation.messages;
                options.rules[ fieldName ] = self.getSettingRules( setting );

                self.setupSettingBehavior( field, setting );
            } );

            form.validate( $.extend( options, {
                errorClass: 'invalid',
                errorPlacement: function ( error, element ) {
                    error.addClass( 'awpcp-message error' ).css( 'display', 'block' );
                    element.closest( 'td' ).append( error );
                }
            } ) );
        },

        getSettingRules: function( setting ) {
            var self = this, rules = {};

            for ( var validator in setting.validation.rules ) {
                if ( setting.validation.rules.hasOwnProperty( validator ) ) {
                    rules[ validator ] = self.getRuleForValidator( validator, setting );
                }
            }

            return rules;
        },

        getRuleForValidator: function( validator, setting ) {
            var rule = setting.validation.rules[ validator ],
                selector = this.getEscapedSelector( rule.depends );

            if ( $( selector ).length === 0 ) {
                return rule;
            } else {
                return $.extend( {}, rule, {
                    depends: function() {
                        return $( selector ).is(':checked');
                    }
                } );
            }
        },

        getEscapedSelector: function ( selector ) {
            if ( typeof selector === 'undefined' ) {
                return false;
            }

            var escapedSelectorParts = $.map( selector.split( ',' ), function( part ) {
                return '#' + part.trim().replace( /(:|\.|\[|\]|,)/g, '\\$1' );
            } );

            return escapedSelectorParts.join( ',' );
        },

        setupSettingBehavior: function( field, setting ) {
            var self = this;

            for ( var behavior in setting.behavior ) {
                if ( setting.behavior.hasOwnProperty( behavior ) ) {
                    if ( $.isFunction( self[ behavior ] ) ) {
                        self[ behavior ].apply( self, [ field, setting.behavior[ behavior ] ] );
                    }
                }
            }
        },

        enabledIf: function( field, element ) {
            var dependencies = $( this.getEscapedSelector( element ) );

            dependencies.change( function() {
                if ( dependencies.is(':checked') ) {
                    field.removeAttr( 'disabled' );
                } else {
                    field.attr( 'disabled', 'disabled' );
                }
            } );

            dependencies.change();
        }
    } );

    return new SettingsValidator();
} );
