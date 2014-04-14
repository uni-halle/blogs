<?php
 require(plugin_dir_path(__FILE__) . 'widget_header.php'); 
?>

<?php
  #lets calculate our size
  $width = 300;
  $height = 350;
  $padding = 5;
    
  if ($settings['width']) {
    $width = $settings['width'];
  }
  if ($settings['height']) {
    $height = $settings['height'];
  }    
  if ($settings['padding']) {
    $padding = $settings['padding'];
  }
    
  #how big?
  $imageWidth = $width;
  #its hip to be a square
  $imageHeight = $imageWidth;
?>
  
<ul class="wpinstagram wpinstagram-slideshow live" style="width: <?php print $width ?>px; height: <?php print $width ?>px;">
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
    <li style="width: <?php print $imageWidth ?>px; height: <?php print $imageHeight ?>px; margin-bottom: <?php print $padding ?>px !important;">
      <a class="mainI" href="http://ink361.com/app/photo/ig-<?php print $image['id'] ?>"
         data-user-url="http://ink361.com/app/photo/ig-<?php print $image['id'] ?>"
         data-original="<?php print $image['image_large'] ?>"
         title="<?php print $image['title'] ?>"
         rel="<?php print $image['id'] ?>"
         data-onclick="http://ink361/com/app/photo/ig-<?php print $image['id'] ?>"
         >
         
         <img src="<?php print $url ?>" style="width: <?php print $imageWidth ?>px; height: <?php print $imageHeight ?>px; margin-bottom: <?php print $padding ?>px;">

      </a>
      <div class="social">
        <a class="facebook" href="javascript:fbshare('http://ink361.com/app/photo/ig-<?php print $image['id'] ?>');"></a>
        <a class="twitter" href="javascript:twtshare('http://ink361.com/app/photo/ig-<?php print $image['id'] ?>');"></a>
      </div>
    </li>
  <?php
    } #END FOREACH
  ?>
</ul>
<div style="clear: both; padding-bottom: 10px;"></div>
<?php
 $delay = 1000;
 if ($settings['delay']) {
  $delay = (int)$settings['delay'] * 1000;
 }

 if ($settings['transition'] && $settings['transition'] == 'vert') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ pagerEvent : null, prevNextEvent: null, fx : "scrollUp", timeout : <?php print $delay ?>, next: 'ul.wpinstagram-slideshow', easing: 'easeInOutBack' });
  });
 </script>
<?php
 } else if ($settings['transition'] && $settings['transition'] == 'horz') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "scrollRight", easing : 'easeInOutBack', timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if ($settings['transition'] && $settings['transition'] == 'shuffle') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "shuffle", easing : 'easeOutBack', timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if ($settings['transition'] && $settings['transition'] == 'zoom') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "zoom", sync: true, timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if ($settings['transition'] && $settings['transition'] == 'turndown') {
?>
 <script>
  jQuery(document).ready(function($) {
   $("ul.wpinstagram-slideshow").cycle({ fx : "turnDown", timeout : <?php print $delay ?> });
  });
 </script>
<?php
 } else if ($settings['transition'] && $settings['transition'] == 'fold') {
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
