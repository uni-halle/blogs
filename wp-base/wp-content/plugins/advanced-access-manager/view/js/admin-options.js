/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

function aamObject(){

    /**
     * Hold Main Menu Sorting Status
     *
     * @var string
     */
    this.sort_status = 'passive';

    /**
     * Form Status
     *
     * @var string
     */
    this.form_status = '';

    /**
     * ConfigPress holder
     *
     * @var object
     */
    this.editor = null;

    /**
     * Show Confirm Apply to All User Role
     *
     * @var boolean
     */
    this.hide_apply_all = parseInt(aamLocal.hide_apply_all);

    /**
     * Hooks for other add-ons
     *
     * @var object
     */
    this.hooks = {
        'tabs-loaded' : []
    };


    /*
     * Array of pre-defined capabilities for default WP roles
     *
     * @var array
     * @access private
     */
    this.default_capset = {
        radio2 : ['moderate_comments', 'manage_categories', 'manage_links',
        'upload_files', 'unfiltered_html', 'edit_posts', 'edit_others_posts',
        'edit_published_posts', 'publish_posts', 'edit_pages', 'read', 'level_7',
        'level_6', 'level_5', 'level_4', 'level_3', 'level_2', 'level_1',
        'level_0', 'edit_others_pages', 'edit_published_pages', 'publish_pages',
        'delete_pages', 'delete_others_pages', 'delete_published_pages',
        'delete_posts', 'delete_others_posts', 'delete_published_posts',
        'delete_private_posts', 'read_private_posts', 'delete_private_pages',
        'edit_private_pages', 'read_private_pages'],
        radio3 : ['upload_files', 'edit_posts', 'edit_others_posts',
        'edit_published_posts', 'publish_posts', 'read', 'level_2', 'level_1',
        'level_0', 'delete_posts', 'delete_published_posts'],
        radio4 : ['edit_posts', 'read', 'level_1', 'level_0', 'delete_posts'],
        radio5 : ['read', 'level_0']
    }

}

/*
 * ===========================
 * ******* MAIN MENU *********
 * ===========================
 */

/**
 * Initalize GUI element for Main Menu Tab
 *
 * @version 1.0
 * @access public
 */
aamObject.prototype.initMainMenuTab = function(){

    var _this = this;

    _this.initAccordion(jQuery('.main-menu-accordion'));

    jQuery('.main-menu-accordion > div').each(function(){
        jQuery('#whole', this).bind('click',{
            _this: this
        }, function(event){
            var checked = (jQuery(this).attr('checked') ? true : false);
            jQuery('input:checkbox', event.data._this).attr('checked', checked);
        });
    });

    jQuery('.reorganize-menu').bind('click', function(event){
        event.preventDefault();
        var helper = jQuery('.main-menu-help');

        if (_this.sort_status != 'passive'){
            //save confirmation message
            if (_this.sort_status == 'sorted'){
                if (parseInt(jQuery('#current_user').val())){
                    _this.saveMenuOrder(false);
                }else{
                    var text = aamLocal.LABEL_100.replace(
                        '%s', jQuery('#current-role-display').html()
                        );
                    var config = {
                        resizable: false,
                        height:165,
                        modal: true,
                        title : aamLocal.LABEL_99,
                        buttons: {}
                    }
                    config.buttons[aamLocal.LABEL_130] = function() {
                        _this.saveMenuOrder(false);
                        jQuery( this ).dialog('close');
                    }
                    config.buttons[aamLocal.LABEL_131] = function() {
                        _this.saveMenuOrder(true);
                        jQuery( this ).dialog('close');
                    }
                    _this.showGeneralDialog(config, text);
                }
            }
            _this.sort_status = 'passive';
            _this.changeText(jQuery('.help1', helper), aamLocal.LABEL_172);
            _this.changeText(jQuery('.reorganize-menu', helper), aamLocal.LABEL_12);
            _this.initAccordion(jQuery('.main-menu-accordion'));
        }else{
            _this.changeText(jQuery('.help1', helper), aamLocal.LABEL_11);
            _this.changeText(jQuery('.reorganize-menu', helper), aamLocal.LABEL_173);
            _this.initAccordion(jQuery('.main-menu-accordion'), true);
            _this.sort_status = 'active';
        }
    });
}

