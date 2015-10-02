/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

jQuery(document).ready(function() {

    jQuery('#info_screen').bind('click', function(event){
        event.preventDefault();
        jQuery('#configpress_area').hide();
        jQuery('#configpress_info').show();
        jQuery(this).hide();
        jQuery('#configpress_screen').show();
    });
    
    jQuery('#configpress_screen').bind('click', function(event){
        event.preventDefault();
        jQuery('#configpress_area').show();
        jQuery('#configpress_info').hide();
        jQuery(this).hide();
        jQuery('#info_screen').show();
    });
});