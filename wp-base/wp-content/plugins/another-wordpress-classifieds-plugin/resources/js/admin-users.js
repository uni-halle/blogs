/*global ajaxurl:true */

if (jQuery !== undefined) {
    (function($, undefined) {

        /* handlers for Fees page */

        $(function() {
            var panel = $('.wp-admin.users-php .wrap');

            panel.delegate('.row-actions a', 'click', function(event) {
                var link = $(this);

                if (!link.hasClass('debit') && !link.hasClass('credit')) {
                    return;
                }

                event.preventDefault();

                var action = link.hasClass('debit') ? 'debit' : 'credit',
                    row = link.closest('tr'),
                    inline;

                $.post(ajaxurl, {
                    action: 'awpcp-users-' + action,
                    user: parseInt(link.closest('tr').attr('id').replace('user-', ''), 10),
                    columns: link.closest('table').find('thead th').length
                }, function(response) {
                    inline = $(response.html).insertBefore(row);

                    // handle save and cancel buttons

                    var fn = function(event) {
                        event.preventDefault();

                        var waiting = inline.find('img.waiting').show();

                        inline.find('div.error').remove();
                        inline.find('form').ajaxSubmit({
                            data: {
                                'save': true
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    row.find('td.balance span.balance').text(response.balance);
                                    inline.remove();
                                    row.show();
                                } else {
                                    waiting.hide();
                                    var errors = $('<div class="error">');
                                    $.each(response.errors, function(k,v) {
                                        errors.append(v + '</br>');
                                    });
                                    inline.find('p.submit').after(errors);
                                }
                            }
                        });
                    };

                    inline.find('a.save').click(fn);
                    inline.find('form').submit(fn);

                    inline.find('a.cancel').click(function() {
                        row.show(); inline.remove();
                    });

                    row.hide();
                });
            });
        });
    })(jQuery);
}
