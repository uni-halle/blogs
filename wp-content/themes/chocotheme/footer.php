<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=page div, id="main" div, id="main-top" div and id="main-bot" div
 *
 * @package WordPress
 * @subpackage Choco
 */
?>
						</div><!-- #main-bot -->
					</div><!-- #main-middle -->
				</div><!-- #main-top -->
			</div><!-- #main -->
			<footer id="footer">
				<p><a href="http://wordpress.org/" rel="generator">Proudly powered by WordPress</a>
<?php printf( __( 'Theme: %1$s by %2$s.', 'choco' ), 'Choco', '<a href="http://cssmayo.com" rel="designer">.css{mayo}</a>' ); ?></p>

				<p class="rss"><a href="<?php bloginfo( 'rss_url' ); ?>"><?php _e( 'Entries (RSS)', 'choco' ); ?></a> <?php _e( 'and', 'choco' ); ?> <a href="<?php bloginfo( 'comments_rss2_url' ); ?>"><?php _e( 'Comments (RSS)', 'choco' ); ?></a></p>
			</footer><!-- #footer -->
		</div><!-- #page -->
		<?php wp_footer(); ?>
	</body>
</html>