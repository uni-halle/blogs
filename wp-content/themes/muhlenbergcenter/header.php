<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until "page-main".
 *
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <?php wp_head(); ?>
        <script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/vendor/modernizr.min.js"></script>
        <script src="//use.typekit.net/tut6xdq.js"></script>
        <script>try{Typekit.load();}catch(e){}</script>
    </head>

    <body <?php body_class(); ?>>
        <div class="page-wrap">
            <a class="visuallyhidden" href="#content"><?php _e( 'Skip to content', 'muhlenbergcenter' ); ?></a>

            <header class="row page-header" role="banner">
                <p class="title">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                </p>
            </header>

            <?php get_sidebar(); ?>

            <?php if ( is_home() || is_front_page() ) : ?>
            <?php /* get the slider posts */
            $sliderConditions = array (
                'category_name' => 'slider',
                'post_type'     => 'post',
                'post_status'   => 'publish',
                'orderby'       => 'date',
                'nopaging'      => true
            );
            $the_query = new WP_Query( $sliderConditions );
            if ( $the_query->have_posts() ) :
            ?>
            <div class="row slider visible-for-medium-up">
                <div class="small-12 columns">
                    <ul data-orbit>
                        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                        <li>
                            <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'full' ); ?>
                            <?php endif; ?>
                            <div class="orbit-caption">
                                <?php the_title(); ?>
                            </div>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
            <?php
                endif;
                wp_reset_postdata();
            ?>
            <?php endif; /* is_home() || is_front_page() */ ?>

            <main class="page-main" role="main" id="content">
