<?php
 require(plugin_dir_path(__FILE__) . 'widget_header.php'); 
?>

<?php
  #some settings
  $rows = 3;
  $cols = 3;
    
  if (array_key_exists("rows", $settings->settings) && $settings->settings['rows']) {
    $rows = $settings->settings['rows'];
  }    
  if (array_key_exists("cols", $settings->settings) && $settings->settings['cols']) {
    $cols = $settings->settings['cols'];
  }
    
  $currentCol = 0;
  $currentRow = 0;
    
  #lets calculate our size
  $width = 220;
  $height = 220;
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
  $imageWidth = ($width - (($cols - 1) * $padding)) / $cols;
  $liWidth = $imageWidth;
  #its hip to be a square
  $imageHeight = $imageWidth;
  $liHeight = $imageHeight;

  if (array_key_exists("responsive", $settings->settings) && $settings->settings['responsive'] === 'yes') {
    $liWidth            .= '%';
    $liHeight           = 'auto';
    $imageWidth         = '100%';
    $imageHeight        = 'auto';
    $width              .= '%';
    $height             .= 'auto';
    $padding            .= '%';
  } else {
    $liWidth            .= 'px';
    $liHeight           .= 'px';
    $imageWidth         .= 'px';
    $imageHeight        .= 'px';
    $width              .= 'px';
    $height             .= 'px';
    $padding            .= 'px';
  }
?>
  
<ul class="wpinstagram live <?php if (array_key_exists("responsive", $settings->settings) && $settings->settings['responsive'] === 'yes') { echo "responsive"; } ?>" style="width: <?php print $width ?>; height: <?php print $height ?>;">
  <?php
    foreach ($images as $image) {
      $imagePadding = $padding;
      if ($currentCol == $cols - 1) {
        $imagePadding = 0;
      }      

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

      if ($settings->settings['responsive'] === 'yes') {
          if ($image['non_square']) {
            $thumbUrl = $image['thumb_non_square_large'];
          } else {
            $thumbUrl = $image['image_large'];
          }
        } else if ($imageWidth <= 150) {
           if ($image['non_square']) {
            $thumbUrl = $image['thumb_non_square_small'];
          } else {
            $thumbUrl = $image['image_small'];
          }
      } else if ($imageWidth <= 306) {
          if ($image['non_square']) {
            $thumbUrl = $image['thumb_non_square_middle'];
          } else {
            $thumbUrl = $image['image_middle'];
          } 
      } else {                
        $thumbUrl = $image['thumb_non_square_large'];
      }
  ?>

    <li style="width: <?php print $liWidth ?>; height: <?php print $liHeight ?>; margin-right: <?php print $imagePadding ?>; margin-bottom: <?php print $padding ?>;">
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
      <span class="wpcaption">
        <?php print $image['parsedtitle'] ?>
      </span>  
      <?php if ($image['type']==='video') {
        print "<button class='videoButton'></button>";
        } ?>
      <?php if ($settings->settings['sharing'] === 'yes') { ?>
        <div class="social">
          <a class="facebook" href="javascript:fbshare('http://ink361.com/app/photo/ig-<?php print $image['id'] ?>');"></a>
          <a class="twitter" href="javascript:twtshare('http://ink361.com/app/photo/ig-<?php print $image['id'] ?>');"></a>
        </div>
      <?php } ?>
    </li>
  <?php
      $currentCol++;
      if ($currentCol == $cols) {
        $currentCol = 0;
        $currentRow++;
      }
      
      if ($currentRow == $rows) {
        #stop displaying photos
        break;
      }        
    } #END FOREACH
  ?>
</ul>
<div style="clear: both;"></div>

<?php
 require(plugin_dir_path(__FILE__) . 'widget_footer.php'); 
?>
