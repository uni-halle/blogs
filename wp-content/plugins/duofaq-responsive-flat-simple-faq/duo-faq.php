<?php
/*
Plugin Name: duoFAQ - Responsive, Flat, Simple FAQ
Plugin URI: http://duogeek.com
Description: A responsive and lightweight FAQ (Frequently Asked Questions) plugin by duogeek
Version: 1.4.3
Author: duogeek
Author URI: http://duogeek.com
License: GPL v2 or later
*/

if ( ! defined( 'ABSPATH' ) ) wp_die( __( 'Sorry hackers! This is not your place!', 'df' ) );

if( ! defined( 'DUO_PLUGIN_URI' ) ) define( 'DUO_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

require 'duogeek/duogeek-panel.php';

if( ! defined( 'DF_VERSION' ) ) define( 'DF_VERSION', '1.0' );
if( ! defined( 'DF_PLUGIN_DIR' ) ) define( 'DF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if( ! defined( 'DF_FILES_DIR' ) ) define( 'DF_FILES_DIR', DF_PLUGIN_DIR . 'duo-faq-files' );
if( ! defined( 'DF_PLUGIN_URI' ) ) define( 'DF_PLUGIN_URI', plugins_url() );
if( ! defined( 'DF_FILES_URI' ) ) define( 'DF_FILES_URI', DF_PLUGIN_URI . '/duofaq-responsive-flat-simple-faq/duo-faq-files' );
if( ! defined( 'DF_CLASSES_DIR' ) ) define( 'DF_CLASSES_DIR', DF_FILES_DIR . '/classes' );
if( ! defined( 'DF_ADDONS_DIR' ) ) define( 'DF_ADDONS_DIR', DF_FILES_DIR . '/addons' );
if( ! defined( 'DF_INCLUDES_DIR' ) ) define( 'DF_INCLUDES_DIR', DF_FILES_DIR . '/includes' );


if( ! defined( 'DUO_FAQ_MENU_POSITION' ) ) define( 'DUO_FAQ_MENU_POSITION', '37' );
if( ! defined( 'FAQ_POST_TYPE_REWRITE_SLUG' ) ) define( 'FAQ_POST_TYPE_REWRITE_SLUG', 'faq' );

$jquery_themes = apply_filters( 'df_jqueryui_themes', array( "UI lightness", "UI darkness", "Smoothness", "Start", "Redmond", "Sunny", "Overcast", "Le Frog", "Flick", "Pepper Grinder", "Eggplant", "Dark Hive", "Cupertino", "South Street", "Blitzer", "Humanity", "Hot Sneaks", "Excite Bike", "Vader", "Dot Luv", "Mint Choc", "Black Tie", "Trontastic", "Swanky Purse" ) );

$custom_themes = apply_filters( 'df_custom_themes', array( 'alizerin', 'amethyst', 'asbestos', 'belize-hole', 'carrot', 'concrete', 'emerland', 'green-sea', 'midnight-blue', 'nephritis', 'orange', 'peter-river', 'pomegranate', 'pumpkin', 'sunflower', 'turquoise', 'wet-asphalt', 'wisteria' ) );



add_action( 'init','duo_faq_localization' );
function duo_faq_localization() {
    load_plugin_textdomain( 'df', FALSE, DF_PLUGIN_DIR . '/lang/' );
}

if( ! class_exists( 'DuoFAQ' ) ) {

    class DuoFAQ extends customPostType{

        private $post_type = array();
        private $pro_themes_dir, $pro_themes_url;
        private $pro_themes = array();

        public function __construct() {

            $this->post_type = array(
                'post_type'			 => 'faq',
                'name'               => _x( 'FAQs', 'post type general name', 'df' ),
                'singular_name'      => _x( 'FAQ', 'post type singular name', 'df' ),
                'menu_name'          => _x( 'FAQ', 'admin menu', 'df' ),
                'name_admin_bar'     => _x( 'FAQ', 'add new on admin bar', 'df' ),
                'add_new'            => _x( 'Add New', 'book', 'df' ),
                'add_new_item'       => __( 'Add New Question', 'df' ),
                'new_item'           => __( 'New Question', 'df' ),
                'edit_item'          => __( 'Edit Question', 'df' ),
                'view_item'          => __( 'View Question', 'df' ),
                'all_items'          => __( 'All Questions', 'df' ),
                'search_items'       => __( 'Search Questions', 'df' ),
                'parent_item_colon'  => __( 'Parent Questions:', 'df' ),
                'not_found'          => __( 'No Questions found.', 'df' ),
                'not_found_in_trash' => __( 'No Questions found in Trash.', 'df' ),
                'supports'			 => apply_filters( 'faq_post_type_supports', array( 'title', 'editor', 'author', 'excerpt' ) ),
                'rewrite'			 => FAQ_POST_TYPE_REWRITE_SLUG
            );

            $this->meta_boxes = array(
                array(
                    'id'            => 'faq_order_no',
                    'title'         => 'Order No',
                    'callback'      => array( $this, 'faq_meta_box_cb' ),
                    'post_type'     => $this->post_type['post_type'],
                    'context'       => 'side',
                    'priority'      => 'high'
                )
            );

            parent::__construct( $this->post_type );

            add_action( 'init', array( $this, 'register_faq_post_type' ) );
            add_action( 'init', array( $this, 'register_faq_taxonomies' ) );
            add_action( 'init', array( $this, 'plugin_init' ) );

            add_filter( 'duogeek_panel_pages', array( $this, 'duogeek_panel_pages_faq' ) );

            //Adding styles and scripts
            add_filter( 'front_scripts_styles', array( $this, 'user_faq_styles' ) );
            add_filter( 'admin_scripts_styles', array( $this, 'admin_faq_styles' ) );
            add_action( 'wp_footer', array( $this, 'df_custom_css' ) );
            add_filter( 'duogeek_submenu_pages', array( $this, 'duofaq_menu' ) );

            add_action( 'add_meta_boxes', array($this, 'register_faq_meta_boxes' ) );
            add_action( 'save_post_faq', array( $this, 'save_faq_meta' ) );

            //Adding custom columns in taxonomy
            add_action( "manage_edit-faq_categories_columns", array($this, 'posts_columns_id') );
            add_filter( "manage_edit-faq_categories_sortable_columns", array($this, 'posts_columns_id') );
            add_filter( "manage_faq_categories_custom_column", array($this, 'posts_custom_id_columns'), 10, 3 );
            //Adding meta field in taxonomy
            add_action( 'faq_categories_add_form_fields', array($this, 'faq_categories_add_new_meta_field') );
            add_action( 'faq_categories_edit_form_fields', array($this, 'faq_categories_edit_meta_field') );
            add_action( 'edited_faq_categories', array($this, 'save_faq_categories_custom_meta') );
            add_action( 'create_faq_categories', array($this, 'save_faq_categories_custom_meta') );

            add_shortcode( 'duo_faq', array($this, 'faq_shortcode') );
            add_filter( 'duo_panel_help', array( $this, 'duo_panel_help_cb' ) );
            register_activation_hook( __FILE__, array( $this, 'faq_plugin_activate' ) );
            add_action( 'admin_init', array( $this, 'faq_plugin_redirect' ) );
            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'faq_action_links' ) );
            add_action( 'admin_head', array( $this, 'faq_banner_plugin' ) );

        }


        public function faq_plugin_activate() {
            update_option( 'faq_plugin_do_activation_redirect', true );
        }


        public function faq_plugin_redirect() {
            if ( get_option( 'faq_plugin_do_activation_redirect', false ) ) {
                delete_option( 'faq_plugin_do_activation_redirect' );
                wp_redirect( admin_url( DUO_SETTINGS_PAGE ) );
            }
        }


        public function faq_action_links( $links ){
            $mylinks = array(
                '<a href="' . admin_url( 'admin.php?page=duofaq-settings' ) . '">FAQ Settings</a>',
            );
            return array_merge( $links, $mylinks );
        }


        public function plugin_init() {
            $upload_dir = wp_upload_dir();
            $this->pro_themes_dir = apply_filters( 'faq_pro_themes_dir',  $upload_dir['basedir'] . '/faq_pro_themes' );
            $this->pro_themes_url = apply_filters( 'faq_pro_themes_url',  $upload_dir['baseurl'] . '/faq_pro_themes' );

            if( is_dir( $this->pro_themes_dir . '/' ) ) {
                if ($handle = opendir($this->pro_themes_dir . '/')) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) == 'css') {

                            array_push($this->pro_themes, ucfirst( basename( $entry, ".css" ) ) );
                        }
                    }
                    closedir($handle);
                }
            }
            $this->pro_themes = apply_filters( 'faq_pro_themes_list', $this->pro_themes );
        }

        /*
		 * Calling register function from parent class
		 */
        public function register_faq_post_type() {
            $this->register_custom_post_type();
        }

        /**
         * Adding meta box for featured mark
         */
        public function register_faq_meta_boxes() {
            $this->add_custom_meta_boxes( $this->meta_boxes );
        }

        /**
         * Meta box callback
         */
        public function faq_meta_box_cb( $post ) {
            wp_nonce_field( 'faq_meta_box', 'faq_meta_box_nonce' );
            $value = get_post_meta( $post->ID, 'faq_order_no', true );
            ?>
            <label for="faq_order_no">
                <?php _e( 'Order No', 'sn' ); ?>
            </label>
            <input type="text" name="faq_order_no" id="faq_order_no" value="<?php echo isset( $value ) && $value != '' ? $value : '' ?>" />
            <?php
        }

        /**
         * Saving meta value
         */
        public function save_faq_meta( $post_id ) {
            if ( !isset( $_POST['faq_meta_box_nonce'] ) ) {
                return;
            }

            if ( !wp_verify_nonce( $_POST['faq_meta_box_nonce'], 'faq_meta_box' ) ) {
                return;
            }

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            if ( !isset( $_POST['faq_order_no'] ) ) $faq_order_no = 0;
            else $faq_order_no = sanitize_text_field( $_POST['faq_order_no'] );

            update_post_meta( $post_id, 'faq_order_no', $faq_order_no );
        }

        public function duogeek_panel_pages_faq( $arr ){
            $arr[] = 'duofaq-settings';
            return $arr;
        }

        /*
		 * Calling register taxonomy function from parent class
		 */
        public function register_faq_taxonomies() {
            $taxes = array(
                array(
                    'tax_name'			=> 'faq_categories',
                    'name'              => _x( 'Categories', 'taxonomy general name', 'df' ),
                    'singular_name'     => _x( 'Category', 'taxonomy singular name', 'df' ),
                    'search_items'      => __( 'Search Categories', 'df' ),
                    'all_items'         => __( 'All Categories', 'df' ),
                    'parent_item'       => __( 'Parent Category', 'df' ),
                    'parent_item_colon' => __( 'Parent Category:', 'df' ),
                    'edit_item'         => __( 'Edit Category', 'df' ),
                    'update_item'       => __( 'Update Category', 'df' ),
                    'add_new_item'      => __( 'Add New Category', 'df' ),
                    'new_item_name'     => __( 'New Category Name', 'df' ),
                    'menu_name'         => __( 'Categories', 'df' ),
                    'rewrite'			=> apply_filters( 'faq_category_cat_rewrite_term', 'faq_categories' ),
                    'hierarchical'		=> true
                )
            );

            foreach( $taxes as $tax ){
                $this->set_tax( $tax );
                $this->register_custom_taxonomies();
            }
        }

        /**
         * Adding meta box for custom category taxonomy
         */
        public function faq_categories_add_new_meta_field() {
            ?>
            <div class="form-field">
                <label for="order_no"><?php _e( 'Category Order No', 'sn' ); ?></label>
                <input type="text" name="order_no" id="order_no" value="">
                <p class="description"><?php _e( 'Enter a value for this field','sn' ); ?></p>
            </div>
            <?php
        }

        /**
         * Edit meta box for custom catgory taxonomy
         */
        public function faq_categories_edit_meta_field($term) {
            $t_id = $term->term_id;
            $order_no = get_option( "faq_categories_order_$t_id" );
            ?>
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="order_no"><?php _e( 'Category Order No', 'sn' ); ?></label>
                </th>
                <td>
                    <input type="text" name="order_no" id="order_no" value="<?php echo esc_attr( $order_no ) ? esc_attr( $order_no ) : ''; ?>">
                    <p class="description"><?php _e( 'Enter a value for this field','sn' ); ?></p>
                </td>
            </tr>
            <?php
        }

        /**
         * Save meta for custom category taxonomy
         */
        public function save_faq_categories_custom_meta( $term_id ) {
            if ( isset( $_POST['order_no'] ) ) {
                $t_id = $term_id;
                $term_meta = get_option( "faq_categories_order_$t_id" );
                update_option( "faq_categories_order_$t_id", $_POST['order_no'] );
            }
        }


        public function user_faq_styles( $enq ) {

            global $jquery_themes, $custom_themes;
            $df_options = get_option( 'df_options' );

            $scripts = array(
                array(
                    'name' => 'accordion_js',
                    'src' => DF_FILES_URI . '/inc/js/accordion.js',
                    'dep' => array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion' ),
                    'version' => DUO_VERSION,
                    'footer' => true,
                    'condition' => true,
                    'localize' => true,
                    'localize_data' => array(
                        'object' => 'faq_obj',
                        'passed_data' => array( 'collapse' => isset( $df_options['collapse'] ) ? $df_options['collapse'] : 0 )
                    )
                )
            );

            $df_options['theme'] = isset( $df_options['theme'] ) && $df_options['theme'] != '' ? $df_options['theme'] : 'orange';
            if( file_exists( $this->pro_themes_dir . '/' . strtolower( $df_options['theme'] ) . '.css' ) ){
                $theme_style = array(
                    'name' => 'ui_accordion_css',
                    'src' => $this->pro_themes_url . '/' . strtolower( $df_options['theme'] ) . '.css',
                    'dep' => '',
                    'version' => DUO_VERSION,
                    'media' => 'all',
                    'condition' => true
                );
            }
            elseif( in_array( $df_options['theme'], $jquery_themes ) ){
                $theme_style = array(
                    'name' => 'ui_accordion_css',
                    'src' => '//code.jquery.com/ui/1.11.2/themes/' . strtolower( str_replace( ' ', '-', $df_options['theme'] ) ) . '/jquery-ui.css',
                    'dep' => '',
                    'version' => DUO_VERSION,
                    'media' => 'all',
                    'condition' => true
                );
            }else{
                $theme_style = array(
                    'name' => 'ui_accordion_css',
                    'src' => DF_FILES_URI .'/inc/css/' . $df_options['theme'] . '.css',
                    'dep' => '',
                    'version' => DUO_VERSION,
                    'media' => 'all',
                    'condition' => true
                );
            }

            $styles = array(
                $theme_style,
                array(
                    'name' => 'accordion_css',
                    'src' => DF_FILES_URI .'/inc/css/faqs.css',
                    'dep' => '',
                    'version' => DUO_VERSION,
                    'media' => 'all',
                    'condition' => true
                )
            );

            if( ! isset( $enq['scripts'] ) || ! is_array( $enq['scripts'] ) ) $enq['scripts'] = array();
            if( ! isset( $enq['styles'] ) || ! is_array( $enq['styles'] ) ) $enq['styles'] = array();
            $enq['scripts'] = array_merge( $enq['scripts'], $scripts );
            $enq['styles'] = array_merge( $enq['styles'], $styles );

            return $enq;
        }

        public function admin_faq_styles( $enq ) {
            $styles = array(
                array(
                    'name' => 'faq_admin_css',
                    'src' => DF_FILES_URI .'/inc/css/faq-admin.css',
                    'dep' => '',
                    'version' => DUO_VERSION,
                    'media' => 'all',
                    'condition' => true
                )
            );

            if( ! isset( $enq['scripts'] ) || ! is_array( $enq['scripts'] ) ) $enq['scripts'] = array();
            if( ! isset( $enq['styles'] ) || ! is_array( $enq['styles'] ) ) $enq['styles'] = array();
            $enq['styles'] = array_merge( $enq['styles'], $styles );

            return $enq;
        }


        public function df_custom_css() {
            $df_options = get_option( 'df_options' );
            ?>
            <style>
                .faq_wrap_all h3{font-size: <?php echo $df_options['qfont'] ?>px !important; }
                .faq_wrap_all h4{font-size: <?php echo $df_options['cfont'] ?>px !important; }
                <?php echo stripslashes( $df_options['custom_css'] ) ?>
            </style>
        <?php
        }


        /*
         * Adding column in taxonomy
         */
        public function posts_columns_id($columns) {
            $columns['tax_id'] = 'ID';
            $columns['order_no'] = 'Order No';
            return $columns;
        }

        public function posts_custom_id_columns($v, $name, $id) {
            $order_no = get_option( "faq_categories_order_$id" );
            switch( $name ) {
                case 'tax_id':
                    return $id;
                    break;
                case 'order_no':
                    return $order_no;
                    break;
                default:
                    break;
            }
        }

        public function cmp($a, $b){
            if( property_exists($a, 'order_no') ) {
                return (int)$a->order_no - (int)$b->order_no;
            } else {
                return;
            }
        }

        public function cmp_post($a, $b){
            if( property_exists($a, 'order_no') ) {
                return (int)$a->faq_order_no - (int)$b->faq_order_no;
            } else {
                return;
            }
        }

        public function faq_shortcode( $atts ){
            extract( shortcode_atts( array(
                'category' => '',
                'title' => __( 'Frequently Asked Questions', 'df' )
            ), $atts ) );

            $html = '';

            if($category != '')
            {
                if( strpos( $category, "," ) > 0 ){
                    $cats = explode( ',', $category );
                    $cats = array_map( 'trim', $cats );
                    $cat = array();
                    foreach( $cats as $v ){
                        $term = get_term( $v, 'faq_categories' );
                        $term->order_no = get_option( "faq_categories_order_$v" );
                        array_push( $cat, $term );
                    }
                    usort($cat, array($this, 'cmp'));
                    include DF_FILES_DIR . '/templates/all_view.php';
                }else{
                    $cat = get_term( $category, 'faq_categories' );
                    include DF_FILES_DIR . '/templates/category_view.php';
                }
            }
            else
            {
                $cats = get_terms('faq_categories');
                $cat = array();
                foreach( $cats as $value ){
                    $id = $value->term_id;
                    $value->order_no = get_option( "faq_categories_order_$id" );
                    array_push( $cat, $value );
                }
                usort($cat, array($this, 'cmp'));
                //var_dump($cat);
                include DF_FILES_DIR . '/templates/all_view.php';
            }

            return $html;
        }

        public function duofaq_menu( $submenus ) {
            $submenus[] = array(
                'title' => __( 'FAQ Settings', 'df' ),
                'menu_title' => __( 'FAQ Settings', 'df' ),
                'capability' => 'manage_options',
                'slug' => 'duofaq-settings',
                'object' => $this,
                'function' => 'duofaq_settings_page'
            );

            return $submenus;
        }

        public function duofaq_settings_page() {
            global $jquery_themes, $custom_themes;

            if( isset( $_POST['df_save'] ) ){
                if ( ! check_admin_referer( 'df_nonce_action', 'df_nonce_field' )){
                    return;
                }

                update_option( 'df_options', $_POST['df'] );

                wp_redirect( admin_url( 'admin.php?page=duofaq-settings&msg=Settings+saved+successfully.' ) );
            }

            if( isset( $_POST['upload_theme'] ) ){

                if( ! isset( $_FILES['faq_pro_theme'] ) || $_FILES['faq_pro_theme']['name'] == '' ){
                    wp_redirect(admin_url('admin.php?page=duofaq-settings&msg=' . __('Please+select+a+file.', 'src')));
                }else{
                    if( ! is_dir( $this->pro_themes_dir ) ){
                        mkdir( $this->pro_themes_dir );
                    }

                    $target_dir = $this->pro_themes_dir. '/';
                    $target_file = $target_dir . basename($_FILES["faq_pro_theme"]["name"]);

                    if (move_uploaded_file($_FILES["faq_pro_theme"]["tmp_name"], $target_file)) {
                        $zip = new ZipArchive;
                        $zip->open( $target_file );
                        $zip->extractTo( $target_dir );
                        $zip->close();
                        unlink( $target_file );
                        wp_redirect(admin_url('admin.php?page=duofaq-settings&msg=' . __('The+template+is+uploaded.', 'src')));
                    } else {
                        wp_redirect(admin_url('admin.php?page=duofaq-settings&msg=' . __('There+is+an+issue+with+the+upload+process.+Please+contact+support.', 'src')));
                    }

                }

            }

            $df_options = get_option( 'df_options' );

            ?>

            <div class="wrap duo_prod_panel">
                <h2><?php _e( 'DuoFAQ Settings' ) ?></h2>
                <a style="outline: none" href="//duogeek.com/products/add-ons/" target="_blnak"><img style="width: 100%;" src="<?php echo DF_FILES_URI . '/inc/img/duofaq-plugin-settings-banner.png' ?>"></a>
                <?php if( isset( $_REQUEST['msg'] ) ) { ?>
                    <div id="message" class="<?php echo isset( $_REQUEST['duoaction'] ) ? $_REQUEST['duoaction'] : 'updated' ?> below-h2"><p><?php echo str_replace( '+', ' ', $_REQUEST['msg'] ) ?></p></div>
                <?php } ?>

                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <div class="postbox">
                                <h3 class="hndle"><?php _e( 'General Settings', 'df' ) ?></h3>
                                <div class="inside">
                                    <form action="<?php echo admin_url( 'admin.php?page=duofaq-settings&noheader=true' ) ?>" method="post">
                                        <?php wp_nonce_field('df_nonce_action','df_nonce_field'); ?>
                                        <table class="form-table">
                                            <tr>
                                                <th><?php _e( 'Choose a theme:', 'df' ) ?></th>
                                                <td>
                                                    <select name="df[theme]">
                                                        <option value=""><?php _e( 'Select a theme', 'df' ) ?></option>

                                                        <?php if( count( $this->pro_themes ) > 0 ) { ?>
                                                            <optgroup label="Premium Themes">
                                                                <?php foreach( $this->pro_themes as $theme ){ ?>
                                                                    <option <?php echo isset( $df_options['theme'] ) && $df_options['theme'] == $theme ? 'selected' : ''; ?> value="<?php echo $theme ?>"><?php echo $theme ?></option>
                                                                <?php } ?>
                                                            </optgroup>
                                                        <?php } ?>

                                                        <optgroup label="jQuery UI Themes">
                                                            <?php foreach( $jquery_themes as $theme ){ ?>
                                                                <option <?php echo isset( $df_options['theme'] ) && $df_options['theme'] == $theme ? 'selected' : ''; ?> value="<?php echo $theme ?>"><?php echo $theme ?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                        <optgroup label="Custom Themes">
                                                            <?php foreach( $custom_themes as $theme ){ ?>
                                                                <option <?php echo isset( $df_options['theme'] ) && $df_options['theme'] == $theme ? 'selected' : ''; ?> value="<?php echo $theme ?>"><?php echo ucfirst( str_replace( '-', ' ', $theme ) ) ?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php _e( 'Collapse all by default', 'df' ) ?></th>
                                                <td>
                                                    <label>
                                                        <input <?php echo ( isset( $df_options['collapse'] ) && $df_options['collapse'] == 0 ) || ! isset( $df_options['collapse'] ) ? 'checked' : ''; ?> type="radio" name="df[collapse]" value="0"> No
                                                    </label>
                                                    <label>
                                                        <input <?php echo isset( $df_options['collapse'] ) && $df_options['collapse'] == 1 ? 'checked' : ''; ?> type="radio" name="df[collapse]" value="1"> Yes
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php _e( 'Font size of question?', 'df' ) ?></th>
                                                <td>
                                                    <input type="text" name="df[qfont]" class="wide small_box" value="<?php echo isset( $df_options['qfont'] )  ? $df_options['qfont'] : 14; ?>"> px
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php _e( 'Font size of category label?', 'df' ) ?></th>
                                                <td>
                                                    <input type="text" name="df[cfont]" class="wide small_box" value="<?php echo isset( $df_options['cfont'] )  ? $df_options['cfont'] : 14; ?>"> px
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php _e( 'Custom CSS:', 'df' ) ?></th>
                                                <td>
                                                    <textarea name="df[custom_css]" rows="10" cols="70"><?php echo isset( $df_options['custom_css'] ) && $df_options['custom_css'] != '' ? stripslashes( $df_options['custom_css'] ) : ''; ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <p><input type="submit" name="df_save" class="button button-primary" value="<?php _e( 'Save Settings', 'df' ) ?>" /></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div class="postbox">
                                <h3 class="hndle"><span><?php _e( 'Upload New Theme', 'src' ) ?></span></h3>
                                <div class="inside">
                                    <form action="<?php echo admin_url( 'admin.php?page=duofaq-settings&noheader=true' ) ?>" method="post" enctype="multipart/form-data">
                                        <table class="form-table">
                                            <tr>
                                                <th valign="top"><?php _e( 'Upload zip file of new theme', 'src' ) ?></th>
                                                <td valign="top">
                                                    <input type="file" name="faq_pro_theme">
                                                </td>
                                            </tr>
                                        </table>
                                        <p>
                                            <input name="upload_theme" type="submit" class="button button-primary" value="<?php _e( 'Upload', 'src' ) ?>">
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="postbox-container" id="postbox-container-1">
                            <?php do_action( 'dg_settings_sidebar', 'free', 'faq-free' ); ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }

        public function duo_panel_help_cb( $arr ){
            $arr[] = array(
                'name'          => __( 'DuoFAQ' ),
                'shortcodes'    => array(
                    array(
                        'source'			=> __( 'Duo FAQ PLugin', 'sn' ),
                        'code'              => '[duo_faq]',
                        'example'           => '[duo_faq category="CATEGORY ID" title="ANY TITLE"], [duo_faq category="CATEGORY ID 1, CATEGORY ID 2" title="ANY TITLE"]',
                        'default'           => 'category = all, title = "Frequently Asked Questions"',
                        'desc'              => __( 'This shortcode will show the FAQ items. If you provide category ID, then only FAQs from that category will be shown. Otherwise all FAQs will be shown. To show some categories (not all) use like [duo_faq category="23, 44, 35" title="ANY TITLE"]' , 'sn' )
                    ),
                )
            );

            return $arr;
        }


        public function faq_banner_plugin() {
            ?>
            <script type="text/javascript">
                jQuery(function($){
                    if($('#duofaq-responsive-flat-simple-faq').length){
                        var html = '<tr>';
                            html += '<td colspan="3" style="padding: 0">';
                            html += '<a href="//duogeek.com/products/add-ons/" target="_blnak"><img style="width: 100%;" src="<?php echo DF_FILES_URI . '/inc/img/duofaq-plugin-list-banner.png' ?>"></a>';
                            html += '</td>';
                            html += '</tr>';
                        $('#duofaq-responsive-flat-simple-faq').after(html);
                    }
                });
            </script>
            <?php
        }

    }

    new DuoFAQ();

    require DF_FILES_DIR . '/classes/class.widget.php';

}