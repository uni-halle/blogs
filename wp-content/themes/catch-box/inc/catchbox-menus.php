<?php
/**
 * The template for displaying custom menus
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 4.0
 */


if ( ! function_exists( 'catchbox_page_menu_args' ) ) :
/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function catchbox_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
endif; //catchbox_page_menu_args

add_filter( 'wp_page_menu_args', 'catchbox_page_menu_args' );


if ( ! function_exists( 'catchbox_page_menu_active' ) ) :
/**
 * Replacing classes in default wp_page_menu
 *
 * REPLACE "current_page_item" WITH CLASS "current-menu-item"
 * REPLACE "current_page_ancestor" WITH CLASS "current-menu-ancestor"
 */
function catchbox_page_menu_active( $text ) {
    $replace = array(
        // List of classes to replace with "active"
        'current_page_item' => 'current-menu-item',
        'current_page_ancestor' => 'current-menu-ancestor',
    );
    $text = str_replace(array_keys($replace), $replace, $text);
        return $text;
}
endif; //catchbox_page_menu_active

add_filter( 'wp_page_menu', 'catchbox_page_menu_active' );


if ( ! function_exists( 'catchbox_wp_page_menu' ) ) :
/**
 * Remove div from wp_page_menu() and replace with ul.
 * @uses wp_page_menu filter
 */
function catchbox_wp_page_menu( $page_markup ) {
    preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
        $divclass = $matches[1];
        $replace = array('<div class="'.$divclass.'">', '</div>');
        $new_markup = str_replace($replace, '', $page_markup);
        $new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
        return $new_markup; 
}
endif; //catchbox_wp_page_menu

add_filter( 'wp_page_menu', 'catchbox_wp_page_menu' );


if ( ! function_exists( 'catchbox_header_menu' ) ) :
/**
 * Header Menu
 *
 * @since Catch Box 2.5
 */
function catchbox_header_menu() { ?>
    <nav id="access" class="main-navigation menu-focus" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'catch-box' ); ?>">
        <h3 class="assistive-text"><?php _e( 'Primary menu', 'catch-box' ); ?></h3>
        <?php
            if ( has_nav_menu( 'primary', 'catch-box' ) ) { 
                $args = array(
                    'theme_location'    => 'primary',
                    'container_class'   => 'menu-header-container', 
                    'items_wrap'        => '<ul class="menu">%3$s</ul>' 
                );
                wp_nav_menu( $args );
            }
            else {
                echo '<div class="menu-header-container">';
                    wp_page_menu( array( 'menu_class'  => 'menu' ) );
                echo '</div>';
            } ?>        
               
        </nav><!-- #access -->
        
    <?php if ( has_nav_menu( 'secondary', 'catch-box' ) ) {
        // Check is footer menu is enable or not
        $options = catchbox_get_theme_options();
        if ( !empty ($options ['enable_menus'] ) ) :
            $menuclass = "menu-focus mobile-enable";
        else :
            $menuclass = "menu-focus mobile-disable";
        endif;
        ?>
        <nav id="access-secondary" class="<?php echo $menuclass; ?>"  role="navigation" aria-label="<?php esc_attr_e( 'Secondary Menu', 'catch-box' ); ?>">
            <h3 class="assistive-text"><?php _e( 'Secondary menu', 'catch-box' ); ?></h3>
            <?php wp_nav_menu( array( 'theme_location'  => 'secondary', 'container_class' => 'menu-secondary-container' ) );  ?>
        </nav>
    <?php }
} 
endif; //catchbox_header_menu

// Load Header Menu in  catchbox_after_headercontent hook 
add_action( 'catchbox_after_headercontent', 'catchbox_header_menu', 10 ); 


if ( ! function_exists( 'catchbox_footer_menu_display' ) ) :
/**
 * Footer Menu
 *
 * @since Catch Box 4.0
 */
function catchbox_footer_menu_display() { 
    if ( has_nav_menu( 'footer', 'catch-box' ) ) {
        // Check is footer menu is enable or not
        $options = catchbox_get_theme_options();
        if ( !empty ($options ['enable_menus'] ) ) :
            $menuclass = "mobile-enable";
        else :
            $menuclass = "mobile-disable";
        endif;
        ?>
        <nav id="access-footer" class="<?php echo $menuclass; ?>" role="navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'catch-box' ); ?>">
            <h3 class="assistive-text"><?php _e( 'Footer menu', 'catch-box' ); ?></h3>
            <?php wp_nav_menu( array( 'theme_location'  => 'footer', 'container_class' => 'menu-footer-container', 'depth' => 1 ) );  ?>
        </nav>
    <?php 
    }
} 
endif; //catchbox_footer_menu_display

// Load Footer Menu in  catchbox_footer_menu hook 
add_action( 'catchbox_footer_menu', 'catchbox_footer_menu_display', 10 ); 


