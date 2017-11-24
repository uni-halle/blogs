<?php
/**
 * Set the theme's version as the scripts version
 * @since Graphene 2.0
 */
function graphene_scripts_version( $graphene_settings ){
	$theme_data = wp_get_theme( basename( GRAPHENE_ROOTDIR ) );
	$graphene_settings['scripts_ver'] = $theme_data->Version;

	return $graphene_settings;
}
add_filter( 'graphene_settings', 'graphene_scripts_version' );


/**
 * Print the stylesheets
*/
function graphene_enqueue_scripts(){
	global $graphene_settings;
	$version = $graphene_settings['scripts_ver'];

	/* Enqueue scripts */
	wp_enqueue_script( 'bootstrap', 				GRAPHENE_ROOTURI . '/bootstrap/js/bootstrap.min.js', 								array( 'jquery' ), $version );
	wp_enqueue_script( 'bootstrap-hover-dropdown', 	GRAPHENE_ROOTURI . '/js/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js', 	array( 'jquery', 'bootstrap' ), $version );
	wp_enqueue_script( 'bootstrap-submenu', 		GRAPHENE_ROOTURI . '/js/bootstrap-submenu/bootstrap-submenu.min.js', 				array( 'jquery', 'bootstrap' ), $version );
	wp_enqueue_script( 'html5shiv', 				GRAPHENE_ROOTURI . '/js/html5shiv/html5shiv.min.js',  								array(), $version );
	wp_enqueue_script( 'respond', 					GRAPHENE_ROOTURI . '/js/respond.js/respond.min.js', 								array(), $version );
	wp_enqueue_script( 'infinite-scroll', 			GRAPHENE_ROOTURI . '/js/jquery.infinitescroll.min.js', 								array( 'jquery' ), $version );
	wp_enqueue_script( 'graphene', 					GRAPHENE_ROOTURI . '/js/graphene.js', 												array( 'bootstrap', 'comment-reply', 'infinite-scroll' ), $version );

	/* Enqueue styles */
	wp_enqueue_style( 'graphene-google-fonts', 		graphene_google_fonts_uri(), 										array(), $version );
	wp_enqueue_style( 'bootstrap', 					GRAPHENE_ROOTURI . '/bootstrap/css/bootstrap.min.css' );
	wp_enqueue_style( 'font-awesome', 				GRAPHENE_ROOTURI . '/fonts/font-awesome/css/font-awesome.min.css',  array() );
	wp_enqueue_style( 'graphene', 					get_stylesheet_uri(), 												array( 'bootstrap', 'font-awesome' ), $version, 'screen' );
	wp_enqueue_style( 'graphene-responsive', 		GRAPHENE_ROOTURI . '/responsive.css', 								array( 'bootstrap', 'font-awesome', 'graphene' ), $version );
	if ( is_rtl() ) {
		wp_enqueue_style( 'bootstrap-rtl', 			GRAPHENE_ROOTURI . '/bootstrap-rtl/bootstrap-rtl.min.css', 			array( 'bootstrap' ), $version );
		wp_enqueue_style( 'graphene-responsive-rtl',GRAPHENE_ROOTURI . '/responsive-rtl.css', 							array( 'bootstrap-rtl', 'graphene' ), $version, 'screen' );
	}
	if ( is_singular() && $graphene_settings['print_css'] ) 
		wp_enqueue_style( 'graphene-print', 		GRAPHENE_ROOTURI . '/style-print.css', 								array( 'graphene' ), $version, 'print' );

}
add_action( 'wp_enqueue_scripts', 'graphene_enqueue_scripts' );


/**
 * Localize scripts and add JavaScript data
 *
 * @package Graphene
 * @since 1.9
 */
