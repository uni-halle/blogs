<?php get_header(); ?>

<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

<div class="post">

<?php the_title('<h1 class="post-title entry-title">', '</h1>'); ?>

	<div class="info">
	<?php edit_post_link(__('Edit', 'philna'), '<span class="editpost">', '</span>'); ?>
	<?php if(comments_open()) : ?>
		<span class="comments-link addcomment"><a href="#respond" title="<?php _e('Add a comment', 'philna') ?>"><?php _e('Add a comment', 'philna') ?></a></span>
	<?php endif;?>
	
	<span class="published"><?php the_time(__('F jS, Y', 'philna')) ?></span>
	<span class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_ID());?>"><?php the_author();?></a></span>
	</div>

	<div class="entry-content">
	<?php the_content(); ?>
	<?php wp_link_pages("before=<p class='pages'>".__('Pages:','philna')."&after=</p>"); ?>
	</div>

</div>

	<?php endwhile; ?>

<?php else: ?>

<p class="no-data"><?php _e('Sorry, no posts matched your criteria.','philna'); ?></p>

<?php endif; ?>


<?php comments_template(); ?>

<?php get_footer(); ?>