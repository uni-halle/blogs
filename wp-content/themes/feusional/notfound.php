<?php
/*
Template Name: 404 / Not Found Message
*/
?>
<h1>404 Error</h1>
 
<p>The page you requested cannot be found. Please check your URL or use the searchform to search for anything throughout this website.</p>
<div class="clear"></div>
 	<div class="searchfof">
		<form method="get" id="searchform" class="fleft" action="<?php bloginfo('home'); ?>/">
 		<input type="text" value="" name="s" id="searchformfield" class="fleft"/>
 		<button type="submit" id="searchformsubmit" class="fright">Find!</button>
 		</form>
	</div>