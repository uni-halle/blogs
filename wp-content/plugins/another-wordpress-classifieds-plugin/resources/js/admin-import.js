/*global AWPCP */
AWPCP.run('awpcp/admin-import', ['jquery', 'awpcp/jquery-userfield'], function($) {
    $(function() {
        $('#awpcp-importer-start-date, #awpcp-importer-end-date').datepicker({
            changeMonth: true,
            changeYear: true
        });

        $( '#awpcp-importer-auto-assign-user' ).change( function() {
            if (!$(this).attr('checked') || !$(this).prop('checked')) {
                $('#awpcp-importer-user').attr('disabled', 'disabled');
            } else {
                $('#awpcp-importer-user').removeAttr('disabled');
            }
        }).change();

        $('#awpcp-importer-user').userfield();
    });
});