aamObject.prototype.saveMenuOrder = function(apply_all){

    var _this = this;
    var params = {
        'action' : 'mvbam',
        'sub_action' : 'save_order',
        '_ajax_nonce': aamLocal.nonce,
        'apply_all' : (apply_all ? 1 : 0),
        'role' : jQuery('#current_role').val(),
        'user' : jQuery('#current_user').val(),
        'menu' : new Array()
    }
    //get list of menus in proper order
    jQuery('.main-menu-accordion > div').each(function(){
        params.menu.push(jQuery(this).attr('id'));
    });

    jQuery.post(aamLocal.ajaxurl, params, function(data){
        if (data.status == 'success'){
            if (_this.form_status == 'submitted'){
                jQuery('#aam-form').submit();
            }
        }else{
            _this.showErrorMessage(data.message);
        }
    }, 'json');
}

/*
 * ===========================
 * *** METABOXES & WIDGETS ***
 * ===========================
 */
aamObject.prototype.initMetaboxTab = function(){

    var _this = this;
    _this.initAccordion(jQuery('.metabox-accordion'));

    jQuery('.refresh-metagox-list').bind('click', function(event){
        event.preventDefault();
        jQuery('.mbox').hide();
        jQuery('#progressbar').progressbar({
            value: 0
        }).show();
        _this.runChain('');
    });

    jQuery('.initialize-url').bind('click', function(event){
        event.preventDefault();
        var val = jQuery('.initialize-url-text').val();
        if (jQuery.trim(val)){
            jQuery('.mbox').hide();
            jQuery('#progressbar').progressbar({
                value: 20
            }).show();
            var params = {
                'action' : 'mvbam',
                'sub_action' : 'initiate_url',
                '_ajax_nonce': aamLocal.nonce,
                'url' : val
            };
            jQuery.post(aamLocal.ajaxurl, params, function(data){
                jQuery('.initialize-url-text').val('');
                if (data.status == 'success'){
                    jQuery('#progressbar').progressbar('option', 'value', 100);
                    _this.grabMetaboxes();
                }else{
                    _this.emergencyCall(data);
                }
            }, 'json');
        }else{
            jQuery('.initialize-url-text').effect('highlight',3000);
        }
    });

}

aamObject.prototype.runChain = function(next){

    var _this = this;
    var params = {
        'action' : 'mvbam',
        'sub_action' : 'initiate_wm',
        '_ajax_nonce': aamLocal.nonce,
        'next' : next
    };

    jQuery.post(aamLocal.ajaxurl, params, function(data){
        jQuery('#progressbar').progressbar("option", "value", data.value);
        if (data.status == 'success'){
            if (data.next){
                _this.runChain(data.next);
            }else{
                jQuery('#progressbar').progressbar("option", "value", 100);
                _this.grabMetaboxes();
            }
        }else{ //try directly to go to that page
            _this.emergencyCall(data);
        }
    }, 'json');
}

aamObject.prototype.grabMetaboxes = function(){

    var _this = this;
    jQuery('#progressbar').progressbar('destroy').hide();
    jQuery('.metabox-help').show();
    jQuery('.metabox-accordion').empty().css({
        'height' : '200px',
        'width' : '100%'
    });
    this.showAjaxLoader('.metabox-accordion');
    var params = {
        'action' : 'mvbam',
        'sub_action' : 'render_metabox_list',
        '_ajax_nonce': aamLocal.nonce,
        'role' : jQuery('#current_role').val(),
        'user' : jQuery('#current_user').val()
    };

    jQuery.post(aamLocal.ajaxurl, params, function(data){
        if (data.status == 'success'){
            jQuery('#tabs-2').html(data.html);
            _this.initMetaboxTab();
        }else{
            _this.showErrorMessage(aamLocal.LABEL_25);
        }
    }, 'json');
}

aamObject.prototype.emergencyCall = function(data){

    var _this = this;
    jQuery.ajax(data.url, {
        success : function(){
            if (data.next){
                _this.runChain(data.next);
            }else{
                jQuery('#progressbar').progressbar("option", "value", 100);
                _this.grabMetaboxes();
            }
        },
        error : function(jqXHR, textStatus, errorThrown){
            if (textStatus != 'timeout'){
                if (data.next){
                    _this.runChain(data.next);
                }else{
                    jQuery('#progressbar').progressbar("option", "value", 100);
                    _this.grabMetaboxes();
                }
            }else{
                _this.showErrorMessage(aamLocal.LABEL_133);
                jQuery('#progressbar').progressbar("option", "value", 100);
                _this.grabMetaboxes();
            }
        },
        timeout : 5000
    });
}

