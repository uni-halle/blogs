<?php
/**
 * @package WordPress
 * @subpackage Studenten für Halle e.V.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
  <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
  <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
  <link href='//fonts.googleapis.com/css?family=Droid+Serif' rel='stylesheet' type='text/css'>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <style type="text/css" media="screen">
    @import url( <?php bloginfo('stylesheet_url'); ?> );
  </style>
  <?php wp_get_archives('type=monthly&format=link'); ?>
  <?php //comments_popup_script(); // off by default ?>
  <?php wp_head() ?>
</head>
<body <?php body_class(); ?>>
<div id="top">
  <div id="top_all">
    <div id="head">
      <div id="header"><h1><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1></div>
      <div id="navi2">
        <ul>
          <?php wp_list_pages('include=26,28&sort_column=menu_order&title_li='); ?>
        </ul>
      </div>
    </div>
    <div id="banner" style="background: url('<?php bloginfo('stylesheet_directory');
	if (is_home()) {echo "/images/banner_halle_rockt.png";} 
	elseif (is_page('14')) {echo "/images/banner_halle_rockt.png";} 
	elseif (is_page('16')) {echo "/images/banner_rundgang.jpg";} 
	elseif (is_page('18')) {echo "/images/banner_projekt2020.png";} 
	elseif (is_page('48')) {echo "/images/banner_asq.png";} 
	else {echo "/images/banner_stadt.jpg";}?>');">
      <div id="navi">
        <ul>
          <?php wp_list_pages('include=16,14,18,48&sort_column=menu_order&title_li='); ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<div id="middle">
  <div id="middle_all">
    <div id="sidebar">
      <div id="logo"><img src="<?php bloginfo('stylesheet_directory')?>/images/logo_120x120.png" alt="Studenten für Halle e. V." alt="Studenten für Halle e. V."></div>
      <div id="news">
        <?php get_sidebar(); ?>
      </div>
    </div>
    <div id="content">

<!-- end header -->
