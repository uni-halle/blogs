<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<script src="<?php bloginfo('template_url'); ?>/js/cufon-yui.js" type="text/javascript"></script>
		<script src="<?php bloginfo('template_url'); ?>/js/Aller_400-Aller_700-Aller_italic_400-Aller_italic_700.font.js" type="text/javascript"></script>
		<script type="text/javascript">
			Cufon.replace('h1');
			Cufon.replace('h2');
			Cufon.replace('h3');
			Cufon.replace('#nav a');
		</script>

<style type="text/css" media="screen">

</style>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body>
<div id="header-wrap">
<div id="header">
<div id="navigation">
<div id="nav">
<ul><?php wp_list_pages('title_li=&depth=1'); ?></ul>
</div>
</div>

		<h1><a class="head" href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<h2><?php bloginfo('description'); ?></h2>
</div>
</div>
<div id="container">

<div id="topboxes">
<div id="about">
<h2>About me</h2>
<div style="clear: both;"></div>
<img src="<?php $newyork_aboutme_image = get_option('newyork_aboutme_image'); echo stripslashes($newyork_aboutme_image); ?>" height="70px" width="70px" alt="" border="0" style="margin:5px 5px 0 0;float:left;"/><?php $newyork_aboutme_text = get_option('newyork_aboutme_text'); echo stripslashes($newyork_aboutme_text); ?><a href="<?php $newyork_aboutme_link = get_option('newyork_aboutme_link'); echo stripslashes($newyork_aboutme_link); ?>" title="About Me">Read more about me &#187;</a>
</div>

<div id="feautred-article">
<?php $my_query = new WP_Query('category_name=Featured Articles&showposts=1');
while ($my_query->have_posts()) : $my_query->the_post();
$do_not_duplicate = $post->ID; ?>
<?php
//Get images attached to the post
$args = array(
 'post_type' => 'attachment',
 'post_mime_type' => 'image',
 'numberposts' => -1,
 'order' => 'ASC',
 'post_status' => null,
 'post_parent' => $post->ID
);
$attachments = get_posts($args);
if ($attachments) {
 foreach ($attachments as $attachment) {
 $img = wp_get_attachment_url( $attachment->ID );
 break;
 }
}
?>

<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
			<?php if (strlen($post->post_title) > 30) {
			echo substr(the_title($before = '', $after = '', FALSE), 0, 30) . '...'; } else {
				the_title();
			} ?>	
</a></h2>

<a href="<?php the_permalink() ?>" class="opacity" rel="bookmark" title="Featured Work | <?php the_title(); ?>">
<img src="<?php bloginfo('stylesheet_directory'); ?>/cropper.php?src=<?php echo $img; ?>&amp;h=175&amp;w=306&amp;zc=1" width="306px" height="175px" style="border: none;margin:7px 0 0 0;" alt="<?php the_title(); ?>" />
<img class="featured-ribbon" src="<?php bloginfo('stylesheet_directory'); ?>/images/featuredribbon.png" alt="Featured Work" />
</a>
<?php endwhile; ?>
</div>

<div id="subscribe">
<h2>Keep in touch</h2>
<div style="text-align:center;">
<a href="<?php $newyork_feed_url = get_option('newyork_feed_url'); echo stripslashes($newyork_feed_url); ?>" class="opacity" title="something"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/rss.png" style="border: none;" alt="RSS Feed" /></a>
<a href="<?php $newyork_twitter_link = get_option('newyork_twitter_link'); echo stripslashes($newyork_twitter_link); ?>" class="opacity" title="something"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/twitter.png" style="border: none;" alt="Twitter" /></a>
<a href="<?php $newyork_facebook_link = get_option('newyork_facebook_link'); echo stripslashes($newyork_facebook_link); ?>" class="opacity" title="Connect via Facebook"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/facebook.png" style="border: none;" alt="Facebook" /></a>
<a href="http://www.delicious.com/post?url=<?php echo get_option('home'); ?>/&title=<?php bloginfo('name'); ?>" class="opacity" title="Add to Delicious Bookmarks"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/fav.png" style="border: none;" alt="Delicious" /></a>
</div>
<h2>Subscribe via Email</h2>

<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php $newyork_feed_name = get_option('newyork_feed_name'); echo ($newyork_feed_name); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
	<input type="text" id="email-subscribe" name="email"  />
	<input type="hidden" value="<?php $newyork_feed_name = get_option('newyork_feed_name'); echo ($newyork_feed_name); ?>" name="uri"/>
	<input type="hidden" name="loc" value="en_US"/>
<input name="submit" type="image" src="<?php bloginfo('stylesheet_directory'); ?>/images/go.png" value="Subscribe" Title="Subscribe to RSS via Email" style="float:right;background:none;margin:6px 0 0 0;padding:0;"/>
<div style="clear: both;"></div>
</form>
</div>
</div>

<div style="clear: both;"></div>