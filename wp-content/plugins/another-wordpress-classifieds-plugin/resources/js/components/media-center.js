/*global AWPCP*/
AWPCP.define( 'awpcp/media-center', [
    'jquery',
    'knockout',
    'awpcp/media-manager',
    'awpcp/media-uploader',
    'awpcp/listings-media-uploader-view',
    'awpcp/thumbnails-generator',
    'awpcp/jquery-messages'
],
function( $, ko, MediaManager, MediaUploader, ListingsMediaUploaderView, ThumbnailsGenerator ) {
    var MediaCenter = function( container, options ) {
        var self = this;

        self.container = $( container );

        var mediaManager = self.container.find( '.awpcp-media-manager' ),
            mediaUploader = self.container.find( '#awpcp-media-uploader' ),
            thumbnailsGenerator = self.container.find( '.awpcp-thumbnails-generator' );

        self.container.find( '.awpcp-messages' ).AWPCPMessages();

        if ( mediaManager.length && options.mediaManagerOptions ) {
            ko.applyBindings( new MediaManager( options.mediaManagerOptions ), mediaManager.get( 0 ) );
        }

        if ( mediaUploader.length && options.mediaUploaderOptions ) {
            var model = new MediaUploader( { settings: options.mediaUploaderOptions } );

            $.noop(new ListingsMediaUploaderView({
                el: mediaUploader,
                model: model
            }));
        }

        if ( thumbnailsGenerator.length ) {
            $.noop( new ThumbnailsGenerator( thumbnailsGenerator ) );
        }
    };

    $.fn.StartMediaCenter = function( options ) {
        $(this).each( function() { $.noop( new MediaCenter( $(this), options ) ); } );
    };

    return MediaCenter;
} );
