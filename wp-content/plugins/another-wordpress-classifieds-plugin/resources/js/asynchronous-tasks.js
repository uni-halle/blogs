/*global AWPCP*/

AWPCP.define('awpcp/asynchronous-tasks', ['jquery', 'knockout', 'awpcp/settings'],
function($, ko, settings) {

    function AsynchronousTask(name, action) {
        this.name = ko.observable(name);
        this.action = action;
        this.recordsCount = ko.observable(null);
        this.recordsLeft = ko.observable(null);

        this.progress = ko.computed(function() {
            var recordsCount = this.recordsCount(),
                recordsLeft = this.recordsLeft(),
                progress;

            if (recordsLeft === null || recordsCount === null) {
                progress = 0;
            } else if (recordsLeft === 0) {
                progress = 100;
            } else if (recordsCount > 0) {
                progress = 100 * (recordsCount - recordsLeft) / recordsCount;
            }

            return progress + '%';
        }, this).extend({ throttle: 1 });
    }

    function AsynchronousTasks(tasks, texts) {
        this.working = ko.observable(false);
        this.message = ko.observable(false);
        this.error = ko.observable(false);

        this.texts = {};

        $.each(texts, $.proxy(function(key, text) {
            this.texts[key] = ko.observable(text);
        }, this));

        this.tasks = ko.observableArray([]);

        $.each(tasks, $.proxy(function(index, task) {
            this.tasks.push(new AsynchronousTask(task.name, task.action));
        }, this));

        this.tasksCount = this.tasks().length;
        this.currentTaskIndex = ko.observable(0);
        this.tasksCompleted = ko.observable(0);

        this.tasksLeft = ko.computed(function() {
            return this.tasksCount - this.tasksCompleted();
        }, this);

        this.completed = ko.observable(this.tasksLeft() === 0);

        this.progress = ko.computed(function() {
            var tasks = this.tasks(), task = tasks[this.currentTaskIndex()],
                tasksCompleted = this.tasksCompleted(),
                currentTaskProgress, progress;

            if (task) {
                currentTaskProgress = parseFloat(task.progress()) / 100;
            } else {
                currentTaskProgress = 0;
            }

            if (this.working()) {
                progress = 100 * (tasksCompleted + currentTaskProgress) / this.tasksCount;
            } else {
                progress = 100 * tasksCompleted / this.tasksCount;
            }

            return progress + '%';
        }, this).extend({ throttle: 1 });
    }

    ko.bindingHandlers.progress = {
        init: function(element, accessor) {
            var observable = accessor();
            $(element).animate({width: observable()});
        },
        update: function(element, accessor) {
            var observable = accessor();
            $(element).animate({width: observable()});
        }
    };

    $.extend(AsynchronousTasks.prototype, {
        render: function(element) {
            ko.applyBindings(this, $(element).get(0));
        },

        start: function() {
            this.working(true);
            setTimeout($.proxy(this.runTask, this), 1);
        },

        runTask: function() {
            var tasks = this.tasks();

            if (this.currentTaskIndex() >= this.tasksCount) {
                this.working(false);
                this.completed(true);
            } else {
                $.getJSON(settings.get('ajaxurl'), {
                    action: tasks[this.currentTaskIndex()].action
                }, $.proxy(this.handleAjaxResponse, this));
            }
        },

        handleAjaxResponse: function(response) {
            if (response.status === 'ok') {
                this.handleSuccessfulResponse(response);
            } else {
                this.handleErrorResponse(response);
            }
        },

        handleSuccessfulResponse: function(response) {
            var tasks = this.tasks(),
                task = tasks[this.currentTaskIndex()];

            if (response.message) {
                this.showMessage(response.message);
            }

            task.recordsCount(task.recordsCount() || parseInt(response.recordsCount, 10));
            task.recordsLeft(parseInt(response.recordsLeft, 10));

            if (task.recordsLeft() === 0) {
                this.tasksCompleted(this.tasksCompleted() + 1);
                this.currentTaskIndex(this.currentTaskIndex() + 1);
            }

            setTimeout($.proxy(this.runTask, this), 1);
        },

        showMessage: function(message) {
            this.clearMessagesAndErrors();
            this.message(message);
        },

        clearMessagesAndErrors: function() {
            this.message(false);
            this.error(false);
        },

        handleErrorResponse: function(response) {
            this.showError(response.error);
        },

        showError: function(error) {
            this.clearMessagesAndErrors();
            this.error(error);
        }
    });

    return AsynchronousTasks;
});
