<?php get_header(); ?>
<div id="content">

<?php if ( is_search() ) { ?>
<h1 class="archivtitle"><?php _e('Search Results','avenue'); ?></h1><?php } ?>

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="post" id="post-<?php the_ID(); ?>">

<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link: 
<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
<p class="date"><?php the_time(__('l, F dS, Y','avenue')) ?></p>

<div class="entry">
<?php if ( is_home() || is_single() ) { ?><?php the_content(__('more...','avenue')); ?><?php } ?>
<?php if ( is_search() ) { ?><?php the_excerpt() ?><?php } ?>
</div><!-- End Entry -->

<p class="info"><span class="category"><?php _e('Category: ','avenue'); ?>
<?php the_category(', ') ?></span>&nbsp;|&nbsp;<span class="bubble">
<?php comments_popup_link(__('Leave a Comment','avenue'), __('One Comment','avenue'), __
('% Comments','avenue'), '', __('Comments off','avenue')); ?></span>
<span class="edit"><?php edit_post_link(' &raquo Edit &laquo','',''); ?></span></p>

</div><!-- End Post -->
<?php endwhile; ?>

<div class="navigation">
<div class="alignleft"><?php posts_nav_link('','',__('&laquo; previous Entries','avenue')) ?></div>
<div class="alignright"><?php posts_nav_link('',__('next Entries &raquo;','avenue'),'') ?></div>
</div><!-- End Navigation -->

<?php else : ?><h1><?php _e('Not found','avenue'); ?></h1>
<p class="sorry"><?php _e("Sorry, but you are looking for something that isn't here. Try something else.",'avenue'); ?></p>
<?php endif; ?>

</div><!-- End Content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>