/*
 * ===========================
 * ****** CAPABILITIES *******
 * ===========================
 */
aamObject.prototype.initCapabilityTab = function(){

    var _this = this;

    jQuery('.default-cap-set > a').each(function(){
        var id = jQuery(this).attr('id');
        jQuery(this).bind('click', function(event){
            event.preventDefault();
            _this.changeCapabilities(id);
        })
    });

    jQuery('#new-capability').bind('click', function(event){
        event.preventDefault();
        _this.addNewCapability();
    });

    //add user to blog
    //TODO - probably delete
    jQuery('#add-user-toblog').bind('click', function(){
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'add_blog_admin',
            '_ajax_nonce': aamLocal.nonce
        };
        jQuery.post(aamLocal.ajaxurl, params, function(data){
            if (data.status != 'success'){
                _this.showErrorMessage(data.message);
            }
        }, 'json');
    });
}

aamObject.prototype.changeCapabilities = function(type){

    switch(type){
        case 'radio1': //administrator
            jQuery('.capability-item input').attr('checked', true);
            break;

        case 'radio2': //editor
        case 'radio3': //author
        case 'radio4': //contributor
        case 'radio5': //subscriber
            jQuery('.capability-item input').attr('checked', false);
            var s = '';
            for (var c in this.default_capset[type]){
                s  = '.capability-item input[name*="[';
                s += this.default_capset[type][c]+']"]';
                jQuery(s).attr('checked', true);
            }
            break;

        case 'radio6': //clear all
            jQuery('.capability-item input').attr('checked', false);
            break;

        default:
            break;
    }
}

aamObject.prototype.addNewCapability = function(){

    var _this = this;
    jQuery( "#capability-form #new-cap" ).val('').focus();
    var pa = {
        resizable: false,
        height: 'auto',
        modal: true,
        buttons: {}
    }
    pa.buttons[aamLocal.LABEL_132] = function() {
        var cap = jQuery( "#capability-form #new-cap" ).val();
        if (jQuery.trim(cap)){
            jQuery( this ).dialog( "close" );
            var params = {
                'action' : 'mvbam',
                'sub_action' : 'add_capability',
                '_ajax_nonce': aamLocal.nonce,
                'cap' : cap,
                'role' : jQuery('#current_role').val(),
                'user' : jQuery('#current_user').val()
            };
            jQuery.post(aamLocal.ajaxurl, params, function(data){
                if (data.status == 'success'){
                    jQuery('.capability-item:last').after(data.html);
                }else{
                    _this.showErrorMessage(data.message);
                }
            }, 'json');
        }else{
            jQuery( "#capability-form #new-cap" ).effect('highlight', 5000);
        }
    }
    pa.buttons[aamLocal.LABEL_77] = function() {
        jQuery( this ).dialog( "close" );
    }
    jQuery( "#capability-form" ).dialog(pa);
}

aamObject.prototype.deleteCapability = function(cap, label){

    var _this = this;
    var config = {
        resizable: false,
        height:180,
        modal: true,
        title : aamLocal.LABEL_101,
        buttons: {}
    }
    config.buttons[aamLocal.LABEL_24] = function() {
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'delete_capability',
            '_ajax_nonce': aamLocal.nonce,
            'cap' : cap
        };
        jQuery.post(aamLocal.ajaxurl, params, function(data){
            if (data.status == 'success'){
                jQuery('#cap-' + cap).parent().parent().remove();
            }else{
                _this.showErrorMessage(data.message);
            }
        }, 'json');
        jQuery( this ).dialog( "close" );
    }
    config.buttons[aamLocal.LABEL_77] = function() {
        jQuery( this ).dialog( "close" );
    }

    _this.showGeneralDialog(config, aamLocal.LABEL_102.replace('%s', label));
}


/*
 * ===========================
 * *** POSTS & TAXONOMIES ****
 * ===========================
 */
aamObject.prototype.initPostTaxonomyTab = function(){

    var _this = this;
    _this.initPostTaxonomyTree();

    jQuery('#sidetreecontrol span').bind('click', function(){
        jQuery("#tree").replaceWith('<ul id="tree" class="filetree"></ul>');
        _this.initPostTaxonomyTree();
    });
}

