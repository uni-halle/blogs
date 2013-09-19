<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php bloginfo('text_direction'); ?>" xml:lang="<?php bloginfo('language'); ?>">
    <head>
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <title><?php wp_title ( '|', true,'right' ); ?></title>
        <meta http-equiv="Content-language" content="<?php bloginfo('language'); ?>" />
		<link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/images/favico.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
        <!--[if IE]><link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('template_url'); ?>/ie.css" /><![endif]-->
        <?php
			wp_enqueue_script('jquery');
			wp_enqueue_script('cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', 'jquery', false);
			wp_enqueue_script('cookie', get_template_directory_uri() . '/js/jquery.cookie.js', 'jquery', false);
            if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
            wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js', 'jquery', false);
		?>
        <?php wp_head(); ?>
        <?php if ( is_home() && !get_option('ss_disable') ) : ?>
        <script type="text/javascript">
            (function($) {
                $(function() {
                    $('#slideshow').cycle({
                        fx:     'scrollHorz',
                        timeout: <?php echo (get_option('ss_timeout')) ? get_option('ss_timeout') : '7000' ?>,
                        next:   '#rarr',
                        prev:   '#larr'
                    });
                })
            })(jQuery)
        </script>
        <?php endif; ?>
	</head>
	<body <?php echo (get_option('bg_color')) ? 'style="background-color: '.get_option('bg_color').';"' : '' ?>>
        <div class="wrapper">

            <div class="header clear">
                <div class="logo">
                    <a href="<?php bloginfo('home'); ?>"><img src="<?php echo (get_option('logo_url')) ? get_option('logo_url') : get_bloginfo('template_url') . '/images/logo.png' ?>" alt="<?php bloginfo('name'); ?>"/></a>
                </div>

                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Site description') ) ?>

                <?php get_search_form(); ?>

                <?php wp_nav_menu(array('menu' => 'Top menu', 'theme_location' => 'Top menu', 'depth' => 1, 'container' => 'div', 'container_class' => 'menu', 'menu_id' => false, 'menu_class' => false)); ?>

            </div>

            <?php wp_nav_menu(array('menu' => 'Navigation', 'theme_location' => 'Navigation', 'depth' => 2, 'container' => 'div', 'container_class' => 'nav', 'menu_class' => 'dd', 'menu_id' => 'dd', 'walker' => new extended_walker())); ?>

            <?php if ( is_home() && !get_option('ss_disable') ) get_template_part('slideshow'); ?>

            <!-- Container -->
            <div id="container" class="clear">
                <!-- Content -->
                <div id="content">
