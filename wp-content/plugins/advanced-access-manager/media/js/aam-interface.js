/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Role List Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
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
    function fetchRoleList(exclude) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'Role.getList',
                _ajax_nonce: aamLocal.nonce,
                exclude: exclude
            },
            beforeSend: function () {
                $('.inherit-role-list').html(
                    '<option value="">' + aam.__('Loading...') + '</option>'
                );
            },
            success: function (response) {
                $('.inherit-role-list').html(
                    '<option value="">' + aam.__('Select Role') + '</option>'
                );
                for (var i in response) {
                    $('.inherit-role-list').append(
                        '<option value="' + i + '">' + response[i].name + '</option>'
                    );
                }
                if ($.aamEditRole) {
                    $('#inherit-role').val($.aamEditRole[0]);
                }
                aam.triggerHook('post-get-role-list', {
                   list : response
               });
               //TODO - Rerwite JavaScript to support $.aam 
               $.aamEditRole = null;
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
            {visible: false, targets: [0, 1, 4, 5]}
        ],
        language: {
            search: '_INPUT_',
            searchPlaceholder: aam.__('Search Role'),
            info: aam.__('_TOTAL_ role(s)'),
            infoFiltered: ''
        },
        initComplete: function () {
            if (!aam.isUI() && parseInt(aamLocal.caps.create_roles)) {
                var create = $('<a/>', {
                    'href': '#',
                    'class': 'btn btn-primary'
                }).html('<i class="icon-plus"></i> ' + aam.__('Create'))
                .bind('click', function (event) {
                    event.preventDefault();
                    $('#add-role-modal').modal('show');
                });

                $('.dataTables_filter', '#role-list_wrapper').append(create);
            }
        },
        createdRow: function (row, data) {
            if (isCurrent(data[0])) {
                $('td:eq(0)', row).html('<strong class="aam-highlight">' + data[2] + '</strong>');
            } else {
                $('td:eq(0)', row).html('<span>' + data[2] + '</span>');
            }
            
            $(row).attr('data-id', data[0]);

            //add subtitle
            var expire = (data[5] ? '; <i class="icon-clock"></i>' : '');
            $('td:eq(0)', row).append(
                $('<i/>', {'class': 'aam-row-subtitle'}).html(
                    aam.__('Users') + ': <b>' + parseInt(data[1]) + '</b>; ID: <b>' + data[0] + '</b>' + expire
                )
            );
    
            var actions = data[3].split(',');

            var container = $('<div/>', {'class': 'aam-row-actions'});
            $.each(actions, function (i, action) {
                switch (action) {
                    case 'manage':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-cog ' + (isCurrent(data[0]) ? 'text-muted': 'text-info')
                        }).bind('click', function () {
                            if (!$(this).prop('disabled')) {
                                $(this).prop('disabled', true);
                                var title = $('td:eq(0) span', row).html();
                                aam.setSubject('role', data[0], title, data[4]);
                                $('td:eq(0) span', row).replaceWith(
                                    '<strong class="aam-highlight">' + title + '</strong>'
                                );
                                $('i.icon-cog', container).attr(
                                    'class', 'aam-row-action icon-cog text-muted'
                                );
                                if (!aam.isUI()) {
                                    $('i.icon-cog', container).attr(
                                        'class', 'aam-row-action icon-spin4 animate-spin'
                                    );
                                    aam.fetchContent();
                                    $('i.icon-spin4', container).attr(
                                        'class', 'aam-row-action icon-cog text-muted'
                                    );
                                } else {
                                    $.aam.loadAccessForm($('#load-post-object-type').val(), $('#load-post-object').val(), $(this));
                                }
                            }
                        }).attr({
                            'data-toggle': "tooltip",
                            'title': aam.__('Manage Role')
                        }).prop('disabled', (isCurrent(data[0]) ? true: false)));
                        break;

                    case 'edit':
                        if (!aam.isUI()) {
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action icon-pencil text-warning'
                            }).bind('click', function () {
                                $('#edit-role-btn').data('role', data[0]);
                                $('#edit-role-name').val(data[2]);
                                $('#edit-role-expiration').val(data[5]);
                                $('#edit-role-modal').modal('show');
                                fetchRoleList(data[0]);
                                //TODO - Rerwite JavaScript to support $.aam 
                                $.aamEditRole = data;
                            }).attr({
                                'data-toggle': "tooltip",
                                'title': aam.__('Edit Role Name')
                            }));
                        }
                        break;
                    
                    case 'clone':
                        if (!aam.isUI()) {
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action icon-clone text-success'
                            }).bind('click', function () {
                                //TODO - Rerwite JavaScript to support $.aam 
                                $.aamEditRole = data;
                                $('#clone-role').prop('checked', true);
                                $('#add-role-modal').modal('show');
                            }).attr({
                                'data-toggle': "tooltip",
                                'title': aam.__('Clone Role')
                            }));
                        }
                        break;

                    case 'delete':
                        if (!aam.isUI()) {
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action icon-trash-empty text-danger'
                            }).bind('click', {role: data}, function (event) {
                                $('#delete-role-btn').data('role', data[0]);
                                var message = $('#delete-role-modal .aam-confirm-message').data('message');
                                $('#delete-role-modal .aam-confirm-message').html(
                                    message.replace(
                                        '%s', '<strong>' + event.data.role[2] + '</strong>'
                                    )
                                );

                                $('#delete-role-modal').modal('show');
                            }).attr({
                                'data-toggle': "tooltip",
                                'title': aam.__('Delete Role')
                            }));
                        }
                        break;

                    default:
                        if (!aam.isUI()) {
                            aam.triggerHook('role-action', {
                                container: container,
                                action   : action,
                                data     : data
                            });
                        }
                        break;
                }
            });
            $('td:eq(1)', row).html(container);
            
            aam.triggerHook('decorate-role-row', {
                row : row,
                data: data
            });
        }
    });
    
    $('#role-list').on( 'draw.dt', function () {
        $('tr', '#role-list tbody').each(function() {
            if (!isCurrent($(this).data('id'))) {
                $('td:eq(0) strong', this).replaceWith(
                    '<span>' + $('td:eq(0) strong', this).text() + '</span>'
                );
                $('.text-muted', this).attr('disabled', false);
                $('.text-muted', this).toggleClass('text-muted text-info');
            }
        });
    } );
    
    $('#add-role-modal').on('shown.bs.modal', function (e) {
        fetchRoleList();
        //clear add role form first
        $('input[name="name"]', '#add-role-modal').val('').focus();
    });
    
    $('#edit-role-modal').on('shown.bs.modal', function (e) {
        $('input[name="name"]', '#edit-role-modal').focus();
    });

    //add role button
    $('#add-role-btn').bind('click', function (event) {
        event.preventDefault();

        var _this = this;

        $('input[name="name"]', '#add-role-modal').parent().removeClass('has-error');

        var data = {
            action: 'aam',
            sub_action: 'Role.add',
            _ajax_nonce: aamLocal.nonce
        };
        
        $('input,select', '#add-role-modal .modal-body').each(function() {
            if ($(this).attr('type') === 'checkbox') {
                data[$(this).attr('name')] = $(this).prop('checked') ? 1 : 0;
            } else {
                data[$(this).attr('name')] = $.trim($(this).val());
            }
        });

        if (data.name) {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: data,
                beforeSend: function () {
                    $(_this).text(aam.__('Saving...')).attr('disabled', true);
                },
                success: function (response) {
                    if (response.status === 'success') {
                        $('#role-list').DataTable().ajax.reload();
                        aam.setSubject(
                            'role', 
                            response.role.id, 
                            response.role.name, 
                            response.role.level
                        );
                        aam.fetchContent();
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
            $('input,select', '#add-role-modal .modal-body').focus().parent().addClass('has-error');
        }
    });

    //edit role button
    $('#edit-role-btn').bind('click', function (event) {
        var _this = this;

        $('#edit-role-name').parent().removeClass('has-error');
        
        var data = {
            action: 'aam',
            sub_action: 'Role.edit',
            _ajax_nonce: aamLocal.nonce,
            subject: 'role',
            subjectId: $(_this).data('role')
        };
        
        $('input,select', '#edit-role-modal .modal-body').each(function() {
            data[$(this).attr('name')] = $.trim($(this).val());
        });

        if (data.name) {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: data,
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
                $('.icon-cog', $(this)).toggleClass('text-muted text-info');
                $('.icon-cog', $(this)).prop('disabled', false);
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
 * User List Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
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
                        $(btn).attr({
                            'class': 'aam-row-action icon-lock text-danger',
                            'title': aam.__('Unlock User'),
                            'data-original-title': aam.__('Unlock User')
                        });
                    } else {
                        $(btn).attr({
                            'class': 'aam-row-action icon-lock-open-alt text-warning',
                            'title': aam.__('Lock User'),
                            'data-original-title': aam.__('Lock User')
                        });
                    }
                } else {
                    aam.notification('danger', aam.__('Failed to block user'));
                }
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            }
        });
    }
    
    /**
     * 
     * @param {type} id
     * @param {type} btn
     * @returns {undefined}
     */
    function switchToUser(id, btn) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'switchToUser',
                _ajax_nonce: aamLocal.nonce,
                user: id
            },
            beforeSend: function () {
                $(btn).attr('class', 'aam-row-action icon-spin4 animate-spin');
            },
            success: function (response) {
                if (response.status === 'success') {
                    location.href = response.redirect;
                } else {
                    aam.notification('danger', response.reason);
                }
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            },
            complete: function () {
                $(btn).attr('class', 'aam-row-action icon-exchange text-success');
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
            {visible: false, targets: [0, 1, 4]}
        ],
        language: {
            search: '_INPUT_',
            searchPlaceholder: aam.__('Search User'),
            info: aam.__('_TOTAL_ user(s)'),
            infoFiltered: ''
        },
        initComplete: function () {
            if (!aam.isUI()  && parseInt(aamLocal.caps.create_users)) {
                var create = $('<a/>', {
                    'href': '#',
                    'class': 'btn btn-primary'
                }).html('<i class="icon-plus"></i> ' + aam.__('Create')).bind('click', function (event) {
                    event.preventDefault();
                    window.open(aamLocal.url.addUser, '_blank');
                });

                $('.dataTables_filter', '#user-list_wrapper').append(create);
            }
        },
        createdRow: function (row, data) {
            if (isCurrent(data[0])) {
                $('td:eq(0)', row).html('<strong class="aam-highlight">' + data[2] + '</strong>');
            } else {
                $('td:eq(0)', row).html('<span>' + data[2] + '</span>');
            }

            //add subtitle
            $('td:eq(0)', row).append(
                $('<i/>', {'class': 'aam-row-subtitle'}).html(
                    aam.__('Role') + ': ' + data[1] + '; ID: <b>' + data[0] + '</b>'
                )
            );

            var actions = data[3].split(',');
            var container = $('<div/>', {'class': 'aam-row-actions'});
            
            if ($.trim(data[3])) { 
                $.each(actions, function (i, action) {
                switch (action) {
                    case 'manage':
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-cog ' + (isCurrent(data[0]) ? 'text-muted': 'text-info')
                        }).bind('click', function () {
                            if (!$(this).prop('disabled')) {
                                $(this).prop('disabled', true);
                                aam.setSubject('user', data[0], data[2], data[4]);
                                $('td:eq(0) span', row).replaceWith(
                                    '<strong class="aam-highlight">' + data[2] + '</strong>'
                                );
                                $('i.icon-cog', container).attr('class', 'aam-row-action icon-cog text-muted');
                                
                                if (!aam.isUI()) {
                                    $('i.icon-cog', container).attr('class', 'aam-row-action icon-spin4 animate-spin');
                                    aam.fetchContent();
                                    $('i.icon-spin4', container).attr('class', 'aam-row-action icon-cog text-muted');
                                } else {
                                    $.aam.loadAccessForm($('#load-post-object-type').val(), $('#load-post-object').val(), $(this));
                                }
                            }
                        }).attr({
                            'data-toggle': "tooltip",
                            'title': aam.__('Manage User')
                        })).prop('disabled', (isCurrent(data[0]) ? true: false));
                        break;

                    case 'edit':
                        if (!aam.isUI()) {
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-pencil text-info'
                        }).bind('click', function () {
                            window.open(
                                    aamLocal.url.editUser + '?user_id=' + data[0], '_blank'
                                    );
                        }).attr({
                            'data-toggle': "tooltip",
                            'title': aam.__('Edit User')
                        }));
                    }
                        break;

                    case 'lock':
                        if (!aam.isUI()) {
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-lock-open-alt text-warning'
                        }).bind('click', function () {
                            blockUser(data[0], $(this));
                        }).attr({
                            'data-toggle': "tooltip",
                            'title': aam.__('Lock User')
                        }));
                    }
                        break;

                    case 'unlock':
                        if (!aam.isUI()) {
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-lock text-danger'
                        }).bind('click', function () {
                            blockUser(data[0], $(this));
                        }).attr({
                            'data-toggle': "tooltip",
                            'title': aam.__('Unlock User')
                        }));
                    }
                        break;

                    case 'switch':
                        if (!aam.isUI()) {
                        $(container).append($('<i/>', {
                            'class': 'aam-row-action icon-exchange text-success'
                        }).bind('click', function () {
                            switchToUser(data[0], $(this));
                        }).attr({
                            'data-toggle': "tooltip",
                            'title': aam.__('Switch To User')
                        }));
                    }
                        break;

                    default:
                        break;
                }
            });
            } else {
                $(container).append($('<i/>', {
                    'class': 'aam-row-action text-muted'
                }).text('---'));
            }
            $('td:eq(1)', row).html(container);
        }
    });

    //add setSubject hook
    aam.addHook('setSubject', function () {
        //clear highlight
        $('tbody tr', '#user-list').each(function () {
            if ($('strong', $(this)).length) {
                var highlight = $('strong', $(this));
                $('.icon-cog', $(this)).toggleClass('text-muted text-info');
                $('.icon-cog', $(this)).prop('disabled', false);
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
 * Visitor Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
 */
(function ($) {

    $('document').ready(function() {
         $('#manage-visitor').bind('click', function (event) {
            var _this = this;
            
            event.preventDefault();
            aam.setSubject('visitor', null, aam.__('Anonymous'), 0);
            $('i.icon-cog', $(this)).attr('class', 'icon-spin4 animate-spin');
            
            if (!aam.isUI()) {
                aam.fetchContent();
                $('i.icon-spin4', $(this)).attr('class', 'icon-cog');
            } else {
                $.aam.loadAccessForm($('#load-post-object-type').val(), $('#load-post-object').val(), null, function () {
                    $('i.icon-spin4', $(_this)).attr('class', 'icon-cog');
                });
            }
            //hide post & pages access control groups that belong to backend
            $('.aam-backend-post-access').hide();
        });
    });
    
})(jQuery);

/**
 * Default Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
 */
(function ($) {

    $('document').ready(function() {
        $('#manage-default').bind('click', function (event) {
            var _this = this;
            
            event.preventDefault();
            aam.setSubject('default', null, aam.__('All Users, Roles and Visitor'), 0);
            $('i.icon-cog', $(this)).attr('class', 'icon-spin4 animate-spin');
            if (!aam.isUI()) {
                aam.fetchContent();
                $('i.icon-spin4', $(this)).attr('class', 'icon-cog');
            } else {
                $.aam.loadAccessForm($('#load-post-object-type').val(), $('#load-post-object').val(), null, function () {
                    $('i.icon-spin4', $(_this)).attr('class', 'icon-cog');
                });
            }
        });
    });
    
})(jQuery);


/**
 * Admin Menu Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
 */
(function ($) {

    /**
     * 
     * @returns {undefined}
     */
    function initialize() {
        $('.aam-restrict-menu').each(function () {
            $(this).bind('click', function () {
                var status = $('i', $(this)).hasClass('icon-eye-off');
                var target = $(this).data('target');

                $('i', $(this)).attr('class', 'icon-spin4 animate-spin');

                var result = aam.save($(this).data('menu-id'), status, 'menu');

                if (result.status === 'success') {
                    if (status) { //locked the menu
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
                            'class': 'aam-panel-title-icon icon-eye-off text-danger'
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
                                '<i class="icon-eye-off"></i>' + aam.__('Restrict Menu')
                        );
                        $('.panel-title .icon-eye-off', target + '-heading').remove();
                    }
                } else {
                    $(this).attr('checked', !status);
                }
            });
        });

        $('input[type="checkbox"]', '#admin-menu').each(function () {
            $(this).bind('click', function () {
                aam.save(
                    $(this).data('menu-id'),
                    $(this).attr('checked') ? true : false,
                    'menu'
                );
            });
        });
        
        //reset button
        $('#menu-reset').bind('click', function (event) {
            event.preventDefault();
            
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'Menu.reset',
                    _ajax_nonce: aamLocal.nonce,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id
                },
                success: function (response) {
                    if (response.status === 'success') {
                        aam.fetchContent();
                    }
                }
            });
        });
        
        aam.readMore($('#admin-menu-help'));
    }

    aam.addHook('init', initialize);

})(jQuery);


/**
 * Metaboxes & Widgets Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
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
        
        $('#init-url-btn').bind('click', function (event) {
            event.preventDefault();

            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'Metabox.initURL',
                    _ajax_nonce: aamLocal.nonce,
                    url: $('#init-url').val()
                },
                beforeSend: function () {
                    $('#init-url-btn').text(aam.__('Processing'));
                },
                success: function (response) {
                    if (response.status === 'success') {
                        $('#init-url-modal').modal('hide');
                        getContent();
                    } else {
                        aam.notification(
                            'danger', aam.__('Failed to initialize URL')
                        );
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application error'));
                },
                complete: function () {
                    $('#init-url-btn').text(aam.__('Initialize'));
                    $('#init-url-modal').modal('hide');
                }
            });
        });
        
         //reset button
        $('#metabox-reset').bind('click', function (event) {
            event.preventDefault();
            
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'Metabox.reset',
                    _ajax_nonce: aamLocal.nonce,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id
                },
                success: function (response) {
                    if (response.status === 'success') {
                        aam.fetchContent();
                    }
                }
            });
        });

        $('input[type="checkbox"]', '#metabox-list').each(function () {
            $(this).bind('click', function () {
                aam.save(
                    $(this).data('metabox'),
                    $(this).attr('checked') ? true : false,
                    'metabox'
                );
            });
        });
    }

    aam.addHook('init', initialize);

})(jQuery);


/**
 * Capabilities Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
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
                infoEmpty: aam.__('Nothing to show'),
                lengthMenu: '_MENU_'
            },
            createdRow: function (row, data) {
                var actions = data[3].split(',');

                var container = $('<div/>', {'class': 'aam-row-actions'});
                $.each(actions, function (i, action) {
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
                            
                        case 'edit':
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action icon-pencil text-warning'
                            }).bind('click', function () {
                                $('#capability-id').val(data[0]);
                                $('#update-capability-btn').attr('data-cap', data[0]);
                                $('#edit-capability-modal').modal('show');
                            }));
                            break;
                            
                        case 'delete':
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action icon-trash-empty text-danger'
                            }).bind('click', function () {
                                var message = $('.aam-confirm-message', '#delete-capability-modal');
                                $(message).html(message.data('message').replace(
                                        '%s', '<b>' + data[0] + '</b>')
                                        );
                                $('#capability-id').val(data[0]);
                                $('#delete-capability-btn').attr('data-cap', data[0]);
                                $('#delete-capability-modal').modal('show');
                            }));
                            break;

                        default:
                            aam.triggerHook('decorate-capability-row', {
                                action: action,
                                container: container,
                                data: data
                            });
                            break;
                    }
                });
                $('td:eq(2)', row).html(container);
            }
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
        
        $('#add-capability-modal').on('shown.bs.modal', function (e) {
            $('#new-capability-name').val('');
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
                        capability: capability,
                        subject: aam.getSubject().type,
                        subjectId: aam.getSubject().id
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
        
        $('#update-capability-btn').bind('click', function (event) {
            event.preventDefault();
            
            var btn = this;
            var cap = $.trim($('#capability-id').val());
            
            if (cap) {
                $.ajax(aamLocal.ajaxurl, {
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'aam',
                        sub_action: 'Capability.update',
                        _ajax_nonce: aamLocal.nonce,
                        capability: $(this).attr('data-cap'),
                        updated: cap
                    },
                    beforeSend: function () {
                        $(btn).text(aam.__('Saving...')).attr('disabled', true);
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('#edit-capability-modal').modal('hide');
                            $('#capability-list').DataTable().ajax.reload();
                        } else {
                            aam.notification(
                                'danger', aam.__('Failed to update capability')
                            );
                        }
                    },
                    error: function () {
                        aam.notification('danger', aam.__('Application error'));
                    },
                    complete: function () {
                        $(btn).text(aam.__('Update Capability')).attr(
                                'disabled', false
                        );
                    }
                });
            }
        });
        
        $('#delete-capability-btn').bind('click', function (event) {
            event.preventDefault();
            
            var btn = this;
            
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'Capability.delete',
                    _ajax_nonce: aamLocal.nonce,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id,
                    capability: $(this).attr('data-cap')
                },
                beforeSend: function () {
                    $(btn).text(aam.__('Deleting...')).attr('disabled', true);
                },
                success: function (response) {
                    if (response.status === 'success') {
                        $('#delete-capability-modal').modal('hide');
                        $('#capability-list').DataTable().ajax.reload();
                    } else {
                        aam.notification(
                            'danger', aam.__('Failed to delete capability')
                        );
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application error'));
                },
                complete: function () {
                    $(btn).text(aam.__('Delete Capability')).attr(
                            'disabled', false
                    );
                }
            });
        });
        
        //reset button
        $('#capability-reset').bind('click', function (event) {
            event.preventDefault();
            
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'Capability.reset',
                    _ajax_nonce: aamLocal.nonce,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id
                },
                success: function (response) {
                    if (response.status === 'success') {
                        aam.fetchContent();
                    }
                }
            });
        });
    }

    aam.addHook('init', initialize);

})(jQuery);


/**
 * Posts & Pages Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
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
     * @param {type} callback
     * @returns {undefined}
     */
    $.aam.loadAccessForm = function(object, id, btn, callback) {
        //reset the form first
        var container = $('.aam-access-form[data-type="' + object + '"]');
        $('#post-overwritten').addClass('hidden');
        
        //show overlay if present
        $('.aam-overlay', container).show();
        
        //reset data preview elements
        $('[data-preview]', container).text('');

        $('.aam-row-action', container).each(function () {
            $(this).attr({
                'class': 'aam-row-action text-muted icon-check-empty',
                'data-type': object,
                'data-id': id
            });

            //initialize each access property
            $(this).unbind('click').bind('click', function (event) {
                event.preventDefault();

                var checked = !$(this).hasClass('icon-check');

                $(this).attr('class', 'aam-row-action icon-spin4 animate-spin');
                var response = save(
                        $(this).data('property'),
                        checked ? 1 : 0,
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
                $(btn).attr('data-class', $(btn).attr('class'));
                $(btn).attr('class', 'aam-row-action icon-spin4 animate-spin');
            },
            success: function (response) {
                //iterate through each property
                for (var property in response.access) {
                    var checked  = (parseInt(response.access[property]) ? 'text-danger icon-check' : 'text-muted icon-check-empty');
                    var checkbox = $('[data-property="' + property + '"]', container);

                    if (checkbox.length) {
                        checkbox.attr({
                            'class': 'aam-row-action ' + checked
                        });
                    } else {
                        $('[data-preview="' + property + '"]', container).prop('data-original-value', response.access[property]);
                        $('[data-preview="' + property + '"]', container).html(
                                preparePreview(response.access[property])
                        );
                    }
                }
                
                //check metadata and show message if necessary
                if (response.meta.overwritten === true) {
                    $('#post-overwritten').removeClass('hidden');
                    //add some specific attributes to reset button
                    $('#post-reset').attr({
                        'data-type': object,
                        'data-id': id
                    });
                }
                
                $('.extended-post-access-btn').attr({
                    'data-type': object,
                    'data-id': id
                });

                $('#post-list_wrapper').addClass('aam-hidden');
                container.addClass('active');
                
                if (typeof callback === 'function') {
                    callback.call();
                }
                
                //update dynamic labels
                if ($('#load-post-object-title').length) {
                    var marker = $('#load-post-object-title').val();
                } else {
                    marker = $('.aam-post-breadcrumb span').text();
                }
                $('[data-dynamic-post-label]').each(function() {
                    $(this).html(
                        $(this).attr('data-dynamic-post-label').replace(/%s/g, '<b>' + marker + '</b>')
                    );
                });
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            },
            complete: function () {
                $(btn).attr('class', $(btn).attr('data-class')).removeAttr('data-class');
                //show overlay if present
                $('.aam-overlay', container).hide();
            }
        });
    };

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
                } else {
                    $('#post-overwritten').removeClass('hidden');
                    //add some specific attributes to reset button
                    $('#post-reset').attr({
                        'data-type': object,
                        'data-id': object_id
                    });
                }
                result = response;
            },
            error: function () {
                aam.notification('danger', aam.__('Application error'));
            }
        });

        return result;
    }
    
    /**
     * 
     * @param {type} text
     * @returns {String}
     */
    function preparePreview(preview) {
        if (typeof preview === 'string') { 
            preview = preview.replace(/<\/?[^>]+(>|$)/g, "");

            if (preview.length > 25) {
                preview = preview.substring(0, 22)  + '...';
            }
        }
        
        return preview;
    }
    
    /**
     * 
     * @returns {undefined}
     */
    function initialize() {
        //reset filter to default list of post types
        filter.type = null;

        //initialize the role list table
        $('#post-list').DataTable({
            autoWidth: false,
            ordering: false,
            pagingType: 'simple',
            processing: true,
            serverSide: true,
            ajax: {
                url: aamLocal.ajaxurl,
                type: 'POST',
                data: function (data) {
                    data.action = 'aam';
                    data.sub_action = 'Post.getTable';
                    data._ajax_nonce = aamLocal.nonce;
                    data.subject = aam.getSubject().type;
                    data.subjectId = aam.getSubject().id;
                    data.type = filter.type;
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
            rowCallback: function (row, data) {
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
                        
                        //finally reload the data
                        $('#post-list').DataTable().ajax.reload();

                        //update the breadcrumb
                        addBreadcrumbLevel('type', data[0], data[3]);

                    }).html(data[3]);
                    $('td:eq(1)', row).html(link);
                } else { //reset the post/term title
                    $('td:eq(1)', row).html(data[3]);
                }

                //update the actions
                var actions = data[4].split(',');

                var container = $('<div/>', {'class': 'aam-row-actions'});
                $.each(actions, function (i, action) {
                    switch (action) {
                        case 'drilldown':
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action text-success icon-level-down'
                            }).bind('click', function () {
                                if (!$(this).prop('disabled')) {
                                    $(this).prop('disabled', true);
                                    //set filter
                                    filter[data[2]] = data[0];
                                    //finally reload the data
                                    $('#post-list').DataTable().ajax.reload();
                                    //update the breadcrumb
                                    addBreadcrumbLevel('type', data[0], data[3]);
                                }
                            }).attr({
                                'data-toggle': "tooltip",
                                'title': aam.__('Drill-Down')
                            }));
                            $('.tooltip').remove();
                            break;
                            
                        case 'manage':
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action text-info icon-cog'
                            }).bind('click', function () {
                                if (!$(this).prop('disabled')) {
                                    $(this).prop('disabled', true);
                                    $.aam.loadAccessForm(data[2], data[0], $(this), function () {
                                        addBreadcrumbLevel('edit', data[2], data[3]);
                                        $(this).prop('disabled', false);
                                    });
                                }
                            }).attr({
                                'data-toggle': "tooltip",
                                'title': aam.__('Manage Access')
                            }));
                            $('.tooltip').remove();
                            break;

                        case 'edit' :
                            $(container).append($('<i/>', {
                                'class': 'aam-row-action text-warning icon-pencil'
                            }).bind('click', function () {
                                window.open(data[1], '_blank');
                            }).attr({
                                'data-toggle': "tooltip",
                                'title': aam.__('Edit')
                            }));
                            break;

                        default:
                            aam.triggerHook('post-action', {
                                container: container,
                                action   : action,
                                data     : data
                            });
                            break;
                    }
                });
                $('td:eq(2)', row).html(container);
            }
        });

        //initialize the breadcrumb
        $('.aam-post-breadcrumb').delegate('a', 'click', function (event) {
            event.preventDefault();

            filter.type = $(this).data('id');
            $('#post-list').DataTable().ajax.reload();
            $(this).nextAll().remove();
            $('.aam-slide-form').removeClass('active');
            $('#post-list_wrapper').removeClass('aam-hidden');
            $('#post-overwritten').addClass('hidden');
        });

        //reset button
        $('#post-reset').bind('click', function (event) {
            event.preventDefault();
            
            var type = $(this).attr('data-type');
            var id   = $(this).attr('data-id');

            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'Post.reset',
                    _ajax_nonce: aamLocal.nonce,
                    type: type,
                    id: id,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id
                },
                beforeSend: function () {
                },
                success: function (response) {
                    if (response.status === 'success') {
                        $('#post-overwritten').addClass('hidden');
                        $.aam.loadAccessForm(type, id);
                    }
                }
            });
        });

        //go back button
        $('.aam-slide-form').delegate('.post-back', 'click', function (event) {
            event.preventDefault();

            var type = $(this).parent().data('type');

            $('.aam-slide-form[data-type="' + type + '"]').removeClass('active');
            $('#post-list_wrapper').removeClass('aam-hidden');
            $('.aam-post-breadcrumb span:last').remove();
            $('#post-overwritten').addClass('hidden');
        });
        
        //load referenced post
        if ($('#load-post-object').val()) {
            $.aam.loadAccessForm(
                    $('#load-post-object-type').val(), 
                    $('#load-post-object').val()
            );
        }
        
        $('.change-password').each(function() {
            $(this).bind('click', function(event) {
                event.preventDefault();
                 
                var password = $('#' + $(this).attr('data-preview-id')).text();
                
                if (password !== '') {
                    $('#password-value').val(password);
                } else {
                    $('#password-value').val('');
                }
                
                $('#change-password-btn').attr({
                    'data-ref': $(this).attr('data-ref'),
                    'data-preview-id': $(this).attr('data-preview-id')
                });

                $('#password-modal').modal('show');
            });
        });
        
        $('#change-password-btn').bind('click', function() {
            $(this).text(aam.__('Saving...'));
            
            var password = $('#password-value').val();
            var response = save(
                    $(this).attr('data-ref'),
                    password,
                    $(this).attr('data-type'),
                    $(this).attr('data-id')
            );
            
            if (response.status === 'success') {
                var preview = $('#' + $(this).attr('data-preview-id'));
                var action  = $('.aam-row-action', preview.parent().parent().parent());
                
                preview.html(password ? password : '');
                
                if ($(action).hasClass('icon-check-empty')) {
                    action.trigger('click');
                }
            }
            $('#password-modal').modal('hide');
            
            $(this).text(aam.__('Set'));
        });
        
        $('.change-location').each(function() {
            $(this).bind('click', function(event) {
                event.preventDefault();
                
                var location = $('#' + $(this).attr('data-preview-id')).text();
                
                if (location !== '') {
                    $('#location-value').val(location);
                } else {
                    $('#location-value').val('');
                }
                
                $('#change-location-btn').attr({
                    'data-ref': $(this).attr('data-ref'),
                    'data-preview-id': $(this).attr('data-preview-id')
                });

                $('#location-modal').modal('show');
            });
        });
        
        $('#change-location-btn').bind('click', function() {
            $(this).text(aam.__('Saving...'));
            
            var redirect = $('#location-value').val();
            var response = save(
                    $(this).attr('data-ref'),
                    redirect,
                    $(this).attr('data-type'),
                    $(this).attr('data-id')
            );
            
            if (response.status === 'success') {
                var preview = $('#' + $(this).attr('data-preview-id'));
                var action  = $('.aam-row-action', preview.parent().parent().parent());
                
                preview.html(redirect ? redirect : '');
                
                if ($(action).hasClass('icon-check-empty')) {
                    action.trigger('click');
                }
            }
            $('#location-modal').modal('hide');
            $(this).text(aam.__('Set'));
        });
        
        $('.change-expiration').each(function() {
            $(this).bind('click', function(event) {
                event.preventDefault();
                
                var expiration = $('#' + $(this).attr('data-preview-id')).text();
                
                if (expiration !== '') {
                    $('#expiration-value').val(expiration);
                } else {
                    $('#expiration-value').val('');
                }
                
                $('#change-expiration-btn').attr({
                    'data-ref': $(this).attr('data-ref'),
                    'data-preview-id': $(this).attr('data-preview-id')
                });

                $('#expiration-modal').modal('show');
            });
        });
        
        $('#change-expiration-btn').bind('click', function() {
            $(this).text(aam.__('Saving...'));
            
            var expires  = $('#expiration-value').val();
            var response = save(
                    $(this).attr('data-ref'),
                    expires,
                    $(this).attr('data-type'),
                    $(this).attr('data-id')
            );
            
            if (response.status === 'success') {
                var preview = $('#' + $(this).attr('data-preview-id'));
                var action  = $('.aam-row-action', preview.parent().parent().parent());
                preview.html(response.value);
                
                if ($(action).hasClass('icon-check-empty')) {
                    action.trigger('click');
                }
            }
            $('#expiration-modal').modal('hide');
            $(this).text(aam.__('Set'));
        });
        
        $('.change-teaser').each(function() {
            $(this).bind('click', function(event) {
                event.preventDefault();
                
                var teaser = $('#' + $(this).attr('data-preview-id')).prop('data-original-value');
                $('#teaser-value').val(teaser);
                
                $('#change-teaser-btn').attr({
                    'data-ref': $(this).attr('data-ref'),
                    'data-preview-id': $(this).attr('data-preview-id')
                });
                
                $('#teaser-modal').modal('show');
            });
        });
        
        $('#change-teaser-btn').bind('click', function() {
            $(this).text(aam.__('Saving...'));
            
            var teaser = $('#teaser-value').val();
            var response = save(
                    $(this).attr('data-ref'),
                    teaser,
                    $(this).attr('data-type'),
                    $(this).attr('data-id')
            );
            
            if (response.status === 'success') {
                var preview = $('#' + $(this).attr('data-preview-id'));
                var action  = $('.aam-row-action', preview.parent().parent().parent());
                
                preview.prop('data-original-value', teaser);
                preview.html(preparePreview(teaser));
                
                if ($(action).hasClass('icon-check-empty')) {
                    action.trigger('click');
                }
            }
            $('#teaser-modal').modal('hide');
            $(this).text(aam.__('Save'));
        });
    }

    aam.addHook('init', initialize);

})(jQuery);

