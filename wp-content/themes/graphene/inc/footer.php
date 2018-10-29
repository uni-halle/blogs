<?php
/**
 * Add a return to top button in the footer
 */
function graphene_return_to_top(){
	global $graphene_settings;
	if ( $graphene_settings['hide_return_top'] ) return;
	?>
		<a href="#" id="back-to-top" title="Back to top"><i class="fa fa-chevron-up"></i></a>
	<?php
}
add_action( 'wp_footer', 'graphene_return_to_top' );


/**
 * Mentions bar
 *
 * @since Graphene 2.4
 */
function graphene_mentions_bar(){
	global $graphene_settings;

	if ( graphene_has_custom_layout() ) return;
	if ( $graphene_settings['mentions_bar_display'] == 'disable' ) return;
	if ( $graphene_settings['mentions_bar_display'] == 'front-page' && ! is_front_page() ) return;
	if ( ( $graphene_settings['mentions_bar_display'] == 'pages' && ! ( is_front_page() || is_page() ) ) ) return;
	
	graphene_stack( 'mentions-bar' );
}
add_action( 'graphene_before_footer_widget_area', 'graphene_mentions_bar' );