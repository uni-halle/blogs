<?php
/**
 * Implements an optional custom header for Catch Box Pro.
 * See http://codex.wordpress.org/Custom_Headers
 *
 * @package WordPress
 * @subpackage Catch+Box_Pro
 * @since Catch Box Pro 1.0
 */

if ( ! function_exists( 'catchbox_custom_header_setup' ) ) :
/**
 * Sets up the WordPress core custom header arguments and settings.
 *
 * @uses add_theme_support() to register support for 3.4 and up.
 * @uses catchbox_header_style() to style front-end.
 * @uses catchbox_admin_header_style() to style wp-admin form.
 * @uses catchbox_admin_header_image() to add custom markup to wp-admin form.
 *
 * @since Catch Box Pro 1.0
 *
 * @remove Remove if block when WordPress 4.8 is released
 */
function catchbox_custom_header_setup() {
	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'wheel' => array(
			'url' => '%s/images/headers/garden.jpg',
			'thumbnail_url' => '%s/images/headers/garden-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Garden', 'catch-box' )
		),
		'shore' => array(
			'url' => '%s/images/headers/flower.jpg',
			'thumbnail_url' => '%s/images/headers/flower-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Flower', 'catch-box' )
		),
		'trolley' => array(
			'url' => '%s/images/headers/mountain.jpg',
			'thumbnail_url' => '%s/images/headers/mountain-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Mountain', 'catch-box' )
		),
	) );

	$args = array(
		// Text color and image (empty to use none).
		'default-text-color'     => '000', // Header Text color
		'default-image'          => '',

		// Set height and width, with a maximum value for the width.
		'height'                 => 400,
		'width'                  => 1000,

		// Support flexible height and width.
		'flex-height'            => true,
		'flex-width'             => true,

		// Random image rotation off by default.
		'random-default'         => false,

		// Callbacks for styling the header and the admin preview.
		'wp-head-callback'       => 'catchbox_header_style',
		'admin-head-callback'    => 'catchbox_admin_header_style',
		'admin-preview-callback' => 'catchbox_admin_header_image',
	);

	add_theme_support( 'custom-header', $args );
}
endif; // catchbox_custom_header_setup

add_action( 'after_setup_theme', 'catchbox_custom_header_setup' );


if ( ! function_exists( 'catchbox_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since Catch Box 1.0
 *
 * @remove Remove if block when WordPress 4.8 is released
 */
function catchbox_header_style() {

	$text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail.
	if ( get_theme_support( 'custom-header', 'default-text-color' ) === $text_color )
		return;

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $text_color ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php

		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // catchbox_header_style

if ( ! function_exists( 'catchbox_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @since Catch Box 1.0
 *
 * @remove Remove if block when WordPress 4.8 is released
 */
function catchbox_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_theme_support( 'custom-header', 'default-text-color' ) !== get_header_textcolor() ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>

	#headimg img {
		height: auto;
		max-width: 100%;
	}
	</style>
<?php
}
endif; // catchbox_admin_header_style

if ( ! function_exists( 'catchbox_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @since Catch Box 1.0
 *
 * @remove Remove if block when WordPress 4.8 is released
 */
function catchbox_admin_header_image() { ?>
	<div id="headimg">
		<?php
		$color = get_header_textcolor();
		$image = get_header_image();
		if ( $color && 'blank' != $color )
			$style = ' style="color:#' . $color . '"';
		else
			$style = ' style="display:none"';
		?>

		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // catchbox_admin_header_image


if ( ! function_exists( 'catchbox_header_image' ) ) :
/**
 * Template for Header Image
 *
 * To override this in a child theme
 * simply create your own catchbox_header_image(), and that function will be used instead.
 *
 * @since Catch Box 2.5
 */
function catchbox_header_image() {
	// Check to see if the header image has been removed
	$header_image = get_header_image();

	// Check Logo
	if ( function_exists( 'has_custom_logo' ) ) {
		if ( has_custom_logo() ) {
			echo '<div id="site-logo">'. get_custom_logo() . '</div><!-- #site-logo -->';
		}
	}
	elseif ( ! empty( $header_image ) ) { ?>

    	<div id="site-logo">
        	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
            </a>
      	</div>

	<?php } // end check for removed header image
}
endif;


if ( ! function_exists( 'catchbox_main_header_image' ) ) :
/**
 * Template for Header Image
 *
 * To override this in a child theme
 * simply create your own catchbox_main_header_image(), and that function will be used instead.
 *
 * @since Catch Box 4.4.2
 */
function catchbox_main_header_image() {
	// Check Logo
	if ( !function_exists( 'has_custom_logo' ) ) {
		//Bail if WP version is less than 4.5 as header image is used as logo in previous options
		return;
	}

	$header_image = get_header_image();
	if ( ! empty( $header_image ) ) : ?>
    	<div id="site-header-image">
        	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
            </a><!-- #site-logo -->
      	</div><!-- #site-header-image -->

	<?php endif; // end check for removed header image
}
endif;

if ( ! function_exists( 'catchbox_main_header_image_position' ) ) :
/**
 * Set Header Image Position
 *
 * To override this in a child theme
 * simply create your own catchbox_main_header_image_position(), and that function will be used instead.
 *
 * @since Catch Box 4.4.2
 */
function catchbox_main_header_image_position() {
	// Getting data from Theme Options
	$options  = catchbox_get_options();
	$position = isset( $options['header_image_position'] ) ? $options['header_image_position'] : 'above';

	if ( 'above' == $position ) {
		add_action( 'catchbox_before_headercontent', 'catchbox_main_header_image', 10 );
	}
	elseif ( 'below' == $position ) {
		add_action( 'catchbox_after_headercontent', 'catchbox_main_header_image', 10 );
	}
}
endif;
add_action( 'wp', 'catchbox_main_header_image_position' );


if ( ! function_exists( 'catchbox_header_details' ) ) :
/**
 * Template for Header Details
 *
 * @since Catch Box 2.5
 */
function catchbox_header_details() {
	?>
	<div id="hgroup" class="site-details">
		<?php if ( is_front_page() && is_home() ) : ?>
			<h1 id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php else : ?>
			<p id="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php endif;

		$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) : ?>
			<p id="site-description"><?php echo $description; ?></p>
		<?php endif; ?>	
   	</div><!-- #hgroup -->
<?php
}
endif;


if ( ! function_exists( 'catchbox_headerdetails' ) ) :
/**
 * Header Details including Site Logo, Title and Description
 *
 * @since Catch Box 2.5
 */
function catchbox_headerdetails() {

	// Getting data from Theme Options
	$options     = catchbox_get_options();
	$sitedetails = $options['site_title_above'];
	$position    = isset( $options['header_image_position'] ) ? $options['header_image_position'] : 'above';

	echo '<div class="logo-wrap clearfix">';

		if ( '0' == $sitedetails ) {
			echo catchbox_header_image();
			if ( 'between' == $position ) {
				catchbox_main_header_image();
			}
			echo catchbox_header_details();
		} else {
			echo catchbox_header_details();
			if ( 'between' == $position ) {
				catchbox_main_header_image();
			}
			echo catchbox_header_image();
		}

	echo '</div><!-- .logo-wrap -->';

}
endif; //catchbox_headerdetails


if ( ! function_exists( 'catchbox_header_search' ) ) :
/**
 * Header Search Box
 *
 * @since Catch Box 2.5
 */
function catchbox_header_search() {

	// Getting data from Theme Options
	$options = catchbox_get_options();

	if ( $options['disable_header_search'] == 0 ) :
    	get_search_form();
    endif;

}
endif; //catchbox_header_search