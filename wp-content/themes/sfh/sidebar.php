<?php
/**
 * @package WordPress
 * @subpackage Studenten für Halle e.V.
 */
?>
<!-- begin sidebar -->

<?php 	/* Widgetized sidebar, if you have the plugin installed. */
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
<!--	<?php wp_list_pages('title_li=' . __('Pages:')); ?>
	<?php wp_list_bookmarks('title_after=&title_before='); ?>
	<?php wp_list_categories('title_li=' . __('Categories:')); ?>
-->
<h1>Neuigkeiten</h2>

<ul>

    <?php
        $lastposts = get_posts('numberposts=4&orderby=desc&cat=1');
        foreach($lastposts as $post) :
        setup_postdata($post); ?>

        <li<?php if ( $post->ID == $wp_query->post->ID ) { echo ' class="current"'; } else {} ?>>
            
            <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
            
        </li>

    <?php endforeach; ?>

</ul>

<?php posts_nav_link(' &#8212; ', __('&laquo; Newer Posts'), __('Older Posts &raquo;')); ?>

<?php endif; ?>

<a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"><img src="//www.mozilla.org/images/feed-icon-14x14.png" alt="RSS Feed" title="RSS Feed" /> RSS Feed</a>

<!-- end sidebar -->
