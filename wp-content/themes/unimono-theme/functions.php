<?php
if ( function_exists('register_sidebar') )
    register_sidebar();
    
    
//3.0 Custom Header Image
//this disables text color adjustments
define( 'HEADER_TEXTCOLOR', '' );
define( 'NO_HEADER_TEXT', true );

define( 'HEADER_IMAGE', '%s/images/header.jpg' );

define( 'HEADER_IMAGE_WIDTH', apply_filters( 'blogsmlu_header_image_width', 1000 ) );
define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'blogsmlu_header_image_height', 240 ) );
set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

function unimono_header_style() {
	//Check and apply this only if a header image has been set
	if (get_header_image())  {
		?><style type="text/css">
	    	#header { 
	    		background: url(<?php header_image(); ?>) top left no-repeat;
	    	}
	    </style>
	<?php }
}

function unimono_admin_header_style() {
	?><style type="text/css">
	#headimg {
		background: url(<?php header_image(); ?>) top left no-repeat;
	}
	</style>
<?php }

add_custom_image_header( 'unimono_header_style', 'unimono_admin_header_style');

//3.0 Menu Management
if (function_exists('add_theme_support')) {
    add_theme_support('menus');
}

function register_my_menus() {
	register_nav_menu( 'header-menu', __( 'Header Menu' ) );
}
add_action( 'init', 'register_my_menus' );

//3.0 Automatische Feeds
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'automatic-feed-links' );
}

?>