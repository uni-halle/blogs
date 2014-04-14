<div class="awpcp-asynchronous-tasks-container">
    <div data-bind="if: message, css: { updated: message }"><p data-bind="html: message"></p></div>
    <div data-bind="if: error, css: { updated: error, error: error }"><p data-bind="html: error"></p></div>

    <div data-bind="ifnot: completed">

        <p data-bind="html: texts.introduction"></p>

        <div data-bind="if: tasks">
            <h3><?php _ex( 'Pending Tasks', 'asynchrounous tasks screen', 'AWPCP' ); ?></h3>
            <ul class="awpcp-asynchronous-tasks" data-bind="foreach: tasks">
                <li><span data-bind="text: name"></span> (<span data-bind=" text: progress"></span>).</li>
            </ul>
        </div>

        <form class="awpcp-asynchronous-tasks-form" data-bind="submit: start">
            <div class="progress-bar">
                <div class="progress-bar-value" data-bind="progress: progress"></div>
            </div>

            <p class="submit">
                <input id="submit" type="submit" class="button-primary" name="submit" data-bind="value: texts.button, disable: working">
            </p>
        </form>
    </div>

    <div data-bind="if: completed">
        <p class="awpcp-asynchronous-tasks-completed-message" data-bind="html: texts.success"></p>
    </div>
</div>
