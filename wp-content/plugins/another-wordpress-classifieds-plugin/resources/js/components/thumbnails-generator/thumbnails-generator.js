/* jshint latedef: false */
/* global AWPCP */

AWPCP.define( 'awpcp/thumbnails-generator', [ 'jquery', 'awpcp/settings' ],
function( $, settings ) {
    var QUEUE_STOPPED = 0;
    var QUEUE_ACTIVE = 1;

    var ThumbnailsGenerator = function( element ) {
        var self = this;

        self.element = $( element );
        self.video = self.element.find( 'video' );
        self.canvas = self.element.find( 'canvas' );

        self.queue = [];

        self.index = 0;
        self.status = QUEUE_STOPPED;

        if ( typeof self.video.get(0).canPlayType === 'undefined' || typeof URL === 'undefined' || typeof URL.createObjectURL === 'undefined' ) {
            // cannot generate thumbnails in this browser
            return;
        }

        $.subscribe( '/file/added', onFileAdded );
        $.subscribe( '/file/uploaded', onFileUploaded );

        self.video.bind( 'canplay', onVideoCanPlay );
        self.video.bind( 'seeked', onVideoSeeked );

        function onFileAdded( event, file ) {
            if ( file.type.match( 'video.*' ) ) {
                self.queue.push( { video: file, thumbnail: null } );
                processQueue();
            }
        }

        function processQueue() {
            if ( self.status === QUEUE_STOPPED ) {
                setTimeout( processNextFile, 100 );
                self.status = QUEUE_ACTIVE;
            }
        }

        function processNextFile() {
            var video = self.video.get(0);

            if ( self.index < self.queue.length ) {
                self.currentItem = self.queue[ self.index ];
                self.index = self.index + 1;

                // if ( ! video.canPlayType( self.currentItem.video.type ) ) {
                //     var message = 'A thumbnail cannot be generated for <video-format> files. We will try to generate a thumbnail on the server after the file is uploaded.';
                //     message = message.replace( '<video-format>', self.currentItem.video.type );
                //     return $.publish( '/messages/thumbnails-generator', { type: 'error', content: message } );
                // }

                video.src = URL.createObjectURL( self.currentItem.video.getNative() );
                video.play();
            } else {
                self.status = QUEUE_STOPPED;
                video.src = null;
                return;
            }
        }

        function onVideoCanPlay() {
            var video = this;

            if ( ! $.isNumeric( video.duration ) || isNaN( video.duration ) ) {
                return;
            }

            if ( Math.abs( video.currentTime - ( video.duration / 2 ) ) > 1 ) {
                video.currentTime = video.duration / 2;
                video.pause();
            }
        }

        function onVideoSeeked() {
            self.currentItem.thumbnail = generateThumbnailForCurrentVideo();
            setTimeout( processNextFile, 100 );
        }

        function generateThumbnailForCurrentVideo() {
            var video = self.video.get(0),
                canvas = self.canvas.get(0),
                context;

            canvas.width = video.clientWidth;
            canvas.height = video.clientHeight;

            context = canvas.getContext( '2d' );
            context.drawImage( video, 0, 0, canvas.width, canvas.height );

            return canvas.toDataURL();
        }

        function onFileUploaded( event, pluploadFile, fileInfo ) {
            var thumbnail = null;

            $.each( self.queue, function( index, item ) {
                if ( item.video.id === pluploadFile.id ) {
                    thumbnail = item.thumbnail;
                }
            } );

            if ( thumbnail === null ) {
                return;
            }

            uploadGeneratedThumbnail( pluploadFile, fileInfo, thumbnail );
        }

        function uploadGeneratedThumbnail( pluploadFile, fileInfo, thumbnail ) {
            $.post( settings.get( 'ajaxurl' ), {
                action: 'awpcp-upload-generated-thumbnail',
                nonce: self.element.attr( 'data-nonce' ),
                file: fileInfo.id,
                thumbnail: thumbnail
            }, function( response ) {
                if ( response.status === 'ok' ) {
                    $.publish( '/file/thumbnail-updated', [ pluploadFile, fileInfo, response.thumbnailUrl ] );
                }
            } );
        }
    };

    return ThumbnailsGenerator;
} );
