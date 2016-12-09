/*global AWPCP*/

AWPCP.define('awpcp/asynchronous-tasks', [
    'jquery',
    'knockout',
    'moment',
    'awpcp/settings',
    'awpcp/knockout-progress'
],
function($, ko, moment, settings) {

    function AsynchronousTask(data) {
        this.name = ko.observable(data.name);
        this.action = data.action;
        this.recordsCount = ko.observable(data.recordsCount || null);
        this.recordsLeft = ko.observable(data.recordsLeft || null);
        this.startTime = ko.observable(null);
        this.lastUpdated = ko.observable(null);

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

            progress = Math.round( progress * 100 ) / 100;

            return '' + progress + '%';
        }, this).extend({ throttle: 1 });

        this.remainingTime = ko.computed((function() {
            var lastRemainingTimeUpdateTime = new Date(),
                remainingTime = null;

            return function() {
                var startTime = this.startTime(),
                    lastUpdated = this.lastUpdated(),
                    progress = parseFloat( this.progress() ),
                    now = new Date(),
                    progressLeft, timeTaken, remainingSeconds;

                if ( ( now - lastRemainingTimeUpdateTime ) < 2500 ) {
                    return remainingTime;
                }

                if ( startTime === null || lastUpdated === null ) {
                    return null;
                }

                if ( progress === 0 ) {
                    return null;
                }

                progressLeft = 100 - progress;
                timeTaken = lastUpdated - startTime;
                remainingSeconds = progressLeft * timeTaken / progress / 1000;
                lastRemainingTimeUpdateTime = now;

                if ( remainingSeconds > 0 ) {
                    remainingTime = moment(now).add(remainingSeconds, 'seconds').from(now, true);
                } else {
                    remainingTime = null;
                }

                return remainingTime;
            };
        })(), this);
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
            this.tasks.push(new AsynchronousTask(task));
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

    $.extend(AsynchronousTasks.prototype, {
        render: function(element) {
            ko.applyBindings(this, $(element).get(0));
        },

        start: function() {
            this.working(true);
            setTimeout($.proxy(this.runTask, this), 1);
        },

        runTask: function() {
            var tasks = this.tasks(), task;

            if (this.currentTaskIndex() >= this.tasksCount) {
                this.working(false);
                this.completed(true);
            } else {
                task = tasks[this.currentTaskIndex()];

                if ( task.startTime() === null ) {
                    task.startTime(new Date());
                }

                $.getJSON(settings.get('ajaxurl'), {
                    action: task.action
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
            task.lastUpdated( new Date() );

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
