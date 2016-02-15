<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Coni
 */

?>
<?php if ( get_option( 'show_on_front' ) == 'posts' || !is_front_page() ) : ?>
        <div class="clearfix"></div>
    </div><!-- /row -->
            
</div><!-- /#container -->
<?php endif; ?>

	<div class="sub-footer">
        <div class="container">
            <div class="row">

                <div class="col-md-5">
                    <p>
                    <?php esc_html_e( 'Powered by ', 'coni' ); ?><a href="<?php echo esc_url( __( 'https://wordpress.org/', 'coni' ) ); ?>"><?php esc_html_e( 'WordPress', 'coni' ); ?></a>. <?php esc_html_e( 'Coni designed by ', 'coni' ); ?><a rel="nofollow" rel="designer" href="<?php echo esc_url( __( 'https://www.quemalabs.com/', 'coni' ) ); ?>"><?php esc_html_e( 'Quema Labs', 'coni' ); ?></a>.
                    </p>
                </div>
                <div class="col-md-7">
                    <?php get_template_part( '/template-parts/social-menu', 'footer' ); ?>
                </div>

            </div><!-- .row -->
        </div><!-- .container -->
    </div><!-- .sub-footer -->


<?php wp_footer(); ?>

</body>
</html>