<?php
/**
 * Generate the Google Rich Snippet LD+JSON script
 */
function graphene_rich_snippet(){
	if ( ! is_singular() ) return;
	global $post, $graphene_in_rich_snippet;
	$graphene_in_rich_snippet = true;

	$metadata = array(
		'@context'         	=> 'http://schema.org',
		'@type'            	=> is_page() ? 'WebPage' : 'Article',
		'mainEntityOfPage' 	=> get_permalink( $post->ID ),
		'publisher'        	=> array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'blog_name' ),
		),
		'headline'         	=> get_the_title( $post->ID ),
		'datePublished'    	=> date( 'c', get_the_time( 'U', $post->ID ) ),
		'dateModified'     	=> date( 'c', get_post_modified_time( 'U', false, $post ) ),
	);

	$excerpt = apply_filters( 'the_excerpt', $post->post_excerpt );
	if ( ! $excerpt ) {
		$excerpt = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $post->post_content);
		$excerpt = wp_trim_words( strip_shortcodes( $excerpt ), 55, ' ...' );
	}
	$metadata['description'] = $excerpt;

	$post_author = get_userdata( $post->post_author );
	if ( $post_author ) {
		$metadata['author'] = array(
			'@type' => 'Person',
			'name'  => html_entity_decode( $post_author->display_name, ENT_QUOTES, get_bloginfo( 'charset' ) ),
		);
	}

	if ( has_site_icon() ) {
		$site_icon_url = get_site_icon_url( 32 );
		$metadata['publisher']['logo'] = array(
			'@type'  => 'ImageObject',
			'url'    => $site_icon_url,
			'height' => 32,
			'width'  => 32,
		);
	}

	$post_images = array(
		'1x1'	=> graphene_get_best_post_image( $post->ID, array( 696, 696 ) ),
		'4x3'	=> graphene_get_best_post_image( $post->ID, array( 696, 522 ) ),
		'16x9'	=> graphene_get_best_post_image( $post->ID, array( 696, 392 ) )
	);

	$images = array();
	foreach ( $post_images as $aspect_ratio => $image )	if ( $image ) $images[] = $image['url'];
	$images = array_unique( $images );

	if ( $images ) $metadata['image'] = $images;

	$metadata = apply_filters( 'graphene_rich_snippet', $metadata );
	$graphene_in_rich_snippet = false;
	if ( ! $metadata ) return;
	?>
		<script type="application/ld+json"><?php echo wp_json_encode( $metadata ); ?></script>
	<?php
}
add_action( 'wp_head', 'graphene_rich_snippet' );


/**
 * Generate the Open Graph metadata
 */
function graphene_open_graph(){
	if ( graphene_is_og_plugins_available() ) return;

	add_filter( 'language_attributes', 'graphene_add_og_namespace', 15 );
	add_action( 'wp_head', 'graphene_print_open_graph' );
}
add_action( 'template_redirect', 'graphene_open_graph' );


/**
 * Add the Open Graph tags to the <head> element
 */
function graphene_print_open_graph(){
	if ( ! is_singular() ) return;
	global $post;

	$open_graph = array(
		'og:type'			=> 'article',
		'og:title'			=> get_the_title( $post->ID ),
		'og:url'			=> get_permalink( $post->ID ),
		'og:site_name'		=> get_bloginfo( 'blog_name' ),
	);

	$excerpt = apply_filters( 'the_excerpt', $post->post_excerpt );
	if ( ! $excerpt ) {
		$excerpt = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $post->post_content);
		$excerpt = wp_trim_words( strip_shortcodes( $excerpt ), 55, ' ...' );
	}
	$open_graph['og:description'] = $excerpt;

	$published_time = date( 'c', get_the_time( 'U', $post->ID ) );
	$updated_time = date( 'c', get_post_modified_time( 'U', false, $post ) );

	$open_graph['og:updated_time'] = $updated_time;
	$open_graph['article:modified_time'] = $updated_time;
	$open_graph['article:published_time'] = $published_time;

	$image = graphene_get_best_post_image( $post->ID, array( 1200, 630 ) );
	if ( $image ) {
		$open_graph['og:image'] = $image['url'];
		$open_graph['og:image:width'] = $image['width'];
		$open_graph['og:image:height'] = $image['height'];
	}


	$open_graph = apply_filters( 'graphene_open_graph', $open_graph, $post );

	foreach ( $open_graph as $property => $content ) {
		echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
	}
}


