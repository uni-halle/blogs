<?php global $graphene_settings; graphene_parent_return_link( $post ); ?>

<?php if ( $graphene_settings['hide_parent_content_if_empty'] && $post->post_content == '' ) : ?>
	<h1 class="page-title">
		<?php if ( get_the_title() == '' ) { _e( '(No title)', 'graphene' ); } else { the_title(); } ?>
	</h1>
<?php else : ?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix post' ); ?>>
	<?php do_action( 'graphene_before_post' ); ?>
	
	<div class="entry clearfix">                
		
		<?php /* Post title */ ?>
        <h1 class="post-title entry-title">
			<?php if ( get_the_title() == '' ) { _e( '(No title)', 'graphene' ); } else { the_title(); } ?>
			<?php do_action( 'graphene_page_title' ); ?>
        </h1>
		
		<?php graphene_entry_meta(); ?>
		
		<?php /* Post content */ ?>
		<div class="entry-content clearfix">
			<?php do_action( 'graphene_before_page_content' ); ?>
				
			<?php /* Social sharing buttons at top of post */ ?>
            <?php if ( stripos( $graphene_settings['addthis_location'], 'top' ) !== false ) { graphene_addthis( get_the_ID() ); } ?>
            
            <?php /* The full content */ ?>
            <?php the_content(); ?>
			<?php graphene_link_pages(); ?>
			<?php do_action( 'graphene_after_page_content' ); ?>
		</div>
		
		<?php /* Post footer */ graphene_entry_footer(); ?>
	</div>
</div>
<?php endif; ?>

<?php /* For printing: the permalink */
	if ( $graphene_settings['print_css'] ) {
		echo graphene_print_only_text( '<span class="printonly url"><strong>' . __( 'Permanent link to this article:', 'graphene' ) . ' </strong><span>' . get_permalink() . '</span></span>' );
	}
?>

<?php 
/**
 * Display Adsense advertising
 * See graphene_adsense() function in functions.php
*/ 
graphene_adsense(); ?>

<?php /* List the child pages */ ?>
<?php get_template_part( 'loop', 'children' ); ?>

<?php /* Get the comments template */ ?>
<?php comments_template(); ?>

<?php do_action( 'graphene_loop_footer' ); ?>