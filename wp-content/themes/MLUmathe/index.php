<?php
/**
 * @package WordPress
 * @subpackage MLUmathe Theme
 */
$options = get_option( 'MLUmathe_theme_settings' );
?>
<?php get_header(' '); ?>

<div id="masonry-wrap">
<div class="tile olglogomlu bg-color-physikgreen wide image masonry-brick" style="background-color: #2d7aab <?php /*uni-gruen: #9BC34B */?> !important; position: absolute; top: 0px; left: 0px;">
<a href="http://www.mathematik.uni-halle.de/" title="Institut für Mathematik" style="display: block; width:100%; height: 100%; text-align: center;" target="_blank">
<img src="http://www.ich-will-wissen.de/fileadmin/templates/img/uni-signet-horizontal.png" style="width: 88%; margin-top: 6%;" alt="Martin-Luther-Universität Halle-Wittenberg"></a>
	<span style="position: absolute; bottom: 20px; display: inline-block; width: 100%; text-align: center;  font-weight: bold; font-size: 18px;letter-spacing: 1px;">Institut für Mathematik</span>
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