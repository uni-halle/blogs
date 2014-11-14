/*global alert*/
/*jshint indent:4*/

if (typeof jQuery !== 'undefined') {

    (function($, undefined) {

        $.WordPressAjaxAdmin = function(element, options) {
            var self = this, block = self.block = $(element);

            self.options = $.extend({}, $.WordPressAjaxAdmin.defaults, options);

            block.delegate('.row-actions a, .row-actions-visible a', 'click', function(event) {
                var link, parent, row, skip;

                link = self.link = $(this);
                parent = self.parent = link.closest('span');
                row = self.row = parent.closest('tr');

                if (self.options.exclude) {
                    skip = false;
                    $.each(parent.attr('class').split(' '), function(i, c) {
                        if ($.inArray(c, self.options.exclude) > -1) {
                            skip = true;
                        }
                    });
                } else if (self.options.include) {
                    skip = true;
                    $.each(parent.attr('class').split(' '), function(i, c) {
                        if ($.inArray(c, self.options.include) > -1) {
                            skip = false;
                        }
                    });
                }

                if (skip) {
                    return;
                } else if (link.attr('target') === '_blank' || parent.length === 0) {
                    return;
                } else if (!parent.hasClass('view') && $.inArray(parent.attr('class'), self.options.ignore) < 0) {
                    event.preventDefault();
                }

                if (parent.hasClass('edit')) {
                    self.edit();
                } else if (parent.hasClass('top') || parent.hasClass('up') ||
                           parent.hasClass('down') || parent.hasClass('bottom')) {
                    self.move();
                } else if (parent.hasClass('trash')) {
                    self.trash();
                }
            });

            block.delegate('a.add', 'click', function(event) {
                event.preventDefault();
                self.link = $(this);
                self.add();
            });
        };

        $.WordPressAjaxAdmin.defaults = {
            ignore: []
        };

        $.WordPressAjaxAdmin.prototype = {

            add: function() {
                var options = this.options,
                    link = this.link,
                    parent = link.closest('div'),
                    tbody = parent.find('table:last tbody'),
                    first = tbody.find('tr:first'), inline;

                $.post(options.ajaxurl, $.extend({}, options.data, {
                    'action': options.actions.add,
                    'columns': tbody.closest('table').find('thead th').length
                }), function(response) {

                    if ( first.length ) {
                        inline = $(response.html).insertBefore( first );
                    } else {
                        inline = $(response.html).appendTo( tbody );
                    }

                    /* handle save and cancel buttons */

                    inline.find('a.cancel').click(function(){
                        first.show();
                        inline.remove();
                    });

                    inline.find('a.save').click(function(){
                        var loadingIcon = inline.find( 'p.submit' ).find( 'img.waiting, .spinner' );

                        loadingIcon.show().addClass( 'is-visible-inline-block' );
                        inline.find( '.awpcp-inline-form-error' ).remove();

                        inline.find('form').ajaxSubmit({
                            data: {'save': true},
                            dataType: 'json',
                            success: function(response) {
                                if ( response.status === 'success' || response.status === 'ok' ) {
                                    var row = $( response.html );

                                    inline.remove();
                                    tbody.append( row );

                                    if ( first.hasClass( 'empty-row' ) || first.hasClass( 'no-items' ) ) {
                                        first.remove();
                                    }

                                    if ( $.isFunction( options.onSuccess ) ) {
                                        options.onSuccess.apply( this, [ options.actions.add, row ] );
                                    }
                                } else {
                                    loadingIcon.hide().removeClass( 'is-visible-inline-block' );
                                    var errors = $( '<div class="awpcp-error awpcp-inline-form-error">' );
                                    $.each(response.errors, function(k,v) {
                                        errors.append(v + '</br>');
                                    });
                                    inline.find('p.submit').after(errors);
                                }
                            }
                        });
                    });

                    if ($.isFunction(options.onFormReady)) {
                        options.onFormReady.apply(this, [options.actions.add, inline]);
                    }

                    if (first.hasClass('empty-row')) {
                        first.hide();
                    }
                });
            },

            edit: function() {
                var options = this.options,
                    row = this.row, inline;

                $.post(options.ajaxurl, $.extend({}, options.data, {
                    'id': row.data('id'),
                    'action': options.actions.edit,
                    'columns': row.find('th, td').length
                }), function(response) {
                    inline = $(response.html).insertAfter(row);

                    inline.find('a.cancel').click(function() {
                        row.show();
                        inline.remove();
                    });

                    inline.find('a.save').click(function() {
                        var loadingIcon = inline.find( 'p.submit' ).find( 'img.waiting, .spinner' );

                        loadingIcon.show().addClass( 'is-visible-inline-block' );

                        inline.find('div.error').remove();
                        inline.find('form').ajaxSubmit({
                            data: $.extend({}, options.data, {save: true}),
                            dataType: 'json',
                            success: function(response) {
                                if ( response.status === 'success' || response.status === 'ok' ) {
                                    var newRow = $( response.html );

                                    row.replaceWith( newRow );
                                    inline.remove();

                                    if ( $.isFunction( options.onSuccess ) ) {
                                        options.onSuccess.apply( this, [ options.actions.edit, newRow ] );
                                    }
                                } else {
                                    loadingIcon.hide().removeClass( 'is-visible-inline-block' );
                                    var errors = $('<div class="awpcp-error awpcp-inline-form-error">');
                                    $.each(response.errors, function(k,v) {
                                        errors.append(v + '</br>');
                                    });
                                    inline.find('p.submit').after(errors);
                                }
                            }
                        });
                    });

                    if ($.isFunction(options.onFormReady)) {
                        options.onFormReady.apply(this, [options.actions.edit, inline]);
                    }

                    row.hide();
                });
            },

            move: function() {
                var options = this.options,
                    parent = this.parent,
                    row = this.row;

                $.post(options.ajaxurl, {
                    action: options.actions.move,
                    target: parent.attr('class'),
                    id: row.data('id')
                }, function(response) {
                    if (response.id === response.other) {
                        return;
                    }

                    var other = $(options.base + response.other);

                    if (response.target === 'top' || response.target === 'up') {
                        other.before(row);
                    } else if (response.target === 'down' || response.target === 'bottom') {
                        other.after(row);
                    }
                });
            },

            trash: function() {
                var options = this.options,
                    row = this.row, inline;

                $.post(options.ajaxurl, $.extend({}, options.data, {
                    'id': row.data('id'),
                    'action': options.actions.remove,
                    'columns': row.find('th, td').length
                }), function(response) {
                    inline = $(response.html).insertAfter(row);
                    inline.find('a.cancel').click(function() {
                        row.show();
                        inline.remove();
                    });

                    var form = inline.find('form'),
                        legend = form.find('label span');

                    inline.delegate('a.delete', 'click', function() {
                        var buttons = inline.find('a.delete'),
                            option = $(this).data('option'),
                            loadingIcon = inline.find( 'p.submit' ).find( 'img.waiting, .spinner' );

                        loadingIcon.show().addClass( 'is-visible-inline-block' );

                        form.ajaxSubmit({
                            data: { 'remove': true, 'option': option },
                            dataType: 'json',
                            success: function(response) {
                                var link = null, label;

                                // mission acomplished!
                                if (response.status === 'success' || response.status === 'ok' ) {
                                    row.remove();
                                    inline.remove();

                                    if ( $.isFunction( options.onSuccess ) ) {
                                        options.onSuccess.apply( this, [ options.actions.remove, row ] );
                                    }

                                // we need to ask something else to the user
                                } else if (response.status === 'confirm') {
                                    loadingIcon.hide().removeClass( 'is-visible-inline-block' );

                                    // create a set of options
                                    $.each(response.options, function(value, label) {
                                        link = $('<a></a>').attr({
                                            href: '#inline-edit' ,
                                            title: label,
                                            'class': 'button-primary delete alignright'
                                        }).text(label).data('option', value);
                                        buttons.eq(0).after(link.css('margin-left', '5px'));
                                    });
                                    buttons.remove();

                                    // update the form legend
                                    legend.text(response.message);

                                // ¬_¬
                                } else {
                                    loadingIcon.hide().removeClass( 'is-visible-inline-block' );
                                    form.find('div.error').remove();
                                    form.append('<div class="error"><p>' + response.message + '</p></div>');

                                    label = 'Delete';

                                    // create default Delete button
                                    link = $('<a></a>').attr({
                                        href: '#inline-edit' ,
                                        title: label,
                                        'class': 'button-primary delete alignright'
                                    }).text(label).data('option', 'delete');
                                    buttons.eq(0).after(link);
                                    buttons.remove();
                                }
                            }
                        });
                    });

                    if ( response.status === 'success' || response.status === 'ok' ) {
                        row.hide();
                    } else {
                        alert(response.message);
                    }
                });
            }
        };

        /**
         * Plugin to handle Model add, edit and delete actions
         */
        $.fn.admin = function(options) {
            return this.each(function() {
                $.noop(new $.WordPressAjaxAdmin($(this), options));
            });
        };

    })(jQuery);

}
