<?php
 require(plugin_dir_path(__FILE__) . 'widget_header.php'); 
?>

<?php
  #some settings
  $rows = 3;
  $cols = 3;
    
  if ($settings['rows']) {
    $rows = $settings['rows'];
  }    
  if ($settings['cols']) {
    $cols = $settings['cols'];
  }
    
  $currentCol = 0;
  $currentRow = 0;
    
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
  $imageWidth = ($width - (($cols - 1) * $padding)) / $cols;
  #its hip to be a square
  $imageHeight = $imageWidth;
?>
  
<ul class="wpinstagram live" style="width: <?php print $width ?>px; height: <?php print $height ?>px;">
  <?php
    foreach ($images as $image) {
      $imagePadding = $padding;
      if ($currentCol == $cols - 1) {
        $imagePadding = 0;
      }      

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
    
  
    <li style="width: <?php print $imageWidth ?>px; height: <?php print $imageHeight ?>px; margin-right: <?php print $imagePadding ?>px !important; margin-bottom: <?php print $padding ?>px !important;">
      <a class="mainI" href="http://ink361.com/app/photo/ig-<?php print $image['id'] ?>"
         data-user-url="http://ink361.com/app/photo/ig-<?php print $image['id'] ?>"
         data-original="<?php print $image['image_large'] ?>"
         title="<?php print $image['title'] ?>"
         rel="<?php print $image['id'] ?>"
         data-onclick="http://ink361/com/app/photo/ig-<?php print $image['id'] ?>"
         >
         
         <img src="<?php print $url ?>" style="width: <?php print $imageWidth ?>px; height: <?php print $imageHeight ?>px; margin-right: <?php print $imagePadding ?>px; margin-bottom: <?php print $padding ?>px;">
      </a>         
      <div class="social">
        <a class="facebook" href="javascript:fbshare('http://ink361.com/app/photo/ig-<?php print $image['id'] ?>');"></a>
        <a class="twitter" href="javascript:twtshare('http://ink361.com/app/photo/ig-<?php print $image['id'] ?>');"></a>
      </div>
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
