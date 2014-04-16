<?php
/**
 * Template Name: Front Page With Slider Template
 *
 * Page template for Front Page
 *
 * @package aadya
 * @since aadya 0.1
 */

get_header(); ?>

<?php
	$divclass = (of_get_option('front_page_widget_section_count')=='4') ? '3' : '4';
	$mysidebars = wp_get_sidebars_widgets();
	$total_widgets = count( $mysidebars['aadya_front_page_widget_row_one'] );
	if($total_widgets <= 3) $aadya_cols = 4;
	else $aadya_cols = 3;
	
	$show_row_titles = of_get_option('showhide_row_titles');

?>
<?php if ( is_active_sidebar( 'aadya_front_page_widget_row_one' ) ) : ?>
<div class="container" >
	<?php $headline = of_get_option('front_page_row_one_title');
		  if(!empty($headline) && $show_row_titles):
	?>
		  <div class="headline"><h2><?php echo of_get_option('front_page_row_one_title');?></h2></div>
	<?php endif; ?>	
	<div class="row">			
			<?php dynamic_sidebar( 'aadya_front_page_widget_row_one' ); ?>				
	</div>	
</div>	
<?php endif; ?>	


<?php if ( is_active_sidebar( 'aadya_front_page_widget_row_two' ) ) : ?>
<div class="container" >
	<?php $headline = of_get_option('front_page_row_two_title');
		  if(!empty($headline) && $show_row_titles):
	?>
	<div class="headline"><h2><?php echo of_get_option('front_page_row_two_title');?></h2></div>
	<?php endif; ?>	
	<div class="row">			
			<?php dynamic_sidebar( 'aadya_front_page_widget_row_two' ); ?>				
	</div>	
</div>	
<?php endif; ?>	

<?php if ( is_active_sidebar( 'aadya_front_page_widget_row_three' ) ) : ?>
<div class="container" >
	<?php $headline = of_get_option('front_page_row_three_title');
		  if(!empty($headline) && $show_row_titles):
	?>
	<div class="headline"><h2><?php echo of_get_option('front_page_row_three_title');?></h2></div>
	<?php endif; ?>	
	<div class="row">			
			<?php dynamic_sidebar( 'aadya_front_page_widget_row_three' ); ?>				
	</div>	
</div>	
<?php endif; ?>	

<?php if ( is_active_sidebar( 'aadya_front_page_widget_row_four' ) ) : ?>
<div class="container" >
	<?php $headline = of_get_option('front_page_row_four_title');
		  if(!empty($headline) && $show_row_titles):
	?>
	<div class="headline"><h2><?php echo of_get_option('front_page_row_four_title');?></h2></div>
	<?php endif; ?>	
	<div class="row">			
			<?php dynamic_sidebar( 'aadya_front_page_widget_row_four' ); ?>				
	</div>	
</div>	
<?php endif; ?>	


<?php get_footer(); ?>