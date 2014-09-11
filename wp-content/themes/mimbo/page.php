<?php get_header(); ?>

	<div id="content">


		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<div class="post" id="post-<?php the_ID(); ?>">
		
					
		<h2 class="pagetitle"><?php the_title(); ?></h2>
			<div class="entry clearfloat">
				<?php the_content(); ?>

				<?php wp_link_pages(array(
				'before' => '<p><strong> '.__('Pages:','Mimbo').' </strong>', 
				'after' => '</p>', 
				'next_or_number' => 'number')); 
				?>

			</div>
		</div>
        
        <?php comments_template(); ?>
        
		<?php endwhile; endif; ?>
	<?php edit_post_link(__('Edit this entry','Mimbo'), '<p>', ' &raquo;</p>'); ?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>