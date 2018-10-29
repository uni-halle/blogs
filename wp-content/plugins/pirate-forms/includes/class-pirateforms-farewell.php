<?php

/**
 * Class PirateForms_Farewell handles notices that are displayed to user about PirateForms retirement.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      2.4.5
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2018, WPForms LLC
 */
class PirateForms_Farewell {

	/**
	 * Slug of a page where the migration screen is located.
	 *
	 * @since 2.4.5
	 *
	 * @var string
	 */
	const SLUG_MIGRATION_PAGE = 'pirateforms-admin-migration';

	/**
	 * Meta key where the option (of the time the Darewell notice was dismissed) is saved.
	 *
	 * @since 2.4.5
	 *
	 * @var string
	 */
	const SLUG_DISMISS_TIME = 'pirate_forms_farewell_dismissed';

	/**
	 * WPForms.com Full Announcement blog post URL.
	 * TODO: Update this URL.
	 *
	 * @since 2.4.5
	 *
	 * @var string
	 */
	const URL_FULL_ANNOUNCE  = 'https://wpforms.com/wpforms-has-acquired-pirate-forms?utm_source=pirateformsplugin&utm_campaign=pirateformsannouncement';

	/**
	 * Direct link to download the plugin.
	 *
	 * @since 2.4.5
	 *
	 * @var string
	 */
	const URL_WPFORMS_ZIP = 'https://downloads.wordpress.org/plugin/wpforms-lite.zip';

	/**
	 * PirateForms_Farewell constructor.
	 *
	 * @since 2.4.5
	 */
	public function __construct() {


		add_action( 'admin_head', array( $this, 'process_notices' ) );
		add_action( 'wp_ajax_pirateforms_migration_install', array( $this, 'process_migration_install' ) );
		add_action( 'wp_ajax_pirateforms_migration_activate', array( $this, 'process_migration_activate' ) );
		add_filter( 'wpforms_upgrade_link_medium', array( $this, 'process_migration_source' ) );
	}

	/**
	 * Decide whether to display notices or not.
	 *
	 * @since 2.4.5
	 */
	public function process_notices() {

		/** @var \WP_Screen $screen */
		$screen = get_current_screen();

		if ( ! empty( $screen->base ) && $screen->base === 'dashboard' ) {

			if ( isset( $_GET['try_wpforms'] ) ) {
				$farewell = empty( $_GET['try_wpforms'] ) ? time() : 0;
				update_user_meta( get_current_user_id(), 'pirate_forms_farewell_dismissed', $farewell );
			}

			$farewell = get_user_meta( get_current_user_id(), 'pirate_forms_farewell_dismissed', true );

			if ( empty( $farewell ) ) {
				$this->display_detailed_notice();
			}
		}

		if ( $this->is_grace_period_ended() ) {
			$this->display_short_notice();
		}
	}

	/**
	 * Compare dates since detailed notice dismissal date and now.
	 *
	 * @since 2.4.5
	 *
	 * @return bool
	 */
	protected function is_grace_period_ended() {

		$dismissed = (int) get_user_meta( get_current_user_id(), 'pirate_forms_farewell_dismissed', true );

		if ( empty( $dismissed ) ) {
			return false;
		}

		$date_dismissed = DateTime::createFromFormat( 'U', $dismissed );

		return $date_dismissed->modify( '+1 month' ) <= DateTime::createFromFormat( 'U', time() );
	}

	/**
	 * Dismissable big (Gutenberg-like) dashboard-only notice about Pirate Forms retirement.
	 *
	 * @since 2.4.5
	 */
	public function display_detailed_notice() {

		// Only people appropriate people should see it.
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}
		?>

		<div id="try-wpforms-panel" class="try-wpforms-panel">
			<?php wp_nonce_field( 'try-wpforms-panel-nonce', 'trywpformspanelnonce', false ); ?>
			<a class="try-wpforms-panel-close" href="<?php echo esc_url( admin_url( '?try_wpforms=0' ) ); ?>" aria-label="<?php esc_attr_e( 'Dismiss the Try WPForms panel' ); ?>">
				<?php esc_html_e( 'Dismiss', 'pirate-forms' ); ?>
			</a>

