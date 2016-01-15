/* jshint latedef: false */
/* global AWPCP, Backbone, _ */

Backbone.View.prototype._super = function( funcName ) {
  return this.constructor.__super__[ funcName ].apply( this, _.rest( arguments ) );
};

AWPCP.define( 'awpcp/media-uploader-view', [ 'jquery' ],
function() {
    var MediaUploaderView = Backbone.View.extend({
        initialize: function() {
            var self = this.render();

            self.$dropzone = self.$('.awpcp-media-uploader-dropzone');
            self.$browseButton = self.$('.awpcp-media-uploader-browser-button');
            self.$restrictions = self.$('.awpcp-media-uploader-restrictions');

            // couldn't make it work using View's events property :(
            self.$dropzone.on( 'dragover', _.bind( self.onDragOver, self ) );
            self.$dropzone.on( 'dragleave', _.bind( self.onDragLeave, self ) );
            self.$dropzone.on( 'drop', _.bind( self.onDragStop, self ) );

            self.model.prepareUploader( self.$el, self.$dropzone, self.$browseButton );
        },

        render: function() {
            var self = this;
            return self;
        },

        onDragOver: function() {
            this.$dropzone.addClass( 'awpcp-media-uploader-dropzone-active' );
        },

        onDragLeave: function() {
            this.$dropzone.removeClass( 'awpcp-media-uploader-dropzone-active' );
        },

        onDragStop: function() {
            this.$dropzone.removeClass( 'awpcp-media-uploader-dropzone-active' );
        }
    });

    return MediaUploaderView;
} );
