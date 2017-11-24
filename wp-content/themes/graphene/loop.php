<?php global $graphene_settings, $post; ?>

<?php if ( $post->post_type == 'page' && $graphene_settings['hide_parent_content_if_empty'] && $post->post_content == '' ) : ?>
	<h1 class="page-title"><?php if ( get_the_title() == '' ) { _e( '(No title)', 'graphene' ); } else { the_title(); } ?></h1>
<?php else : ?>                

<div id="post-<?php the_ID(); ?>" <?php post_class( 'clearfix post' ); ?>>
	<?php do_action( 'graphene_before_post' ); ?>
	
	<div class="entry clearfix">
    
    	<?php /* Post date is not shown if this is a Page post */ ?>
		<?php if ( stristr( graphene_post_date_setting( get_the_ID() ), 'icon' ) ) echo graphene_post_date(); ?>
        
		<?php /* Post title */ ?>
        <h2 class="post-title entry-title">
			<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'graphene' ), the_title_attribute( 'echo=0' ) ); ?>">
				<?php if ( get_the_title() == '' ) { _e( '(No title)', 'graphene' ); } else { the_title(); } ?>
            </a>
			<?php do_action( 'graphene_post_title' ); ?>
        </h2>
		
		<?php /* Post meta */ ?>
		<?php graphene_entry_meta(); ?>
		
		<?php /* Post content */ ?>
		<div class="entry-content clearfix">
			<?php do_action( 'graphene_before_post_content' ); ?>
			
			<?php if ( ( is_home() && ! $graphene_settings['posts_show_excerpt'] ) || is_singular() || ( ! is_singular() && ! is_home() && $graphene_settings['archive_full_content'] ) ) : ?>
				
				<?php /* Social sharing buttons at top of post */ ?>
				<?php if ( stripos( $graphene_settings['addthis_location'], 'top' ) !== false) { graphene_addthis( get_the_ID() ); } ?>
				
				<?php /* The full content */ ?>
				<?php the_content( '<span class="btn">' . __( 'Continue reading', 'graphene' ) . ' <i class="fa fa-arrow-circle-right"></i></span>' ); ?>

			<?php else : ?>

				<?php /* The post thumbnail */
					if ( has_post_thumbnail( get_the_ID() ) ) { ?>
						<p class="excerpt-thumb">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'graphene' ), the_title_attribute( 'echo=0' ) ); ?>">
								<?php the_post_thumbnail( apply_filters( 'graphene_excerpt_thumbnail_size', 'medium' ) ); ?>
							</a>
						</p>
						<?php
					} else {
						echo graphene_get_post_image( get_the_ID(), apply_filters( 'graphene_excerpt_thumbnail_size', 'medium' ), 'excerpt' );	
					}
				?>
                
                <?php /* Social sharing buttons at top of post */ ?>
				<?php if ( stripos( $graphene_settings['addthis_location'], 'top' ) !== false && $graphene_settings['show_addthis_archive'] ) { graphene_addthis( get_the_ID() ); } ?>
                
				<?php /* The excerpt */ ?>
				<?php the_excerpt(); ?>
                
			<?php endif; ?>
			
			<?php graphene_link_pages(); ?>
			
			<?php do_action( 'graphene_after_post_content' ); ?>
			
		</div>
		
		<?php /* Post footer */ graphene_entry_footer(); ?>
	</div>
</div>
<?php endif; ?>

 <?php /* For printing: the permalink */
	if ( $graphene_settings['print_css']) {
		echo graphene_print_only_text( '<span class="printonly url"><strong>' .__( 'Permanent link to this article:', 'graphene' ). ' </strong><span>' . get_permalink(). '</span></span>' );
	} 
?>

<?php /* Display Adsense advertising */ ?>
<?php if ( ! is_front_page() || ( is_front_page() && $graphene_settings['adsense_show_frontpage'] ) ) { graphene_adsense(); } ?>

<?php do_action( 'graphene_loop_footer' ); ?>