if ( ! function_exists( 'catchbox_mobile_header_menu' ) ) :
/**
 * This function loads Mobile Menus in Header Section
 *
 * @get the data value from theme options
 * @uses catchbox_after_headercontent action to add in the Header
 */
function catchbox_mobile_header_menu() {
    //Getting Ready to load options data
    $options = catchbox_get_theme_options();

    // Header Left Mobile Menu Anchor 
    if ( has_nav_menu( 'primary' ) ) {
        $classes = "mobile-menu-anchor primary-menu";
    }
    else {
        $classes = "mobile-menu-anchor page-menu"; 
    }
    ?>
    <div class="menu-access-wrap clearfix">
        <div id="mobile-header-left-menu" class="<?php echo $classes; ?>">
            <a href="#mobile-header-left-nav" id="header-left-menu" class="genericon genericon-menu">
                <span class="mobile-menu-text"><?php _e( 'Menu', 'catch-box' );?></span>
            </a>
        </div><!-- #mobile-header-menu -->

        <nav id="mobile-header-left-nav" class="mobile-menu"  role="navigation" aria-label="<?php esc_attr_e( 'Primary Mobile Menu', 'catch-box' ); ?>">
            <?php if ( has_nav_menu( 'primary' ) ) :
                $args = array(
                    'theme_location'    => 'primary',
                    'container'         => false,
                    'items_wrap'        => '<ul id="header-left-nav" class="menu">%3$s</ul>'
                );
                wp_nav_menu( $args );
            else :
                wp_page_menu( array( 'menu_class'  => 'menu' ) );
            endif; ?>
        </nav><!-- #mobile-header-left-nav -->


        <?php
        if ( ( !empty ( $options ['enable_menus'] ) &&  has_nav_menu( 'secondary' ) ) ) {
            $classes = "mobile-menu-anchor secondary-menu";
        }
        else {
            return; 
        }
        ?>
        <div id="mobile-header-right-menu" class="<?php echo $classes; ?>">
            <a href="#mobile-header-right-nav" id="header-right-menu" class="genericon genericon-menu">
                <span class="mobile-menu-text"><?php _e( 'Secondary Menu', 'catch-box' );?></span>
            </a>
        </div><!-- #mobile-header-menu -->

        <?php if ( ( !empty ( $options ['enable_menus'] ) &&  has_nav_menu( 'secondary' ) ) ) : ?>
            <nav id="mobile-header-right-nav" class="mobile-menu"  role="navigation" aria-label="<?php esc_attr_e( 'Secondary Mobile Menu', 'catch-box' ); ?>">
                <?php
                    $args = array(
                        'theme_location'    => 'secondary',
                        'container'         => false,
                        'items_wrap'        => '<ul id="header-right-nav" class="menu">%3$s</ul>'
                    );
                    wp_nav_menu( $args ); 
                ?>
            </nav><!-- #mobile-header-right-nav -->
        <?php endif; ?>
    </div><!-- .menu-access-wrap -->   

    <?php    
}
endif; //catchbox_mobile_header_menu  
  
add_action( 'catchbox_after_headercontent', 'catchbox_mobile_header_menu', 20 );


if ( ! function_exists( 'catchbox_mobile_footer_menu' ) ) :
/**
 * This function loads Footer Mobile Menus in Footer Section
 *
 * @get the data value from theme options
 * @uses catchbox_footer_menu action to add in the Header
 */
function catchbox_mobile_footer_menu() {
    //Getting Ready to load options data
    $options = catchbox_get_theme_options();

    if ( ( !empty ( $options ['enable_menus'] ) &&  has_nav_menu( 'footer' ) ) ) { ?>
        <div class="menu-access-wrap clearfix">
            <div id="mobile-footer-menu" class="mobile-menu-anchor footer-menu">
                <a href="#mobile-footer-nav" id="footer-menu" class="genericon genericon-menu">
                    <span class="mobile-menu-text"><?php _e( 'Footer Menu', 'catch-box' );?></span>
                </a>
            </div><!-- #mobile-footer-menu -->
            <?php 
                echo '<nav id="mobile-footer-nav" class="mobile-menu" role="navigation" aria-label="' . esc_attr__( 'Secondary Mobile Menu', 'catch-box' ) . '">';
                    $args = array(
                        'theme_location'    => 'footer',
                        'container'         => false,
                        'items_wrap'        => '<ul id="footer-nav" class="menu">%3$s</ul>'
                    );
                    wp_nav_menu( $args );
                echo '</nav><!-- #mobile-footer-nav -->';
            ?>
        </div><!-- .menu-access-wrap -->  
        <?php  
    }  
}
endif; //catchbox_mobile_footer_menu 

add_action( 'catchbox_footer_menu', 'catchbox_mobile_footer_menu', 20 );