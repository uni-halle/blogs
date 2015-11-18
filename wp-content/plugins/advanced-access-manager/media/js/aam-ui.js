/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {
    
    /**
     * 
     * @param {type} id
     * @returns {Boolean}
     */
    function isCurrent(id) {
        var subject = aam.getSubject();
        
        return (subject.type === 'role' && subject.id === id);
    }

    /**
     * 
     * @returns {undefined}
     */
    function fetchRoleList() {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'Role.getList',
                _ajax_nonce: aamLocal.nonce
            },
            beforeSend: function () {
                $('#inherit-role-list').html(
                    '<option value="">' + aam.__('Loading...') + '</option>'
                );
            },
            success: function (response) {
                $('#inherit-role-list').html(
                        '<option value="">' + aam.__('Select Role') + '</option>'
                );
                for (var i in response) {
                    $('#inherit-role-list').append(
                        '<option value="' + i + '">' + response[i].name + '</option>'
                    );
                }
            }
        });
    }

    //initialize the role list table
    $('#role-list').DataTable({
        autoWidth: false,
        ordering: false,
        dom: 'ftrip',
        pagingType: 'simple',
        processing: true,
        serverSide: false,
        ajax: {
            url: aamLocal.ajaxurl,
            type: 'POST',
            data: {
                action: 'aam',
                sub_action: 'Role.getTable',
                _ajax_nonce: aamLocal.nonce
            }
        },
        columnDefs: [
            {visible: false, targets: [0, 1]}
        ],
        language: {
            search: '_INPUT_',
            searchPlaceholder: aam.__('Search Role'),
            info: aam.__('_TOTAL_ role(s)'),
            infoFiltered: ''
        },
        initComplete: function () {
            var create = $('<a/>', {
                'href'  : '#',
                'class' : 'btn btn-primary'
            }).html('<i class="icon-plus"></i> ' + aam.__('Create'))
              .bind('click', function (event) {
                event.preventDefault();
                
                //clear add role form first
                $('#new-role-name', '#add-role-modal').val('');
                fetchRoleList();

                $('#add-role-modal').modal('show').on('shown.bs.modal', function (e) {
                    $('#new-role-name', '#add-role-modal').focus();
                });
            });

            $('.dataTables_filter', '#role-list_wrapper').append(create);
        },
        createdRow: function (row, data) {
            if (isCurrent(data[0])) {
                $('td:eq(0)', row).html('<strong>' + data[2] + '</strong>');
            } else {
                $('td:eq(0)', row).html('<span>' + data[2] + '</span>');
            }
            
            //add subtitle
            $('td:eq(0)', row).append(
                $('<i/>', {'class': 'aam-row-subtitle'}).text(
                    aam.__('Users') + ': ' + parseInt(data[1])
                )
            );
            
            var actions = data[3].split(',');

            var container = $('<div/>', {'class': 'aam-row-actions'});
            $.each(actions, function (i, action) {
                switch (action) {
                    case 'manage':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-cog text-info'
                        }).bind('click', function() {
                            aam.setSubject('role', data[0], data[2]);
                            $('td:eq(0) span', row).replaceWith(
                                    '<strong>' + data[2] + '</strong>'
                            );
                            $('i.icon-cog', container).attr(
                                'class', 'aam-row-action icon-spin4 animate-spin'
                            );
                            aam.fetchContent();
                            $('i.icon-spin4', container).attr(
                                'class', 'aam-row-action icon-cog text-info'
                            );
                            //Show add capability that may be hidden after manager user
                            $('#add-capability').show();
                        }));
                        break;

                    case 'edit':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-pencil text-warning'
                        }).bind('click', function () {
                            $('#edit-role-btn').data('role', data[0]);
                            $('#edit-role-name').val(data[2]);
                            $('#edit-role-modal').modal('show').on('shown.bs.modal', function () {
                                $('#edit-role-name').focus();
                            });
                        }));
                        break;

                    case 'delete':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-trash-empty text-danger'
                        }).bind('click', {role: data}, function (event) {
                            $('#delete-role-btn').data('role', data[0]);
                            var message = $('#delete-role-modal .aam-confirm-message').data('message');
                            $('#delete-role-modal .aam-confirm-message').html(
                                message.replace(
                                    '%s',
                                    '<strong>' + event.data.role[2] + '</strong>'
                                )
                            );

                            $('#delete-role-modal').modal('show');
                        }));
                        break;

                    case 'no-delete':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-trash-empty text-muted'
                        }).bind('click', function () {
                            $('#role-notification-modal').modal('show');
                        }));
                        break;

                    default:
                        break;
                }
            });
            $('td:eq(1)', row).html(container);
        }
    });

    //add role button
    $('#add-role-btn').bind('click', function (event) {
        event.preventDefault();

        var _this = this;

        $('#new-role-name', '#add-role-modal').parent().removeClass('has-error');

        var name = $.trim($('#new-role-name', '#add-role-modal').val());
        var inherit = $.trim($('#inherit-role-list', '#add-role-modal').val());

        if (name) {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'Role.add',
                    _ajax_nonce: aamLocal.nonce,
                    name: name,
                    inherit: inherit
                },
                beforeSend: function () {
                    $(_this).text(aam.__('Saving...')).attr('disabled', true);
                },
                success: function (response) {
                    if (response.status === 'success') {
                        $('#role-list').DataTable().ajax.reload();
                        $('#add-role-modal').modal('hide');
                    } else {
                        aam.notification(
                                'danger', aam.__('Failed to add new role')
                        );
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application error'));
                },
                complete: function () {
                    $(_this).text(aam.__('Add Role')).attr('disabled', false);
                }
            });
        } else {
            $('#new-role-name').focus().parent().addClass('has-error');
        }
    });

    //edit role button
    $('#edit-role-btn').bind('click', function (event) {
        var _this = this;

        $('#edit-role-name').parent().removeClass('has-error');
        var name = $.trim($('#edit-role-name').val());

        if (name) {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'Role.edit',
                    _ajax_nonce: aamLocal.nonce,
                    subject: 'role',
                    subjectId: $(_this).data('role'),
                    name: name
                },
                beforeSend: function () {
                    $(_this).text(aam.__('Saving...')).attr('disabled', true);
                },
                success: function (response) {
                    if (response.status === 'success') {
                        $('#role-list').DataTable().ajax.reload();
                    } else {
                        aam.notification(
                                'danger', aam.__('Failed to update role')
                        );
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application error'));
                },
                complete: function () {
                    $('#edit-role-modal').modal('hide');
                    $(_this).text(aam.__('Update')).attr('disabled', false);
                }
            });
        } else {
            $('#edit-role-name').focus().parent().addClass('has-error');
        }
    });
    
    //edit role button
    $('#delete-role-btn').bind('click', function (event) {
        var _this = this;

        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'Role.delete',
                _ajax_nonce: aamLocal.nonce,
                subject: 'role',
                subjectId: $(_this).data('role')
            },
            beforeSend: function () {
                $(_this).text(aam.__('Deleting...')).attr('disabled', true);
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('#role-list').DataTable().ajax.reload();
                } else {
                    aam.notification('danger', aam.__('Failed to delete role'));
                }
            },
            error: function () {
                    aam.notification('danger', aam.__('Application error'));
            },
            complete: function () {
                $('#delete-role-modal').modal('hide');
                $(_this).text(aam.__('Delete Role')).attr('disabled', false);
            }
        });
    });
    
    //add setSubject hook
    aam.addHook('setSubject', function () {
        //clear highlight
        $('tbody tr', '#role-list').each(function () {
            if ($('strong', $(this)).length) {
                var highlight = $('strong', $(this));
                highlight.replaceWith($('<span/>').text(highlight.text()));
            }
        });
        //show post & pages access control groups that belong to backend
        $('.aam-backend-post-access').show();
    });
    
    //in case interface needed to be reloaded
    aam.addHook('refresh', function () {
        $('#role-list').DataTable().ajax.url(aamLocal.ajaxurl).load();
    });

})(jQuery);


