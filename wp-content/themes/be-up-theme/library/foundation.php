<?php
/**
 * Foundation PHP template
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

// Pagination.
if ( ! function_exists( 'foundationpress_pagination' ) ) :
	function foundationpress_pagination() {
		global $wp_query;

		$big = 999999999; // This needs to be an unlikely integer

		// For more options and info view the docs for paginate_links()
		// http://codex.wordpress.org/Function_Reference/paginate_links
		$paginate_links = paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', html_entity_decode( get_pagenum_link( $big ) ) ),
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $wp_query->max_num_pages,
				'mid_size'  => 5,
				'prev_next' => true,
				'prev_text' => __( '&laquo;', 'foundationpress' ),
				'next_text' => __( '&raquo;', 'foundationpress' ),
				'type'      => 'list',
			)
		);

		$paginate_links = str_replace( "<ul class='page-numbers'>", "<ul class='pagination text-center' role='navigation' aria-label='Pagination'>", $paginate_links );
		$paginate_links = str_replace( '<li><span class="page-numbers dots">', "<li><a href='#'>", $paginate_links );
		$paginate_links = str_replace( '</span>', '</a>', $paginate_links );
		$paginate_links = str_replace( "<li><span class='page-numbers current'>", "<li class='current'>", $paginate_links );
		$paginate_links = str_replace( "<li><a href='#'>&hellip;</a></li>", "<li><span class='dots'>&hellip;</span></li>", $paginate_links );
		$paginate_links = preg_replace( '/\s*page-numbers/', '', $paginate_links );

		// Display the pagination if more than one page is found.
		if ( $paginate_links ) {
			echo $paginate_links;
		}
	}
endif;

/**
 * A fallback when no navigation is selected by default.
 */

if ( ! function_exists( 'foundationpress_menu_fallback' ) ) :
	function foundationpress_menu_fallback() {
		echo '<div class="alert-box secondary">';
		/* translators: %1$s: link to menus, %2$s: link to customize. */
		printf(
			__( 'Please assign a menu to the primary menu location under %1$s or %2$s the design.', 'foundationpress' ),
			/* translators: %s: menu url */
			sprintf(
				__( '<a href="%s">Menus</a>', 'foundationpress' ),
				get_admin_url( get_current_blog_id(), 'nav-menus.php' )
			),
			/* translators: %s: customize url */
			sprintf(
				__( '<a href="%s">Customize</a>', 'foundationpress' ),
				get_admin_url( get_current_blog_id(), 'customize.php' )
			)
		);
		echo '</div>';
	}
endif;

// Add Foundation 'is-active' class for the current menu item.
if ( ! function_exists( 'foundationpress_active_nav_class' ) ) :
	function foundationpress_active_nav_class( $classes, $item ) {
		if ( $item->current == 1 || $item->current_item_ancestor == true ) {
			$classes[] = 'is-active';
		}
		return $classes;
	}
	add_filter( 'nav_menu_css_class', 'foundationpress_active_nav_class', 10, 2 );
endif;

/**
 * Use the is-active class of ZURB Foundation on wp_list_pages output.
 * From required+ Foundation http://themes.required.ch.
 */
if ( ! function_exists( 'foundationpress_active_list_pages_class' ) ) :
	function foundationpress_active_list_pages_class( $input ) {

		$pattern = '/current_page_item/';
		$replace = 'current_page_item is-active';

		$output = preg_replace( $pattern, $replace, $input );

		return $output;
	}
	add_filter( 'wp_list_pages', 'foundationpress_active_list_pages_class', 10, 2 );
endif;

/**
 * Enable Foundation responsive embeds for WP video embeds
 */

