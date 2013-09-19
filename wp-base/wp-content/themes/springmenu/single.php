<?php get_header(); ?>
<div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="post" id="post-<?php the_ID(); ?>">

<h1><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: 
<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
<p class="date"><?php the_time(__('l, F dS, Y','avenue')) ?>&nbsp;|&nbsp;
<?php _e('Author: ','avenue'); ?><?php the_author_posts_link('nickname'); ?>
<span class="edit"><?php edit_post_link(' &raquo Edit &laquo','',''); ?></span></p>

<div class="entry"><?php the_content(); ?>
</div><!-- End Entry -->
</div><!-- End Post -->

<?php if ( function_exists('the_tags') ) : ?>
<div class="tags"><?php _e('Tags  &raquo;&nbsp;&nbsp;&nbsp;'); ?>
<?php the_tags('', ', ', ' &laquo;'); ?></div><?php endif; ?>

<div id="postmeta"><p class="data">
<?php _e('Trackback: '); ?><a href="<?php trackback_url(display); ?>">Trackback-URL</a>&nbsp;|&nbsp;<span class="red"><?php _e('Comments Feed: ','avenue'); ?>
<?php comments_rss_link('RSS 2.0'); ?></span><br />
<?php _e('Category: ','avenue'); ?><?php the_category(', ') ?></p>

<p class="note">
<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Both Comments and Pings are open ?>
<?php _e('You can leave a <a href="#respond">response.</a>','avenue'); ?>

<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Only Pings are Open ?>
<?php _e('Responses are currently closed, but you can<br />','avenue'); ?><a href="<?php trackback_url(display); ?> ">Trackback</a><?php _e(' the post from your own site.','avenue'); ?>

<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) { // Comments are open, Pings are not ?>
<?php _e('You can leave a <a href="#respond">response</a>.<br />Pinging is currently not allowed.','avenue'); ?>

<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) { // Neither Comments, nor Pings are open ?>
<?php _e('Comments and pings are currently closed.','avenue');} ?></p>
</div><!-- End Postmeta -->

<?php comments_template(); ?>
<?php endwhile; else: ?><p class="sorry"><?php _e("Sorry, but you are looking for something that isn't here. Try something else.",'avenue'); ?></p>
<?php endif; ?>

</div><!-- End Content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>