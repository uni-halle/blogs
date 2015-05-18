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
			<?php do_action('generate_inside_navigation'); ?>
			<h3 class="menu-toggle"><span class="mobile-menu"><?php echo apply_filters('generate_mobile_menu_label', __( 'Menu', 'generate' ) ); ?></span></h3>
			<div class="screen-reader-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'generate' ); ?>"><?php _e( 'Skip to content', 'generate' ); ?></a></div>
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


/**
 * Menu fallback. 
 *
 * @param  array $args
 * @return string
 * @since 1.1.4
 */
function generate_menu_fallback( $args )
{ 
?>
	<div class="main-nav">
		<ul <?php generate_menu_class(); ?>>
			<?php wp_list_pages('sort_column=menu_order&title_li='); ?>
		</ul>
	</div><!-- .main-nav -->
<?php 
}

/**
 * If the navigation is in one of the sidebars, move it up top when on mobile
 * @since 1.1.0
 */
add_action('wp_footer','generate_mobile_navigation_position');
function generate_mobile_navigation_position()
{
	$generate_settings = wp_parse_args( 
		get_option( 'generate_settings', array() ), 
		generate_get_defaults() 
	);
	
	if ( 'nav-left-sidebar' !== $generate_settings['nav_position_setting'] && 'nav-right-sidebar' !== $generate_settings['nav_position_setting'] )
		return;
		
	?>
	<script type="text/javascript">
		jQuery(window).load(function($) {
			var mobile, widthTimer;
			mobile = jQuery( '.menu-toggle' );
			
			function generateCheckWidth() {
				if ( mobile.is( ':visible' ) ) {
					jQuery('.main-navigation').insertAfter('.site-header');
				} else {
					jQuery('.main-navigation').appendTo('.gen-sidebar-nav');
				}
			}
			
			if ( mobile.is( ':visible' ) ) {
				generateCheckWidth();
			}
			
			jQuery(window).resize(function() {
				clearTimeout(widthTimer);
				widthTimer = setTimeout(generateCheckWidth, 100);
			});
		});
	</script>
	<?php
}