<?php
/**
 * Template part Imagemap
 *
 *<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/slick/slick.js"></script>
 */

?>
<?php
        $page = get_posts(
            array(
                //'name'      => esc_attr($a['page']),
                'name'      => 'slider_top',
                'post_type' => 'page'
            )
        );
        if ( $page ) {
            echo '<div class="slick-rail show-for-small-only"><div class="slick slick-top-mobile">'; 
            echo do_shortcode($page[0]->post_content);
            echo '</div></div>';
        };

?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.slick-top-mobile').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            //dots: true,
            autoplay: true,
            autoplaySpeed: 5000,
            arrows: false,
            fade: true,
            lazyLoad: 'ondemand'
        });
    });
        
</script>