if ( ! function_exists( 'foundationpress_responsive_video_oembed_html' ) ) :
	function foundationpress_responsive_video_oembed_html( $html, $url, $attr, $post_id ) {

		// Whitelist of oEmbed compatible sites that **ONLY** support video.
		// Cannot determine if embed is a video or not from sites that
		// support multiple embed types such as Facebook.
		// Official list can be found here https://codex.wordpress.org/Embeds
		$video_sites = array(
			'youtube', // first for performance
			'collegehumor',
			'dailymotion',
			'funnyordie',
			'ted',
			'videopress',
			'vimeo',
		);

		$is_video = false;

		// Determine if embed is a video
		foreach ( $video_sites as $site ) {
			// Match on `$html` instead of `$url` because of
			// shortened URLs like `youtu.be` will be missed
			if ( strpos( $html, $site ) ) {
				$is_video = true;
				break;
			}
		}

		// Process video embed
		if ( true == $is_video ) {

			// Find the `<iframe>`
			$doc = new DOMDocument();
			$doc->loadHTML( $html );
			$tags = $doc->getElementsByTagName( 'iframe' );

			// Get width and height attributes
			foreach ( $tags as $tag ) {
				$width  = $tag->getAttribute( 'width' );
				$height = $tag->getAttribute( 'height' );
				break; // should only be one
			}

			$class = 'responsive-embed'; // Foundation class

			// Determine if aspect ratio is 16:9 or wider
			if ( is_numeric( $width ) && is_numeric( $height ) && ( $width / $height >= 1.7 ) ) {
				$class .= ' widescreen'; // space needed
			}

			// Wrap oEmbed markup in Foundation responsive embed
			return '<div class="' . $class . '">' . $html . '</div>';

		} else { // not a supported embed
			return $html;
		}

	}
	add_filter( 'embed_oembed_html', 'foundationpress_responsive_video_oembed_html', 10, 4 );
endif;

/**
 * Get mobile menu ID
 */

if ( ! function_exists( 'foundationpress_mobile_menu_id' ) ) :
	function foundationpress_mobile_menu_id() {
		if ( get_theme_mod( 'wpt_mobile_menu_layout' ) === 'offcanvas' ) {
			echo 'off-canvas-menu';
		} else {
			echo 'mobile-menu';
		}
	}
endif;

/**
 * Get title bar responsive toggle attribute
 */

if ( ! function_exists( 'foundationpress_title_bar_responsive_toggle' ) ) :
	function foundationpress_title_bar_responsive_toggle() {
		if ( ! get_theme_mod( 'wpt_mobile_menu_layout' ) || get_theme_mod( 'wpt_mobile_menu_layout' ) === 'topbar' ) {
			echo 'data-responsive-toggle="mobile-menu"';
		}
	}
endif;


if( !function_exists('id_fix_shortcodes') ) {
	function id_fix_shortcodes($content){
		$array = array (
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']'
		);
		$content = strtr($content, $array);
		return $content;
	}
	add_filter('the_content', 'id_fix_shortcodes');
}

function menu_function($atts, $content = null) {
	extract(
		shortcode_atts(
			array( 'name' => null, ),
			$atts
		)
	);
	return wp_nav_menu(
		array(
			'menu' => $name,
			'echo' => false
		)
	);
}
add_shortcode('menu', 'menu_function');


function my_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form class="secret" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    ' . __( "Der Zugang zu dieser Seite ist Passwortgeschützt." ) . '
    <label for="' . $label . '">' . __( "Password:" ) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" /><input type="submit" name="Submit" value="' . esc_attr__( "Absenden" ) . '" />
    </form>
    ';
    return $o;
}
add_filter( 'the_password_form', 'my_password_form' );


