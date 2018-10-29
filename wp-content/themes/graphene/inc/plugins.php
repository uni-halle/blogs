<?php 
/**
 * Add breadcrumbs to the top of the content area. Uses the Breadcrumb NavXT plugin
*/
function graphene_breadcrumb_navxt( $in_custom_layout = false ){
	if ( ! function_exists( 'bcn_display' ) ) {
		if ( current_user_can( 'manage_options' ) && $in_custom_layout ) { ?>
        	<p class="alert alert-warning"><?php printf( __( '<strong>NOTICE:</strong> Please install and activate %s in order to use the Breadcrumbs feature.', 'graphene' ), '<a target="_blank" href="' . admin_url( 'plugin-install.php?s=breadcrumb-navxt&tab=search&type=term' ) . '">Breadcrumb NavXT</a>' ); ?></p>
        <?php }	return;
	}

	if ( graphene_has_custom_layout() && ! $in_custom_layout ) return;
	else {
		if ( ! is_singular() && ! is_archive() && ! is_search() ) return;
		if ( is_front_page() || is_author() ) return;
	}
	?>
	<div class="breadcrumb breadcrumb-navxt breadcrumbs-wrapper row">
        <div class="container breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#"><?php bcn_display(); ?></div>
    </div>
    <?php
}
add_action( 'graphene_before_content', 'graphene_breadcrumb_navxt' );


/**
 * Add 'nodate' class for bbPress user home
*/
if ( class_exists( 'bbPress' ) ) :
	function graphene_bbpress_post_class( $classes ){
		if ( bbp_is_user_home() )
			$classes[] = 'nodate';
			
		return $classes;
	}
	add_filter( 'post_class', 'graphene_bbpress_post_class' );
endif;


/* WP e-Commerce compat stuffs */
if ( function_exists( 'is_products_page' ) ) :

/**
 * Disable child page listing for Products page
 */
function graphene_wpsc_disable_child(){
	if ( ! is_products_page() ) return;
	global $graphene_settings;
	$graphene_settings['child_page_listing'] = 'hide';
}
add_action( 'wp_head', 'graphene_wpsc_disable_child' );
 
endif;


/**
 * Dequeue YARPP's CSS
 */
function graphene_dequeue_yarpp_css(){
	if ( function_exists( 'yarpp_related' ) ) {
		global $graphene_settings;
		if ( $graphene_settings['disable_yarpp_template'] ) return;

		wp_dequeue_style( 'yarppRelatedCss' );
	}
}
add_action( 'wp_footer', 'graphene_dequeue_yarpp_css', 5 );


/**
 * Remove YARPP's auto-display related content from post content
 */
function graphene_remove_yarpp_auto_content(){
	if ( function_exists( 'yarpp_related' ) ) {
		global $graphene_settings;
		if ( $graphene_settings['disable_yarpp_template'] ) return;

		graphene_remove_anonymous_object_filter( 'the_content', 'YARPP', 'the_content' ); 
	}
}
add_action( 'template_redirect', 'graphene_remove_yarpp_auto_content' );


if ( ! function_exists( 'graphene_related_posts' ) ) :
/**
 * Manages the display of related posts
 */
function graphene_related_posts( $in_custom_layout = false ){
	global $graphene_settings;
	
	/* Display a notice if YARPP has not been installed */
	if ( ! function_exists( 'yarpp_related' ) ) { 
		if ( current_user_can( 'manage_options' ) && $in_custom_layout ) { ?>
		<p class="alert alert-warning"><?php printf( __( '<strong>NOTICE:</strong> Please install and activate %s in order to use the Related Posts feature.', 'graphene' ), '<a target="_blank" href="' . admin_url( 'plugin-install.php?s=yarpp&tab=search&type=term' ) . '">Yet Another Related Posts (YARPP)</a>' ); ?></p>
		<?php } 
		return;
	}

	if ( $graphene_settings['disable_yarpp_template'] ) return;
	
	$yarpp_options = get_option( 'yarpp', array() );
	if ( $yarpp_options ) {
		$display_settings = $yarpp_options['auto_display_post_types'];
		if ( ! is_singular() || ! in_array( get_post_type(), $display_settings ) ) return;
	}
	
	$args = array(
		'template'	=> 'yarpp-template-single.php',
		'limit'		=> 3,
	);
	yarpp_related( apply_filters( 'graphene_yarpp_args', $args ) );
}
endif;