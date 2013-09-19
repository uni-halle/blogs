<?php
/**
 * @package Duotone
 * @since Duotone v2.0
 */
class Duotone {

	static $image = false;

	/**
	 * @since Duotone v2.0
	 */
	public static function init() {
		add_action( 'publish_post', array( __class__, 'save_image_data' ) );
		add_action( 'publish_page', array( __class__, 'save_image_data' ) );

		add_action( 'request', array( __class__, 'modify_request' ) );
		add_action( 'template_redirect', array( __class__, 'setup_single_post_template' ) );
		add_action( 'wp_print_scripts', array( __class__, 'enqueue_scripts' ) );
		add_filter( 'theme_mod_background_color', array( __class__, 'deprecated_background_color_override' ) );
		add_filter( 'duotone_archive_image_url', array( __class__, 'wpcom_archive_image_url' ) );
		add_filter( 'duotone_singular_image_url', array( __class__, 'wpcom_singular_image_url' ), 10, 2 );
		add_filter( 'body_class', array( __class__, 'body_class' ), 0 );
		add_filter( 'the_content', array( __class__, 'content_setup' ), 0 );
	}

	public static function body_class( $classes ) {
		$orientation = 'horizontal';
		if ( 1 == absint( self::$image['is_vertical'] ) && ! is_archive() && ! is_search() )
			$orientation = 'vertical';

		$classes[] = $orientation;

		if ( is_search() )
			$classes[] = 'archive';

		return $classes;
	}

	public static function enqueue_scripts() {
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		wp_enqueue_script( 'duotone', get_template_directory_uri() . '/inc/duotone.js', array( 'jquery' ), '20111219' );
		wp_localize_script( 'duotone', 'Duotone', array( 'homeUrl' => esc_url( home_url( '/' ) ) ) );
	}

	/**
	 * Set the image property for all templates that display a single post object.
	 *
	 * Hooks into the "template_redirect" action.
	 *
	 * @since Duotone v2.0
	 */
	public static function setup_single_post_template() {
		global $wp_query;
		if ( 0 == $wp_query->post_count )
			return;

		if ( is_archive() || is_search() || is_404() )
			return;

		self::$image = self::get_image_data();

		self::set_themecolors();

		add_action( 'wp_head', array( __class__, 'styles' ) );
	}

	/**
	 * Return an image tag for display in archive templates.
	 *
	 * @since Duotone v2.0
	 */
	public static function get_archive_image() {
		$url = self::get_the_image_url_for_display();
		$url = apply_filters( 'duotone_archive_image_url', $url, self::$image );

		// VideoPress images don't support ImgPress
		if ( self::is_videopress_image( $url ) ) {
			$url = remove_query_arg( 'w', $url );
			$url = remove_query_arg( 'h', $url );
		}

		if ( ! empty( $url ) )
			return '<img class="archive-image" src="' . esc_url( $url ) . '" alt="" />';

		return '';
	}

	/**
	 * Return an image tag for display in templates that display a single post object.
	 *
	 * @since Duotone v2.0
	 */
	public static function get_singular_image() {
		$url = self::get_the_image_url_for_display();
		$url = apply_filters( 'duotone_singular_image_url', $url, self::$image );

		if ( ! empty( $url ) ) {
			if ( self::is_videopress_image( $url ) ) {
				// It's a VideoPress image - replace with the video
				$html = self::get_videopress_html( $url );
				if ( ! empty( $html ) )
					return $html;
			}

			return '<img src="' . esc_url( $url ) . '" alt="" />';
		}

		return '';
	}

	public static function is_videopress_image( $url ) {
			$vp = strpos( $url, 'http://videos.videopress.com/' );
			$vps = strpos( $url, 'https://videos.files.wordpress.com/' );

			return ( 0 === $vp || 0 === $vps );
	}

	public static function get_videopress_html( $url ) {
		$matches = array();
		preg_match( '#^http(s)?://videos.(files.word|video)press.com/([[:alnum:]]+)/#i', $url, $matches );
		if ( empty( $matches[3] ) )
			return '';

		$guid = $matches[3] ;
		$style = '<style type="text/css"> .image .nav {z-index: auto !important} #image .video-player {z-index: 1; position: relative}</style>';
		return $style . do_shortcode( "[wpvideo $guid]" );
	}

