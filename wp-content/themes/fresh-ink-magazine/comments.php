<?php
// Do not delete these lines 
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');

// Standard WordPress comments security
if ( post_password_required() ) {
	echo '<p class="nocomments">This post is password protected. Enter the password to view comments.</p>';
	return;
}
?>
<?php if (have_comments()): ?>
	<h4 id="comments">
		<?php comments_number('No Comments', 'One Comment', '% Comments' );?>
	</h4>
	
	<div class="navigation">
		<div class="alignleft">
			<?php previous_comments_link() ?>
		</div>
		<div class="alignright">
			<?php next_comments_link() ?>
		</div>
		<br/>
	</div>
	
	<ol class="commentlist">
		<?php wp_list_comments(); ?>
	</ol>
	<div class="navigation">
		<?php paginate_comments_links(); ?>
	</div>
<?php else: // this is displayed if there are no comments so far ?>

	<?php if (comments_open()): // If comments are open, but there are no comments. ?>
	

<?php endif; // if you delete this the sky will fall on your head ?>
<?php endif; // if you delete this the sky will fall on your head ?>

<?php comment_form(); ?>