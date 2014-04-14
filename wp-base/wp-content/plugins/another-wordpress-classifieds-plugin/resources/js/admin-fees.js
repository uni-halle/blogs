/*global ajaxurl:true */

if (jQuery !== undefined) {
    (function($, undefined) {

        /* handlers for Fees page */
        $(function() {
            var panel = $('#awpcp-admin-fees');

            panel.admin({
                actions: {
                    add: 'awpcp-fees-add',
                    edit: 'awpcp-fees-edit',
                    remove: 'awpcp-fees-delete'
                },
                ajaxurl: ajaxurl,
                base: '#fee-',
                include: ['add', 'edit', 'trash'],

                onFormReady: function () {
                    $('.awpcp-fees .category-checklist').each(function() {
                        $.noop(new $.AWPCP.CategoriesChecklist(this));
                    });
                }
            });
        });

        $();

    })(jQuery);
}
