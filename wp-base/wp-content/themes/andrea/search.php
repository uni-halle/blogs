<?php get_header(); ?>

<div id="content" class="group">
<?php if (have_posts()) : ?>

<h2 class="archive">Search Results</h2>

<?php while (have_posts()) : the_post(); ?>

<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a> &nbsp; <a href="<?php comments_link(); ?>" class="commentsLink"><?php comments_number('no comments','1 comment','% comments'); ?></a></h2>
<div class="stamp">Posted at <?php the_time('g:i a'); ?> in <?php the_category(',') ?></div>

<div class="main">
	<?php the_content('Read the rest of this entry &raquo;'); ?>
</div>

<div class="meta">
	<p>Written by <?php the_author() ?> on <?php the_time('F jS, Y'); ?> <span class="edit"><?php edit_post_link('Edit'); ?></span></p>
    <?php if ( the_tags('<p>Tagged with ', ', ', '</p>') ) ?>
</div>

<?php if ( comments_open() ) comments_template(); ?>

<?php endwhile; ?>
<div class="navigation group">
	<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
	<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
</div>
<?php else : ?>
	<h2>No posts found.</h2>
	<div class="warning">
		<p>Apologies, but we were unable to find what you were looking for. Try a different search?</p>
	</div>
<?php endif; ?>

</div> 

<?php get_sidebar(); ?>

<?php get_footer(); ?>