/**
 * Redirect Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
 */
(function ($) {

    /**
     * 
     * @param {type} param
     * @param {type} value
     * @returns {undefined}
     */
    function save(param, value) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'Redirect.save',
                _ajax_nonce: aamLocal.nonce,
                subject: aam.getSubject().type,
                subjectId: aam.getSubject().id,
                param: param,
                value: value
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#aam-redirect-overwrite').show();
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
        var container = '#redirect-content';
        
        $('input[type="radio"]', container).each(function () {
            $(this).bind('click', function () {
                //hide group
                $('.' + $(this).data('group')).hide();
                
                //show the specific one
                $($(this).data('action')).show();
                
                //save redirect type
                save($(this).attr('name'), $(this).val());
            });
        });
        
        $('input[type="text"],select,textarea', container).each(function () {
            $(this).bind('change', function () {
                //save redirect type
                save($(this).attr('name'), $(this).val());
            });
        });
        
        $('#redirect-reset').bind('click', function () {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    action: 'aam',
                    sub_action: 'Redirect.reset',
                    _ajax_nonce: aamLocal.nonce,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id
                },
                success: function (response) {
                    if (response.status === 'success') {
                         aam.fetchContent();
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application Error'));
                }
            });
        });
        
    }

    aam.addHook('init', initialize);

})(jQuery);

