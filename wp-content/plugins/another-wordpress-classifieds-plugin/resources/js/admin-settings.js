/*global AWPCP*/
AWPCP.run( 'awpcp/admin-settings', [ 'jquery', 'awpcp/settings-validator' ],
function( $, SettingsValidator ) {

    (function() {

        $.AWPCP.DateTimeSettings = function(element) {
            var self = this;

            self.element = $(element);

            self.time = self.element.find('#time-format');
            self.date = self.element.find('#date-format');
            self.format = self.element.find('#date-time-format');
            self.example = self.element.find('[example]');

            self.keep_format_strings = false;

            self.radios = self.element.find(':radio');
            self.radios.change(function() {
                if (!self.keep_format_strings) {
                    var value = $(this).val(),
                        descriptions = $.AWPCP.get('datetime-formats'),
                        description;

                    if (descriptions.hasOwnProperty($(this).val())) {
                        description = descriptions[value];
                    }

                    self.time.val(description.time);
                    self.date.val(description.date);
                    self.format.val(description.format);

                    self.update();
                }

                self.keep_format_strings = false;
            });

            self.element.find(':text').change(function() {
                self.keep_format_strings = true;
                self.radios.filter('[value="custom"]').click();
                self.update();
            });
        };

        $.extend($.AWPCP.DateTimeSettings.prototype, {
            update: function() {
                var self = this;

                self.progress = 0;
                self.string = self.format.val();

                $.ajax({
                    url: $.AWPCP.get('ajaxurl'),
                    type: 'POST',
                    data: {
                        'action': 'time_format',
                        'date': self.time.val()
                    },
                    success: function(time) {
                        self.replace('time', time);
                    }
                });

                $.ajax({
                    url: $.AWPCP.get('ajaxurl'),
                    type: 'POST',
                    data: {
                        'action': 'date_format',
                        'date': self.date.val()
                    },
                    success: function(time) {
                        self.replace('date', time);
                    }
                });
            },

            replace: function(search, replacement) {
                var self = this;

                if (search === 'time') {
                    self.string = self.string.replace('<time>', replacement);
                } else if (search === 'date') {
                    self.string = self.string.replace('<date>', replacement);
                }

                self.progress = self.progress + 50;

                if (self.progress >= 100) {
                    self.example.text(self.string);
                }
            }
        });

    })();

    (function() {
        $(function() {
            var table = $('#x-date-time-format-american').closest('table');
            $.noop(new $.AWPCP.DateTimeSettings(table));

            $( '#awpcp-admin-settings .settings-form' ).each( function() {
                SettingsValidator.setup( $(this) );
            } );
        });
    })();

} );
