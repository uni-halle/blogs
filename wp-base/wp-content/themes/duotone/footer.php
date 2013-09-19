<?php
/**
 * @package Duotone
 */
?>

			<div id="footer">

			<!-- If you'd like to support WordPress, having the "powered by" link somewhere on your blog is the best way; it's our only promotion or advertising. -->
			<p class="info">
				<a href="<?php esc_url( bloginfo( 'rss2_url' ) ); ?>"><img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/rss.png' ); ?>" title="<?php esc_attr_e( 'Subscribe via RSS', 'duotone' ); ?>" alt="<?php esc_attr_e( 'RSS Feed', 'duotone' ); ?>" /></a>
				<a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a> <?php printf( __( 'Theme: %1$s by %2$s.', 'duotone' ), 'Duotone', '<a href="http://automattic.com/" rel="designer">Automattic</a>' ); ?>
			</p>
			<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</div>

</div>

<?php wp_footer(); ?>
</body>
</html>