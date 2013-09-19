<?php get_header(); ?>
	       
    <div id="bd">

		<div id="yui-main">
			
			<div class="yui-b">
				
				<div id="content">

				<?php if (have_posts()) : ?>

					<h1 class="post-title">Search Results for <strong>'<?php the_search_query(); ?>'</strong></h1>
			
					<div class="navigation">
						<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
						<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
					</div>			
			
					<?php while (have_posts()) : the_post(); ?>
			
						<div <?php post_class() ?>>
							<div class="meta-date"><?php the_time('l, F jS, Y') ?> at <?php the_time('g:ia') ?></div>
							<h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							<div class="meta-author">Posted by <?php the_author(); ?> in <?php the_category(', '); ?></div>
							
							<div class="entry">
								<?php the_excerpt(); ?>
							</div>
								
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
			
					<?php endwhile; ?>
			
					<div class="navigation">
						<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
						<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
					</div>
			
				<?php else : ?>
			
					<h2 class="center">No posts found. Try a different search?</h2>
					<?php get_search_form(); ?>
			
				<?php endif; ?>
			
				</div>
				
			</div><!-- end .yui-b -->
								
		</div><!-- end #yui-main -->
		
		<?php get_sidebar(); ?>

	 	</div><!-- end #bd -->        
        
<?php get_footer(); ?>     
