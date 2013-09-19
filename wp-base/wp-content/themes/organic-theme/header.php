<?php
/**
 * @package WordPress
 * @subpackage Organic Theme
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo bloginfo('template_directory'); ?>/styles/<?php echo get_option('organic_theme_style'); ?>.css" type="text/css" media="screen, projection" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=monthly&format=link'); ?>
	<?php //comments_popup_script(); // off by default ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="rap">
<div id="header">
	<div id="head-top">
		<h1 id="logo"><img src="<?php echo bloginfo('template_directory'); ?>/img/logos/<?php echo get_option('organic_theme_logo'); ?>.gif" class="small-logo" alt="logo" width="40" height="40" /><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<form id="searchform" method="get" action="<?php bloginfo('home'); ?>">
			<input onfocus="if (this.value == 'search this site') {this.value = '';}" onblur="if (this.value == '') {this.value = 'search this site';}" type="text" value="search this site" name="s" id="s" />
			<input type="submit" value="<?php esc_attr_e(''); ?>" />
		</form>
	</div>
	<div id="head-sub">
		<p class="blog-desc"><?php bloginfo('description'); ?></p>
		<p class="rss-feed"><a href="<?php if (get_option('organic_feedburner') == "#") { bloginfo('rss2_url'); } else { echo get_option('organic_feedburner' );} ?>" title="<?php _e('Syndicate this site using RSS'); ?>" rel="nofollow"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></p>
	</div>
	<div id="navigation">
		<div id="nav-inner">
			<?php include('nav.php') ?>
			<div class="feed-box">
				<h5>SUBSCRIBE</h5>
				<ul id="feed-list">
					<li><a href="<?php if (get_option('organic_feedburner') == "#") { bloginfo('rss2_url'); } else { echo get_option('organic_feedburner' );} ?>" rel="nofollow">Articles</a></li>
					<li><a href="<?php bloginfo('comments_rss2_url'); ?>" rel="nofollow">Comments</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div id="content">