function showCatentries($atts){
	extract(shortcode_atts(array(
	"catid" => '1',
	"items" => '1'
	), $atts));
	//$temp_query = $wp_query;
	$catid = $atts['catid'];
	if (!empty($catid)) {
		$catidout = '&cat='.$catid;
	}
	else {
		$catidout = '0';
	}
	$items = $atts['items'];
	//echo $catid;
	$my_query = new WP_Query( 'showposts='.$items.''.$catidout );

	ob_start();
	while ( $my_query->have_posts() ) : $my_query->the_post();
		$categories = get_the_category();

		if ( ! empty( $categories ) ) {
			// echo '<div class="catpost-title" style="margin-top: 1rem;">'.esc_html( $categories[0]->name ).'</div>';
			echo '<div class="catpost-title" style="margin-top: 1rem;">
				<p style="font-size: 22px; color: #FFF; line-height: 20px; margin-bottom: 0px; padding-top: 8px;">'.esc_html( $categories[0]->name ).'</p>
				<p style="font-size: 18px; color: #FFEFDE; margin-bottom: 0px;">'.esc_html( get_the_date('d.m.Y') ).' | '.esc_html( get_the_author()).'</p>
			</div>';
		}
		echo '<div class="entry-content be-gradient-2-invers spacer-v-4" style="padding:1rem; margin-bottom:1rem;">';
			echo '<div class="catpost" id="post-'.get_the_ID().'">';
				echo '<a href="'.get_the_permalink().'" rel="bookmark" title="'.get_the_title().'">';
					echo '<h3>';

					echo get_the_title();

					echo '</h3>';
				echo '</a>';

					if (has_post_thumbnail()) {
					echo '<div class="threadimg">';
					the_post_thumbnail('medium');
					echo '</div>';
					};
					echo get_the_excerpt();
				echo '</div>';
			echo '</div>';
	endwhile;
	$contentt = ob_get_clean();
	return $contentt;
	wp_reset_postdata();
	wp_reset_query();
}
add_shortcode("showCatentries", "showCatentries");




function custom_password_cookie_expiry( $expires ) {
    return time() + 1800;  // Make it a session cookie
}
add_filter( 'post_password_expires', 'custom_password_cookie_expiry' );



//Password Logout Cookie

