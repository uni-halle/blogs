/* jshint latedef: false */
/* global AWPCP, plupload, _ */

AWPCP.define( 'awpcp/listings-media-uploader-view', [ 'jquery', 'awpcp/media-uploader-view', 'awpcp/settings' ],
function( $, MediaUploaderView, settings) {
    var ListingsMediaUploaderView = MediaUploaderView.extend({
        initialize: function() {
            var self = this;

            self._super('initialize');

            self.listenTo( self.model, 'media-uploader:file-uploaded', _.bind( self.updateUploadRestrictionsMessage, self ) );
            self.listenTo( self.model, 'media-uploader:file-deleted', _.bind( self.updateUploadRestrictionsMessage, self ) );

            self.configureBeforeUnloadEventHandler();
            self.updateUploadRestrictionsMessage();
        },

        configureBeforeUnloadEventHandler: function() {
            var self = this;

            window.onbeforeunload = function() {
                if ( ! self.model.uploader ) {
                    return false;
                }

                if ( self.model.uploader.state === plupload.STARTED ) {
                    return settings.l10n( 'media-uploader-beforeunload', 'files-are-being-uploaded' );
                }

                if ( self.model.uploader.total.queued > 0 ) {
                    return settings.l10n( 'media-uploader-beforeunload', 'files-pending-to-be-uploaded' );
                }

                if ( self.model.uploader.total.uploaded === 0 && settings.get( 'show-popup-if-user-did-not-upload-files' ) ) {
                    return settings.l10n( 'media-uploader-beforeunload', 'no-files-were-uploaded' );
                }
            };
        },

        updateUploadRestrictionsMessage: function() {
            this.$restrictions.html( this.model.buildUploadRestrictionsMessage() );
        }
    });

    return ListingsMediaUploaderView;
} );
