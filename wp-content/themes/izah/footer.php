<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "#content" div and all content after.
 */
$menu_locations = get_nav_menu_locations();
$menu_footer_1 = get_term( $menu_locations['footer-1'], 'nav_menu' );
$menu_footer_2 = get_term( $menu_locations['footer-2'], 'nav_menu' );
?>
			
	</div><!-- #content -->

	<div class="row">
		<div class="cell position-6 width-2">
			<a href="#content">
				 <span class="cleantraditional-icon-small icon-up"></span><!-- 
				 --><?php _e( 'zum Seitenanfang', 'cleantraditional' ); ?>
			</a>	
		</div>
	</div>	
	
	<div class="row"><div class="cell position-0 width-8 delimiter"></div></div>

	<footer id="foot" role="contentinfo" class="site-footer">
		<div class="row">
			<div class="cell position-2 width-1">
				<?php if ( has_nav_menu( 'footer-1' ) ) : ?>
					<nav id="footer-1-navigation" class="navigation-footer" role="navigation">
						<div class="title"><?php echo $menu_footer_1->name ?></div>
						<?php
							// Primary navigation menu.
							wp_nav_menu( array(
								'menu_class'     => 'nav-menu',
								'theme_location' => 'footer-1',
								'depth'			 => 1,
							) );
						?>
					</nav>
				<?php endif; ?>					
			</div>
			<div class="cell position-3 width-1">
				<?php if ( has_nav_menu( 'footer-2' ) ) : ?>
					<nav id="footer-2-navigation" class="navigation-footer" role="navigation">
						<div class="title"><?php echo $menu_footer_2->name ?></div>
						<?php
							// Primary navigation menu.
							wp_nav_menu( array(
								'menu_class'     => 'nav-menu',
								'theme_location' => 'footer-2',
								'depth'			 => 1,
							) );
						?>
					</nav>
				<?php endif; ?>		
			</div>
		</div>
		<div class="row">
			<?php if ( has_nav_menu( 'site-bottom' ) ) : ?>
				<nav id="site-bottom-navigation" class="navigation-site-bottom cell position-0 width-8" role="navigation">
					<?php
						// Primary navigation menu.
						wp_nav_menu( array(
							'menu_class'     => 'nav-menu',
							'theme_location' => 'site-bottom',
							'depth'			 => 1,
						) );
					?>
				</nav>
			<?php endif; ?>			
		</div>		
	</footer>

</div><!-- #site -->

<?php wp_footer(); ?>

</body>
</html>


