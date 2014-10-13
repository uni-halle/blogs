/*global AWPCP*/
AWPCP.define( 'awpcp/file-manager', [ 'jquery', 'knockout', 'awpcp/file', 'awpcp/settings' ],
function( $, ko, File, settings ) {
    var FileManager = function( nonce, files, options ) {
        var self = this;

        self.files = ko.observableArray( [] );
        self.images = ko.observableArray( [] );
        self.attachments = ko.observableArray( [] );

        self.imagesAllowed = ko.observable( options.imagesAllowed );
        self.imagesUploaded = ko.observable( options.imagesUploaded );

        self.primaryImageId = ko.observable( options.primaryImageId );

        self.setImageAsPrimary = function( file ) {
            self.showLoadingSpinner( file );

            $.post( settings.get( 'ajaxurl' ), {
                nonce: nonce,
                action: 'awpcp-set-image-as-primary',
                listing_id: file.listing_id,
                file_id: file.id
            }, function( response ) {
                if ( response.status === 'ok' ) {
                    self.primaryImageId( file.id );
                    self.hideLoadingSpinner( file );
                }
            } );
        };

        self.showLoadingSpinner = function( file ) {
            $( '#file-' + file.id ).find( '.image-actions .spinner' ).removeClass( 'spinner-hidden' );
        };

        self.hideLoadingSpinner = function( file ) {
            $( '#file-' + file.id ).find( '.image-actions .spinner' ).addClass( 'spinner-hidden' );
        };

        self.updateFileEnabledStatus = function( new_status ) {
            var file = this;

            if ( file.locked ) {
                return;
            }

            self.showLoadingSpinner( file );
            file.locked = true;

            $.post( settings.get( 'ajaxurl' ), {
                nonce: nonce,
                action: 'awpcp-update-file-enabled-status',
                listing_id: file.listing_id,
                file_id: file.id,
                new_status: new_status
            }, function( response ) {
                if ( response.status !== 'ok' ) {
                    file.enabled( ! new_status );
                }
                self.hideLoadingSpinner( file );
                file.locked = false;
            } );
        };

        self.deleteFile = function( file ) {
            self.showLoadingSpinner( file );

            $.post( settings.get( 'ajaxurl' ), {
                nonce: nonce,
                action: 'awpcp-delete-file',
                listing_id: file.listing_id,
                file_id: file.id
            }, function( response ) {
                if ( response.status === 'ok' ) {
                    self.files.remove(file);
                    if ( file.isImage ) {
                        self.images.remove( file );
                    } else {
                        self.attachments.remove( file );
                    }
                    self.hideLoadingSpinner( file );
                }
            } );
        };

        self.prepareFiles( nonce, files );
        self.defineComputedProperties();
    };

    $.extend( FileManager.prototype, {
        prepareFiles: function( nonce, files ) {
            var self = this;

            $.each( files, function( index, file ) {
                var model = new File( file, self.primaryImageId );

                model.enabled.subscribe( self.updateFileEnabledStatus, model );

                self[ file.isImage ? 'images' : 'attachments' ].push( model );
                self.files.push( model );
            } );
        },

        defineComputedProperties: function() {
            var self = this;

            self.haveFiles = ko.computed( function() {
                return self.files().length > 0;
            } );

            self.haveImages = ko.computed( function() {
                return self.images().length > 0;
            } );

            self.haveAttachments = ko.computed( function() {
                return self.attachments().length > 0;
            } );

            self.canUploadImages = ko.computed( function() {
                return self.imagesAllowed() > self.images().length;
            } );
        }
    } );

    return FileManager;
} );
