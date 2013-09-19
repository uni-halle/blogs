<?php get_header(); ?>

<div class="main">
	
	<?php include ('column-one.php'); ?>

		<div class="content">

			<div class="column two">
				<div class="edge-alt"></div>
				
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="entry-extended">
				<h1 class="pagetitle"><?php the_title(); ?></h1>
				
				<?php the_content('<p class="serif">Weiterlesen &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Seiten:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		<?php edit_post_link('Seite bearbeiten.', '<p>', '</p>'); ?>
		</div>
		<?php endwhile; endif; ?>
	

	</div><!-- end column -->
</div><!-- end content -->
<?php get_footer(); ?>