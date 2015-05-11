<?php
 require(plugin_dir_path(__FILE__) . 'widget_header.php'); 
?>

<?php
  #lets calculate our size
  $width = 300;
  $height = 350;
  $padding = 5;
    
  if (array_key_exists("width", $settings->settings) && $settings->settings['width']) {
    $width = $settings->settings['width'];
  }
  if (array_key_exists("height", $settings->settings) && $settings->settings['height']) {
    $height = $settings->settings['height'];
  }    
  if (array_key_exists("padding", $settings->settings) && $settings->settings['padding']) {
    $padding = $settings->settings['padding'];
  }
  
  if (array_key_exists("responsive", $settings->settings) && $settings->settings['responsive'] === 'yes') {
   $width = 100;
   $height = 100;
   $padding = 2;
  }
    
  #how big?
  $imageWidth = $width;
  #its hip to be a square
  $imageHeight = $imageWidth;
  
  if (array_key_exists("responsive", $settings->settings) && $settings->settings['responsive'] === 'yes') {
    $imageWidth         = '100%';
    $imageHeight        = 'auto';
    $width              .= '%';
    $height             .= 'auto';
    $padding            .= '%';
  } else {
    $imageWidth         .= 'px';
    $imageHeight        .= 'px';
    $width              .= 'px';
    $height             .= 'px';
    $padding            .= 'px';
  }
?>
  
<ul class="wpinstagram wpinstagram-slideshow live <?php if (array_key_exists("responsive", $settings->settings) && $settings->settings['responsive'] === 'yes') { echo "responsive"; } ?>" style="width: <?php print $width ?>; height: <?php print $width ?>;">
  <?php
    foreach ($images as $image) {
    
     #determine photo to use for best quality
     $url = $image['image_small'];
     if ($imageWidth <= 150) {
       $url = $image['image_small'];
     } else if ($imageWidth <= 306) {
       $url = $image['image_middle'];
     } else {
       $url = $image['image_large'];
     }     
  ?>  
    <li style="width: <?php print $imageWidth ?>; height: <?php print $imageHeight ?>; margin-bottom: <?php print $padding ?>;">
      <a class="mainI" 
         href="http://ink361.com/app/users/ig-<?php print $image['user'] ?>/<?php print $image['username'] ?>/photos/ig-<?php print $image['id'] ?>"
         data-user-url="http://ink361.com/app/users/ig-<?php print $image['user'] ?>/<?php print $image['username'] ?>/photos/ig-<?php print $image['id'] ?>"
         data-original="<?php print $image['image_large'] ?>"
         title="<?php print htmlspecialchars($image['title']) ?>"
         rel="<?php print $image['id'] ?>"
         data-onclick="http://ink361/com/app/users/ig-<?php print $image['user'] ?>/<?php print $image['username'] ?>/photos/ig-<?php print $image['id'] ?>"
         >
         
         <img src="<?php print $url ?>" style="width: <?php print $imageWidth ?>; height: <?php print $imageHeight ?>; margin-bottom: <?php print $padding ?>;">
      </a>
      <span class="wpcaption">
        <?php print $image['parsedtitle'] ?>
      </span> 
      <?php if ($settings->settings['sharing'] === 'yes') { ?>
       <div class="social">
         <a class="facebook" href="javascript:fbshare('http://ink361.com/app/photo/ig-<?php print $image['id'] ?>');"></a>
         <a class="twitter" href="javascript:twtshare('http://ink361.com/app/photo/ig-<?php print $image['id'] ?>');"></a>
       </div>
      <?php } ?>
    </li>
  <?php
    } #END FOREACH
  ?>
</ul>
<div style="clear: both; padding-bottom: 10px;"></div>
<?php
 $delay = 1000;
 if ($settings->settings['delay']) {
  $delay = (int)$settings->settings['delay'] * 1000;
 }

 if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'vert') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ pagerEvent : null, prevNextEvent: null, fx : "scrollUp", timeout : <?php print $delay ?>, next: 'ul.wpinstagram-slideshow', easing: 'easeInOutBack' });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'horz') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "scrollRight", easing : 'easeInOutBack', timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'shuffle') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "shuffle", easing : 'easeOutBack', timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'zoom') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "zoom", sync: true, timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'turndown') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "turnDown", timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'fold') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "curtainX", timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "fade", timeout : <?php print $delay ?> });
  });
 </script>
<?php
 }#END OF TRANSITION IF
 
 require(plugin_dir_path(__FILE__) . 'widget_footer.php'); 
?>
