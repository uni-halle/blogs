<div id="kommentare">
    <!-- Template comments -->
    <?php $comments = array_reverse($comments, true); ?>
    <?php foreach ($comments as $comment) : ?>
    <div class="comment" id="comment-<?php comment_ID() ?>">
        <p class="commentmetadata"><?php comment_author_link() ?> sagt:<br/><span>am <?php comment_date('j. F Y') ?> um <?php comment_time('H:i') ?> Uhr</span></p>
        <?php comment_text() ?>
        <?php if ($comment->comment_approved == '0') : ?>
        <strong>Achtung: Dein Kommentar muss erst noch freigegeben werden.</strong><br />
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>