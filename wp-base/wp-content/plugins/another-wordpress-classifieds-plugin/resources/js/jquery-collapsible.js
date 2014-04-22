/*global AWPCP*/

AWPCP.define('awpcp/jquery-collapsible', ['jquery', 'awpcp/collapsible'],
function($, Collapsible) {
    $.fn.collapsible = function() {
        return $(this).each(function() {
            var collapsible = new Collapsible($(this));
            collapsible.setup();
        });
    };
});
