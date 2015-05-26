(function(){
	var allWidgets = WidgetWrangler.data.allWidgets || [];

	var widgetOptions = [];

	_( allWidgets ).forEach( function( widget ){
		widgetOptions.push( {
			'text': widget.post_title,
			'value': widget.post_name
		} );
	});

	tinymce.PluginManager.add('ww_insert_widget', function(editor, url) {
		editor.addButton('ww_insert_widget', {
			icon: 'widget-wrangler-button-icon',
			text: 'Widget Wrangler',
			onclick: function() {
				editor.windowManager.open({
					title: 'Select a widget to insert',
					position: 'relative',
					classes: 'widget-wrangler-tinymce-button',
					body: [	{
							type: 'listbox',
							name: 'widgetSlug',
							label: 'Widget',
							values: widgetOptions
						},
					],
					onsubmit: function(e) {
						editor.insertContent(
							'[ww_widget slug=&quot;' +
							e.data.widgetSlug +
							'&quot;]');
					}
				});
			}
		});
	});
})();