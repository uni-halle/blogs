if (typeof jQuery !== 'undefined') {

    (function($, undefined) {

        $.AWPCP.UpgradeForm = function(element) {
            var self = this;

            self.form = $(element);
            self.progressbar = self.form.find('.progress-bar-value');
            self.count = self.form.find('.pending-upgrades span');

            self.pending = $.AWPCP.get('pending-upgrades', []);

            self.total = false;
            self.action = self.form.attr('data-action');

            self.form.submit(function(event) {
                event.preventDefault();
                $(this).find(':submit').attr('disabled', true);
                self.update();
            });
        };

        $.extend($.AWPCP.UpgradeForm.prototype, {
            update: function() {
                var self = this;

                $.getJSON($.AWPCP.get('ajaxurl'), {
                    action: self.action
                }, function(response) {
                    if (response) {
                        self.total = self.total || response.total;

                        var p = 100 * ((self.total - response.remaining) / self.total);

                        if (!isNaN(p)) {
                            self.progressbar.animate({ width: p + '%' });
                        }

                        if (response.remaining > 0) {
                            setTimeout(function() { self.update(); }, 10);
                        } else {
                            self.finish();
                        }
                    }
                });
            },

            finish: function() {
                var self = this, current = false, next = false;

                for (var i = self.pending.length - 1; i >= 0; i = i - 1) {
                    if (self.pending[i].action === self.action) {
                        current = i;
                    } else if ( next === false ) {
                        next = i;
                    }
                }

                if (false === next) {
                    self.form.slideUp().closest('div').find('.awpcp-upgrade-completed-message').fadeIn();
                } else {
                    self.action = self.pending[next].action;

                    self.form.find(':submit').removeAttr('disabled');
                    self.progressbar.animate({ width: 0 });
                    self.count.text(self.pending.length - 1);
                }

                if (false !== current) {
                    self.pending.splice(current, 1);
                }
            }
        });

    })(jQuery);

    (function($, undefined) {

        $.AWPCP.StickyNotice = function(element) {
            var self = this;

            self.element = $(element);
            self.actions = self.element.find('.actions .button, .actions .button-primary');
            self.actions.click(function() {
                self.on_click($(this));
            });
        };

        $.extend($.AWPCP.StickyNotice.prototype, {
            on_click: function(button) {
                var self = this;

                $.ajax({
                    url: $.AWPCP.get('ajaxurl'),
                    type: 'POST',
                    data: {
                        'action': button.attr('data-action')
                    },
                    success: function() {
                        self.element.fadeOut(function() {
                            $(this).remove();
                        });
                    }
                });
            }
        });

    })(jQuery);

    (function($, undefined) {

        $.AWPCP.CategoriesChecklist = function(element) {
            var self = this, fn = $.fn.prop ? 'prop' : 'attr';

            self.element = $(element);
            self.parent = self.element.parent('div');
            self.checkboxes = self.parent.find('.category-checklist :checkbox');

            self.parent.find('a[data-categories]').click(function(event) {
                event.preventDefault();
                self.checkboxes[fn]('checked', $(this).attr('data-categories') === 'all');
            });
        };

    })(jQuery);

    (function($, undefined) {

        $(function() {
            $('#widget-modification-notice, #quick-start-guide-notice').each(function() {
                $.noop(new $.AWPCP.StickyNotice(this));
            });

            $('.awpcp-upgrade-form').each(function() {
                $.noop(new $.AWPCP.UpgradeForm(this));
            });
        });

    })(jQuery);
}
