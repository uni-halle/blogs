/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

AAM_PageError = false;

(function () {
    var callback = null;

    if (typeof window.onerror === 'function') {
        callback = window.onerror;
    }

    window.onerror = function (msg, url, line, col, error) {
        AAM_PageError = true;
        if (callback) {
            callback.call(null, msg, url, line, col, error);
        }
    };
})();
