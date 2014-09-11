<?php
/**
 * The template for displaying Google map.
 * It can be loaded by placing [google-map] shortcode 
 * inside your homepage custom post.
 */
?>

<?php $maja_option = maja_get_global_options(); ?>

<?php if ($maja_option['maja_map-check'] =='1') { ?> 
         
    <div id="map_canvas"></div>
    
<?php } ?>