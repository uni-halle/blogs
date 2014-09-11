<?php 
get_header();
if (have_posts()) 
{
  while (have_posts())  
  {
    art_page_navi();
    art_post();
    comments_template();
  }
  art_page_navi();
} else {    
  art_not_found_msg();
}
get_footer(); 