/*
  Copyright (C) <2011>  Vasyl Martyniuk  <whimba@gmail.com>

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

function aamms_multisite_support_object(){}

aamms_multisite_support_object.prototype.prepareURL = function(){
    var c_url = jQuery.url();
    //add site attribute
    c_url.sparam('site', jQuery('#site').val());
    
    return c_url.compileURL();
}

aamms_multisite_support_object.prototype.redirect = function(){
    
    window.location = this.prepareURL();
}

jQuery(document).ready(function(){
    
    aamms_multisite_supportObj = new aamms_multisite_support_object();
    
    jQuery('.change-site').bind('click', function(e){
        e.preventDefault();
        jQuery('div #site-select').show();
        jQuery(this).hide();
    });
    
    jQuery('#site-ok').bind('click', function(e){
        e.preventDefault();
        if (mObj.formChanged > 0){
            jQuery( "#leave-confirm" ).dialog({
                resizable: false,
                height: 180,
                modal: true,
                buttons: {
                    "Change Site": function() {
                        jQuery( this ).dialog( "close" );
                        aamms_multisite_supportObj.redirect();
                    },
                    Cancel: function() {
                        jQuery( this ).dialog( "close" );
                    }
                }
            }); 
        }else{
            aamms_multisite_supportObj.redirect();
        }
    });
        
    jQuery('#site-cancel').bind('click', function(e){
        e.preventDefault();
        jQuery('div #site-select').hide();
        jQuery('.change-site').show();
    });
    
    jQuery('#site').bind('change', function(e){
        mObj.formChanged--; 
    });
    
    jQuery('#add-user-toblog').bind('click', function(event){
        event.preventDefault();
        
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'add_blog_admin',
            '_ajax_nonce': wpaccessLocal.nonce
        };
        jQuery.post(wpaccessLocal.ajaxurl, params, function(data){  
            if (data.status == 'success'){
                window.location.reload(true);
                mObj.success_message(data.message);
            }else{
                mObj.failed_message(data.message);
            }
        },' json');
    });
    
});