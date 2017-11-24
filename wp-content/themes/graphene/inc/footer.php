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