<?php
/**
 * @package WordPress
 * @subpackage MLUphysik Theme
 */
?>
<?php get_header(); ?>
<!-- single.php -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="post clearfix">

	<?php
    if (has_post_thumbnail() ) {
		//get full-sized image
		$full_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full-size');
	?>
		<div class="post-thumbnail">
			<a href="<?php echo $full_img[0]; ?>" title="<?php the_title(); ?>" rel="prettyPhoto" class="prettyphoto-link"><?php the_post_thumbnail('medium'); ?></a>
		</div>
		<!-- END post-thumbnail -->
	<?php } ?>

    <div class="entry clearfix">
        
	<h1><?php the_title(); ?></h1>
      
		<?php the_content(); ?>
        <div class="clear"></div>
        
        <?php wp_link_pages(' '); ?>
         
        <div class="post-bottom">
        	<?php the_tags('<div class="post-tags">',' , ','</div>'); ?>
        </div>
        <!-- END post-bottom -->
        
        
        </div>
        <!-- END entry -->
	
	   
        
</div>
<!-- END post -->

<?php endwhile; ?>
<?php endif; ?>
             
<?php //get_sidebar();
 ?>
<?php get_footer(); ?>