/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {
    
    /**
     * 
     * @param {type} id
     * @returns {Boolean}
     */
    function isCurrent(id) {
        var subject = aam.getSubject();
        
        return (subject.type === 'user' && parseInt(subject.id) === id);
    }
    
    /**
     * 
     * @param {type} id
     * @param {type} btn
     * @returns {undefined}
     */
    function blockUser(id, btn) {
        var state = ($(btn).hasClass('icon-lock') ? 0 : 1);
        
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'User.block',
                _ajax_nonce: aamLocal.nonce,
                subject: 'user',
                subjectId: id
            },
            beforeSend: function () {
                $(btn).attr('class', 'aam-row-action icon-spin4 animate-spin');
            },
            success: function (response) {
                if (response.status === 'success') {
                    if (state === 1) {
                        $(btn).attr('class', 'aam-row-action icon-lock text-danger');
                    } else {
                        $(btn).attr('class', 'aam-row-action icon-lock-open-alt text-warning');
                    }
                } else {
                    aam.notification(
                        'danger', aam.__('Failed to block user')
                    );
                }
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            }
        });
    }
    
    //initialize the user list table
    $('#user-list').DataTable({
        autoWidth: false,
        ordering: false,
        dom: 'ftrip',
        pagingType: 'simple',
        serverSide: true,
        processing: true,
        ajax: {
            url: aamLocal.ajaxurl,
            type: 'POST',
            data: {
                action: 'aam',
                sub_action: 'User.getTable',
                _ajax_nonce: aamLocal.nonce
            }
        },
        columnDefs: [
            {visible: false, targets: [0, 1]}
        ],
        language: {
            search: '_INPUT_',
            searchPlaceholder: aam.__('Search User'),
            info: aam.__('_TOTAL_ user(s)'),
            infoFiltered: ''
        },
        initComplete: function () {
             var create = $('<a/>', {
                'href'  : '#',
                'class' : 'btn btn-primary'
            }).html('<i class="icon-plus"></i> ' + aam.__('Create')).bind('click', function (event) {
                event.preventDefault();
                window.open(aamLocal.url.addUser, '_blank');
            });

            $('.dataTables_filter', '#user-list_wrapper').append(create);
        },
        createdRow: function (row, data) {
            if (isCurrent(data[0])) {
                $('td:eq(0)', row).html('<strong>' + data[2] + '</strong>');
            } else {
                $('td:eq(0)', row).html('<span>' + data[2] + '</span>');
            }
            
            //add subtitle
            $('td:eq(0)', row).append(
                $('<i/>', {'class': 'aam-row-subtitle'}).text(
                    aam.__('Role') + ': ' + data[1]
                )
            );
    
            var actions = data[3].split(',');

            var container = $('<div/>', {'class': 'aam-row-actions'});
            $.each(actions, function (i, action) {
                switch (action) {
                    case 'manage':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-cog text-info'
                        }).bind('click', function() {
                            aam.setSubject('user', data[0], data[2]);
                            $('td:eq(0) span', row).replaceWith(
                                    '<strong>' + data[2] + '</strong>'
                            );
                            $('i.icon-cog', container).attr('class', 'aam-row-action icon-spin4 animate-spin');
                            aam.fetchContent();
                            $('i.icon-spin4', container).attr('class', 'aam-row-action icon-cog text-info');
                            //make sure that there is no way user add's new capability
                            $('#add-capability').hide();
                        }));
                        break;

                    case 'edit':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-pencil text-warning'
                        }).bind('click', function () {
                            window.open(
                                aamLocal.url.editUser + '?user_id=' + data[0], '_blank'
                            );
                        }));
                        break;

                    case 'lock':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-lock-open-alt text-warning'
                        }).bind('click', function () {
                            blockUser(data[0], $(this));
                        }));
                        break;
                        
                    case 'unlock':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-lock text-danger'
                        }).bind('click', function () {
                            blockUser(data[0], $(this));
                        }));
                        break;
                        
                      case 'no-lock':
                      case 'no-unlock':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-lock text-muted'
                        }).bind('click', function () {
                            $('#user-notification-modal').modal('show');
                        }));
                        break;
                        
                    default:
                        break;
                }
            });
            $('td:eq(1)', row).html(container);
        }
    });
    
    //add setSubject hook
    aam.addHook('setSubject', function () {
        //clear highlight
        $('tbody tr', '#user-list').each(function () {
            if ($('strong', $(this)).length) {
                var highlight = $('strong', $(this));
                highlight.replaceWith('<span>' + highlight.text() + '</span>');
            }
        });
        //show post & pages access control groups that belong to backend
        $('.aam-backend-post-access').show();
    });
    
    //in case interface needed to be reloaded
    aam.addHook('refresh', function () {
        $('#user-list').DataTable().ajax.url(aamLocal.ajaxurl).load();
    });
    
})(jQuery);