/**
 * Login Redirect Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
 */
(function ($) {

    /**
     * 
     * @param {type} param
     * @param {type} value
     * @returns {undefined}
     */
    function save(param, value) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'LoginRedirect.save',
                _ajax_nonce: aamLocal.nonce,
                subject: aam.getSubject().type,
                subjectId: aam.getSubject().id,
                param: param,
                value: value
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#aam-login-redirect-overwrite').show();
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
        var container = '#login_redirect-content';
        
        $('input[type="radio"]', container).each(function () {
            $(this).bind('click', function () {
                //hide all fields
                $('.login-redirect-action').hide();
                
                //show the specific one
                $($(this).data('action')).show();
                
                //save redirect type
                save($(this).attr('name'), $(this).val());
            });
        });
        
        $('input,select,textarea', container).each(function () {
            $(this).bind('change', function () {
                if ($(this).is('input[type="checkbox"]')) {
                    var val = $(this).prop('checked') ? $(this).val() : 0;
                } else {
                    val = $.trim($(this).val());
                }
                //save redirect type
                save($(this).attr('name'), val);
            });
        });
        
        $('#login-redirect-reset').bind('click', function () {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    action: 'aam',
                    sub_action: 'LoginRedirect.reset',
                    _ajax_nonce: aamLocal.nonce,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id
                },
                success: function (response) {
                    if (response.status === 'success') {
                         aam.fetchContent();
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application Error'));
                }
            });
        });
    }

    aam.addHook('init', initialize);

})(jQuery);

