/* global AWPCP */

AWPCP.define( 'awpcp/file-view-model', [ 'knockout' ],
function( ko ) {
    var FileViewModel = function( file ) {
        var vm = this;

        vm.id = file.id;
        vm.name = file.name;
        vm.listingId = file.listingId;
        vm.mimeType = file.mimeType;

        vm.enabled = ko.observable( !! parseInt( file.enabled, 10 ) );
        vm.status = ko.observable( file.status );

        vm.isPrimary = ko.observable( !! file.isPrimary );
        vm.isApproved = ko.computed( isApproved );
        vm.isRejected = ko.computed( isRejected );
        vm.isAwaitingApproval = ko.computed( isAwaitingApproval );

        vm.isBeingModified = ko.observable( false );

        vm.thumbnailUrl = ko.observable( file.thumbnailUrl );
        vm.iconUrl = file.iconUrl;
        vm.url = file.url;

        vm.progress = ko.observable( file.progress ? parseInt( file.progress, 10 ) : null );
        vm.shouldShowProgressBar = ko.computed( shouldShowProgressBar );

        function isApproved() {
            return vm.status() === 'Approved';
        }

        function isRejected() {
            return vm.status() === 'Rejected';
        }

        function isAwaitingApproval() {
            return vm.status() === 'Awaiting-Approval';
        }

        function shouldShowProgressBar() {
            return vm.progress() !== null;
        }
    };

    return FileViewModel;
} );
