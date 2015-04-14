<aside class="widget widget-instagram-for-wordpress">
  <h3 class="widget-title">
    <?php
      if ($settings->settings['title']) {
        print $settings->settings['title'];
      } else {
        print "My Instagrams";
      }
    ?>
  </h3>
