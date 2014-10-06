<?php
/**
 * Der Header.
 *
 * Zeigt den ganzen <head> Bereich und alles &uuml;ber dem <div id="main">
 *
 * @package WordPress
 * @subpackage Deutschdidaktik
 * @since Deutschdidaktik 1.0
 */

global $page, $paged;
if(!($cCat = get_query_var('cat'))){
	include('startseite.php');
	exit;
}
$cCat = get_query_var('cat');

?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=1.5, user-scalable=1" />
        <title><?php
            // Print the <title> tag based on what is being viewed.
            wp_title('|', true, 'right');
            // Add the blog name.
            bloginfo('name');
            // Add a page number if necessary:
            if ($paged >= 2 || $page >= 2) echo ' | ' . sprintf(__('Page %s'/*, 'Deutschdidaktik Uni Halle'*/), max($paged, $page));
        ?></title>
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url') ?>" />
        <link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
        <link href="<?php echo get_bloginfo('template_url').'/images/favicon.ico' ?>" rel="shortcut icon" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <!--[if lt IE 9]>
        <script src="<?php echo bloginfo('template_url') ?>/js/html5.js" type="text/javascript"></script>
        <![endif]-->
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?> >
        <!-- Template header -->
        <div class="wrapper">
            <header id="banner" role="banner">
                <a href="http://www.uni-halle.de"><img src="<?php bloginfo('template_url'); ?>/images/logo.png" class="left" width="185" height="106" alt="Logo der Martin-Luther-Universität Halle" /></a>
                <a href="<?php echo home_url(); ?>">
                    <img src="<?php bloginfo('template_url'); ?>/images/header_text.png" class="right" width="762" height="106" alt="Überschrift der Martin-Luther-Universität im Fachbereich Didaktik" />
                </a>
                <!--<nav class="navigation right" role="navigation">
                    <ul>-->
                        <?php
                           //  wp_list_categories('title_li=&hide_empty=0&depth=1&orderby=title');
						    wp_nav_menu( array( 'theme_location' => 'header-nav' )); 
                        ?>
                    <!--</ul>
                </nav>-->
            </header>
            <div id="main" class="clear">
                <div id="nav-left" role="navigation">
                    <ul class="nav-parents">
                    <?php if($cCat): ?>
                        <?php $ups = array_reverse(get_ancestors($cCat,'category')); ?>
                       
                        <?php foreach($ups as $uCat): ?>
                            <?php wp_list_categories('hide_empty=0&title_li=&include='.$uCat); ?>
                        <?php endforeach; ?>
                        <?php if($subs=wp_list_categories('hide_empty=0&show_option_none=&orderby=id&echo=0&depth=1&title_li=&child_of='.$cCat)): ?>
                            <?php wp_list_categories('hide_empty=0&title_li=&include='.$cCat); ?>
                            </ul>
                            <ul class="nav-subs">
                            <?php echo $subs ?>
                        <?php else: ?>
                            </ul>
                            <ul class="nav-currents">
                            <?php wp_list_categories('hide_empty=0&orderby=id&title_li=&depth=1&child_of='.$uCat?:1)?>
                        <?php endif; ?>
                    <?php endif; ?>
                    </ul>
							
					
                    <?php  /* if(isset($cCat) && $cCat > 0 && $cCat != ""){ ?>
                    <ul class="nav-currents">
                        <?php wp_list_categories('hide_empty=0&orderby=ID&order=ASC&title_li=&depth=1&show_option_none=&child_of='.$cCat)?>
                    </ul>
                    <?php }else{ ?>
                    <ul class="nav-currents">
                        <?php wp_list_categories('hide_empty=0&orderby=ID&order=ASC&title_li=&depth=1&show_option_none=&child_of=16')?>
                    </ul>
                    <?php } */ ?>
                </div>

                <?php /*?><textarea style="width: 100%;">
                    <?php  global $wp_query; print_r($wp_query); ?>
                </textarea><?php */?>
