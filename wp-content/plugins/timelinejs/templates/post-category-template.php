<?php

if ( ! defined( 'ABSPATH' ) ) exit;

global $post;

$category = get_the_category();

// var_dump($category);exit;

$social_icon_url = plugins_url( 'img/', dirname(__FILE__) );
$post_url = esc_url( get_permalink($category[0]->term_id) );

$post_title = site_url().'/category/'.$category[0]->slug;
$post_url = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php wp_head(); ?>
	<style type="text/css">
	body:before {display:none;}
	.sm-social-sharing {padding:10px;}
	.sm-social-icon {width:40px;height:40px;}
	</style>
</head>
<body>
	<?php do_shortcode('[timeline_post_category name="'.$category[0]->slug.'" fullscreen="true"]'); ?>
	
	<div class="sm-social-sharing">
	   
	    <a href="http://www.facebook.com/sharer.php?u=<?php echo $post_url; ?>" target="_blank">
	        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>facebook.png" alt="Facebook" />
	    </a>
	    <a href="https://plus.google.com/share?url=<?php echo $post_url; ?>" target="_blank">
	        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>google+.png" alt="Google" />
	    </a>
	    <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $post_url; ?>" target="_blank">
	        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>linkedin.png" alt="LinkedIn" />
	    </a>
	    <a href="javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());">
	        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>pinterest.png" alt="Pinterest" />
	    </a>
	    <a href="http://www.tumblr.com/share/link?url=<?php echo $post_url; ?>&amp;title=Simple Share Buttons" target="_blank">
	        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>tumblr.png" alt="Tumblr" />
	    </a>
	    <a href="https://twitter.com/share?url=<?php echo $post_url; ?>&amp;text=<?php echo $post_title; ?>&amp;hashtags=storymap" target="_blank">
	        <img class="sm-social-icon" src="<?php echo $social_icon_url; ?>twitter.png" alt="Twitter" />
	    </a>

	</div>
	<?php wp_footer(); ?>
</body>
</html>