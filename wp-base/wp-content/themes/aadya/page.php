<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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
	<div id="primary" class="site-content">
		<div id="content" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php //comments_template( '', true ); ?>
			<?php endwhile; // end of the loop. ?>
		</div><!-- #content -->
	</div><!-- #primary -->
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