/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {

    /**
     * 
     * @returns {undefined}
     */
    function initialize() {
        $('#manage-visitor').bind('click', function (event) {
            event.preventDefault();
            aam.setSubject('visitor', null, aam.__('Anonymous'));
            $('i.icon-cog', $(this)).attr('class', 'icon-spin4 animate-spin');
            aam.fetchContent();
            $('i.icon-spin4', $(this)).attr('class', 'icon-cog');
            //hide post & pages access control groups that belong to backend
            $('.aam-backend-post-access').hide();
        });
    }

    //add setSubject hook
    aam.addHook('init', initialize);

})(jQuery);


/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {

    /**
     * 
     * @returns {undefined}
     */
    function initialize() {
        $('.aam-restrict-menu').each(function () {
            $(this).bind('click', function () {
                var status = ($('i', $(this)).hasClass('icon-eye-off') ? 1 : 0);
                var target = $(this).data('target');

                $('i', $(this)).attr('class', 'icon-spin4 animate-spin');

                var result = aam.save($(this).data('menu-id'), status, 'menu');
                
                if (result.status === 'success') {
                    if (status === 1) { //locked the menu
                        $('input', target).each(function () {
                            $(this).attr('checked', true);
                            aam.save($(this).data('menu-id'), status, 'menu');
                        });
                        $('.aam-bordered', target).append(
                                $('<div/>', {'class': 'aam-lock'})
                        );
                        $(this).removeClass('btn-danger').addClass('btn-primary');
                        $(this).html(
                            '<i class="icon-eye"></i>' + aam.__('Show Menu')
                        );
                        //add menu restricted indicator
                        var ind = $('<i/>', {
                            'class' : 'aam-panel-title-icon icon-eye-off text-danger'
                        });
                        $('.panel-title', target + '-heading').append(ind);
                    } else {
                        $('input', target).each(function () {
                            $(this).attr('checked', false);
                            aam.save($(this).data('menu-id'), status, 'menu');
                        });
                        $('.aam-lock', target).remove();
                        $(this).removeClass('btn-primary').addClass('btn-danger');
                        $(this).html(
                            '<i class="icon-eye-off"></i>' + aam.__('Hide Menu')
                        );
                        $('.panel-title .icon-eye-off', target + '-heading').remove();
                    }
                } else {
                    $(this).attr('checked', (status ? false : true));
                }
            });
        });

        $('input[type="checkbox"]', '#admin-menu').each(function () {
            $(this).bind('click', function () {
                aam.save(
                    $(this).data('menu-id'), !$(this).attr('checked'), 'menu'
                );
            });
        });
    }

    aam.addHook('init', initialize);

})(jQuery);


