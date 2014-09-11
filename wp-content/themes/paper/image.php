<?php get_header(); ?>
<div id="multi_posts">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		
		
		
		<div class="post" id="post-<?php the_ID(); ?>">
			
			
<h2><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <?php the_title(); ?></h2>
			
			
			
			<div class="entry">
													<p class="attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></p>
				<div class="caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?></div>
												
												
				<div class="navigation">
					<div class="alignleft"><?php previous_image_link() ?></div>
					<div class="alignright"><?php next_image_link() ?></div>
				</div>
				
												
												</div>
												
												<p class="edit_comments">
														<span class="edit"><?php edit_post_link('+ edit +', '', '  '); ?></span>
														<span class="commentct"><?php comments_popup_link('0', '1', '%'); ?><br /><span class="c_title">comments</span></span>
												</p>
												<p class="postmetadata">
													<span class="category">Posted in <?php the_category(', ') ?></span> 	<span class="tags"><?php the_tags(' ',' '); ?></span>
												</p>						
			
			
<?php comments_template(); ?>
<?php endwhile; else: ?>

<p>Sorry, no attachments matched your criteria.</p>

<?php endif; ?>
</div><!-- END POST -->
</div><!-- END MULTIPOSTS -->
<?php get_footer(); ?>




	
