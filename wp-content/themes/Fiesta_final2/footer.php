<?php  
$content = ob_get_clean();
echo art_parse_template(art_page_template(), art_page_variables(array('content'=> $content)));
?>
    <div id="wp-footer">
	        <?php wp_footer(); ?>
	        <!-- <?php printf(__('%d queries. %s seconds.', THEME_NS), get_num_queries(), timer_stop(0, 3)); ?> -->
    </div>
</body>
</html>
