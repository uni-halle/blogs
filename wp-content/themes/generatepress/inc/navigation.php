<?php
/**
 * Generate the navigation based on settings
 * @since 0.1
 */
add_action( 'generate_after_header', 'generate_add_navigation_after_header', 5 );
function generate_add_navigation_after_header()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	if ( 'nav-below-header' == $generate_settings['nav_position_setting'] ) :
		generate_navigation_position();
	endif;
	
}
add_action( 'generate_before_header', 'generate_add_navigation_before_header', 5 );
function generate_add_navigation_before_header()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	if ( 'nav-above-header' == $generate_settings['nav_position_setting'] ) :
		generate_navigation_position();
	endif;
	
}
add_action( 'generate_before_header_content', 'generate_add_navigation_float_right', 5 );
function generate_add_navigation_float_right()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	if ( 'nav-float-right' == $generate_settings['nav_position_setting'] ) :
		generate_navigation_position();
	endif;
	
}
add_action( 'generate_before_right_sidebar_content', 'generate_add_navigation_before_right_sidebar', 5 );
function generate_add_navigation_before_right_sidebar()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	if ( 'nav-right-sidebar' == $generate_settings['nav_position_setting'] ) :
		echo '<div class="gen-sidebar-nav">';
			generate_navigation_position();
		echo '</div>';
	endif;
	
}
add_action( 'generate_before_left_sidebar_content', 'generate_add_navigation_before_left_sidebar', 5 );
function generate_add_navigation_before_left_sidebar()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	if ( 'nav-left-sidebar' == $generate_settings['nav_position_setting'] ) :
		echo '<div class="gen-sidebar-nav">';
			generate_navigation_position();
		echo '</div>';
	endif;
	
}

if ( ! function_exists( 'generate_navigation_position' ) ) :
/**
 *
 * Build the navigation
 * @since 0.1
 *
 */
function generate_navigation_position()
{
	?>
	<nav itemtype="http://schema.org/SiteNavigationElement" itemscope="itemscope" id="site-navigation" role="navigation" <?php generate_navigation_class(); ?>>
		<div class="inside-navigation grid-container grid-parent">
			<?php do_action( 'generate_inside_navigation' ); ?>
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<?php do_action( 'generate_inside_mobile_menu' ); ?>
				<span class="mobile-menu"><?php echo apply_filters('generate_mobile_menu_label', __( 'Menu', 'generate' ) ); ?></span>
			</button>
			<?php 
			wp_nav_menu( 
				array( 
					'theme_location' => 'primary',
					'container' => 'div',
					'container_class' => 'main-nav',
					'menu_class' => '',
					'fallback_cb' => 'generate_menu_fallback',
					'items_wrap' => '<ul id="%1$s" class="%2$s ' . join( ' ', generate_get_menu_class() ) . '">%3$s</ul>'
				) 
			);
			?>
		</div><!-- .inside-navigation -->
	</nav><!-- #site-navigation -->
	<?php
}
endif;

/**
 * Menu fallback. 
 *
 * @param  array $args
 * @return string
 * @since 1.1.4
 */
function generate_menu_fallback( $args )
{ 
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	?>
	<div class="main-nav">
		<ul <?php generate_menu_class(); ?>>
			<?php 
			wp_list_pages('sort_column=menu_order&title_li=');
			if ( 'enable' == $generate_settings['nav_search'] ) :
				echo '<li class="search-item" title="' . _x( 'Search', 'submit button', 'generate' ) . '"><a href="#"><i class="fa fa-search"></i></a></li>';
			endif;
			?>
		</ul>
	</div><!-- .main-nav -->
	<?php 
}