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
           if ($image['non_square']) {
          $fullSize = $image['non_square_large'];
          $fullSizeFallback = $image['image_large'];
          $thumbUrl = $image['thumb_non_square_large'];
      } else {
          $fullSize = $image['image_large'];
          $fullSizeFallback = $image['image_large'];
          $thumbUrl = $image['image_large'];
      }   

  ?>  
    <li style="width: <?php print $imageWidth ?>; height: <?php print $imageHeight ?>; margin-bottom: <?php print $padding ?>;">
     <a class="mainI <?php if ($image['type']==='video') {  print "video"; } else { print "image"; } ?>"
         href="http://ink361.com/app/users/ig-<?php print $image['user'] ?>/<?php print $image['username'] ?>/photos/ig-<?php print $image['id'] ?>"
         data-user-url="http://ink361.com/app/users/ig-<?php print $image['user'] ?>/<?php print $image['username'] ?>/photos"
         data-original="<?php print $fullSize ?>"
         data-video="<?php if ($image['type']==='video') {  print $image['video']; } ?>"
         title="<?php print htmlspecialchars($image['title']) ?>"
         rel="<?php print $image['id'] ?>"
         data-onclick="http://ink361.com/app/users/ig-<?php print $image['user'] ?>/<?php print $image['username'] ?>/photos/ig-<?php print $image['id'] ?>"
         >                  
         <img src="<?php print $thumbUrl ?>" style="width: <?php print $imageWidth ?>; height: <?php print $imageHeight ?>; margin-right: <?php print $imagePadding ?>; margin-bottom: <?php print $padding ?>;">
      </a>   
        <?php if ($image['type']==='video') {
        print "<button class='videoButton'></button>";
        } ?>
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
  window.wpigplugJS.jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ pagerEvent : null, prevNextEvent: null, fx : "scrollUp", timeout : <?php print $delay ?>, next: 'ul.wpinstagram-slideshow', easing: 'easeInOutBack' });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'horz') {
?>
 <script>
  window.wpigplugJS.jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "scrollRight", easing : 'easeInOutBack', timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'shuffle') {
?>
 <script>
  window.wpigplugJS.jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "shuffle", easing : 'easeOutBack', timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'zoom') {
?>
 <script>
  window.wpigplugJS.jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "zoom", sync: true, timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'turndown') {
?>
 <script>
  window.wpigplugJS.jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "turnDown", timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if (array_key_exists("transition", $settings->settings) && $settings->settings['transition'] == 'fold') {
?>
 <script>
  window.wpigplugJS.jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "curtainX", timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else {
?>
 <script>
  window.wpigplugJS.jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "fade", timeout : <?php print $delay ?> });
  });
 </script>
<?php
 }#END OF TRANSITION IF
 
 require(plugin_dir_path(__FILE__) . 'widget_footer.php'); 
?>
