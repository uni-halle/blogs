// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('rebelicPrezi');
	 
	tinymce.create('tinymce.plugins.rebelicPrezi', {
		
		init : function(ed, url) {
		// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('rebelicPrezi', function() {
				ed.windowManager.open({
					file : url + '/dialog.php',
					width : 360,
					height : 150,
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('rebelicPreziButton', {
				title : 'Add Prezi',
				cmd : 'rebelicPrezi',
				image : url + '/img/prezi.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('rebelicPrezi', n.nodeName == 'IMG');
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
					longname  : 'rebelicPrezi',
					author 	  : 'Nick Remaslinnikov',
					authorurl : 'http://www.homolibere.info',
					infourl   : 'http://www.homolibere.info',
					version   : "0.1 beta"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('rebelicPrezi', tinymce.plugins.rebelicPrezi);
})();


