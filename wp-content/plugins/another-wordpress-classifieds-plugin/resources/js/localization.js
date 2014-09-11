/*global AWPCP*/

AWPCP.define('awpcp/localization', ['jquery'],
function($) {
    return {
        get: function(context, message) {
            return $.AWPCP.l10n(context, message);
        }
    }
});
