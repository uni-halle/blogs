<?php

if ( ! class_exists( 'Inkthemes_About_Page' ) ) {

	// Include utils functions
	require_once( get_template_directory() . '/includes/plugin-notification/cw-notifications/functions/cw-notifications-utils.php' );

	/**
	 * Singleton class used for generating the about page of the theme.
	 */
	class Inkthemes_About_Page {
		/**
		 * Define the version of the class.
		 *
		 * @var string $version The Inkthemes_About_Page class version.
		 */
		private $version = '1.0.0';
		/**
		 * Used for loading the texts and setup the actions inside the page.
		 *
		 * @var array $config The configuration array for the theme used.
		 */
		private $config;
		/**
		 * Get the theme name using wp_get_theme.
		 *
		 * @var string $theme_name The theme name.
		 */
		private $theme_name;
		/**
		 * Get the theme slug ( theme folder name ).
		 *
		 * @var string $theme_slug The theme slug.
		 */
		private $theme_slug;
		/**
		 * The current theme object.
		 *
		 * @var WP_Theme $theme The current theme.
		 */
		private $theme;
		/**
		 * Holds the theme version.
		 *
		 * @var string $theme_version The theme version.
		 */
		private $theme_version;
		/**
		 * Define the menu item name for the page.
		 *
		 * @var string $menu_name The name of the menu name under Appearance settings.
		 */
		private $menu_name;
		/**
		 * Define the page title name.
		 *
		 * @var string $page_name The title of the About page.
		 */
		private $page_name;
		/**
		 * Define the page tabs.
		 *
		 * @var array $tabs The page tabs.
		 */
		private $tabs;
		/**
		 * Define the html notification content displayed upon activation.
		 *
		 * @var string $notification The html notification content.
		 */
		private $notification;
		/**
		 * The single instance of Inkthemes_About_Page
		 *
		 * @var Inkthemes_About_Page $instance The  Inkthemes_About_Page instance.
		 */
		private static $instance;

		/**
		 * The Main Inkthemes_About_Page instance.
		 *
		 * We make sure that only one instance of Inkthemes_About_Page exists in the memory at one time.
		 *
		 * @param array $config The configuration array.
		 */
		public static function init( $config ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Inkthemes_About_Page ) ) {
				self::$instance = new Inkthemes_About_Page;
				if ( ! empty( $config ) && is_array( $config ) ) {
					self::$instance->config = $config;
					self::$instance->setup_config();
					self::$instance->setup_actions();
				}
			}

		}

		/**
		 * Setup the class props based on the config array.
		 */
		public function setup_config() {
			$theme = wp_get_theme();
			if ( is_child_theme() ) {
				$this->theme_name = $theme->parent()->get( 'Name' );
				$this->theme      = $theme->parent();
			} else {
				$this->theme_name = $theme->get( 'Name' );
				$this->theme      = $theme->parent();
			}
			$this->theme_version = $theme->get( 'Version' );
			$this->theme_slug    = $theme->get_template();
//			$this->menu_name     = isset( $this->config['menu_name'] ) ? $this->config['menu_name'] : 'About ' . $this->theme_name;
//			$this->page_name     = isset( $this->config['page_name'] ) ? $this->config['page_name'] : 'About ' . $this->theme_name;
//			$this->notification  = isset( $this->config['notification'] ) ? $this->config['notification'] : ( apply_filters( 'colorway_welcome_notice_filter', ( '<p>' . sprintf( 'Welcome! Thank you for choosing %1$s! To fully take advantage of the best our theme can offer please make sure you visit our %2$swelcome page%3$s.', $this->theme_name, '<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-welcome' ) ) . '">', '</a>' ) . '</p><p><a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-welcome' ) ) . '" class="button" style="text-decoration: none;">' . sprintf( 'Get started with %s', $this->theme_name ) . '</a></p>' ) ) );
//			$this->tabs          = isset( $this->config['tabs'] ) ? $this->config['tabs'] : array();

		}

		/**
		 * Setup the actions used for this page.
		 */
		public function setup_actions() {

			//add_action( 'admin_menu', array( $this, 'register' ) );
			/* activation notice */
			//add_action( 'load-themes.php', array( $this, 'activation_admin_notice' ) );
			/* enqueue script and style for about page */
			add_action( 'admin_enqueue_scripts', array( $this, 'style_and_scripts' ) );

			/* ajax callback for dismissable required actions */
			add_action( 'wp_ajax_cw_about_page_dismiss_required_action', array( $this, 'dismiss_required_action_callback' ) );
			add_action( 'wp_ajax_nopriv_cw_about_page_dismiss_required_action', array( $this, 'dismiss_required_action_callback' ) );
		}



		/**
		 * Render the main content page.
		 */
		public function inkthemes_about_page_render() {

			if ( ! empty( $this->config['welcome_title'] ) ) {
				$welcome_title = $this->config['welcome_title'];
			}
			if ( ! empty( $this->config['welcome_content'] ) ) {
				$welcome_content = $this->config['welcome_content'];
			}

			if ( ! empty( $welcome_title ) || ! empty( $welcome_content ) || ! empty( $this->tabs ) ) {

				echo '<div class="wrap about-wrap epsilon-wrap">';

				if ( ! empty( $welcome_title ) ) {
					echo '<h1>';
					echo esc_html( $welcome_title );
					if ( ! empty( $this->theme_version ) ) {
						echo esc_html( $this->theme_version ) . ' </sup>';
					}
					echo '</h1>';
				}
				if ( ! empty( $welcome_content ) ) {
					echo '<div class="about-text">' . wp_kses_post( $welcome_content ) . '</div>';
				}

				echo '<a href="https://inkthemes.com/colorway" target="_blank" class="wp-badge epsilon-welcome-logo"></a>';

				/* Display tabs */
				if ( ! empty( $this->tabs ) ) {
					$active_tab = isset( $_GET['tab'] ) ? wp_unslash( $_GET['tab'] ) : 'getting_started';

					echo '<h2 class="nav-tab-wrapper wp-clearfix">';

					$actions_count = $this->get_required_actions();

					$count = 0;

					if ( ! empty( $actions_count ) ) {
						$count = count( $actions_count );
					}

					foreach ( $this->tabs as $tab_key => $tab_name ) {

						if ( ( $tab_key != 'changelog' ) || ( ( $tab_key == 'changelog' ) && isset( $_GET['show'] ) && ( $_GET['show'] == 'yes' ) ) ) {

							if ( ( $count == 0 ) && ( $tab_key == 'recommended_actions' ) ) {
								continue;
							}

							echo '<a href="' . esc_url( admin_url( 'themes.php?page=' . $this->theme_slug . '-welcome' ) ) . '&tab=' . $tab_key . '" class="nav-tab ' . ( $active_tab == $tab_key ? 'nav-tab-active' : '' ) . '" role="tab" data-toggle="tab">';
							echo esc_html( $tab_name );
							if ( $tab_key == 'recommended_actions' ) {
								$count = 0;

								$actions_count = $this->get_required_actions();

								if ( ! empty( $actions_count ) ) {
									$count = count( $actions_count );
								}
								if ( $count > 0 ) {
									echo '<span class="badge-action-count">' . esc_html( $count ) . '</span>';
								}
							}
							echo '</a>';
						}
					}

					echo '</h2>';

					/* Display content for current tab */
					if ( method_exists( $this, $active_tab ) ) {
						$this->$active_tab();
					}
				}// End if().

				echo '</div><!--/.wrap.about-wrap-->';
			}// End if().
		}

		/**
		 * Call plugin api
		 */
		public function call_plugin_api( $slug ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

			$call_api = get_transient( 'cw_about_plugin_info_' . $slug );

			if ( false === $call_api ) {
				$call_api = plugins_api(
					'plugin_information', array(
						'slug'   => $slug,
						'fields' => array(
							'downloaded'        => false,
							'rating'            => false,
							'description'       => false,
							'short_description' => true,
							'donate_link'       => false,
							'tags'              => false,
							'sections'          => true,
							'homepage'          => true,
							'added'             => false,
							'last_updated'      => false,
							'compatibility'     => false,
							'tested'            => false,
							'requires'          => false,
							'downloadlink'      => false,
							'icons'             => true,
						),
					)
				);
				set_transient( 'cw_about_plugin_info_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
			}

			return $call_api;
		}

		/**
		 * Check if plugin is active
		 *
		 * @param plugin-slug $slug the plugin slug.
		 * @return array
		 */
		public function check_if_plugin_active( $slug ) {
			if ( ( $slug == 'intergeo-maps' ) || ( $slug == 'visualizer' ) ) {
				$plugin_root_file = 'index';
			} elseif ( $slug == 'adblock-notify-by-bweb' ) {
				$plugin_root_file = 'adblock-notify';
			} else {
				$plugin_root_file = $slug;
			}

			$path = WPMU_PLUGIN_DIR . '/' . $slug . '/' . $plugin_root_file . '.php';
			if ( ! file_exists( $path ) ) {
				$path = WP_PLUGIN_DIR . '/' . $slug . '/' . $plugin_root_file . '.php';
				if ( ! file_exists( $path ) ) {
					$path = false;
				}
			}

			if ( file_exists( $path ) ) {

				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

				$needs = is_plugin_active( $slug . '/' . $plugin_root_file . '.php' ) ? 'deactivate' : 'activate';

				return array(
					'status' => is_plugin_active( $slug . '/' . $plugin_root_file . '.php' ),
					'needs'  => $needs,
				);
			}

			return array(
				'status' => false,
				'needs'  => 'install',
			);
		}

		/**
		 * Get icon of wordpress.org plugin
		 *
		 * @param array $arr array of image formats.
		 *
		 * @return mixed
		 */
		public function get_plugin_icon( $arr ) {

			if ( ! empty( $arr['svg'] ) ) {
				$plugin_icon_url = $arr['svg'];
			} elseif ( ! empty( $arr['2x'] ) ) {
				$plugin_icon_url = $arr['2x'];
			} elseif ( ! empty( $arr['1x'] ) ) {
				$plugin_icon_url = $arr['1x'];
			} else {
				$plugin_icon_url = get_template_directory_uri() . '/includes/plugin-notification/cw-notifications/cw-about-page/images/placeholder_plugin.png';
			}

			return $plugin_icon_url;
		}

		/**
		 * Check if a slug is from intergeo, visualizer or adblock and returns the correct slug for them.
		 *
		 * @param string $slug Plugin slug.
		 *
		 * @return string
		 */
		public function check_plugin_slug( $slug ) {
			switch ( $slug ) {
				case 'intergeo-maps':
				case 'visualizer':
					$slug = 'index';
					break;
				case 'adblock-notify-by-bweb':
					$slug = 'adblock-notify';
					break;
			}
			return $slug;
		}

		/**
		 * Display button for recommended actions or
		 *
		 * @param array $data Data for an item.
		 */
		public function display_button( $data ) {
			$button_new_tab = '_self';
			$button_class   = '';
			if ( isset( $tab_data['is_new_tab'] ) ) {
				if ( $data['is_new_tab'] ) {
					$button_new_tab = '_blank';
				}
			}

			if ( $data['is_button'] ) {
				$button_class = 'button button-primary';
			}
			echo '<a target="' . $button_new_tab . '" href="' . $data['button_link'] . '"class="' . esc_attr( $button_class ) . '">' . $data['button_label'] . '</a>';
		}

		/**
		 * Getting started tab
		 */
		public function getting_started() {

			if ( ! empty( $this->config['getting_started'] ) ) {

				$getting_started = $this->config['getting_started'];

				if ( ! empty( $getting_started ) ) {

					echo '<div class="feature-section three-col">';

					foreach ( $getting_started as $getting_started_item ) {

						echo '<div class="col">';
						if ( ! empty( $getting_started_item['title'] ) ) {
							echo '<h3>' . $getting_started_item['title'] . '</h3>';
						}
						if ( ! empty( $getting_started_item['text'] ) ) {
							echo '<p>' . $getting_started_item['text'] . '</p>';
						}
						if ( ! empty( $getting_started_item['button_link'] ) && ! empty( $getting_started_item['button_label'] ) ) {

							echo '<p>';

							$count = 0;

							$actions_count = $this->get_required_actions();

							if ( ! empty( $actions_count ) ) {
								$count = count( $actions_count );
							}

							if ( $getting_started_item['recommended_actions'] && isset( $count ) ) {
								if ( $count == 0 ) {
									echo '<span class="dashicons dashicons-yes"></span>';
								} else {
									echo '<span class="dashicons dashicons-no-alt"></span>';
								}
							}
							$this->display_button( $getting_started_item );
							echo '</p>';
						}

						echo '</div><!-- .col -->';
					}// End foreach().
					echo '</div><!-- .feature-section three-col -->';
				}// End if().
			}// End if().
		}


		/**
		 * Load css and scripts for the about page
		 */
		public function style_and_scripts( $hook_suffix ) {

			// this is needed on all admin pages, not just the about page, for the badge action count in the WordPress main sidebar
			wp_enqueue_style( 'cw-about-page-css', get_template_directory_uri() . '/includes/plugin-notification/cw-notifications/cw-about-page/css/cw_about_page_css.css', array());

			if ( 'appearance_page_' . $this->theme_slug . '-welcome' == $hook_suffix ) {

				wp_enqueue_script( 'cw-about-page-js', get_template_directory_uri() . '/includes/plugin-notification/cw-notifications/cw-about-page/js/cw_about_page_scripts.js', array( 'jquery' ));

				wp_enqueue_style( 'plugin-install' );
				wp_enqueue_script( 'plugin-install' );
				wp_enqueue_script( 'updates' );

				$recommended_actions = isset( $this->config['recommended_actions'] ) ? $this->config['recommended_actions'] : array();
				$required_actions    = $this->get_required_actions();
				wp_localize_script(
					'cw-about-page-js', 'tiAboutPageObject', array(
						'nr_actions_required' => count( $required_actions ),
						'ajaxurl'             => admin_url( 'admin-ajax.php' ),
						'template_directory'  => get_template_directory_uri(),
						'activating_string'   => esc_html__( 'Activating', 'colorway' ),
					)
				);

			}

		}

	}
}// End if().
