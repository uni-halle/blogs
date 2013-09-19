<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" />

<link rel="ico" type="image/ico" href="<?php bloginfo('template_url'); ?>/images/favicon.ico" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />

<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<style type="text/css" media="screen">

<?php

// Checks to see whether it needs a sidebar or not

if ( !empty($withcomments) && !is_single() ) {

?>


<?php } else { // No sidebar ?>

	#page { background:  no-repeat top; border: none; }

<?php } ?>

</style>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>

</head>

<body>
<div id="circles">

<div id="page">



<div id="header">

<div>  
	<ul id="supernav">  
		<?php include("suckerfish.php"); ?> 
</div>  


<h1 id="logo"><a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a></h1>

<h4><?php if(get_bloginfo('description')): ?><p class="headline"><?php bloginfo('description'); ?></p><?php endif; ?></h4>




</div>

 
