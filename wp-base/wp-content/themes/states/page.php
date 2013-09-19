<?php get_header(); ?>
	       
    <div id="bd">

		<div id="yui-main">
			
			<div class="yui-b">
				
				<div id="content">

					<?php if (have_posts()) : ?>
				
						<?php while (have_posts()) : the_post(); ?>
				
							<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
								<h1 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
								
								<div class="entry">
									<?php the_content('Read the rest of this entry &raquo;'); ?>
								</div>
				
							</div>
							
							<?php comments_template(); ?>
				
						<?php endwhile; ?>
				
						<div class="navigation">
							<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
							<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
						</div>
				
					<?php else : ?>
				
						<h2 class="center">Not Found</h2>
						<p class="center">Sorry, but you are looking for something that isn't here.</p>
						<?php get_search_form(); ?>
				
					<?php endif; ?>
				
				</div>
				
			</div><!-- end .yui-b -->
								
		</div><!-- end #yui-main -->
		
		<?php get_sidebar(); ?>

	 	</div><!-- end #bd -->        
        
<?php get_footer(); ?>     