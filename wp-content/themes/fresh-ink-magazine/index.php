<?php get_header(); ?>

<div id="content">

<?php if (have_posts()) : ?>

	<div class="content-header"></div>

<?php while (have_posts()) : the_post(); ?>

	<div>
	 <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
	
		<h2 class="largeheadline"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'magazinetheme'), the_title_attribute('echo=0')); ?>">
		  <?php the_title(); ?>
		</a></h2>

			
	<div><span class="smallheadline1">
			<?php _e("Written By: ", "magazinetheme"); ?><?php the_author(); ?>  
			<em>
			
		    </em></span><span class="smallheadline2">-
		   
		 
		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'magazinetheme'), the_title_attribute('echo=0')); ?>"> <?php the_time('M'); ?>&#8226;
		    <?php the_time('d'); ?>&#8226;<?php the_time('y'); ?></a></span></div>	

		<div class="entry">
			<?php the_content(); ?>
		</div>

		<?php wp_link_pages(); ?>
        
        <div class="postmetadata">
			
          
			<?php edit_post_link('Edit'); ?>
			 <?php _e("Posted in ", "magazinetheme"); ?>
				<?php the_category(', ') ?> <?php the_tags(', ') ?>
				 <strong>|</strong> 
				<?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
				

			
			
			
		</div>
	</div>
	</div>
	<!-- end of post -->

<?php endwhile; ?>
<div class="navigationtest">
			 
<?php get_pagination() ?>  

		</div>


<?php else : /* NO posts */

	if ( '' != get_404_template() )
	 get_template_part('404');
	else
		echo( "<h3><?php _e( 'Sorry, but you are looking for something that isn't here.', 'magazinetheme' ); ?></h3>" );

endif; ?>


</div><!-- end #content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
