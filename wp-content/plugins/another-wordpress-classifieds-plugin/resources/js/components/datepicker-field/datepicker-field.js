/*global AWPCP*/
AWPCP.define( 'awpcp/datepicker-field', [ 'jquery', 'awpcp/settings' ],
function( $, settings ) {
    var DatepickerField = function( element, options ) {
        var self = this;

        self.element = $(element);
        self.textfield = self.element.parent().find( '[datepicker-placeholder]' );

        self.textfield.datepicker( $.extend( {},
            settings.l10n( 'datepicker' ),
            {
                dateFormat: settings.get('date-format'),
                altField: self.element,
                altFormat: 'yy/mm/dd'
            },
            options ? options.datepicker : {}
        ) );
    };

    return DatepickerField;
} );
