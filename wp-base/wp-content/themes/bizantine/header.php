<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Bizantine
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="wrapper-one">
<div id="wrapper-two">
<div id="wrapper-three">
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
    <div class="header-social">
        
       <div class="responsive-container">
            
            
       </div>
        
    </div> 
       
	<header id="masthead" class="site-header" role="banner">
    
    	<div class="responsive-container">
        
        <?php if( of_get_option('logo_layout_style') == 'sbys' ) : ?>
        
        	<div class="site-header-half-width-logo">
    
                <div class="site-branding">
                	
                    <?php if( of_get_option('logo_image') ) : ?>
                    	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo of_get_option('logo_image'); ?>" /></a>
                    <?php else : ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                        <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
                    <?php endif; ?>
                    
                </div>
                
            </div>
            
            <div class="site-header-half-width-nav">
    
                <nav id="site-navigation" class="main-navigation" role="navigation">
                    <div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'Bizantine' ); ?>"><?php _e( 'Skip to content', 'Bizantine' ); ?></a></div>
        
                    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'main-nav', 'fallback_cb' => 'Bizantine_backupmenu'  ) ); ?>
                </nav><!-- #site-navigation -->
                
            </div>                
            
        <?php else : ?>
        
            <div class="site-branding">
            
            	<?php if( of_get_option('logo_image') ) : ?>
                	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo of_get_option('logo_image'); ?>" /></a>
                <?php else : ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                        <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
                <?php endif; ?>
                
            </div> 
        
        <?php endif; ?>
            
    	</div><!-- #Responsive-Container -->            
            
	</header><!-- #masthead -->
    
    <div id="inner-container"> 
    
    <?php if( !of_get_option('logo_layout_style') || of_get_option('logo_layout_style') == 'onebone' ) : ?>
    <div class="main-navigation-inner">
    
        <nav id="site-navigation" class="main-navigation" role="navigation">
                        <div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'Bizantine' ); ?>"><?php _e( 'Skip to content', 'Bizantine' ); ?></a></div>
            
                        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'main-nav', 'fallback_cb' => 'Bizantine_backupmenu'  ) ); ?>
        </nav><!-- #site-navigation -->
        
    </div>
    <?php endif; ?>        
    
	<?php 
		
		if( is_front_page() && ( !of_get_option('show_Bizantine_slider_home') || of_get_option('show_Bizantine_slider_home') == 'true' ) ) {
			get_template_part( 'slider', 'cheader' );
		}
				
		if( ( (is_page() && !is_front_page() ) || is_404() ) && ( !of_get_option('show_Bizantine_slider_page') || of_get_option('show_Bizantine_slider_page') == 'true' ) ) {
			get_template_part( 'slider', 'cheader' );
		}
		
		if( ( is_single() ) && ( !of_get_option('show_Bizantine_slider_single') || of_get_option('show_Bizantine_slider_single') == 'true' ) ) {
			get_template_part( 'slider', 'cheader' );
		}
		
		if( ( is_archive() || (is_home() && !is_front_page()) ) && ( !of_get_option('show_Bizantine_slider_archive') || of_get_option('show_Bizantine_slider_archive') == 'true' ) ) {
			get_template_part( 'slider', 'cheader' );
		}
		
	?>

	<div id="main" class="site-main">
    
    	<div class="responsive-container">    
        
    		<div class="content-container">         
