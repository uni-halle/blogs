<?php
function yinheli_addrss($content){
$options = get_option('philna_options');
if($options['rss_show_comment_state']):
global $id;
$comment_num = get_comments_number($id);
if($comment_num==0):
$rss_comment_tip="Here is no comments yet by the time  your rss reader get this, Do you want to be the first commentor? Hurry up ";
elseif($comment_num>=1 && $comment_num<30):
$rss_comment_tip="By the time  your rss reader get this post here is <strong> ".$comment_num." </strong>comments ,Welcome you come to leave your opinion !";
elseif($comment_num>=30):
$rss_comment_tip="By the time  your rss reader get this post here is<strong> ".$comment_num." </strong>comments,Heated discussion,Why not to come to check it out ?!";
endif;
endif;

if($options['rss_copyright_show']){
$rss_copyright =$options['rss_copyright'];
$this_post_info="\n<p>This article addresses:".'<a href="'.get_permalink().'">'.get_permalink().'</a></p>';
}

if(is_feed())
$content =$content.$this_post_info.$rss_comment_tip.$rss_copyright;

return $content;
}
add_filter('the_content', 'yinheli_addrss');
?>