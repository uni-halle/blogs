<?php 
/*
YARPP Template: Franz Josef Single
Author: Graphene Themes Solutions
Description: YARPP template for displaying related posts in Franz Josef theme's single posts page
*/
global $graphene_settings;
if ( have_posts() ) :
?>
<div class="related-posts">
	<?php do_action( 'graphene_related_posts_top' ); ?>
    <h3 class="section-title-sm"><?php _e( 'Related posts', 'graphene' ); ?></h3>
    
    <div class="row">
		<?php while ( have_posts() ) : the_post(); ?>
            <div class="related-post col-sm-4" id="related-post-<?php the_ID(); ?>">
                <div class="image"><?php graphene_post_image( get_the_ID(), 'medium' ); ?></div>
                <h4 class="item-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
                <?php do_action( 'graphene_related_post' ); ?>
            </div>
        <?php endwhile; ?>
    </div>
    
    <?php do_action( 'graphene_related_posts_bottom' ); ?>
</div>
<?php endif; ?>