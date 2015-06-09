/* jshint latedef: false */
/* global AWPCP, plupload */

AWPCP.define( 'awpcp/media-uploader', [ 'jquery', 'awpcp/settings' ],
function( $, settings) {
    var MediaUploader = function( element, options ) {
        var self = this;

        self.element = $( element );
        self.options = options;

        insertOrUpdateUploadRestrictionsMessage();
        configurePluploadQueue();

        $.subscribe( '/file/deleted', onFileDeleted );

        function insertOrUpdateUploadRestrictionsMessage() {
            var message = '', titles = [], replacements = {}, files_left = 0, max_file_size = 0;

            settings.l10n( 'media-uploader-strings', 'upload-restrictions' );

            $.each( self.options.allowed_files, function( title, group ) {
                files_left = ( group.allowed_file_count - group.uploaded_file_count );

                if ( files_left > 0 ) {
                    titles.push( title );

                    max_file_size = group.max_file_size / 1000000; // max file size in MB
                    max_file_size = Math.round( max_file_size * 10 ) / 10; // max file size rounded to one decimal place

                    replacements['<' + title + '-left>'] = '<strong>' + files_left + '</strong>';
                    replacements['<' + title + '-max-file-size>'] = '<strong>' + max_file_size + ' MB</strong>';
                }
            } );

            message = settings.l10n( 'media-uploader-strings', 'upload-restrictions-' + titles.sort().join( '-' ) );

            $.each( replacements, function( search, replacement ) {
                message = message.replace( search, replacement );
            } );

            self.element.find( '.awpcp-media-uploader-upload-restrictions' ).html( message );
        }

        function configurePluploadQueue() {
            plupload.addFileFilter( 'restrict_file_size', filterFileBySize );
            plupload.addFileFilter( 'restrict_file_count', filterFileByCount );

            // the second call to pluploadQueue is to retrieve a reference to
            // the plupload.Uploader object.
            self.uploader = self.element.find( '.awpcp-media-uploader-queue' )
                .pluploadQueue( {
                    url: settings.get( 'ajaxurl' ),
                    filters: {
                        mime_types: getFileTypeFilters(),
                        restrict_file_size: true,
                        restrict_file_count: true
                    },
                    multipart_params: {
                        action: 'awpcp-upload-listing-media',
                        listing: options.listing_id,
                        nonce: options.nonce
                    },
                    multi_selection: false,
                    chunk_size: '10000000',
                    runtimes: 'html5,flash,silverlight,html4',
                    multiple_queues: true,
                    flash_swf_url : options.flash_swf_url,
                    silverlight_xap_url : options.silverlight_xap_url,
                    init: {
                        FilesAdded: onFilesAdded,
                        FileUploaded: onFileUplaoded,
                        FilesRemoved: onFilesRemoved
                    }
                } )
                .pluploadQueue();
        }

        function filterFileBySize( enabled, file, done ) {
            var group = getFileGroup( file ), message;

            if ( group === null ) {
                return done( false );
            }

            if ( file.size > group.max_file_size ) {
                message = settings.l10n( 'media-uploader-strings', 'file-is-too-large' );
                message = message.replace( '<filename>', '<strong>' + file.name + '</strong>' );
                message = message.replace( '<bytes-count>', '<strong>' + group.max_file_size + '</strong>' );

                $.publish( '/messages/media-uploader', { type: 'error', 'content': message } );

                return done( false );
            }

            return done( true );
        }

        function getFileGroup( file ) {
            var fileGroup = null;

            $.each( self.options.allowed_files, function( title, group ) {
                if ( $.inArray( file.type, group.mime_types ) !== -1 ) {
                    fileGroup = group;
                    return false; // break
                }
            } );

            return fileGroup;
        }

        function filterFileByCount( enabled, file, done ) {
            var group = getFileGroup( file ), message;

            if ( group === null ) {
                return done( false );
            }

            if ( group.uploaded_file_count >= group.allowed_file_count ) {
                message = settings.l10n('media-uploader-strings', 'cannot-add-more-files' );
                message = message.replace( '<filename>', '<strong>' + file.name + '</strong>' );

                $.publish( '/messages/media-uploader', { type: 'error', 'content': message } );

                return done( false );
            }

            group.uploaded_file_count = group.uploaded_file_count + 1;

            return done( true );
        }

        function getFileTypeFilters() {
            return $.map( self.options.allowed_files, function( group, title ) {
                return { title: title.substr( 0, 1 ).toUpperCase() + title.substr( 1 ), extensions: group.extensions.join( ',' ) };
            } );
        }

        function onFilesAdded( uploader, files ) {
            $.each( files, function( index, file ) {
                $.publish( '/file/added', file );
            } );
        }

        function onFileUplaoded( uploader, file, data ) {
            var response = $.parseJSON( data.response );

            if ( response.status === 'ok' && response.file ) {
                $.publish( '/file/uploaded', [ file, response.file ] );
                insertOrUpdateUploadRestrictionsMessage();
            } else if ( response.status !== 'ok' ) {
                file.status = plupload.FAILED;
                // to force the queue widget to update the icon and the uploaded files count
                self.uploader.trigger( 'UploadProgress', file );

                $.publish( '/messages/media-uploader', { type: 'error', 'content': response.errors.join( ' ' ) } );
            }
        }

        function onFilesRemoved( uploader, files ) {
            $.each( files, function( index, file ) {
                decreaseFileCountInGroup( file );
            } );

            insertOrUpdateUploadRestrictionsMessage();
        }

        function onFileDeleted( event, file ) {
            decreaseFileCountInGroup( file );
            insertOrUpdateUploadRestrictionsMessage();
        }

        function decreaseFileCountInGroup( file ) {
            var group = getFileGroup( file );

            if ( group !== null ) {
                group.uploaded_file_count = group.uploaded_file_count - 1;
            }
        }
    };

    return MediaUploader;
} );
