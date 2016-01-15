AWPCP.run('awpcp/init-asynchronous-tasks', ['jquery', 'awpcp/settings', 'awpcp/asynchronous-tasks'],
function($, settings, AsynchronousTasks) {
    $(function(){
        var element = $('.awpcp-asynchronous-tasks-container');
        if (element.length) {
            var tasks = settings.get('asynchronous-tasks'),
                texts = settings.get('asynchronous-tasks-texts'),
                widget = new AsynchronousTasks(tasks, texts);

            widget.render(element);
        }
    });
});
