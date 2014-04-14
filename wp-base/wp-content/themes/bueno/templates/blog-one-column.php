<?php
/**
 * The template for displaying posts into single column on blog page.
 *
 * @package bueno
 *
 */
?>
<?php 
$bueno_post_iterator = 0;
$bueno_posts_per_page = get_option( 'posts_per_page' );
$bueno_sticky_number = sizeof( get_option( 'sticky_posts' ) );

while (have_posts()) : the_post(); 
	global $paged, $wp_query;
	if ( 1 == $wp_query->max_num_pages ) {
		$bueno_last_page_posts = $wp_query->found_posts;
	} else {
		$bueno_last_page_posts = $wp_query->found_posts - ($wp_query->max_num_pages-1)*$bueno_posts_per_page;
	}
	$bueno_post_iterator++;
	$bueno_custom_class = "";
	if ( 0 == $paged && 1 == $wp_query->max_num_pages ) {
		if ( $bueno_post_iterator == $bueno_last_page_posts ) $bueno_custom_class = " last_item";
	} elseif ( 0 == $paged && 1 < $wp_query->max_num_pages && is_home()  ) {
		if ( $bueno_post_iterator == $bueno_posts_per_page + $bueno_sticky_number ) $bueno_custom_class = " last_item";
	}  elseif ( $wp_query->max_num_pages == $paged ) {
		if ( $bueno_post_iterator == $bueno_last_page_posts ) $bueno_custom_class = " last_item";
	} else {
		if ( $bueno_post_iterator == $bueno_posts_per_page ) $bueno_custom_class = " last_item";
	}
?>
<div class="one_column_item<?php echo $bueno_custom_class; ?>">
	<?php
		// The following determines what the post format is and shows the correct file accordingly
		$bueno_format = get_post_format();
		get_template_part( 'templates/format-'.$bueno_format );					
		if($bueno_format == '')
		get_template_part( 'templates/format-standard' );		
	?>
</div><?php endwhile; ?>