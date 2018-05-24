<?php
/**
 * Template functions
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

function easy_image_gallery_db_version(){
	$old_meta_structure = get_post_meta(get_the_ID(), '_easy_image_gallery', true);
	$new_meta_structure = get_post_meta(get_the_ID(), '_easy_image_gallery_v2', true);

	if (isset($new_meta_structure) && $new_meta_structure != null) {
		return "new";
	}elseif( isset($old_meta_structure) && $old_meta_structure != null ) {
		return "old";
	}

	return;
}

/**
 * Check the POST META
*/
function easy_image_gallery_get_post_meta(){
	$db_version = easy_image_gallery_db_version();
	if ( $db_version == "new" ) {
		$gallery_ids = get_post_meta(get_the_ID(), '_easy_image_gallery_v2', true);
		return $gallery_ids;
	} elseif ( $db_version == "old" ) {
		$get_gallery_attachments = get_post_meta(get_the_ID(), '_easy_image_gallery', true);
		$get_gallery_old_data = explode(",", $get_gallery_attachments);

		$get_open_images = get_post_meta(get_the_ID(), '_easy_image_gallery_link_images');
		if ( isset($get_open_images) && !empty($get_open_images) ){
			$get_open_images = $get_open_images;
		}else{
			$get_open_images = null;
		}

		$gallery_ids = array(array(
			"SHORTCODE" => rand(100, 999),
			"DATA" => $get_gallery_old_data,
			"OPEN_IMAGES" => $get_open_images[0],
		));

		return $gallery_ids;
	}

	return;
}
/**
 * Is gallery
 *
 * @since 1.0
 * @return boolean
 */
function easy_image_gallery_is_gallery() {
	$gallery_ids = easy_image_gallery_get_post_meta();
	if ( $gallery_ids ) {
		return true;
	}

	return false;
}

/**
 * Get page images ids
 *
 * @since 1.3
 * @return array
 */
function easy_image_gallery_get_image_ids( $post_id = null, $all_galleries_images = true, $gallery_id = null ) {
	global $post;

    if ( $post_id == null ) {
        $post_id = $post->ID;
    }

	if( ! isset( $post_id ) || $post_id == null ) return;

	$db_version = easy_image_gallery_db_version();
	if ( $db_version == "new" ) {

		$new_db_structure = get_post_meta(get_the_ID(), '_easy_image_gallery_v2', true);
		if ( $all_galleries_images == true ){

			$images_ids = array();
			if( isset( $new_db_structure ) && !empty( $new_db_structure ) ){
				foreach( $new_db_structure as $gallery ){
					foreach( $gallery['DATA'] as $image ){
						$images_ids[] = $image;
					}
				}
			}

			return $images_ids;

		}elseif( $all_galleries_images == false ){

			if ( isset( $gallery_id ) && !empty( $gallery_id ) ){

				if( isset( $new_db_structure ) && !empty( $new_db_structure ) ){
					foreach( $new_db_structure as $gallery ){
						if( $gallery['SHORTCODE'] == $gallery_id ){
							return $gallery['DATA'];
						}
					}
				}

			}

        }

	}elseif ( $db_version == "old" ) {
		$old_db_structure = get_post_meta(get_the_ID(), '_easy_image_gallery', true);
		$attachment_ids = explode( ',', $old_db_structure );

		return array_filter( $attachment_ids );
	}

	return;
}

/**
 * Check the current post for the existence of a short code
 *
 * @since 1.0
 * @return boolean
 */
function easy_image_gallery_has_shortcode( $shortcode = '' ) {
	global $post;

	// false because we have to search through the post content first
	$found = false;

	// if no short code was provided, return false
	if ( !$shortcode ) {
		return $found;
	}

	if (  is_object( $post ) && stripos( $post->post_content, '[' . $shortcode ) !== false ) {
		// we have found the short code
		$found = true;
	}

	// return our final results
	return $found;
}


/**
 * Setup Lightbox array
 *
 * @since 1.0
 * @return array
 */
function easy_image_gallery_lightbox() {

	$lightboxes = array(
		'fancybox' => __( 'fancyBox', 'easy-image-gallery' ),
		'prettyphoto' => __( 'prettyPhoto', 'easy-image-gallery' ),
	);

	return apply_filters( 'easy_image_gallery_lightbox', $lightboxes );

}

