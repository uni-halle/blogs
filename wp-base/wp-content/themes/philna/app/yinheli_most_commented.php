<?php
function yinheli_most_commented($posts_num, $days){
$result=get_option('yinheli_most_commented');
if(empty($result) || $result['num']!=$posts_num || $result['days']!=$days)
yinheli_most_commented_cache($posts_num, $days);
$result=get_option('yinheli_most_commented');
echo $result['output'];
}
function yinheli_most_commented_cache($posts_num=10, $days=30){
global $wpdb;   
$posts =$wpdb->get_results("SELECT `ID` , `post_title` , `comment_count` FROM $wpdb->posts 
WHERE `post_type` = 'post' AND TO_DAYS( now( ) ) - TO_DAYS( `post_date` ) < $days 
ORDER BY `comment_count` DESC LIMIT 0 , $posts_num ");

foreach ($posts as $post){
$output_most_commented .= "<li><a href= \"".get_permalink($post->ID)."\" rel=\"bookmark\" title=\"".$post->post_title."\" >".$post->post_title."</a></li>\n"; 
}
if($posts==null):
$output_most_commented="none";
endif;
//echo $output_most_commented;
update_option('yinheli_most_commented',array('num'=>$posts_num,'days'=>$days,'output'=>$output_most_commented));
}
add_action('comment_post', 'yinheli_most_commented_cache');
?>