<?php get_header(); ?>

<?php $options = get_option('philna_options'); ?>


<div class="position"><?php _e('Archive:', 'philna'); ?>
<strong>
	<?php
	// If this is a category archive
	if (is_category()) {
		printf( __('&#8216;%1$s&#8217; Category', 'philna'), single_cat_title('', false) );
	// If this is a tag archive
	} elseif(is_tag()) {
		printf( __('Posts Tagged &#8216;%1$s&#8217;', 'philna'), single_tag_title('', false) );
	// If this is a daily archive
	} elseif (is_day()) {
		printf( __('%1$s', 'philna'), get_the_time(__('F jS, Y', 'philna')) );
	// If this is a monthly archive
	} elseif (is_month()) {
		printf( __('%1$s', 'philna'), get_the_time(__('F, Y', 'philna')) );
	// If this is a yearly archive
	} elseif (is_year()) {
		printf( __('%1$s', 'philna'), get_the_time(__('Y', 'philna')) );
	// If this is an author archive
	} elseif (is_author()) {
		_e('Author Archive', 'philna');
	// If this is a paged archive
	} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
		_e('Blog Archives', 'philna');
	}
	?>
	</strong>
</div>


<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

<div class="post">

<?php the_title('<h2 class="post-title entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">', '</a></h2>'); ?>

	<div class="info">
	<?php edit_post_link(__('Edit', 'philna'), '<span class="editpost">', '</span>'); ?>	
	<span class="comments-link"><?php comments_popup_link(__('No comments', 'philna'), __('1 comment', 'philna'), __('% comments', 'philna')); ?></span>	
	<span class="published"><?php the_time(__('F jS, Y', 'philna')) ?></span>
	<span class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_ID());?>"><?php the_author();?></a> </span>
	</div>

	<div class="entry-content">
	<?php the_content(__('Continue reading...','philna')); ?>
	</div>

	<div class="entry-meta">
	<span class="cat-links"><?php the_category(', '); ?></span>
	<?php the_tags('<span class="tag-links">', ', ', '</span>');?>
	</div>

</div>
	<?php endwhile; ?>

<?php else: ?>

<p class="no-data"><?php _e('Sorry, no posts matched your criteria.','philna'); ?></p>

<?php endif;if(has_next_page()):?>

<div id="pagenavi">
	<?php if(function_exists('wp_pagenavi')) : ?>
		<?php wp_pagenavi() ?>
	<?php else : ?>
		<span class="newer"><?php previous_posts_link(__('Newer Entries', 'philna')); ?></span>
		<span class="older"><?php next_posts_link(__('Older Entries', 'philna')); ?></span>
	<?php endif; ?>
	<div class="fixed"></div>
</div>
<?php endif; ?>
<?php get_footer(); ?>