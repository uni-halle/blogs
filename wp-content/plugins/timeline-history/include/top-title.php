<?php
if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly!');
}
$args = array( 'post_type' => 'history_post', 'posts_per_page' => -1, 'meta_key' => 'history-date',
            'orderby' => 'meta_value', 'order' => 'ASC');
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
	$history_top_title = esc_html(get_post_meta( get_the_ID(), 'history_top_title', true ));
	$history_date = esc_html(get_post_meta( get_the_ID(), 'history-date', true ));

	$date = date_create($history_date);
    $date_formate = date_format($date,"Y/m/d");
    $history_date_value = date("d/m/Y", strtotime($date_formate));
	$content .='<li><a href="#0" data-date="'.esc_attr($history_date_value).'">'.esc_attr($history_top_title).'</a></li>';
endwhile; ?>