/*global AWPCP*/
/*global AWPCP*/
console.log('sadasd');
AWPCP.run('awpcp/init-admin-attachments', ['jquery'],
function($) {
    console.log('sadasd');
    $(function() {
        console.log('sadasd');
        $('.awpcp-media-manager-file').delegate('.awcp-media-manager-file-actions a', 'click', function(event) {
            console.log('hi there!');
            event.preventDefault();

            var link = $(this),
                action = link.attr('data-action'),
                form = $(this).closest('form');

            console.log(link, action, form);

            form.find('[name="action"]').val(action);
            form.submit();
        });
    });
});