/**
 * Logout Redirect Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
 */
(function ($) {

    /**
     * 
     * @param {type} param
     * @param {type} value
     * @returns {undefined}
     */
    function save(param, value) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'LogoutRedirect.save',
                _ajax_nonce: aamLocal.nonce,
                subject: aam.getSubject().type,
                subjectId: aam.getSubject().id,
                param: param,
                value: value
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#aam-logout-redirect-overwrite').show();
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
        var container = '#logout_redirect-content';
        
        $('input[type="radio"]', container).each(function () {
            $(this).bind('click', function () {
                //hide all fields
                $('.logout-redirect-action').hide();
                
                //show the specific one
                $($(this).data('action')).show();
                
                //save redirect type
                save($(this).attr('name'), $(this).val());
            });
        });
        
        $('input[type="text"],select,textarea', container).each(function () {
            $(this).bind('change', function () {
                //save redirect type
                save($(this).attr('name'), $(this).val());
            });
        });
        
        $('#logout-redirect-reset').bind('click', function () {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    action: 'aam',
                    sub_action: 'LogoutRedirect.reset',
                    _ajax_nonce: aamLocal.nonce,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id
                },
                success: function (response) {
                    if (response.status === 'success') {
                         aam.fetchContent();
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application Error'));
                }
            });
        });
    }

    aam.addHook('init', initialize);

})(jQuery);

