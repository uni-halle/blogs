<?php get_header(); ?>
<h1 class="catheader">Tag-Archiv</h1>

<div id="tagcloud"><?php wp_tag_cloud('smallest=8&largest=16'); ?></div>

<?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
			

    <div class="posts">
    <h2><a href="<?php the_permalink() ?>" title="Zum Lesen klicke <?php the_title(); ?>"><?php the_title(); ?></a></h2>
    <div class="meta">
				Von <?php the_author() ?>
			</div>
	<?php theme_excerpt('60'); ?>
    </div>

	<?php endwhile; ?>
	
	<?php endif; ?>
<?php get_footer(); ?>