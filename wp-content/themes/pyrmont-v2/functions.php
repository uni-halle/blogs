<?php
	if ( function_exists('register_sidebar') )
		register_sidebar();
	function widget_mytheme_search() {
?>
	<li class="widget_search">
  <form id="searchform" action="<?php bloginfo('url'); ?>" method="get">
	<input type="text" id="searchinput" name="s" class="searchinput" value="search" onfocus="if (this.value == 'search') {this.value = '';}" onblur="if (this.value == '') {this.value = 'search';}" />
	<input type="submit" id="searchsubmit" class="button" value="" />
	</form>
	</li>

<?php
}

if ( function_exists('register_sidebar_widget') )
    register_sidebar_widget(__('Search'), 'widget_mytheme_search');

function mytheme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; 
?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<div class="comment-author vcard">
				<div class="left">
					<?php echo get_avatar($comment,$size='48'); ?>
					<div class="reply">
						<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
					</div>
				</div>
			</div><!-- end vcard -->
		
			<div class="right">
				<?php if ($comment->comment_approved == '0') : ?>
		        	<em><?php _e('Waiting for the admin to approve your comment. Please be patient.', 'pyrmont_v2'); ?></em>
		         	<br />
		        <?php endif; ?>
				
				<div class="comment-meta commentmetadata">
					<?php comment_author_link() ?><?php _e('&nbsp;said:&nbsp;','pyrmont_v2');?><?php comment_date(__('Y.m.d','pyrmont_v2')) ?>&nbsp;<?php comment_time(__('H:i','pyrmont_v2')) ?>
				</div>
			
				<?php comment_text() ?>
			</div>
			<div class="clear"></div>
		</div>
<?php }

function rc(){
	global $wpdb;
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,15) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 8";


	$comments = $wpdb->get_results($sql);
	$output = $pre_HTML;

	foreach($comments as $comment){
		$before = '<li class="rc"> ';
		$after = '</li>';
		
		if(strlen($comment->com_excerpt) >= 15){
			$comment->com_excerpt .= '...'; 
		}
		
		$output .= $before . '<a href="' . get_permalink($comment->ID) . '#comment-' . $comment->comment_ID . '" title="'. $comment->comment_author . __(' comments on ', 'pyrmont_v2') .$comment->post_title . '">' . strip_tags($comment->comment_author) . '</a>' . ': ' . '<span class="excerpt">' . strip_tags($comment->com_excerpt) . '</span>' . $after;
	}

	$output .= $post_HTML;
	
	echo $output;
}

add_action ('init','my_theme_init');
function my_theme_init (){
	load_theme_textdomain ('pyrmont_v2', get_template_directory() . '/languages');
}


 ?>