<?php get_header(); ?>
<div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="post" id="post-<?php the_ID(); ?>">

<h1 class="pagetitle"><?php the_title(); ?></h1>

<div class="entry">
<?php the_content(); ?>
</div><!-- End Entry -->
</div><!-- End Post -->

<p class="pagedata">
<?php _e('Trackback: '); ?><a href="<?php trackback_url(display); ?>">Trackback-URL</a>&nbsp;|&nbsp;
<?php _e('Author: ','avenue'); ?><?php the_author_posts_link('nickname'); ?>
<span class="edit"><?php edit_post_link(' &raquo Edit &laquo','',''); ?></span></p>

<p class="note">
<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Both Comments and Pings are open ?>
<?php _e('You can leave a <a href="#respond">response.</a>','avenue'); ?>

<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) { // Only Pings are Open ?>
<?php _e('Responses are currently closed, but you can<br />','avenue'); ?><a href="<?php trackback_url(display); ?> ">Trackback</a><?php _e(' the post from your own site.','avenue'); ?>

<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) { // Comments are open, Pings are not ?>
<?php _e('You can leave a <a href="#respond">response</a>.<br />Pinging is currently not allowed.','avenue'); ?>

<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) { // Neither Comments, nor Pings are open ?>
<?php _e('Comments and pings are currently closed.','avenue');} ?></p>

<?php comments_template(); ?>
<?php endwhile; else: ?><p class="sorry"><?php _e("Sorry, but you are looking for something that isn't here. Try something else.",'avenue'); ?></p>
<?php endif; ?>

</div><!-- End Content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>