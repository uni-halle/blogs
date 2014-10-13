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

    $.AWPCP.CategoriesDropdown = function(hidden, dropdown) {
        var self = this, selected;

        self.hidden = $(hidden);
        self.dropdown = $(dropdown);

        // using multiple dropdowns
        if (self.hidden.length > 0) {
            self.identifier = self.dropdown.attr('target');
            self.category_id = parseInt(self.dropdown.val(), 10);

            self.widget = new $.AWPCP.CategoriesDropdownWidget(self.identifier,
                                                               self.dropdown,
                                                               null,
                                                               self.category_id);

            $.subscribe('/category/updated/' + self.identifier, function(event, category_id) {
                self.hidden.val(category_id);
            });

            selected = self.dropdown.attr('chain');
            if (selected && selected.length > 0) {
                selected = $.map(selected.split(','), function(v) {
                    return parseInt(v, 10);
                });

                self.widget.choose(selected);
            } else {
                setTimeout(function() {
                    self.widget.change(null);
                }, 100);
            }

        // using a single dropdown
        } else {
            self.dropdown.change(function() {
                var category_id = parseInt(self.dropdown.val(), 10);
                $.publish('/category/updated' , [self.dropdown, isNaN(category_id) ? null : category_id]);
            });
        }
    };

    $.AWPCP.CategoriesDropdownWidget = function(identifier, dropdown, parent, category_id) {
        var self = this;

        self.identifier = identifier;

        self.category_id = category_id;

        self.parent = parent;  // parent dropdown
        self.child = null;  // child dropdown

        if (!dropdown && parent) {
            self.default_option = self.parent.attr('next-default-option');
            self.dropdown = $('<select class="awpcp-category-dropdown">').insertAfter(parent).hide();
        } else if (dropdown) {
            self.dropdown = dropdown;
        } else {
            return;
        }

        self.dropdown.change(function() {
            self.change(parseInt($(this).val(), 10));
        });

        $.subscribe('/category/widget/updated/' + self.identifier, function(event, dropdown, parent_category_id) {
            if (self.parent === dropdown) {
                self.render(parent_category_id);
            }
        });

        self.dropdown.val(self.category ? self.category : '');
    };

    $.extend($.AWPCP.CategoriesDropdownWidget.prototype, {
        change: function(category_id) {
            var self = this;

            self.category_id = isNaN(category_id) ? null : category_id;

            if (self.child === null) {
                self.child = new $.AWPCP.CategoriesDropdownWidget(self.identifier, null, self.dropdown, null);
            }

            $.publish('/category/updated' , [self.dropdown, self.category_id]);
            $.publish('/category/updated/' + self.identifier , [self.category_id]);
            $.publish('/category/widget/updated/' + self.identifier, [self.dropdown, self.category_id]);
        },

        render: function(parent_category_id) {
            var self = this, children, categories, length;

            categories = $.AWPCP.get('categories');
            if (null === self.parent && categories.hasOwnProperty('root')) {
                children = categories.root;
            } else if (categories.hasOwnProperty(parent_category_id)) {
                children = categories[parent_category_id];
            } else {
                children = [];
            }

            self.dropdown.empty()
                         .append($('<option value="">' + self.default_option + '</option>'));

            length = children.length;
            for (var i = 0; i < length; i = i + 1) {
                self.dropdown.append($('<option value="' + children[i].id + '">' + children[i].name + '</option>'));
            }

            if (length > 0) {
                self.dropdown.show();
            } else {
                self.hide();
            }
        },

        choose: function(selected) {
            var self = this;

            if (selected.length > 0) {
                self.dropdown.val(selected[0]);
                self.change(selected[0]);
                self.child.choose(selected.slice(1));
            }
        },

        hide: function() {
            this.dropdown.hide();
        }
    });

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

        $('.awpcp-category-dropdown').each(function() {
            var select = $(this), hidden = $('#awpcp-category-dropdown-' + select.attr('target'));
            $.noop(new $.AWPCP.CategoriesDropdown(hidden, select));
        });

        $('.awpcp-navigation').each(function() {
            $.noop(new $.AWPCP.MainMenu($(this).find('.awpcp-menu-toggle')));
        });
    });

})(jQuery);
