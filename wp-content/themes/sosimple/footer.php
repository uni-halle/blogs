<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package SoSimple
 */
?>

</div><!-- #content -->
	<!-- Some more link css -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'sosimple' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'sosimple' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php $sosimple_theme = wp_get_theme(); ?>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'sosimple' ), 'sosimple', '<a href="'.$sosimple_theme->get( 'AuthorURI' ).'" rel="designer">Fernando Villamor Jr.</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
