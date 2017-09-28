<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Catch Themes
 * @subpackage Catch Box
 * @since Catch Box 1.0
 */
?>

	</div><!-- #main -->

	<?php
    /**
     * catchbox_after_main hook
     */
    do_action( 'catchbox_after_main' );
    ?>

	<footer id="colophon" role="contentinfo">
		<?php
        /**
         * catchbox_before_footer_menu hook
         */
        do_action( 'catchbox_before_footer_sidebar' );

		/* A sidebar in the footer? Yep. You can can customize
		 * your footer with three columns of widgets.
		 */
		get_sidebar( 'footer' );

		/**
		 * catchbox_before_footer_menu hook
		 */
		do_action( 'catchbox_after_footer_sidebar' );

		/**
		 * catchbox_before_footer_menu hook
		 */
		do_action( 'catchbox_before_footer_menu' );

			/**
			 * catchbox_before_footer_menu hook
			 *
			 * @hooked catchbox_footer_menu_display - 10
			 * @hooked catchbox_mobile_footer_menu - 20
			 */
			do_action( 'catchbox_footer_menu' );

		/**
		 * catchbox_before_footer_menu hook
		 */
		do_action( 'catchbox_after_footer_menu' ); ?>

        <div id="site-generator" class="clearfix">

            <?php
            /**
             * catchbox_site_generator hook
             *
             * @hooked catchbox_socialprofile - 10
             * @hooked catchbox_footer_content - 15
             */
            do_action( 'catchbox_site_generator' ); ?>

        </div> <!-- #site-generator -->

	</footer><!-- #colophon -->

</div><!-- #page -->

<?php
/**
 * catchbox_after hook
 *
 * @hooked catchbox_scrollup - 10
 * @hooked catchbox_mobile_menus - 20
 */
do_action( 'catchbox_after' );
?>

<?php wp_footer(); ?>

</body>
</html>
