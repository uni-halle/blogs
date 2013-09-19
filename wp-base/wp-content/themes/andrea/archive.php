<?php get_header(); ?>

<div id="content">
<?php if (have_posts()) : ?>

<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<?php /* If this is a category archive */ if (is_category()) { ?>
<h2 class="archive">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
<?php /* If this is a tag */ } elseif (is_tag()) { ?>
<h2 class="archive">Archive for the &#8216;<?php single_tag_title(); ?>&#8217; tag</h2>
<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
<h2 class="archive">Archive for <?php the_time('F jS, Y'); ?></h2>
<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
<h2 class="archive">Archive for <?php the_time('F, Y'); ?></h2>
<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
<h2 class="archive">Archive for <?php the_time('Y'); ?></h2>
<?php /* If this is an author archive */ } elseif (is_author()) { ?>
<h2 class="archive">Author Archive</h2>
<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
<h2 class="archive">Blog Archives</h2>
<?php } ?>

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

<?php endwhile; else: ?>
<div class="warning">
	<p>Sorry, but you are looking for something that isn't here.</p>
</div>
<?php endif; ?>

<div class="navigation group">
	<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
	<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
</div>

</div> 

<?php get_sidebar(); ?>

<?php get_footer(); ?>
