<?php
/**
 * Functions to flush caches appropriately
 */

/* Clearing Theme Option Cache
 * @uses delete_transient
 */
function catchbox_flush_transients() {
	delete_transient( 'catchbox_sliders' ); // Featured Slider
	delete_transient( 'catchbox_socialprofile' ); //Social Profile
    delete_transient( 'catchbox_footer_content' ); //Footer Content
}


/* Clearing Theme Option Cache
 * @uses delete_transient and action publish_post
 */
function catchbox_posts_invalidate_caches() {
	delete_transient( 'catchbox_sliders' ); // Featured Slider
}
add_action( 'publish_post', 'catchbox_posts_invalidate_caches' ); // publish posts runs whenever posts are published or published posts are edited


/* Clearing Site Detail Cache
 * @uses delete_transient
 */
function catchbox_sitedetails_invalidate_caches() {
	delete_transient( 'catchbox_footer_content' ); //Footer Content
}
add_action( 'update_option_blogname', 'catchbox_sitedetails_invalidate_caches' );
add_action( 'update_option_blogdescription', 'catchbox_sitedetails_invalidate_caches' );
add_action( 'customize_preview_init', 'catchbox_sitedetails_invalidate_caches' );