<?php
/**
 * Template part Imagemap
 *
 *<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/slick/slick.js"></script>
 */

?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.slick-top').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            autoplay: true,
            fade: true,
            autoplaySpeed: 5000,
            lazyLoad: 'ondemand'
        });
    });
        
</script>