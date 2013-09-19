<?php

$themename = "RedTime";
$adminmenuname = "RedTime Options";
define('SHORTNAME','askopt');

$include_dir = 'includes';
$themeoptions_dir = $include_dir.'/theme-options';

// Functions
require_once($include_dir.'/fn-general.php');
require_once($themeoptions_dir.'/setup.php');

if ( function_exists('register_sidebar') )
{
    register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));
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
							<span class="commentmetadata_title"><?php echo get_comment_author_link(); ?></span> <span>said on <?php comment_time('d-m-Y') ?></span>
						</div>
					</td>
				</tr>
            	<tr>
                	<td width="102">
            			<div class="commentmet_avatar">
							<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, 86 /*$args['avatar_size']*/ ); ?>
            			</div>
                    </td>
                	<td width="78%">
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