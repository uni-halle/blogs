<?php
if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly!');
}
//Add NEW COLUMN in Custom Post Type List eg. History Date

add_filter( 'manage_edit-history_post_columns', 'kt_my_edit_history_post_columns' ) ;
function kt_my_edit_history_post_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'History Post Title' ),
		'history_date' => __( 'History Date</br>(MM/DD/YYYY)' ),
		'top_title' => __( 'History Top Tab Title' ),
		'date' => __( 'Publish Date' )
	);

	return $columns;
}

add_action( 'manage_history_post_posts_custom_column', 'kt_my_manage_history_post_columns', 10, 2 );
function kt_my_manage_history_post_columns($column_name, $post_ID) {
    if ($column_name == 'history_date') {
        $history_date = get_post_meta( get_the_ID(), 'history-date', true );
		$date = date_create($history_date);
	    $date_formate = date_format($date,"Y/m/d");
	    $history_date_value = date("m/d/Y", strtotime($date_formate));
	    echo "$history_date_value";
    }
    if ($column_name == 'top_title') {
    	$history_top_title = esc_html(get_post_meta( get_the_ID(), 'history_top_title', true ));
	    echo "$history_top_title";
    }
}
/*End Custom Post Type*/ 
?>