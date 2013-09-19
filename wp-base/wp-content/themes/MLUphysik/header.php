<?php
/**
 * @package WordPress
 * @subpackage MLUphysik Theme
 */
$options = get_option( 'MLUphysik_theme_settings' );
?>
<!DOCTYPE html>

<!-- BEGIN html -->
<html  class="no-js" <?php language_attributes(); ?>>
<!-- Design by OLIGOFORM GbR (http://www.oligoform.com) - Powered by WordPress (http://wordpress.org) -->

<!-- BEGIN head -->
<head>

<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta http-equiv="imagetoolbar" content="no">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        		<meta name="MobileOptimized" content="320">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="white">


<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' |'; } ?> <?php bloginfo('name'); ?></title>

<script type="text/javascript" src="<?php bloginfo('template_url')?>/js/modernizr.custom.mlu.js"></script>
<!-- Stylesheet & Favicon -->
<?php if($options['favicon'] !='') { ?>
<link rel="icon" type="image/png" href="<?php echo $options['favicon']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />

  <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url')?>/content/css/bootmetro-tiles.css">
  <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url')?>/content/css/icomoon.css">

   <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url')?>/css/flexslider.css">

<!-- WP Head -->

<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

<?php if(!empty($options['notification'])) { ?>
<div id="notifications">
    <div id="notifications-inner">
		<?php echo stripslashes($options['notification']); ?>
    </div>
    <!-- /notifications-exit -->  
</div>
<!-- /notifications -->
<?php } ?>

<div id="wrap" class="clearfix metro">

<?php 
//get_sidebar(' '); 
?>