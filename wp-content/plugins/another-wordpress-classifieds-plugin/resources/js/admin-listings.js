/*global AWPCP, ajaxurl, ko */
AWPCP.run( 'awpcp/listing-admin-page', [
    'jquery',
    'awpcp/media-center',
    'awpcp/settings'
], function( $, MediaCenter, settings ) {

        var AWPCP = jQuery.AWPCP = jQuery.extend({}, jQuery.AWPCP, AWPCP);

    /**
     * handlers for Manage Images/Attachments page
     */

    $(function() {
        $( '.awpcp-admin-listings .awpcp-media-center' ).StartMediaCenter( {
            mediaManagerOptions: settings.get( 'media-manager-data' ),
            mediaUploaderOptions: settings.get( 'media-uploader-data' )
        } );
    });

        /**
         * handlers for Listings page
         */

        $(function() {
            var panel = $('#awpcp-admin-listings'), items;

            // handle row actions

            panel.admin({
                actions: {
                    remove: 'awpcp-listings-delete-ad'
                },
                ajaxurl: ajaxurl,
                base: '#awpcp-ad-',
                include: ['trash']
            });

            // handle Delete Selected Ads button

            panel.find('[name=action], [name=action2]').siblings('[type=submit]').click(function(event) {
                event.preventDefault();

                var button = $(this),
                    select = button.siblings('select'),
                    form = $(this).closest('form'),
                    message, cancel;

                if (select.val() === 'bulk-delete' && !button.hasClass('waiting')) {
                    message = AWPCP.l10n('admin-listings', 'delete-message') + ' &nbsp;';
                    cancel = AWPCP.l10n('admin-listings', 'cancel');
                    button.before($('<span class="delete-verification">' + message + '</span>'))
                          .before($('<input type="button" class="cancel button" value="' + cancel + '" /> ')
                                   .css('marginRight', '4px'))
                          .addClass('waiting').addClass('button-primary');
                } else {
                    form.get(0).submit();
                }

            }).closest('form').delegate('.cancel:button', 'click', function(event) {
                event.preventDefault();

                var cancel = $(this),
                    button = cancel.siblings('[type=submit]'),
                    form = $(this).closest('form');

                form.find('span.delete-verification').remove();
                button.removeClass('waiting').removeClass('button-primary');
                cancel.remove();
            });

            // handle items per page dropdown

            items = panel.find('[name="items-per-page"]').change(function() {
                var dropdown = $(this);
                items.val(dropdown.val());
                dropdown.closest('form').get(0).submit();
            });
        });

} );