/**
 * Get lightbox from settings
 *
 * @since 1.0
 * @return string
 */

if ( !function_exists( 'easy_image_gallery_get_lightbox' ) ) :
	function easy_image_gallery_get_lightbox() {

		$settings = (array) get_option( 'easy-image-gallery' );

		// set fancybox as default for when the settings page hasn't been saved
		$lightbox = isset( $settings['lightbox'] ) ? esc_attr( $settings['lightbox'] ) : 'prettyphoto';

		return $lightbox;

	}
endif;


/**
 * Returns the correct rel attribute for the anchor links
 *
 * @since 1.0
 * @return string
 */

function easy_image_gallery_lightbox_rel() {

	$lightbox = easy_image_gallery_get_lightbox();

	switch ( $lightbox ) {

	case 'prettyphoto':

		$rel = 'prettyPhoto';

		break;

	case 'fancybox':

		$rel = 'fancybox';

	default:

		$rel = 'prettyPhoto';

		break;
	}

	return $rel;
}

/**
 * Get list of post types for populating the checkboxes on the admin page
 *
 * @since 1.0
 * @return array
 */
function easy_image_gallery_get_post_types() {

	$args = array(
		'public' => true
	);

	$post_types = array_map( 'ucfirst', get_post_types( $args ) );

	// remove attachment
	unset( $post_types[ 'attachment' ] );

	return apply_filters( 'easy_image_gallery_get_post_types', $post_types );

}

/**
 * Retrieve the allowed post types from the option row
 * Defaults to post and page when the settings have not been saved
 *
 * @return array
 * @since 1.0
*/
function easy_image_gallery_allowed_post_types() {
	
	$defaults['post_types']['post'] = 'on';
	$defaults['post_types']['page'] = 'on';

	// get the allowed post type from the DB
	$settings = ( array ) get_option( 'easy-image-gallery', $defaults );
	$post_types = isset( $settings['post_types'] ) ? $settings['post_types'] : '';

	// post types don't exist, bail
	if ( ! $post_types )
		return;

	return $post_types;

}


/**
 * Is the currently viewed post type allowed?
 * For use on the front-end when loading scripts etc
 *
 * @since 1.0
 * @return boolean
 */
function easy_image_gallery_allowed_post_type() {

	// post and page defaults
	$defaults['post_types']['post'] = 'on';
	$defaults['post_types']['page'] = 'on';

	// get currently viewed post type
	$post_type = ( string ) get_post_type();

	//echo $post_type; exit; // download

	// get the allowed post type from the DB
	$settings = ( array ) get_option( 'easy-image-gallery', $defaults );
	$post_types = isset( $settings['post_types'] ) ? $settings['post_types'] : '';

	// post types don't exist, bail
	if ( ! $post_types )
		return;

	// check the two against each other
	if ( array_key_exists( $post_type, $post_types ) )
		return true;
}


/**
 * Retrieve attachment IDs
 *
 * @since 1.0
 * @return string
 */
function easy_image_gallery_get_galleries() {
	global $post;

	if( !isset( $post->ID) ){
		return;
	}

	$gallery_ids = easy_image_gallery_get_post_meta();

	return $gallery_ids;
}


/**
 * Shortcode
 *
 * @since 1.0
 */

function easy_image_gallery_shortcode( $atts ) {

	// return early if the post type is not allowed to have a gallery
	if ( !easy_image_gallery_allowed_post_type() ){
        return;
    }else{
        if ( isset($atts['gallery']) && !empty($atts['gallery']) ){
            return easy_image_gallery( $atts['gallery'] );
        }else{
	        return easy_image_gallery( 'old_db' );
        }
    }
}
add_shortcode( 'easy_image_gallery', 'easy_image_gallery_shortcode' );


/**
 * Count number of images in array
 *
 * @since 1.0
 * @return integer
 */
