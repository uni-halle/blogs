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
function catchbox_header_menu() {
    $classes = "mobile-menu-anchor page-menu";

    $options = catchbox_get_options();

    // Header Left Mobile Menu Anchor
    if ( has_nav_menu( 'primary' ) ) {
        $classes = "mobile-menu-anchor primary-menu";
    }
    ?>
    <div class="menu-access-wrap mobile-header-menu clearfix">
        <div id="mobile-header-left-menu" class="<?php echo $classes; ?>">
            <a href="#mobile-header-left-nav" id="menu-toggle-primary" class="genericon genericon-menu">
                <span class="mobile-menu-text"><?php esc_html_e( 'Menu', 'catch-box' ); ?></span>
            </a>
        </div><!-- #mobile-header-left-menu -->
            
        <?php
        if ( !empty ( $options['enable_sec_menu'] ) && has_nav_menu( 'secondary' ) ) {
            $menuclass = "mobile-enable"; 
            ?>
            <div id="mobile-header-right-menu" class="mobile-menu-anchor secondary-menu">
                <a href="#mobile-header-right-nav" id="menu-toggle-secondary" class="genericon genericon-menu">
                    <span class="mobile-menu-text"><?php esc_html_e( 'Secondary Menu', 'catch-box' ); ?></span>
                </a>
            </div><!-- #mobile-header-right-menu -->
        <?php 
        } else {
            $menuclass = "mobile-disable";
        }
        ?>

        <div id="site-header-menu-primary" class="site-header-menu">
            <nav id="access" class="main-navigation menu-focus" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'catch-box' ); ?>">
            
                <h3 class="screen-reader-text"><?php _e( 'Primary menu', 'catch-box' ); ?></h3>
                <?php
                    if ( has_nav_menu( 'primary', 'catch-box' ) ) {
                        $args = array(
                            'theme_location'    => 'primary',
                            'container_class'   => 'menu-header-container',
                            'items_wrap'        => '<ul class="menu">%3$s</ul>'
                        );
                        wp_nav_menu( $args );
                    } else {
                        echo '<div class="menu-header-container">';
                            wp_page_menu( array( 'menu_class'  => 'menu' ) );
                        echo '</div><!-- .menu-header-container -->';
                    } 
                ?>
            </nav><!-- #access -->
        </div><!-- .site-header-menu -->

        <?php 
        if ( has_nav_menu( 'secondary' ) ) {            
            ?>
            <div id="site-header-menu-secondary" class="site-header-menu">
                <nav id="access-secondary" class="<?php echo $menuclass; ?>"  role="navigation" aria-label="<?php esc_attr_e( 'Secondary Menu', 'catch-box' ); ?>">
                    <h3 class="screen-reader-text"><?php esc_html_e( 'Secondary menu', 'catch-box' ); ?></h3>
                    <?php wp_nav_menu( array( 'theme_location'  => 'secondary', 'container_class' => 'menu-secondary-container' ) );  ?>
                </nav><!-- #access-secondary -->
            </div><!-- .site-header-menu -->
            <?php 
        }
        ?>
    </div><!-- .menu-access-wrap -->
    <?php
}
endif; //catchbox_header_menu

// Load Header Menu in  catchbox_after_headercontent hook
add_action( 'catchbox_after_headercontent', 'catchbox_header_menu', 20 );


if ( ! function_exists( 'catchbox_footer_menu_display' ) ) :
/**
 * Footer Menu
 *
 * @since Catch Box 4.0
 */
function catchbox_footer_menu_display() {
    if ( has_nav_menu( 'footer' ) ) {
        // Check is footer menu is enable or not
        $options = catchbox_get_options();

        $menuclass = "mobile-disable";
        
        if ( !empty ( $options['enable_footer_menu'] ) ) {
            $menuclass = "mobile-enable";

            ?>
            <div id="mobile-footer-menu" class="menu-access-wrap clearfix">
                <div class="mobile-menu-anchor">
                    <a href="#mobile-footer-nav" id="menu-toggle-footer" class="genericon genericon-menu">
                        <span class="mobile-menu-text"><?php esc_html_e( 'Footer Menu', 'catch-box' ); ?></span>
                    </a>
                </div><!-- .mobile-menu-anchor -->
        
            <?php
        }
        ?>
        
        <div id="site-footer-mobile-menu" class="site-footer-menu">
            <nav id="access-footer" class="<?php echo $menuclass; ?>" role="navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'catch-box' ); ?>">
                <h3 class="screen-reader-text"><?php _e( 'Footer menu', 'catch-box' ); ?></h3>
                <?php wp_nav_menu( array( 'theme_location'  => 'footer', 'container_class' => 'menu-footer-container', 'depth' => 1 ) );  ?>
            </nav>
        </div><!-- .site-footer-menu -->
    <?php 
    if ( !empty ( $options['enable_footer_menu'] ) ) { ?>
    </div><!-- #mobile-footer-menu -->   
    <?php
        }
    }
}
endif; //catchbox_footer_menu_display

// Load Footer Menu in  catchbox_footer_menu hook
add_action( 'catchbox_footer_menu', 'catchbox_footer_menu_display', 10 );