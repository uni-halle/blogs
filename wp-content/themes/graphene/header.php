<!DOCTYPE html><?php global $graphene_settings; ?>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
    <head>
        <meta charset="<?php esc_attr( bloginfo( 'charset' ) ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <?php do_action( 'graphene_container_before' ); ?>

        <div class="<?php echo ( $graphene_settings['container_style'] == 'boxed' ) ? 'container boxed-wrapper' : 'container-fluid'; ?>">
            
            <?php if ( ! $graphene_settings['hide_top_bar'] ) : ?>
                <div id="top-bar" class="row clearfix <?php if ( $graphene_settings['light_header'] ) echo 'light'; ?>">

                    <?php do_action( 'graphene_before_feed_icon' ); ?>
        			<div id="profiles"><?php do_action( 'graphene_social_profiles' ); ?></div>

                    <?php /* Search form */ if ( ( $search_box_location = $graphene_settings['search_box_location'] ) && $search_box_location == 'top_bar' || $search_box_location == '' ) : ?>
                        <button type="button" class="search-toggle navbar-toggle collapsed" data-toggle="collapse" data-target="#top_search">
                            <span class="sr-only"><?php _e( 'Toggle search form', 'graphene' ); ?></span>
                            <i class="fa fa-search-plus"></i>
                        </button>

                        <div id="top_search">
                            <?php get_search_form(); ?>
                            <?php do_action( 'graphene_top_search' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php do_action( 'graphene_top_bar' ); ?>
                </div>
            <?php endif; ?>

            <?php
        		global $post;
        		$post_id = ( $post ) ? $post->ID : false;
                $header_img = graphene_get_header_image( $post_id );
        		$alt = graphene_get_header_image_alt( $header_img );

                /* Check if the page uses SSL and change HTTP to HTTPS if true */
                if ( is_ssl() && stripos( $header_img, 'https' ) === false ) {
                    $header_img = str_replace( 'http', 'https', $header_img );
                }
            ?>
            <div id="header" class="row">
                <?php echo graphene_get_image_html( $header_img, array( HEADER_IMAGE_WIDTH, $graphene_settings['header_img_height'] ) ); ?>
            	
                <?php 
        			if ( ! is_front_page() && $graphene_settings['link_header_img'] ) {
                        echo '<a href="' . apply_filters( 'graphene_header_link' , home_url() ) . '" id="header_img_link" title="' . esc_attr__( 'Go back to the front page', 'graphene' ) . '">&nbsp;</a>';
        			}
        		?>
                	       
                <?php /* Header widget area */
        		if ( $graphene_settings['enable_header_widget'] && is_active_sidebar( 'header-widget-area' ) ) {
        			echo '<div class="header-widget">';
        			dynamic_sidebar( 'header-widget-area' );
        			echo '</div>';
        		}
        		?>
                
        		<?php do_action( 'graphene_header' ); ?>
            </div>


            <?php /* The navigation menu */ ?>
            <nav class="navbar row <?php if ( ! $graphene_settings['light_header'] ) echo 'navbar-inverse'; ?>">

                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-menu-wrap, #secondary-menu-wrap">
                        <span class="sr-only"><?php _e( 'Toggle navigation', 'graphene' ); ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    
                    <?php /* The site title and description */ 
                    if ( ! in_array( get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ), array( 'blank', '' ) ) ) :
                        if ( is_front_page() || is_home() ) { 
                            $title_tag = 'h1';
                            $desc_tag = 'h2';
                        } else {
                            $title_tag = 'h2';
                            $desc_tag = 'h3';
                        }
                        ?>
                        <?php echo "<$title_tag class=\"header_title\">"; ?>
                            <?php if ( ! is_front_page() ) : ?><a href="<?php echo apply_filters( 'graphene_header_link' , home_url() ); ?>" title="<?php esc_attr_e( 'Go back to the front page', 'graphene' ); ?>"><?php endif; ?>
                                <?php bloginfo( 'name' ); ?>
                            <?php if ( ! is_front_page() ) : ?></a><?php endif; ?>
                        <?php echo "</$title_tag>"; ?>
                        
                        <?php echo "<$desc_tag class=\"header_desc\">"; ?>
                            <?php bloginfo( 'description' ); ?>
                        <?php echo "</$desc_tag>"; ?>
                    <?php endif; ?>

                    <?php do_action( 'grahene_navbar_header' ); ?>
                </div>

                <div class="collapse navbar-collapse" id="header-menu-wrap">
        			<?php
                    /* Header menu */
                    $args = array(
                        'theme_location'=> 'Header Menu',
                        'container'     => false,
                        'menu_id'       => 'header-menu',
                        'menu_class'    => 'nav navbar-nav flip',
                        'fallback_cb'   => 'graphene_page_menu',
                        'items_wrap'    => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth'         => 10,
                        'walker'        => new Graphene_Walker_Page(),
                    );
                    if ( has_nav_menu( $args['theme_location'] ) ) $args['walker'] = new Graphene_Walker_Nav_Menu();

                    wp_nav_menu( apply_filters( 'graphene_header_menu_args', $args ) ); ?>
                    
        			<?php if ( ( $search_box_location = $graphene_settings['search_box_location'] ) && $search_box_location == 'nav_bar' ) : ?>
                        <div id="top_search" class="navbar-form navbar-right">
                            <?php get_search_form(); ?>
                            <?php do_action( 'graphene_nav_search' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php do_action( 'graphene_header_menu' ); ?>
                </div>

                <?php
                /* Secondary menu */
                $args = array(
                    'theme_location'    => 'secondary-menu',
                    'container'         => 'div',
                    'container_id'      => 'secondary-menu-wrap',
                    'container_class'   => 'collapse navbar-collapse',
                    'menu_id'           => 'secondary-menu',
                    'menu_class'        => 'nav navbar-nav flip',
                    'fallback_cb'       => 'none',
                    'depth'             => 5,
                    'walker'            => new Graphene_Walker_Nav_Menu()
                );
                wp_nav_menu( apply_filters( 'graphene_secondary_menu_args', $args ) );
                ?>
                
                <?php do_action( 'graphene_top_menu' ); ?>
            </nav>

            <?php do_action( 'graphene_before_content' ); ?>

            <div id="content" class="clearfix hfeed row">
                <?php do_action( 'graphene_before_content-main' ); ?>
                
                <?php
                
                    /* Sidebar2 on the left side? */
                    if ( in_array( graphene_column_mode(), array( 'three_col_right', 'three_col_center', 'two_col_right' ) ) ){
                        get_sidebar( 'two' );
                    }
        			
        			/* Sidebar1 on the left side? */            
                    if ( in_array( graphene_column_mode(), array( 'three_col_right' ) ) ){
                        get_sidebar();                
                    }
                
                ?>
                
                <div id="content-main" <?php graphene_grid( 'clearfix content-main', 12, $graphene_settings['column_width']['two_col']['content'], $graphene_settings['column_width']['three_col']['content'] ); ?>>
                <?php do_action( 'graphene_top_content' ); ?>