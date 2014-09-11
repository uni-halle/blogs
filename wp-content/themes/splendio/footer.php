<?php
/* The template for displaying the footer. */
?>

<div class="container-bot"></div>
</div>
<!-- End Container -->


<!-- Start - Footer -->
<div id="footer" role="contentinfo">

<!-- Start Footer Flickr Photostream -->
<?php if (function_exists('get_flickrrss')) { ?>
<div class="footer-flick">
  <ul><?php get_flickrrss(); ?> </ul>
</div>
<?php } ?>
<!-- End Footer Flickr Photostream -->

<!-- Start Footer Widgets -->
<?php /* A sidebar in the footer? Yep. */ get_sidebar( 'footer' ); ?>
<!-- End Footer Widgets -->

<div class="site-info">
 &copy;  2010 <a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>. All Rights Reserved <br />
 Splendio Theme is created by <?php do_action( 'twentyten_credits' ); ?><a href="<?php echo esc_url( __('http://designdisease.com/', 'twentyten') ); ?>" title="<?php esc_attr_e('Profesional Blog Design', 'twentyten'); ?>" rel="generator"> <?php printf( __('%s.', 'twentyten'), 'DesignDisease' ); ?></a> Powered by: Wordpress
</div>
</div>
<!-- End - Footer -->

</div>
<!-- End - Wrapper -->

<?php wp_footer();?>

</body>
</html>