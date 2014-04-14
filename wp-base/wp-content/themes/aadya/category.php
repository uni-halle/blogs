<?php
/**
 * The template for displaying Category pages.
 *
 * Used to display archive-type pages for posts in a category.
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
		
			<div class="panel panel-warning">
				<div class="panel-heading">
					<h1 class="archive-title"><?php printf( __( 'Category Archives: %s', 'aadya' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h1>
				</div>
				<?php if ( category_description() ) : // Show an optional category description ?>
				<div class="panel-body">					
						<div class="archive-meta"><p><?php echo category_description(); ?></p></div>					
				</div>
				<?php endif; ?>
			</div>	
		
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			endwhile;

			aadya_content_nav( 'nav-below' );
			?>

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