/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {

    /**
     * 
     * @returns {undefined}
     */
    function getContent() {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'html',
            data: {
                action: 'aam',
                sub_action: 'Metabox.getContent',
                _ajax_nonce: aamLocal.nonce,
                subject: aam.getSubject().type,
                subjectId: aam.getSubject().id
            },
            success: function (response) {
                $('#metabox-content').replaceWith(response);
                $('#metabox-content').addClass('active');
                initialize();
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            }
        });
    }

    /**
     * 
     * @returns {undefined}
     */
    function initialize() {
        //init refresh list button
        $('#refresh-metabox-list').bind('click', function (event) {
            event.preventDefault();

            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'Metabox.refreshList',
                    _ajax_nonce: aamLocal.nonce
                },
                beforeSend: function () {
                    $('i', '#refresh-metabox-list').attr(
                            'class', 'icon-spin4 animate-spin'
                            );
                },
                success: function (response) {
                    if (response.status === 'success') {
                        getContent();
                    } else {
                        aam.notification(
                            'danger', aam.__('Failed to retrieve mataboxes')
                        );
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application error'));
                },
                complete: function () {
                    $('i', '#refresh-metabox-list').attr(
                            'class', 'icon-arrows-cw'
                    );
                }
            });

        });
        
        $('input[type="checkbox"]', '#metabox-list').each(function () {
            $(this).bind('click', function () {
                aam.save(
                    $(this).data('metabox'), !$(this).attr('checked'), 'metabox'
                );
            });
        });
    }
    
    aam.addHook('init', initialize);

})(jQuery);


