<?php

class AWPCP_Admin_Settings {

	public function AWPCP_Admin_Settings() {
		// TODO: avoid instatiation of this class until is necessary
		$pages = new AWPCP_Classified_Pages_Settings();
		$facebook = new AWPCP_Facebook_Page_Settings();
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

require_once( AWPCP_DIR . '/includes/helpers/facebook.php' );

class AWPCP_Facebook_Page_Settings {

	public function __construct() {
		add_action( 'load-classifieds_page_awpcp-admin-settings', array( $this, 'maybe_redirect' ) );
		add_action( 'awpcp-admin-settings-page--facebook-settings', array($this, 'dispatch'));
	}

	public function maybe_redirect() {
		if ( !isset( $_GET['g'] ) || $_GET['g'] != 'facebook-settings' || $this->get_current_action() != 'obtain_user_token' )
			return;

		if ( isset( $_GET[ 'error_code' ] ) ) {
			return $this->redirect_with_error( $_GET[ 'error_code' ], urlencode( $_GET['error_message'] )  );
		}

		$code = isset( $_GET['code'] ) ? $_GET['code'] : '';

		$fb = AWPCP_Facebook::instance();
		$access_token = $fb->token_from_code( $code );

		if ( ! $access_token ) {
			return $this->redirect_with_error( 1, '' );
		}

		$fb->set( 'user_token', $access_token );

		wp_redirect( admin_url( 'admin.php?page=awpcp-admin-settings&g=facebook-settings' ) );
		die();
	}

	public function get_current_action() {
		if ( isset( $_POST['diagnostics'] ) )
			return 'diagnostics';

		if ( isset( $_POST['save_config'] ) )
			return 'save_config';

		if ( isset( $_REQUEST['obtain_user_token'] ) && $_REQUEST['obtain_user_token'] == 1 )
			return 'obtain_user_token';

		return 'display_settings';
	}

	private function redirect_with_error( $error_code, $error_message ) {
		$params = array( 'code_error' => $error_code, 'error_message' => $error_message );
		$settings_url = admin_url( 'admin.php?page=awpcp-admin-settings&g=facebook-settings' );
		wp_redirect( add_query_arg( $params, $settings_url ) );
		die();
	}

	private function get_current_settings_step() {
		$fb = AWPCP_Facebook::instance();
		$config = $fb->get_config();

		if ( !empty( $config['app_id'] ) && !empty( $config['app_secret'] ) ) {
			if ( !empty( $config['user_token'] )  && !empty( $config['user_id'] ) )
				return 3;
			else
				return 2;
		}

		return 1; 
	}

	public function dispatch() {
		$action = $this->get_current_action();

		switch ( $action ) {
			case 'save_config':
				return $this->save_config();
				break;

			case 'diagnostics':
			case 'display_settings':
			default:
				return $this->display_settings();
				break;
		}
	}
	
	private function display_settings( $errors=array() ) {
		$fb = AWPCP_Facebook::instance();
		$config = $fb->get_config();
		$current_step = $this->get_current_settings_step();

		if ( $current_step == 3 ) {
			// User Pages.
			$pages = $fb->get_user_pages();
		}

		if ( $current_step >= 2 ) {
			// Login URL.
			$redirect_uri = add_query_arg( 'obtain_user_token', 1, admin_url( '/admin.php?page=awpcp-admin-settings&g=facebook-settings' ) );
			$login_url = $fb->get_login_url( $redirect_uri, 'publish_stream,manage_pages' );
		}

		if ( isset( $_GET['code_error'] ) && isset( $_GET['error_message'] )  ) {
			$errors[] = sprintf( __( 'AWPCP could not obtain a valid access token from Facebook: %s', 'AWPCP' ), $_GET['error_message'] );
		} else if ( isset( $_GET['code_error'] ) ) {
			$errors[] = __( 'AWPCP could not obtain a valid access token from Facebook. Please try again.', 'AWPCP' );
		}

		if ( $this->get_current_action() == 'diagnostics' ) {
			$diagnostics_errors = array();
			$fb->validate_config( $diagnostics_errors );

			$error_msg  = '';
			$error_msg .= '<strong>' . __( 'Facebook Config Diagnostics', 'AWPCP' ) . '</strong><br />';			

			if ( $diagnostics_errors ) {				
				foreach ( $diagnostics_errors as &$e ) {
					$error_msg .= '&#149; ' . $e . '<br />';
				}
			} else {
				$error_msg .= __( 'Everything looks OK.', 'AWPCP' );
			}

			$errors[] = $error_msg;
		}

		ob_start();
			include(AWPCP_DIR . '/admin/templates/admin-panel-settings-facebook-settings.tpl.php');
			$content = ob_get_contents();
		ob_end_clean();

		echo $content;
	}

	private function save_config() {
		$awpcp_fb = AWPCP_Facebook::instance();
		$config = $awpcp_fb->get_config();

		$app_id = isset( $_POST['app_id'] ) ? trim( $_POST['app_id'] ) : '';
		$app_secret = isset( $_POST['app_secret'] ) ? trim( $_POST['app_secret'] ) : '';
		$user_token = isset( $_POST['user_token'] ) ? trim( $_POST['user_token'] ) : '';
		$page = isset( $_POST['page'] ) ? trim( $_POST['page'] ) : '';

		$config['app_id'] = $app_id;
		$config['app_secret'] = $app_secret;
		$config['user_token'] = $user_token;

		if ( $page ) {
			$parts = explode( '|', $page );
			$page_id = $parts[0];
			$page_token = $parts[1];

			$config['page_id'] = $page_id;
			$config['page_token'] = $page_token;
		}

		$awpcp_fb->set_config( $config );

		if ( $last_error = $awpcp_fb->get_last_error() ) {
			$message = __( 'There was an error trying to contact Facebook servers: "%s".', 'AWPCP' );
			$errors[] = sprintf( $message, $last_error->message );
		} else {
			$errors = array();
		}

		return $this->display_settings( $errors );
	}

}
