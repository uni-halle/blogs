<?php get_header(); ?>
	       
    <div id="bd">

		<div id="yui-main">
			
			<div class="yui-b">
				
				<div id="content">

					<?php if (have_posts()) : ?>
				
						<?php while (have_posts()) : the_post(); ?>
				
							<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
								<div class="meta-date"><?php the_time('l, F jS, Y') ?> at <?php the_time('g:ia') ?></div>
								<h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
								<div class="meta-author">Posted by <?php the_author(); ?></div>
								
								<div class="entry">
									<?php the_content('Read the rest of this entry &raquo;'); ?>
								</div>
								
								<?php wp_link_pages(array('before' => '<p class="multi-page"><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				
								<div class="postmetadata secondary">
									<span class="floatleft">
										<?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?>
									</span>
									<span class="floatright washout">
										<?php the_tags('Tags: ', ', ', ' '); ?> <?php edit_post_link('Edit', ' | ', ''); ?>
									</span>
									<div class="clear"></div>
								</div>
							</div>
							
							<?php comments_template(); ?>
				
						<?php endwhile; else : ?>
				
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