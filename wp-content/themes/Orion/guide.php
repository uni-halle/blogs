<?php
add_action('admin_menu', 't_guide');

function t_guide() {
	add_theme_page('How to use the theme', 'Theme user guide', 8, 'user_guide', 't_guide_options');
	
}

function t_guide_options() {

?>
<div class="wrap">
<div class="opwrap" style="background:#fff; margin:20px auto; width:80%; padding:30px; border:1px solid #ddd;" >

<div id="wrapr">

<div class="headsection">
<h2 style="clear:both; padding:20px 10px; color:#444; font-size:32px; background:#eee">Theme user guide</h2>
</div>

<div class="gblock">

  <h2>License</h2>

  <p> The PHP code of the theme is licensed under the GPL license as is WordPress itself. You can read it here: http://codex.wordpress.org/GPL. </p>
  <p> All other parts of the theme including, but not limited to the CSS code, images, and design are licensed for free personal usage.  </p>
  <p> You are requested to retain the credit banners on the template. </p>
  <p> You are allowed to use the theme on multiple installations, and to edit the template for your personal use. </p>
  <p> You are NOT allowed to edit the theme or change its form with the intention to resell or redistribute it. </p>  
  <p> You are NOT allowed to use the theme as a part of a template repository for any paid CMS service. </p>  
  
  <h2>How to add featured thumbnail to posts?</h2>
  
  <p>Check the video below to see how to add featured images to posts. Theme uses timthumb script to generate thumbnail images. Make sure your host has PHP5 and GD library enabled. You will also need to set the CHMOD value for the "cache" folder <strong>within the theme</strong> to "777" or "755" </p>
  <p><object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0' width='560' height='345'><param name='movie' value='http://screenr.com/Content/assets/screenr_1116090935.swf' /><param name='flashvars' value='i=88375' /><param name='allowFullScreen' value='true' /><embed src='http://screenr.com/Content/assets/screenr_1116090935.swf' flashvars='i=88375' allowFullScreen='true' width='560' height='345' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed></object></p>
 
</div>



</div>

</div>

<?php }; ?>