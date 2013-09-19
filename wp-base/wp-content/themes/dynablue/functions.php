<?php
if ( function_exists('register_sidebar') )
{
    register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
}

define('FEATURED_SPEED', 5); // seconds
define('FEATURED_POSTS', 5); // copunt

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
		
		$html .= '<strong><a href="'.$permalink.'">'.get_string_limit($post_title,50).'</a></strong> '.get_string_limit($post_content,200).'</p>
			<p class="more"><a href="'.$permalink.'">Readmore</a></p>
			<!-- /board_item -->
		</div>';
	}
	echo $html;
	return $coint_i;
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





/* ======================== ADMIN ========================================= */

// Hook for adding admin menus
add_action('admin_menu', 'theme_ads_add_pages');



// action function for above hook
function theme_ads_add_pages() {
    // Add a new submenu under Options:
    //add_options_page('Theme Ads', 'Theme Ads', 8, 'theme_ads', 'theme_ads_options_page');
	add_menu_page('Theme Options', 'Theme Options', 8, 'functions.php', 'theme_main_options_page');
	add_submenu_page('functions.php', 'Main Settings', 'Main Settings', 8, 'functions.php', 'theme_main_options_page');

}

function theme_main_options_page() {

$theme_ads_page_options = 'theme_twitter_id, theme_google_id';


?>
<div class="wrap">

<form method="post" action="options.php">
<fieldset>
<?php wp_nonce_field('update-options'); ?>
<h2>Theme Main Options</h2>
<br />
<?php if ( $_REQUEST['updated'] ) echo '<div id="message" class="updated fade"><p><strong>Theme Main settings saved.</strong></p></div>'; ?>
<h3>General</h3>
<table>
	<tr>
    	<td><label for="theme_twitter_id">Twitter ID</label></td>
        <td>:</td>
        <td><input type="text" name="theme_twitter_id" id="theme_twitter_id" value="<?php echo get_option('theme_twitter_id'); ?>" size="40" /></td>
    </tr>
</table>
<table>
    <tr>
    	<td>
            <p class="submit">
            <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
            </p>
        </td>
    </tr>
</table>
</fieldset>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="<? echo $theme_ads_page_options; ?>" />

</form>

</div>
<?
}

function theme_twitter_show()
{
	$id = trim(get_option('theme_twitter_id'));
	if(!empty($id))
	{
	?>
	<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
	<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $id; ?>.json?callback=twitterCallback2&count=1"></script>
	<?php
	}
}

function theme_twitter_link_show()
{
	$id = trim(get_option('theme_twitter_id'));
	if(!empty($id))
	{
		return 'http://twitter.com/'.$id;
	}
	else
	{
		return '#';
	}
}



/* +++++++++++++++++++++++++++ ADMIN END ++++++++++++++++++++++++++++++++++++++++++++++++  */
/**
 * HTML comment list class.
 *
 * @package WordPress
 * @uses Walker
 * @since unknown
 */
class Walker_Comment2 extends Walker {
	/**
	 * @see Walker::$tree_type
	 * @since unknown
	 * @var string
	 */
	var $tree_type = 'comment';

	/**
	 * @see Walker::$db_fields
	 * @since unknown
	 * @var array
	 */
	var $db_fields = array ('parent' => 'comment_parent', 'id' => 'comment_ID');

	/**
	 * @see Walker::start_lvl()
	 * @since unknown
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of comment.
	 * @param array $args Uses 'style' argument for type of HTML list.
	 */
	function start_lvl(&$output, $depth, $args) {
		$GLOBALS['comment_depth'] = $depth + 1;

		echo "<div class='children '>\n";
	}

	/**
	 * @see Walker::end_lvl()
	 * @since unknown
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of comment.
	 * @param array $args Will only append content if style argument value is 'ol' or 'ul'.
	 */
	function end_lvl(&$output, $depth, $args) {
		$GLOBALS['comment_depth'] = $depth + 1;

		echo "</div>\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since unknown
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $comment Comment data object.
	 * @param int $depth Depth of comment in reference to parents.
	 * @param array $args
	 */
	function start_el(&$output, $comment, $depth, $args) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;

		if ( !empty($args['callback']) ) {
			call_user_func($args['callback'], $comment, $args, $depth);
			return;
		}

		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);
		