/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {

    /**
     * 
     * @param {type} capability
     * @param {type} btn
     * @returns {undefined}
     */
    function save(capability, btn) {
        var granted = $(btn).hasClass('icon-check-empty') ? 1 : 0;
        
        //show indicator
        $(btn).attr('class', 'aam-row-action icon-spin4 animate-spin');
        
        if (aam.save(capability, granted, 'capability').status === 'success') {
            if (granted) {
                $(btn).attr('class', 'aam-row-action text-success icon-check');
            } else {
                $(btn).attr('class', 'aam-row-action text-muted icon-check-empty');
            }
        } else {
             if (granted) {
                aam.notification(
                    'danger', aam.__('Failed to grand capability - WordPress policy')
                );
                $(btn).attr('class', 'aam-row-action text-muted icon-check-empty');
            } else {
                $(btn).attr('class', 'aam-row-action text-success icon-check');
            }
        }
    }
    /**
     * 
     * @returns {undefined}
     */
    function initialize() {
        //initialize the role list table
        $('#capability-list').DataTable({
            autoWidth: false,
            ordering: false,
            pagingType: 'simple',
            serverSide: false,
            ajax: {
                url: aamLocal.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aam',
                    sub_action: 'Capability.getTable',
                    _ajax_nonce: aamLocal.nonce,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id
                }
            },
            columnDefs: [
                {visible: false, targets: [0]}
            ],
            language: {
                search: '_INPUT_',
                searchPlaceholder: aam.__('Search Capability'),
                info: aam.__('_TOTAL_ capability(s)'),
                infoFiltered: '',
                lengthMenu: '_MENU_'
            },
            createdRow: function (row, data) {
                var actions = data[3].split(',');

                var container = $('<div/>', {'class': 'aam-row-actions'});
                $.each(actions, function (i, action) {
                    var checkbox = $('<input/>').attr({
                            'type': 'checkbox'
                    });
                    switch (action) {
                        case 'unchecked':
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action text-muted icon-check-empty'
                            }).bind('click', function () {
                                save(data[0], this);
                            }));
                            break;

                        case 'checked':
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action text-success icon-check'
                            }).bind('click', function () {
                                save(data[0], this);
                            }));
                            break;

                        default:
                            break;
                    }
                });
                $('td:eq(2)', row).html(container);
            }
        });

        //filter capability dropdown
        $('#capability-filter').bind('click', function () {
            $('.dropdown-menu').dropdown('toggle');
        });

        $('a', '#capability-groups').each(function () {
            $(this).bind('click', function () {
                var table = $('#capability-list').DataTable();
                if ($(this).data('clear') !== true) {
                    table.column(1).search($(this).text()).draw();
                } else {
                    table.column(1).search('').draw();
                }
            });
        });


        $('#add-capability').bind('click', function (event) {
            event.preventDefault();
            $('#add-capability-modal').modal('show');
        });

        $('#add-capability-btn').bind('click', function () {
            var _this = this;

            var capability = $.trim($('#new-capability-name').val());
            $('#new-capability-name').parent().removeClass('has-error');

            if (capability) {
                $.ajax(aamLocal.ajaxurl, {
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'aam',
                        sub_action: 'Capability.add',
                        _ajax_nonce: aamLocal.nonce,
                        capability: capability
                    },
                    beforeSend: function () {
                        $(_this).text(aam.__('Saving...')).attr('disabled', true);
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('#add-capability-modal').modal('hide');
                            $('#capability-list').DataTable().ajax.reload();
                        } else {
                            aam.notification(
                                'danger', aam.__('Failed to add new capability')
                            );
                        }
                    },
                    error: function () {
                        aam.notification('danger', aam.__('Application error'));
                    },
                    complete: function () {
                        $(_this).text(aam.__('Add Capability')).attr('disabled', false);
                    }
                });
            } else {
                $('#new-capability-name').parent().addClass('has-error');
            }
        });

        $('#add-capability-modal').on('shown.bs.modal', function (e) {
            $('#new-capability-name').focus();
        });

    }

    aam.addHook('init', initialize);

})(jQuery);


