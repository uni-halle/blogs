/*
 * $Id: mce3_editor_plugin.js 201 2008-02-12 15:56:56Z RavanH $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('sosquicktag');

	tinymce.create('tinymce.plugins.SOSQuicktagPlugin', {
		init : function(ed, url) {

			// Register buttons
			ed.addButton('sosquicktag', {
				title : 'sosquicktag.title', 
				image : url + '/../skype_button.gif', 
				onclick : function() {
					var m = '';
					var skypeId = prompt("Step 1/6: Your Skype ID\r\n\r\nEnter an alternative Skype ID or leave empty to use the default.\r\n ", "");
            				if (skypeId == null)
            					return;
            				if (skypeId != '')
            					var m = ' skype_id="'+skypeId+'"';
            				
					var userName = prompt("Step 2/6: Your Name\r\n\r\nEnter an alternative name or leave empty to use the default.\r\n ", "");
            				if (userName == null)
            					return;
            				if (userName != '')
            					var m = m+' user_name="'+userName+'"';
            				
					var buttonTheme = prompt("Step 3/6: Skype Button Theme\r\n\r\nTo use an alternative template, enter any template file name (without the .html extention) from /wp-content/plugins/skype-online-status/templates/", "");
            				if (buttonTheme == null)
            					return;
            				if (buttonTheme != '')
            					var m = m+' button_theme="'+buttonTheme+'"';
            
					var buttonFunction = prompt("Step 4/6: Skype Button Function\r\n\r\nEnter an alternative function like 'call', 'add', 'chat', 'userinfo', 'voicemail' or 'sendfile' (without the quotes) or leave empty to use the default.", "");
            				if (buttonFunction == null)
            					return;
            				if (buttonFunction != '')
            					var m = m+' button_function="'+buttonFunction+'"';
            
					var useVoicemail = prompt("Step 5/6: Use 'Leave a voicemail' link\r\n\r\nEnter 'on' or 'off' (without the quotes) or leave empty to use the default. NOTE: The link is only available in multi-function themes.", "");
            				if (useVoicemail == null)
            					return;
            				if (useVoicemail != '')
            					var m = m+' use_voicemail="'+useVoicemail+'"';
            
					var useGetSkype = prompt("Step 6/6: Use 'Get Skype' Link\r\n\r\nForce enable or disable display of the Get Skype link below or after the button. Enter 'on' or 'off' (without the quotes) or leave blank to use default.", "");
            				if (useGetSkype == null)
            					return;
            				if (useGetSkype != '')
            					var m = m+' use_getskype="'+useGetSkype+'"';
            
                			ed.execCommand('mceInsertContent', false, '[skype-status'+m+']');
				}
			});

		},
		
		createControl : function(n, cm) {
            		return null;
        	},

		getInfo : function() {
			return {
				longname : "Skype Online Status Quicktag",
				author : 'RavanH',
				authorurl : 'http://status301.net/',
				infourl : 'http://status301.net/wordpress-plugins/skype-online-status/',
				version : '2.8.6'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('sosquicktag', tinymce.plugins.SOSQuicktagPlugin);
})();
