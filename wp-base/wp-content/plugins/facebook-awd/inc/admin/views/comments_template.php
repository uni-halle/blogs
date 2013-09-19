<?php
	// Do not delete these lines
    if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
        die (esc_html__('Please do not load this page directly. Thanks!','Polished'));
	
    echo '<div id="comment-section" class="clearfix">'.do_shortcode('[AWD_comments url="'.get_permalink($post->ID).'" ]').'</div>';
?>