aamObject.prototype.initPostTaxonomyTree = function(){

    jQuery("#tree").treeview({
        url: aamLocal.ajaxurl,
        // add some additional, dynamic data and request with POST
        ajax: {
            data : {
                action: "mvbam",
                sub_action : 'get_treeview',
                '_ajax_nonce': aamLocal.nonce
            },
            type : 'post'
        },
        animated: "medium",
        control:"#sidetreecontrol",
        persist: "location"
    });
}

aamObject.prototype.loadInfo = function(event, type, id, restore){

    var _this = this;
    if (typeof(event.preventDefault) != 'undefined'){ //for IE
        event.preventDefault();
    }else{
        event.returnValue = false;
    }
    this.showAjaxLoader('.post-information', 'small');

    var params = {
        'action' : 'mvbam',
        'sub_action' : 'get_info',
        '_ajax_nonce': aamLocal.nonce,
        'type' : type,
        'role' : jQuery('#current_role').val(),
        'user' : jQuery('#current_user').val(),
        'id' : id,
        'restore' : restore
    }

    jQuery.post(aamLocal.ajaxurl, params, function(data){
        _this.removeAjaxLoader('.post-information', 'small');
        if (data.status == 'success'){
            var pi = jQuery('.post-information');
            jQuery('#post-date', pi).html(data.html);

            jQuery('.save-postinfo', pi).bind('click', function(event){
                event.preventDefault();
                _this.saveInfo(_this, pi, type, id, 0);
            });

            jQuery('.save-postinfo-all', pi).bind('click', function(event){
                event.preventDefault();
                if (parseInt(jQuery('#current_user').val()) == 0){
                    if (_this.hide_apply_all){
                        _this.saveInfo(_this, pi, type, id, 1);
                    }else{
                        var pa = {
                            resizable: false,
                            height:204,
                            width: 320,
                            modal: true,
                            buttons: {}
                        }
                        pa.buttons[aamLocal.LABEL_137] = function() {
                            _this.saveInfo(_this, pi, type, id, 1);
                            _this.hide_apply_all = (jQuery('#hide-apply-all').attr('checked') ? 1 : 0);
                            jQuery( this ).dialog( "close" );
                        }

                        pa.buttons[aamLocal.LABEL_77] = function() {
                            jQuery( this ).dialog( "close" );
                        }
                        //save information
                        jQuery( "#dialog-apply-all" ).dialog(pa);
                    }
                }
            });

            jQuery('#frontend_browse').bind('change', function(){
                if (jQuery(this).attr('checked')){
                    jQuery('.frontend-browse').attr('checked', true);
                }else{
                    jQuery('.frontend-browse').attr('checked', false);
                }
            });
            jQuery('#backend_browse').bind('change', function(){
                if (jQuery(this).attr('checked')){
                    jQuery('.backend-browse').attr('checked', true);
                }else{
                    jQuery('.backend-browse').attr('checked', false);
                }
            });
            jQuery('#post_in_category').bind('change', function(){
                var checked = jQuery(this).attr('checked');
                if (checked){
                    jQuery('.incat-check').attr('disabled', false);
                }else{
                    jQuery('.incat-check').attr('disabled', true);
                }
            });

            if (!jQuery('#post_in_category').attr('checked')){
                jQuery('.incat-check').attr('disabled', true);
            }

            jQuery('.restore-postinfo', pi).bind('click', function(event){
                event.preventDefault();
                var config = {
                    resizable: false,
                    height: 'auto',
                    modal: true,
                    title : aamLocal.LABEL_177,
                    buttons: {}
                }
                config.buttons[aamLocal.LABEL_130] = function() {
                    _this.loadInfo(event, type, id, true);
                    jQuery( this ).dialog( "close" );
                }

                config.buttons[aamLocal.LABEL_77] = function() {
                    jQuery( this ).dialog( "close" );
                }
                _this.showGeneralDialog(config, aamLocal.LABEL_176)
            });

        }else{
            //TODO - Implement error
            alert(aamLocal.LABEL_138);
        }
    },'json');
}

