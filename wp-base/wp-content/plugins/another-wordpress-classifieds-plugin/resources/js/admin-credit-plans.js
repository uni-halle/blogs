/*global ajaxurl:true */

if (jQuery !== undefined) {
    (function($, undefined) {

        /* handlers for Credit Plans page */

        $(function() {
            var panel = $('#awpcp-admin-credit-plans');

            panel.admin({
                actions: {
                    add: 'awpcp-credit-plans-add',
                    edit: 'awpcp-credit-plans-edit',
                    remove: 'awpcp-credit-plans-delete'
                },
                ajaxurl: ajaxurl,
                base: '#credit-plan-',
                include: ['add', 'edit', 'trash']
            });
        });

    })(jQuery);
}