			<div class="try-wpforms-panel-content">
				<h2><?php esc_html_e( 'A New, Modern Form Builder is Here!', 'pirate-forms' ); ?></h2>

				<p class="about-description">
					<?php esc_html_e( 'Pirate Forms is now part of the WPForms Family. Switch to WPForms today and unlock more powerful features (for free).', 'pirate-forms' ); ?>
				</p>

				<hr/>

				<div class="try-wpforms-panel-column-container">
					<div class="try-wpforms-panel-column try-wpforms-panel-image-column">
						<picture>
							<source srcset="about:blank" media="(max-width: 1024px)">
							<img src="<?php echo esc_url( PIRATEFORMS_URL . 'admin/img/wpforms-builder.png'); ?>"
							     alt="<?php esc_attr_e( 'Screenshot from the WPForms Builder interface', 'pirate-forms' ); ?>"/>
						</picture>
					</div>
					<div class="try-wpforms-panel-column plugin-card-wpforms">

						<div>
							<h3><?php esc_html_e( 'Switch to WPForms form builder today.', 'pirate-forms' ); ?></h3>

							<p>
								<?php esc_html_e( 'We’re committed to providing you the best WordPress form building experience. That’s why we have made it easy for you to try WPForms for free and move your existing contact forms with just a few clicks.', 'pirate-forms' ); ?>
							</p>

							<p>
								<?php esc_html_e( 'WPForms allows you to create unlimited forms with a modern drag & drop form builder. You will also get access to powerful form features. Experience the WPForms difference and see why over 1 million websites use WPForms.', 'pirate-forms' ); ?>
							</p>
						</div>

						<div class="try-wpforms-action">
							<p>
								<a class="button button-primary button-hero" href="<?php echo esc_url( $this->get_migration_page_url() ); ?>">
									<?php esc_html_e( 'Migrate to WPForms', 'pirate-forms' ); ?>
								</a>
							</p>

							<p>
								<?php
								echo '<a href="https://wpforms.com/" target="_blank" rel="noopener noreferrer">' .
								     esc_html__( 'Learn more about WPForms', 'pirate-forms' ) .
								     '</a>';
								?>
							</p>
						</div>
					</div>

					<div class="try-wpforms-panel-column plugin-card-classic-editor">

						<div>
							<h3><?php esc_html_e( 'We’re retiring Pirate Forms.', 'pirate-forms' ); ?></h3>

							<p>
								<?php esc_html_e( 'We’re retiring the Pirate Forms plugin in favor of the more powerful WPForms plugin. This means that there will be no new feature updates. We will continue to maintain the Pirate Forms plugin for any major security issues for the next 6 months.', 'pirate-forms' ); ?>
							</p>
							<p>
								<?php esc_html_e( 'We strongly encourage all users to switch to WPForms. We’ve built an easy importer that will migrate your forms and all settings with just a few clicks.', 'pirate-forms' ); ?>
							</p>
						</div>

						<div class="try-wpforms-action">
							<p>
								<a class="button button-secondary button-hero" href="<?php echo esc_url( self::URL_FULL_ANNOUNCE ); ?>" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Read the Full Announcement', 'pirate-forms' ); ?>
								</a>
							</p>
						</div>
					</div>
				</div>
			</div>

		</div>

		<script>
			jQuery(document).ready(function(){
				jQuery( '#try-wpforms-panel' ).insertAfter( '#wpbody-content .wrap h1' ).show();
			});
		</script>

