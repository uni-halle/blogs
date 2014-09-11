/*global AWPCP*/

AWPCP.define('awpcp/collapsible', ['jquery'], function($) {
    function Collapsible(element) {
        this.element = element;
        this.handler = this.element.find('.js-handler').eq(0);
        this.subject = this.element.find('[data-collapsible]').eq(0);
    }

    $.extend(Collapsible.prototype, {
        setup: function() {
            var self = this;

            if (self.subject.length !== 0) {
                if (!self.subject.is('[awpcp-keep-open]')) {
                    self.subject.hide();
                }

                self.toggleClass();

                self.handler.click(function(event) {
                    self.toggle.apply(self, [event, this]);
                });
            } else {
                self.handler.hide();
            }
        },

        toggleClass: function() {
            if (this.subject.is(':visible')) {
                this.handler.find('span').removeClass('open').addClass('close');
            } else {
                this.handler.find('span').removeClass('close').addClass('open');
            }
        },

        toggle: function(event) {
            event.preventDefault();
            var self = this;
            self.subject.slideToggle(function() { self.toggleClass(); });
        }
    });

    return Collapsible;
});