		$tag = 'div';
		$add_below = 'div-comment';
?>
		<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
        
        
        <div class="commentmet_data" id="div-comment-<?php comment_ID() ?>">
        	<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td colspan="2">
						<div class="commentmetadata">
							<span><?php echo get_comment_author_link(); ?></span> said on <?php comment_time('d-m-Y') ?>
						</div>
					</td>
				</tr>
            	<tr>
                	<td width="102">
            			<div class="commentmet_avatar">
							<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, 86 /*$args['avatar_size']*/ ); ?>
            			</div>
                    </td>
                	<td width="83%">
                          <div class="commentmet_text">
                              <?php comment_text() ?>
                              <div class="commentmet_replay"><?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
                          </div>
                    </td>
                </tr>
            </table>
         </div>
<?php
	}

	/**
	 * @see Walker::end_el()
	 * @since unknown
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $comment
	 * @param int $depth Depth of comment.
	 * @param array $args
	 */
	function end_el(&$output, $comment, $depth, $args) {
		if ( !empty($args['end-callback']) ) {
			call_user_func($args['end-callback'], $comment, $args, $depth);
			return;
		}
		echo "</div>\n";
	}

}

/**
 * List comments
 *
 * Used in the comments.php template to list comments for a particular post
 *
 * @since 2.7.0
 * @uses Walker_Comment
 *
 * @param string|array $args Formatting options
 * @param array $comments Optional array of comment objects.  Defaults to $wp_query->comments
 */
function wp_list_comments2($args = array(), $comments = null ) {
	global $wp_query, $comment_alt, $comment_depth, $comment_thread_alt, $overridden_cpage, $in_comment_loop;

	$in_comment_loop = true;

	$comment_alt = $comment_thread_alt = 0;
	$comment_depth = 1;

	$defaults = array('walker' => null, 'max_depth' => '', 'style' => 'div', 'callback' => null, 'end-callback' => null, 'type' => 'all',
		'page' => '', 'per_page' => '', 'avatar_size' => 86, 'reverse_top_level' => null, 'reverse_children' => '');

	$r = wp_parse_args( $args, $defaults );

	// Figure out what comments we'll be looping through ($_comments)
	if ( null !== $comments ) {
		$comments = (array) $comments;
		if ( empty($comments) )
			return;
		if ( 'all' != $r['type'] ) {
			$comments_by_type = &separate_comments($comments);
			if ( empty($comments_by_type[$r['type']]) )
				return;
			$_comments = $comments_by_type[$r['type']];
		} else {
			$_comments = $comments;
		}
	} else {
		if ( empty($wp_query->comments) )
			return;
		if ( 'all' != $r['type'] ) {
			if ( empty($wp_query->comments_by_type) )
				$wp_query->comments_by_type = &separate_comments($wp_query->comments);
			if ( empty($wp_query->comments_by_type[$r['type']]) )
				return;
			$_comments = $wp_query->comments_by_type[$r['type']];
		} else {
			$_comments = $wp_query->comments;
		}
	}

	if ( '' === $r['per_page'] && get_option('page_comments') )
		$r['per_page'] = get_query_var('comments_per_page');

	if ( empty($r['per_page']) ) {
		$r['per_page'] = 0;
		$r['page'] = 0;
	}

	if ( '' === $r['max_depth'] ) {
		if ( get_option('thread_comments') )
			$r['max_depth'] = get_option('thread_comments_depth');
		else
			$r['max_depth'] = -1;
	}

	if ( '' === $r['page'] ) {
		if ( empty($overridden_cpage) ) {
			$r['page'] = get_query_var('cpage');
		} else {
			$threaded = ( -1 == $r['max_depth'] ) ? false : true;
			$r['page'] = ( 'newest' == get_option('default_comments_page') ) ? get_comment_pages_count($_comments, $r['per_page'], $threaded) : 1;
			set_query_var( 'cpage', $r['page'] );
		}
	}
	// Validation check
	$r['page'] = intval($r['page']);
	if ( 0 == $r['page'] && 0 != $r['per_page'] )
		$r['page'] = 1;

	if ( null === $r['reverse_top_level'] )
		$r['reverse_top_level'] = ( 'desc' == get_option('comment_order') ) ? TRUE : FALSE;

	extract( $r, EXTR_SKIP );

	if ( empty($walker) )
		$walker = new Walker_Comment2;

	$walker->paged_walk($_comments, $max_depth, $page, $per_page, $r);
	$wp_query->max_num_comment_pages = $walker->max_pages;

	$in_comment_loop = false;
}


?>