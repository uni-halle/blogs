/*jslint evil: true */
/*global AWPCP*/

AWPCP.define( 'awpcp/users-dropdown', [ 'jquery' ], function( $ ) {
    function UsersDropdown(element) {
        var self = this;

        self.field = $(element);
        self.previous_value = null;
    }

    $.extend(UsersDropdown.prototype, {
        setup: function() {
            var self = this, current_user_id = self.getSelectedUserId();

            self.field.change(function() {
                if (self.getSelectedUserId() > 0) {
                    self.update(self.getSelectedUser());
                } else {
                    self.previous_value = 0;
                }
            });

            if (current_user_id > 0) {
                self.init(self.getSelectedUser());
            }
        },

        getSelectedUserId: function() {
            return parseInt(this.field.val(), 10);
        },

        getSelectedUser: function() {
            var user_information = this.field.find(':selected').attr('data-user-information');

            if (user_information && $.parseJSON) {
                return $.parseJSON(user_information);
            } else if (user_information) {
                return eval('(' + user_information + ')');
            } else {
                return null;
            }
        },

        init: function(user) {
            this.previous_value = user.ID;
            $.publish('/user/updated', [user, false]);
        },

        update: function(user) {
            var overwrite = this.previous_value !== user.ID;
            $.publish('/user/updated', [user, overwrite]);
            this.previous_value = user.ID;
        }
    });

    return UsersDropdown;
});
