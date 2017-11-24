<?php global $graphene_settings; ?>

<?php /* Post navigation */ ?>
<?php graphene_post_nav(); ?>
        
<div id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix post' ); ?>>
	<?php do_action( 'graphene_before_post' ); ?>
	
	<div class="entry clearfix">
    	
        <?php /* Post date */ ?>
        <?php if ( stristr( graphene_post_date_setting( get_the_ID() ), 'icon' ) ) echo graphene_post_date(); ?>
        
		<?php /* Post title */ ?>
        <h1 class="post-title entry-title">
			<?php if ( get_the_title() == '' ) { _e( '(No title)', 'graphene' ); } else { the_title(); } ?>
			<?php do_action( 'graphene_post_title' ); ?>
        </h1>
		
		<?php graphene_entry_meta(); ?>
		
		<?php /* Post content */ ?>
		<div class="entry-content clearfix">
			<?php do_action( 'graphene_before_post_content' ); ?>
				
			<?php /* Social sharing buttons at top of post */ ?>
			<?php if ( stripos( $graphene_settings['addthis_location'], 'top' ) !== false ) { graphene_addthis( get_the_ID() ); } ?>
				
			<?php /* The full content */ ?>
			<?php the_content(); ?>
			<?php graphene_link_pages(); ?>
			<?php do_action( 'graphene_after_post_content' ); ?>
		</div>
		
		<?php /* Post footer */ graphene_entry_footer(); ?>
	</div>

</div>

<?php graphene_single_author_bio(); ?>

<?php /* For printing: the permalink */
	if ( $graphene_settings['print_css'] ) {
		echo graphene_print_only_text( '<span class="printonly url"><strong>' . __( 'Permanent link to this article:', 'graphene' ) . ' </strong><span>' . get_permalink() . '</span></span>' );
	}
?>

<?php 
/**
 * Display Adsense advertising for single post pages 
 * See graphene_adsense() function in functions.php
*/ 
graphene_adsense(); ?>

<?php /* Get the comments template for single post pages */ ?>
<?php comments_template(); ?>

<?php do_action( 'graphene_loop_footer' ); ?>