aamObject.prototype.saveInfo = function(obj, pi, type, id, apply){

    var _this = this;
    obj.showAjaxLoader('.post-information', 'small');
    this.form_status = '';

    var params = {
        'action' : 'mvbam',
        'sub_action' : 'save_info',
        '_ajax_nonce': aamLocal.nonce,
        'role' : jQuery('#current_role').val(),
        'user' : jQuery('#current_user').val(),
        'info' : {
            'id' : id,
            'type' : type,
            'data' : jQuery('.restriction-table :input').serializeArray()
        },
        'apply' : apply,
        'apply_all_cb' : this.hide_apply_all
    }

    jQuery.post(aamLocal.ajaxurl, params, function(data){
        obj.removeAjaxLoader('.post-information', 'small');
        if (data.status == 'success'){
            jQuery('#expire-success-message', pi).show().delay(5000).hide('slow');
        }else{
            jQuery('#expire-error-message', pi).show().delay(10000).hide('slow');
            var config = {
                resizable: false,
                height:190,
                modal: true,
                title : aamLocal.LABEL_114,
                buttons: {}
            }
            config.buttons[aamLocal.LABEL_76] = function() {
                jQuery( this ).dialog( "close" );
            }
            _this.showGeneralDialog(config, data.message);
        }
    }, 'json');
}

/*
 * ===========================
 * ****** CONFIG PRESS *******
 * ===========================
 */
aamObject.prototype.initConfigPressTab = function(){

    this.editor = CodeMirror.fromTextArea(document.getElementById("access_config"), {
        mode: {
            name: "ini"
        //   htmlMode: true
        },
        lineNumbers: true
    });
    jQuery('#cp-ref').bind('click', function(){
        jQuery('#cp-ref-trigger').trigger('click');
    });
}

/*
 * ===========================
 * ***** GENERAL METABOX *****
 * ===========================
 */
aamObject.prototype.initGeneralMetabox = function(){

    var _this = this;
    jQuery('.change-role').bind('click', function(e){
        e.preventDefault();
        jQuery('div #role-select').show();
        jQuery(this).hide();
    });

    jQuery('.change-user').bind('click', function(e){
        e.preventDefault();
        jQuery('div #user-select').show();
        jQuery(this).hide();
    });

    jQuery('#role-ok').bind('click', function(e){
        e.preventDefault();
        _this.changeRole();
    });

    jQuery('#user-ok').bind('click', function(e){
        e.preventDefault();
        _this.changeUser();
    });

    jQuery('#role-cancel').bind('click', function(e){
        e.preventDefault();
        jQuery('div #role-select').hide();
        jQuery('.change-role').show();
    });
    jQuery('#user-cancel').bind('click', function(e){
        e.preventDefault();
        jQuery('div #user-select').hide();
        jQuery('.change-user').show();
    });


    jQuery('.restore-conf').bind('click', function(e){
        e.preventDefault();
        _this.restoreDefault();
    });
}

aamObject.prototype.changeRole = function(){

    this.getRoleOptionList(jQuery('#role').val());
    this.toggleUserSelector();
}

aamObject.prototype.toggleUserSelector = function(){

    if (jQuery('#current_role').val() == '_visitor'){
        jQuery('.misc-user-section').hide();
    }else{
        jQuery('.misc-user-section').show();
    }
}

aamObject.prototype.changeUser = function(){
    this.getUserOptionList(jQuery('#current_role').val(), jQuery('#user').val());
}

aamObject.prototype.getRoleOptionList = function(currentRoleID){

    this.showAjaxLoader('.aam-tabs');
    var params = {
        'action' : 'render_optionlist',
        '_ajax_nonce': aamLocal.nonce,
        'role' : currentRoleID
    };
    jQuery('#current_role').val(currentRoleID);
    var _this = this;
    this.sort_status = 'passive';

    jQuery.post(aamLocal.handlerURL, params, function(data){
        if (data.status == 'success'){
            jQuery('#metabox-wpaccess-options').replaceWith(data.html);
            _this.initMainMetabox();
            jQuery('div #role-select').hide();
            jQuery('#current-role-display').html(jQuery('#role option:selected').text());
            jQuery('.change-role').show();
            //get list of users
            var params = {
                'action' : 'mvbam',
                'sub_action' : 'get_userlist',
                '_ajax_nonce': aamLocal.nonce,
                'role' : currentRoleID
            };
            jQuery('#current_user').val(0);
            jQuery.post(aamLocal.ajaxurl, params, function(data){
                if (data.status == 'success'){
                    jQuery('#user').html(data.html);
                    jQuery('div #user-select').hide();
                    jQuery('.change-user').show();
                    jQuery('#current-user-display').html(jQuery('#user option:eq(0)').text());
                }else{
                    _this.showErrorMessage('Error during User List preparation');
                }
            }, 'json');
        }else{
            _this.showErrorMessage('Error during Option List preparation');
        }
    }, 'json');
}

