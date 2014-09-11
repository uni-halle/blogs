<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
    
	<?php $comments_by_type = &separate_comments($comments); if ( ! empty($comments_by_type['comment']) ) : ?>
	<h3 id="comments">Reader's Comments</h3>
    <ol class="commentlist">
    <?php wp_list_comments(array('callback' => 'custom_comment', 'type' => 'comment')); ?>
    </ol>
	<?php endif; ?>
    
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
    <h3 id="pingbacks">Pingbacks/Trackbacks</h3>
    <ol class="pingbacklist">
    <?php wp_list_comments(array('callback' => 'custom_pingback', 'type' => 'pings')); ?>
    </ol>
	<?php endif; ?>
    
	<div style="clear:both"></div>
	<div class="commentsnavigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link('Newer Comments &raquo;') ?></div>
	</div>
   	<div style="clear:both"></div>
    
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
        <?php if (!is_page()) { ?>
		<p class="nocomments">Comments are closed.</p>
		<?php } ?>
	<?php endif; ?>
<?php endif; ?>

<?php comment_form('title_reply=Leave a Comment&label_submit=Submit Comment&comment_notes_before=&comment_notes_after=&comment_field=<p class="comment-form-comment"><textarea id="comment" name="comment" cols="35" rows="10" aria-required="true"></textarea></p>'); ?>