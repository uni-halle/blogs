/*global AWPCPUsers*/
(function($, undefined) {

    var AWPCP = jQuery.AWPCP = jQuery.extend({}, jQuery.AWPCP, AWPCP);

    $.AWPCP.UsersDropdown = function(dropdown) {
        var self = this;

        self.dropdown = dropdown.change(function() {
            var select = $(this),
                id = parseInt(select.val(), 10),
                terms;

            if (id > 0) {
                terms = select.find(':selected').attr('data-payment-terms');
                terms = terms ? terms.split(',') : [];
                $.publish('/user/updated', [id, terms, self.previous !== id]);
            }

            self.previous = self.id;
        });

        self.previous = parseInt(self.dropdown.val(), 10);
        self.dropdown.change();
    };


    $.AWPCP.PaymentTermsTable = function(table) {
        var self = this;

        self.table = table;
        self.terms = table.find('.awpcp-payment-term');

        self.category = null;
        self.user_terms = null;

        $.subscribe('/category/updated', function(event, dropdown, category) {
            if ($.contains(dropdown.closest('form').get(0), self.table.get(0))) {
                if (category) {
                    self.category = category;
                    self.update();
                }
            }
        });

        $.subscribe('/user/updated', function(event, user, terms) {
            self.user_terms = terms;
            self.update();
        });
    };

    $.extend($.AWPCP.PaymentTermsTable.prototype, {
        update: function() {
            var self = this, enabled, disabled, radio, term, categories;

            disabled = self.terms.filter(function() {
                term = $(this);

                // filter by user
                if ($.isArray(self.user_terms) && $.inArray(term.attr('id'), self.user_terms) === -1) {
                    return true;
                }

                // filter by category
                if (self.category) {
                    categories = $.parseJSON(term.attr('data-categories'));
                    if ($.isArray(categories)) {
                        categories = $.map(categories, function(category) {
                            return parseInt(category, 10);
                        });
                    } else {
                        categories = [];
                    }

                    if (categories.length > 0 && $.inArray(self.category, categories) === -1) {
                        return true;
                    }
                }

                return false;
            });

            enabled = self.terms.not(disabled.get());
            if (enabled.find(':radio:checked').length === 0) {
                radio = enabled.eq(0).find(':radio');
                if (radio.prop) {
                    radio.prop('checked', true);
                } else {
                    radio.attr('checked', 'checked');
                }
            }

            enabled.fadeIn();
            disabled.fadeOut();
        }
    });


    $.AWPCP.UserInformation = function(container) {
        var self = this;

        self.container = container;

        self.name = self.container.find('input[name=ad_contact_name]');
        self.email = self.container.find('input[name=ad_contact_email]');
        self.website = self.container.find('input[name=websiteurl]');
        self.phone = self.container.find('input[name=ad_contact_phone]');
        self.state = self.container.find('input[name=ad_state], select[name=ad_state]');
        self.city = self.container.find('input[name=ad_city], select[name=ad_city]');

        $.subscribe('/user/updated', function(event, user, terms, overwrite) {
            self.update(self.getUserData(user), overwrite);
        });
    };

    $.extend($.AWPCP.UserInformation.prototype, {
        getUserData: function(id) {
            var self = this;

            if (!self.hasOwnProperty('users')) {
                self.users = {};
                $.each(AWPCPUsers, function(k, entry) {
                    self.users[entry.ID] = entry;
                });
            }

            if (self.users[id]) {
                return self.users[id];
            }

            return null;
        },

        update: function(data, overwrite) {
            var self = this,
                current,
                passed,
                updated = {};

            current = {
                name: self.name.val(),
                email: self.email.val(),
                website: self.website.val(),
                phone: self.phone.val(),
                state: self.state.val(),
                city: self.city.val()
            };

            passed = {
                name: data.first_name + ' ' + data.last_name,
                email: data.user_email,
                website: data.user_url,
                phone: data.phone,
                state: data.state,
                city: data.city
            };

            $.each(current, function(field) {
                if (current[field] && current[field].length > 0 && !overwrite) {
                    updated[field] = current[field];
                } else {
                    updated[field] = passed[field] ? passed[field] : '';
                }
            });

            self.name.val(updated.name);
            self.email.val(updated.email);
            self.website.val(updated.website);
            self.phone.val(updated.phone);

            this.city.one('awpcp-update-region-options-completed', function() {
                self.city.val(updated.city).change();
            });
            this.state.val(updated.state).change();
        }
    });

    $.AWPCP.RestrictedLengthField = function(element) {
        var self = this;

        self.element = $(element);
        self.container = self.element.closest('.awpcp-form-spacer');
        self.placeholder = self.container.find('.characters-left-placeholder');

        self.allowed = parseInt(self.element.attr('data-max-characters'), 10);
        self.remaining = parseInt(self.element.attr('data-remaining-characters'), 10);

        self.element.bind('keyup keydown', function() {
            var text = self.element.val();
            if (self.allowed > 0) {
                if (text.length > self.allowed) {
                    text = text.substring(0, self.allowed);
                    self.element.val(text);
                }

                self.placeholder.text(self.allowed - text.length);
            }
        }).trigger('keyup');
    };

    $.AWPCP.validate();

    $(function() {
        var pages = [], container, form;

        pages.push('.awpcp-buy-subscription');
        pages.push('.awpcp-place-ad');
        pages.push('.awpcp-edit-ad');
        pages.push('.awpcp-admin-listings-place-ad');
        pages.push('.awpcp-admin-listings-edit-ad');

        container = $(pages.join(', '));

        /* Order Page */

        (function() {
            var form = container.find('.awpcp-order-form');
            if (form.length) {
                $.noop(new $.AWPCP.PaymentTermsTable(container.find('.awpcp-payment-terms-table')));
                // $.noop(new $.AWPCP.CategoriesDropdown(container.find('[name="category"]')));
                $.noop(new $.AWPCP.UsersDropdown(container.find('[name="user"]')));

                form.validate({
                    messages: $.AWPCP.l10n('page-place-ad-order')
                });
            }
        })();

        /* Checkout Page */

        (function() {
            var form = container.find('.awpcp-checkout-form');
            if (form.length) {
                form.validate({});
            }
        })();

        /* Details Form */

        (function() {
            form = container.find('.awpcp-details-form');
            if (form.length) {
                // update profile information everytime the selected user changes
                $.noop(new $.AWPCP.UserInformation(container));

                // $.noop(new $.AWPCP.CategoriesDropdown(container.find('[name="ad_category"]')));
                $.noop(new $.AWPCP.UsersDropdown(container.find('[name="user"]')));

                $.noop(new $.AWPCP.DatepickerField(container.find('[name="start_date"]')));
                $.noop(new $.AWPCP.DatepickerField(container.find('[name="end_date"]')));

                // display and control characters allowed for the Ad title
                $.noop(new $.AWPCP.RestrictedLengthField(container.find('[name="ad_title"]')));

                // display and control characters allowed for the Ad details
                $.noop(new $.AWPCP.RestrictedLengthField(container.find('[name="ad_details"]')));

                form.validate({
                    messages: $.AWPCP.l10n('page-place-ad-details'),
                    onfocusout: false
                });
            }
        })();

        /* Upload Images Form */

        (function() {
            form = container.find('.awpcp-upload-images-form');
            if (form.length) {
                var radios = form.find('.uploadform :radio').change(function() {
                    radios.closest('li').removeClass('primary').addClass('not-primary');
                    $(this).closest('li').removeClass('not-primary').addClass('primary');
                });
            }
        })();

        /* Deleta Ad Form */

        (function() {
            var form = container.find('.awpcp-delete-ad-form'),
                submit = form.find(':submit'),
                confirmation = form.find('.confirm');
            if (form.length) {
                form.submit(function(event) {
                    if (!submit.data('submit')) {
                        event.preventDefault();
                        confirmation.show();
                        form.append($('<input type="hidden" name="confirm" value="true">'));
                        submit.data('submit', true);
                    }
                }).find('.confirm:button').click(function() {
                    confirmation.hide();
                    form.find('[name="confirm"]').remove();
                    submit.data('submit', false);
                });
            }
        })();
    });
})(jQuery);
