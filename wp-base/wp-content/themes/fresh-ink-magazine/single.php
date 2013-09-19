<?php get_header(); ?>

<div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>



	 <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>><h2 class="largeheadline">
		  <?php the_title(); ?></h2>

			
	<div><span class="smallheadline1">
			Written By: <?php the_author(); ?>  
			<em>
			
		    </em></span><span class="smallheadline2">-
		    <?php the_time('M'); ?>&#8226;
		    <?php the_time('d'); ?>&#8226;<?php the_time('y'); ?></span></div>	


	

			<div class="entry">
				<?php the_content(); ?>
              <?php wp_link_pages() ?>
			</div>

		<div class="postmetadata">
			
          
			<?php edit_post_link('Edit'); ?>
			<?php _e(" Posted in ", "magazinetheme"); ?>
				<?php the_category(', ') ?> <?php the_tags(', ') ?>
				 <strong>|</strong> 
				<?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
				
</div>
			
		<div class="navigationtest">
			 
<?php get_pagination() ?>  

		</div>	
			
		</div>
			
		<p class="small">
		<?php printf(__("You can follow any responses to this entry through the <a href='%s'>RSS 2.0</a> feed.", "default"), get_post_comments_feed_link()); ?> 
		<?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
			// Both Comments and Pings are open ?>
			<?php printf(__('You can <a href="#respond">leave a response</a>, or <a href="%s" rel="trackback">trackback</a> from your own site.', 'magazinetheme'), get_trackback_url(false)); ?>
		<?php } elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) {
			// Only Pings are Open ?>
			<?php printf(__('Responses are currently closed, but you can <a href="%s" rel="trackback">trackback</a> from your own site.', 'magazinetheme'), get_trackback_url(false)); ?>
		<?php } elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
			// Comments are open, Pings are not ?>
			<?php _e('You can skip to the end and leave a response. Pinging is currently not allowed.', 'magazinetheme'); ?>
		<?php } elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) {
			// Neither Comments, no Pings are open ?>
			<?php _e('Both comments and pings are currently closed.', 'magazinetheme'); ?>
		<?php } ?>
		</p>

	<div class="marg">
	<?php comments_template(); ?></div><!-- end of post -->

<?php endwhile; ?>

<?php else : /* NO posts */

	if ( '' != get_404_template() )
	 get_template_part('404');
	else
		echo( "<h3><?php _e( 'Upss, not found...', 'magazinetheme' ); ?></h3>" );

endif; ?>
</div>
	
<?php get_sidebar(); ?>


<?php get_footer(); ?>
