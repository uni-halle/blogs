<?php

class AWPCP_Admin_Settings {

	public function AWPCP_Admin_Settings() {
		// TODO: avoid instatiation of this class until is necessary
		$pages = new AWPCP_Classified_Pages_Settings();
	}

	public function dispatch() {
		global $awpcp;

		$groups = $awpcp->settings->groups;
		unset($groups['private-settings']);

		$group = $groups[awpcp_request_param('g', 'pages-settings')];

		ob_start();
			include(AWPCP_DIR . '/admin/templates/admin-panel-settings.tpl.php');
			$content = ob_get_contents();
		ob_end_clean();

		echo $content;
	}

	public function scripts() {
		wp_enqueue_script('awpcp-admin-settings');
	}
}

class AWPCP_Classified_Pages_Settings {

	public function __construct() {
		add_action('awpcp-admin-settings-page--pages-settings', array($this, 'dispatch'));
	}

	public function dispatch() {
		global $awpcp;

		$nonce = awpcp_post_param('_wpnonce');
		$restore = awpcp_post_param('restore-pages', false);
		if ($restore && wp_verify_nonce($nonce, 'awpcp-restore-pages')) {
			$awpcp->restore_pages();
		}

		$missing = $awpcp->get_missing_pages();

		ob_start();
			include(AWPCP_DIR . '/admin/templates/admin-panel-settings-pages-settings.tpl.php');
			$content = ob_get_contents();
		ob_end_clean();

		echo $content;
	}
}
