/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
*/

function AAMSecurity() {

}

AAMSecurity.prototype.init = function() {
    var _this = this;
    
    if (jQuery('#country_list').length) {
        jQuery('#country_list').dataTable({
            sDom: "t",
            bAutoWidth: false,
            bSort: false,
            aoColumnDefs: [
                {
                    sClass: 'center',
                    aTargets: [1]
                }
            ]
        });
    }
    
    jQuery('.aam-icon', '.large-icons-row').each(function(){
        jQuery(this).bind('click', function(){
            _this.switchMode(jQuery(this).attr('mode'));
        });
    });
    jQuery('#setting_trigger_inline').bind('click', function(event){
        event.preventDefault();
        _this.switchMode('settings');
    });
};

AAMSecurity.prototype.switchMode = function(mode) {
    jQuery('.mode-container').hide();
    jQuery('#' + mode + '_mode').show();
};

jQuery(document).ready(function() {
    var security = new AAMSecurity();
    security.init();
});