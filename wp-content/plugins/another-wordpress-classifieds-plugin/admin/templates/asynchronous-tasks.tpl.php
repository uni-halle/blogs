<div class="awpcp-asynchronous-tasks-container">
    <div data-bind="if: message, css: { 'awpcp-updated': message, updated: message }"><p data-bind="html: message"></p></div>
    <div data-bind="if: error, css: { 'awpcp-updated': error, updated: error, error: error }"><p data-bind="html: error"></p></div>

    <div data-bind="ifnot: completed">

        <p data-bind="html: texts.introduction"></p>

        <div data-bind="if: tasks">
            <h3 data-bind="text: texts.title"></h3>
            <ul class="awpcp-asynchronous-tasks" data-bind="foreach: tasks">
                <li>
                    <span data-bind="text: name"></span> (<span data-bind="text: progress"></span> <span data-bind="text: $root.texts.percentageOfCompletion"></span><span data-bind="if: remainingTime"> &mdash; <span data-bind="text: remainingTime"></span> <span data-bind="text: $root.texts.remainingTime"></span></span>).
                </li>
            </ul>
        </div>

        <form class="awpcp-asynchronous-tasks-form" data-bind="submit: start">
            <div class="progress-bar">
                <div class="progress-bar-value" data-bind="progress: progress"></div>
            </div>

            <p class="submit">
                <input id="submit" type="submit" class="button-primary" name="submit" disabled="disabled" data-bind="value: texts.button, disable: working">
            </p>
        </form>
    </div>

    <div data-bind="if: completed">
        <div class="awpcp-asynchronous-tasks-completed-message" data-bind="if: texts.success">
            <p data-bind="html: texts.success"></p>
        </div>
        <div class="awpcp-asynchronous-tasks-completed-message-container" data-bind="if: texts.successHtml">
            <div data-bind="html: texts.successHtml"></div>
        </div>
    </div>
</div>