/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {

    /**
     * Table extra filter
     * 
     * @type Object
     */
    var filter = {
        type: null
    };

    /**
     * Ajax query queue
     * 
     * Is used to get the posts/terms breadcrumbs
     * 
     * @type Array
     */
    var queue = new Array();

    /**
     * 
     * @param {type} type
     * @param {type} id
     * @param {type} title
     * @returns {undefined}
     */
    function addBreadcrumbLevel(type, id, title) {
        var level = $((type === 'type' ? '<a/>' : '<span/>')).attr({
            'href': '#',
            'data-level': type,
            'data-id': id
        }).html('<i class="icon-angle-double-right"></i>' + title);
        $('.aam-post-breadcrumb').append(level);
    }

    /**
     * 
     * @param {type} object
     * @param {type} id
     * @param {type} btn
     * @returns {undefined}
     */
    function loadAccessForm(object, id, btn) {
        //reset the form first
        var container = $('.aam-post-manager[data-type="' + object + '"]');
        
        $('.aam-row-action', container).each(function() {
            $(this).attr({
                'class' : 'aam-row-action text-muted icon-check-empty',
                'data-type' : object,
                'data-id' : id
            });
            
            //initialize each access property
            $(this).unbind('click').bind('click', function (event) {
                event.preventDefault();

                var checked = !$(this).hasClass('icon-check');

                $(this).attr('class', 'aam-row-action icon-spin4 animate-spin');
                var response = save(
                        $(this).data('property'),
                        checked,
                        object,
                        id
                );
                if (response.status === 'success') {
                    if (checked) {
                        $(this).attr(
                            'class', 'aam-row-action text-danger icon-check'
                        );
                    } else {
                        $(this).attr(
                            'class', 'aam-row-action text-muted icon-check-empty'
                        );
                    }
                }
            });
            
        });
        
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'Post.getAccess',
                _ajax_nonce: aamLocal.nonce,
                type: object,
                id: id,
                subject: aam.getSubject().type,
                subjectId: aam.getSubject().id
            },
            beforeSend: function () {
                $(btn).attr('class', 'aam-row-action icon-spin4 animate-spin');
            },
            success: function (response) {
                //iterate through each property
                for (var property in response) {
                    var checked = (response[property] ? 'text-danger icon-check' : 'text-muted icon-check-empty');

                    $('[data-property="' + property + '"]', container).attr({
                        'class' : 'aam-row-action ' + checked
                    });
                }

                $('#post-list_wrapper').addClass('aam-hidden');
                container.addClass('active');
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            },
            complete: function () {
                $(btn).attr('class', 'aam-row-action text-info icon-cog');
            }
        });
    }
    
    /**
     * 
     * @param {type} param
     * @param {type} value
     * @param {type} object
     * @param {type} object_id
     * @returns {unresolved}
     */
    function save(param, value, object, object_id) {
        var result = null;
        
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            async: false,
            data: {
                action: 'aam',
                sub_action: 'Post.save',
                _ajax_nonce: aamLocal.nonce,
                subject: aam.getSubject().type,
                subjectId: aam.getSubject().id,
                param: param,
                value: value,
                object: object,
                objectId: object_id
            },
            success: function (response) {
                if (response.status === 'failure') {
                    aam.notification('danger', response.error);
                }
                result = response;
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            }
        });
        
        return result;
    };
    
    /**
     * 
     * @returns {undefined}
     */
    function initialize() {
        //initialize the role list table
        $('#post-list').DataTable({
            autoWidth: false,
            ordering: false,
            pagingType: 'simple',
            serverSide: false,
            ajax: {
                url: aamLocal.ajaxurl,
                type: 'POST',
                data: function () {
                    return {
                        action: 'aam',
                        sub_action: 'Post.getTable',
                        _ajax_nonce: aamLocal.nonce,
                        subject: aam.getSubject().type,
                        subjectId: aam.getSubject().id,
                        type: filter.type
                    };
                }
            },
            columnDefs: [
                {visible: false, targets: [0, 1]}
            ],
            language: {
                search: '_INPUT_',
                searchPlaceholder: aam.__('Search'),
                info: aam.__('_TOTAL_ object(s)'),
                infoFiltered: '',
                lengthMenu: '_MENU_'
            },
            drawCallback: function () {
                setTimeout(function () {
                    if (queue.length) {
                        queue.pop().call();
                    }
                }, 700);
            },
            initComplete: function () {
                //reset the ajax queue
                queue = new Array();
            },
            createdRow: function (row, data) {
                //object type icon
                switch (data[2]) {
                    case 'type':
                        $('td:eq(0)', row).html('<i class="icon-box"></i>');
                        break;

                    case 'term':
                        $('td:eq(0)', row).html('<i class="icon-folder"></i>');
                        break;

                    default:
                        $('td:eq(0)', row).html('<i class="icon-doc-text-inv"></i>');
                        break;
                }

                //update the title to a link
                if (data[2] === 'type') {
                    var link = $('<a/>', {
                        href: '#'
                    }).bind('click', function (event) {
                        event.preventDefault();
                        //visual feedback - show loading icon
                        $('td:eq(0)', row).html(
                                '<i class="icon-spin4 animate-spin"></i>'
                        );
                        //set filter
                        filter[data[2]] = data[0];
                        //reset the ajax queue
                        queue = new Array();
                        //finally reload the data
                        $('#post-list').DataTable().ajax.reload();

                        //update the breadcrumb
                        addBreadcrumbLevel('type', data[0], data[3]);

                    }).html(data[3]);
                    $('td:eq(1)', row).html(link);
                }

                //add breadcrumb but only if not a type
                if (data[2] !== 'type') {
                    queue.push(function () {
                        $.ajax(aamLocal.ajaxurl, {
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                action: 'aam',
                                sub_action: 'Post.getBreadcrumb',
                                _ajax_nonce: aamLocal.nonce,
                                type: data[2],
                                id: data[0]
                            },
                            success: function (response) {
                                if (response.status === 'success') {
                                    $('td:eq(1) span', row).html(
                                            response.breadcrumb
                                            );
                                }
                            },
                            error: function () {
                                $('td:eq(1) span', row).html(aam.__('Failed'));
                            },
                            complete: function () {
                                if (queue.length) {
                                    queue.pop().call();
                                }
                            }
                        });
                    });
                    $('td:eq(1)', row).append(
                        $('<span/>', {'class': 'aam-row-subtitle'}).text(
                            aam.__('Loading...')
                        )
                   );
                }

                //update the actions
                var actions = data[4].split(',');

                var container = $('<div/>', {'class': 'aam-row-actions'});
                $.each(actions, function (i, action) {
                    switch (action) {
                        case 'manage':
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action text-info icon-cog'
                            }).bind('click', function () {
                                loadAccessForm(data[2], data[0], $(this));
                                addBreadcrumbLevel('edit', data[2], data[3]);
                            }));
                            break;

                        case 'edit' :
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action text-warning icon-pencil'
                            }).bind('click', function () {
                                window.open(data[1], '_blank');
                            }));
                            break;
                            break;

                        default:
                            break;
                    }
                });
                $('td:eq(2)', row).html(container);
            }
        });

        //initialize the breadcrumb
        $('.aam-post-breadcrumb').delegate('a', 'click', function (event) {
            event.preventDefault();

            //stop any pending ajax calls
            queue = new Array();

            filter.type = $(this).data('id');
            $('#post-list').DataTable().ajax.reload();
            $(this).nextAll().remove();
            $('.aam-post-manager').removeClass('active');
            $('#post-list_wrapper').removeClass('aam-hidden');
        });

        //go back button
        $('.aam-post-manager').delegate('.post-back', 'click', function (event) {
            event.preventDefault();

            var type = $(this).parent().data('type');

            $('.aam-post-manager[data-type="' + type + '"]').removeClass('active');
            $('#post-list_wrapper').removeClass('aam-hidden');
            $('.aam-post-breadcrumb span:last').remove();
        });
    }

    aam.addHook('init', initialize);

})(jQuery);

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {

    var dump = null;

    /**
     * 
     * @param {type} data
     * @returns {undefined}
     */
    function downloadExtension(data) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            async: false,
            data: data,
            success: function (response) {
                if (response.status === 'success') {
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                } else {
                    aam.notification('danger', aam.__(response.error));
                    if (typeof response.content !== 'undefined') {
                        dump = response;
                        $('#installation-error').text(response.error);
                        $('#extension-notification-modal').modal('show');
                    }
                }
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            }
        });
    }

    /**
     * 
     * @returns {undefined}
     */
    function initialize() {

        //check if extension list is empty
        if ($('#extension-list tbody tr').length === 1) {
            $('#extension-list-empty').removeClass('hidden');
        }

        //init refresh list button
        $('#install-extension').bind('click', function (event) {
            event.preventDefault();

            $('#extension-key').parent().removeClass('error');
            var license = $.trim($('#extension-key').val());

            if (!license) {
                $('#extension-key').parent().addClass('error');
                $('#extension-key').focus();
                return;
            }

            $('i', $(this)).attr('class', 'icon-spin4 animate-spin');
            downloadExtension({
                action: 'aam',
                sub_action: 'Extension.install',
                _ajax_nonce: aamLocal.nonce,
                license: $('#extension-key').val()
            });
            $('i', $(this)).attr('class', 'icon-download-cloud');
        });

        //update extension
        $('.aam-update-extension').each(function () {
            $(this).bind('click', function (event) {
                event.preventDefault();

                $('i', $(this)).attr('class', 'icon-spin4 animate-spin');
                downloadExtension({
                    action: 'aam',
                    sub_action: 'Extension.update',
                    _ajax_nonce: aamLocal.nonce,
                    extension: $(this).data('product')
                });
                $('i', $(this)).attr('class', 'icon-arrows-cw');
            });
        });
        
        //download extension
        $('.aam-download-extension').each(function () {
            $(this).bind('click', function (event) {
                event.preventDefault();

                $('i', $(this)).attr('class', 'icon-spin4 animate-spin');
                downloadExtension({
                    action: 'aam',
                    sub_action: 'Extension.install',
                    _ajax_nonce: aamLocal.nonce,
                    license: $(this).data('license')
                });
                $('i', $(this)).attr('class', 'icon-download-cloud');
            });
        });

        //bind the download handler
        $('#download-extension').bind('click', function () {
            download(
                    'data:image/gif;base64,' + dump.content,
                    dump.title + '.zip',
                    'application/zip'
                    );
            $('#extension-notification-modal').hide();
        });
    }

    aam.addHook('init', initialize);

})(jQuery);


