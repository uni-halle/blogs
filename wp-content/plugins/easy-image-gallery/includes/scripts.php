<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Scripts
 *
 * @since 1.0
 */
function easy_image_gallery_scripts() {

	global $post;

	// return if post object is not set
	if ( !isset( $post->ID ) )
		return;


	// JS
	wp_register_script( 'pretty-photo', EASY_IMAGE_GALLERY_URL . 'includes/lib/prettyphoto/jquery.prettyPhoto.js', array( 'jquery' ), EASY_IMAGE_GALLERY_VERSION, true );
	wp_register_script( 'fancybox', EASY_IMAGE_GALLERY_URL . 'includes/lib/fancybox/jquery.fancybox-1.3.4.pack.js', array( 'jquery' ), EASY_IMAGE_GALLERY_VERSION, true );

	// CSS
	wp_register_style( 'pretty-photo', EASY_IMAGE_GALLERY_URL . 'includes/lib/prettyphoto/prettyPhoto.css', '', EASY_IMAGE_GALLERY_VERSION, 'screen' );
	wp_register_style( 'fancybox', EASY_IMAGE_GALLERY_URL . 'includes/lib/fancybox/jquery.fancybox-1.3.4.css', '', EASY_IMAGE_GALLERY_VERSION, 'screen' );

	// create a new 'css/easy-image-gallery.css' in your child theme to override CSS file completely
	if ( file_exists( get_stylesheet_directory() . '/css/easy-image-gallery.css' ) )
		wp_register_style( 'easy-image-gallery', get_stylesheet_directory_uri() . '/css/easy-image-gallery.css', '', EASY_IMAGE_GALLERY_VERSION, 'screen' );
	else
		wp_register_style( 'easy-image-gallery', EASY_IMAGE_GALLERY_URL . 'includes/css/easy-image-gallery.css', '', EASY_IMAGE_GALLERY_VERSION, 'screen' );

	// post type is not allowed, return
	if ( ! easy_image_gallery_allowed_post_type() )
		return;

	// needs to load only when there is a gallery
	if ( easy_image_gallery_is_gallery() )
		wp_enqueue_style( 'easy-image-gallery' );

	$linked_images = true;

	// only load the JS if gallery images are linked or the featured image is linked
	if ( $linked_images ) {

		$lightbox = easy_image_gallery_get_lightbox();

		switch ( $lightbox ) {
				
				case 'prettyphoto':
					
					// CSS
					wp_enqueue_style( 'pretty-photo' );

					// JS
					wp_enqueue_script( 'pretty-photo' );

				break;
				
				case 'fancybox':

					// CSS
					wp_enqueue_style( 'fancybox' );

					// JS
					wp_enqueue_script( 'fancybox' );

				break;

				default:
					

					break;
			}

		// allow developers to load their own scripts here
		do_action( 'easy_image_gallery_scripts' );

	}

}
add_action( 'wp_enqueue_scripts', 'easy_image_gallery_scripts', 20 );




/**
 * JS
 *
 * @since 1.0
 */
function easy_image_gallery_js() {

	if ( ! easy_image_gallery_allowed_post_type() || ! easy_image_gallery_is_gallery() )
		return;

	if ( is_singular() ) : ?>

		<?php

			$lightbox = easy_image_gallery_get_lightbox();

			switch ( $lightbox ) {
				
				case 'prettyphoto': ob_start(); ?>
					
					<script>
					  jQuery(document).ready(function() {
					    jQuery("a[rel^='prettyPhoto']").prettyPhoto({
					    	social_tools : false,
					    	show_title : false
					    });
					  });
					</script>

					<?php 
						$js = ob_get_clean();
						echo apply_filters( 'easy_image_gallery_prettyphoto_js', $js );
					?>

				<?php break;
				
				case 'fancybox': ob_start(); ?>

					<script>
						jQuery(document).ready(function() {

							jQuery("a.popup").attr('rel', 'fancybox').fancybox({
									'transitionIn'	:	'elastic',
									'transitionOut'	:	'elastic',
									'speedIn'		:	200, 
									'speedOut'		:	200, 
									'overlayShow'	:	false
								});
						});
					</script>

					<?php 
						$js = ob_get_clean();
						echo apply_filters( 'easy_image_gallery_fancybox_js', $js );
					?>

				<?php break;


				default:
					
					break;
			}

			// allow developers to add/modify JS 
			do_action( 'easy_image_gallery_js', $lightbox );
		?>

    <?php endif; ?>

<?php }
add_action( 'wp_footer', 'easy_image_gallery_js', 20 );


function easy_image_gallery_admin_scripts() {
    wp_enqueue_script( 'repeatable-fields', EASY_IMAGE_GALLERY_URL . 'includes/lib/repeatable-fields.js', array('jquery', 'jquery-ui-core') );
    wp_enqueue_style( 'easy_image_gallery_admin_css', EASY_IMAGE_GALLERY_URL . 'includes/css/easy-image-gallery-admin.css' );
}

add_action( 'admin_head', 'easy_image_gallery_admin_scripts' );