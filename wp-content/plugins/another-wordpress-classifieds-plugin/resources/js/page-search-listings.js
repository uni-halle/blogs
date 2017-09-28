/*global AWPCP*/
AWPCP.run( 'awpcp/page-search-listings', [
    'jquery',
    'awpcp/datepicker-field',
    'awpcp/jquery-validate-methods'
], function( $, DatepickerField ) {
    var AWPCP = $.AWPCP = $.extend({}, $.AWPCP, AWPCP);

    $(function() {
        $.AWPCP.validate();

        var container = $('.awpcp-search-ads'), form, fields;

        /* Search Ads Form */

        form = container.find('.awpcp-search-ads-form');

        if (form.length) {
            // create and store jQuery objects for all form fields
            fields = form.find(':input').filter(':not(:button,:submit)').filter('[type!="hidden"]');

            $( '[datepicker-placeholder]' ).each( function() {
                $.noop( new DatepickerField( $(this).siblings('[name]:hidden') ) );
            } );
        }
    });
} );
