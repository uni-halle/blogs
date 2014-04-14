/*global ko */
if (typeof jQuery !== 'undefined') {
    (function($) {
        var BillingForm, form = $('.awpcp-billing-form');

        BillingForm= function() {
            var self = this, number;

            self.country = ko.observable(form.find('[name="country"]').val());
            self.credit_card_number = ko.observable(form.find('[name="credit_card_number"]').val());
            self.exp_month = ko.observable(form.find('[name="exp_month"]').val());
            self.exp_year = ko.observable(form.find('[name="exp_year"]').val());

            self.first_name = ko.observable(form.find('[name="first_name"]').val());
            self.last_name = ko.observable(form.find('[name="last_name"]').val());
            self.address_1 = ko.observable(form.find('[name="address_1"]').val());
            self.address_2 = ko.observable(form.find('[name="address_2"]').val());
            self.state = null; // see actual implementation below
            self.city = ko.observable(form.find('[name="city"]').val());
            self.postal_code = ko.observable(form.find('[name="postal_code"]').val());
            self.email = ko.observable(form.find('[name="email"]').val());

            // helper functions to find out the credit card type

            self.is_visa = ko.computed(function() {
                number = self.credit_card_number();
                return (/^(4)/.test(number) && number.length === 16);
            });

            self.is_mastercard = ko.computed(function() {
                number = self.credit_card_number();
                return (/^(5[12345])/.test(number) && number.length === 16);
            });

            self.is_discover = ko.computed(function() {
                number = self.credit_card_number();
                return (/^(6011)/.test(number) && number.length === 16);
            });

            self.is_amex = ko.computed(function() {
                number = self.credit_card_number();
                return (/^(3[47])/.test(number) && number.length === 15);
            });

            self.credit_card_type = ko.computed(function() {
                if (self.is_visa()) {
                    return 'Visa';
                } else if (self.is_mastercard()) {
                    return 'MasterCard';
                } else if (self.is_discover()) {
                    return 'Discover';
                } else if (self.is_amex()) {
                    return 'Amex';
                }
            });

            // helper functions to highlight the icon corresponding with the
            // current credit card type

            self.hide_visa = ko.computed(function() {
                return self.credit_card_number() && self.credit_card_number().length > 0 && !self.is_visa();
            });

            self.hide_mastercard = ko.computed(function() {
                return self.credit_card_number() && self.credit_card_number().length > 0 && !self.is_mastercard();
            });

            self.hide_discover = ko.computed(function() {
                return self.credit_card_number() && self.credit_card_number().length > 0 && !self.is_discover();
            });

            self.hide_amex = ko.computed(function() {
                return self.credit_card_number() && self.credit_card_number().length > 0 && !self.is_amex();
            });

            // helper function to switc between a textfield and a dropdown
            // for the State field

            self.countries = [{
                    name: 'United States',
                    code: 'US',
                    states: [
                        { code: 'AL', name: 'Alabama' },
                        { code: 'AK', name: 'Alaska' },
                        { code: 'AZ', name: 'Arizona' },
                        { code: 'AR', name: 'Arkansas' },
                        { code: 'CA', name: 'California' },
                        { code: 'CO', name: 'Colorado' },
                        { code: 'CT', name: 'Connecticut' },
                        { code: 'DE', name: 'Delaware' },
                        { code: 'FL', name: 'Florida' },
                        { code: 'GA', name: 'Georgia' },
                        { code: 'HI', name: 'Hawaii' },
                        { code: 'ID', name: 'Idaho' },
                        { code: 'IL', name: 'Illinois' },
                        { code: 'IN', name: 'Indiana' },
                        { code: 'IA', name: 'Iowa' },
                        { code: 'KS', name: 'Kansas' },
                        { code: 'KY', name: 'Kentucky' },
                        { code: 'LA', name: 'Louisiana' },
                        { code: 'ME', name: 'Maine' },
                        { code: 'MD', name: 'Maryland' },
                        { code: 'MA', name: 'Massachusetts' },
                        { code: 'MI', name: 'Michigan' },
                        { code: 'MN', name: 'Minnesota' },
                        { code: 'MS', name: 'Mississippi' },
                        { code: 'MO', name: 'Missouri' },
                        { code: 'MT', name: 'Montana' },
                        { code: 'NE', name: 'Nebraska' },
                        { code: 'NV', name: 'Nevada' },
                        { code: 'NH', name: 'New Hampshire' },
                        { code: 'NJ', name: 'New Jersey' },
                        { code: 'NM', name: 'New Mexico' },
                        { code: 'NY', name: 'New York' },
                        { code: 'NC', name: 'North Carolina' },
                        { code: 'ND', name: 'North Dakota' },
                        { code: 'OH', name: 'Ohio' },
                        { code: 'OK', name: 'Oklahoma' },
                        { code: 'OR', name: 'Oregon' },
                        { code: 'PA', name: 'Pennsylvania' },
                        { code: 'RI', name: 'Rhode Island' },
                        { code: 'SC', name: 'South Carolina' },
                        { code: 'SD', name: 'South Dakota' },
                        { code: 'TN', name: 'Tennessee' },
                        { code: 'TX', name: 'Texas' },
                        { code: 'UT', name: 'Utah' },
                        { code: 'VT', name: 'Vermont' },
                        { code: 'VA', name: 'Virginia' },
                        { code: 'WA', name: 'Washington' },
                        { code: 'WV', name: 'West Virginia' },
                        { code: 'WI', name: 'Wisconsin' },
                        { code: 'WY', name: 'Wyoming' }
                    ]
                }, {
                    name: 'Canada',
                    code: 'CA',
                    states: [
                        { code: 'AB', name: 'Alberta' },
                        { code: 'BC', name: 'British Columbia' },
                        { code: 'MB', name: 'Manitoba' },
                        { code: 'NB', name: 'New Brunswick' },
                        { code: 'NF', name: 'Newfoundland and Labrador' },
                        { code: 'NT', name: 'Northwest Territories' },
                        { code: 'NS', name: 'Nova Scotia' },
                        { code: 'NU', name: 'Nunavut' },
                        { code: 'ON', name: 'Ontario' },
                        { code: 'PE', name: 'Prince Edward Island' },
                        { code: 'PQ', name: 'Quebec' },
                        { code: 'SK', name: 'Saskatchewan' },
                        { code: 'YT', name: 'Yukon Territory' }
                    ]
                }, {
                    name: 'Australia',
                    code: 'AU',
                    states: [
                        { code: 'AC', name: 'Australian Capital Territory' },
                        { code: 'NW', name: 'New South Wales' },
                        { code: 'NO', name: 'Northern Territory' },
                        { code: 'QL', name: 'Queensland' },
                        { code: 'SA', name: 'South Australia' },
                        { code: 'TS', name: 'Tasmania' },
                        { code: 'VC', name: 'Victoria' },
                        { code: 'WS', name: 'Western Australia'}
                    ]
                }
            ];

            self._country = ko.computed(function() {
                var code = self.country();
                return ko.utils.arrayFirst(self.countries, function(item) {
                    return item.code === code;
                });
            });

            // handle state field options, whether a text or select field is shown
            // or if the field is shown at all

            self._state = form.find('[name="state"]').val();

            self.show_state_field = ko.computed(function() {
                return self.country() !== 'GB';
            });

            self.state = ko.computed({
                read: function() {
                    if (!self.show_state_field()) {
                        return self.city();
                    } else {
                        return self._state;
                    }
                },
                write: function (value) {
                    self._state = value;
                }
            });

        };

        $(function() {
            ko.applyBindings(new BillingForm(), form.get(0));
        });
    })(jQuery);
}
