<?php
/**
 * @package Esquire
 * @since Esquire 1.0
 */
?>
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="widgets" class="widget-area postbody">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #widgets .widget-area -->
		<?php endif; ?>

		<div id="footer">
			<?php if ( ! is_singular() ) : ?>
				<p>
					<?php next_posts_link( '<span class="prev">' . __( 'Older Posts', 'esquire' ) . '</span>' ); ?>
					<?php previous_posts_link( '<span class="next">' . __( 'Newer Posts', 'esquire' ) . '</span>' ); ?>
				</p>

			<?php elseif ( is_single() && ! is_attachment() ) : ?>
				<p>
					<?php previous_post_link( '%link', __( '<span class="prev">Previous Post</span>', 'esquire' ) ); ?>
					<?php next_post_link( '%link', __( '<span class="next">Next post</span>', 'esquire' ) ); ?>
				</p>

			<?php
				elseif ( is_attachment() ) :
					if ( wp_attachment_is_image( $post->ID ) ) :
			?>
				<p id="image-nav">
					<span class="prev"><?php previous_image_link( ); ?></span>
					<span class="next"><?php next_image_link( ); ?></span>
				</p>
			<?php
					endif; // end mime type check
				endif; // end footer navigation check
			?>
		</div>
	</div>

	<div id="credit">
		<p><strong><a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a></strong><span class="sep"> <br /> </span><?php printf( __( 'Theme: %1$s by %2$s.', 'esquire' ), 'Esquire', '<strong><a href="http://matthewbuchanan.name/post/87541244/esquire-theme-for-tumblr" rel="designer">Matthew Buchanan</a></strong>' ); ?></p>
	</div>

	<?php wp_footer(); ?>

</body>
</html>