<?php
/**
 * The template used for displaying posts with video format
 *
 */
?>

<li id="post-<?php the_ID(); ?>" class="media">
    <?php 
        /*
           Check if custom field 'Video-ID' is used
           if true return content
        */
        $youtube = get_post_meta(get_the_ID(), 'Video-ID', true);
        if (!empty($youtube)) :
    ?>
    <div class="thumbnail">
        <div class="flex-video">
            <iframe src="https://www.youtube-nocookie.com/embed/<?php echo $youtube ?>?rel=0"
                    class="video-player"
                    allowfullscreen>
            </iframe>
        </div>
    </div>
    <?php endif; ?>
    <p><?php the_title(); ?></p>
</li>
