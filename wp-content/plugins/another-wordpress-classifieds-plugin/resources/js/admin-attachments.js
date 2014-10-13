/*global AWPCP*/
AWPCP.run('awpcp/init-admin-attachments', ['jquery'],
function($) {
    $(function() {
        $('.awpcp-media-manager-file').delegate('.awcp-media-manager-file-actions a', 'click', function(event) {
            event.preventDefault();

            var link = $(this),
                action = link.attr('data-action'),
                form = $(this).closest('form');


            form.find('[name="action"]').val(action);
            form.submit();
        });
    });
});
