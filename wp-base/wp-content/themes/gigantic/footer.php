<ul class="footer">
	
	<li>&copy; copyright <?php echo date("Y"); ?> <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></li>
	<li>Powered by <a href="http://wordpress.org/">WordPress</a> and the <a href="http://leonpaternoster.com/2009/02/introducing-the-gigantic-theme-for-wordpress/">Gigantic theme</a></li>
	<li><?php wp_loginout(); ?></li>
	<li><a href="<?php echo bloginfo('url'); ?>/wp-admin">Admin</a></li>
	<li><?php bloginfo('admin_email'); ?></li>
	
</ul>

<?php wp_footer(); ?>

</div> <!-- end page -->

</body>

</html>
