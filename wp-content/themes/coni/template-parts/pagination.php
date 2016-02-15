<div class="clearfix"></div>
<?php

$temp_query = $wp_query;
global $the_query;//The query from the portfolio

if ( isset( $the_query ) ) {
	$wp_query = $the_query;
}
$pagination = get_the_posts_pagination( array(
    'prev_text'          => esc_attr__( 'Previous page', 'coni' ),
	'next_text'          => esc_attr__( 'Next page', 'coni' )
) );
if ($pagination) {
	echo '<div class="pagination_wrap">';
	echo wp_kses_post( $pagination );
	echo '</div><!-- /pagination_wrap -->';
}
$wp_query = $temp_query;
wp_reset_postdata();
?>