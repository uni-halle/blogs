/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

jQuery(document).ready(function() {

    /**
     * Highlight the specified DOM area
     *
     * @param {String} selector
     * @param {String} status
     *
     * @returns {void}
     *
     * @access public
     */
    function highlight(selector, status) {
        if (status === 'success') {
            jQuery(selector).effect("highlight", {
                color: '#98CE90'
            }, 3000);
        } else {
            jQuery(selector).effect("highlight", {
                color: '#FFAAAA'
            }, 3000);
        }
    }

    var editor = CodeMirror.fromTextArea(
            document.getElementById("configpress"), {}
    );

    jQuery('#save_config').bind('click', function(event) {
        event.preventDefault();
        jQuery.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'saveConfigpress',
                config: editor.getValue(),
                _ajax_nonce: aamLocal.nonce
            },
            success: function(response) {
                highlight('#control_panel', response.status);
            },
            error: function() {
                highlight('#control_panel', 'failure');
            }
        });
    });
    
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