<?php
/**
 * The template for displaying posts into 2 columns on blog page.
 *
 * @package bueno
 *
 */
?>
<div class="row two_column_wrapper" data-resize="false"><?php
	$bueno_post_iterator = 0;
	$bueno_col_output = "";
	$bueno_posts_per_page = get_option( 'posts_per_page' );
	$bueno_sticky_number = sizeof( get_option( 'sticky_posts' ) );
?>
	<div class="span4 column_1">
<?php
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
		if ( $bueno_post_iterator == $bueno_last_page_posts || $bueno_post_iterator == $bueno_last_page_posts - 1 ) $bueno_custom_class = " last_item";
	} elseif ( 0 == $paged && 1 < $wp_query->max_num_pages && is_home() ) {
		if ( $bueno_post_iterator == $bueno_posts_per_page + $bueno_sticky_number || $bueno_post_iterator == $bueno_posts_per_page + $bueno_sticky_number - 1 ) $bueno_custom_class = " last_item";
	}  elseif ( $wp_query->max_num_pages == $paged ) {
		if ( $bueno_post_iterator == $bueno_last_page_posts || $bueno_post_iterator == $bueno_last_page_posts - 1 ) $bueno_custom_class = " last_item";
	} else {
		if ( $bueno_post_iterator == $bueno_posts_per_page || $bueno_post_iterator == $bueno_posts_per_page - 1 ) $bueno_custom_class = " last_item";
	}
	if ( 0 == $bueno_post_iterator%2 ) ob_start();
?>
	<div class="two_column_item<?php echo $bueno_custom_class; ?>" data-count="<?php echo $bueno_post_iterator; ?>">
		<?php
			// The following determines what the post format is and shows the correct file accordingly
			$bueno_format = get_post_format();
			get_template_part( 'templates/format-'.$bueno_format );					
			if($bueno_format == '')
			get_template_part( 'templates/format-standard' );		
		?>
	</div>
<?php
	
	if ( 0 == $bueno_post_iterator%2 ) {
		$bueno_col_output .= ob_get_contents();
		ob_end_clean();
	}
	
	endwhile;
?></div>
  <div class="span4 column_2">
  	<?php echo $bueno_col_output; ?>
  </div>
</div>