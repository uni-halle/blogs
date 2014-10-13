/*global AWPCP*/
AWPCP.define( 'awpcp/file', [ 'knockout' ], function( ko ) {
    var File = function( file, primaryImageId ) {
        var self = this;

        self.id = file.id;
        self.name = file.name;
        self.listing_id = file.listing_id;
        self.thumbnailUrl = file.thumbnailUrl;
        self.iconUrl = file.iconUrl;
        self.url = file.url;

        self.enabled = ko.observable( parseInt( file.enabled, 10 ) );
        self.isImage = file.isImage;
        self.status = ko.observable( file.status );

        self.isPrimaryImage = ko.computed( function() {
            return primaryImageId() === self.id;
        } );

        self.getFileClasses = ko.computed( function() {
            var classes = [];

            if ( self.isPrimaryImage() ) {
                classes.push( 'primary-image' );
            }

            classes.push( self.enabled() ? 'enabled' : 'disabled' );
            classes.push( self.status().toLowerCase() );

            return classes.join( ' ' );
        } );

        self.getFileId = ko.computed( function() {
            return 'file-' + self.id;
        } );
    };

    return File;
} );