/**
 * Teaser Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
 */
(function ($) {

    /**
     * 
     * @param {type} param
     * @param {type} value
     * @returns {undefined}
     */
    function save(param, value) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: 'Teaser.save',
                _ajax_nonce: aamLocal.nonce,
                subject: aam.getSubject().type,
                subjectId: aam.getSubject().id,
                param: param,
                value: value
            },
            success: function(response) {
                if (response.status === 'success') {
                    $('#aam-teaser-overwrite').show();
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
        var container = '#teaser-content';
        
        $('input, textarea', container).each(function () {
            $(this).bind('change', function () {
                if ($(this).is('input[type="checkbox"]')) {
                    var val = $(this).prop('checked') ? $(this).val() : 0;
                } else {
                    val = $.trim($(this).val());
                }
                //save redirect type
                save($(this).attr('name'), val);
            });
        });
        
        $('#teaser-reset').bind('click', function () {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    action: 'aam',
                    sub_action: 'Teaser.reset',
                    _ajax_nonce: aamLocal.nonce,
                    subject: aam.getSubject().type,
                    subjectId: aam.getSubject().id
                },
                success: function (response) {
                    if (response.status === 'success') {
                        aam.fetchContent();
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application Error'));
                }
            });
        });
    }

    aam.addHook('init', initialize);

})(jQuery);

