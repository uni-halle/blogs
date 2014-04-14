<?php
/**
 * The template for displaying Author Archive pages.
 *
 * Used to display archive-type pages for posts by an author.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Aadya
 * @subpackage Aadya
 * @since Aadya 1.0.0
 */

get_header(); ?>
<?php 
	$aadya_layout = of_get_option('page_layouts'); 
	$aadya_col =  aadya_get_content_cols();
?>

<?php
	if($aadya_layout ==  "sidebar-content" || $aadya_layout ==  "sidebar-content-sidebar") {
		get_sidebar('left');
	}	
	if($aadya_layout == "content-sidebar" || $aadya_layout ==  "sidebar-content") {
		$aadya_smcol = 8;
	} elseif($aadya_layout == "sidebar-content-sidebar" || $aadya_layout == "content-sidebar-sidebar") {
		$aadya_smcol = 6;
	}	
?>

<div class="col-xs-12 col-sm-<?php echo $aadya_smcol;?> col-md-<?php echo $aadya_col;?>" role="content">
<div id="primary" class="site-content post-content">

		<?php if ( have_posts() ) : ?>

			<?php
				/* Queue the first post, that way we know
				 * what author we're dealing with (if that is the case).
				 *
				 * We reset this later so we can run the loop
				 * properly with a call to rewind_posts().
				 */
				the_post();
			?>

			

			<?php
				/* Since we called the_post() above, we need to
				 * rewind the loop back to the beginning that way
				 * we can run the loop properly, in full.
				 */
				rewind_posts();
			?>


			<?php
			// If a user has filled out their description, show a bio on their entries.
			if ( get_the_author_meta( 'description' ) ) : ?>
			<div class="panel panel-default author-info">	
			<div class="panel-body">
			<div class="media author-avatar">
			  <a class="pull-left" href="#">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'aadya_author_bio_avatar_size', 70 ) ); ?>
			  </a>
			  <div class="media-body author-description">
				<h3 class="media-heading"><?php printf( __( 'About %s', 'aadya' ), get_the_author() ); ?></h3>
				<p><?php the_author_meta( 'description' ); ?></p>

			  </div><!-- .author-description -->
			</div><!-- .author-avatar -->
			</div>
			</div><!-- .author-info -->				
			<?php endif; ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php aadya_content_nav( 'nav-below' ); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->

</div><!-- .col-md-<?php echo $aadya_col;?> -->
<?php
	if($aadya_layout ==  "content-sidebar-sidebar") {
		get_sidebar('left');
	}	
?>
<?php	
	if($aadya_layout ==  "content-sidebar" || 
	   $aadya_layout ==  "sidebar-content-sidebar" ||
	   $aadya_layout ==  "content-sidebar-sidebar") {		
		get_sidebar();
	}
?>
<?php get_footer(); ?>