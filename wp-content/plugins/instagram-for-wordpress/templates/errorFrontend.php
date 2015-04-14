<?php
 require(plugin_dir_path(__FILE__) . 'widget_header.php');
?>
<div class="wpinstagram-error">
  <b>Widget Error!</b>
  
  <p>
    There was a problem displaying this widget:
  </p>
  
  <p class="wpinstagram-error-description">
    <?php
      echo $error;
    ?>
  </p>
</div>
<?php
 require(plugin_dir_path(__FILE__) . 'widget_footer.php');
?>
