<?php
/**
 * Contextual help implementation.
 */

class Wdgpo_ContextualHelp {
	
	private $_help;
	
	private $_pages = array(
		'list', 'edit', 'get_started', 'settings',
	);
	
	private $_sidebar = '';
	
	private function __construct () {
		if (!class_exists('WpmuDev_ContextualHelp')) require_once WDGPO_PLUGIN_BASE_DIR . '/lib/external/class_wd_contextual_help.php';
		$this->_help = new WpmuDev_ContextualHelp();
		$this->_set_up_sidebar();
	}
	
	public static function serve () {
		$me = new Wdgpo_ContextualHelp;
		$me->_initialize();
	}
	
	private function _set_up_sidebar () {
		$this->_sidebar = '<h4>' . __('Google+', 'wdgpo') . '</h4>';
		if (defined('WPMUDEV_REMOVE_BRANDING') && constant('WPMUDEV_REMOVE_BRANDING')) {
			$this->_sidebar .= '<p>' . __('The Google+ Plugin is your one-stop solution for total WordPress - Google Plus integration. This is the only plugin you\'ll ever need to take full advantage of Google\'s social media tools.', 'wdgpo') . '</p>';
		} else {
				$this->_sidebar .= '<ul>' .
					'<li><a href="http://premium.wpmudev.org/project/google-1" target="_blank">' . __('Project page', 'wdgpo') . '</a></li>' .
					'<li><a href="http://premium.wpmudev.org/project/google-1/installation/" target="_blank">' . __('Installation and instructions page', 'wdgpo') . '</a></li>' .
					'<li><a href="http://premium.wpmudev.org/forums/tags/the-google-plugin" target="_blank">' . __('Support forum', 'wdgpo') . '</a></li>' .
				'</ul>' . 
			'';
		}
	}
	
	private function _initialize () {
		foreach ($this->_pages as $page) {
			$method = "_add_{$page}_page_help";
			if (method_exists($this, $method)) $this->$method();
		}
		$this->_help->initialize();
	}
	
	private function _add_settings_page_help () {
		$auth = Wdgpo_GoogleAuth::get_instance();
		$this->_help->add_page(
			'settings_page_wdgpo',
			array(
				array(
					'id' => 'wdgpo-intro',
					'title' => __('Intro', 'wdgpo'),
					'content' => '<p>' . __('This is where you configure <b>Google+</b> plugin for your site', 'wdgpo') . '</p>',
				),
				array(
					'id' => 'wdgpo-general',
					'title' => __('General Info', 'wdgpo'),
					'content' => '' .
						'<p>' . __('The Google+ Plugin is your one-stop solution for total WordPress - Google Plus integration. This is the only plugin you\'ll ever need to take full advantage of Google\'s social media tools.', 'wdgpo') . '</p>' .
						'<p><b>' . __('Check out the Google+ Plugin\'s awesome features:', 'wdgpo') . '</b></p>' .
						'<ul>' .
							'<li>' . __('<b>Add a +1 button to your site</b>  and let visitors spread the word about your content.', 'wdgpo') . '</li>' .
							'<li>' . __('<b>Integrate your WordPress site with Google Pages</b> so people can easily add you to their circles.', 'wdgpo') . '</li>' .
							'<li>' . __('<b>Post directly to WordPress from Google+</b> for a faster blogging experience.', 'wdgpo') . '</li>' .
							'<li>' . __('<b>Post from G+ directly to your BuddyPress activity stream</b>', 'wdgpo') . '</li>' .
						'</ul>' .
					''
				),
				array(
					'id' => 'wdgpo-google-setup',
					'title' => __('Setting up your Google+ account info', 'wdgpo'),
					'content' => '' .
						'<p>' . __('Follow these steps to set up <em>My Google+ page ID</em> and <em>My Google+ profile ID</em> fields', 'wdgpo') . '</p>' .
						'<ol>' .
							'<li>' . __('If you don\'t already own a Google+ account, go to the <a href="https://plus.google.com/" target="_blank">Google+ page</a> and get an account', 'wdgpo') . '</li>' .
							'<li>' . __('Once you have an account, sign in', 'wdgpo') . '</li>' .
							'<li>' . __('Next, click on your name and in the URL at the top is a long number right before <code>/posts</code>', 'wdgpo') . '</li>' .
							'<li>' . __('Copy the number (approximately 21 characters)', 'wdgpo') . '</li>' .
							'<li>' . __('Paste it into <em>My Google+ profile ID</em> field', 'wdgpo') . '</li>' .
							'<li>' . __('To set up Google+ page ID, navigate to your Google+ page', 'wdgpo') . '</li>' .
							'<li>' . __('Copy the long number at the end of the URL (approximately 20ish characters)', 'wdgpo') . '</li>' .
							'<li>' . __('Paste it into <em>My Google+ page ID</em> field', 'wdgpo') . '</li>' .
						'</ol>' .
					''
				),
				array(
					'id' => 'wdgpo-api-setup',
					'title' => __('Setting up Google API settings', 'wdgpo'),
					'content' => '' .
						'<p>' . __('Follow these steps to set up <em>My Google+ page ID</em> and <em>My Google+ profile ID</em> fields', 'wdgpo') . '</p>' .
						"<ol>" .
							"<li><a href='https://code.google.com/apis/console/'>" . __('Create a project in Google API Console', 'wdgpo') . '</a></li>' .
							'<li>' . __('Under &quot;Services&quot; tab, turn &quot;Google+ API&quot; to <b>ON</b>', 'wdgpo') . '</li>' .
							'<li>' . sprintf(__("Under &quot;API Access&quot; click &quot;Create oAuth Client Access.&quot; Fill in your details and use this as your &quot;Authorized Redirect URIs&quot;: <code>%s</code>", 'wdgpo'), esc_url($auth->get_redirect_url())) . '</li>' .
							'<li>' . __('Copy your <b>Client ID</b> and <b>Client secret</b> values and paste them in the corresponding fields on this page', 'wdgpo') . '</li>' .
							'<li>' . __('Save your plugin settings and click the &quot;Authenticate&quot; button', 'wdgpo') . '</li>' .
						"</ol>" .
					''
				),
				array(
					'id' => 'wdgpo-plusone',
					'title' => __('Settings', 'wdgpo'),
					'content' => '' . 
							'<h4>' . __('Google +1 settings', 'wdgpo') . '</h4>' .
							'<p>' . __('Set up appearance and behavior of your +1 buttons. For integration with your Google Analytics setup, see <b>Google Analytics integration</b> further down the page.', 'wdgpo') . '</p>' .
							
							'<h4>' . __('Google+ Pages and Profiles', 'wdgpo') . '</h4>' .
							'<p>' . __('Set up your Google+ identities for Google+ Pages integration and syncing posts with WordPress.', 'wdgpo') . '</p>' .
							
							'<h4>' . __('Google+ activities import', 'wdgpo') . '</h4>' .
							'<p>' . __('Set up synchronisation of your Google+ profiles and your WordPress blog.', 'wdgpo') . '</p>' .
						'',
				),
			),
			$this->_sidebar,
			true
		);	
	}
}
