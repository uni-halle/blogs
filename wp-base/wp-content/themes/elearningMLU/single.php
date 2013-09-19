<?php get_header(); ?>

<div class="main">
	
	<?php include ('column-one.php'); ?>

		<div class="content">
					<div class="column two">
						<div class="edge-alt"></div>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


				<div class="entry-extended">
					<h1><a href="<?php the_permalink(); ?>" title="Permalink f&uuml;r <?php the_title(); ?>"><?php the_title(); ?></a></h1>
									<p class="meta">Verfasst von <a href="<?php the_author(); ?>"><?php the_author(); ?></a> am <?php the_time('j. F Y') ?><?php edit_post_link('Bearbeiten', ' - ', ''); ?></p>
					<?php the_content('<p class="serif">Weiterlesen &raquo;</p>'); ?>
					<p class="meta">Abgelegt in: <?php the_category(', ') ?>.</p>
					

					<?php wp_link_pages(array('before' => '<p><strong>Seiten:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

		<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, keine Beitr&auml;ge vorhanden.</p>

<?php endif; ?>
</div>

		</div><!-- end column -->

	</div><!-- end content -->

	<?php get_footer(); ?>