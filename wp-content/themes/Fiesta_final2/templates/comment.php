<div class="comment-author vcard">
    <?php echo $get_avatar; ?>
    <cite class="fn"><?php echo $comment_author_link; ?>:</cite>
</div>
<?php echo $status; ?>
<div class="comment-meta commentmetadata">
    <a href="<?php echo $get_comment_link; ?>"><?php echo $get_comment_date; ?></a>
    <?php echo $edit_comment_link; ?>
</div>
<?php echo $comment_text; ?>
<div class="reply"><?php echo $comment_reply_link; ?></div>