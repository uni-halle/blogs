/* jshint latedef: false */
/* global AWPCP, plupload, Backbone, _ */

AWPCP.define( 'awpcp/media-uploader', [ 'jquery', 'awpcp/settings' ],
function( $, settings) {
    var MediaUploader = Backbone.Model.extend({
        initialize: function() {
            $.subscribe( '/file/deleted', _.bind( this.onFileDeleted, this ) );
        },

        prepareUploader: function( container, dropzone, browseButton ) {
            var self = this;

            plupload.addFileFilter( 'restrict_file_size', _.bind( self.filterFileBySize, self ) );
            plupload.addFileFilter( 'restrict_file_count', _.bind( self.filterFileByCount, self ) );

            self.uploader = new plupload.Uploader({
                browse_button: browseButton.get(0),
                url: settings.get( 'ajaxurl' ),
                container: container.get(0),
                drop_element: dropzone.get(0),
                filters: {
                    mime_types: _.bind( self.getFileTypeFilters, self ),
                    restrict_file_size: true,
                    restrict_file_count: true
                },
                multipart_params: {
                    action: 'awpcp-upload-listing-media',
                    listing: self.get('settings').listing_id,
                    context: self.get( 'settings' ).context,
                    nonce: self.get('settings').nonce
                },
                multi_selection: false,
                chunk_size: '10000000',
                runtimes: 'html5,flash,silverlight,html4',
                flash_swf_url : self.get('settings').flash_swf_url,
                silverlight_xap_url : self.get('settings').silverlight_xap_url
            });

            self.uploader.init();

            self.uploader.bind( 'FilesAdded', self.onFilesAdded, self );
            self.uploader.bind( 'UploadProgress', self.onUploadProgress, self );
            self.uploader.bind( 'FileUploaded', self.onFileUplaoded, self );
        },

        filterFileBySize: function( enabled, file, done ) {
            var self = this,
                group = self.getFileGroup( file ), message;

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
        },

        getFileGroup: function( file ) {
            var self = this,
                fileGroup = null;

            $.each( self.get( 'settings' ).allowed_files, function( title, group ) {
                if ( $.inArray( file.type, group.mime_types ) !== -1 ) {
                    fileGroup = group;
                    return false; // break
                }
            } );

            return fileGroup;
        },

        filterFileByCount: function( enabled, file, done ) {
            var self = this,
                group = self.getFileGroup( file ), message;

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
        },

        getFileTypeFilters: function() {
            var self = this;

            return $.map( self.get('settings').allowed_files, function( group, title ) {
                return { title: title.substr( 0, 1 ).toUpperCase() + title.substr( 1 ), extensions: group.extensions.join( ',' ) };
            } );
        },

        onFilesAdded: function( uploader, files ) {
            $.each( files, function( index, file ) {
                $.publish( '/file/added', file );
            } );

            uploader.start();
        },

        onUploadProgress: function( uploader, file ) {
            $.publish( '/file/progress', [ uploader, file ] );
        },

        onFileUplaoded: function( uploader, file, data ) {
            var response = $.parseJSON( data.response );

            if ( response.status === 'ok' && response.file ) {
                $.publish( '/file/uploaded', [ file, response.file ] );
            } else if ( response.status !== 'ok' ) {
                this.decreaseFileCountInGroup( file );
                file.status = plupload.FAILED;

                // to force the queue widget to update the icon and the uploaded files count
                this.uploader.trigger( 'UploadProgress', file );

                $.publish( '/file/failed', file );
                $.publish( '/messages/media-uploader', { type: 'error', 'content': response.errors.join( ' ' ) } );
            }

            this.trigger('media-uploader:file-uploaded');
        },

        onFileDeleted: function( event, file ) {
            this.decreaseFileCountInGroup( file );
            this.trigger('media-uploader:file-deleted');
        },

        decreaseFileCountInGroup: function( file ) {
            var group = this.getFileGroup( file );

            if ( group !== null ) {
                group.uploaded_file_count = group.uploaded_file_count - 1;
            }
        },

        buildUploadRestrictionsMessage: function() {
            var self = this,
                message = '',
                titles = [],
                replacements = {},
                files_left = 0,
                max_file_size = 0;

            settings.l10n( 'media-uploader-strings', 'upload-restrictions' );

            $.each( self.get('settings').allowed_files, function( title, group ) {
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

            return message;
        }
    });

    return MediaUploader;
} );
