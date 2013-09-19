<?php 
get_header(); 
if (have_posts()) 
{
  art_page_navi(__('Search Results', THEME_NS));
  while (have_posts())  
  {
    art_post();
  }
  art_page_navi();
} else {  
  art_not_found_msg(__('Search Results', THEME_NS),
    '<p class="center">' .  __('No posts found. Try a different search?', THEME_NS) . '</p>'
    .  "\r\n" . art_get_search());
}
get_footer(); 