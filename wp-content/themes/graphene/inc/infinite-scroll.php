<?php
/**
 * Add default args to posts navigation
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_inf_posts_nav_defaults( $defaults ){
	global $wp_query, $graphene_settings;
	$defaults = array_merge( $defaults, array(
		'infinite-scroll'	=> array(
			'container'		=> '.entries-wrapper',
			'nav-selector'	=> '.pagination',
			'next-selector'	=> '.pagination .next',
			'item-selector'	=> '.entries-wrapper .item-wrap',
			'total-posts'	=> $wp_query->found_posts,
			'posts-per-page'=> $wp_query->query_vars['posts_per_page'],
			'method'		=> $graphene_settings['inf_scroll_method']
		)
	) );
	
	return $defaults;
}
add_filter( 'graphene_posts_nav_defaults', 'graphene_inf_posts_nav_defaults' );


/**
 * Add default args to comments navigation
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_inf_comments_nav_defaults( $defaults ){
	global $graphene_settings;
	$args = array( 
		'infinite-scroll'	=> array(
			'container'		=> '.comments-list',
			'nav-selector'	=> '.comments-list-wrapper .pagination',
			'next-selector'	=> '.comments-list-wrapper .pagination .next',
			'item-selector'	=> '.comments-list > .comment',
			'total-posts'	=> graphene_get_comment_count( 'comments', true, true ),
			'posts-per-page'=> get_option( 'comments_per_page' ),
			'method'		=> $graphene_settings['inf_scroll_method']
		)
	);
	
	if ( get_option( 'default_comments_page' ) == 'newest' ) {
		$args['infinite-scroll']['next-selector'] = '.comments-list-wrapper .pagination .prev';
		$args['infinite-scroll']['direction'] = 'reverse';
	}
	
	$defaults = array_merge( $defaults, $args );
	
	return $defaults;
}
add_filter( 'graphene_comments_nav_defaults', 'graphene_inf_comments_nav_defaults' );


/**
 * Add default args to posts stack navigation
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_inf_posts_stack_nav_args( $args, $posts ){
	global $graphene_settings;
	$args = array_merge( $args, array(
		'infinite-scroll'	=> array(
			'container'		=> '.posts-list .row',
			'nav-selector'	=> '.posts-list .pagination',
			'next-selector'	=> '.posts-list .pagination .next',
			'item-selector'	=> '.posts-list .row .item-wrap',
			'total-posts'	=> $posts->found_posts,
			'posts-per-page'=> $posts->query_vars['posts_per_page'],
			'method'		=> $graphene_settings['inf_scroll_method']
		)
	) );
	
	return $args;
}
add_filter( 'graphene_posts_stack_nav_args', 'graphene_inf_posts_stack_nav_args', 10, 2 );


/**
 * Add the infinite scroll HTML elements
 * 
 * @package Graphene
 * @since 2.0
 */
function graphene_inf_posts_nav( $args ){
	global $graphene_settings;
	
	if ( ( $args['total'] > 1 ) && $args['infinite-scroll'] ) : 
		if ( $args['type'] == 'post' && ! $graphene_settings['inf_scroll_enable']) return;
		if ( $args['type'] == 'comment' && ! $graphene_settings['inf_scroll_comments']) return;
	?>
    	<p class="infinite-load">
        	<a href="#" class="load" <?php foreach ( $args['infinite-scroll'] as $attr => $val ) printf( 'data-%1$s="%2$s" ', $attr, $val ); ?>><?php _e( 'Load more', 'graphene' ); ?></a>
        </p>
	<?php
	endif;
}
add_action( 'graphene_posts_nav', 'graphene_inf_posts_nav' );