aamObject.prototype.getUserOptionList = function(currentRoleID, currentUserID){

    this.showAjaxLoader('.aam-tabs');

    var params = {
        'action' : 'render_optionlist',
        '_ajax_nonce': aamLocal.nonce,
        'role' : currentRoleID,
        'user' : currentUserID
    };
    jQuery('#current_user').val(currentUserID);
    var _this = this;
    //restore some params
    this.sort_status = 'passive';

    jQuery.post(aamLocal.handlerURL, params, function(data){
        if (data.status == 'success'){
            jQuery('#metabox-wpaccess-options').replaceWith(data.html);
            _this.initMainMetabox();
            jQuery('div #user-select').hide();
            jQuery('#current-user-display').html(jQuery('#user option:selected').text());
            jQuery('.change-user').show();
        }else{
            _this.showErrorMessage('Error during Option List preparation');
        }
    }, 'json');
}

aamObject.prototype.restoreDefault = function(){

    var _this = this;
    var config = {
        resizable: false,
        height:180,
        modal: true,
        title : aamLocal.LABEL_103,
        buttons: {}
    }
    config.buttons[aamLocal.LABEL_135] = function() {
        var role = jQuery('#current_role').val();
        var user = parseInt(jQuery('#current_user').val());

        var params = {
            'action' : 'mvbam',
            'sub_action' : ( user ? 'restore_user' : 'restore_role'),
            '_ajax_nonce': aamLocal.nonce,
            'role' : role,
            'user' : user
        };
        var _dialog = this;
        jQuery.post(aamLocal.ajaxurl, params, function(data){
            if (data.status == 'success'){
                if (user){
                    _this.getUserOptionList(role, user);
                }else{
                    _this.getRoleOptionList(role);
                }
            }else{
                _this.showErrorMessage(aamLocal.LABEL_136)
            }
            jQuery( _dialog ).dialog( "close" );
        },'json');
    }
    config.buttons[aamLocal.LABEL_77] = function() {
        jQuery( this ).dialog( "close" );
    }

    this.showGeneralDialog(config, aamLocal.LABEL_104);
}

aamObject.prototype.submitForm = function(){

    jQuery('#ajax-loading').show();
    var result = true;
    //check if user still reorganizing menu
    if (this.sort_status == 'sorted'){
        this.form_status = 'submitted';
        this.saveMenuOrder(false); //apply only for one role
        result = false; //wait until ordering saves
    }

    return result;
}

/*
 * ===========================
 * ****** ROLE MANAGER *******
 * ===========================
 */
aamObject.prototype.initRoleManagerMetabox = function(){

    var _this = this;
    jQuery('#role-tabs').tabs();

    this.initRoleNameList();
    jQuery('#new-role-ok').bind('click', function(e){
        e.preventDefault();
        _this.addNewRole();
    });
    jQuery('#new-role-name').keypress(function(event){
        if (event.which == 13){
            event.preventDefault();
            _this.addNewRole();
        }
    });

}

aamObject.prototype.initRoleNameList = function(){

    var _this = this;
    jQuery('.delete-role-table tbody > tr').each(function(){
        _this.initRoleNameRow(this);
    });
}

aamObject.prototype.initRoleNameRow = function(obj){

    var _this = this;
    var row_id = jQuery(obj).attr('id');
    var role_id = row_id.match(/\-([0-9a-z_]+)$/i);
    jQuery('.role-name', obj).bind('click', function(){
        var label = jQuery(this).text();
        jQuery(this).replaceWith('<input type="text" value="'+label+'" id="edit-role-name" />');
        jQuery('#edit-role-name').bind('blur', function(){
            _this.updateRoleName(role_id[1], row_id, label, this);
        });
        jQuery('#edit-role-name').keypress(function(event){
            if (event.which == 13){
                event.preventDefault();
                _this.updateRoleName(role_id[1], row_id, label, this);
            }
        });
        jQuery('#edit-role-name').focus();
    });
}

aamObject.prototype.updateRoleName = function(role_id, row_id, or_label, obj){

    var _this = this;
    var e_label = jQuery(obj).val();
    if (e_label != or_label){
        this.showAjaxLoader('.delete-role-table', 'small');
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'update_role_name',
            '_ajax_nonce': aamLocal.nonce,
            'role_id' : role_id,
            'label' : e_label
        };
        jQuery.post(aamLocal.ajaxurl, params, function(data){
            _this.removeAjaxLoader('.delete-role-table', 'small');
            if (data.status == 'success'){
                _this.UpdateRoleNameOk(e_label, row_id, role_id);
            }else{
                _this.UpdateRoleNameError(or_label, row_id, role_id);
            }
        }, 'json');
    }else{
        _this.UpdateRoleNameOk(e_label, row_id, role_id);
    }
}

