<div class="asides">
	<div class="navigation">
		<div class="edge-nav"></div>
		
		<?php wp_page_menu('show_home=1&sort_column=menu_order'); ?>

	</div><!-- end navigation -->
	
	<div class="column one">
			<h2 class="featured">Featured</h2>
			
						<?php $my_query = new WP_Query('category_name=featured&showposts=4'); ?>
						<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
							<div class="featured-post">
							
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link f&uuml;r <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<p class="meta">Verfasst von <a href="<?php the_author(); ?>"><?php the_author(); ?></a> am <?php the_time('j. F Y') ?></p>
			            <?php the_excerpt(); ?>
			<p class="more"><a href="<?php the_permalink() ?>">Weiterlesen</a></p>
					</div>

					<?php endwhile; ?>

	</div><!-- end column -->
	
</div><!-- end asides -->