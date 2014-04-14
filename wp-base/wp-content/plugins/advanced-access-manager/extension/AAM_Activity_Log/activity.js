/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Activity List
 *
 * @type object
 */
AAM.prototype.blogTables.activityList = null;

/**
 * Initialize and load activity tab
 *
 * @returns void
 */
AAM.prototype.loadActivityTab = function() {
    var _this = this;

    if (this.blogTables.activityList === null) {
        this.blogTables.activityList = jQuery('#activity_list').dataTable({
            sDom: "<'top'lf<'activity-top-actions'><'clear'>>t<'footer'ip<'clear'>>",
            sPaginationType: "full_numbers",
            bAutoWidth: false,
            bDestroy: true,
            bSort: false,
            sAjaxSource: ajaxurl,
            fnServerParams: function(aoData) {
                aoData.push({
                    name: 'action',
                    value: 'aam'
                });
                aoData.push({
                    name: 'sub_action',
                    value: 'activity_list'
                });
                aoData.push({
                    name: 'subject',
                    value: _this.getSubject().type
                });
                aoData.push({
                    name: 'subject_id',
                    value: _this.getSubject().id
                });
                aoData.push({
                    name: '_ajax_nonce',
                    value: aamLocal.nonce
                });
            },
            fnInitComplete: function() {
                var a = jQuery('#activity_list_wrapper .activity-top-actions');

                var clear = jQuery('<a/>', {
                    'href': '#',
                    'class': 'activity-top-action activity-top-action-clear',
                    'aam-tooltip': aamLocal.labels['Clear Logs']
                }).bind('click', function(event) {
                    event.preventDefault();
                    _this.launch(jQuery(this), 'activity-top-action-clear');
                    _this.launchClearActivityLog();
                });
                jQuery(a).append(clear);

                var info = jQuery('<a/>', {
                    'href': '#',
                    'class': 'activity-top-action activity-top-action-info',
                    'aam-tooltip': aamLocal.labels['Get More']
                }).bind('click', function(event) {
                    event.preventDefault();
                    _this.launch(jQuery(this), 'activity-top-action-info');
                    _this.launchActivityLogInfo();
                });
                jQuery(a).append(info);

                _this.doAction('aam_activity_top_actions', {container: a});
            },
            fnRowCallback: function(nRow, aData) {
                jQuery('td:eq(0)', nRow).html(jQuery('<a/>', {
                    href: aamLocal.editUserURI + '?user_id=' + aData[0],
                    target: '_blank'
                }).html(aData[1]));
            },
            aoColumnDefs: [
                {bVisible: false, aTargets: [0]}
            ],
            oLanguage: {
                sSearch: "",
                oPaginate: {
                    sFirst: "&Lt;",
                    sLast: "&Gt;",
                    sNext: "&gt;",
                    sPrevious: "&lt;"
                },
                sLengthMenu: "_MENU_"
            }
        });
    }
};

/**
 * Show Clear Activity Log Confirmation dialog
 *
 * @returns {void}
 *
 * @access public
 */
AAM.prototype.launchClearActivityLog = function() {
    var _this = this;

    var buttons = {};

    buttons[aamLocal.labels['Clear Logs']] = function() {
        jQuery.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: _this.compileAjaxPackage('clear_activities', true),
            complete: function() {
                jQuery('#clear_activity_dialog').dialog("close");
            }
        });
    };

    buttons[aamLocal.labels['Cancel']] = function() {
        jQuery('#clear_activity_dialog').dialog("close");
    };

    jQuery('#clear_activity_dialog').dialog({
        resizable: false,
        height: 'auto',
        width: '20%',
        modal: true,
        buttons: buttons,
        close: function() {
            _this.terminate(
                    jQuery('.activity-top-action-clear'),
                    'activity-top-action-clear'
            );
            //refresh the table
            _this.blogTables.activityList = null;
            _this.loadActivityTab();
        }
    });
};

/**
 * Show Activation Log Information Dialog
 *
 * @returns {void}
 *
 * @access public
 */
AAM.prototype.launchActivityLogInfo = function() {
    var _this = this;

    var buttons = {};

    buttons[aamLocal.labels['Close']] = function() {
        jQuery('#info_activity_dialog').dialog("close");
    };

    jQuery('#info_activity_dialog').dialog({
        resizable: false,
        height: 'auto',
        width: '30%',
        modal: true,
        buttons: buttons,
        close: function() {
            _this.terminate(
                    jQuery('.activity-top-action-info'),
                    'activity-top-action-info'
            );
        }
    });
};

jQuery(document).ready(function() {
    aamInterface.addAction('aam_feature_activation', function(params) {
        if (params.feature === 'activity_log') {
            aamInterface.loadActivityTab();
        }
    });
});