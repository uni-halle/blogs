<?php get_header(); ?>

<div id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>





	<div>

	  <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div><h2 class="largeheadline">
		  <?php the_title(); ?></h2></div>

			
	<div><span class="smallheadline1">
			<?php _e("Written By: ", "magazinetheme"); ?><?php the_author(); ?>  
			<em>
			
		    </em></span></div>	
		<div class="entry">
			<?php the_content(); ?>
		</div>
<?php wp_link_pages(); ?>
		<div class="postmetadata">
			<?php edit_post_link('Edit'); ?>
				
		</div>
</div>

</div>


	
	<div class="marg"><?php comments_template(); ?></div>

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

