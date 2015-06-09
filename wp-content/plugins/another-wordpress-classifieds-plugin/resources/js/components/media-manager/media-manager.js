/* jshint latedef: false */
/* global AWPCP */

AWPCP.define( 'awpcp/media-manager', [ 'jquery', 'knockout', 'awpcp/file-view-model', 'awpcp/settings' ],
function( $, ko, FileViewModel, settings ) {
    var MediaManager = function( options ) {
        var vm = this;

        vm.files = ko.observableArray( prepareFiles( options.files ) );
        vm.images = ko.computed( filterImageFiles );
        vm.videos = ko.computed( filterVideoFiles );
        vm.others = ko.computed( filterOtherFiles );

        vm.haveImages = ko.computed( haveImages );
        vm.haveVideos = ko.computed( haveVideos );
        vm.haveOtherFiles = ko.computed( haveOtherFiles );
        vm.showAdminActions = ko.observable( !! options.show_admin_actions );

        vm.deleteFile = deleteFile;
        vm.setFileAsPrimary = setFileAsPrimary;
        vm.approveFile = approveFile;
        vm.rejectFile = rejectFile;

        vm.getFileCSSClasses = getFileCSSClasses;
        vm.getFileId = getFileId;

        $.subscribe( '/file/uploaded', onFileUploaded );
        $.subscribe( '/file/thumbnail-updated', onFileThumbnailUpdated );

        function prepareFiles( files ) {
            return $.map( files, function( file ) {
                var model = new FileViewModel( file );
                model.enabled.subscribe( updateFileEnabledStatus, model );
                return model;
            } );
        }

        function filterImageFiles() {
            return filterFilesByType( 'images' );
        }

        function filterFilesByType( type ) {
            if ( options.allowed_files[ type ] && options.allowed_files[ type ].hasOwnProperty( 'mime_types' ) ) {
                return filterFilesByMimeType( vm.files(), options.allowed_files[ type ].mime_types );
            } else {
                return [];
            }
        }

        function filterFilesByMimeType( files, mimeTypes ) {
            return $.grep( files, function( file ) {
                return $.inArray( file.mimeType, mimeTypes ) !== -1;
            } );
        }

        function filterVideoFiles() {
            return filterFilesByType( 'videos' );
        }

        function filterOtherFiles() {
            return filterFilesByType( 'others' );
        }

        function haveImages() {
            return vm.images().length > 0;
        }

        function haveVideos() {
            return vm.videos().length > 0;
        }

        function haveOtherFiles() {
            return vm.others().length > 0;
        }

        function setFileAsPrimary( file ) {
            file.isBeingModified( true );

            $.post( settings.get( 'ajaxurl' ), {
                nonce: options.nonce,
                action: 'awpcp-set-file-as-primary',
                listing_id: file.listingId,
                file_id: file.id
            }, function( response ) {
                if ( response.status === 'ok' ) {
                    $.each( getFilesOfTheSameType( file ), function( index, currentFile ) {
                        currentFile.isPrimary( false );
                    } );

                    file.isPrimary( true );
                    file.isBeingModified( false );
                }
            } );
        }

        function getFilesOfTheSameType( file ) {
            var mimeTypes = null;

            $.each( options.allowed_files, function( index, config ) {
                if ( $.inArray( file.mimeType, config.mime_types ) !== -1 ) {
                    mimeTypes = config.mime_types;
                    return false;
                }
            } );

            return mimeTypes === null ? [] : filterFilesByMimeType( vm.files(), mimeTypes );
        }

        function updateFileEnabledStatus( newStatus ) {
            var file = this;

            if ( file.isBeingModified() ) {
                return;
            } else {
                file.isBeingModified( true );
            }

            $.post( settings.get( 'ajaxurl' ), {
                nonce: options.nonce,
                action: 'awpcp-update-file-enabled-status',
                listing_id: file.listingId,
                file_id: file.id,
                new_status: newStatus
            }, function( response ) {
                if ( response.status !== 'ok' ) {
                    file.enabled( ! newStatus );
                }
                file.isBeingModified( false );
            } );
        }

        function deleteFile( file ) {
            file.isBeingModified( true );

            $.post( settings.get( 'ajaxurl' ), {
                nonce: options.nonce,
                action: 'awpcp-delete-file',
                listing_id: file.listingId,
                file_id: file.id
            }, function( response ) {
                if ( response.status === 'ok' ) {
                    $.publish( '/file/deleted', { type: file.mimeType } );

                    vm.files.remove( file );
                    file.isBeingModified( false );
                }
            } );
        }

        function approveFile( file ) {
            file.isBeingModified( true );

            $.post( settings.get( 'ajaxurl' ), {
                nonce: options.nonce,
                action: 'awpcp-approve-file',
                listing_id: file.listingId,
                file_id: file.id
            }, function( response ) {
                if ( response.status === 'ok' ) {
                    file.status( 'Approved' );
                    file.isBeingModified( false );
                }
            } );
        }

        function rejectFile( file ) {
            file.isBeingModified( true );

            $.post( settings.get( 'ajaxurl' ), {
                nonce: options.nonce,
                action: 'awpcp-reject-file',
                listing_id: file.listingId,
                file_id: file.id
            }, function( response ) {
                if ( response.status === 'ok' ) {
                    file.status( 'Rejected' );
                    file.isBeingModified( false );
                }
            } );
        }

        function getFileCSSClasses( file ) {
            var classes = [ 'awpcp-uploaded-file' ];

            classes.push( file.enabled() ? 'is-enabled' : 'is-disabled' );
            classes.push( 'is-' + file.status().toLowerCase() );

            if ( file.isPrimary() ) {
                classes.push( 'is-primary' );
            }

            return classes.join( ' ' );
        }

        function getFileId( file ) {
            return 'file-' + file.id;
        }

        function onFileUploaded( event, pluploadFile, fileInfo ) {
            vm.files.push( new FileViewModel( fileInfo ) );
        }

        function onFileThumbnailUpdated( event, pluploadFile, fileInfo, thumbnailUrl ) {
            $.each( vm.files(), function( index, file ) {
                if ( file.id === fileInfo.id ) {
                    file.thumbnailUrl( thumbnailUrl );
                }
            } );
        }
    };

    return MediaManager;
} );
