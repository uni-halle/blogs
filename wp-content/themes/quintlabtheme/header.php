<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
    <head>
        <meta content='<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>' http-equiv='Content-Type'>
        <meta content='width=device-width, initial-scale=1' name='viewport'>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
        <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <header class='container'>
            <div class='logo'>
                <a href='<?php echo home_url(); ?>/'>
                    <img alt='' src='<?php echo QL_BASE; ?>img/logo_quintlab.png'>
                </a>
            </div>

            <?php if (function_exists('qtranxf_generateLanguageSelectCode')): ?>
            <div class='langselect adjustbold'>
                <div class='current'><?php echo qtranxf_getLanguageName(); ?></div>
                <?php qtranxf_generateLanguageSelectCode(array("type"=>"text")); ?>
            </div>
            <?php endif; ?>

            <div class='fontselect'>
                <ul>
                    <li class='active'><a href="#" class="tiny">A</a></li>
                    <li><a href="#" class="medium">A</a></li>
                    <li><a href="#" class="large">A</a></li>
                </ul>
            </div>
            <div class='mlulogo'>
                <a href='http://www.uni-halle.de/'>
                    <img alt='' src='<?php echo QL_BASE; ?>img/logo_mlu.png'>
                </a>
            </div>
        </header>

        <nav class='mainnav container adjustbold'>
            <div class='current navtoggle'><?php the_title(); ?></div>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'container' => '',
            ));
            ?>
        </nav>