/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

(function ($) {

    /**
     * 
     * @returns {undefined}
     */
    function initializeMenu() {
        //initialize the menu switch
        $('li', '#feature-list').each(function () {
            $(this).bind('click', function () {
                $('.aam-feature').removeClass('active');
                //highlight active feature
                $('li', '#feature-list').removeClass('active');
                $(this).addClass('active');
                //show feature content
                $('#' + $(this).data('feature') + '-content').addClass('active');
            });
        });

        $('li:eq(0)', '#feature-list').trigger('click');
    }

    /**
     * 
     * @returns {undefined}
     */
    aam.fetchContent = function () {
        $.ajax(aamLocal.url.site, {
            type: 'POST',
            dataType: 'html',
            async: false,
            data: {
                action: 'aam-content',
                _ajax_nonce: aamLocal.nonce,
                subject: this.getSubject().type,
                subjectId: this.getSubject().id
            },
            beforeSend: function () {
                var loader = $('<div/>', {'class': 'aam-loading'}).html(
                        '<i class="icon-spin4 animate-spin"></i>'
                );
                $('#aam-content').html(loader);
            },
            success: function (response) {
                $('#aam-content').html(response);
                //init menu
                initializeMenu();
                //trigger initialization hook
                aam.triggerHook('init');
            }
        });
    };

    aam.fetchContent(); //fetch default AAM content

})(jQuery);