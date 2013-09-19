<?php

?>

<hr />
<div id="footer">
	<p>
		<?php bloginfo('name'); ?>

<?php _e('by ' ); ?> <a href="http://www.themebuilder.nl" target="_blank" title="Themebuilder">Themebuilder</a> |

 		<a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a>
		and <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a>.
		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
	</p>
</div>
</div>
</div>

		<?php wp_footer(); ?>
</body>
</html>
