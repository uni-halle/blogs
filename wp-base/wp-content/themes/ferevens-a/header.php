<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript" src="<?php echo bloginfo('template_url'); ?>/javascript/tabs.js"></script>
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" type="image/x-icon" />

<?php wp_head(); ?>
</head>
<body>


<!-- Start BG -->
<div id="bg">


<!-- Start Header -->
<div class="header">
 <h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
 <ul class="rss">
  <li><a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a></li>
  <li><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a></li>
 </ul>
</div>
<!-- End Header -->

<div class="menu">
 <ul>
   <li class="<? echo (is_home())?'current_page_item':''; ?>"><a href="<?php echo get_option('home'); ?>/"><span>Home</span></a></li>
<?php $pages = wp_list_pages('sort_column=menu_order&title_li=&echo=0');
$pages = preg_replace('%<a ([^>]+)>%U','<a $1><span>', $pages);
$pages = str_replace('</a>','</span></a>', $pages);
echo $pages; ?>
  </ul>
<? unset($pages); ?> 
</div>



<!-- Start Con-->
<div class="con">

<div class="scs1">

<!-- Start SL -->
<div class="sc-all"><div class="sc">

