/*
 * Create a custom button for the TinyMCE WYSIWYG editor that allows users to insert a shortcode for person listings.
 * The shortcode is replaced by placeholder images in the visual editor.
 *
 * Adapted from http://generatewp.com/take-shortcodes-ultimate-level/
 */

( function() {
	tinymce.PluginManager.add( 'personlist_button', function( editor, url ) {

		// Shortcode tag, used to find and replace/restore placeholders.
		var sh_tag = 'personlist';

		//helper functions
		function getAttr(s, n) {
			n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
			return n ?  window.decodeURIComponent(n[1]) : '';
		};

		function html( cls, data ,con) {
			var placeholder = url + '/img/personlist-placeholder-' + getAttr(data, 'person_group') + '.png';
			data = window.encodeURIComponent( data );
			content = window.encodeURIComponent( con );

			return '<img src="' + placeholder + '" class="mceItem ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
		}

		function replaceShortcodes( content ) {
			// match [personlist including attributes]
			return content.replace( /\[personlist(.*)]/g, function( all,attr ) {
				return html( 'personlist', attr );
			});
		}

		function restoreShortcodes( content ) {
			//match any image tag with our class and replace it with the shortcode's content and attributes
			return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
				var data = getAttr( image, 'data-sh-attr' );

				if ( data ) {
					return '<p>[' + sh_tag + data + ']</p>';
				}
				return match;
			});
		}
		editor.addCommand('personlist_popup', function(ui, selection) {

			// pre-select an existing choice if there is one, default to team.
			var group = 'team';
			if (selection) {
				group = selection
			}

			editor.windowManager.open( {
				title: 'Person List',
				body: [{
					type: 'listbox',
					value: group,
					'values': JSON.parse(personlist_button.terms),
					name: 'term',
					label: 'Select a group:'
				}],
				onsubmit: function( e ) {
					// Insert shortcode content when the window form is submitted
					editor.insertContent( '[personlist person_group="' + e.data.term + '"]');
				}
			});
		});
		// Add a button that opens a window
		editor.addButton( 'personlist_button', {
			text: 'Person List',
			onclick: function() {
				editor.execCommand( 'personlist_popup' );
			}
		} );

		// Replace shortcode with a placeholder image
		editor.on('BeforeSetcontent', function(event){
			event.content = replaceShortcodes( event.content );
		});

		// Replace placeholder image with shortcode
		editor.on('GetContent', function(event){
			event.content = restoreShortcodes(event.content);
		});

		// Open the popup when double clicking on the placeholder
		editor.on('DblClick',function(e) {
			var cls  = e.target.className.indexOf('personlist');
			if ( e.target.nodeName == 'IMG' && e.target.className.indexOf('personlist') > -1 ) {
				var selection = e.target.attributes['data-sh-attr'].value;
				selection = getAttr(window.decodeURIComponent(selection), 'person_group');
				editor.execCommand('personlist_popup', '', selection );
			}
		});
	} );

} )();