aamObject.prototype.UpdateRoleNameOk = function(e_label, row_id, role_id){

    jQuery('#edit-role-name').replaceWith('<span class="role-name">'+e_label+'</span>');
    this.initRoleNameRow(jQuery('#dl-row-' + role_id));
    jQuery('#' + row_id).effect('highlight',{
        color: '#72f864'
    }, 3000);
    if (jQuery('#current_role').val() == role_id){
        jQuery('#current-role-display').html(e_label);
    }
    jQuery('#role option[value="'+role_id+'"]').text(e_label);
}

aamObject.prototype.UpdateRoleNameError = function(or_label, row_id, role_id){

    jQuery('#edit-role-name').replaceWith('<span class="role-name">'+or_label+'</span>');
    this.initRoleNameRow(jQuery('#dl-row-' + role_id));
    jQuery('#' + row_id).effect('highlight',{
        color: '#f6a499'
    }, 3000);
}

aamObject.prototype.addNewRole = function(){

    var newRoleTitle = jQuery.trim(jQuery('#new-role-name').val());
    if (!newRoleTitle){
        jQuery('#new-role-name').effect('highlight',3000);
        return;
    }
    jQuery('#new-role-name').val('');
    jQuery('.new-role-name-empty').show();
    this.showAjaxLoader('.aam-tabs');
    var params = {
        'action' : 'mvbam',
        'sub_action' : 'create_role',
        '_ajax_nonce': aamLocal.nonce,
        'role' : newRoleTitle
    };
    var _this = this;
    jQuery.post(aamLocal.ajaxurl, params, function(data){
        if (data.result == 'success'){
            var nOption = '<option value="'+data.new_role+'">'+newRoleTitle+'</option>';
            jQuery('#role option:last').after(nOption);
            jQuery('#role').val(data.new_role);
            _this.getRoleOptionList(data.new_role);
            jQuery('div #new-role-form').hide();
            jQuery('.change-role').show();
            jQuery('.delete-role-table tbody').append(data.html);
            jQuery('#new-role-message-ok').show().delay(2000).hide('slow');
            _this.initRoleNameRow(jQuery('#dl-row-' + data.new_role));
            _this.toggleUserSelector();
        }else{
            _this.removeAjaxLoader('.aam-tabs');
            _this.showErrorMessage(aamLocal.LABEL_91);
        }
    }, 'json');
}

aamObject.prototype.deleteRole = function(role){

    var message = aamLocal.LABEL_98.replace('%s', jQuery('.delete-role-table #dl-row-'+role+' td:first').html());
    var _this = this;
    var config = {
        resizable: false,
        height:180,
        modal: true,
        title : aamLocal.LABEL_97,
        buttons: {}
    }
    config.buttons[aamLocal.LABEL_134] = function() {
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'delete_role',
            '_ajax_nonce': aamLocal.nonce,
            'role' : role
        };
        jQuery.post(aamLocal.ajaxurl, params, function(){
            jQuery('.delete-role-table #dl-row-' + role).remove();
            if (jQuery('#role option[value="'+role+'"]').attr('selected')){
                _this.getRoleOptionList(jQuery('#role option:first').val());
            }
            jQuery('#role option[value="'+role+'"]').remove();
            _this.toggleUserSelector();
        });
        jQuery( this ).dialog( "close" );
    }

    config.buttons[aamLocal.LABEL_77] = function() {
        jQuery( this ).dialog( "close" );
    }
    _this.showGeneralDialog(config, message);
}


/*
 * ===========================
 * ****** MISCELANEOUS *******
 * ===========================
 */
aamObject.prototype.addHook = function(zone, callback){

    this.hooks[zone].push(callback);
}

aamObject.prototype.triggerHooks = function(){
    //execute hooks
    for(var i in this.hooks['tabs-loaded']){
        this.hooks['tabs-loaded'][i].call();
    }
}

