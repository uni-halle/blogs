<!-- This file is used to markup the public facing widget. -->
<?php if ( $title ) echo $before_title . $title . $after_title; ?>
<p class="readability-wrapper">
  <div
    class="rdbWrapper"
    data-show-read-now="<?php if ( 1 == $show_read_now ) : ?>1<?php else : ?>0<?php endif; ?>"
    data-show-read-later="<?php if ( 1 == $show_read_later ) : ?>1<?php else : ?>0<?php endif; ?>"
    data-show-send-to-kindle="<?php if ( 1 == $show_send_to_kindle ) : ?>1<?php else : ?>0<?php endif; ?>"
    data-show-print="<?php if ( 1 == $show_print ) : ?>1<?php else : ?>0<?php endif; ?>"
    data-show-email="<?php if ( 1 == $show_email ) : ?>1<?php else : ?>0<?php endif; ?>"
    data-orientation="<?php if ( 1 == $show_vertical ) : ?>1<?php else : ?>0<?php endif; ?>"
    data-version="1"
    data-text-color="#<?php echo $text_color; ?>"
    data-bg-color="#<?php echo $bg_color; ?>">
  </div>
  <script>(function() {var s = document.getElementsByTagName("script")[0],rdb = document.createElement("script"); rdb.type = "text/javascript"; rdb.async = true; rdb.src = "//d370mdr42phnax.cloudfront.net/embed/embed.js"; s.parentNode.insertBefore(rdb, s); })();</script>
</p>