		<?php
	}

	/**
	 * Non-dismissable notice displayed to a user after 30 days of detailed notice dismiss.
	 *
	 * @since 2.4.5
	 */
	public function display_short_notice() {

		echo '<div class="notice notice-error"><p>';
		printf(
			wp_kses(
				'<strong>Important:</strong> Pirate Forms is being retired. We have created an easy migrator to move your forms + settings to WPForms, which is the most user-friendly WordPress form builder. Click here to <a href="%1$s">migrate to WPForms</a> | <a href="%2$s" target="_blank" rel="noopener noreferrer">See the full announcement</a>.',
				array(
					'strong' => array(),
					'a'      => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
				)
			),
			esc_url( $this->get_migration_page_url() ), // Migration.
			'https://wpforms.com/#full-announcement' // Full announcement. TODO: change this URL.
		);
		echo '</p></div>';
	}

	/**
	 * URL to the page where a user will be guided to migrate to WPForms.
	 *
	 * @since 2.4.5
	 *
	 * @return string
	 */
	protected function get_migration_page_url() {
		return add_query_arg( 'page', self::SLUG_MIGRATION_PAGE, admin_url( 'admin.php' ) );
	}

	/**
	 * Do all the installation and activation logic.
	 * Like a boss.
	 *
	 * @since 2.4.5
	 */
	public function process_migration_install() {

		$error = esc_html__( 'Please manually install WPForms Lite from WordPress.org.', 'pirate-forms' );

		// Run a security check.
		if ( empty( $_POST['nonce'] ) || ! check_ajax_referer( 'pirateforms_migration', 'nonce', false ) ) {
			wp_send_json_error( $error );
		}

		// Set the current screen to avoid undefined notices.
		set_current_screen();

		$creds = request_filesystem_credentials( $this->get_migration_page_url(), '', false, false, null );

		// Check for file system permissions.
		if ( false === $creds ) {
			wp_send_json_error( $error );
		}

		if ( ! WP_Filesystem( $creds ) ) {
			wp_send_json_error( $error );
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once PIRATEFORMS_DIR . 'admin/class-pirateforms-migration-install-skin.php';

		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

		// Create the plugin upgrader with our custom skin.
		$installer = new Plugin_Upgrader( new PirateForms_Migration_Install_Skin() );

		// Error check.
		if ( ! method_exists( $installer, 'install' ) ) {
			wp_send_json_error( $error );
		}

		$installer->install( self::URL_WPFORMS_ZIP );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		if ( $installer->plugin_info() ) {

			$plugin_basename = $installer->plugin_info();

			// Activate, do not redirect, run the plugin activation routine.
			$activated = activate_plugin( $plugin_basename );

			if ( function_exists( 'wpforms' ) ) {
				update_option( 'wpforms_version_upgraded_from', wpforms()->version );
			}

			if ( ! is_wp_error( $activated ) ) {
				wp_send_json_success(
					esc_html__( 'Plugin was successfully installed and activated.', 'wpforms' )
				);
			}

			// Activation failed, so we need to instruct the user.
			$error = esc_html__( 'Please manually activate the WPForms plugin.', 'pirate-forms' );
		}

		wp_send_json_error( $error );
	}

	/**
	 * Do all the installation and activation logic.
	 * Like a boss.
	 *
	 * @since 2.4.5
	 */
	public function process_migration_activate() {

		$error = esc_html__( 'Please manually activate WPForms.', 'pirate-forms' );

		// Run a security check.
		if ( empty( $_POST['nonce'] ) || ! check_ajax_referer( 'pirateforms_migration', 'nonce', false ) ) {
			wp_send_json_error( $error );
		}

		$lite = 'wpforms-lite/wpforms.php';
		$pro  = 'wpforms/wpforms.php';

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();
		$activated   = false;

		// Plugin exists but not active.
		if ( array_key_exists( $pro, $all_plugins ) ) {
			$activated = activate_plugin( $pro );
		} else if ( array_key_exists( $lite, $all_plugins ) ) {
			$activated = activate_plugin( $lite );
		}

		// Null means success.
		if ( $activated !== false && ! is_wp_error( $activated ) ) {
			if ( function_exists( 'wpforms' ) ) {
				update_option( 'wpforms_version_upgraded_from', wpforms()->version );
			}

			wp_send_json_success(
				esc_html__( 'Plugin was successfully activated.', 'wpforms' )
			);
		}

		wp_send_json_error( $error );
	}

	public function process_migration_source() {
		return 'pirateforms';
	}
}
