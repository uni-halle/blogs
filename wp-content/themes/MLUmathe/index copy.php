<?php
/**
 * @package WordPress
 * @subpackage MLUmathe Theme
 */
$options = get_option( 'MLUmathe_theme_settings' );
?>
<?php get_header(' '); ?>

<div id="masonry-wrap">







<div class="tile olglogomlu bg-color-physikgreen wide image masonry-brick" style="background-color: #9BC34B !important;">
<a href="http://www.mathematik.uni-halle.de/" title="Institut für Mathematik" style="display: block; width:100%; height: 100%; text-align: center;" target="_blank">
<!--
<img src="<?php get_stylesheet_directory_uri()?>/MLU-Siegel_zentriert_mit_Text.png">
-->
<img src="http://www.ich-will-wissen.de/fileadmin/templates/img/uni-signet-horizontal.png" style="width: 75%; margin-top: 10%;" alt="Martin-Luther-Universität Halle-Wittenberg"> 
<span style="display:none">Martin-Luther-Universität Halle-Wittenberg</span>
</a>
	<span style="position: absolute; bottom: 20px; display: inline-block; width: 100%; text-align: center; "><!-- <?php bloginfo('name'); ?> -->Institut für Mathematik</span>
</div>




	<?php 
    if (have_posts()) :
        get_template_part( 'loop' , 'entry');
    endif;
    ?>

</div>  
<!-- /masonry-wrap -->

<?php if (function_exists("pagination")) { pagination(); } ?>
<?php get_footer(' '); ?>