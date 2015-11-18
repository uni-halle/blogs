<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "#content" div and all content after.
 */
$menu_locations = get_nav_menu_locations();
?>
			
	</div><!-- #content -->

	<div class="row">
		<div class="cell position-2 width-4" style="padding: 2em 0 1em">
			<a href="#content">
				 <span class="cleantraditional-icon-small icon-up"></span><!-- 
				 --><?php _e( 'zum Seitenanfang', 'cleantraditional' ); ?>
			</a>	
		</div>
	</div>	
	
	<div class="row"><div class="cell position-0 width-8 delimiter"></div></div>

	<footer id="foot">
		<div class="row">
			<?php for ($i = 1; $i <= 4; $i++) : ?>
				<div class="cell position-<?php echo ( $i-1 ) * 2;?> width-2">
						<?php if ( has_nav_menu( "footer-$i" ) ) : 
							$menu = get_term( $menu_locations["footer-$i"], 'nav_menu' )
						?>
							<nav id="footer-<?php echo $i;?>-navigation" class="navigation-footer">
								<div class="title"><?php echo $menu->name ?></div>
								<?php
									// Primary navigation menu.
									wp_nav_menu( array(
										'menu_class'     => 'nav-menu',
										'theme_location' => "footer-$i",
										'depth'			 => 1,
									) );
								?>
							</nav>
						<?php endif; ?>					
				</div>
			<?php endfor;?>
		</div>
		<div class="row">
			<?php if ( has_nav_menu( 'site-bottom' ) ) : ?>
				<nav id="site-bottom-navigation" class="navigation-site-bottom cell position-0 width-8">
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


