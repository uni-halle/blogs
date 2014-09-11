

<?php 
$countComments = 0;
$countPings = 0;
if($post->comment_count > 0) {
	$comments_list = array();
	$pings_list = array();
	foreach($comments as $comment) {
		if('comment' == get_comment_type()) $comments_list[++$countComments] = $comment;
		else $pings_list[++$countPings] = $comment;
	}
}

$fields =  array(
	'author' => '<p><input type="text" name="author" id="author" value="" size="22" tabindex="1" aria-required="true" /><label for="author"><small> Name</small></label></p>',
	'email'  => '<p><input type="text" name="email" id="email" size="22" tabindex="2" aria-required="true" /><label for="email"><small> Mail (will not be published)</small></label></p>',
	'url'    => '<p><input type="text" name="url" id="url" size="22" tabindex="3" /><label for="url"><small> Website</small></label></p>'
); 

$fargs = array(
'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
'comment_notes_after'  => '',
'comment_notes_before' => '',
'cancel_reply_link'    => __( '<small>Cancel reply</small>' ),
'comment_field'        => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>'

);

$largs = array(
'style'             => 'ol',
'avatar_size'       => 42,
'reply_text'		=> '<small>Reply</small>',
'type'				=> 'comment'
);


if ( have_comments() ) : ?>
<div id="commentbox">
<h3 id="comments"><?php comments_number('No Comments', 'One Comment', '% Comments' );?></h3>

<ol class="commentlist"><?php wp_list_comments($largs); ?></ol>

	<div class="navigation page-link commentpage">
	<?php $args = array(
  'prev_text' => __('Previous'),
  'next_text' => __('Next')
); ?>
		<?php paginate_comments_links( $args ) ?> 
	</div>
	</div>
	
<?php

comment_form($fargs); ?>

<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( comments_open() ) : ?>
	<div id="commentbox">
	
	<?php
		comment_form($fargs);// If comments are open, but there are no comments.
		?> </div><?php
	else : // comments are closed 

	 
	endif;
endif;?>

<?php if ($countPings > 0) { ?>
<h3>Pingbacks &amp; Trackbacks</h3>

<ol id="pinglist">
				<?php foreach($pings_list as $comment) : 
						if('pingback' == get_comment_type()) $pingtype = 'Pingback';
						else $pingtype = 'Trackback';
				?>
				<li id="comment-<?php echo $comment->comment_ID ?>">
					<?php comment_author_link(); ?> - <?php echo $pingtype; ?> on <?php echo mysql2date('Y/m/d', $comment->comment_date); ?>
				</li>
				<?php endforeach; ?>
</ol>

<?php
} ?>
