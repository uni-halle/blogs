</div><!--wrap-->
<div id="footer-wrap">
<div id="footer">
<div id="credits">
<p><strong><?php bloginfo('name');?></strong> &copy; All Rights Reserved</p>
<p>Proudly Powered by:<a href="http://wordpress.org>">Wordpress.org</a></p>
</div><!--credits-->
<div id="credits-right">
<p><a href="http://fearlessflyer.com">Theme Design by: Fearless Flyer</a></p>
</div><!--credits-->


</div><!--footer-->
</div><!--footer-wrap-->
<?
global $options;
foreach ($options as $value) {
    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>
<?php echo $koc_google_analytics; ?>




</body>
</html>