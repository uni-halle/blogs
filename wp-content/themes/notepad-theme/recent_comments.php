<ul>

<?php
  $comments = get_comments('number=10&status=approve');

  $true_comment_count = 0;

  foreach($comments as $comment) :
?>

<?php $comment_type = get_comment_type(); ?>
<?php if($comment_type == 'comment') { ?>

<?php $true_comment_count = $true_comment_count +1; ?>

<?php $comm_title = get_the_title($comment->comment_post_ID);?>
<?php $comm_link = get_comment_link($comment->comment_ID);?>
<?php $comm_comm_temp = get_comment($comment->comment_ID,ARRAY_A);?>
<?php $comm_content = $comm_comm_temp['comment_content'];?>

<li><span class="comment_author"><?php echo($comment->comment_author)?></span> on <a href="<?php echo($comm_link)?>" title="<?php comment_excerpt(); ?>"> <?php echo $comm_title?> </a></li> 

<?php } ?>

<?php if($true_comment_count == 5) {break;} ?>
<?php endforeach;?>

</ul>
