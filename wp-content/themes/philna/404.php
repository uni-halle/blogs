<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<?php
$options = get_option('philna_options');
if($options['feed'] && $options['feed_url']) {
if (substr(strtoupper($options['feed_url']), 0, 7) == 'HTTP://') {
$feed = $options['feed_url'];
} else {
$feed = 'http://' . $options['feed_url'];
}
} else {
$feed = get_bloginfo('rss2_url');
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php yinheli_title();?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); _e('RSS 2.0 - posts', 'philna'); ?> RSS Feed" href="<?php echo $feed; ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php _e('RSS 2.0 - all comments', 'philna'); ?>" href="<?php bloginfo('comments_rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if(is_single()){ ?><link rel="canonical" href="<?php echo get_permalink($post->ID);?>" /><?php }?>
<?php if(is_home() || is_page() || is_tag() || is_category() || is_month() || is_year()){ ?><link rel="canonical" href="<?php bloginfo('url');?>" /><?php }?>

<?php yinheli_meta_dec();?>
<?php wp_head(); ?>
</head>
<body>

<div id="main">
<div id="warp">
<div id="page">

<div id="header">
<div id="title">

<h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>
<div id="dec"><?php bloginfo('description'); ?></div>

</div>


<div id="header-right">
<div id="search">
<?php if($options['google_cse'] && $options['google_cse_cx']) : ?>
<form action="http://www.google.com/cse" method="get">
<div>
<input type="text" class="textfield" name="q" size="24" value="Search" onblur="if(this.value == ''){this.value= 'Search';}" onfocus="this.value='';" />
<input type="hidden" name="cx" value="<?php echo $options['google_cse_cx']; ?>" />
<input type="hidden" name="ie" value="UTF-8" />
</div>
</form>
<?php else : ?>
<form action="<?php bloginfo('home'); ?>" method="get">
<div>
<input type="text" class="textfield" name="s" size="24" value="<?php echo wp_specialchars($s, 1); ?>" />
</div>
</form>
<?php endif; ?>
</div>

<div id="feed">
<?php if($options['feed_email'] && $options['feed_url_email']) : ?>
<a id="mailfeed" href="<?php echo $options['feed_url_email']; ?>" title="<?php _e('E_mail Feed','philna')?>">E_mail Feed</a>
<?php endif;?>
<a id="rssfeed" href="<?php echo $feed ;?>" title="<?php _e('Rss Feed','philna')?>">Rss Feed</a>
</div>
</div>
</div>
<div id="nav">

<ul id="menus">
<li class="home <?php if(is_home()) echo "current_page_item";?>"><a href="<?php echo get_option('home'); ?>/"><?php _e('Home','philna')?></a></li>
<?php wp_list_pages('depth=1&title_li=0&sort_column=menu_order'); ?>
</ul>		
</div>


<div id="container">
<div id="content">



<div class="post">
<div id="errorpage">
<h1><?php _e('Welcome to 404 error page!', 'philna'); ?></h1>
<p><?php _e("Welcome to this customized error page. You've reached this page because you've clicked on a link that does not exist. This is probably our fault... but instead of showing you the basic '404 Error' page that is confusing and doesn't really explain anything, we've created this page to explain what went wrong.", 'philna'); ?></p>

<h2><?php _e('try to search', 'philna'); ?></h2>

<?php if($options['google_cse'] && $options['google_cse_cx']) : ?>
<div id="use-google">
<p>Google custom search</p>
<form action="http://www.google.com/cse" method="get">
<div class="content">
<input type="text" class="textfield" name="q" size="24" />
<input type="hidden" name="cx" value="<?php echo $options['google_cse_cx']; ?>" />
<input type="hidden" name="ie" value="UTF-8" />
<input class="searchbtn" type="submit" value=""/>
</div>
</form>
</div>
<?php endif;?>
<div id="use-wp-self">
<p>Wordpress search</p>
<form action="<?php bloginfo('home'); ?>" method="get">
<div class="content">
<input type="text" class="textfield" name="s" size="24" value="<?php echo wp_specialchars($s, 1); ?>" />
<input class="searchbtn" type="submit" value=""/>
</div>
</form>
</div>

</div>
</div>


<?php get_footer(); ?>