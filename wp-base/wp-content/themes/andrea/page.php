<?php get_header(); ?>

<div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a> &nbsp; <a href="<?php comments_link(); ?>" class="commentsLink"><?php comments_number('no comments','1 comment','% comments'); ?></a></h2>
<div class="stamp">Posted at <?php the_time('g:i a'); ?> in <?php the_category(',') ?></div>

<div class="main">
	<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
	<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
</div>

<div class="meta">
	<p>Written by <?php the_author() ?> on <?php the_time('F jS, Y'); ?> <span class="edit"><?php edit_post_link('Edit'); ?></span></p>
    <?php if ( the_tags('<p>Tagged with ', ', ', '</p>') ) ?>
</div>

<?php if ( comments_open() ) comments_template(); ?>

<?php endwhile; else: ?>
<div class="warning">
	<p>Sorry, but you are looking for something that isn't here.</p>
</div>
<?php endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
