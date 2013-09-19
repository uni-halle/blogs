	</div>
    <?php
	if(get_option('uwc_sidebar_location') == "oneright") {   	
		get_sidebar(1);
	}
	if(get_option('uwc_site_sidebars') == "2" && get_option('uwc_sidebar_location') == "twoseparate") { 
		include(TEMPLATEPATH.'/sidebar2.php'); 
	}
	if(get_option('uwc_site_sidebars') == "2" && get_option('uwc_sidebar_location') == "tworight") {   	
		get_sidebar(1);
		include(TEMPLATEPATH.'/sidebar2.php');
	}
	?>
</div>
<!-- begin footer -->

<div id="footer">
    Copyright &copy; <?php echo date('Y'); ?> <a href="<?php bloginfo('url') ?>"><?php bloginfo('name'); ?></a>. Alle Rechte vorbehalten.<br />
    <span class="red">Magazine Basic</span> theme kreiert von <a href="http://bavotasan.com"><span class="red">c.bavota</span></a> von <a href="http://tinkerpriestmedia.com"><span class="red">Tinker Priest Media</span></a>.<br />
    &Uuml;bersetzt durch <a href="http://www.wp-uebersetzer.de/"><span class="red">Pascal Horn</span></a>. Powered by <a href="http://www.wordpress.org">WordPress</a>.
</div>
	
<?php wp_footer(); ?>
<!-- Magazine Three Column theme designed by Chris Bavota, http://tinkerpriestmedia.com -->

<script type="text/javascript">
	var menu=new menu.dd("menu");
	menu.init("menu","menuhover");
</script>
</body>
</html>