	/**
	 * Get the image url for display in a template.
	 *
	 * Since the url is being plucked from the post_content we
	 * need to ensure that it is only displayed where appropriate.
	 *
	 * @since Duotone v2.0
	 */
	private static function get_the_image_url_for_display() {
		if ( post_password_required() )
			return '';

		if ( is_home() || is_singular() ) {
			$image = self::$image;
			$size = 'duotone_singular';
		} else {
			$image = self::get_image_data( 0, false );
			$size = 'duotone_archive';
		}

		$url = '';
		if ( ! empty( $image['image_id'] ) ) {
			$src = wp_get_attachment_image_src( $image['image_id'], $size );
			if ( isset( $src[0] ) )
				$url = $src[0];
		}

		if ( empty( $url ) && ! empty( $image['url'] ) )
			$url = $image['url'];

		return $url;
	}

	/**
	 * Remove the first image from the post_content.
	 *
	 * Hooks into the "the_content" filter as early as possible.
	 */
	public static function content_setup( $entry ) {
		if ( is_feed() )
			return $entry;

		if ( 0 != get_query_var( 'page' ) )
			return $entry;

		/* Remove first image tag. */
		$count = 0;
		$entry = preg_replace( '/<img [^>]*src=(\"|\').+?(\1)[^>]*\/*>/','', $entry, 1, $count );

		// If no image was removed, remove the first video instead
		if ( ! $count )
			$entry = preg_replace( '/\[(wpvideo|videopress)[^[]+\]/','', $entry, 1, $count );

		return $entry;
	}

	/**
	 * Adjust the main query.
	 *
	 * Show only a single post where is_home() returns true.
	 * Show 27 posts in archive and search results.
	 *
	 * Hooks into the "request" action.
	 *
	 * @since Duotone v2.0
	 */
	public static function modify_request( $request ) {
		$q = new WP_Query();
		$q->parse_query( $request );

		if ( $q->is_home() ) {
			$request['posts_per_page'] = 1;
			$request['post__not_in'] = get_option( 'sticky_posts' );
		} else if ( $q->is_archive() || $q->is_search() ) {
			$request['posts_per_page'] = 27;
		}

		return $request;
	}

