<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */

get_header(); ?>
 
<div id="main">
    <div id="header">
    <!--
    <hgroup>
        <h1><a href="<?php // echo home_url( '/' ); ?>" title="<?php // echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php // bloginfo( 'name' ); ?></a></h1>
        <h2><?php // bloginfo( 'description' ); ?></h2>
    </hgroup>
    /-->
        <div id="header_images">
            <div id="cimy_div_id_0">Loading images...</div>
            <div class="cimy_div_id_0_caption"></div>
            <style type="text/css">
                #cimy_div_id_0 {
                    float: left;
                    width: 799px;
                    height: 238px;
                }
                div.cimy_div_id_0_caption {
                    position: absolute;
                    margin-top: 175px;
                    margin-left: -75px;
                    width: 150px;
                    text-align: center;
                    left: 50%;
                    padding: 5px 10px;
                    background: black;
                    color: white;
                    font-family: sans-serif;
                    border-radius: 10px;
                    display: none;
                    z-index: 2;
                }
            </style>
            <noscript> 
                <div id="cimy_div_id_0_nojs">
                    <img id="cimy_img_id" src="http://ausdauerprofi.com/wordpress/wp-content/Cimy_Header_Images/0/bild01.jpg" alt="" />
                </div>
            </noscript>
        </div>
    </div>
	<div id="content">

	<?php get_template_part( 'loop', 'single' ); ?>
    </div><!-- #content -->
</div><!-- #main -->
		
<?php get_sidebar(); ?>
<?php get_footer(); ?>
