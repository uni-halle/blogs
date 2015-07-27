/**
    Copyright (C) <2013-2014>  Vasyl Martyniuk <support@wpaam.com>

    This program is commercial software: you are not allowed to redistribute it 
    and/or modify. Unauthorized copying of this file, via any medium is strictly 
    prohibited.
    For any questions or concerns contact Vasyl Martyniuk <support@wpaam.com>
 */

jQuery(document).ready(function() {
    var parent = null;

    aamInterface.addAction('aam_init_features', function() {
        jQuery('.post-access-area input', '#default_access_dialog').each(function() {
            jQuery(this).bind('click', function() {
                jQuery('#default_access_dialog .dataTable').hide();
                jQuery('#default_term_access_' + jQuery(this).val()).show();
                jQuery('#default_post_access_' + jQuery(this).val()).show();
            });
        });
        //remove sub posts limitation
        jQuery('.post-access-block', '#access_dialog').remove();
    });

    function launchManageDefaultAccessDialog(button, nRow, aData, type) {
        jQuery('.dataTable', '#default_access_dialog').hide();

        //by default show frontend access
        jQuery('#default_post_access_frontend').show();
        jQuery('#default_term_access_frontend').show();

        //reset the Frontend/Backend radio
        if (jQuery('.post-access-area', '#default_access_dialog').length) {
            //in case it is Visitor, this section is not rendered
            jQuery('.post-access-area input').attr('checked', false);
            jQuery('.post-access-area #default_post_area_frontend').attr('checked', true);
            jQuery('.post-access-area').buttonset('refresh');
        }

        //reset all checkboxes
        jQuery('input', '#default_access_dialog').prop('checked', false);

        //retrieve settings and display the dialog
        var data = parent.compileAjaxPackage('get_default_access');
        data.id = aData[0];
        data.type = type;
        data.subject = parent.getSubject().type;
        data.subject_id = parent.getSubject().id;

        jQuery.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(response) {
                //populate the form
                for (var object in response) {
                    for (var area in response[object]) {
                        for (var action in response[object][area]) {
                            var element = '#default_' + object + '_' + area;
                            element += '_' + action;
                            jQuery(element, '#default_access_dialog').attr(
                                    'checked', 
                                    (response[object][area][action] == '1' ? true : false)
                            );
                        }
                    }
                }
                jQuery('#default_access_dialog').dialog({
                    resizable: false,
                    height: 'auto',
                    width: '25%',
                    modal: true,
                    title: 'Manage Default Access',
                    buttons: {
                        'Reset Default': function() {
                            //retrieve settings and display the dialog
                            var data = parent.compileAjaxPackage('clear_default_access');
                            data.id = aData[0];
                            data.type = type;
                            data.subject = parent.getSubject().type;
                            data.subject_id = parent.getSubject().id;

                            jQuery.ajax(aamLocal.ajaxurl, {
                                type: 'POST',
                                dataType: 'json',
                                data: data,
                                success: function(response) {
                                    parent.highlight(nRow, response.status);
                                },
                                error: function() {
                                    parent.highlight(nRow, 'failure');
                                }
                            });
                            jQuery(this).dialog("close");
                        },
                        Apply: function() {
                            parent.showMetaboxLoader('#default_access_dialog');
                            var data = parent.compileAjaxPackage('set_default_access');
                            data.id = aData[0];
                            data.subject = parent.getSubject().type;
                            data.subject_id = parent.getSubject().id;
        
                            jQuery('input', '#default_access_dialog').each(function() {
                                if (jQuery(this).attr('object')) {
                                    var name = 'access[' + jQuery(this).attr('object');
                                    name += '][' + jQuery(this).attr('area') + '][';
                                    name += jQuery(this).attr('action') + ']';
                                    data[name] = (jQuery(this).prop('checked') ? 1 : 0);
                                }
                            });
                            jQuery.ajax(aamLocal.ajaxurl, {
                                type: 'POST',
                                dataType: 'json',
                                data: data,
                                success: function(response) {
                                    parent.highlight(nRow, response.status);
                                },
                                error: function() {
                                    parent.highlight(nRow, 'failure');
                                },
                                complete: function() {
                                    parent.hideMetaboxLoader('#access_dialog');
                                }
                            });
                            jQuery(this).dialog("close");
                        },
                        Close: function() {
                            jQuery(this).dialog("close");
                        }
                    },
                    close: function() {
                        parent.deactivateIcon(button);
                        jQuery('#default_access_dialog').dialog('destroy');
                    }
                });
            },
            error: function() {
                parent.highlight(nRow, 'failure');
            }
        });
    }

    aamInterface.addAction('aam_breadcrumb_action', function(params) {
        parent = this;
        if (/^[\d]+$/.test(this.postTerm) === false) {
            jQuery('.post-breadcrumb-line-actions .post-breadcrumb-line-action-lock').remove();
            
            jQuery('.post-breadcrumb-line-actions').append(parent.createIcon(
                        'small', 
                        'manage',
                        'Manager Default Access'
            ).bind('click', function(event) {
                event.preventDefault();
                //parent.launch(this, 'post-breadcrumb-line-action-manage');
                launchManageDefaultAccessDialog(
                        this,
                        jQuery('.post-breadcrumb'),
                        new Array(params.breadcrumb[0][0]), 'post'
                );
            }));
        }
    });
});