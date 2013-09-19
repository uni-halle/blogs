<?php

/*
URI: http://wphacks.com/log/how-to-add-spam-and-delete-buttons-to-your-blog/
*/ 

function delete_comment_link($id) {
    if (current_user_can('edit_post')) {
        echo '&nbsp;-&nbsp; <a href="'.admin_url("comment.php?action=cdc&c=$id").'">'.get_option('bizzthemes_comment_delete_name').'</a> ';
        echo '&nbsp;-&nbsp; <a href="'.admin_url("comment.php?action=cdc&dt=spam&c=$id").'">'.get_option('bizzthemes_comment_spam_name').'</a>';
    }
}

// Use legacy comments on versions before WP 2.7
add_filter('comments_template', 'old_comments');

function old_comments($file) {

	if(!function_exists('wp_list_comments')) : // WP 2.7-only check
		$file = TEMPLATEPATH . '/comments-old.php';
	endif;

	return $file;
}

// Custom comment loop
function custom_comment($comment, $args, $depth) {	
       $GLOBALS['comment'] = $comment; ?>
	
<li class="comment wrap threaded" id="comment-<?php comment_ID() ?>">
		
		    <div class="meta-left">
			
				<div class="meta-wrap">
					
					<?php echo get_avatar( $comment, 48, $template_path . ''.get_bloginfo('template_directory').'/images/gravatar.png' ); ?><br />
					
					<p class="authorcomment"><?php comment_author_link() ?><br /></p>
					
					<p><small><?php if(!function_exists('how_long_ago')){comment_date('M d, Y'); } else { echo how_long_ago(get_comment_time('U')); } ?></small></p>
				
				</div>
				
			</div>
			    
			<div class="text-right <?php if (1 == $comment->user_id) $oddcomment = "authcomment"; echo $oddcomment; ?>">
				
				<?php comment_text() ?>
					
				<?php if ($comment->comment_approved == '0') : ?>
					
				<p><em><?php echo get_option('bizzthemes_comment_moderation_name'); ?></em></p>
					
				<?php endif; ?>
				
			</div>
		

		<span class="comm-reply">
		
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'reply_text' => __(''.get_option('bizzthemes_comment_justreply_name').''), 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		
		<?php edit_comment_link(''.get_option('bizzthemes_comment_edit_name').'', '&nbsp;-&nbsp;&nbsp;', ''); ?>
		
		<?php delete_comment_link(get_comment_ID()); ?>
		
		</span>

		<div class="fix"></div>
		 
<?php } ?>