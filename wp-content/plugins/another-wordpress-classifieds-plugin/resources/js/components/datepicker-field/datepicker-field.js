/*global AWPCP*/
AWPCP.define( 'awpcp/datepicker-field', [ 'jquery', 'awpcp/settings' ],
function( $, settings ) {
    var DatepickerField = function( element ) {
        var self = this, options;

        self.element = $(element);

        options = $.extend( {}, settings.l10n( 'datepicker' ), {
            dateFormat: settings.get('date-format'),
            altField: self.element,
            altFormat: 'yy/mm/dd'
        } );

        self.element.parent().find( '[datepicker-placeholder]' ).datepicker( options );
    };

    return DatepickerField;
} );
