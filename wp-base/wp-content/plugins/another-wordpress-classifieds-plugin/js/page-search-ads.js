(function($, undefined) {

    var AWPCP = jQuery.AWPCP = jQuery.extend({}, jQuery.AWPCP, AWPCP);

    $.AWPCP.validate();

    $(function() {
        var container = $('.awpcp-search-ads'), form, fields;

        /* Search Ads Form */

        form = container.find('.awpcp-search-ads-form');

        if (form.length) {
            // create and store jQuery objects for all form fields
            fields = form.find(':input').filter(':not(:button,:submit)').filter('[type!="hidden"]');

            // initialize Categories dropdown. It will send a message
            // everytime its value change.

            $.noop(new $.AWPCP.CategoriesDropdown(form.find('[name="searchcategory"]')));

            // validate: make sure at least one of the standard fields has
            // information to search for

            form.validate({
                rules: {
                    'keywordphrase': {
                        required: function() {
                            // required if no other field has a value
                            return fields.filter(':visible').filter(function() {
                                return $(this).val().length > 0;
                            }).length === 0;
                        }
                    }
                },
                messages: $.AWPCP.l10n('page-search-ads')
            });
        }
    });
})(jQuery);
