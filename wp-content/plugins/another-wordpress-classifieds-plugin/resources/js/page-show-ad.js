/*global confirm, alert*/
(function($, undefined) {

    var AWPCP = jQuery.AWPCP = jQuery.extend({}, jQuery.AWPCP, AWPCP);

    $.AWPCP.FlagLink = function(link) {
        var self = this;

        self.id = parseInt($(link).attr('data-ad'), 10);

        self.link = link.click(function(event) {
            event.preventDefault();
            var proceed = confirm($.AWPCP.l10n('page-show-ad', 'flag-confirmation-message'));
            if (proceed) {
                self.flag_ad();
            }
        });
    };

    $.extend($.AWPCP.FlagLink.prototype, {
        flag_ad: function() {
            var self = this;

            $.ajax({
                url: $.AWPCP.get('ajaxurl'),
                data: {
                    'action': 'awpcp-flag-ad',
                    'ad': self.id,
                    'nonce': $.AWPCP.get('page-show-ad-flag-ad-nonce')
                },
                success: $.proxy(self.callback, self),
                error: $.proxy(self.callback, self)
            });
        },

        callback: function(data) {
            if (parseInt(data, 10) === 1) {
                alert($.AWPCP.l10n('page-show-ad', 'flag-success-message'));
            } else {
                alert($.AWPCP.l10n('page-show-ad', 'flag-error-message'));
            }
        }
    });

    $(function() {
        $.noop(new $.AWPCP.FlagLink($('#flag_ad_link')));
    });
})(jQuery);
