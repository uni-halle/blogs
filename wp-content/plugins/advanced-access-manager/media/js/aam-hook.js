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