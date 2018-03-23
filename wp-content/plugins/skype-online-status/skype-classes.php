<?php

/**
 * Skype widget class
 *
 * @since 2.8.4
 */
class Skype_Status_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'skype-status',
			__('Skype Button', 'skype-online-status'),
			array(
				'classname' => 'skype_widget',
				'description' => __('Add a Skype legacy button', 'skype-online-status')
			),
			array(
				'width' => 370,
				'id_base' => 'skype-status'
			)
		);
	}

	public function widget( $args, $instance ) {
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

	public function update( $new_instance, $old_instance ) {
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

	public function form( $instance ) {
		$defaults = array (
			'title' => __('Skype Button', 'skype-online-status'),	// Widget title
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

		$walk = Skype_Online_Status::walk_templates('', $instance, false, true); // get list of templates
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

		<p style="clear:both;font-size:78%;font-weight:lighter;">* <?php _e('Leave blank to use the default options as you defined on the <a href="options-general.php?page=skype-status.php">Skype Settings</a> page.', 'skype-online-status'); //printf(__('Leave blank to use the default options as you defined on the %1$s page.', 'skype-online-status'), '<a href="'.admin_url('options-general.php?page='.SOSPLUGINBASENAME).'">'.__('Settings').'</a>'); ?><br />
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

	protected static $whats_new = '<p>
	* Allow email as Skype ID<br>
	* Schemeless image urls.</p>';

	protected static $avail_languages = array();

	protected static $avail_statusmsg = array();

	protected static $avail_functions = array();


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
		$defaults = self::get_default_values();
		self::$config = get_option('skype_status', $defaults);

		// do stuff for admin ONLY when on the backend
		if ( is_admin() ) {

			// check for plugin upgrade
			if (self::$config['skype_status_version'] !== SOSVERSION) {
				// merge new default into old settings
				self::$config = array_merge ($defaults, self::$config);
				// update: populate db with missing values and set upgraded flag to true
				self::$config['skype_status_version'] = SOSVERSION;
				self::$config['upgraded'] = true;
				if ( !empty(self::$config['button_template']) && stripos(self::$config['button_template'], 'mystatus.skype.com') ) {
					self::$config['button_theme'] = $defaults['button_theme'];
					self::$config['button_template'] = self::get_template_file($defaults['button_theme']);
					self::$config['nostatus'] = true;
				}
				update_option('skype_status',self::$config);

				// attempt upgrade from pre 2.8.4 widgets
				if ( $options = get_option('skype_widget_options') ) {
					$options['_multiwidget'] = 1;
					update_option('widget_skype-status', $options);
					delete_option('skype_widget_options');
				}
			}

			// Warning about status button replaced by default
			if ( !empty(self::$config['nostatus']) && current_user_can( 'manage_options' ) ) {
				add_action('admin_notices', create_function('', 'echo \'<div class="error fade"><p>Microsoft officially <a href="https://support.skype.com/en/faq/FA605/how-do-i-set-up-the-skype-button-to-show-my-status-on-the-web-in-skype-for-windows-desktop" target="_blank">dropped the Skype Online status service</a> per Mai 15th, 2015.</p><p>It looks like you were using one of the "My Status" Skype button themes or a custom theme to show your online status. This type of button will not work anymore and has been replaced by the default button after the plugin upgrade. Please go to <a href="options-general.php?page=skype-status.php">Settings > Skype Buttons</a> and switch to any of the remaining legacy button themes. Save the Skype Button options to get rid of this warning message.</p></div>\';'));
			}

			// Quicktag button
			if (self::$config['use_buttonsnap']=="on" && current_user_can('edit_posts') && current_user_can('edit_pages')) {
				add_filter('mce_external_plugins', array(__CLASS__, 'mce3_plugin'));
				add_filter('mce_buttons', array(__CLASS__, 'mce3_button'), 99);
			}

			// create WP hooks
			add_filter('plugin_action_links_' . SOSPLUGINBASENAME, array(__CLASS__, 'add_action_link'));
			add_action('admin_menu', array(__CLASS__, 'add_menu'));
			add_filter('screen_layout_columns', array(__CLASS__, 'admin_layout_columns'), 10, 2); // creates column option in Screen Options; not needed as the option does nothing?

		}

		add_shortcode('skype-status', array(__CLASS__, 'shortcode_callback'));

		add_action('wp_head', array(__CLASS__, 'print_style'));

		// http://scribu.net/wordpress/optimal-script-loading.html
		wp_register_script('skypecheck', plugins_url('/js/skypeCheck.js', __FILE__), '', SOSVERSION, true);

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
			//'button_function' => 'call',// Function to replace {function} in template files
			'use_voicemail' => '', 		// Wether to use the voicemail invitation ("on") or not (""),
										// set to "on" if you have a SkypeIn account
			'use_function' => 'on', 	// Wether to replace the tags:
										// {add/call/chat/userinfo/voicemail/sendfile} ("on") or not ("")
										//Skype default according to language (e.g. "en" for english) or nothing
										// ("" - use this when remote file access is disabled on your server!)
			'use_buttonsnap' => 'on', 	// Wether to display a Skype Status quicktag button in RTE for posts
										// ("on") or not ("")
			'local_images' => '',		// use the local plugin-included images instead of remote Skype hosted ones
			'seperator1_text' => __(' - ', 'skype-online-status'),
										// Text to replace {sep1} in template files
			'use_getskype' => 'on', 	// Wether to show the Download Skype now! link
			'getskype_newline' => 'on',	// Put the Download Skype now! link on a new line ("on") or not ("")
			'getskype_text' => __('&raquo; Get Skype, call free!', 'skype-online-status'),
										// Text to use for the Download Skype now! link
			'getskype_link' => '',		// What link to use for download: the default ("") will generate some
										// revenue for me (thanks! :-) ), "skype_mainpage" for skype.com main page,
										// "skype_downloadpage" for skype.com download page
			'getskype_custom_link' => '',	// put your own customized link here
			'skype_status_version' => SOSVERSION,
			'installed' => true,
		);

		self::$avail_functions = array (
			'call' => __('Call me!', 'skype-online-status'),
			'add' => __('Add me to Skype', 'skype-online-status'),
			'chat' => __('Chat with me', 'skype-online-status'),
			'userinfo' => __('View my profile', 'skype-online-status'),
			'voicemail' => __('Leave me voicemail', 'skype-online-status'),
			'sendfile' => __('Send me a file', 'skype-online-status'),
		);

		//build function texts
		foreach (self::$avail_functions as $key => $value) {
			$fullkey = $key."_text";
			$default_values[$fullkey] = $value;
		}
		unset($value);

		return $default_values;
	}

	// CORE

	public static function skype_status($r = '', $use_js = true) {
		$r = wp_parse_args( $r, self::$config );

		if (!$r['skype_id'])
			return "<!-- " . __('Skype button disabled:', 'skype-online-status') . " " . __('Missing Skype ID.', 'skype-online-status') . "-->";

		// set footer script flag
		self::$add_script = true;

		// if alternate theme is set or no template in db, get it from template file and override
		if ($r['button_theme'] != self::$config['button_theme'] || ($r['button_theme'] && empty($r['button_template'])) )
			$r['button_template'] = self::get_template_file($r['button_theme']);

		return '<!-- Skype button generated by Skype Legacy Buttons plugin version '.SOSVERSION.' ( RavanH - http://status301.net/ ) -->
	' . self::parse_theme( $r , $use_js ) . '
	<!-- end Skype button -->';

	}

	public static function get_template_file($filename) { // check template file existence and return content
		$buttondir = SOSPLUGINDIR."/templates/";
		if ($filename != "" && file_exists($buttondir.$filename.".html"))
			return file_get_contents($buttondir.$filename.".html");
		else
			return '<a href="skype:{skypeid}?call" title="{call}{sep1}{username}">{call}{sep1}{username}</a>';
	}

	protected static function parse_theme( $config, $use_js = true ) {

		// build array with tags and replacement values
		$replace = array(
			"{skypeid}" => $config['skype_id'],
			"{lang}" => '',//($config['use_status']=="custom") ? '' : '.'.$config['use_status'],
			"{function}" => '', //$config['button_function'],
			"{functiontxt}" => '', //$config[$config['button_function'].'_text'],
			"{status}" => '', //$status,
			"{statustxt}" => '', //$config['my_status_text'],
			"{username}" => $config['user_name'],
			"{sep1}" => $config['seperator1_text'],
			"{sep2}" => '', //$config['seperator2_text'],
			);
		//and append with function texts
		foreach ( self::$avail_functions as $key => $value ) {
			if ( $config['use_function'] != "on" )
				$config[$key.'_text'] = "";
			$replace["{".$key."}"] = $config[$key.'_text'];
		}

		// delete javascript from template if disabled
		if ( $use_js !== true ) {
			$config['button_template'] = preg_replace('/<script .*script>/', '', $config['button_template']);
		}

		// delete voicemail lines if not needed else append arrays with tags and replacement values
		if ($config['use_voicemail']!="on") {
			$config['button_template'] = preg_replace('/<!-- voicemail_start .* voicemail_end -->/', '', $config['button_template']);
		} else {
			$replace['<!-- voicemail_start -->'] = '';
			$replace['<!-- voicemail_end -->'] = '';
		}

		// after that, delete from first line <!-- (.*) -->
		$theme_output = preg_replace('/<!-- .* http:\/\/www.skype.com\/go\/skypebuttons -->/', '', $config['button_template']);

		// replace http://download.skype.com/share/skypebuttons/ URI with local URI
		// auto-activates when on ssl for
		if ( $config['local_images'] == "on" ) {
			$replace['http:'] = ''; // backward compat: remove protocol
			$replace['//download.skype.com/share/skypebuttons/'] = trailingslashit( plugins_url( '/', __FILE__ ) );
		} elseif ( is_ssl() ) {
			$replace['http:'] = ''; // backward compat: remove protocol
			$replace['javascript" src="//download.skype.com/share/skypebuttons/'] = 'javascript" src="' . trailingslashit( plugins_url( '/', __FILE__ ) );
		}

		// replace all tags with values
		$theme_output = str_replace(array_keys($replace),array_values($replace),$theme_output);

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
		return $theme_output;
	}

	// routine to render all template files based on one config
	public static function walk_templates( $buttondir, $options, $use_js = true, $select_only = false ) {
		$options = wp_parse_args( $options, self::$config );
		$select = array();

		// default dir
		if (!$buttondir) $buttondir = SOSPLUGINDIR.'/templates/';

		if (is_dir($buttondir)) {
			// go through the template files in the given directory
			if ($dh = opendir($buttondir)) {
				while (($file = readdir($dh)) !== false) {
					$fname = $buttondir . $file;
					if (is_file($fname) && ".html" == substr($fname,-5)) {

						$template_name = substr(basename($fname),0,-5);

						// attempt to get the human readable name from the first line of the file
						$options['button_template'] = file_get_contents($fname);
						preg_match('/<!-- (.*) - /ms',$options['button_template'],$matches);
						if (!$matches[1])
							$matches[1] = $template_name;

						// collect the options
						$select[$matches[1]] = $template_name;

						// and collect their previews if...
						if (!$select_only)
							$previews[$matches[1]] = array( $template_name ,
								self::parse_theme( $options, $use_js ) ) ;
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
			return false;
	}

	// echo styles in header
	public static function print_style() {

		echo '<style type="text/css">#skypeDropdown-transparent,#skypeDropdown-white{z-index:1}#skypedetectionswf{position:fixed;top:0px;left:-10px}#skypeCheckNotice{position:fixed !important}</style>';

	}

	// skypeCheck script in footer
	// http://scribu.net/wordpress/optimal-script-loading.html
	public static function print_script() {

		if ( ! self::$add_script )
			return;

		wp_print_scripts('skypecheck');
	}

	// CONTENT SHORTCODE

	public static function shortcode_callback($atts, $content = null) {
		$r = shortcode_atts( array(
				'skype_id' => self::$config['skype_id'],
				'user_name' => self::$config['user_name'],
				'button_theme' => self::$config['button_theme'],
				'use_voicemail' => self::$config['use_voicemail'],
//				'button_function' => self::$config['button_function'],
				'use_getskype' => self::$config['use_getskype'],
			), $atts );
		return self::skype_status($r);
	}

	// ADMIN HOOKS

	public static function add_menu() {
		/* Register our plugin page */
		self::$pagehook = add_submenu_page('options-general.php',__('Skype Legacy Buttons', 'skype-online-status'),__('Skype Buttons', 'skype-online-status'),'manage_options',SOSPLUGINFILE,array(__CLASS__, 'skype_options'));
		/* Using registered $page handle to hook script load */
		add_action('load-' . self::$pagehook, array(__CLASS__, 'scripts_admin'));
	}

	public static function skype_options() {

		add_filter( 'get_user_option_closedpostboxes_'.self::$pagehook, array(__CLASS__, 'closed_meta_boxes') );

		add_thickbox();

		//may be needed to ensure that a special box is always available
		add_meta_box('submitdiv', __('Sections','skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_submit'), self::$pagehook, 'side', 'high');
		add_meta_box('previewdiv', __('Preview','skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_preview'), self::$pagehook, 'side', 'core');
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

		// needed javascripts to allow drag/drop, expand/collapse and hide/show of boxes
		wp_enqueue_script('common');
		wp_enqueue_script('wp-list');
		wp_enqueue_script('postbox');

		//wp_enqueue_script('skypecheck'); // let's forget about the skypecheck script on admin
		wp_enqueue_script('sos-admin', plugins_url('/js/skype_admin.js',__FILE__), array( 'jquery' ), SOSVERSION, true);

		//add several metaboxes now, all metaboxes registered during load page can be switched off/on at "Screen Options" automatically, nothing special to do therefore
		add_meta_box('advanceddiv', __('Advanced Options', 'skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_advanced'), self::$pagehook, 'normal', 'core');
		add_meta_box('discussiondiv', __('Discussion'), array(__CLASS__.'_Admin', 'meta_box_discussion'), self::$pagehook, 'normal', 'low');
		add_meta_box('donationsdiv', __('Credits','skype-online-status'), array(__CLASS__.'_Admin', 'meta_box_donations'), self::$pagehook, 'side', 'default');

	}

	// Add button for WordPress 2.5+ using built in hooks, thanks to Subscribe2
	public static function mce3_plugin($arr) {
		$arr['sosquicktag'] = plugins_url('/js/mce3_editor_plugin.js',__FILE__);
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
