/*global AWPCP*/
AWPCP.define('awpcp/user-information-updater', [ 'jquery' ],
function( $ ) {

    var UserInformationUpdater = function( container ) {
        var self = this;

        self.container = container;

        self.name = self.container.find('input[name=ad_contact_name]');
        self.email = self.container.find('input[name=ad_contact_email]');
        self.website = self.container.find('input[name=websiteurl]');
        self.phone = self.container.find('input[name=ad_contact_phone]');
        self.state = self.container.find('input[name=ad_state], select[name=ad_state]');
        self.city = self.container.find('input[name=ad_city], select[name=ad_city]');
    };

    $.extend( UserInformationUpdater.prototype, {
        watch: function() {
            var self = this;

            $.subscribe('/user/updated', function(event, user, overwrite) {
                self.update(user, overwrite);
            });
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
                name: data.display_name,
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
    } );

    return UserInformationUpdater;
} );
