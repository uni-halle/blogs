/*global AWPCP*/
AWPCP.define( 'awpcp/media-center', [
    'jquery',
    'knockout',
    'awpcp/media-manager',
    'awpcp/media-uploader',
    'awpcp/thumbnails-generator',
    'awpcp/settings',
    'awpcp/jquery-messages'
],
function( $, ko, MediaManager, MediaUploader, ThumbnailsGenerator ) {
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
            $.noop( new MediaUploader( mediaUploader, options.mediaUploaderOptions ) );
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
