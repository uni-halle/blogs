<?php
/**
 * Admin settings helper
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package     ColorWay
 * @author      ColorWay
 * @copyright   Copyright (c) 2018, ColorWay
 * @link        http://inkthemes.com/
 * @since       ColorWay 1.0
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Colorway_Admin_Settings')) {

    /**
     * ColorWay Admin Settings
     */
    class Colorway_Admin_Settings {

        /**
         * View all actions
         *
         * @since 1.0
         * @var array $view_actions
         */
        static public $view_actions = array();

        /**
         * Menu page title
         *
         * @since 1.0
         * @var array $menu_page_title
         */
        static public $menu_page_title = 'ColorWay Theme';

        /**
         * Page title
         *
         * @since 1.0
         * @var array $page_title
         */
        static public $page_title = 'ColorWay';

        /**
         * Plugin slug
         *
         * @since 1.0
         * @var array $plugin_slug
         */
        static public $plugin_slug = 'colorway';

        /**
         * Default Menu position
         *
         * @since 1.0
         * @var array $default_menu_position
         */
        static public $default_menu_position = 'themes.php';

        /**
         * Parent Page Slug
         *
         * @since 1.0
         * @var array $parent_page_slug
         */
        static public $parent_page_slug = 'general';

        /**
         * Current Slug
         *
         * @since 1.0
         * @var array $current_slug
         */
        static public $current_slug = 'general';

        /**
         * Constructor
         */
        function __construct() {

            if (!is_admin()) {
                return;
            }

            add_action('after_setup_theme', __CLASS__ . '::init_admin_settings', 99);
        }

        /**
         * Admin settings init
         */
        static public function init_admin_settings() {
            self::$menu_page_title = apply_filters('colorway_menu_page_title', __('ColorWay Options', 'colorway'));
            self::$page_title = apply_filters('colorway_page_title', __('ColorWay', 'colorway'));

            if (isset($_REQUEST['page']) && strpos(sanitize_key(wp_unslash($_REQUEST['page'])), self::$plugin_slug) !== false) {

                add_action('admin_enqueue_scripts', __CLASS__ . '::styles_scripts');

                // Let extensions hook into saving.
                do_action('colorway_admin_settings_scripts');

                self::save_settings();
            }

//			add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_scripts' );
//			add_action( 'customize_controls_enqueue_scripts', __CLASS__ . '::customizer_scripts' );

            add_action('admin_menu', __CLASS__ . '::add_admin_menu', 99);

            add_action('colorway_menu_general_action', __CLASS__ . '::general_page');

            add_action('colorway_header_right_section', __CLASS__ . '::top_header_right_section');

            add_filter('admin_title', __CLASS__ . '::colorway_admin_title', 10, 2);

            add_action('colorway_welcome_page_content', __CLASS__ . '::colorway_welcome_page_sites_section', 10);

            add_action('colorway_welcome_page_content', __CLASS__ . '::colorway_welcome_page_content');
            //add_action('colorway_welcome_page_content', __CLASS__ . '::colorway_welcome_page_five_star_scetion', 13);

            // AJAX.
            add_action('wp_ajax_colorway-sites-plugin-activate', __CLASS__ . '::required_plugin_activate');
        }

        /**
         * View actions
         */
        static public function get_view_actions() {

            if (empty(self::$view_actions)) {

                $actions = array(
                    'general' => array(
                        'label' => __('Welcome', 'colorway'),
                        'show' => !is_network_admin(),
                    ),
                );
                self::$view_actions = apply_filters('colorway_menu_options', $actions);
            }

            return self::$view_actions;
        }

        /**
         * Save All admin settings here
         */
        static public function save_settings() {

            // Only admins can save settings.
            if (!current_user_can('manage_options')) {
                return;
            }

            // Let extensions hook into saving.
            do_action('colorway_admin_settings_save');
        }

        /**
         * Load the scripts and styles in the customizer controls.
         *
         * @since 1.2.1
         */
//		static public function customizer_scripts() {
//			$color_palettes = json_encode( colorway_color_palette() );
//			wp_add_inline_script( 'wp-color-picker', 'jQuery.wp.wpColorPicker.prototype.options.palettes = ' . $color_palettes . ';' );
//		}

        /**
         * Enqueues the needed CSS/JS for Backend.
         *
         * @since 1.0
         */
//		static public function admin_scripts() {
//
//			// Styles.
//			wp_enqueue_style( 'colorway-admin-styles', get_template_directory_uri() . '/includes/css/colorway-admin.css');
//
//			/* Directory and Extension */
//			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
//			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';
//
////			$assets_js_uri =  get_template_directory() . '/js/' . $dir_name . '/';
////
////			wp_enqueue_script( 'colorway-color-alpha', $assets_js_uri . 'wp-color-picker-alpha' . $file_prefix . '.js', array( 'jquery', 'customize-base', 'wp-color-picker' ), true );
//		}

        /**
         * Enqueues the needed CSS/JS for the builder's admin settings page.
         *
         * @since 1.0
         */
        static public function styles_scripts() {

            // Styles.
            wp_enqueue_style('colorway-admin-settings', get_template_directory_uri() . '/includes/colorway-admin-settings/css/colorway-admin-menu-settings.css', array());
            // Script.
            wp_enqueue_script('colorway-admin-settings', get_template_directory_uri() . '/includes/colorway-admin-settings/js/colorway-admin-menu-settings.js', array('jquery', 'wp-util', 'updates'));

            $localize = array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'btnActivating' => __('Activating Importer Plugin ', 'colorway') . '&hellip;',
                'colorwaySitesLink' => admin_url('themes.php?page=colorway-sites'),
                'colorwaySitesLinkTitle' => __('See Library »', 'colorway'),
            );
            wp_localize_script('colorway-admin-settings', 'colorway', apply_filters('colorway_theme_js_localize', $localize));
        }

        /**
         * Update Admin Title.
         *
         * @since 1.0.19
         *
         * @param string $admin_title Admin Title.
         * @param string $title Title.
         * @return string
         */
        static public function colorway_admin_title($admin_title, $title) {

            $screen = get_current_screen();
            if ('appearance_page_colorway' == $screen->id) {

                $view_actions = self::get_view_actions();

                $current_slug = isset($_GET['action']) ? esc_attr(sanitize_key(wp_unslash($_GET['action']))) : self::$current_slug;
                $active_tab = str_replace('_', '-', $current_slug);

                if ('general' != $active_tab && isset($view_actions[$active_tab]['label'])) {
                    $admin_title = str_replace($title, $view_actions[$active_tab]['label'], $admin_title);
                }
            }

            return $admin_title;
        }

        /**
         * Get and return page URL
         *
         * @param string $menu_slug Menu name.
         * @since 1.0
         * @return  string page url
         */
        static public function get_page_url($menu_slug) {

            $parent_page = self::$default_menu_position;

            if (strpos($parent_page, '?') !== false) {
                $query_var = '&page=' . self::$plugin_slug;
            } else {
                $query_var = '?page=' . self::$plugin_slug;
            }

            $parent_page_url = admin_url($parent_page . $query_var);

            $url = $parent_page_url . '&action=' . $menu_slug;

            return esc_url($url);
        }

        /**
         * Add main menu
         *
         * @since 1.0
         */
        static public function add_admin_menu() {

            $parent_page = self::$default_menu_position;
            $page_title = self::$menu_page_title;
            $capability = 'manage_options';
            $page_menu_slug = self::$plugin_slug;
            $page_menu_func = __CLASS__ . '::menu_callback';

            if (apply_filters('colorway_dashboard_admin_menu', true)) {
                add_theme_page($page_title, $page_title, $capability, $page_menu_slug, $page_menu_func);
            } else {
                do_action('colorway_register_admin_menu', $parent_page, $page_title, $capability, $page_menu_slug, $page_menu_func);
            }
        }

        /**
         * Menu callback
         *
         * @since 1.0
         */
        static public function menu_callback() {

            $current_slug = isset($_GET['action']) ? esc_attr(sanitize_key(wp_unslash($_GET['action']))) : self::$current_slug;

            $active_tab = str_replace('_', '-', $current_slug);
            $current_slug = str_replace('-', '_', $current_slug);

            $ast_icon = apply_filters('colorway_page_top_icon', true);
            $ast_visit_site_url = apply_filters('colorway_site_url', 'https://www.inkthemes.com/colorway/');
            $ast_wrapper_class = apply_filters('colorway_welcome_wrapper_class', array($current_slug));
            ?>
            <div class="cwy-menu-page-wrapper wrap cwy-clear <?php echo esc_attr(implode(' ', $ast_wrapper_class)); ?>">
                <div class="cwy-theme-page-header">
                    <div class="cwy-container cwy-flex">
                        <div class="cwy-theme-title">
                            <a href="<?php echo esc_url($ast_visit_site_url); ?>" target="_blank" rel="noopener" >
                                <?php if ($ast_icon) { ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" class="cwy-theme-icon" alt="<?php echo esc_attr(self::$page_title); ?> " >
                                <?php } ?>
                                <?php do_action('colorway_welcome_page_header_title'); ?>
                            </a>
                        </div>

                        <?php do_action('colorway_header_right_section'); ?>

                    </div>
                </div>

                <?php do_action('colorway_menu_' . esc_attr($current_slug) . '_action'); ?>
            </div>
            <?php
        }

        /**
         * Include general page
         *
         * @since 1.0
         */
        static public function general_page() {
            require_once get_template_directory() . '/includes/colorway-admin-settings/view-general.php';
        }

        /**
         * Include Welcome page right starter sites content
         *
         * @since 1.2.4
         */
        static public function colorway_welcome_page_sites_section() {
            ?>

            <div class="postbox colorway-sites-box">
                <div class="box-content">
                    <h2 class="cwy-normal-cusror">
                        <span class="dashicons dashicons-admin-customizer"></span>
                        <span class="head-color"><?php echo esc_html(apply_filters('colorway_sites_menu_page_title', __('Import ColorWay Site', 'colorway'))); ?></span>
                    </h2>
                    <div class="inside">
                                                <p>
                            <?php
                            esc_html_e('In need of advanced customization?', 'colorway');
                            ?>
                        </p>
                        <p class="inside-desc">
                            <?php
                            $colorway_sites_doc_link = apply_filters('colorway_sites_documentation_link', esc_url('https://inkthemes.com/demos/', 'colorway-dashboard', 'how-colorway-sites-works', 'welcome-page'));
                            $colorway_sites_doc_link_text = apply_filters('colorway_sites_doc_link_text', __('ColorWay Site Templates', 'colorway'));
                            printf(
                                    
                                    /* translators: %1$s: ColorWay site link. */
                                    esc_html__('You can import your favorite %1$s site from the library of %2$s in just a couple of clicks.', 'colorway'), self::$page_title, !empty($colorway_sites_doc_link) ? '<a href=' . esc_url($colorway_sites_doc_link) . ' target="_blank" rel="noopener">' . esc_html($colorway_sites_doc_link_text) . '</a>' :
                                            esc_html($colorway_sites_doc_link_text)
                                   
                            );
                            ?>
                        </p>

                        <?php
                        // ColorWay Sites - Installed but Inactive.
                        // ColorWay Premium Sites - Inactive.
                        if (file_exists(WP_PLUGIN_DIR . '/colorway-sites/colorway-sites.php') && is_plugin_inactive('colorway-sites/colorway-sites.php') && is_plugin_inactive('colorway-pro-sites/colorway-pro-sites.php')) {

                            $class = 'button cwy-sites-inactive';
                            $button_text = __('Activate ColorWay Sites Plugin', 'colorway');
                            $data_slug = 'colorway-sites';
                            $data_init = '/colorway-sites/colorway-sites.php';

                            // ColorWay Sites - Not Installed.
                            // ColorWay Premium Sites - Inactive.
                        } elseif (!file_exists(WP_PLUGIN_DIR . '/colorway-sites/colorway-sites.php') && is_plugin_inactive('colorway-pro-sites/colorway-pro-sites.php')) {

                            $class = 'button cwy-sites-notinstalled';
                            $button_text = __('Install Colorway Sites Plugin', 'colorway');
                            $data_slug = 'colorway-sites';
                            $data_init = '/colorway-sites/colorway-sites.php';

                            // ColorWay Premium Sites - Active.
                        } elseif (is_plugin_active('colorway-pro-sites/colorway-pro-sites.php')) {
                            $class = 'active';
                            $button_text = __('See Library »', 'colorway');
                            $link = admin_url('themes.php?page=colorway-sites');
                        } else {
                            $class = 'active';
                            $button_text = __('See Library »', 'colorway');
                            $link = admin_url('themes.php?page=colorway-sites');
                        }

                        printf(
                                '<a class="%1$s" %2$s %3$s %4$s> %5$s </a>', esc_attr($class), isset($link) ? 'href="' . esc_url($link) . '"' : '', isset($data_slug) ? 'data-slug="' . esc_attr($data_slug) . '"' : '', isset($data_init) ? 'data-init="' . esc_attr($data_init) . '"' : '', esc_html($button_text)
                        );
                        ?>
                        <div>
                        </div>
                    </div>

                </div>
                <div class="colorway-box-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/small-thumb.jpg" alt="" title=""/>
                </div>
            </div>

            <?php
        }

        /**
         * Include Welcome page right side Five Star Support
         *
         * @since 1.2.4
         */
        static public function colorway_welcome_page_five_star_scetion() {
            ?>

            <div class="postbox">
                <h2 class="hndle cwy-normal-cusror">
                    <span class="dashicons dashicons-sos"></span>
                    <span><?php esc_html_e('Five Star Support', 'colorway'); ?></span>
                </h2>
                <div class="inside">
                    <p>
                        <?php
                        printf(
                                /* translators: %1$s: ColorWay Theme name. */
                                esc_html__('Got a question? Visit %1$s Support forum. We\'re happy to help!', 'colorway'), self::$page_title
                        );
                        ?>
                    </p>
                    <?php
                    $colorway_support_link = apply_filters('colorway_support_link', esc_url('https://wordpress.org/support/theme/colorway/', 'colorway-dashboard', 'submit-a-ticket', 'welcome-page'));
                    $colorway_support_link_text = apply_filters('colorway_support_link_text', __('Visit Forum »', 'colorway'));

                    printf(
                            /* translators: %1$s: ColorWay Knowledge doc link. */
                            '%1$s', !empty($colorway_support_link) ? '<a href=' . esc_url($colorway_support_link) . ' target="_blank" rel="noopener">' . esc_html($colorway_support_link_text) . '</a>' :
                                    esc_html($colorway_support_link_text)
                    );
                    ?>
                </div>
            </div>
            <?php
        }

        /**
         * Include Welcome page content
         *
         * @since 1.2.4
         */
        static public function colorway_welcome_page_content() {

            //$colorway_addon_tagline = apply_filters('colorway_addon_list_tagline', __('More Options Available with ColorWay Pro!', 'colorway'));

            // Quick settings.
//            $quick_settings = apply_filters(
//                    'colorway_quick_settings', array(
//                'logo-favicon' => array(
//                    'title' => __('Upload Logo', 'colorway'),
//                    'dashicon' => 'dashicons-format-image',
//                    'quick_url' => admin_url('customize.php?autofocus[control]=inkthemes_options[colorway_logo]'),
//                ),
//                'colors' => array(
//                    'title' => __('Set Colors and Background', 'colorway'),
//                    'dashicon' => 'dashicons-admin-customizer',
//                    'quick_url' => admin_url('customize.php?autofocus[panel]=color_section'),
//                ),
//                'typography' => array(
//                    'title' => __('Typography', 'colorway'),
//                    'dashicon' => 'dashicons-editor-textcolor',
//                    'quick_url' => admin_url('customize.php?autofocus[panel]=typography_section'),
//                ),
//                'layout' => array(
//                    'title' => __('Layout Options', 'colorway'),
//                    'dashicon' => 'dashicons-layout',
//                    'quick_url' => admin_url('customize.php?autofocus[panel]=layout_setting_panel'),
//                ),
//                'header' => array(
//                    'title' => __('Header Options', 'colorway'),
//                    'dashicon' => 'dashicons-align-center',
//                    'quick_url' => admin_url('customize.php?autofocus[section]=header_layout_section'),
//                ),
//                'blog-layout' => array(
//                    'title' => __('Blog Layouts', 'colorway'),
//                    'dashicon' => 'dashicons-welcome-write-blog',
//                    'quick_url' => admin_url('customize.php?autofocus[section]=blog_layout_section'),
//                ),
//                'footer' => array(
//                    'title' => __('Footer Settings', 'colorway'),
//                    'dashicon' => 'dashicons-admin-generic',
//                    'quick_url' => admin_url('customize.php?autofocus[section]=footer_layout_section'),
//                ),
//                'sidebars' => array(
//                    'title' => __('Footer Widget Options', 'colorway'),
//                    'dashicon' => 'dashicons-align-left',
//                    'quick_url' => admin_url('customize.php?autofocus[section]=footer_col_layout'),
//                ),
//                'home_settings' => array(
//                    'title' => __('Homepage Settings', 'colorway'),
//                    'dashicon' => 'dashicons-admin-generic',
//                    'quick_url' => admin_url('customize.php?autofocus[section]=static_front_page'),
//                ),
//                    )
//            );
            ?>
<div class="cwy-container-iframe">
<!--            <div class="postbox1 left">
                <h2 class="hndle cwy-normal-cusror"><span><?php esc_html_e('Links to Customizer Settings:', 'colorway'); ?></span></h2>
                <div class="cwy-quick-setting-section">
                    <?php
                    //if (!empty($quick_settings)) :
                        ?>
                        <div class="cwy-quick-links">
                            <ul class="cwy-flex">
                                <?php
                                //foreach ((array) $quick_settings as $key => $link) {
                                 //   echo '<li class=""><span class="dashicons ' . esc_attr($link['dashicon']) . '"></span><a class="cwy-quick-setting-title" href="' . esc_url($link['quick_url']) . '" target="_blank" rel="noopener">' . esc_html($link['title']) . '</a></li>';
                                //}
                                ?>
                            </ul>
                        </div>
                    <?php //endif; ?>
                </div>
            </div>-->
            <div class="postbox1 right">
                <h2 class="hndle cwy-normal-cusror"><span><?php esc_html_e('How it works? Watch below Video!', 'colorway'); ?></span></h2>
                <div class="cwy-video-section embed-container">
<iframe width="100%" height="auto" src="https://www.youtube.com/embed/8G8sZYNJGm0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>                </div>
            </div>
</div>

            <!-- Notice for Older version of ColorWay Addon -->
            <?php
            self::min_addon_version_message();
        }

        /**
         * Required Plugin Activate
         *
         * @since 1.2.4
         */
        static public function required_plugin_activate() {

            if (!current_user_can('install_plugins') || !isset($_POST['init']) || !sanitize_key(wp_unslash($_POST['init']))) {
                wp_send_json_error(
                        array(
                            'success' => false,
                            'message' => __('No plugin specified', 'colorway'),
                        )
                );
            }

            $plugin_init = ( isset($_POST['init']) ) ? esc_attr(wp_unslash($_POST['init'])) : '';

            $activate = activate_plugin($plugin_init, '', false, true);

            if (is_wp_error($activate)) {
                wp_send_json_error(
                        array(
                            'success' => false,
                            'message' => $activate->get_error_message(),
                        )
                );
            }

            wp_send_json_success(
                    array(
                        'success' => true,
                        'message' => __('Plugin Successfully Activated', 'colorway'),
                    )
            );
        }

        /**
         * Check compatible theme version.
         *
         * @since 1.2.4
         */
        static public function min_addon_version_message() {

            $colorway_global_options = get_option('colorway-settings');

            if (isset($colorway_global_options['colorway-addon-auto-version']) && defined('COLORWAY_EXT_VER')) {

                if (version_compare($colorway_global_options['colorway-addon-auto-version'], '1.2.1') < 0) {

                    // If addon is not updated & White Label for Addon is added then show the white labelewd pro name.
                    $colorway_addon_name = colorway_get_addon_name();
                    $update_colorway_addon_link = esc_url('https://inkthemes.com/?p=25258', 'colorway-dashboard', 'update-to-colorway-pro', 'welcome-page');
                    if (class_exists('Colorway_Ext_White_Label_Markup')) {
                        $plugin_data = Colorway_Ext_White_Label_Markup::$branding;
                        if (!empty($plugin_data['colorway-pro']['name'])) {
                            $update_colorway_addon_link = '';
                        }
                    }

                    $class = 'cwy-notice cwy-notice-error';
                    $message = sprintf(
                            /* translators: %1$1s: Addon Name, %2$2s: Minimum Required version of the ColorWay Addon */
                            __('Update to the latest version of %1$2s to make changes in settings below.', 'colorway'), (!empty($update_colorway_addon_link) ) ? '<a href=' . esc_url($update_colorway_addon_link) . ' target="_blank" rel="noopener">' . $colorway_addon_name . '</a>' : $colorway_addon_name
                    );

                    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
                }
            }
        }

        /**
         * ColorWay Header Right Section Links
         *
         * @since 1.2.4
         */
        static public function top_header_right_section() {

            $top_links = apply_filters(
                    'colorway_header_top_links', array(
                'colorway-theme-info' => array(
                    'title' => __('ColorWay theme offers a library of ready sites that can be imported for your website in just one click!', 'colorway'),
                ),
                    )
            );

            if (!empty($top_links)) {
                ?>
                <div class="cwy-top-links">
                    <ul>
                        <?php
                        foreach ((array) $top_links as $key => $info) {
                            /* translators: %1$s: Top Link URL wrapper, %2$s: Top Link URL, %3$s: Top Link URL target attribute */
                            printf(
                                    '<li><%1$s %2$s %3$s > %4$s </%1$s>', isset($info['url']) ? 'a' : 'span', isset($info['url']) ? 'href="' . esc_url($info['url']) . '"' : '', isset($info['url']) ? 'target="_blank" rel="noopener"' : '', esc_html($info['title'])
                            );
                        }
                        ?>
                    </ul>
                </div>
                <?php
            }
        }

    }

    new Colorway_Admin_Settings;
}
