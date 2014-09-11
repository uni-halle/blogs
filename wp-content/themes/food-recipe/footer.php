</div> <!-- container -->

<div class="container footer">
    <div class='span-24 last'>
		<div class="credits">Design: <a href="http://ericulous.com/2009/02/03/wp-theme-food-recipe/" title="Smashing Wordpress Themes">Smashing Wordpress Themes</a></div>
		<?php if (function_exists('wp_nav_menu') && has_nav_menu('footer-menu' )) {
	        $footermenu = wp_nav_menu( array( 'theme_location' => 'footer-menu', 'echo' => false, 'container' => '', 'menu_id' => 'footernav', 'depth' => 1 ) );
			$prefootermenu = '<ul id="footernav" class="menu"><li>&copy; <a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('description') . '">' . get_bloginfo('name') . '</a></li><li><a href="#" title="Return to Top">#Top</a></li>';
			echo str_replace('<ul id="footernav" class="menu">',$prefootermenu,$footermenu);
        } else { ?>
		<ul id="footernav">
			<li>&copy; <a href="<?php bloginfo('url') ?>" title="<?php bloginfo('description') ?>"><?php bloginfo('name') ?></a></li>
			<li><a href="#" title="Return to Top">#Top</a></li>
			<?php wp_list_pages('title_li=&depth=1'); ?>
        </ul>
        <?php } ?>
    </div>
</div>
<?php wp_footer() ?>
</body>
</html>