function graphene_localize_scripts(){
	global $graphene_settings, $wp_query;
	$posts_per_page = $wp_query->get( 'posts_per_page' );
	$comments_per_page = get_option( 'comments_per_page' );
	
	$js_object = array(
		/* General */
		'templateUrl'			=> GRAPHENE_ROOTURI,
		'isSingular'			=> is_singular(),
		
		/* Comments */
		'shouldShowComments'	=> graphene_should_show_comments(),
		'commentsOrder'			=> get_option( 'default_comments_page' ),
		
		/* Slider */
		'sliderDisable'			=> $graphene_settings['slider_disable'],
		'sliderInterval'		=> $graphene_settings['slider_speed'],
		
		/* Infinite Scroll */
		'infScrollBtnLbl'		=> __( 'Load more', 'graphene' ),
		'infScrollOn'			=> $graphene_settings['inf_scroll_enable'],
		'infScrollCommentsOn'	=> $graphene_settings['inf_scroll_comments'],
		'totalPosts'			=> $wp_query->found_posts,
		'postsPerPage'			=> $posts_per_page,
		'isPageNavi'			=> function_exists( 'wp_pagenavi' ),
		'infScrollMsgText'		=> sprintf( 
										__( 'Fetching %1$s of %2$s items left ...', 'graphene' ),
										'window.grapheneInfScrollItemsPerPage', 
										'window.grapheneInfScrollItemsLeft' ),
		'infScrollMsgTextPlural'=> sprintf( 
										_n( 'Fetching %1$s of %2$s item left ...', 
											'Fetching %1$s of %2$s items left ...', 
											$posts_per_page, 'graphene' ), 
										'window.grapheneInfScrollItemsPerPage', 
										'window.grapheneInfScrollItemsLeft' ),
		'infScrollFinishedText'	=> __( 'All loaded!', 'graphene' ),
		'commentsPerPage'		=> $comments_per_page,
		'totalComments'			=> graphene_get_comment_count( 'comments', true, true ),
		'infScrollCommentsMsg'	=> sprintf( 
										__( 'Fetching %1$s of %2$s comments left ...', 'graphene' ), 
										'window.grapheneInfScrollCommentsPerPage', 
										'window.grapheneInfScrollCommentsLeft' ),
		'infScrollCommentsMsgPlural'=> sprintf( 
										_n( 'Fetching %1$s of %2$s comments left ...', 
											'Fetching %1$s of %2$s comments left ...', 
											$comments_per_page, 'graphene' ), 
										'window.grapheneInfScrollCommentsPerPage', 
										'window.grapheneInfScrollCommentsLeft' ),
		'infScrollCommentsFinishedMsg'	=> __( 'All comments loaded!', 'graphene' ),
	);
	wp_localize_script( 'graphene', 'grapheneJS', apply_filters( 'graphene_js_object', $js_object ) );
}
add_action( 'wp_enqueue_scripts', 'graphene_localize_scripts' );


/**
 * Generate the stylesheet link for Google Fonts
 */
function graphene_google_fonts_uri(){
	$query_args = array(
		'family' => 'Lato:400,400i,700,700i',
		'subset' => 'latin',
	);
	return add_query_arg( apply_filters( 'graphene_google_fonts', $query_args ), "//fonts.googleapis.com/css" );
}


/**
 * Ensure correct ordering of stylesheets when using a child theme
 * @since Graphene 2.0.3
 */
function graphene_child_stylesheets_order(){
	global $wp_styles;
	if ( ! $wp_styles->registered ) return;

	$child_stylesheet = get_stylesheet_uri();
	if ( $child_stylesheet == GRAPHENE_ROOTURI . '/style.css' ) return;

	$parent_theme = wp_get_theme( basename( GRAPHENE_ROOTDIR ) );
	$parent_stylesheet = basename( GRAPHENE_ROOTURI ) . '/style.css';

	foreach ( $wp_styles->registered as $handle => $script ) {
		if ( stripos( $script->src, $parent_stylesheet ) !== false ) {
			$wp_styles->registered[$handle]->deps = array_merge( $script->deps, array( 'bootstrap', 'font-awesome' ) );
			$wp_styles->registered[$handle]->ver = $parent_theme->Version;
			$parent_handle = $handle;
		}

		if ( $script->src === $child_stylesheet ) $child_handles[] = $handle;
	}

	foreach ( $child_handles as $handle ){
		if ( count( $child_handles ) > 1 && $handle === 'graphene' ) {
			unset( $wp_styles->registered['graphene'] );
			continue;
		}

		if ( isset( $parent_handle ) ) {
			$wp_styles->registered[$handle]->deps[] = $parent_handle;
			$wp_styles->registered[$handle]->deps = array_unique( $wp_styles->registered[$handle]->deps );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'graphene_child_stylesheets_order', 100 );