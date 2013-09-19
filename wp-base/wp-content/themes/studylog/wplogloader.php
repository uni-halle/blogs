<?php
function bdw_get_images($postID) {  
  
    
    // Get images for this post  
    $arrImages = get_children('post_type=attachment&post_mime_type=image&post_parent=' . $postID );  
    
    if($arrImages){
     $arrKeys = array_keys($arrImages);
     for($count = 0; $count < sizeof($arrKeys) ; $count++) {     
       
        $attachmentId = $arrKeys[$count];  
  
        $sThumbUrl = wp_get_attachment_thumb_url($attachmentId);  
        $laenge = strlen($sThumbUrl);
        if($laenge==0)
        {
          $sThumbUrl = wp_get_attachment_url($attachmentId);             
        }
        echo "<thumb>".$sThumbUrl."</thumb>\n"; 
    }  
    }
} ;?> 

<?php
function get_images($postID) {  
  
    
      
$args = array(
	'post_type' => 'attachment',
	'numberposts' => -1,
	'post_status' => 'inherit',
	'post_parent' => $postID
	); 
$attachments = get_posts($args);
if ($attachments) {
	foreach ($attachments as $attachment) {
		if ( wp_attachment_is_image( $attachment->ID ) )
		{
		  $sThumbUrl = wp_get_attachment_thumb_url($attachment->ID);  
          $laenge = strlen($sThumbUrl);
          if($laenge==0)
          {
           $sThumbUrl = wp_get_attachment_url($attachment->ID);             
          }
		  echo "<thumb>".$sThumbUrl."</thumb>\n"; 
		}
	}
}
} ;?> 
  



 
 <?php print "<?xml version='1.0' encoding='UTF-8' ?>\n";?>
 <?php print "<studylog>\n";?>

 <? query_posts('showposts=500');
  while (have_posts()) : the_post(); ?>

 <?php print "<log>\n";?>
 <?php print "<id>";?><?php the_ID(); ?><?php print "</id>\n";?>
 <?php print "<author>";?><?php the_author(); ?><?php print "</author>\n";?>
 <?php print "<item>";?><?php the_title(); ?><?php print "</item>\n";?>
 <?php print "<date>";?><?php the_time('Y/m/d'); ?><?php print "</date>\n";?>
 <?php print "<tags>";?> 
 <?php
$posttags = get_the_tags();
if ($posttags) {
foreach($posttags as $tag) {
echo "<tag><id>".$tag->term_id."</id><name>".$tag->name."</name><slug>".$tag->slug."</slug></tag>"; 
}
}
?>
<?php print "</tags>\n";?>
 
 
 
 <?php print "<thumbs>\n";?><?php get_images( ($post->ID) ); ?><?php print "</thumbs>\n";?>
 <?php print "<description>\n";?><?php the_excerpt(); ?><?php print "</description>\n";?> 
 <?php print "<link>";?><?php bloginfo('url'); ?>?p=<?php echo $post->ID; ?><?php print "</link>\n";?>
 <?php print "</log>\n";?>

  <?php endwhile; ?>

 
  <?php print "</studylog>\n";?>
  
  
