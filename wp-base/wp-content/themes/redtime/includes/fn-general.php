<?php
/**
 * @package WordPress
 * @subpackage magazine_obsession
 */

/**
 * Get Meta post/pages value
 * $type = string|int
 */
function obwp_get_meta($var, $type = 'string', $count = 1)
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
		$page_current_id = get_page_id();

	$value = get_post_meta($page_current_id, $filedname, true);

	return $value;
}

function wp_list_pages2($args) {

	$defaults = array(
		'depth' => 0, 'show_date' => '',
		'date_format' => get_option('date_format'),
		'child_of' => 0, 'exclude' => '',
		'title_li' => __('Pages'), 'echo' => 1,
		'authors' => '', 'sort_column' => 'menu_order, post_title',
		'link_before' => '', 'link_after' => ''
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );

	$output = '';
	$current_page = 0;

	// sanitize, mostly to keep spaces out
	$r['exclude'] = preg_replace('/[^0-9,]/', '', $r['exclude']);

	// Allow plugins to filter an array of excluded pages
	$r['exclude'] = implode(',', apply_filters('wp_list_pages_excludes', explode(',', $r['exclude'])));

	// Query pages.
	$r['hierarchical'] = 0;
	$pages = get_pages($r);

	if ( !empty($pages) ) {
		if ( $r['title_li'] )
			$output .= '<li class="pagenav">' . $r['title_li'] . '<ul>';

		global $wp_query;
		if ( is_page() || $wp_query->is_posts_page )
			$current_page = $wp_query->get_queried_object_id();
		$output .= walk_page_tree($pages, $r['depth'], $current_page, $r);

		if ( $r['title_li'] )
			$output .= '</ul></li>';
	}

	$output = apply_filters('wp_list_pages', $output);

	if ( $r['echo'] )
		echo $output;
	else
		return $output;
}

function theme_twitter_link_show()
{
	$id = obwp_get_meta(SHORTNAME."_twitter_id");
	if(!empty($id))
	{
		return 'http://twitter.com/'.$id;
	}
	else
	{
		return '#';
	}
}

function theme_twitter_show()
{
	$id = obwp_get_meta(SHORTNAME."_twitter_id");
	if(!empty($id))
	{
	?>
	<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
	<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $id; ?>.json?callback=twitterCallback2&count=1"></script>
	<?php
	}
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

?>