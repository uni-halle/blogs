<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
	
	<div <?php post_class() ?>>
	
		<p class="date"><?php the_time('j.'); ?>
			<span><?php the_time('M'); ?></span>
			<?php the_time('Y'); ?>
		</p>
		<h2 id="post-<?php the_ID(); ?>" class="posttitle"><a href="<?php echo get_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<p class="author">Verfasst von <?php the_author_posts_link(); ?></p>
		
			<div class="entry">
				
				<?php 
					if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
						$width = get_option('thumbnail_size_w') / 2; //get the width of the thumbnail setting
						$height = get_option('thumbnail_size_h') /2; //get the height of the thumbnail setting

						the_post_thumbnail(array($width, $height), array('class' => 'alignleft'));
					} 
				
					if (!empty($post->post_excerpt)) :
					
						the_excerpt(); ?>
						<p><a class="more-link" href="<?php the_permalink(); ?>" rel="bookmark" title="Link zu <?php the_title(); ?>"><?php _e('[ Read On ... ]', 'blogsmlu'); ?></a></p>
						
				<?php else :
					
						the_content(__('[ Read On ... ]', 'blogsmlu'));
					
				endif; ?>
		
			</div>
	
		<p class="postmetadata category-line"><?php the_category(', ') ?> <a class="comments-line" href="<?php comments_link(); ?>" title=""><?php comments_number('0', '1', '%'); ?></a> <?php edit_post_link('Bearbeiten ', '', ''); ?></p>
	
	</div>
	
	<?php endwhile; ?>
	
	<div class="pagenavigation2">
		<div class="alignleft"><?php next_posts_link('&Auml;ltere Beitr&auml;ge') ?></div>
		<div class="alignright"><?php previous_posts_link('Neuere Beitr&auml;ge') ?></div>
	</div>
	
	
	<?php else : ?>
	
	<h2 class="center"><?php _e('Nicht gefunden.'); ?></h2>
	
		<p class="center"><?php _e("Sorry, aber du suchst nach etwas, das nicht hier ist."); ?></p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>
	<?php endif; ?>
	
</div>

<?php get_sidebar('Sidebar'); ?>

<?php get_footer(); ?>