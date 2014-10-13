/*global AWPCP*/

AWPCP.define('awpcp/jquery-userfield', ['jquery', 'awpcp/users-dropdown', 'awpcp/users-autocomplete'],
function($, UsersDropdown, UsersAutocomplete) {
    $.fn.userfield = function() {
        return $(this).each(function() {
            var field = $(this), widget;

            if (field.is('[dropdown-field]')) {
                widget = new UsersDropdown(field);
            } else if (field.is('[autocomplete-field]')) {
                widget = new UsersAutocomplete(field);
            }

            widget.setup();
        });
    };
});
