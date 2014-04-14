<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Aadya
 * @subpackage Aadya
 * @since Aadya 1.0.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">							   
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="bodywrap">
	<!-- Wrap all page content here -->  
	<div id="content-wrap">	
	
	<?php 
		$aadya_site_logo = of_get_option('site_logo');
		$aadya_header =  get_header_textcolor();	
		$aadya_header_background = of_get_option('header_background');
		
		//check and get if any header image set from WP Settings
		$wp_header_image = get_header_image();		
		if(empty($aadya_header_background) && !empty($wp_header_image)):
			$aadya_header_background = get_header_image();
		endif;
		
	?>	
	<?php if ( $aadya_header !== "blank" ) : ?>
		<header class="site-header" role="banner"> 	


		<div class="container">		
		 <div class="row logo-row">
		  <div class="col-sm-4 col-md-4 pull-left">
			<?php if ( $aadya_site_logo != '' ) : ?>
			<a href="<?php echo esc_url( home_url( '/' )); ?>"><img src="<?php echo esc_url($aadya_site_logo); ?>" alt="<?php bloginfo('description'); ?>" class="img-responsive" /></a>
			<?php elseif($aadya_site_logo == '' || !isset($aadya_site_logo)): ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1><h6><?php bloginfo( 'description' ); ?></h6>
			
			<?php endif; ?>					
		  </div>	
		  <div class="col-sm-4 col-md-4 hidden-xs hidden-sm">	
		  <div class="pull-left">
				<?php if ( is_active_sidebar( 'aadya_header_left' ) ) : ?>
					<?php dynamic_sidebar( 'aadya_header_left' ); ?>	
				<?php endif; ?>		
		  </div>			
		  </div>
		  <div class="col-md-4 hidden-xs hidden-sm">
			<div class="pull-right">
				<?php if ( is_active_sidebar( 'aadya_header_right' ) ) : ?>
					<?php dynamic_sidebar( 'aadya_header_right' ); ?>	
				<?php endif; ?>	
			</div>
		  </div>
		</div>	  
		</div>
		
	</header>
	<?php endif; ?>

    <div class="navbar-wrapper">
		<div class="navbar navbar-inverse navbar-static-top" role="navigation">
		  <div class="container">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand visible-xs" href="<?php echo esc_url (home_url( '/' )); ?>"><i class="fa fa-home"></i>
			  </a>
			</div>
			<div class="navbar-collapse collapse">
			<?php wp_nav_menu( array( 
								'theme_location' => 'primary', 
								'menu_class' => 'nav navbar-nav', 
								'depth' =>4,
								'container' => false, 
								'fallback_cb' => false, 
								'walker' => new wp_bootstrap_navwalker() ) ); ?>	
			</div><!--/.nav-collapse -->
		  </div>
		</div>
	</div>
	
	<?php
		/**
		 * We need to put our slider code here as we are doing a full width carousel 	
		*/
		if (is_page_template( 'page-templates/front-page-with-slider.php' )) {
			$display_slider = of_get_option('display_slider');
			if(isset($display_slider) && $display_slider==true) {
				get_template_part( 'slides', 'index' );
				//get_template_part( 'test', 'slides' );
			}			
		}	
	?>
	
    <div class="container" id="main-container">
	<div class="row" id="main-row">

