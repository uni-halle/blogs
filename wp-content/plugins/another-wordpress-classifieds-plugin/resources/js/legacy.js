/*global __awpcp_js_data*/
/*global __awpcp_js_l10n*/
/*global ajaxurl*/

/*!
 * jQuery Tiny Pub/Sub - v0.3 - 11/4/2010
 * http://benalman.com/
 *
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($){
    var o = $({});

    $.subscribe = function() {
        o.bind.apply( o, arguments );
    };

    $.unsubscribe = function() {
        o.unbind.apply( o, arguments );
    };

    $.publish = function() {
        o.trigger.apply( o, arguments );
    };
})(jQuery);



(function($) {

    /* AWPCP Main Object */

    var AWPCP = function() {
        if (typeof __awpcp_js_data === 'object') {
            this.options = __awpcp_js_data;
        } else {
            this.options = {};
        }

        if (typeof __awpcp_js_l10n === 'object') {
            this.localization = __awpcp_js_l10n;
        } else {
            this.localization = {};
        }

        if (this.get('ajaxurl') === null) {
            if (typeof AWPCP !== 'undefined' && AWPCP.ajaxurl) {
                this.set('ajaxurl', AWPCP.ajaxurl);
            } else if ( typeof ajaxurl !== 'undefined' ) {
                this.set('ajaxurl', ajaxurl);
            } else {
                this.set('ajaxurl', '/wp-admin/admin-ajax.php');
            }
        }
    };

    $.extend(AWPCP.prototype, {
        set: function(name, value) {
            this.options[name] = value;
            return this;
        },

        get: function(name, fallback) {
            return this.options[name] ? this.options[name] : (fallback ? fallback : null);
        },

        l10n: function(context, message) {
            if (this.localization.hasOwnProperty(context)) {
                if (!message) {
                    return this.localization[context];
                } else if (message && this.localization[context].hasOwnProperty(message)) {
                    return this.localization[context][message];
                }
            }
            return '';
        },

        /**
         * Common validation setup:
         *
         * - make reCAPTCHA fields required
         * - default jQuery Validate configuration
         * @return {[type]} [description]
         */
        validate: function(defaults) {
            // if there are reCAPTCHA fields in the page, make them required
            $('[name="recaptcha_response_field"]').addClass('required');

            $.extend($.validator.messages, $.AWPCP.get('default-validation-messages'));

            $.validator.addMethod('money', (function() {
                var decimal = $.AWPCP.get('decimal-separator'),
                    thousands = $.AWPCP.get('thousands-separator'),
                    pattern = new RegExp('^-?(?:\\d+|\\d{1,3}(?:\\' + thousands + '\\d{3})+)?(?:\\' + decimal + '\\d+)?$');
                return function(value, element) {
                    return this.optional(element) || pattern.test(value);
                };
            })()/*, validation message provided as a default validation message in awpcp.php */);

            $.validator.addClassRules('integer', {
                integer: true
            });

            $.validator.setDefaults(defaults || {
                errorClass: 'invalid',
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('awpcp-error');
                    var tables = ['payment_term', 'credit_plan', 'payment_method'];
                    if ($.inArray(element.attr('name'), tables) !== -1) {
                        error.insertBefore(element.closest('table'));
                    } else if (element.closest('.awpcp-form-spacer').length) {
                        error.appendTo(element.closest('.awpcp-form-spacer'));
                    }
                }
            });
        }
    });

    // refers to the AWPCP "class" in the current function, not the one in awpcp.js
    $.AWPCP = new AWPCP();


    /* Widgets & Plugins */

    $.AWPCP.DatepickerField = function(element) {
        var self = this;

        self.element = $(element);

        self.element.parent().find('[datepicker-placeholder]').datepicker({
            dateFormat: $.AWPCP.get('date-format'),
            altField: self.element,
            altFormat: 'yy/mm/dd'
        });
    };

    $.AWPCP.PaginationForm = function(form) {
        this.form = $(form);
        this.form.find('select').change(function() {
            this.form.submit();
        });
    };

    $.AWPCP.MainMenu = function(toggle) {
        var self = this;

        self.toggle = $(toggle);
        self.container = self.toggle.parent();

        self.toggle.click(function() {
            self.container.toggleClass('toggle-on');
        });
    };

    /* Common Operations */

    $(function() {
        $('.awpcp-pagination-form').each(function() {
            $.noop(new $.AWPCP.PaginationForm(this));
        });

        $('.awpcp-navigation').each(function() {
            $.noop(new $.AWPCP.MainMenu($(this).find('.awpcp-menu-toggle')));
        });
    });

})(jQuery);
