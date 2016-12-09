<?php

if ( is_admin() ) {
	/* Hook the omega child themes page function to 'admin_menu'. */
	add_action( 'admin_menu', 'omega_child_themes_page_init', 11 );

	function omega_child_themes_page_init() {

		$page = add_theme_page( 
			sprintf( esc_html__( 'Omega Child Themes', 'omega' ) ),	// Settings page name.
			esc_html__( 'Omega Child Themes', 'omega' ), 			// Menu item name.
			'edit_theme_options', 									// Required capability.
			'omega-child-themes', 									// Screen name.
			'omega_child_themes_list' );							// Callback function.

		add_action ( 'omega_child_theme', 'child_theme_button' );
	}

	function omega_child_themes_list() {

		$omegachilds = array(
			 	array( 'name' => 'Composer Pro',
			 		   'url' => 'https://themehall.com/product/composer-pro',
			 			'class' => 'composer-pro'),
			 	array( 'name' => 'Church+',
			 		   'url' => 'https://themehall.com/product/churchplus',
			 			'class' => 'churchplus'),
			 	array( 'name' => 'Custom Footer',
			 		   'url' => 'https://themehall.com/product/omega-custom-footer-plugin',
			 			'class' => 'custom-footer'),
			 	array( 'name' => 'Superstore',
			 		   'url' => 'https://wordpress.org/themes/superstore/',
			 			'p' => ''),
			 	array( 'name' => 'Delicious',
			 		   'url' => 'https://wordpress.org/themes/delicious/',
			 			'p' => ''),
			 	array( 'name' => 'Lifestyle',
			 		   'url' => 'https://wordpress.org/themes/lifestyle/',
			 			'p' => ''),
			 	array( 'name' => 'Me',
			 		   'url' => 'https://themehall.com/me-omega-child-theme',
			 			'p' => ''),
			 	array( 'name' => 'Composer',
			 		   'url' => 'https://themehall.com/composer-one-column-omega-child-theme',
			 			'p' => ''),
			 	array( 'name' => 'Alpha',
			 		   'url' => 'https://themehall.com/alpha-first-omega-child-theme',
			 			'p' => ''),
			 	array( 'name' => 'Beta',
			 		   'url' => 'https://themehall.com/beta-second-omega-child-theme',
			 			'p' => ''),
			 	array( 'name' => 'Omega Child',
			 		   'url' => 'https://themehall.com/product/omega-child',
			 			'p' => ''),
			 	array( 'name' => 'Custom',
			 		   'url' => 'https://themehall.com/custom-free-omega-child-theme-wordpress',
			 			'p' => ''),
			 	array( 'name' => 'Mobile',
			 		   'url' => 'https://themehall.com/mobile-theme-mobile-friendly-start',
			 			'p' => ''),
			 	array( 'name' => 'Magazine',
			 		   'url' => 'https://themehall.com/responsive-magazine-theme',
			 			'p' => ''),
			 	array( 'name' => 'Shopping',
			 		   'url' => 'https://themehall.com/shopping-ecommerce-wordpress-theme',
			 			'p' => ''),
			 	array( 'name' => 'Family',
			 		   'url' => 'https://themehall.com/free-responsive-family-wordpress-theme',
			 			'p' => ''),
			 	array( 'name' => 'Hotel',
			 		   'url' => 'https://themehall.com/hotel-wordpress-theme',
			 			'p' => ''),
			 	array( 'name' => 'Sans-serif',
			 		   'url' => 'https://wordpress.org/themes/sans-serif',
			 			'p' => ''),
			 	array( 'name' => 'Sumo',
			 		   'url' => 'https://wordpress.org/themes/sumo',
			 			'p' => '')			 	
			 );
		?>
	 	<div class="wrap">
			<h2>
				<?php printf( __( 'Omega Child Themes', 'omega' ) ); ?>
			</h2>

			<?php do_action( 'omega_child_page' ); ?>

			<p>
				<?php printf( __( 'Personalize your Omega powered site using one of Omega child themes below', 'omega' ) ); ?>
			</p>

			<div id="availablethemes">
			<?php
			$currenttheme = wp_get_theme();

			foreach ( $omegachilds as $omegachild) {
				echo '<div class="available-theme ' . $omegachild['class'] .'">
					<a class="screenshot" target="_blank" href="' . $omegachild['url'] .'" title="'.$omegachild['name'].'">
						'.$omegachild['name'].'
					</a>
				</div>';
			}
			?>			
				<div class="available-theme">
					More to come...
				</div>		
			</div>


		</div>
		<?php
	}

	function child_theme_button() {
		?>
		<a href="<?php echo admin_url( 'themes.php?page=omega-child-themes' ); ?>" class="add-new-h2"><?php esc_html_e( 'Child Themes', 'omega' ); ?></a>
		<?php
	}
}


/**
 * Add Child Theme menu item to Admin Bar.
 */

function omega_child_theme_adminbar() {

	global $wp_admin_bar;

	if ( !is_super_admin() || !is_admin_bar_showing() )
        return;
    
	$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id' => 'omega-child-themes',
			'title' => __( 'Omega Child Themes', 'omega' ),
			'href' => admin_url( 'themes.php?page=omega-child-themes' )
		));
}
add_action( 'wp_before_admin_bar_render', 'omega_child_theme_adminbar' );