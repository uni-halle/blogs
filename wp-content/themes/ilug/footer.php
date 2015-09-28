<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */
?>

	<footer id="colophon">

<?php
	get_sidebar( 'footer' );
?>

		
		Copyright © <?php echo date(Y); ?> ILUG 
		<span class="footer_menu"><a href="http://www.ilug.uni-halle.de/institut/kontakt-anfahrt/">Kontakt</a> | <a href="http://www.ilug.uni-halle.de/wordpress/impressum/">Impressum</a></span>


	</footer>

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>