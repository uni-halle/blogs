<?php
/**
 * The Header for our theme.
 * Displays all of the <head> section and everything up till <a id="logo">
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>><head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<title>
	<?php 
		if(is_home()) {
			echo bloginfo('name').' - Home';
		} elseif(is_category()) {
			echo 'Browsing the Category ';
			wp_title(' ', true, '');
		} elseif(is_archive()) {
			echo 'Browsing Archives of';
			wp_title(' ', true, '');
		} elseif(is_search()) {
			echo 'Search Results for "'.$s.'"';
		} elseif(is_404()) {
			echo '404 - Page got lost!';
		} else
			bloginfo('name'); wp_title('-', true, '');
    ?>
</title>

<?php $maja_option = maja_get_global_options(); ?>

<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" media="screen" /> 
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/extra.css" media="screen" /> 
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/lib/css/media_queries.css" media="screen" /> 
<!--<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/lib/css/<?php echo $maja_option['maja_color'] ?>.css" type="text/css" media="screen" />-->


<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="<?php echo $maja_option['maja_favicon'] ?>" />

<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/lib/css/style_ie.css" />
<![endif]--> 

<!-- load custom css code -->
<style type="text/css">
	<?php echo $maja_option['maja_customcss'] ?>
</style>
<!-- /load custom css code -->

<?php wp_head(); ?>

</head>

<body onload="initialize()" <?php body_class(); ?>>
<div class="languages" >    <?php echo qtrans_generateLanguageSelectCode('image'); ?></div>
<div id="container"  style="clear:both;">

   
    
    	<header>
        <div>
        <div  class="header_text" ><h1><a href="<?php echo bloginfo('url') ?>" >
        	<span style="font-family: 'OpenSansLight', Arial, sans-serif;">Georg</span><br />
        	<span style="font-family: 'OpenSansSemibold', Arial, sans-serif;">Forster</span><br/>
        	<span style="font-family: 'OpenSansExtrabold', Arial, sans-serif;">Haus</span></a></h1></div>
		<?php $lang = qtrans_getLanguage();  if($lang=='de'){ ?>
		<div  class="header_subline header_subline_img_de">Internationales Begegnungszentrum der Martin-Luther-Universität Halle-Wittenberg und der Leopoldina - Nationale Akademie der Wissenschaften</div>
		<?php }else { ?>
		<div  class="header_subline header_subline_img_en">International Guest House of the Martin Luther University Halle-Wittenberg and the German National Academy of Sciences Leopoldina</div>
		<?php } ?>
		</div>
        
        </header> <div style="width:100%;clear:both;"><section id="menu">
