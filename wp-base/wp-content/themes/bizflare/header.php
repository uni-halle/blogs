<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package BizFlare
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
        
        <?php if( !of_get_option('logo_layout_style') || of_get_option('logo_layout_style') == 'sbys' ) : ?>
        
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
                    <div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'BizFlare' ); ?>"><?php _e( 'Skip to content', 'BizFlare' ); ?></a></div>
        
                    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'main-nav', 'fallback_cb' => 'BizFlare_backupmenu'  ) ); ?>
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
                
            

            <nav id="site-navigation" class="main-navigation nav-border-top" role="navigation">
                    <div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'BizFlare' ); ?>"><?php _e( 'Skip to content', 'BizFlare' ); ?></a></div>
        
                    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'main-nav', 'fallback_cb' => 'BizFlare_backupmenu'  ) ); ?>
            </nav><!-- #site-navigation --> 
                
        
        <?php endif; ?>
            
    	</div><!-- #Responsive-Container -->            
            
	</header><!-- #masthead -->
    
	<?php 
		
		if( is_front_page() && ( !of_get_option('show_slider_home') || of_get_option('show_slider_home') == 'true' ) ) {
			get_template_part( 'slider' );
		}
				
		if( ( (is_page() && !is_front_page() ) || is_404() ) && ( !of_get_option('show_slider_page') || of_get_option('show_slider_page') == 'true' ) ) {
			get_template_part( 'slider' );
		}
		
		if( ( is_single() ) && ( !of_get_option('show_slider_single') || of_get_option('show_slider_single') == 'true' ) ) {
			get_template_part( 'slider' );
		}
		
		if( ( is_archive() || (is_home() && !is_front_page()) ) && ( !of_get_option('show_slider_archive') || of_get_option('show_slider_archive') == 'true' ) ) {
			get_template_part( 'slider' );
		}
		
	?>

	<?php if( ( is_front_page() ) && of_get_option('homepage_layout') == 'bsix' ) : ?>
    
    <div class="bizsix-welcome-container">
            
                <div class="responsive-container">
                
                    <div class="bizsix-welcome">
                    
                        <h1>
                            <?php 
                                if( of_get_option('welcome_headline_bizsix') ){
                                    echo esc_html( of_get_option('welcome_headline_bizsix') );
                                }else {
                                    _e('Welcome Headline Comes Here',  'BizFlare');
                                }
                            ?>    
                        </h1>
                        
                        <p>
                            <?php 
                                if( of_get_option('welcome_text_bizsix') ){
                                    echo esc_html( of_get_option('welcome_text_bizsix') );
                                }else {
                                    _e('You can change this text in welcome text box of welcome section block in Biz six tab of theme options page. You can change this text in welcome text box of welcome section block in Biz six tab of theme options page.',  'BizFlare');
                                }
                            ?>                                
                        </p>
                        
                    </div><!-- .bizsix-welcome -->
                
                </div><!-- .responsive-container -->
            
    </div><!-- .bizsix-welcome-container -->
    
    <?php endif; ?>

	<div id="main" class="site-main">
    
    	<div class="responsive-container">    
        
    		<div class="content-container">         
