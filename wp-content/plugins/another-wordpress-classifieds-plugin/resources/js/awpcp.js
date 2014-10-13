/*global console*/
/*exported log*/
function AWPCPError(message) {
    this.name = 'AWPCPError';
    this.message = message || 'AWPCP Error.';
}

AWPCPError.prototype = new Error();
AWPCPError.prototype.constructor = AWPCPError;

var AWPCP = function() {
    var resources = {};
    var instances = {};

    function define(resource, dependencies, handler) {
        resources[resource] = { dependencies: dependencies, handler: handler };
    }

    function run(resource, dependencies, handler) {
        define(resource, dependencies, handler);
        instantiate(resource);
    }

    function instantiate(resource) {
        try {
            _instantiate(resource);
        } catch (e) {
            if (e instanceof AWPCPError) {
                error('AWPCP: ' + e.message);
            } else {
                throw e;
            }
        }
    }

    function _instantiate(resource) {
        var definition = resources[resource];

        if (instances[resource]) {
            return instances[resource];
        }

        if (definition === undefined) {
            throw new AWPCPError('Dependency [' + resource + '] not found.');
        }

        var dependencies = [];

        for (var i = definition.dependencies.length - 1; i >= 0; i = i - 1) {
            // log('AWPCP: Finding an instance of ' + definition.dependencies[i] + ' required by ' + resource);
            dependencies.unshift(_instantiate(definition.dependencies[i]));
        }

        // log('AWPCP: Creating an instance of ' + resource);
        instances[resource] = definition.handler.apply(null, dependencies);

        return instances[resource];
    }

    function logger(method, parameters) {
        return console && console[method] && console[method].apply && console[method].apply(console, parameters);
    }

    function log() {
        return logger('log', arguments);
    }

    function error() {
        return logger('error', arguments);
    }

    return {
        instantiate: instantiate,
        define: define,
        run: run,
        log: log,
        error: error
    };
};

AWPCP = new AWPCP(window);
