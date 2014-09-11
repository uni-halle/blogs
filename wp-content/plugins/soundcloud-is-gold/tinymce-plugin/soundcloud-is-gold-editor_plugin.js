
(function() {
	tinymce.create('tinymce.plugins.soundcloudIsGold', {
	
		init : function(ed, url) {
			var t = this;
			
			t.url = url;
			t._createButtons();

			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
			ed.addCommand('soundcloud_Is_Gold', function() {
				var el = ed.selection.getNode(), post_id, vp = tinymce.DOM.getViewPort(),
					H = vp.h - 80, W = ( 640 < vp.w ) ? 640 : vp.w;

				if ( el.nodeName != 'IMG' ) return;
				if ( ed.dom.getAttrib(el, 'class').indexOf('soundcloudIsGold') == -1 )	return;

				post_id = tinymce.DOM.get('post_ID').value;
				tb_show('', tinymce.documentBaseURL + '/media-upload.php?post_id='+post_id+'&tab=soundcloud_is_gold&TB_iframe=true&width='+W+'&height='+H);
				
				tinymce.DOM.setStyle( ['TB_overlay','TB_window','TB_load'], 'z-index', '999999' );
			});
			
			
			ed.onMouseDown.add(function(ed, e) {
				if ( e.target.nodeName == 'IMG' && ed.dom.hasClass(e.target, 'soundcloudIsGold') )
					t._showButtons(t, e.target, 'soundcloudisgoldbtns');
			});
						
			//Replace Shortcode with Styled img or whatever
			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = t._do_soundcloudIsGold(o.content);
			});
			//Put Back the shortcode when saving
			ed.onPostProcess.add(function(ed, o) {
				if (o.get)
					o.content = t._get_soundcloudIsGold(o.content);
			});
		},
		
		_showButtons : function(that, n, id) {
			var ed = tinyMCE.activeEditor, p1, p2, vp, DOM = tinymce.DOM, X, Y;

			vp = ed.dom.getViewPort(ed.getWin());
			p1 = DOM.getPos(ed.getContentAreaContainer());
			p2 = ed.dom.getPos(n);

			X = Math.max(p2.x - vp.x, 0) + p1.x;
			Y = Math.max(p2.y - vp.y, 0) + p1.y;

			DOM.setStyles(id, {
				'top' : Y+5+'px',
				'left' : X+5+'px',
				'display' : 'block',
				'position' : 'absolute'
			});

			if ( this.mceTout )
				clearTimeout(this.mceTout);

			this.mceTout = setTimeout( function(){that._hideButtons();}, 5000 );
		},
		
		_hideButtons : function() {
			if ( !this.mceTout )
				return;

			if ( document.getElementById('soundcloudisgold_edit_shortcode') )
				tinymce.DOM.hide('soundcloudisgold_edit_shortcode');

			if ( document.getElementById('soundcloudisgoldbtns') )
				tinymce.DOM.hide('soundcloudisgoldbtns');

			clearTimeout(this.mceTout);
			this.mceTout = 0;
		},
		
		//Replace Shortcode with Styled img or whatever
		_do_soundcloudIsGold : function(co) {
			return co.replace(/\[soundcloud([^\]]*)\]/g, function(a,b){
				return '<img src="../wp-content/plugins/soundcloud-is-gold/tinymce-plugin/img/t.gif" class="soundcloudIsGold mceItem" title="soundcloud'+tinymce.DOM.encode(b)+'" />';
			});
		},
		
		//Put Back the shortcode when saving
		_get_soundcloudIsGold : function(co) {

			function getAttr(s, n) {
				n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
				return n ? tinymce.DOM.decode(n[1]) : '';
			};

			return co.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function(a,im) {
				var cls = getAttr(im, 'class');

				if ( cls.indexOf('soundcloud') != -1 )
					return '<p>['+tinymce.trim(getAttr(im, 'title'))+']</p>';

				return a;
			});
		},

		
		_createButtons : function() {
			/*var t = this, ed = tinyMCE.activeEditor, DOM = tinymce.DOM, soundcloudIGold_editButton, soundcloudIGold_dellButton;

			DOM.remove('soundcloudisgoldbtns');

			DOM.add(document.body, 'div', {
				id : 'soundcloudisgoldbtns',
				style : 'display:none;'
			});
			
			//Create Edit Button: Keep wp_editgallery as id to herite style for gallery edit button
			soundcloudIGold_editButton = DOM.add('soundcloudisgoldbtns', 'img', {
				src : '../wp-content/plugins/soundcloud-is-gold/tinymce-plugin/img/edit.png',
				id : 'wp_editgallery',
				width : '24',
				height : '24',
				title : 'Replace or edit Player'
			});

			tinymce.dom.Event.add(soundcloudIGold_editButton, 'mousedown', function(e) {
				var ed = tinyMCE.activeEditor;
				ed.windowManager.bookmark = ed.selection.getBookmark('simple');
				ed.execCommand("soundcloud_Is_Gold");
			});
			
			//Create Delete Button: Keep wp_editgallery as id to herite style for gallery edit button
			/*soundcloudIGold_dellButton = DOM.add('soundcloudisgoldbtns', 'img', {
				src : '../wp-content/plugins/soundcloud-is-gold/tinymce-plugin/img/delete.png',
				id : 'wp_delgallery',
				width : '24',
				height : '24',
				title : 'Remove Player'
			});

			tinymce.dom.Event.add(soundcloudIGold_dellButton, 'mousedown', function(e) {
				var ed = tinyMCE.activeEditor, el = ed.selection.getNode();

				if ( el.nodeName == 'IMG' && ed.dom.hasClass(el, 'soundcloudIsGold') ) {
					ed.dom.remove(el);
					t._hideButtons();
					ed.execCommand('mceRepaint');
					return false;
				}
			});*/
		},

		getInfo : function() {
			return {
				longname : 'Soundcloud is Gold Shortcode Settings',
				author : 'TM',
				authorurl : 'http://www.mightymess.com',
				infourl : '',
				version : "1.0"
			};
		}
	});

	tinymce.PluginManager.add('soundcloudIsGold', tinymce.plugins.soundcloudIsGold);
})();