aamObject.prototype.init = function(){

    var _this = this;
    this.initMainMetabox();
    this.initGeneralMetabox();
    this.initRoleManagerMetabox();

    if (aamLocal.first_time == 1){
        var params = {
            'action' : 'mvbam',
            'sub_action' : 'create_super',
            '_ajax_nonce': aamLocal.nonce,
            'answer' : 0
        };
        var config = {
            resizable: false,
            height: 'auto',
            width: 450,
            modal: true,
            title : aamLocal.LABEL_115,
            buttons: {}
        }
        config.buttons[aamLocal.LABEL_76] = function() {
            params.answer = 1;
            var _dialog = this;
            _this.showAjaxLoader('#dialog-firsttime');
            jQuery.post(aamLocal.ajaxurl, params, function(data){
                _this.removeAjaxLoader('#dialog-firsttime');
                if (data.result == 'success'){
                    window.location.reload(true);
                }else{
                    _this.showErrorMessage(aamLocal.LABEL_25)
                }
                jQuery( _dialog ).dialog( "close" );
            }, 'json');

        }
        _this.showGeneralDialog(config, aamLocal.LABEL_147)
    }

    jQuery('#aam-form').bind('change', function(){
        mObj.form_status = 'changed';
    });

    jQuery('.message-active').show().delay(5000).hide('slow');

    if (jQuery('.plugin-notification p').length){
        jQuery('#aam_wrap').addClass('aam-warning');
    }
    
    jQuery('#aam_wrap').show();
}

aamObject.prototype.initMainMetabox = function(){

    var _this = this;
    jQuery('.aam-tabs').tabs({
        show: function(event, ui) {
            if (ui.index == 4){
                _this.editor.refresh();
            }
        }
    });
    this.initMainMenuTab();
    this.initMetaboxTab();
    this.initCapabilityTab();
    this.initPostTaxonomyTab();
    this.initConfigPressTab();

    this.triggerHooks();

    //fix for 3.6.
    jQuery('.ui-accordion-content').each(function(){
        if (jQuery(this).css('height') == '0px'){
            jQuery(this).css('height', 'auto');
        }
    });
}

aamObject.prototype.initAccordion = function(element, sortable){

    var _this = this;

    //jQuery(element).accordion('destroy');
    var ac = jQuery(element).accordion({
        collapsible: true,
        header: 'h4',
        autoHeight: false,
        heightStyleType : 'content',
        icons: {
            header: "ui-icon-circle-arrow-e",
            headerSelected: "ui-icon-circle-arrow-s"
        },
        active: -1
    });
    if (sortable === true){
        ac.sortable({
            axis: "y",
            handle: "h4",
            stop: function() {
                _this.sort_status = 'sorted';
            }
        });
    }
}

aamObject.prototype.changeText = function(element, text){

    jQuery(element).html(text);
}

aamObject.prototype.showGeneralDialog = function(config, text){

    //jQuery('#general-dialog').dialog('destroy');
    jQuery('#general-dialog .dialog-text').html(text);
    jQuery('#general-dialog').dialog(config);
}

aamObject.prototype.showErrorMessage = function(message){

    var config = {
        resizable: false,
        height: 'auto',
        modal: true,
        title : aamLocal.LABEL_25,
        buttons: {}
    }
    config.buttons[aamLocal.LABEL_76] = function() {
        jQuery( this ).dialog('close');
    }

    this.showGeneralDialog(config, message);
}

aamObject.prototype.showAjaxLoader = function(selector, type){

    jQuery(selector).addClass('loading-new');
    var l_class = (type == 'small' ? 'ajax-loader-small' : 'ajax-loader-big');

    jQuery(selector).prepend('<div class="' + l_class +'"></div>');
    jQuery('.' + l_class).css({
        top: jQuery(selector).height()/2 - (type == 'small' ? 16 : 50),
        left: jQuery(selector).width()/2 - (type == 'small' ? 8 : 25)
    });
}

aamObject.prototype.removeAjaxLoader = function(selector, type){

    jQuery(selector).removeClass('loading-new');
    var l_class = (type == 'small' ? 'ajax-loader-small' : 'ajax-loader-big');
    jQuery('.' + l_class).remove();
}


/*
 * ===========================
 * ****** INIT SECTION *******
 * ===========================
 */

jQuery(document).ready(function(){

    try{
        mObj = new aamObject();
        mObj.init();
    }catch(error){
        alert(error);
        jQuery('#aam_wrap').addClass('aam-error');
        jQuery('.plugin-notification').append('<p>' + aamLocal.LABEL_166 + '</p>');
    }
});