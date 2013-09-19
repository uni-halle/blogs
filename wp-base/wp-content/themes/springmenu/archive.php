<?php get_header(); ?>
<div id="content">

<?php if (have_posts()) : ?><?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

<?php /* If this is a category archive */ if (is_category()) { ?>
<h1 class="archivtitle"><?php _e('Archive for the Category &raquo; ','avenue'); ?><?php echo single_cat_title(); ?><?php _e(' &laquo;'); ?></h1> 

<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
<h1 class="archivtitle"><?php _e('Archive for ','avenue'); ?><?php the_time(__('F jS, Y','avenue')); ?></h1>

<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
<h1 class="archivtitle"><?php _e('Archive &raquo; ','avenue'); ?><?php the_time(__('F, Y','avenue')); ?><?php _e(' &laquo;'); ?></h1>

<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
<h1 class="archivtitle"><?php _e('All entries ','avenue'); ?><?php the_time('Y','avenue'); ?></h1>

<?php /* If this is a search */ } elseif (is_search()) { ?>
<h1 class="archivtitle"><?php _e('Search Results','avenue'); ?></h1>

<?php /* If this is an author archive */ } elseif (is_author()) { ?>
<h1 class="archivtitle"><?php _e('Author Archive','avenue'); ?></h1>

<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
<h1 class="archivtitle"><?php _e('Blog Archives','avenue'); ?></h1>

<?php /* If this is a tag archive */ } elseif (is_tag()) { ?> 
<h1 class="archivtitle"><?php _e('Tag-Archive for &raquo; ','avenue'); ?><?php single_tag_title(); ?><?php _e(' &laquo;'); ?></h1><?php } ?>

<?php while (have_posts()) : the_post(); ?><div class="post">
<h1 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

<p class="date">
<?php the_time(__('l, F dS, Y','avenue')) ?>&nbsp;|&nbsp;<?php _e('Author: ','avenue'); ?><?php the_author_posts_link('nickname'); ?>

<span class="edit">
<?php edit_post_link(' &raquo Edit &laquo','',''); ?></span></p>

<div class="entry">
<?php the_content(__('more...','avenue')); ?>
</div><!-- End Entry -->

<p class="info">
<span class="category"><?php _e('Category: ','avenue'); ?>
<?php the_category(', ') ?></span>&nbsp;|&nbsp;<span class="bubble"><?php comments_popup_link(__('Leave a Comment','avenue'), __('One Comment','avenue'), __('% Comments','avenue'), '', __('Comments off','avenue')); ?></span></p>


<!-- <?php trackback_rdf(); ?> -->
</div><!-- End Post -->
<?php endwhile; ?>

<div class="navigation">
<div class="alignleft"><?php posts_nav_link('','',__('&laquo; previous Entries','avenue')) ?></div>
<div class="alignright"><?php posts_nav_link('',__('next Entries &raquo;','avenue'),'') ?></div></div>

<?php else : ?><h1><?php _e('Not found','avenue'); ?></h1>
<?php endif; ?>

</div><!-- End Content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>