/**
 * 404 Redirect Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
 */
(function ($) {

    /**
     * 
     * @param {type} param
     * @param {type} value
     * @returns {undefined}
     */
    function save(param, value) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'aam',
                sub_action: '404Redirect.save',
                _ajax_nonce: aamLocal.nonce,
                subject: aam.getSubject().type,
                subjectId: aam.getSubject().id,
                param: param,
                value: value
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
        var container = '#404redirect-content';
        
        $('input[type="radio"]', container).each(function () {
            $(this).bind('click', function () {
                //hide group
                $('.aam-404redirect-action').hide();
                
                //show the specific one
                $($(this).data('action')).show();
                
                //save redirect type
                save($(this).attr('name'), $(this).val());
            });
        });
        
        $('input[type="text"],select,textarea', container).each(function () {
            $(this).bind('change', function () {
                //save redirect type
                save($(this).attr('name'), $(this).val());
            });
        });
    }

    aam.addHook('init', initialize);

})(jQuery);

/**
 * Extensions Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
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
                        $('#installation-error').html(response.error);
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
                    'data:application/zip;base64,' + dump.content,
                    dump.title + '.zip',
                    'application/zip'
            );
            $('#extension-notification-modal').modal('hide');
        });
    }

    aam.addHook('init', initialize);

})(jQuery);


/**
 * Utilities Interface
 * 
 * @param {type} $
 * 
 * @returns {undefined}
 */
