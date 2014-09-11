<?php
$now = gmdate("Y-m-d H:i:s",time());
$lastmonth = gmdate("Y-m-d H:i:s",gmmktime(date("H"), date("i"), date("s"), date("m")-12,date("d"),date("Y")));
$popularposts = "SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS 'stammy' FROM $wpdb->posts, $wpdb->comments WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish' AND post_date < '$now' AND post_date > '$lastmonth' AND comment_status = 'open' GROUP BY $wpdb->comments.comment_post_ID ORDER BY stammy DESC LIMIT 10";
$posts = $wpdb->get_results($popularposts);
$popular = '';
if($posts){
foreach($posts as $post){
$post_title = stripslashes($post->post_title);
$guid = get_permalink($post->ID);
$popular .= '<li><a href="'.$guid.'" title="'.$post_title.'">'.$post_title.'</a></li>';
}
}echo $popular;
?>