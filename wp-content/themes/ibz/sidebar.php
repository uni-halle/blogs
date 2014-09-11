<?php
/**
 * The template for displaying the sidebar for blog page.
 * By default it comes with three widgets (search, categories and archives), 
 * which will be replaced when you insert a new widget in the dashboard).
 */
?>

<aside id="sidebar" class="one_third">

    <ul>
	    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

            <li id="search" class="widget">
                <?php get_search_form(); ?>
            </li>
                      
            <li id="categories" class="widget">
                <h2><?php _e('Categories', 'maja'); ?></h2>
                <ul class="lists-arrow">
                    <?php wp_list_categories('title_li=&orderby=name'); ?>
                </ul>
            </li>
        
            <li id="archives" class="widget">
                <h2><?php _e('Archives', 'maja'); ?></h2>
                <ul class="lists-arrow">
                    <?php wp_get_archives('type=monthly'); ?>
                </ul>
            </li>         
        
        <?php endif; ?> 
    </ul>
        
</aside> <!--/sidebar-->