(function ($) {
    
    /**
     * 
     * @param {type} param
     * @param {type} value
     * @returns {undefined}
     */
    function save(param, value) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            async: false,
            data: {
                action: 'aam',
                sub_action: 'Utility.save',
                _ajax_nonce: aamLocal.nonce,
                param: param,
                value: value
            },
            error: function () {
                aam.notification('danger', aam.__('Application Error'));
            }
        });
    }

    /**
     * 
     * @returns {undefined}
     */
    function initialize() {
        $('input[type="checkbox"]', '#utilities-content').bind('change', function () {
            save($(this).attr('name'), ($(this).prop('checked') ? 1 : 0));
        });
        
        $('input[type="text"]', '#utilities-content').bind('change', function() {
            save($(this).attr('name'), $(this).val());
        });
        
        $('#clear-settings').bind('click', function () {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    action: 'aam',
                    sub_action: 'Utility.clear',
                    _ajax_nonce: aamLocal.nonce
                },
                success: function (response) {
                    if (response.status === 'success') {
                        location.reload();
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application Error'));
                }
            });
        });
        
        $('#clear-cache').bind('click', function () {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                async: false,
                data: {
                    action: 'aam',
                    sub_action: 'Utility.clearCache',
                    _ajax_nonce: aamLocal.nonce
                },
                success: function (response) {
                    if (response.status === 'success') {
                        location.reload();
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application Error'));
                }
            });
        });
    }

    aam.addHook('init', initialize);

})(jQuery);

