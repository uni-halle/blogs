<?php if ( function_exists('register_sidebar') ) {register_sidebar();register_sidebar();}
// Custom Header Image Support

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/img/header.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 1100);
define('HEADER_IMAGE_HEIGHT', 140);
define( 'NO_HEADER_TEXT', true );


function theme_admin_header_style() {
?>
<style type="text/css">
#headimg {
	background:#fff url(<?php header_image() ?>) no-repeat center;  
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
#headimg * {
  display:none;
}
</style>
<?php
}
function theme_header_style() {
?>
<style type="text/css">
  #splash
  {
  background:url(<?php header_image(); ?>) no-repeat center;
  height:<?php echo HEADER_IMAGE_HEIGHT; ?>px;
  width:<?php echo HEADER_IMAGE_WIDTH; ?>px;
}
</style>
<?php
}
if ( function_exists('add_custom_image_header') ) {
	add_custom_image_header('theme_header_style', 'theme_admin_header_style');
}
?>