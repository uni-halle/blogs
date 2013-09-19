<?php
function get_xing($comment_email){
$options = get_option('philna_options');
$result=get_option('philna_commenter_star');
if(empty($result)){
philna_commenter_star($options['xing_limit_days']);
$result=get_option('philna_commenter_star');
}
$times=$result[$comment_email];
if($times>=$options['xing_limit_cm']){
$style= ' style'."=".'"cursor:pointer;"';
$output =" ";
$output .='<img src='.'"'.get_bloginfo('template_url').'/img/xin.gif" alt="Enthusiastic commentator! This guy comment '.$times.' times in'.$options['xing_limit_days'].'days . 	
So get this reward" '.' title="'.'Enthusiastic commentator! This guy comment '.$times.' times in '.$options['xing_limit_days'].' days. So get this reward" />';
echo $output;
}else return;
}

function philna_commenter_star( $limit_days=30){
global $wpdb, $tablecomments;
$options = get_option('philna_options');
$limit_days=$options['xing_limit_days'];
$sql=$wpdb->get_results(
			"SELECT comment_author_email, count( comment_author_email ) AS cmtcount
			FROM  $tablecomments
			WHERE comment_approved =1
			AND comment_type = ''
			AND TO_DAYS( now( ) )-TO_DAYS(`comment_date`)<$limit_days
			GROUP BY comment_author_email
			ORDER BY cmtcount DESC
			LIMIT 0 ,2000"
			);
foreach ($sql as $mycomment){
$mail=$mycomment->comment_author_email;
$comment_times=$mycomment->cmtcount;
$cache[$mail]=$comment_times;
}
update_option('philna_commenter_star',$cache);
}
add_action('comment_post', 'philna_commenter_star');
?>