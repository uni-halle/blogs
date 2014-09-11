		</div><!-- #inner-wrapper -->
	</div><!-- #main -->

	<div id="footer" role="contentinfo">
		<div id="colophon">

<?php get_sidebar( 'footer' ); ?>

		<?php /* Add in our Horizontal Social Media */ ?>
		<?php get_template_part( 'social', 'horizontal' ); ?>
		<?php /* Get our footer credit bar */ ?>
		<?php get_template_part( 'footer', 'credit' ); ?>
		
		<div id="site-info">
			<a href="http://www.digitalraindrops.net/atmosphere">Theme Created by Digital Raindrops</a>
		</div><!-- #site-info -->

		<div id="site-generator">
			<?php do_action( 'atmosphere_credits' ); ?>
			<a href="<?php echo esc_url( __('http://wordpress.org/', 'atmosphere') ); ?>"
					title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'atmosphere'); ?>" rel="generator">
				<?php printf( __('Proudly powered by %s.', 'atmosphere'), 'WordPress' ); ?>
			</a>
		</div><!-- #site-generator -->

		</div><!-- #colophon -->
	</div><!-- #footer -->

</div><!-- #wrapper -->

<?php wp_footer(); ?>
</body>
</html>
