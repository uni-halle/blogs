<?php get_header(); ?>
<div class="main">
	
	<?php include ('column-one.php'); ?>

		<div class="content">
			<div class="column two">
				<div class="edge-alt"></div>
				
	<?php if (have_posts()) : ?>


		<h2 class="pagetitle">Suchergebnisse f&uuml;r '<?php the_search_query(); ?>'</h2>

		<?php while (have_posts()) : the_post(); ?>
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
											
	                <div class="entry-thumb">
	                	<?php if (function_exists('images')) images('1', '82', '82', '', false); ?>
	                </div>
					<div class="entry">
<p class="meta"><?php the_time('j. F Y') ?> - <span class="category"><?php the_category(', '); ?></span> <?php edit_post_link('Bearbeiten', ' - ', ''); ?></p>


						<?php the_excerpt(); ?>
						<p class="more"><a href="<?php the_permalink() ?>">Weiterlesen</a></p>


					</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h1 class="pagetitle">Nichts gefunden.</h1>
			<div class="entry-extended">
				<p>Vielleicht hilft dir eine weitere Suche:</p>
					
					<p><?php include ('searchform.php'); ?></p>
			</div>

	<?php endif; ?>


		</div><!-- end column -->
	</div><!-- end content -->
	<?php get_footer(); ?>