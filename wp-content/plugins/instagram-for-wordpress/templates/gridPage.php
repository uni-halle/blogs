<?php
 require(plugin_dir_path(__FILE__) . 'widget_header.php'); 
?>

<?php
  #some settings
  $rows = 3;
  $cols = 3;
    
  if ($settings->settings['rows']) {
    $rows = $settings->settings['rows'];
  }    
  if ($settings->settings['cols']) {
    $cols = $settings->settings['cols'];
  }
    
  $currentCol = 0;
  $currentRow = 0;
    
  #lets calculate our size
  $width = 220;
  $height = 220;
  $padding = 5;
    
  if ($settings->settings['width']) {
    $width = $settings->settings['width'];
  }
  if ($settings->settings['height']) {
    $height = $settings->settings['height'];
  }    
  if ($settings->settings['padding']) {
    $padding = $settings->settings['padding'];
  }
  
  if ($settings->settings['responsive'] === 'yes') {
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
  
  if ($settings->settings['responsive'] === 'yes') {
    $liWidth 		.= '%';
    $liHeight 		= 'auto';
    $imageWidth 	= '100%';
    $imageHeight 	= 'auto';
    $width 		.= '%';
    $height 		.= 'auto';
    $padding 		.= '%';
  } else {
    $liWidth		.= 'px';
    $liHeight		.= 'px';
    $imageWidth 	.= 'px';
    $imageHeight 	.= 'px';
    $width 		.= 'px';
    $height 		.= 'px';
    $padding 		.= 'px';
  }
?>
  
<ul class="wpinstagram live <?php if ($settings->settings['responsive'] === 'yes') { echo "responsive"; } ?>" style="width: <?php print $width ?>; height: <?php print $height ?>;">
  <?php
    $count=0;
    foreach ($images as $image) {
      $imagePadding = $padding;
      if ($currentCol == $cols - 1) {
        $imagePadding = 0;
      
        if ($settings->settings['responsive'] === 'yes') {
          $imagePadding .= '%';
        } else {
          $imagePadding .= 'px';
        }
      }      
      
      $count++;
      $page = ceil($count / ($rows * $cols));

      #determine photo to use for best quality
      if ($image['non_square']) {
          $fullSize = $image['non_square_large'];
          $fullSizeFallback = $image['image_large'];
      } else {
          $fullSize = $image['image_large'];
          $fullSizeFallback = $image['image_large'];
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
    
  
    <li class="paged <?php print $settings->uid . '-all' ?> <?php print $settings->uid . '-page-' . $page ?>" style="width: <?php print $liWidth ?>; height: <?php print $liHeight ?>; margin-right: <?php print $imagePadding ?>; margin-bottom: <?php print $padding ?>;">
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
    } #END FOREACH
  ?>
</ul>
<div style="clear: both;"></div>
<div class="wppages">
  <div class="page first">
    Page
  </div>
  <?php
    $perpage = $rows * $cols;
    $pages = ceil(sizeof($images) / $perpage);
    
    for ($i = 1; $i <= $pages; $i++) {
     ?>
     <a href="javascript:gotoPage<?php print $settings->uid ?>(<?php print $i ?>);" class="page">
      <?php print $i ?>
     </a>
     <?php
    }
  ?> 
</div>
<div style="clear: both;"></div>

<script>
  function gotoPage<?php print $settings->uid ?>(page) {
    var prePended = '.<?php print $settings->uid . '-page-' ?>';
    var allIMG = '.<?php print $settings->uid . '-all' ?>';
    
    jQuery(allIMG).removeClass('visible');
    jQuery(prePended + page).addClass('visible');
  }
  
  jQuery(document).ready(function() {
    gotoPage<?php print $settings->uid ?>(1);
  });
</script>

<?php
 require(plugin_dir_path(__FILE__) . 'widget_footer.php'); 
?>
