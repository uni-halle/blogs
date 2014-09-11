<?php 

//sidebar widgets
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
}


//custom comment template
function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li>
		<p class="comment-author">
			<?php echo get_avatar($comment,$size='42',$default='<path_to_url>' ); ?>
			<?php printf(__('<cite>%s</cite>'), get_comment_author_link()) ?><br />
			<small><?php comment_date('M d, Y'); ?> @ <?php comment_time('H:i:s'); ?><?php edit_comment_link('Edit',' [',']') ?></small>
		</p>
		<div class="commententry" id="commententry-<?php comment_ID() ?>">
			<?php if ($comment->comment_approved == '0') : ?>
			<p><em><?php _e('Your comment is awaiting moderation.') ?></em></p>
			<?php endif; ?>
		
			<?php comment_text() ?>
		</div>
		
		<p class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'commententry', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</p>

<?php
        }


?>