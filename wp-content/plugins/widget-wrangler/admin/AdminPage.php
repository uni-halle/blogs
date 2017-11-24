<?php

namespace WidgetWrangler;

/**
 * Class AdminPage
 * @package WidgetWrangler
 */
class AdminPage {

	/**
	 * Hook for this specific page.
	 *
	 * @var null
	 */
	public $page_hook = null;

	/**
	 * Widget Wrangler settings values array.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Parent relative url for this page.
	 *
	 * @return string
	 */
	function parentSlug() {
		return 'edit.php?post_type=widget';
	}

	/**
	 * Capability required to access this page.
	 *
	 * @return string
	 */
	function capability() {
		return 'manage_options';
	}

	/**
	 * This page's title.
	 *
	 * @return string
	 */
	function title() {
		return __('Page Title', 'widgetwrangler');
	}

	/**
	 * This page's menu title.
	 *
	 * @return string
	 */
	function menuTitle() {
		return $this->title();
	}

	/**
	 * This page's description.
	 *
	 * @return string|array
	 */
	function description() {
		return '';
	}

	/**
	 * This page's unique slug.
	 *
	 * @return string
	 */
	function slug() {
		return '';
	}

	/**
	 * Array of action_name => callable pairs
	 *
	 * @return array
	 */
	function actions() {
		return array();
	}

	/**
	 * AdminPage constructor.
	 *
	 * @param $settings
	 */
	function __construct($settings) {
		$this->settings = $settings;
	}

	/**
	 * Register hooks.
	 *
	 * @param $settings
	 *
	 * @return AdminPage
	 */
	public static function register($settings) {
		$class = get_called_class();
		$plugin = new $class($settings);

		add_action( 'admin_menu', array( $plugin, 'menu' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin, 'enqueue') );

		return $plugin;
	}

	/**
	 * WordPress hook admin_menu.
	 */
	function menu() {
		if ( $this->parentSlug() && $this->slug()) {
			$this->page_hook = add_submenu_page( $this->parentSlug(), $this->title(), $this->menuTitle(), $this->capability(), $this->slug(), array( $this, 'route' ));
		}
	}

	/**
	 * Override to enqueue styles and scripts
	 */
	function enqueue() {
		if ( $this->onPage() ) {
			wp_enqueue_style('ww-admin');
			wp_enqueue_script('ww-admin');
		}
	}

	/**
	 * Helper function to determine if the user is on this admin page.
	 *
	 * @return bool
	 */
	function onPage() {
		$screen = get_current_screen();
		return $screen->id == $this->page_hook;
	}

	/**
	 * Helper function to get the relative page path.
	 *
	 * @return string
	 */
	function pagePath() {
		return $this->parentSlug() . '&page=' . $this->slug();
	}

	/**
     * Helper function to get the full page url.
     *
	 * @return string
	 */
	function pageUrl() {
	    return admin_url( $this->pagePath() );
    }

	/**
     * Standard action path creation.
     *
	 * @param $action
	 *
	 * @return string
	 */
	function actionPath( $action ) {
	    return "{$this->pagePath()}&ww_action={$action}&noheader=true";
    }

	/**
	 * Route the page navigation
	 */
	function route() {
		$action = !empty($_GET['ww_action']) ? $_GET['ww_action'] : null;
		$actions = $this->actions();

		if ( $action && !empty( $actions[ $action ] ) && is_callable( $actions[ $action ] ) ) {
			$result = call_user_func( $actions[ $action ] );
			$message = null;
			$redirect = $_SERVER['HTTP_REFERER'];

			if ( is_array($result) && !empty( $result['redirect'] ) ) {
				$redirect = $result['redirect'];
			}

			if ( is_array($result) && !empty( $result['message'] ) ) {
				AdminMessages::instance()->add( $result['message'], $result['type'] );
			}

			wp_safe_redirect($redirect);
			exit;
		}

		$this->showPage();
	}

	/**
	 * Output the templated page.
	 */
	function showPage() {
		ob_start();
			$this->page();
		$content = ob_get_clean();

		print $this->templatePage(array(
			'title' => $this->title(),
			'description' => $this->description(),
			'content' => $content,
		));
	}

	/**
	 * Override in child to produce page output.
	 */
	function page() {}


	/**
	 * Page wrapper template
	 *
	 * @param array $page
	 *
	 * @return string
	 */
	function templatePage($page = array()) {
		$default_page = array(
			'title' => '',
			'description' => '',
			'content' => '',
		);
		$page = array_replace($default_page, $page);
		ob_start();
		?>
		<div class="wrap">
			<h2><?php printf( __('%s', 'widgetwrangler'), $page['title']); ?></h2>
			<?php
			print $this->templateMessages();
			print $this->templateDescriptions($page['description']);
			?>
			<div>
				<?php print $page['content']; ?>
			</div>
			<div class="ww-clear-gone">&nbsp;</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template messages.
	 *
	 * @return string
	 */
	function templateMessages() {
		$messages = AdminMessages::instance()->get();

		ob_start();
		if ( !empty( $messages ) ) {
			?>
			<div id="message">
				<?php foreach ( $messages as $message ) { ?>
					<div class="<?php print $message['type']; ?> notice is-dismissible">
						<p><?php print $message['message']; ?></p>
					</div>
				<?php } ?>
			</div>
			<?php
		}

		return ob_get_clean();
	}

	/**
	 * Template descriptions.
	 *
	 * @param $descriptions array
	 *
	 * @return string
	 */
	function templateDescriptions( $descriptions ) {
		ob_start();
		if ( !empty( $descriptions ) ) {
			if ( !is_array($descriptions) ){
				$descriptions = array($descriptions);
			}
			?>
			<div class="ww-box description">
				<?php foreach ( $descriptions as $description ) { ?>
					<p><?php print $description; ?></p>
				<?php } ?>
			</div>
			<?php
		}

		return ob_get_clean();
	}

	/**
	 * Standard action result array
	 *
	 * @param string $message
	 * @param null $redirect
	 * @param string $type
	 *
	 * @return array
	 */
	function result( $message, $redirect = null, $type = 'updated') {
		$result = array(
			'message' => $message,
			'type' => $type,
		);

		if ( $redirect ) {
			$result['redirect'] = $redirect;
		}

		return $result;
	}

	/**
	 * An error result.
	 *
	 * @param $message
	 * @param null $redirect
	 *
	 * @return array
	 */
	function error( $message = null, $redirect = null ) {
		if (!$message) {
			$message = __('Something went wrong, please refresh the page and try again.', 'widgetwrangler');
		}
		return $this->result($message, $redirect, 'error');
	}
}
