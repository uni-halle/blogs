<?php

/**
 * Skype widget class
 *
 * @since 2.8.4
 */
class Skype_Status_Widget extends WP_Widget {

	function Skype_Status_Widget() {
		$this->WP_Widget(
			'skype-status', 
			__('Skype Status', 'skype-online-status'),
			array(
				'classname' => 'skype_widget', 
				'description' => __('Add a Skype Online Status button', 'skype-online-status')
			), 
			array(
				'width' => 370, 
				'id_base' => 'skype-status'
			)
		);
		
		// attempt upgrade from pre 2.8.4 widgets
		if ( $options = get_option('skype_widget_options') ) {
			$options['_multiwidget'] = 1;
			update_option('widget_skype-status', $options);
			delete_option('skype_widget_options');
		}
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		$before = apply_filters('widget_text', $instance['before'], $instance);
		$after = apply_filters('widget_text', $instance['after'], $instance);

		$skype_args = ($instance['skype_id']) ? 'skype_id='.$instance['skype_id'].'&' : '';
		$skype_args .= ($instance['user_name']) ? 'user_name='.$instance['user_name'].'&' : '';
		$skype_args .= ($instance['use_voicemail']) ? 'use_voicemail='.$instance['use_voicemail'].'&' : '';
		$skype_args .= ($instance['button_template']) ? 'button_template='.stripslashes($instance['button_template']):'';

		echo $before_widget;
		if (!empty( $title ))
			echo $before_title . $title . $after_title;
		echo '<div class="skype-status-button">' . $before;
		echo Skype_Online_Status::skype_status($skype_args);
		echo $after . '</div>' . $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['skype_id'] =  trim(strip_tags(stripslashes($new_instance['skype_id']))); 
		$instance['user_name'] =  trim(strip_tags(stripslashes($new_instance['user_name']))); 

		if ( current_user_can('unfiltered_html') ) {
			$instance['before'] =  $new_instance['before'];
			$instance['after'] =  $new_instance['after'];
		} else {
			$instance['before'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['before']) ) ); // wp_filter_post_kses() expects slashed
			$instance['after'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['after']) ) ); // wp_filter_post_kses() expects slashed
		}

		if ( $new_instance['button_theme'] != '' ) // then get template file content to load into db
			$instance['button_template'] = stripslashes(Skype_Online_Status::get_template_file($new_instance['button_theme'])); 
		else 
			$instance['button_template'] = '';
		
		$instance['button_theme'] =  stripslashes($new_instance['button_theme']); 

		$instance['use_voicemail'] =  $new_instance['use_voicemail']; 

		return $instance;
	}

	function form( $instance ) {
		$defaults = array ( 
			'title' => __('Skype Status', 'skype-online-status'),	// Widget title
			'skype_id' => '',			// Skype ID to replace {skypeid} in template files
			'user_name' => '',			// User name to replace {username} in template files
			'button_theme' => '',			// Theme to be used, value must match a filename (without extention) from the /plugins/skype_status/templates/ directory or leave blank
			'button_template' => '',		// Template of the theme loaded
			'use_voicemail' => '',			// Wether to use the voicemail invitation ("on") or not ("off") or leave to default ("")
			'before' => '',				// text that should go before the button
			'after' => '',				// text that should go after the button
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = strip_tags($instance['title']);
		$user_name = strip_tags($instance['user_name']);
		$before = format_to_edit($instance['before']);
		$after = format_to_edit($instance['after']);

		$walk = Skype_Online_Status::walk_templates('', $instance, '', '', FALSE, TRUE); // get list of templates
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('skype_id'); ?>"><?php _e('Skype ID', 'skype-online-status'); ?>*<?php _e(': ', 'skype-online-status'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('skype_id'); ?>" name="<?php echo $this->get_field_name('skype_id'); ?>" type="text" value="<?php echo $instance['skype_id']; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('user_name'); ?>"><?php _e('Full Name', 'skype-online-status'); ?>*<?php _e(': ', 'skype-online-status'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('user_name'); ?>" name="<?php echo $this->get_field_name('user_name'); ?>" type="text" value="<?php echo esc_attr($user_name); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('before'); ?>"><?php _e('Text before button', 'skype-online-status'); ?>**<?php _e(': ', 'skype-online-status'); ?></label>
		<textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id('before'); ?>" name="<?php echo $this->get_field_name('before'); ?>"><?php echo $before; ?></textarea></p>

		<p><label for="<?php echo $this->get_field_id('button_theme'); ?>"><?php _e('Theme', 'skype-online-status'); ?><?php _e(': ', 'skype-online-status'); ?></label>
		<select class="select" id="<?php echo $this->get_field_id('button_theme'); ?>" name="<?php echo $this->get_field_name('button_theme'); ?>"><option value=""<?php if ($instance['button_theme'] == '') echo " selected=\"selected\"" ?>><?php _e('Default', 'skype-online-status') ?>&nbsp;</option><?php foreach ($walk['select'] as $key => $value) { echo "<option value=\"$value\""; if ($value == $instance['button_theme']) { echo " selected=\"selected\""; } echo ">$key&nbsp;</option>"; } unset($value) ?></select></p>

		<p><label for="<?php echo $this->get_field_id('after'); ?>"><?php _e('Text after button', 'skype-online-status'); ?>**<?php _e(': ', 'skype-online-status'); ?></label>
		<textarea class="widefat" rows="2" cols="20" id="<?php echo $this->get_field_id('after'); ?>" name="<?php echo $this->get_field_name('after'); ?>"><?php echo $after; ?></textarea></p>

		<p><label for="<?php echo $this->get_field_id('use_voicemail'); ?>"><?php _e('Use Voicemail?', 'skype-online-status'); ?></label>*** 
		<select class="select" id="<?php echo $this->get_field_id('use_voicemail'); ?>" name="<?php echo $this->get_field_name('use_voicemail'); ?>"><option value=""<?php if ($instance['use_voicemail'] == '') echo " selected=\"selected\"" ?>><?php _e('Default', 'skype-online-status'); ?></option><option value="on"<?php if ($instance['use_voicemail'] == 'on') echo " selected=\"selected\"" ?>><?php _e('Yes'); ?></option><option value="off"<?php if ($instance['use_voicemail'] == 'off') echo " selected=\"selected\"" ?>><?php _e('No'); ?></option></select></p>

		<p style="clear:both;font-size:78%;font-weight:lighter;">* <?php _e('Leave blank to use the default options as you defined on the <a href="options-general.php?page=skype-status.php">Skype Online Status Settings</a> page.', 'skype-online-status'); //printf(__('Leave blank to use the default options as you defined on the %1$s page.', 'skype-online-status'), '<a href="'.admin_url('options-general.php?page='.SOSPLUGINBASENAME).'">'.__('Settings').'</a>'); ?><br />
		** <?php _e('You can use some basic HTML here like &lt;br /&gt; for new line.', 'skype-online-status'); ?><br />
		*** <?php printf(__('Set to %1$s if you do not have %2$s or %3$s.', 'skype-online-status'), __('No'), '<a href="https://support.skype.com/en/category/ONLINE_NUMBER_SKYPEIN/" target="_blank">'.__('SkypeIn','skype-online-status').'</a><img src="//www.ftjcfx.com/image-3049686-10520919" width="1" height="1" border="0"/>', '<a href="https://support.skype.com/en/category/VOICEMAIL/" target="_blank">'.__('Skype Voicemail','skype-online-status').'</a><img src="//www.ftjcfx.com/image-3049686-10423078" width="1" height="1" border="0"/>'); ?></p>
<?php
	}
}

/**
 * Skype class
 *
 * @since 2.8.5
 */

class Skype_Online_Status {

	/**
	* Plugin variables
	*/
	
	public static $pagehook;

	protected static $config;

	protected static $walk;
	protected static $previews;
	protected static $preview_options;
	protected static $current_theme_fullname;

	private static $add_script;

	protected static $whats_new = "<p>
	* SSL compat (as far as possible)<br/>
	* Prevent skype.com redirect or error message from showing
	</p>";

	protected static $avail_languages;
	
	protected static $avail_statusmsg;
	
	protected static $avail_functions;
	
	
	/**
	* Plugin functions
	*/

	public static function init() {
		// Internationalization
		add_action('plugins_loaded', array(__CLASS__, 'textdomain_init'));

		// Initialisation
		add_action('init', array(__CLASS__, 'skype_status_init'));
		add_action('widgets_init', array(__CLASS__, 'register_widget'));
	}
	
	public static function textdomain_init() {
		load_plugin_textdomain('skype-online-status', false, dirname(plugin_basename( __FILE__ )).'/languages');
	}

	public static function register_widget() {
		register_widget('Skype_Status_Widget');
	}
	
	public static function skype_status_init() {
		self::$config = get_option('skype_status', self::get_default_values());

		// do stuff for admin ONLY when on the backend
		if ( is_admin() ) {

			// check for plugin upgrade
			if (self::$config['skype_status_version'] !== SOSVERSION) {
				// merge new default into old settings
				self::$config = array_merge (self::get_default_values(), self::$config);
				// update: populate db with missing values and set upgraded flag to true
				self::$config['skype_status_version'] = SOSVERSION;
				self::$config['upgraded'] = TRUE;
				update_option('skype_status',self::$config);
			}

			// Quicktag button
			if (self::$config['use_buttonsnap']=="on" && current_user_can('edit_posts') && current_user_can('edit_pages')) {
				add_filter('mce_external_plugins', array(__CLASS__, 'mce3_plugin'));
				add_filter('mce_buttons', array(__CLASS__, 'mce3_button'), 99);
			}

			// create WP hooks
			add_filter('plugin_action_links_' . SOSPLUGINBASENAME, array(__CLASS__, 'add_action_link'));
			add_action('admin_menu', array(__CLASS__, 'add_menu'));
			add_filter('screen_layout_columns', array(__CLASS__, 'admin_layout_columns'), 10, 2);

		}
	
		add_shortcode('skype-status', array(__CLASS__, 'shortcode_callback'));

		add_action('wp_head', create_function('', 'echo \'<style type="text/css">#skypedetectionswf{position:fixed;top:0px;left:-10px}#skypeCheckNotice{position:fixed!important}</style>\';'));

		// http://scribu.net/wordpress/optimal-script-loading.html
		add_action('wp_footer',  array(__CLASS__, 'print_script'));
	}

	private static function get_default_values() { 
	
		$default_values = array(
			'skype_id' => 'echo123', 	// Skype ID to replace {skypeid} in template files
			'user_name' => __('Skype Test Call', 'skype-online-status'),
							// User name to replace {username} in template files
			'button_theme' => 'transparent_dropdown', 
							// Theme to be used, value must match a filename (without extention)
							// from the /plugins/skype-online-status/templates/ directory or leave blank
			'button_template' => '', 	// Will hold template loaded from user-selected template file
			'button_function' => 'call',	// Function to replace {function} in template files
			'use_voicemail' => '', 		// Wether to use the voicemail invitation ("on") or not (""),
							// set to "on" if you have a SkypeIn account
			'use_function' => 'on', 	// Wether to replace the tags:
							// {add/call/chat/userinfo/voicemail/sendfile} ("on") or not ("")
			'use_status' => 'custom',	// Wether to replace the tag {status} with your custom texts ("custom") or 
							//Skype default according to language (e.g. "en" for english) or nothing
							// ("" - use this when remote file access is disabled on your server!)
			'use_buttonsnap' => 'on', 	// Wether to display a Skype Status quicktag button in RTE for posts
							// ("on") or not ("")
			'seperator1_text' => __(' - ', 'skype-online-status'),
				 			// Text to replace {sep1} in template files
			'seperator2_text' => __(': ', 'skype-online-status'), 
							// Text to replace {sep2} in template files
			'use_getskype' => 'on', 	// Wether to show the Download Skype now! link
			'getskype_newline' => 'on',	// Put the Download Skype now! link on a new line ("on") or not ("")
			'getskype_text' => __('&raquo; Get Skype, call free!', 'skype-online-status'),
						 	// Text to use for the Download Skype now! link
			'getskype_link' => '',		// What link to use for download: the default ("") will generate some
							// revenue for me (thanks! :-) ), "skype_mainpage" for skype.com main page,
							// "skype_downloadpage" for skype.com download page
			'getskype_custom_link' => '',	// put your own customized link here
			'skype_status_version' => SOSVERSION,
			'upgraded' => FALSE,
			'installed' => TRUE,
			'my_status_text' => __('My status is', 'skype-online-status') . ' ',
					 		// Text to replace {statustxt} in template files
			'status_error_text' => '',
					 		// Text to replace {status} in template files when status could not be checked
		);

		// Available status message languages as provided by Skype,
		// e.g. http://mystatus.skype.com/yourusername.txt.pt-br will
		// show your online status message in Brazilian Portuguese. If
		// there are new languages available, they can be added to this
		// array to make them optional on the Skype Settings page.
		self::$avail_languages = array ( 
			'en' => __('English', 'skype-online-status'),
			'fr' => __('French', 'skype-online-status'),
			'de' => __('German', 'skype-online-status'),
			'ja' => __('Japanese', 'skype-online-status'),
			'zh-tw' => __('Taiwanese', 'skype-online-status'),
			'zh' => __('Chinese', 'skype-online-status'),
			'pt-br' => __('Brazilian', 'skype-online-status'),
			'pt' => __('Portuguese', 'skype-online-status'),
			'it' => __('Italian', 'skype-online-status'),
			'es' => __('Spanish', 'skype-online-status'),
			'pl' => __('Polish', 'skype-online-status'),
			'se' => __('Swedish', 'skype-online-status'),
		);

		// Available status messages as provided by Skype to replace {status} in template files
		self::$avail_statusmsg = array ( 
			'0' => __('Offline', 'skype-online-status'), 		// when status is unknown (0)
			'1' => __('Offline', 'skype-online-status'), 		// when status is offline (1)
			'2' => __('Online', 'skype-online-status'), 		// when status is online (2)
			'3' => __('Away', 'skype-online-status'), 		// when status is away (3)
			'4' => __('Not available', 'skype-online-status'), 	// when status is not available (4)
			'5' => __('Do not disturb', 'skype-online-status'),	// when status is do not disturb (5)
			//"6" => __('Invisible', 'skype-online-status'), 	// when status is invisible (6)
			'7' => __('Skype me!', 'skype-online-status'), 		// when status is skype me! (7)
		);

		self::$avail_functions = array (
			'call' => __('Call me!', 'skype-online-status'),
			'add' => __('Add me to Skype', 'skype-online-status'),
			'chat' => __('Chat with me', 'skype-online-status'),
			'userinfo' => __('View my profile', 'skype-online-status'),
			'voicemail' => __('Leave me voicemail', 'skype-online-status'),
			'sendfile' => __('Send me a file', 'skype-online-status'),
		);
	
		//build status texts
		foreach (self::$avail_statusmsg as $key => $value) {
			$fullkey = "status_".$key."_text";
			$default_values[$fullkey] = $value;
		}
		unset($value);

		//build function texts
		foreach (self::$avail_functions as $key => $value) {
			$fullkey = $key."_text";
			$default_values[$fullkey] = $value;
		}
		unset($value);

		// set language to blogs WPLANG (or leave unchanged)
		if (constant("SOSREMOTE")) {
			if (!defined("WPLANG") || WPLANG=='') {
				$default_values['use_status'] = "en";
			} else {
				$conv = strtolower(str_replace("_","-",WPLANG));
				$first_two = substr(WPLANG,0,2);
				foreach (self::$avail_languages as $key => $value) {
					if ( $conv == $key ) { // get full language/country match
						$default_values['use_status'] = $key;
						break;
					} elseif ( $first_two == $key ) { // or try to get language only match
						$default_values['use_status'] = $key;
						break;
					}
				}
			}
		} else { $default_values['use_status'] = ""; }

		if ($default_values['button_theme']!="custom_edit") // get template file content to load into db
			$default_values['button_template'] = self::get_template_file($default_values['button_theme']);

		return $default_values;
	}

	// CORE
	
	public static function skype_status($r = '', $use_js = TRUE, $status = FALSE) {
		$r = wp_parse_args( $r, self::$config );

		if (!$r['skype_id'])
			return "<!-- " . __('Skype button disabled:', 'skype-online-status') . " " . __('Missing Skype ID.', 'skype-online-status') . "-->";

		// set footer script flag
		self::$add_script = true;

		// if alternate theme is set or no template in db, get it from template file and override
		if ($r['button_theme'] != self::$config['button_theme'] || ($r['button_theme'] && !$r['button_template']) ) 
			$r['button_template'] = self::get_template_file($r['button_theme']);

		return '<!-- Skype button generated by Skype Online Status plugin version '.SOSVERSION.' ( RavanH - http://status301.net/ ) -->
	' . self::parse_theme( $r , $use_js , $status ) . '
	<!-- end Skype button -->'; 

	}

	public static function get_template_file($filename) { // check template file existence and return content
		$buttondir = SOSPLUGINDIR."/templates/";
		if ($filename != "" && file_exists($buttondir.$filename.".html")) 
			return file_get_contents($buttondir.$filename.".html");
		else 
			return '<a href="skype:{skypeid}?call" title="{call}{sep1}{username}{sep2}{status}">{username}{sep2}{status}</a>';
	}

	protected static function parse_theme($config, $use_js = TRUE, $status = FALSE) {

		// get online status to replace {status} tag
		if ($config['use_status']=="") {
			$status = "";
			$config['my_status_text'] = "";
			$config['seperator2_text'] = "";
		} elseif (!$status) {
			if ($config['use_status']=="custom") {
				$check = self::skype_status_check(rawurlencode($config['skype_id']), ".num");
				$status = $config['status_'.$check.'_text'];
			} else {
				$check = self::skype_status_check(rawurlencode($config['skype_id']), ".txt.".$config['use_status']);
				$status = ($check == 'error') ? $config['status_error_text'] : $check;
			}
		}

		// build array with tags and replacement values
		$tags_replace = array(
			"{skypeid}" => $config['skype_id'],
			"{lang}" => ($config['use_status']=="custom") ? '' : '.'.$config['use_status'],
			"{function}" => $config['button_function'],
			"{functiontxt}" => $config[$config['button_function'].'_text'],
			"{status}" => $status,
			"{statustxt}" => $config['my_status_text'],
			"{username}" => $config['user_name'],
			"{sep1}" => $config['seperator1_text'],
			"{sep2}" => $config['seperator2_text'],
			);
		//and append with function texts
		foreach (self::$avail_functions as $key => $value) {
			if ($config['use_function']!="on")
				$config[$key.'_text'] = "";
			$tags_replace["{".$key."}"] = $config[$key.'_text'];
		}

		// delete javascript from template if disabled
		if ($use_js == FALSE) {
			$config['button_template'] = preg_replace("|<script type=\"text\/javascript\" (.*)script>|","",$config['button_template']);
		}

		// delete voicemail lines if not needed else append arrays with tags and replacement values
		if ($config['use_voicemail']!="on") {
			$config['button_template'] = preg_replace("|<!-- voicemail_start (.*) voicemail_end -->|","",$config['button_template']);
		} else {
			$tags_replace["<!-- voicemail_start -->"] = "";
			$tags_replace["<!-- voicemail_end -->"] = "";
		}

		// after that, delete from first line <!-- (.*) -->
		$theme_output = preg_replace("|<!-- (.*) http://www.skype.com/go/skypebuttons -->|","",$config['button_template']);

		// replace all tags with values
		$theme_output = str_replace(array_keys($tags_replace),array_values($tags_replace),$theme_output);

		if ($config['use_getskype'] == "on") { 
			if ($config['getskype_newline'] == "on") 
				$theme_output .= "<br />";

			if ($config['getskype_link'] == "skype_mainpage")
				$theme_output .= " <a rel=\"nofollow\" href=\"http://www.skype.com\" title= \"".$config['getskype_text']."\">".$config['getskype_text']."</a>";
			elseif ($config['getskype_link'] == "skype_downloadpage")
				$theme_output .= " <a rel=\"nofollow\" href=\"http://www.skype.com/go/download\" title= \"".$config['getskype_text']."\">".$config['getskype_text']."</a>";
			elseif ($config['getskype_link'] == "custom_link" && $config['getskype_custom_link'] != "" )
				$theme_output .= stripslashes($config['getskype_custom_link']);
			else
				$theme_output .= " <a rel=\"nofollow\" target=\"_blank\" href=\"http://status301.net/skype-online-status/go/download\" title=\"".$config['getskype_text']."\" onmouseover=\"window.status='http://www.skype.com/go/download';return true;\" onmouseout=\"window.status=' ';return true;\">".$config['getskype_text']."</a>";
			}
		return str_replace(array("\r\n", "\n\r", "\n", "\r", "%0D%0A", "%0A%0D", "%0D", "%0A"), "", $theme_output);
	}

	// online status checker function
	private static function skype_status_check($skypeid=false, $format=".num") {
		if (!$skypeid) return 'error';

		// use http_request_timeout filter if we need to adjust timeout
		// in seconds, default: 5
		add_filter( 'http_request_timeout', create_function('', 'return 3;') );

		$tmp = wp_remote_fopen('http://mystatus.skype.com/'.$skypeid.$format);
		if ( !$tmp || strpos($tmp, 'html') || strpos($tmp, 'Error') || strpos($tmp, 'PNG') ) return 'error';
		else $contents = str_replace("\n", "", $tmp);

		if ($contents!="") 
			return $contents;
		else 
			return 'error';
// TODO fix / adapt to new skype buttons :::
	}

	// routine to render all template files based on one config
	public static function walk_templates( $buttondir, $option_preview, $select, $previews, $use_js = TRUE, $select_only = FALSE ) {
		$option_preview = wp_parse_args( $option_preview, self::$config );

		// default dir
		if (!$buttondir) $buttondir = SOSPLUGINDIR.'/templates/';

		if (is_dir($buttondir)) {
			// do online status check once
			if ($option_preview['use_status']=="") {
				$status = "";
			} else {
				if ($option_preview['use_status']=="custom") {
					$num = self::skype_status_check($option_preview['skype_id'], ".num");
					$status = $option_preview['status_'.$num.'_text'];
				} else {
					$status = self::skype_status_check($option_preview['skype_id'], ".txt.".$option_preview['use_status']);
				}
			}

			// go through the template files in the given directory
			if ($dh = opendir($buttondir)) {
				while (($file = readdir($dh)) !== false) {
					$fname = $buttondir . $file;
					if (is_file($fname) && ".html" == substr($fname,-5)) {

						$template_name = substr(basename($fname),0,-5);

						// attempt to get the human readable name from the first line of the file
						$option_preview['button_template'] = file_get_contents($fname);
						preg_match("|<!-- (.*) - |ms",$option_preview['button_template'],$matches);
						if (!$matches[1])
							$matches[1] = $template_name;

						// collect the options
						$select[$matches[1]] = $template_name;
					
						// and collect their previews if...
						if (!$select_only)
							$previews[$matches[1]] = array( $template_name , 
								self::parse_theme($option_preview,$use_js,$status) ) ; 
					}
				}
				closedir($dh);
			}
		}
		if ( !$select_only && ksort($select) && ksort($previews))
			return array ( "select" => $select , "previews" => $previews );
		elseif ( ksort($select) )
			return array ( "select" => $select );
		else
			return FALSE;
	}

	// skypeCheck script in footer
	// http://scribu.net/wordpress/optimal-script-loading.html
	public static function print_script() {

		if ( ! self::$add_script )
			return;

		if ( self::$config['getskype_link'] == "skype_mainpage" || self::$config['getskype_link'] == "skype_downloadpage" ) {
			wp_register_script('skypecheck', 'http://download.skype.com/share/skypebuttons/js/skypeCheck.js', '', '2.2', true);
		} else {
			wp_register_script('skypecheck', SOSPLUGINURL.'/js/skypeCheck.js', '', SOSVERSION, true);
		}
		wp_print_scripts('skypecheck');
	}

	// CONTENT SHORTCODE

	public static function shortcode_callback($atts, $content = null) {
		$r = shortcode_atts( array(
				'skype_id' => self::$config['skype_id'],
				'user_name' => self::$config['user_name'],
				'button_theme' => self::$config['button_theme'],
				'use_voicemail' => self::$config['use_voicemail'],
				'button_function' => self::$config['button_function'],
				'use_getskype' => self::$config['use_getskype'],
			), $atts );
		return self::skype_status($r);
	}

	// ADMIN HOOKS
	
	public static function add_menu() {
		/* Register our plugin page */
		self::$pagehook = add_submenu_page('options-general.php',__('Skype Online Status', 'skype-online-status'),__('Skype Status', 'skype-online-status'),'manage_options',SOSPLUGINFILE,array(__CLASS__, 'skype_options'));
		/* Using registered $page handle to hook script load */
		add_action('load-' . self::$pagehook, array(__CLASS__, 'scripts_admin'));
	}

	public static function skype_options() {
		
		add_filter( 'get_user_option_closedpostboxes_'.self::$pagehook, array(__CLASS__, 'closed_meta_boxes') );
		
		add_thickbox();
		
		//may be needed to ensure that a special box is always available
		add_meta_box('basicdiv', __('Basic Options', 'skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_basic'), self::$pagehook, 'normal', 'high');
		add_meta_box('submitdiv', __('Sections','skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_submit'), self::$pagehook, 'side', 'high');
		add_meta_box('previewdiv', __('Preview theme template:','skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_preview'), self::$pagehook, 'side', 'core');
		add_meta_box('supportdiv', __('Support','skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_support'), self::$pagehook, 'side', 'core');
		add_meta_box('morediv', __('Get more from Skype','skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_more'), self::$pagehook, 'normal', 'low');
		add_meta_box('resourcesdiv', __('Resources','skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_resources'), self::$pagehook, 'side', 'low');

		//load admin page
		include(SOSPLUGINDIR . '/skype-admin.php');
	}

	public static function closed_meta_boxes( $closed ) {
		// set default closed metaboxes
		if ( false === $closed )
			$closed = array( 'advanceddiv', 'supportdiv', 'donationsdiv', 'resourcesdiv' );

		// remove closed setting of some metaboxes
		$closed = array_diff ( $closed , array ( 'morediv' ) );

		return $closed;
	}

	// Adds an action link to the Plugins page
	public static function add_action_link( $links ) {
		$settings_link = '<a href="' . admin_url('options-general.php?page='.SOSPLUGINFILE) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	public static function scripts_admin($hook) {

		//if ( $hook == 'settings_page_'.SOSPLUGINFILE ) {
		if (self::$config['getskype_link'] == "skype_mainpage" || self::$config['getskype_link'] == "skype_downloadpage") {
			wp_register_script('skypecheck', 'http://download.skype.com/share/skypebuttons/js/skypeCheck.js', '', '2.0', true);
		} else {
			wp_register_script('skypecheck', SOSPLUGINURL.'/js/skypeCheck.js', '', SOSVERSION, true);
		}
	
		// needed javascripts to allow drag/drop, expand/collapse and hide/show of boxes
		wp_enqueue_script('common');
		wp_enqueue_script('wp-list');
		wp_enqueue_script('postbox');
	
		wp_enqueue_script('skypecheck');
		wp_enqueue_script('sos-admin', SOSPLUGINURL . '/js/skype_admin.js', array( 'jquery' ), SOSVERSION, true);

		//add several metaboxes now, all metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
		add_meta_box('advanceddiv', __('Advanced Options', 'skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_advanced'), self::$pagehook, 'normal', 'core');
		add_meta_box('discussiondiv', __('Discussion'), array(__CLASS__.'_Admin', 'meta_box_discussion'), self::$pagehook, 'normal', 'low');
		add_meta_box('donationsdiv', __('Credits','skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_donations'), self::$pagehook, 'side', 'default');

	}

	// Add button for WordPress 2.5+ using built in hooks, thanks to Subscribe2
	public static function mce3_plugin($arr) {
		$arr['sosquicktag'] = SOSPLUGINURL . '/js/mce3_editor_plugin.js';
		return $arr;
	}

	public static function mce3_button($buttons) {
		array_push($buttons, "|", "sosquicktag");
		return $buttons;
	}

	//for WordPress 2.8 we have to tell, that we support 2 columns !
	public static function admin_layout_columns($columns, $screen) {
		if ($screen == self::$pagehook) {
			$columns[self::$pagehook] = 2;
		}
		return $columns;
	}

}
