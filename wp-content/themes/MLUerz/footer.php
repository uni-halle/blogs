<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>


</div><!-- .site-content --></div>



<!-- #col2: zweite Float-Spalte des Inhaltsbereiches -->
    <div id="col2">
      <div id="col2_content" class="clearfix"><?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php
					// Primary navigation menu.
					wp_nav_menu( array(
						'menu_class'     => 'nav-menu',
						'theme_location' => 'primary',
					) );
				?>
			</nav><!-- .main-navigation -->
		<?php endif; ?></div>
    </div>




	<footer id="colophon" class="site-footer" role="contentinfo"><div id="footer" class="site-info">
<?php if ( has_nav_menu( 'social' ) ) : ?>
			<nav id="social-navigation" class="social-navigation" role="navigation">
				<?php
					// Social links navigation menu.
					wp_nav_menu( array(
						'theme_location' => 'social',
						'depth'          => 1,
						'container'=>'',
						'after' => ' | ',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s <li><a href="javascript:window.print()"><img src="' . get_template_directory_uri() . '/js/print.gif" alt="' . __('Print') . '"/> ' . __('Print') . '</a></li></ul>',
					) ); 
				?>
			</nav><!-- .social-navigation -->
		<?php endif; ?>
		
			
			
		</div><!-- .site-info -->
	</footer><!-- .site-footer -->


</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>
