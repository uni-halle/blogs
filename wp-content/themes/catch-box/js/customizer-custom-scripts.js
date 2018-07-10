/**
 * Theme Customizer custom scripts
 * Control of show/hide events on feature slider type selection
 */
(function($) {

    wp.customize( 'catchbox_theme_options[reset_all_settings]', function( setting ) {
        setting.bind( function( setting_value ) {
            var code = 'needs_refresh';
            if ( setting_value ) {
                setting.notifications.add( code, new wp.customize.Notification(
                    code,
                    {
                        type: 'info',
                        message: catchbox_misc_links.reset_message
                    }
                ) );
            } else {
                setting.notifications.remove( code );
            }
        } );
    } );
})(jQuery);

//Change value of hidden field below custom checkboxes
jQuery( document ).ready( function() {
    jQuery( '.customize-control-catchbox_custom_checkbox input[type="checkbox"]' ).on(
        'change',
        function() {
        	checkbox_value = "0";

            if ( jQuery( this ).is(":checked") ) {
            	checkbox_value = "1";
            }

            jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_value ).trigger( 'change' );
        }
    );

} ); // jQuery( document ).ready