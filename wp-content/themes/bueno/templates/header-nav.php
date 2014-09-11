<?php
/**
 * The template part for displaying Header main navigation and search box.
 *
 * @package bueno
 */
?>
<div class="header_bottom_row">
	<div class="header_bottom_row_inner">
		<?php if ( "no" != of_get_option('g_search_box_id') ) { ?>  
		  <div id="top-search" class="visible-desktop">
		    <form method="get" action="<?php echo home_url(); ?>/" id="header_search_form">
		      <input type="text" name="s"  class="input-search"  placeholder="<?php esc_attr_e( 'Search...', 'bueno' ) ?>" />
		      <i class="icon-search search-trigger tr-hidden"></i>
		      <a href="#" onClick="document.getElementById('header_search_form').submit()" class="top-search-submit"><i class="icon-search"></i></a>
		    </form>
		  </div>  
		<?php } ?>
		<nav id="site-navigation" class="main-nav" role="navigation">
			<div class="navbar_inner">
			<?php 
				wp_nav_menu( array( 
					'container'       => 'ul', 
		            'menu_class'      => 'sf-menu', 
		            'menu_id'         => 'topnav',
		            'depth'           => 0,
		            'theme_location' => 'primary' 
				) ); 
			?>
			<span class="nav_hover_marker"></span>
			</div>
		</nav><!-- #site-navigation -->
	</div>
	<div class="header_bottom_row_bg_holder"></div>
	<div class="header_bottom_row_border_holder"></div>
</div>