/**
 * Security Interface
 * 
 * @param {type} $
 * 
 * @returns {undefined}
 */
(function ($) {
    
    /**
     * 
     * @param {type} param
     * @param {type} value
     * @returns {undefined}
     */
    function save(param, value) {
        $.ajax(aamLocal.ajaxurl, {
            type: 'POST',
            dataType: 'json',
            async: false,
            data: {
                action: 'aam',
                sub_action: 'Security.save',
                _ajax_nonce: aamLocal.nonce,
                param: param,
                value: value
            },
            error: function () {
                aam.notification('danger', aam.__('Application Error'));
            }
        });
    }

    /**
     * 
     * @returns {undefined}
     */
    function initialize() {
        $('input[type="checkbox"]', '#security-content').bind('change', function () {
            save($(this).attr('name'), ($(this).prop('checked') ? 1 : 0));
        });
        
        $('input[type="text"]', '#security-content').bind('change', function() {
            save($(this).attr('name'), $(this).val());
        });
    }

    aam.addHook('init', initialize);

})(jQuery);


/**
 * Main Panel Interface
 * 
 * @param {jQuery} $
 * 
 * @returns {void}
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
                location.hash = $(this).data('feature');
                //trigger hook
                aam.triggerHook('menu-feature-click', $(this).data('feature'));
            });
        });
    }

    /**
     * 
     * @returns {undefined}
     */
    aam.fetchContent = function () {
        //referred object ID like post, page or any custom post type
        var object   = window.location.search.match(/&oid\=([^&]*)/);
        var type     = window.location.search.match(/&otype\=([^&]*)/);
        
        $.ajax(aamLocal.url.site, {
            type: 'POST',
            dataType: 'html',
            data: {
                action: 'aamc',
                _ajax_nonce: aamLocal.nonce,
                subject: this.getSubject().type,
                subjectId: this.getSubject().id,
                oid: object ? object[1] : null,
                otype: type ? type[1] : null
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
                //activate one of the menu items
                var item = $('li:eq(0)', '#feature-list');
        
                if (location.hash !== '') {
                    var hash = location.hash.substr(1);
                    if ($('li[data-feature="' + hash + '"]', '#feature-list').length) {
                        item = $('li[data-feature="' + hash + '"]', '#feature-list');
                    }
                }

                item.trigger('click');
            }
        });
    };

    aam.fetchContent(); //fetch default AAM content

})(jQuery);

/**
 * Miscellenious features
 * 
 */
(function ($) {
    $('#subscribe').bind('click', function() {
        var email = $('#subscribe-email').val();
        
        if (email) {
            $.ajax(aamLocal.ajaxurl, {
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'aam',
                    sub_action: 'subscribe',
                    _ajax_nonce: aamLocal.nonce,
                    email: email
                },
                beforeSend: function () {
                    $('#subscribe i').attr('class', 'icon-spin4 animate-spin');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        aam.notification('success', aam.__('Your are subscribed now!'));
                        $('#subscribe-email').val('');
                    } else {
                        aam.notification('danger', response.reason);
                    }
                },
                error: function () {
                    aam.notification('danger', aam.__('Application Error'));
                },
                complete: function() {
                    $('#subscribe i').attr('class', 'icon-rss');
                }
            });
        }
    });
    
})(jQuery);