/**
 * Check if there are other plugins generating Open Graph metadata
 */
function graphene_is_og_plugins_available(){
	$plugins = array(
		'2-click-socialmedia-buttons/2-click-socialmedia-buttons.php',
		// 2 Click Social Media Buttons.
		'add-link-to-facebook/add-link-to-facebook.php',         // Add Link to Facebook.
		'add-meta-tags/add-meta-tags.php',                       // Add Meta Tags.
		'easy-facebook-share-thumbnails/esft.php',               // Easy Facebook Share Thumbnail.
		'facebook/facebook.php',                                 // Facebook (official plugin).
		'facebook-awd/AWD_facebook.php',                         // Facebook AWD All in one.
		'facebook-featured-image-and-open-graph-meta-tags/fb-featured-image.php',
		// Facebook Featured Image & OG Meta Tags.
		'facebook-meta-tags/facebook-metatags.php',              // Facebook Meta Tags.
		'wonderm00ns-simple-facebook-open-graph-tags/wonderm00n-open-graph.php',
		// Facebook Open Graph Meta Tags for WordPress.
		'facebook-revised-open-graph-meta-tag/index.php',        // Facebook Revised Open Graph Meta Tag.
		'facebook-thumb-fixer/_facebook-thumb-fixer.php',        // Facebook Thumb Fixer.
		'facebook-and-digg-thumbnail-generator/facebook-and-digg-thumbnail-generator.php',
		// Fedmich's Facebook Open Graph Meta.
		'network-publisher/networkpub.php',                      // Network Publisher.
		'nextgen-facebook/nextgen-facebook.php',                 // NextGEN Facebook OG.
		'opengraph/opengraph.php',                               // Open Graph.
		'open-graph-protocol-framework/open-graph-protocol-framework.php',
		// Open Graph Protocol Framework.
		'seo-facebook-comments/seofacebook.php',                 // SEO Facebook Comments.
		'seo-ultimate/seo-ultimate.php',                         // SEO Ultimate.
		'sexybookmarks/sexy-bookmarks.php',                      // Shareaholic.
		'shareaholic/sexy-bookmarks.php',                        // Shareaholic.
		'sharepress/sharepress.php',                             // SharePress.
		'simple-facebook-connect/sfc.php',                       // Simple Facebook Connect.
		'social-discussions/social-discussions.php',             // Social Discussions.
		'social-sharing-toolkit/social_sharing_toolkit.php',     // Social Sharing Toolkit.
		'socialize/socialize.php',                               // Socialize.
		'only-tweet-like-share-and-google-1/tweet-like-plusone.php',
		// Tweet, Like, Google +1 and Share.
		'wordbooker/wordbooker.php',                             // Wordbooker.
		'wpsso/wpsso.php',                                       // WordPress Social Sharing Optimization.
		'wp-caregiver/wp-caregiver.php',                         // WP Caregiver.
		'wp-facebook-like-send-open-graph-meta/wp-facebook-like-send-open-graph-meta.php',
		// WP Facebook Like Send & Open Graph Meta.
		'wp-facebook-open-graph-protocol/wp-facebook-ogp.php',   // WP Facebook Open Graph protocol.
		'wp-ogp/wp-ogp.php',                                     // WP-OGP.
		'zoltonorg-social-plugin/zosp.php',                      // Zolton.org Social Plugin.

		'jetpack/jetpack.php',										// Jetpack
		'wordpress-seo/wp-seo.php',									// WordPress SEO
	);

	$active_plugins = get_option( 'active_plugins' );
	
	foreach ( $plugins as $plugin ) {
		if ( in_array( $plugin, $active_plugins ) ) return true;
	}

	return false;
}


/**
 * Add Open Graph namespace
 */
function graphene_add_og_namespace( $input ) {
	$namespace = 'og: http://ogp.me/ns#';

	if ( strpos( $input, ' prefix=' ) !== false ) {
		$regex   = '`prefix=([\'"])(.+?)\1`';
		$replace = 'prefix="$2 ' . $namespace . '"';
		$input   = preg_replace( $regex, $replace, $input );
	}
	else {
		$input .= ' prefix="' . $namespace . '"';
	}

	return $input;
}