<?php
/**
 * @package WordPress
 * @subpackage magazine_obsession
 */

/* Get ID of the page, if this is current page */

function obwp_get_page_id () {
	global $wp_query;

	if ( !$wp_query->is_page )
		return -1;

	$page_obj = $wp_query->get_queried_object();

	if ( isset( $page_obj->ID ) && $page_obj->ID >= 0 )
		return $page_obj->ID;

	return -1;
}

/**
 * Get Meta post/pages value
 * $type = string|int
 */
function obwp_get_meta($var, $type = 'string', $count = 0)
{
	$value = stripslashes(get_option($var));
	
	if($type=='string')
	{
		return $value;
	}
	elseif($type=='int')
	{
		$value = intval($value);
		if( !is_int($value) || $value <=0 )
		{
			$value = $count;
		}
		
		return $value;
	}
	
	return NULL;
}

/**
 * Get custom field of the current page
 * $type = string|int
 */
function obwp_getcustomfield($filedname, $page_current_id = NULL)
{
	if($page_current_id==NULL)
		$page_current_id = obwp_get_page_id();

	$value = get_post_meta($page_current_id, $filedname, true);

	return $value;
}
function the_title_limited($length = false, $before = '', $after = '', $echo = true)
{
	$title = get_the_title();

	if ( $length && is_numeric($length) )
	{
		$title = substr( $title, 0, $length );
	}
	if ( strlen($title)> 0 )
	{
		$title = apply_filters('the_title2', $before . $title . $after, $before, $after);
		if ( $echo )
			echo $title;
		else
			return $title;
	}
}

function the_content_limit($max_char, $more_link_text = '', $use_p = false, $stripteaser = 0, $more_file = '')
{
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = strip_tags($content);

   if (strlen($_GET['p']) > 0) {
	  if($use_p)
      	echo "<p>";
      echo $content;
	  if($use_p)
      	echo "</p>";
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
	  	if($use_p)
       		echo "<p>";
        echo $content;
        echo "...";
	  	if($use_p)
        	echo "</p>";
   }
   else {
	  if($use_p)
      	echo "<p>";
      echo $content;
	  if($use_p)
      	echo "</p>";
   }
}

function get_string_limit($output, $max_char)
{
    $output = str_replace(']]>', ']]&gt;', $output);
    $output = strip_tags($output);

  	if ((strlen($output)>$max_char) && ($espacio = strpos($output, " ", $max_char )))
	{
        $output = substr($output, 0, $espacio).'...';
		return $output;
   }
   else
   {
      return $output;
   }
}

function obwp_recent_comments($number = 10) {
	global $wpdb, $comments, $comment;
	$comments = get_comments('');

		 $i=0;
		 $last = '';
        if ( $comments ) : foreach ($comments as $comment) :
		 $i++;
		if($i==$number) $last = 'last';
		$comment_content = strip_tags($comment->comment_content);
        echo  '<li class="recentcomments '.$last.'">' . sprintf(__('<b>%1$s</b> : <span>%2$s</span>'), get_comment_author_link(), '<a href="'. get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '">' . get_string_limit($comment_content, 200) . '</a>') . '</li>';
        endforeach; endif;?>
<?php
}


function obwp_list_recent_posts($number = 10) {

	$posts = get_posts('cat='.EXCEPT_CAT.'&orderby=date&numberposts='.$number);
	
	?>
    <ul>
    <?php
	$countposts = count($posts);
	for($i=0; $i<$countposts; $i++)
	{
		?>
        	<li <?php if($i==($countposts-1)) echo 'class="last"'; ?>><a href="<?php echo get_permalink($posts[$i]->ID); ?>"><?php echo $posts[$i]->post_title; ?></a></li>
        <?php
	}
	?>
    </ul>
    <?php

}

function theme_about_logo()
{
	$logo = obwp_get_meta(SHORTNAME.'_about_logo_url');
	$uploads = wp_upload_dir();
	$dir = $uploads['basedir'].'/'.SHORTNAME.'_images';
	$logo_path = $dir.'/'.basename($logo);

	if(!empty($logo) && file_exists($logo_path))
	{
		return $logo;
	}
	else
	{
		return '';
	}
}

/***********************************************************************/
/* CUSTOM FIELDS */
/*
	thumbnail = for top carusel
*/
/***********************************************************************/


function carousel_featured_posts($cat_id, $max_posts=5, $offset=0) {
	
	query_posts('cat='.$cat_id.'&showposts=-1');
	if (have_posts()) :
		
		$html = '';
		$coint_i = 0;
		while (have_posts()) : the_post();

			$coint_i++;
			$post = get_post(get_the_ID());
			$post_title = stripslashes($post->post_title);
			$post_title = str_replace('"', '', $post_title);
			$post_content = stripslashes($post->post_content);
			$post_content = str_replace(']]>', ']]&gt;', $post_content);
			$post_content = strip_tags($post_content);
			$permalink = get_permalink(get_the_ID());
			$post_id = get_the_ID();
			$html .= '<div class="board_item">
				<!-- board_item -->
				<p>';
				
			$thumbnail = get_post_meta($post_id, 'thumbnail', true);
			
			if( isset($thumbnail) && !empty($thumbnail) ):
				$html .= '<img src="'.$thumbnail.'" alt="'.$post_title.'" />';
			endif;
			
			$html .= '<strong><a href="'.$permalink.'">'.get_string_limit($post_title,50).'</a></strong> '.get_string_limit($post_content,200).'</p>
				<p class="more"><a href="'.$permalink.'">Read more</a></p>
				<!-- /board_item -->
			</div>';
		endwhile; wp_reset_query();
	
	endif;
	echo $html;
	return $coint_i;
}

?>