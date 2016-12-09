<?php
/**
 * Frontpage helper functions
 * Creates the custom css for the presentation page
 *
 * @package tempera
 * @subpackage Functions
 */
 

function tempera_presentation_css() {
	$temperas= tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value; }
	ob_start();
	echo '<style type="text/css">';
	if ($tempera_fronthideheader) {?> #branding {display: none;} <?php }
	if ($tempera_fronthidemenu) {?> #access, .topmenu {display: none;} <?php }
  	if ($tempera_fronthidewidget) {?> #colophon {display: none;} <?php }
	if ($tempera_fronthidefooter) {?> #footer2 {display: none;} <?php }
    if ($tempera_fronthideback) {?> #main {background: none;} <?php }
	
	if ($tempera_fpslider_topmargin) { ?> .slider-wrapper {padding-top: <?php echo $tempera_fpslider_topmargin; ?>px;} <?php }
?>

.slider-wrapper {
	max-width: <?php echo ($tempera_fpsliderwidth) ?>px ;
	max-height: <?php echo $tempera_fpsliderheight ?>px ;
	}
.slider-shadow {
	/* width: <?php echo ($tempera_fpsliderwidth) ?>px ; */
	}
#slider{
	max-width: <?php echo ($tempera_fpsliderwidth) ?>px ;
	max-height: <?php echo $tempera_fpsliderheight ?>px ;
<?php if ($tempera_fpslider_bordersize): ?> border:<?php echo $tempera_fpslider_bordersize ;?>px solid <?php echo $tempera_fpsliderbordercolor; ?>; <?php endif; ?> }
.theme-default .nivo-controlNav {top:-<?php echo $tempera_fpslider_bordersize+33 ?>px;}

#front-text1 h1, #front-text2 h1 {
	color: <?php echo $tempera_fronttitlecolor; ?>; 
	font-size: <?php echo round(30*(preg_replace("/[^\d]/","",$tempera_headingsfontsize)/100),0) ?>px;
    line-height: <?php echo round(42*(preg_replace("/[^\d]/","",$tempera_headingsfontsize)/100),0) ?>px; }

#front-columns > div, #front-columns > li {
	width: <?php switch ($tempera_nrcolumns) {
    case 0: break;
	case 1: echo "100"; break;
    case 2: echo "47.5"; break;
    case 3: echo "30"; break;
    case 4: echo "21.2"; break;
	} ?>%; }

#front-columns > div.column<?php echo $tempera_nrcolumns; ?>, #front-columns > li:nth-child(n+<?php echo $tempera_nrcolumns; ?>) { margin-right: 0; }

.column-image {	max-width:<?php echo $tempera_colimagewidth;?>px;margin:0 auto;}
.column-image img {	max-width:<?php echo $tempera_colimagewidth;?>px;  max-height:<?php echo $tempera_colimageheight;?>px;}

.nivo-caption { background-color: rgba(<?php echo cryout_hex2rgb($tempera_fpslidercaptionbg); ?>,0.7); }
.nivo-caption, .nivo-caption a { color: <?php echo $tempera_fpslidercaptioncolor; ?>; }
.theme-default .nivo-controlNav, .theme-default .nivo-directionNav a { background-color:<?php echo $tempera_fpsliderbordercolor; ?>; }
.slider-bullets .nivo-controlNav a { background-color: <?php echo $tempera_sidetitlebg; ?>; }
.slider-bullets .nivo-controlNav a:hover { background-color: <?php echo $tempera_menucolorbgdefault; ?>; }
.slider-bullets .nivo-controlNav a.active {background-color: <?php echo $tempera_accentcolora; ?>; }
.slider-numbers .nivo-controlNav a { color:<?php echo $tempera_fpslidercaptioncolor; ?>;background-color:<?php echo $tempera_fpslidercaptionbg;?>;}
.slider-numbers .nivo-controlNav a:hover { color: <?php echo $tempera_accentcolora; ?>; }
.slider-numbers .nivo-controlNav a.active { color:<?php echo $tempera_accentcolora; ?>;}


.column-image h3{ color: <?php echo $tempera_contentcolortxt; ?>; background-color: rgba(<?php echo cryout_hex2rgb($tempera_contentcolorbg); ?>,0.6); }
.columnmore { background-color: <?php echo $tempera_backcolormain; ?>; }
#front-columns h3.column-header-noimage { background: <?php echo $tempera_contentcolorbg;?>; }

<?php
	echo '</style>';
	$tempera_presentation_page_styling = ob_get_contents();
	ob_end_clean();
	return $tempera_presentation_page_styling;
} // tempera_presentation_css()

?>