	/**
	 * @since Duotone v2.0
	 */
	public static function styles() {
		extract( self::$image );

		$background_color = get_background_color();
		if ( empty( $background_color ) && isset( $background['+2'] ) )
			$background_color = $background['+2'];

		?>
	<style type="text/css" media="screen">
		body {
			background-color: <?php echo self::sanitize_color_hex( $background_color ); ?>;
		}
		#page {
			background-color: <?php echo self::sanitize_color_hex( $background['-2'] ); ?>;
			color: <?php echo self::sanitize_color_hex( $foreground['-2'] ); ?>;
		}
		#menu a, #menu a:link, #menu a:visited {
			color: <?php echo self::sanitize_color_hex( $background['-3'] ); ?>;
		}
		#menu a:hover, #menu a:active {
			color: <?php echo self::sanitize_color_hex( $foreground['-3'] ); ?>;
		}
		a,a:link, a:visited {
			color: <?php echo self::sanitize_color_hex( $foreground['-3'] ); ?>;
		}
		a:hover, a:active {
			color: <?php echo self::sanitize_color_hex( $background['+2'] ); ?>;
		}
		#header h1,
		#header h1 a,
		#header h1 a:link,
		#header h1 a:visited,
		#header h1 a:active {
			color: <?php echo self::sanitize_color_hex( $background['+3'] ); ?>;
		}
		#header h1 a:hover {
			color: <?php echo self::sanitize_color_hex( $background['+2'] ); ?>;
		}
		.navigation a,
		.navigation a:link,
		.navigation a:visited,
		.navigation a:active {
			color: <?php echo self::sanitize_color_hex( $foreground['-1'] ); ?>;
		}
		h1:hover,
		h2:hover,
		h3:hover,
		h4:hover,
		h5:hover,
		h6:hover,
		.navigation a:hover {
			color: <?php echo self::sanitize_color_hex( $foreground['-2'] ); ?>;
		}
		.description,
		h3#reply-title,
		#comments,
		#content #sidebar h2,
		h2, h2 a, h2 a:link, h2 a:visited, h2 a:active,
		h3, h3 a, h3 a:link, h3 a:visited, h3 a:active,
		h4, h4 a, h4 a:link, h4 a:visited, h4 a:active,
		h5, h5 a, h5 a:link, h5 a:visited, h5 a:active,
		h6, h6 a, h6 a:link, h6 a:visited, h6 a:active {
			color: <?php echo self::sanitize_color_hex( $background['+3'] ); ?> !important;
			border-color: <?php echo self::sanitize_color_hex( $background['+2'] ); ?> !important;
		}
		#content #sidebar {
			border-top: 1px solid <?php echo self::sanitize_color_hex( $background['+3'] ); ?>;
		}
		#postmetadata, #commentform p, .commentlist li, #post, #postmetadata .sleeve, #post .sleeve,
		#content {
			color: <?php echo self::sanitize_color_hex( $background['+3'] ); ?>;
			border-color: <?php echo self::sanitize_color_hex( $background['+3'] ); ?>;
		}
	</style><?php
	}

	public static function scrape_first_image_url( $content ) {
		if ( preg_match( '/<img [^>]*src=(\"|\')(.+?)(\1)[^>]*\/*>/i', $content, $matches ) )
			return $matches[2];

		if( preg_match( '/\[(wpvideo|videopress) ([[:alnum:]]+)/', $content, $matches ) && function_exists( 'video_image_url_by_guid' ) )
			return video_image_url_by_guid( $matches[2] );

		return '';
	}

	/**
	 * Save Image Data.
	 *
	 * @since Duotone v2.0
	 */
	public static function save_image_data( $ID ) {
		$post = get_post( $ID );

		$image_url = self::scrape_first_image_url( $post->post_content );

		/*
		 * If there is no img tag in the post_content we will
		 * clear all image related post meta data and return early.
		 */
		if ( empty( $image_url ) ) {
			self::flush_image_data( $ID );
			return false;
		}

		$saved = self::get_image_data( $ID );

		if ( $image_url == $saved['url'] && ! empty( $saved['image_url'] ) )
			return false;

		self::flush_image_data( $ID );

		include_once( get_template_directory() . '/inc/csscolor.php' );
		$color = new Duotone_CSS_Color( self::best_color( $image_url ) );

		$image_id = self::get_the_image_id( $image_url, $ID );

		if ( ! empty( $image_id ) ) {
			$image_meta = wp_get_attachment_metadata( $image_id );
			$image_meta = wp_parse_args( $image_meta, array(
				'width'  => 0,
				'height' => 0,
			) );

			$size = array( $image_meta['width'], $image_meta['height'] );
		} else {
			$image_path = self::get_image_path( $image_url );
			$size = self::is_file( $image_path ) ? getimagesize( $image_path ) : array();
		}

		$is_vertical = ( self::is_vertical( $size ) ) ? 1 : 0;

		$post_meta = array(
			'background'  => $color->bg,
			'foreground'  => $color->fg,
			'url'         => esc_url_raw( $image_url ),
			'is_vertical' => absint( $is_vertical ),
			'image_id'    => absint( $image_id ),
		);

		add_post_meta( $ID, '_duotone', $post_meta );
	}

	/**
	 * Return the ID of the first image in a post.
	 *
	 * First check to see if the image is already stored in
	 * the Media Library. If so, this ID will be returned.
	 *
	 * If the image cannot be found in the Media Library it
	 * will be added as an attachment to the post represented
	 * by the $ID parameter.
	 *
	 * Always return zero on WordPress.com.
	 *
	 * @uses Duotone::sideload_image()
	 *
	 * @param string $url Full url to the image.
	 * @param int $ID Post ID.
	 * @return int Image $ID.
	 *
	 * @access private
	 * @since Duotone v2.0
	 */
	private static function get_the_image_id( $url, $ID ) {
		$image_id = 0;

		if ( defined( 'IS_WPCOM' ) && IS_WPCOM )
			return $image_id;

		$cached = get_children( array(
			'numberposts'    => 1,
			'meta_compare'   => 'LIKE',
			'meta_key'       => '_wp_attachment_metadata',
			'meta_value'     => basename( $url ),
			'post_mime_type' => 'image',
			'post_parent'    => null,
			'post_status'    => null,
			'post_type'      => 'attachment',
		) );

		if ( $cached ) {
			$cached = array_shift( $cached );
			$image_id = $cached->ID;
		}
		else {
			$image_id = self::sideload_image( $url, $ID );
		}

		return $image_id;
	}

	/**
	 * Download an image from the specified URL and attach it to a post.
	 *
	 * This is a reworked version of WordPress core function
	 * media_sideload_image(). The core version returns a html
	 * tag representing the image but Duotone requires the image's
	 * ID to be returned.
	 *
	 * @param string $file The URL of the image to download
	 * @param int $ID The post ID the media is to be associated with
	 * @param string $desc Optional. Description of the image
	 * @return string|WP_Error Populated HTML img tag on success
	 *
	 * @since Duotone v2.0
	 */
	private function sideload_image( $file, $ID, $desc = null ) {
		if ( empty( $file ) )
			return 0;

		/* Download file to temp location. */
		$tmp = download_url( $file );

		/* fix file filename for query strings. */
		preg_match( '/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $file, $matches );

		/* Set variables for storage */
		$file_array['name'] = basename( $matches[0] );
		$file_array['tmp_name'] = $tmp;

		/* If error storing temporarily, unlink */
		if ( is_wp_error( $tmp ) ) {
			@unlink( $file_array['tmp_name'] );
			$file_array['tmp_name'] = '';
		}

		/* do the validation and storage stuff */
		$id = media_handle_sideload( $file_array, $ID, $desc );

		/* Image cound not be stored. Delete the temporary image. */
		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );
			return 0;
		}

		return $id;
	}

	/**
	 * Get Image Data.
	 *
	 * Retrives postmeta from database, merged with default values.
	 * This function cas the potential to be pretty resource intensive
	 * and therefore should only be called once per document.
	 *
	 * @since Duotone v2.0
	 */
	public static function get_image_data( $ID = 0, $scan_image = true ) {
		$defaults = array(
			'background'  => array(),
			'foreground'  => array(),
			'url'         => '',
			'is_vertical' => 0,
			'image_id'    => 0,
		);

		if ( empty( $ID ) )
			$ID = get_the_ID();

		$meta = get_post_meta( $ID, '_duotone', true );

		/*
		 * COMPAT: Allow deprecated post meta to override defaults.
		 */
		if ( empty( $meta ) )
			$defaults = wp_parse_args( self::get_deprecated_meta( $ID ), $defaults );

		/*
		 * Scrape first image if no value for url is stored.
		 */
		if ( empty( $defaults['url'] ) ) {
			$current_post = get_post( $ID );
			$scraped = self::scrape_first_image_url( $current_post->post_content );
			if ( ! empty( $scraped ) )
				$defaults['url'] = $scraped;
		}

		/*
		 * Return early in cases where multiple images are
		 * displayed: search, category, archives.
		 */
		if ( ! $scan_image ) {
			$data = wp_parse_args( $meta, $defaults );
			return $data;
		}

		/*
		 * Generate Colors.
		 */
		if ( ! empty( $defaults['url'] ) && ( empty( $defaults['background'] ) || empty( $defaults['background'] ) ) ) {
			include_once( get_template_directory() . '/inc/csscolor.php' );
			$path = self::get_image_path( $defaults['url'] );
			$colors = new Duotone_CSS_Color( self::best_color( $path ) );

			if ( empty( $defaults['background'] ) )
				$defaults['background'] = $colors->bg;

			if ( empty( $defaults['foreground'] ) )
				$defaults['foreground'] = $colors->fg;
		}

		/*
		 * Still no colors? Use default values.
		 */
		if ( empty( $defaults['background'] ) )
			$defaults['background'] = self::get_colors( 'background' );

		if ( empty( $defaults['foreground'] ) )
			$defaults['foreground'] = self::get_colors( 'foreground' );

		if ( ! isset( $meta['is_vertical'] ) ) {
			$image_path = self::get_image_path( $defaults['url'] );
			$size = self::is_file( $image_path ) ? getimagesize( $image_path ) : array();

			if ( self::is_vertical( $size ) )
				$defaults['is_vertical'] = 1;
		}

		$data = wp_parse_args( $meta, $defaults );
		return $data;
	}

	/**
	 * Checks whether a file exists. Accounts for both files and URLs.
	 *
	 * @param string $file Path or URL to file.
	 * @return boolean Whether file exists.
	 */
	private static function is_file( $file ) {
		if ( is_file( $file ) ) {
			return true;
		} else {
			$response = wp_remote_head( $file );
			return 200 == wp_remote_retrieve_response_code( $response );
		}
	}

	public static function get_image_path( $url ) {
		$uploads = wp_upload_dir();
		$path = str_replace( $uploads['baseurl'], $uploads['basedir'], $url );
		list( $path ) = explode( '?', $path );
		return $path;
	}

	/**
	 * Get deprecated meta data stored by Duotone v1.1.
	 *
	 * @param int $ID Unique id of a WordPress post object.
	 * @return array
	 *
	 * @since Duotone v2.0
	 */
	private static function get_deprecated_meta( $ID ) {
		$meta = array();

		$background = get_post_meta( $ID, 'image_colors_bg', true );
		if ( is_array( $background ) )
			$meta['background'] = $background;

		$foreground = get_post_meta( $ID, 'image_colors_fg', true );
		if ( is_array( $foreground ) )
			$meta['foreground'] = $foreground;

		$url = get_post_meta( $ID, 'url', true );
		if ( ! empty( $url ) )
			$meta['url'] = $url;

		$size = get_post_meta( $ID, 'image_size', true );
		if ( self::is_vertical( $size ) )
			$defaults['is_vertical'] = 1;

		return $meta;
	}

	/**
	 * Get colors.
	 *
	 * The color arrays returned by Duotone_CSS_Color possess
	 * keys with both numeric and string types making it inappropriate
	 * to merge it's values with wp_parse_args(). This function will
	 * manually merge it's values with defaults.
	 *
	 * @since Duotone v2.0
	 */
	public static function get_colors( $area = 'background', $merge = array() ) {
		include_once( get_template_directory() . '/inc/csscolor.php' );
		$defaults = new Duotone_CSS_Color( 'ffffff' );

		if ( 'background' == $area )
			$colors = $defaults->bg;
		else
			$colors = $defaults->fg;

		foreach ( $colors as $k => $color ) {
			if ( isset( $merge[$k] ) )
				$colors[$k] = $merge[$k];
		}

		return $colors;
	}

	/**
	 * Flush post meta.
	 *
	 * Delete all post metadata that this theme may have
	 * ever stored for a post including currently supported
	 * and deprecated keys.
	 *
	 * @param int $ID Unique id of a WordPress post object.
	 *
	 * @since Duotone v2.0
	 */
	public static function flush_image_data( $ID ) {
		delete_post_meta( $ID, '_duotone' );

		$deprecated = array(
			'image_url',
			'image_size',
			'image_tag',
			'image_colors_bg',
			'image_colors_fg',
			'image_md5',
			'image_colors',
			'image_color_base'
		);

		foreach ( $deprecated as $key ) {
			delete_post_meta( $ID, $key );
		}
	}

	/**
	 * DEBUG: Dump Image Data.
	 *
	 * @since Duotone v2.0
	 */
	public static function dump_image_data( $ID = 0 ) {
		if ( empty( $ID ) )
			$ID = get_the_ID();

		$meta = self::get_image_data( $ID );

		self::dump_colors( __( 'Foreground' , 'duotone' ), $meta['foreground'] );
		self::dump_colors( __( 'Background' , 'duotone' ), $meta['background'] );

		echo '<pre>';
		echo 'image_id: ' . absint( $meta['image_id'] );
		echo "\n" . 'is_vertical: ' . absint( $meta['is_vertical'] );
		echo "\n" . 'url: ' . esc_url( $meta['url'] );
		echo '</pre>';
	}

	/**
	 * DEBUG: Dump Colors.
	 *
	 * @since Duotone v2.0
	 */
	public static function dump_colors( $label, $color ) {
		echo "\n\n" . '<strong>' . esc_html( $label ) . '</strong>';

		if ( empty( $color ) ) {
			echo '<p>' . __( 'empty', 'duotone' ) . '</p>';
			return;
		}

		echo "\n"   . '<p>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['-5'] ) . ';">-5</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['-4'] ) . ';">-4</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['-3'] ) . ';">-3</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['-2'] ) . ';">-2</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['-1'] ) . ';">-1</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color[0] ) . ';">0</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['+1'] ) . ';">+1</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['+2'] ) . ';">+2</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['+3'] ) . ';">+3</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['+4'] ) . ';">+4</span>';
		echo "\n\t" . '<span style="background-color:' . self::sanitize_color_hex( $color['+5'] ) . ';">+5</span>';
		echo "\n"   . '</p>';
	}

	/**
	 * @since Duotone v2.0
	 */
	public static function is_vertical( $size ) {
		if ( ! isset( $size[0] ) && ! isset( $size[1] ) )
			return false;
		else if ( $size[0] <= $size[1] || $size[0] < MIN_WIDTH )
			return true;
		return false;
	}

	/**
	 * @since Duotone v2.0
	 */
	public static function rgbhex( $red, $green, $blue ) {
		return sprintf( '%02X%02X%02X', $red, $green, $blue );
	}

	/**
	 * @since Duotone v2.0
	 */
	public static function hsv( $r, $g, $b ) {
		$max = max( $r, $g, $b );
		$min = min( $r, $g, $b );
		$delta = $max - $min;
		$v = round( ( $max / 255 ) * 100 );
		$s = ( $max != 0 ) ? ( round( $delta / $max * 100 ) ) : 0;
		if ( $s == 0 ) {
			$h = false;
		} else {
			if ( $r == $max )
				$h = ( $g - $b ) / $delta;
			else if ( $g == $max )
				$h = 2 + ( $b - $r ) / $delta;
			else if ( $b == $max )
				$h = 4 + ( $r - $g ) / $delta;

			$h = round( $h * 60 );

			if ( $h > 360 )
				$h = 360;

			if ( $h < 0 )
				$h += 360;
		}

		return array( $h, $s, $v );
	}

	private static function best_color( $url ) {
		$default = 'ffffff';
		$url = trim( $url );

		global $current_blog;
		if ( defined( 'IS_WPCOM' ) && IS_WPCOM && $current_blog->public == -1 ) {
			$url = apply_filters( 'wpcom_get_private_file', $url );
			// VideoPress images don't support ImgPress
			if ( ! self::is_videopress_image( $url ) )
				$url = add_query_arg( array( 'w' => 300 ), $url );
		}
		else {
			$url = self::get_image_path( $url );
		}

		$ext = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
		$ext = explode( '?', $ext );
		$ext = $ext[0];

		if ( ! self::is_file( $url ) )
			return $default;

		switch ( $ext ) {
			case 'gif' :
				$im = imagecreatefromgif( $url );
				break;
			case 'png' :
				$im = imagecreatefrompng( $url );
				break;
			case 'jpg' :
			case 'jpeg' :
				$im = imagecreatefromjpeg( $url );
				break;
			default:
				return $default;
		}

		if ( false === $im )
			return $default;

		$height = imagesy( $im );
		$width  = imagesx( $im );

		// sample five points in the image, based on rule of thirds and center
		$topy    = round( $height / 3 );
		$bottomy = round( ( $height / 3 ) * 2 );
		$leftx   = round( $width / 3 );
		$rightx  = round( ( $width / 3 ) * 2 );
		$centery = round( $height / 2 );
		$centerx = round( $width / 2 );

		// grab those colors
		$rgb = array(
			imagecolorat( $im, $leftx, $topy ),
			imagecolorat( $im, $rightx, $topy ),
			imagecolorat( $im,  $leftx, $bottomy ),
			imagecolorat( $im,  $rightx, $bottomy ),
			imagecolorat( $im, $centerx, $centery ),
		);

		// process points
		for ( $i = 0; $i <= count( $rgb ) - 1; $i++ ) {
			$r[$i] = ( $rgb[$i] >> 16 ) & 0xFF;
			$g[$i] = ( $rgb[$i] >> 8 ) & 0xFF;
			$b[$i] = $rgb[$i] & 0xFF;

			/* rgb */
			list( $colors[$i]['r'], $colors[$i]['g'], $colors[$i]['b'] ) = array( $r[$i], $g[$i], $b[$i] );

			/* hsv */
			list( $colors[$i]['h'], $colors[$i]['s'], $colors[$i]['v']) = Duotone::hsv( $r[$i], $g[$i], $b[$i] );

			/* hex */
			$colors[$i]['hex'] = Duotone::rgbhex( $r[$i], $g[$i], $b[$i] );
		}

		$best_saturation = $best_brightness = 0;
		$the_best_s      = $the_best_v      = array( 'v' => 0 );
		foreach ( $colors as $color => $value ) {
			if ( $value['s'] > $best_saturation ) {
				$best_saturation = $value['s'];
				$the_best_s = $value;
			}
			if ( $value['v'] > $best_brightness ) {
				$best_brightness = $value['v'];
				$the_best_v = $value;
			}
		}

		// is brightest the same as most saturated?
		$the_best = ( $the_best_s['v'] >= ( $the_best_v['v'] - ( $the_best_v['v'] / 2 ) ) ) ? $the_best_s : $the_best_v;
		return $the_best['hex'];
	}

	/**
	 * @todo Use sideloaded image instead of first attached image if possible.
	 */
	public static function exif_table() {
		$images = array_values( get_children( array(
			'post_parent'    => get_the_ID(),
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image'
		) ) );

		if ( ! $images )
			return;

		$image = array_shift( $images );

		$meta = wp_get_attachment_metadata( $image->ID );

		if ( ! isset( $meta['image_meta'] ) )
			return;

		$exif = wp_parse_args( $meta['image_meta'], array(
			'aperture'      => '',
			'focal_length'  => '',
			'iso'           => '',
			'shutter_speed' => '',
			'camera'        => '',
		) );

		$rows = array();

		if ( ! empty( $exif['aperture'] ) )
			$rows[] = '<th>' . __( 'Aperture:', 'duotone' ) . '</th><td>' . sprintf( __( 'f/%1$s', 'duotone' ), esc_html( $exif['aperture'] ) ) . '</td>';

		if ( ! empty( $exif['focal_length'] ) )
			$rows[] = '<th>' . __( 'Focal Length:', 'duotone' ) . '</th><td>' . sprintf( __( '%1$smm', 'duotone' ), esc_html( $exif['focal_length'] ) ) . '</td>';

		if ( ! empty( $exif['iso'] ) )
			$rows[] = '<th>' . __( 'ISO:', 'duotone' ) . '</th><td>' . esc_html( $exif['iso'] ) . '</td>';

		if ( ! empty( $exif['shutter_speed'] ) )
			$rows[] = '<th>' . __( 'Shutter:', 'duotone' ) . '</th><td>' . sprintf( __( '%1$s sec', 'duotone' ), esc_html( self::dec2frac( $exif['shutter_speed'] ) ) ) . '</td>';

		if ( ! empty( $exif['iso'] ) )
			$rows[] = '<th>' . __( 'Camera:', 'duotone' ) . '</th><td>' . esc_html( $exif['camera'] ) . '</td>';

		if ( empty( $rows ) )
			return;

		echo "\n" . '<table class="photo-tech">';
		foreach ( $rows as $row ) {
			echo "\n\t" . '<tr>' . $row . '</tr>';
		}
		echo "\n" . '</table>';
	}

	/**
	 * Used only by Duotone::exif_table().
	 */
	public static function dec2frac( $dec ) {
		global $duotone_result;

		if ( (int) $dec > 1 )
			return $dec;

		$count = 0;
		$duotone_result = array();
		self::decimalToFraction( $dec, $count, $duotone_result );
		$count = count( $dec );
		return self::simplifyFraction( $duotone_result, $count, 1, $duotone_result[$count] );
	}

	/**
	 * Used only by Duotone::dec2frac().
	 */
	public static function decimalToFraction( $decimal, $count, $duotone_result ) {
		global $duotone_result;
		$a = ( 1 / $decimal );
		$b = ( $a - floor( $a )  );
		$count++;
		if ( $b > .01 && $count <= 5 )
			self::decimalToFraction( $b, $count, $duotone_result );
		$duotone_result[$count] = floor( $a );
	}

	/*
	 * Simplifies a fraction in an array form that is returned from
	 * Duotone::decimalToFraction()
	 *
	 * Used only by Duotone::decimalToFraction().
	 */
	public static function simplifyFraction( $fraction, $count, $top, $bottom ) {
		$next = 0;
		if ( isset( $fraction[$count-1] ) )
			$next = $fraction[$count-1];
		$a = ( $bottom * $next ) + $top;
		$top = $bottom;
		$bottom = $a;
		$count--;
		if ( $count > 0 )
			self::simplifyFraction( $fraction, $count, $top, $bottom );
		else
			return sprintf( __( '%1$d/%2$d', 'duotone' ), $bottom, $top );
	}

	/**
	 * Ensure that a string representing a color in hexadecimal
	 * notation is safe for use in css and database saves.
	 *
	 * @param string Color in hexadecimal notation. "#" may or may not be prepended to the string.
	 * @return string Color in hexadecimal notation on success - the string "transparent" otherwise.
	 *
	 * @since Duotone v2.0
	 */
	public static function sanitize_color_hex( $hex, $prefix = '#' ) {
		$hex = trim( $hex );

		/* Strip recognized prefixes. */
		if ( 0 === strpos( $hex, '#' ) )
			$hex = substr( $hex, 1 );
		elseif ( 0 === strpos( $hex, '%23' ) )
			$hex = substr( $hex, 3 );

		if ( 0 !== preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) )
			return $prefix . $hex;

		return 'transparent';
	}

	/**
	 * COMPAT: Allow deprecated `background_color` option to temporarily sub for theme modification.
	 *
	 * Prior to version 2.0, Duotone had a custom Theme Options screen
	 * which allowed users to define a custom background color to override
	 * automatic color generation. For complinace with the WPTRT guidelines,
	 * this custom implementation has been replaced with WordPress core
	 * background functionality.
	 *
	 * Users should see the deprecated background color in Appearance -> Background
	 * as well as in all template files. We will need to override get_theme_mod()
	 * when used by get_background_color().
	 *
	 * Hooks in the `theme_mod_background_color` filter.
	 *
	 * @since Duotone v2.0
	 */
	public static function deprecated_background_color_override( $color ) {
		$deprecated_color = self::sanitize_color_hex( get_option( 'background_color' ), '' );
		if ( 'transparent' != $deprecated_color ) {
			set_theme_mod( 'background_color', $deprecated_color );
			return $deprecated_color;
		}

		return $color;
	}

	/**
	 * WPCOM: Add crop parameters to image urls for archive templates.
	 *
	 * @since Duotone v2.0
	 */
	public static function wpcom_archive_image_url( $url ) {
		if ( empty( $url ) )
			return $url;

		if ( defined( 'IS_WPCOM' ) && IS_WPCOM )
			$url = add_query_arg( array( 'w' => 75, 'h' => 75, 'crop' => 1 ), $url );

		return $url;
	}

	/**
	 * WPCOM: Add width query arg to image urls for singular templates.
	 *
	 * @since Duotone v2.0
	 */
	public static function wpcom_singular_image_url( $url, $image ) {
		if ( empty( $url ) )
			return $url;

		if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
			$is_vertical = ( isset( $image['is_vertical'] ) ) ? $image['is_vertical'] : 1;
			$new_width = ( $image['is_vertical'] ) ? MIN_WIDTH : MAX_WIDTH;
			$url = add_query_arg( array( 'w' => absint( $new_width ) ), $url );
		}

		return $url;
	}

	/**
	 * WPCOM: Set $themecolors global.
	 *
	 * @since Duotone v2.0
	 */
	public static function set_themecolors() {
		if ( defined( 'IS_WPCOM' ) && IS_WPCOM ) {
			extract( self::$image );
			global $themecolors;
			$themecolors = array(
				'bg'     => self::sanitize_color_hex( $background['-2'], '' ),
				'border' => self::sanitize_color_hex( $background['-1'], '' ),
				'text'   => self::sanitize_color_hex( $foreground['-2'], '' ),
				'link'   => self::sanitize_color_hex( $foreground['-3'], '' ),
				'url'    => self::sanitize_color_hex( $foreground['-4'], '' ),
			);
		}
	}
}