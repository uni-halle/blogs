<?php get_header(); ?>
	       
    <div id="bd">

		<div id="yui-main">
			
			<div class="yui-b">
				
				<div id="content">

					<?php if (have_posts()) : ?>
				
						<?php while (have_posts()) : the_post(); ?>
				
							<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
								<div class="meta-date"><?php the_date() ?> at <?php the_time() ?></div>
								<h2 class="post-title"><a href="<?php the_permalink() ?>" class="post-title-link" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
								<div class="meta-author">Posted by <?php the_author_posts_link(); ?> in <?php the_category(', '); ?></div>
								
								<div class="entry">
									<?php the_content('Read the rest of this entry &raquo;'); ?>
								</div>
				
								<div class="postmetadata primary">
									<span class="floatleft">
										<?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?>
									</span>
									<span class="floatright washout">
										<?php the_tags('Tags: ', ', ', ' |'); ?> <?php edit_post_link('Edit', '', ''); ?>
									</span>
									<div class="clear"></div>
								</div>
							</div>
				
						<?php endwhile; ?>
				
						<div class="navigation">
							<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
							<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
						</div>
				
					<?php else : ?>
				
						<h2 class="center">Not Found</h2>
						<p class="center">Sorry, but you are looking for something that isn't here.</p>
				
					<?php endif; ?>
				
				</div>
				
			</div><!-- end .yui-b -->
								
		</div><!-- end #yui-main -->
		
		<?php get_sidebar(); ?>

	 	</div><!-- end #bd -->        
        
<?php get_footer(); ?>     
