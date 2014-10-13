/*global AWPCP*/

AWPCP.define('awpcp/users-autocomplete', ['jquery', 'awpcp/settings'], function($, settings) {
    function UsersAutocomplete(element) {
        var self = this;

        self.field = $(element);
        self.hidden = self.field.siblings('[autocomplete-selected-value]');
        self.previous_value = null;
    }

    $.extend(UsersAutocomplete.prototype, {
        setup: function() {
            var self = this, current_user = settings.get('users-autocomplete-default-user');

            self.field.autocomplete({
                source: function(request, response) {
                    $.getJSON(settings.get('ajaxurl'), {
                        action: 'awpcp-autocomplete-users',
                        term: request.term
                    }, function(ajax_response) {
                        if (ajax_response.status === 'ok') {
                            response(ajax_response.items);
                        }
                    });
                },

                select: function(event, ui) {
                    if ( ui.item ) { self.update(ui.item); }
                }
            });

            if (current_user) {
                self.init(current_user);
            }
        },

        init: function(user) {
            var self = this;

            self.hidden.val(user.ID);
            self.field.val(user.display_name);
            self.previous_value = parseInt(self.hidden.val(), 10);

            $.publish('/user/updated', [user, false]);
        },

        update: function(user) {
            var self = this, overwrite;

            self.hidden.val(user.ID);
            self.field.val(user.display_name);
            self.previous_value = parseInt(self.hidden.val(), 10);

            overwrite = user.ID !== self.previous_value;

            $.publish('/user/updated', [user, overwrite]);
        }
    });

    return UsersAutocomplete;
});
