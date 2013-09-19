<?php
if(!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME']))
	die('You can not access this page directly!');
?>
	<div id="guide" style="min-height:800px;display:none">
		<h2><?php _e('Quick Guide','skype-online-status'); ?></h2>
		<ul>
			<li><a href="#basic">Basic Use</a></li>
			<li><a href="#adv">Advanced</a></li>
			<li><a href="#templ">Skype Button Templates</a></li>
		</ul>

		<p id="basic" style="text-align:right"><a href="#wphead"><?php _e('Top') ?></a></p>
		<h3>Basic Use</h3>
		<p>Define basic Skype settings such as Skype ID (more then one possible, seperate with a semi-colon <strong>;</strong>), User name and preferred Theme on the Skype Online Status Settings page as default for each Skype Online Status Button on your blog. Then use one or more of the methodes described below to trigger the default Skype Status button on your blog pages. Under 'Advanced' you can read about ways to override your default settings and create multiple and different Skype buttons across your blog.</p>
		<p><img src="http://c.skype.com/i/legacy/images/share/buttons/privacy_shot.jpg" alt="" style="float:right" /><strong>Important:</strong> Be sure to enable <strong><em>online status</em></strong> in your personal Skype settings on your PC: open your Skype client, go to Tools > Options > Privacy (or Advanced), tick the 'Allow my status to be shown on the web' (or similar in your language) checkbox and 'Save'.</p>
		<p>To make your Skype button initiate conference calls or multi-chat sessions, put multiple Skype ID's seperated with a semi-colon (;) in the Skype ID box.</p>

		<h4>Widgets</h4>
		<p>Since version 2.6.1.0 there is a Skype Status Sidebar Widget available. Go to your Design > Widgets page and activate the Skype Status widget. When activated, it defaults to your settings on the Skype Status Options page but you can customize it if you like.</p>

		<h4>In posts and page content</h4>
		<p>It is also possible to trigger a Skype Status button (as predefined on the Skype Online Status Settings page) within posts or page content. Use the quicktag button <img src="<?php echo SOSPLUGINURL; ?>/skype_button.gif" alt="Skype Online Status" style="vertical-align:text-bottom;" /> or insert manually the shortcode <strong><code>[skype-status]</code></strong> in your post or page content to display a Skype Online Status button in your post. </p>
		<p>Note: the setting 'Use Skype Status quicktag button' should be checked for the quicktag button <img src="<?php echo SOSPLUGINURL; ?>/skype_button.gif" alt="Skype Online Status" style="vertical-align:text-bottom;" /> to appear in WordPress's Rich Text Editor (TinyMCE) so you can easily drop the quicktag into the source code.</p>
		<h4>In theme files</h4>
		<p>Put <strong><code>&lt;?php if (function_exists(get_skype_status)) { get_skype_status(''); } else { echo "Skype button disabled"; } ?&gt;</code></strong> in your sidebar.php or other WordPress template files to display a Skype Button with Online Status information on your blog pages. Your predefined default settings (above) will be used.</p><p>The 'function_exists'-check is there to prevent an error when the plugin is disabled. In this case the echo text is displayed. You can define another alternative action or remove 'else { ... }' to display nothing at all.</p>

		<p id="adv" style="text-align:right"><a href="#wphead"><?php _e('Top') ?></a></p>
		<h3>Advanced</h3>
		<p>It is also possible to use multiple Skype buttons for different Skype Users and with different themes across your blog pages.</p>
		<h4>Syntax</h4>
		<p><strong>In posts and page content</strong>, use the shortcode syntax <strong><code>[skype-status parameter1="something" parameter2="something_else"]</code></strong></p>
		<p><strong>In theme files</strong>, use the function syntax <strong><code>get_skype_status('parameter1=something&parameter2=something_else');</code></strong></p>
		<h4>Available Parameters</h4>
			<dl><dt><strong>skype_id</strong></dt><dd>Alternative Skype ID.</dd>
				<dt><strong>user_name</strong></dt><dd>Define the full Skype user or screen name.</dd>
				<dt><strong>button_theme</strong></dt><dd>Define the theme template file to use for the button. Value must match a filename (without extention) from the /plugins/skype-online-status/templates/ directory or the predefined theme template will be used.</dd>
				<dt><strong>use_voicemail</strong></dt><dd>Set to 'on' if you want to display the 'Leave a voicemail' link in the Dropdown themes. Use this only if you have a <a href="http://www.tkqlhce.com/click-3049686-10520919" target="_top">SkypeIn number</a> or <a href="http://www.tkqlhce.com/click-3049686-10423078" target="_top">Skype Voicemail</a>
<img src="http://www.ftjcfx.com/image-3049686-10423078" width="1" height="1" style="border:0" alt=""/>. Set of 'off' if you have a predefined setting 'on' and you want to override it.</dd>
				<dt><strong>button_function</strong></dt><dd>Define the button's function. Value must match a valid Skype function like 'call', 'add', 'chat', 'userinfo', 'voicemail' or 'sendfile'.</dd>
				<dt><strong>use_getskype</strong></dt><dd>Set to 'on' if you want to display the <em>Get Skype</em> link. Set to 'off' to remove the link.</dd>
			</dl>
		<h4>Example</h4>
		<p>The php-code <strong><code>get_skype_status('skype_id=echo123&amp;user_name=Skype voice test&amp;button_theme=callme_mini_blue')</code></strong> will generate a Skype button with all your predefined settings <em><strong>except</strong></em> for Skype user 'echo123' (the Skype voice test user) with the screen name 'Skype voice test' and using template file 'callme_mini.html':</p>
		<p><?php get_skype_status('skype_id=echo123&user_name=Skype voice test&button_theme=callme_mini_blue'); ?></p>

		<p id="templ" style="text-align:right"><a href="#wphead"><?php _e('Top') ?></a></p>
		<h4>Skype Button Templates</h4>
		<p>Whenever the options on the Skype Status Options page are saved, the template is read either from the selected template file or the editable textarea (customizable view) and loaded into the database. To change the Skype Online Status button view to your liking you can choose to edit an existing template file, create a new one or edit the preloaded template in the editable textarea on the 'Skype Online Status Settings' page. Remember that after editing a template file, the new content must be reloaded into the database before changes apply.</p>
		<p>All template files can be found in the /plugins/skype-online-status/templates/ directory. You add new or edit existing ones with any simple text editor (like Notepad) or even a WYSIWYG editor (like Dreamweaver) as long as you follow some rules.</p>
		<h4>Template file rules</h4>
		<ol>
			<li>All template files must have a name consisting of only <strong>lowercase letters</strong>, <strong>numbers</strong> and/or <strong>underscores (_)</strong> or <strong>dashes (-)</strong>. Please, avoid any other signs, dots or whitespaces. Do not use the name <strong>custom_edit</strong> as is reserved for the customizable view.</li>
			<li>The template files must reside in the <strong>/templates/</strong> subdirectory of this plugin directory.</li>
			<li>The template files must have the <strong>.html</strong> extention. All other extentions in the templates directory will be ignored.</li>
			<li>The first line of any <strong>file</strong> must be something like: <br />
				<strong>&lt;!-- 'Template Name' style description - http://www.skype.com/go/skypebuttons --&gt;</strong><br />
				where the <em>'Template Name' style description</em> part will represent the template name on the Skype Online Status Settings page. Choose a recognizable name and a very short description.</li>
		</ol>
		<h4>Template rules</h4>
		<ol>
			<li>Within each template (file or customizable view) certain tags like <strong>{skypeid}</strong> are used that will be replaced according to their respective configuration on the Skype Status Settings page. See 'Template tags' below for all available tags.</li>
			<li>Everything within the template between <strong>&lt;!-- voicemail_start --&gt;</strong> and <strong>&lt;!-- voicemail_end --&gt;</strong> will be erased when the option 'Use <strong>Leave a voicemail</strong>' on the Skype Online Status Settings page is NOT checked.</li>
		</ol>
		<p>For the rest you are free to put anything you like in the template files.<br />
		To get started see <a href="http://www.skype.com/go/skypebuttons">http://www.skype.com/go/skypebuttons</a> for an interactive form to create new Skype Button code.</p> 
		<h4>Template tags</h4>
		<p>The following tags are available:</p>
		<strong>General tags</strong>
		<dl>
			<dt>{skypeid}</dt><dd>Put this where the 'Skype ID' should go. Usually href="skype:{skypeid}?call" but it can also be used elsewhere.</dd>
			<dt>{username}</dt><dd>Put this where you want the 'User name' to appear, such as in title="", alt="" or as link text.</dd>
			<dt>{function}</dt><dd>Put this where you want the <em>preselected</em> Function to appear, such as after href="skype:{skypeid}?... in the link URL. The function can be set on the Skype Status Settings page under 'Function' to options like 'Call me', 'Chat with me' or 'Leave a voicemail'.</dd>
			<dt>{functiontxt}</dt><dd>Put this where you want the <em>corresponding</em> Function text to appear, such as in title="", alt="" or as link text.</dd>
			<dt>{status}</dt><dd>Put this where you want the <em>dynamic</em> 'Status' texts to appear, such as in title="", alt="" or as link text. The status text (defined on the Skype Status Settings page under 'Status text') depends on the actual online status of the defined Skype user and ranges from 'Unknown' to 'Online'.</dd>
			<dt>{statustxt}</dt><dd>Put this where you want the <em>static</em> 'My status' text to appear, such as in title="", alt="" or as link text.</dd>
			<dt>{sep1}</dt><dd>Put this where you want the 'First seperator' text to appear, usually between the tags like {call} and {username}.</dd>
			<dt>{sep2}</dt><dd>Put this where you want the 'Second seperator' text to appear, usually between the tags like {username} and {status}.</dd>
		</dl>
		<strong>Action text tags</strong>
		<dl>
			<dt>{add}</dt><dd>Put this where you want the 'Add me to Skype' text to appear, such as in title="", alt="" or as link text.</dd>
			<dt>{call}</dt><dd>Put this where you want the 'Call me!' text to appear, such as in title="", alt="" or as link text.</dd>
			<dt>{chat}</dt><dd>Put this where you want the 'Chat with me' text to appear, such as in title="", alt="" or as link text.</dd>
			<dt>{sendfile}</dt><dd>Put this where you want the 'Send me a file' text to appear, such as in title="", alt="" or as link text.</dd>
			<dt>{userinfo}</dt><dd>Put this where you want the 'View my profile' text to appear, such as in title="", alt="" or as link text.</dd>
			<dt>{voicemail}</dt><dd>Put this where you want the 'Leave me a voicemail' text to appear, such as in title="", alt="" or as link text.</dd>
		</dl>
		<h4>Examples</h4>
		<p>The classic 'Call me!' button template looks like this:</p>
		<blockquote>&lt;!-- 'Call me!' classic style - http://www.skype.com/go/skypebuttons --&gt;<br />&lt;a href="skype:{skypeid}?call" onclick="return skypeCheck();" title="{call}{sep1}{username}{sep2}{status}">&lt;img src="http://download.skype.com/share/skypebuttons/buttons/call_blue_white_124x52.png" style="border: none;" width="124" height="52" alt="{call}{sep1}{username}{sep2}{status}" /&gt;&lt;/a&gt;</blockquote>
		<p>The template for a simple text link displaying username and online status (seperated by the second seperator tag) could look like this:</p>
		<blockquote>&lt;!-- 'My status' plain text link - http://www.skype.com/go/skypebuttons --&gt;<br />&lt;a href="skype:{skypeid}?call" onclick="return skypeCheck();" title="{username}{sep2}{status}">{username}{sep2}{status}&lt;/a&gt;</blockquote>
		<p style="text-align:right"><a href="#wphead"><?php _e('Top') ?></a></p>

	</div> <!-- #guide -->

	<div id="notes" style="min-height:800px;display:none">

		<h2><?php _e('Notes &amp; Live Support','skype-online-status'); ?></h2>
		<ul>
			<li><a href="#prl">Version, Pricing and Licensing</a></li>
			<li><a href="#live"><?php _e('Support','skype-online-status') ?></a></li>
			<li><a href="#credits"><?php _e('Credits','skype-online-status') ?></a></li>
			<li><a href="#revhist">FAQ's, Changelog, Todo and other notes</a></li>
		</ul>

		<p id="prl" style="text-align:right"><a href="#wphead"><?php _e('Top') ?></a></p>
		<h3>Version, Pricing and Licensing</h3>
		<p>This is <strong>version <?php echo SOSVERSION; ?></strong> of the Skype Online Status plugin for WordPress 2+.<br />
			Release date: <?php echo SOSVERSION_DATE; ?>. <br />
			The latest available release is: <strong>version <?php if(isset($r->new_version)) { echo $r->new_version . " <span class=\"updated fade\">PLEASE, <a href=\"" . $r->url . "\">UPDATE</a> BEFORE REPORTING BUGS !</span>"; } else { echo SOSVERSION; } ?></strong></p>

		<p>This plugin is in beta testing stage and is released under the <a href="http://www.gnu.org/licenses/gpl.txt">GNU General Public License</a>. You can use it free of charge but at your own risk on your personal or commercial blog. <br />
			Report bugs, feature requests and user experiences on <a href="http://wordpress.org/tags/skype-online-status/"><?php _e('WordPress Support','skype-online-status') ?> - <?php _e('Skype Online Status','skype-online-status') ?></a>.</p>
		<p>If you enjoy this plugin, you can thank me by way of a small donation for my efforts and the time I spend maintaining and developing this plugin and giving <a href="#live">live user support</a> in dutch, english and even a little french and german :).</p>
		<p>I appreciate every contribution, no matter if it&#8217;s two or twenty euro/dollar or any other amount. Please, use the link in the sidebar.</p>
		<p>Thanks!<br />
			<em>RavanH</em></p>
	
		<p id="live" style="text-align:right"><a href="#wphead"><?php _e('Top') ?></a></p>

		<h3><?php _e('Support','skype-online-status') ?></h3>
		<p>Please read support questions on <a href="http://wordpress.org/tags/skype-online-status/"><?php _e('WordPress Support','skype-online-status') ?> - <?php _e('Skype Online Status','skype-online-status') ?></a> and post new questions there.</p>
		<p>Live support on this plugin via Skype is reserved for contributors. Those that decide to donate or send in their translation will 'magically' get to see a Skype Chat button in the right sidebar.</p>
		<p>I appreciate every contribution, no matter if it&#8217;s two or twenty euro/dollar or any other amount. Please, use the link in the sidebar.</p>
		<p>Thanks!<br />
			<em>RavanH</em></p>

		<p id="credits" style="text-align:right"><a href="#wphead"><?php _e('Top') ?></a></p>
		
		<h3>Credits</h3>
		<p>This plugin was built by <em>RavanH</em>. It was originally based upon the neat little plugin <a href="http://anti.masendav.com/skype-button-for-wordpress/">Skype Button v2.01</a> by <em>Anti Veeranna</em>. My continued development of this plugin is supported by donators, mentioned in the sidebar. Many thanks!</p>

		<p id="revhist" style="text-align:right"><a href="#wphead"><?php _e('Top') ?></a></p>
		<h4>FAQ's, Revision History, Todo and other info</h4>
		<p>See the included <a target="_blank" href="<?php echo SOSPLUGINURL; ?>/readme.txt">README</a> file:</p>
		<iframe src="<?php echo SOSPLUGINURL; ?>/readme.txt" scrolling="auto" allowtransparency="yes" style="margin:0;padding:0;border:0;width:100%;height:600px"></iframe>
		<p style="text-align:right"><a href="#wphead"><?php _e('Top') ?></a></p>
	</div> <!-- #notes -->
	
	<?php
	if (SOSDATADUMP) { 
		echo "<div id=\"dump\"><h3>All Skype Online Status settings</h3>
		<div style=\"width:32%;float:left\"><h4>Old database values</h4><textarea readonly=\"readonly\" style=\"width:100%;height:600px\">";
		foreach ($this->config as $key => $value) {
			echo $key . " => " . stripslashes(htmlspecialchars($value)) . "\r\n";
		}
		unset($value);
		echo "</textarea></div>
		<div style=\"width:32%;margin:0 2%;float:left\"><h4>Updated to</h4><textarea readonly=\"readonly\" style=\"width:100%;height:600px\">";
		if (!empty($_POST['skype_status_update']) || !empty($_POST['skype_status_reset'])) { 
			foreach ($option as $key => $value) {
				echo $key . " => " . stripslashes(htmlspecialchars($value)) . "\r\n";
			}
			unset($value);
		}
		echo "</textarea></div>
		<div style=\"width:32%;float:left\"><h4>Default values</h4><textarea readonly=\"readonly\" style=\"width:100%;height:600px\">";
		foreach ($this->get_default_values() as $key => $value) {
			echo $key . " => " . stripslashes(htmlspecialchars($value)) . "\r\n";
		}
		unset($value);
		echo "</textarea></div><div style=\"clear:both\"></div>
		<div id=\"globals\"><h4>Pluging global values and flags</h4> 
		<p>SOSDATADUMP=".SOSDATADUMP." (obviously ;-) )<br />SOSPLUGINURL=".SOSPLUGINURL."<br />SOSVERSION=".SOSVERSION."<br />SOSVERSION_DATE=".SOSVERSION_DATE."<br />SOSREMOVEFLAG=".constant('SOSREMOVEFLAG')."
</p>
		</div></div>";	
	}
	?>
