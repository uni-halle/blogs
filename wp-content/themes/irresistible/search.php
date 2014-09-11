<?php get_header(); ?>

	<div id="content">

		<div id="main">
			
			<h2 class="title">Search Results</h2>
		<?php if (have_posts()) : ?>	
			
			<?php while (have_posts()) : the_post(); ?>
			<div class="box1 clearfix">
				<div class="post">

					<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
					
					<p><?php the_excerpt(); ?></p>

				</div>
			</div>
			<?php endwhile; ?>
				
					<div class="navigation nav clearfix">
						<div class="fl"><?php next_posts_link('&laquo; Older Entries') ?></div>
						<div class="fr"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
					</div>
			
	
		<?php else : ?>
	
			<h2 class="center">No results under "<?php echo $_GET['s']; ?>"</h2>
	
		<?php endif; ?>
	
		</div><!-- / #main -->

<?php get_sidebar(); ?>

	</div><!-- / #content -->

<?php get_footer(); ?>