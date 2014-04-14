<?php $aadya_col =  aadya_get_content_cols(); ?>
<!-- Main Blog Content -->
<div class="col-xs-12 col-sm-8 col-md-<?php echo $aadya_col;?>" role="content">
<div id="primary" class="site-content post-content">
<?php if ( have_posts() ) : ?>
	<?php /* Start the Loop */ ?>
	<?php while ( have_posts() ) : the_post(); ?>
	<?php get_template_part( 'content', get_post_format() ); ?>
	<?php endwhile; ?>
	<?php aadya_content_nav( 'nav-below' ); ?>
<?php else : ?>	
<?php get_template_part( 'content', 'noresults' ); ?>	
<?php endif; // end have_posts() check ?>	
</div><!-- #primary -->
</div> <!-- .col-md-<?php echo $aadya_col;?> .content -->
<!-- End Main Content -->
<?php get_sidebar(); ?>