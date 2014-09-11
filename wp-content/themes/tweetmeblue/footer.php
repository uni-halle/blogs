
            
        </div>
    </div>

	<div id="body_right">
		
		<div id="main_search">
			<form method="get" id="searchform_top" action="<?php bloginfo('url'); ?>/">
				<div>
					<input type="text" value="Type your search here..." name="s" id="searchform_top_text" onclick="this.value='';" />
					<input type="image" src="<?php bloginfo('template_url')?>/images/button_go.gif" id="gosearch" />
				</div>
			</form>
		</div>
		<div id="sidebars">
			<?php get_sidebar(); ?>
		</div>
	</div>

</div>
</div>

<div id="footer">
	<h2>
		<a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a>
	</h2>
	<div id="footer_text">
    	<p>&copy; All Rights Reserved. <a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></p>
		<p class="designed">Powered by <a href="http://wordpress.org/">WordPress</a> | Designed by <b><a href="http://www.webdesignlessons.com/">WebDesignLessons.com</a></b></p>
    </div>
</div>

<!-- Gorgeous design by Michael Heilemann - http://binarybonsai.com/kubrick/ -->
<?php /* "Just what do you think you're doing Dave?" */ ?>

		<?php wp_footer(); ?>


</div>

</body>
</html>
