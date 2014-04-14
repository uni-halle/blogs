<?php
/**
 * The template part for displaying Header top row.
 *
 * @package bueno
 */
?>
<div class="top_page_row">
	<div class="top_page_row_inner">
		<div class="row">
			<div class="span6 top_page_row_item">
				<?php
					if ( has_nav_menu( 'top' ) ) {
						wp_nav_menu( array( 
							'container'       => 'ul', 
			                'menu_class'      => 'top_page_menu', 
			                'menu_id'         => 'topnav',
			                'depth'           => 0,
			                'theme_location' => 'top' 
						) ); 
					}
				?>
			</div>
			<div class="span6 top_page_row_item">
				<?php
					$bueno_header_slogan = of_get_option('g_header_slogan');
					if ( '' != $bueno_header_slogan ) {
				?>
					<div class="header_slogan">
						<?php echo esc_html( $bueno_header_slogan ); ?>
					</div>
				<?php
					}
				?>
			</div>
		</div>
	</div>
	<div class="top_page_row_bg_holder"></div>
	<div class="top_page_row_border_holder"></div>
</div>