function easy_image_gallery_count_images( $gallery_shortcode ) {

	$galleries = easy_image_gallery_get_post_meta();

	if ( isset($galleries) && !empty($galleries) ) {
        foreach ( $galleries as $gallery ){
            if ( $gallery['SHORTCODE'] == $gallery_shortcode ){
                $number = count($gallery['DATA']);
                return $number;
            }
        }
    }

    return;
}


/**
 * Output gallery
 *
 * @since 1.0
 */
function easy_image_gallery( $gallery_id = 'old_db' ) {

	$galleries = easy_image_gallery_get_galleries();
    global $post;

    if ( isset($galleries) && !empty($galleries) ){
        ob_start();
        foreach ($galleries as $gallery){

            if ($gallery_id == 'old_db'){
                $gallery_id = $gallery['SHORTCODE'];
            }

            if ( $gallery['SHORTCODE'] == $gallery_id ){
                $gallery_exist = true;

                $has_gallery_images = $gallery['DATA'];

                if ( !$has_gallery_images )
                    return;

                // clean the array (remove empty values)
                $has_gallery_images = array_filter( $has_gallery_images );

                // css classes array
                $classes = array();

                // thumbnail count
                $classes[] = $has_gallery_images ? 'thumbnails-' . easy_image_gallery_count_images( $gallery['SHORTCODE'], $gallery ) : '';

                // linked images
                if ( isset($gallery['OPEN_IMAGES']) && $gallery['OPEN_IMAGES'] == 'on' ){
                    $classes[] = 'linked';
                }

                $classes = implode( ' ', $classes );
    			if ( isset($has_gallery_images) && !empty($has_gallery_images) ) {
					?>
	                <ul class="image-gallery <?php echo $classes; ?>">
                    <?php
                    	foreach ( $has_gallery_images as $attachment_id ) {
	                        $classes = array( 'popup' );

	                        // get original image
	                        $image_link	= wp_get_attachment_image_src( $attachment_id, apply_filters( 'easy_image_gallery_linked_image_size', 'large' ) );
	                        $image_link	= $image_link[0];

	                        $image = wp_get_attachment_image( $attachment_id, apply_filters( 'easy_image_gallery_thumbnail_image_size', 'thumbnail' ), '', array( 'alt' => trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ) ) );

	                        $image_caption = get_post( $attachment_id )->post_excerpt ? esc_attr( get_post( $attachment_id )->post_excerpt ) : '';

	                        $image_class = esc_attr( implode( ' ', $classes ) );

	                        $lightbox = easy_image_gallery_get_lightbox();

	                        $rel = 'rel="'. $lightbox .'[group-'.$gallery_id.']"';

	                        if ( isset($gallery['OPEN_IMAGES']) && $gallery['OPEN_IMAGES'] == 'on' )
	                            $html = sprintf( '<li><a %s href="%s" class="%s" title="%s"><i class="icon-view"></i><span class="overlay"></span>%s</a></li>', $rel, $image_link, $image_class, $image_caption, $image );
	                        else
	                            $html = sprintf( '<li>%s</li>', $image );

	                        echo apply_filters( 'easy_image_gallery_html', $html, $rel, $image_link, $image_class, $image_caption, $image, $attachment_id, $post->ID );
	                    }
                	echo '</ul>';
            	}
            }
        }

        $eig_gallery = ob_get_clean();
        return apply_filters( 'easy_image_gallery', $eig_gallery );
    }
}

/**
 * Append gallery images to page automatically
 *
 * @since 1.0
 */
function easy_image_gallery_append_to_content( $content ) {
	// if it is single page and supported post_type and page not have shortcode.
	if ( is_singular() && is_main_query() && easy_image_gallery_allowed_post_type() && !easy_image_gallery_has_shortcode('easy_image_gallery') ) {
		$new_content = easy_image_gallery( 'old_db' );
		$content .= $new_content;
	}

	return $content;
}
add_filter( 'the_content', 'easy_image_gallery_append_to_content' );

/**
 * Remove the_content filter if shortcode is detected on page
 *
 * @since 1.0
 */
function easy_image_gallery_template_redirect() {
    if ( easy_image_gallery_has_shortcode( 'easy_image_gallery' ) )
		remove_filter( 'the_content', 'easy_image_gallery_append_to_content' );
}
add_action( 'template_redirect', 'easy_image_gallery_template_redirect' );
