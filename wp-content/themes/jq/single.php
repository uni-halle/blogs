<?php get_header(); ?>
<div id="content" class="clearfix">
<?php include (TEMPLATEPATH . "/sidebar2.php"); ?>
<div id="left">
<p id="sidebar_show"><a href="#" id="show_s">&larr; Sidebar</a></p>
<!-- single post -->
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<div class="headline clearfix">
<div class="date">
<?php the_time('M/y'); ?>
<p class="date-month"><?php the_time('j'); ?></p>
</div>
<h1><?php the_title(); ?></h1>
<div class="clearfix"></div>
<p class="post_info">         
<?php comments_popup_link('No comments','1 Comment','% Comments','comment-link info-icon','Comments off'); ?> &middot; Posted by <i><?php the_author(); ?></i> in <?php the_category(', '); ?><?php if ( is_user_logged_in() ) : ?><?php edit_post_link('Edit', ' &middot; ', ''); ?><?php endif; ?>
</p>
</div>
<div class="single_content">
<?php the_content(); ?>
<div class="clearfix"></div>
<p class="tag_info"><?php $tag = get_the_tags(); if (! $tag) {echo "No tags";} else {the_tags('', ' &middot; ', '');} ?></p>
<?php comments_template(); ?>
<?php endwhile; ?><p class="previous-posts"><?php next_post_link('<< %link') ?></p>
<p class="next-posts"><?php previous_post_link('%link >>') ?></p>
<?php else : ?>
<?php _e('Sorry, no posts matched your criteria.'); ?>
<?php include (TEMPLATEPATH . "/searchform.php"); ?>
<?php endif; ?></div>
</div>
</div>
<?php get_footer(); ?>
