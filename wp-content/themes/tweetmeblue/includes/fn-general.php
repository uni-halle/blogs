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
	<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $id; ?>.json?callback=twitterCallback2&amp;count=1"></script>
	<?php
	}
}

/***********************************************************************/
/* CUSTOM FIELDS */
/*
	thumbnail = for top carusel
*/
/***********************************************************************/


function carousel_featured_posts($max_posts=5, $offset=0) {
	if(!function_exists('show_featured_posts'))
		return false;
		
	global $wpdb, $table_prefix;
	$table_name = $table_prefix."features";
	
	$sql = "SELECT * FROM $table_name ORDER BY date DESC LIMIT $offset, $max_posts";
	$posts = $wpdb->get_results($sql);
	
	$html = '';
	$coint_i = 0;
	foreach($posts as $post) {
		$coint_i++;
		$id = $post->id;
		$posts_table = $table_prefix.'posts'; 
		$sql_post = "SELECT * FROM $posts_table where id = $id";
		$rs_post = $wpdb->get_results($sql_post);
		$data = $rs_post[0];
		$post_title = stripslashes($data->post_title);
		$post_title = str_replace('"', '', $post_title);
		$post_content = stripslashes($data->post_content);
		$post_content = str_replace(']]>', ']]&gt;', $post_content);
		$post_content = strip_tags($post_content);
		$permalink = get_permalink($data->ID);
		$post_id = $data->ID;
		$html .= '<div class="board_item">
			<!-- board_item -->
			<p>';
			
		$thumbnail = get_post_meta($post_id, 'thumbnail', true);
		
		if( isset($thumbnail) && !empty($thumbnail) ):
			$html .= '<img src="'.$thumbnail.'" alt="'.$post_title.'" />';
		endif;
		
		$html .= '<strong><a href="'.$permalink.'">'.get_string_limit($post_title,50).'</a></strong> '.get_string_limit($post_content,170).'</p>
			<p class="more"><a href="'.$permalink.'">Readmore</a></p>
			<!-- /board_item -->
		</div>';
	}
	echo $html;
	return $coint_i;
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

?>