<?php

class AWPCP_Admin_Debug {

	public function AWPCP_Admin_Debug() {
		add_action('init', array($this, 'download'));
	}

	public function scripts() {
	}

	private function blacklisted($setting) {
		// TODO: add other settings from premium modules
		$blacklisted = array(
			'tos',
			'admin-recipient-email',
			'awpcpadminemail',
			'paypalemail',
			'2checkout',
			'smtphost', 'smtpport', 'smtpusername', 'smtppassword',
			'googlecheckoutmerchantID', 'googlecheckoutsandboxseller', 'googlecheckoutbuttonurl',
			'authorize.net-login-id', 'authorize.net-transaction-key',
			'paypal-pro-username', 'paypal-pro-password', 'paypal-pro-signature',
		);
		return in_array($setting, $blacklisted);
	}

	private function sanitize($setting, $value) {
		static $hosts_regexp = '';
		static $email_regexp = '';

		if (empty($hosts_regexp)) {
			$hosts = array_unique(array(parse_url(home_url(), PHP_URL_HOST),
						   				parse_url(site_url(), PHP_URL_HOST)));
			$hosts_regexp = '/' . preg_quote(join('|', $hosts), '/') . '/';
			$email_regexp = '/[_a-z0-9-+]+(\.[_a-z0-9-+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})/';
		}

		$sanitized = (is_object($value) || is_array($value)) ? print_r($value, true) : $value;
		// remove Website domain
		$sanitized = preg_replace($hosts_regexp, '<host>', $sanitized);
		// remove email addresses
		$sanitized = preg_replace($email_regexp, '<email>', $sanitized);

		return $sanitized;
	}

	/**
	 * Renders an HTML page with AWPCP informaiton useful for debugging tasks.
	 *
	 * @since 2.0.7
	 */
	private function render($download=false) {
		global $awpcp, $wpdb, $wp_rewrite;

		$debug_info = $this;
		$options = $awpcp->settings->options;

        $options['awpcp_installationcomplete'] = get_option('awpcp_installationcomplete');
        $options['awpcp_pagename_warning'] = get_option('awpcp_pagename_warning');
        $options['widget_awpcplatestads'] = get_option('widget_awpcplatestads');
        $options['awpcp_db_version'] = get_option('awpcp_db_version');

        $plugin_pages_info = awpcp_get_plugin_pages_info();
        $page_objects = get_pages( array( 'include' => awpcp_get_properties( $plugin_pages_info, 'page_id', 0 ) ) );
        $plugin_pages = array();

        foreach ( $page_objects as $page ) {
        	$plugin_pages[ $page->ID ] = $page;
        }

		$rules = (array) $wp_rewrite->wp_rewrite_rules();

		ob_start();
			include(AWPCP_DIR . '/admin/templates/admin-panel-debug.tpl.php');
			$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Allow users to download Debug Info as an HTML file.
	 *
	 * @since 2.0.7
	 */
	public function download() {
		global $pagenow;

		if (!awpcp_current_user_is_admin()) return;

		if ($pagenow == 'admin.php' && awpcp_request_param('page') === 'awpcp-debug'
									&& awpcp_request_param('download') === 'debug-info') {
			$filename = sprintf('awpcp-debug-info-%s.html', date('Y-m-d-Hi', current_time('timestamp')));

			header('Content-Description: File Transfer');
			header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
			header('Content-Disposition: attachment; filename=' . $filename);
	        header("Pragma: no-cache");

			die($this->render(true));
		}
	}

	/**
	 * Handler for the Classifieds->Debug AWPCP Admin page.
	 *
	 * @since unknown
	 */
	public function dispatch() {
		echo $this->render();
	}
}