if ( !defined( 'PPLB_TEMPLATE_PATH' ) ) {
	define( 'PPLB_TEMPLATE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) . 'templates/' );
}

if ( !class_exists( 'PPLB_Plugin' ) ) {
	class PPLB_Plugin {

		function __construct(){
			add_action('init', array( $this, 'add_pplb_filter' ), 10, 1);						// add the filter on load.

			add_action('admin_menu', array( $this, 'pplb_add_admin' ) );
			add_action('wp_enqueue_scripts', array( $this, 'pplb_logout_js' ) ); 					// adds the script to the header.
			add_action('wp_ajax_nopriv_pplb_logout', array( $this, 'pplb_protected_logout' ) ); 	// logout for non-logged in wp users
			add_action('wp_ajax_pplb_logout', array( $this, 'pplb_protected_logout' ) ); 		// logout for logged in wp users

			add_shortcode('logout_btn', array( $this, 'pplb_logout_button' ) );					// adds the shortcode.

			add_filter( 'post_password_expires', array( $this, 'pplb_change_postpass_expires' ), 10, 1 );

			add_action( 'admin_init',  array( $this, 'pplb_options_save' ) );
		}

		/*
			Add the logout button to posts which require a password and the password has been provided.
		*/

		function pplb_logout_filter( $content ){
			global $post;
			$html = '';

			//Check if the post has a password and we are inside the loop.
			if ( !empty( $post->post_password) && in_the_loop() ){
				//Check to see if the password has been provided.
				if ( !post_password_required( get_the_ID() ) ) {
					//add the logout button to the output.
					$options = get_option('pplb_options');
					$class = ( array_key_exists('pplb_button_class', $options) ) ? $options['pplb_button_class'] : '';
					$text = (array_key_exists('pplb_button_text', $options)) ? $options['pplb_button_text'] : 'logout';
					if ( empty( $text ) ) {
						$text = 'Logout';
					}
					// $html .= ' <input type="button" id="logout" class="button logout '.esc_attr($class).'" value="'.esc_attr( $text ).'">';
					$html .= '';
				}
			}
			return $html.$content;

		}

		/*
			Adds for use in wordpress shortcode or php.
		*/

		function pplb_logout_button(){
			$qid = get_queried_object_id();
			$qpost = get_post($qid);
			$html = '';
			// Check if the post has a password
			if ( !empty( $qpost->post_password ) ) {
				// Check to see if the password has been provided.
				if(!post_password_required($qid)){
					$options = get_option('pplb_options');
					$class = (array_key_exists('pplb_button_class', $options)) ? $options['pplb_button_class'] : '';
					$text = (array_key_exists('pplb_button_text', $options)) ? $options['pplb_button_text'] : 'logout';
					if ( empty( $text ) ) {
						$text = 'logout';
					}
					$html = ' <input type="button" class="button logout '.esc_attr($class).'" value="'.esc_attr( $text ).'">';
				}
			}
			return $html;

		}

		/*
			Ajax function to reset the cookie in wordpress.
		*/

		function pplb_protected_logout(){
			// Set the cookie to expire ten days ago... instantly logged out.
			setcookie( 'wp-postpass_' . COOKIEHASH, stripslashes( '' ), time() - 864000, COOKIEPATH, COOKIE_DOMAIN );
			// setcookie( 'wp-postpass-dang', '', time() + 864000 );
			// $mycookie = setcookie( 'wp-postpass_bad' . COOKIEHASH, stripslashes( '' ), time() - 864000, COOKIEPATH );

			// unset( $_COOKIE[$wp-postpass] );
			// setcookie( $v_username, '', time() - ( 15 * 60 ) );

			$options = get_option('pplb_options');
			$pplb_alert = (array_key_exists('pplb_alert', $options)) ? $options['pplb_alert'] : 'no';
			$log = isset( $options['pplb_debug'] ) ? $options['pplb_debug'] : 0;

			$response = array(
				'status' 	=> 0,
				'message' 	=> '',
				'log'           => $log
			);

			if ( $pplb_alert == 'yes' ) {
				$response['status'] = 1;
				$response['message'] = stripslashes( $options['pplb_message'] );
			}
			else {
				$response['status'] = 0;
				$response['message'] = '';
			}
			wp_send_json( $response );
		}

		/*
			Enqueue the scripts.
		*/
		function pplb_logout_js(){
			// wp_register_script( 'pplb_logout_js', plugins_url( '/logout.js', __FILE__ ), array('jquery'), null, true );
			wp_register_script( 'pplb_logout_js', get_template_directory_uri().'/assets/js/logout.js', array('jquery'), null, true );
			wp_enqueue_script( 'pplb_logout_js' );
			wp_localize_script( 'pplb_logout_js', 'pplb_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}

		/*
			Filter the expiration time if necessary based upon the option.
		*/
		function pplb_change_postpass_expires( $expire ){
			$new_expire = get_option( 'pplb_pass_expires', false );
			if ( $new_expire !== false && is_numeric( $new_expire ) ) {
				return time() + $new_expire;
			}
			else {
				return $expire;
			}
		}

		/*
		        Save on admin init
		*/
		function pplb_options_save(){
		        if ( isset( $_POST['pplb_action'] ) ) {
				//update the option.
				$options = array();
				$options['pplb_alert'] = ( array_key_exists('pplb_alert', $_POST) ) ? $_POST['pplb_alert']: 'no';
				$options['pplb_message'] = esc_js( $_POST['pplb_message'] );
				$options['pplb_debug'] = ( array_key_exists('pplb_debug', $_POST) ) ? $_POST['pplb_debug']: 0;
				$options['pplb_button_class'] = esc_attr($_POST['pplb_button_class']);
				$options['pplb_button_text'] = !empty($_POST['pplb_button_text']) ? esc_attr($_POST['pplb_button_text']) : 'logout';


				update_option('pplb_options', $options);

				$expire = ( isset( $_POST['pplb_pass_expires'] ) && !empty( $_POST['pplb_pass_expires'] ) ) ? $_POST['pplb_pass_expires']: false;
				update_option('pplb_pass_expires', $expire );

				$filter = isset($_POST['pplb_button_filter']) ? $_POST['pplb_button_filter']: 'yes';
				update_option('pplb_button_filter', $filter);
				$redirect = add_query_arg( array( 'message' => 1 ) );
				wp_redirect( $redirect );
				exit();
			}
		}
		/*
			The settings page in admin
		*/
		function pplb_settings_page(){
			include ( PPLB_TEMPLATE_PATH . 'pplb-options.php' );
		}

		/*
			Add the admin page
		*/
		function pplb_add_admin(){
			add_options_page('Protected Post Logout Settings', 'Protected Post Logout', 'manage_options', 'pplb-settings-page', array( $this, 'pplb_settings_page' ) );
		}

		/*
			Activation hook to install the options if they haven't been installed before.
		*/
		function install_pplb_options(){
			if ( !get_option('pplb_options') ) {
				$options = array(
					'pplb_alert' => 'no',
					'pplb_message' => 'Successfully logged out.',
					'pplb_button_class' => ''
				);
				update_option('pplb_options', $options);
			}
			if ( !get_option('pplb_button_filter') ) {
				update_option('pplb_button_filter', 'yes');
			}
		}

		/*
			Only add the filter if the option declares it
		*/
		function add_pplb_filter(){
			if ( !get_option( 'pplb_button_filter' ) ) {
				// if the option isn't set, assume we want it there.
				update_option('pplb_button_filter', 'yes');
			}
			$add_filter = get_option('pplb_button_filter');
			if ( $add_filter == 'yes' ) {
				add_filter('the_content', array( $this, 'pplb_logout_filter' ), 9999, 1); 	// adds the button.
			}
		}
	}
}

function pplb_logout_button() {
	return do_shortcode( '[logout_btn]' );
}

function init_pplb(){
	new PPLB_Plugin();
}

add_action( 'init', 'init_pplb', 9, 1 );

register_activation_hook( __FILE__ , array( 'PPLB_Plugin', 'install_pplb_options' ) );		// set up options












class PHP_Code_Widget extends WP_Widget {
	function __construct() {
		load_plugin_textdomain( 'php-code-widget', false, dirname( plugin_basename( __FILE__ ) ) );
		$widget_ops = array('classname' => 'widget_execphp', 'description' => __('Arbitrary text, HTML, or PHP Code', 'php-code-widget'));
		$control_ops = array('width' => 400, 'height' => 350);
		parent::__construct('execphp', __('PHP Code', 'php-code-widget'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$text = apply_filters( 'widget_execphp', $instance['text'], $instance );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }
			ob_start();
			eval('?>'.$text);
			$text = ob_get_contents();
			ob_end_clean();
			?>
			<!--<div class="execphpwidget"><?php echo $instance['filter'] ? wpautop($text) : $text; ?></div>-->
			<div class="execphpwidget"><?php
			if ( is_single() ) {
			echo '<ul><a href="'.get_site_url().'/intern" style="font-weight:700;">zur Übersicht</a></ul>';
			}
			elseif (is_archive() ){
			echo '<ul><a href="'.get_site_url().'/intern" style="font-weight:700;">zur Übersicht</a></ul>';
			}

			?>
			</div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( $new_instance['text'] ) );
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
		$text = format_to_edit($instance['text']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'php-code-widget'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs.', 'php-code-widget'); ?></label></p>
<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("PHP_Code_Widget");'));


add_filter( 'wp_insert_post_data', function( $data, $postarr ){
	if ( 'post' == $data['post_type'] && 'auto-draft' == $data['post_status'] ) {
		$data['post_password'] = 'Aktive*Geburt2020';
	}
	return $data;
}, '99', 2 );


function secret_download_handler() {
    if (strpos($_SERVER["REQUEST_URI"], 'internal_download.php') !== false) {
        // echo json_encode($_COOKIE);
        // echo "<br>";
        // echo json_encode($post);
        // echo "<br>";
        // echo COOKIEHASH;
        if (isset($_COOKIE['wp-postpass_' . COOKIEHASH])) {
            //if (wp_check_password( $post->post_password, $_COOKIE['wp-postpass_' . COOKIEHASH])) {
            if (true) {
                require_once('wp-content/themes/be-up-theme/secret_download.php');
                $pdf_filename = urldecode($_GET['file']);
                $php_filename = sd_pdf_to_php_filename($pdf_filename);
                sd_echo_file_by_get($php_filename);
            } else {
                echo "wp_check_password failed.<br>";
            }
        } else {
            echo "Bitte vorher im internel Bereich anmelden.";
        }
        exit();
    }
}
add_action('parse_request', 'secret_download_handler');


function secret_upload_handler() {
    if (strpos($_SERVER["REQUEST_URI"], 'internal_upload.php') !== false) {
        require_once('wp-content/themes/be-up-theme/secret_download.php');
        sd_upload_file();
        exit();
    }
}
add_action('parse_request', 'secret_upload_handler');

add_filter( 'protected_title_format', 'remove_protected_text' );
	function remove_protected_text() {
	return __('%s');
}