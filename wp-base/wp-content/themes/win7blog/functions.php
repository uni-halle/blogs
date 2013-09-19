<?php

//get sub string
function win7blog_substr($text, $length)  {
	$j = 0;
	for($i=0; $i<$length; $i++)
	{
		$chr = substr($text, $i, 1);
		if (ord($chr) > 127) { //SBC case
			$j++;
		}
	}
	while($j % 3 !== 0) { $j--;$length--;}
	$str = substr($text, 0, $length);
	return $str;
}

function win7blog_clear_content($text,   $length)  {
	$text = strip_tags($text);
	return ' - '.win7blog_substr($text, $length).'...';
}

// Produces threaded/nested comments structure, requires wordpress 2.7&higher
function win7blog_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
   <div id="div-comment-<?php comment_ID() ?>">
      <?php $add_below = 'div-comment'; ?>
		<div class="comment-author vcard"><?php win7blog_commenter_link() ?>
			<span class="datetime"><?php printf('   @ %1$s %2$s', get_comment_date('Y-n-j'), get_comment_time('H:i') );?></span>
			<span class="reply"><?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'reply_text' => __('Reply', 'win7blog'), 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></span>
		</div>
		<?php comment_text() ?>
  </div>
<?php
}

function win7blog_end_comment() {
		echo '</li>';
}

//Determine if it is an original post
function win7blog_is_original() {
	$tags = get_the_tags();
	if($tags) {
		foreach($tags as $tag) {
			if(strpos( $tag->name, "Original") !== false ) return true;
		}
	}

	$cats = get_the_category();
	if($cats) {
		foreach($cats as $cat) {
			if(strpos($cat->name, "Original") !== false ) return true;
		}
	}
	return false;
}

// Make index of sticky posts
function win7blog_sticky_index() {
	global $sticky_index;

	$c = '';

	if (is_sticky()) {
		$c = $c . ++$sticky_index;
		return $c;
	}
	else
		return '-not';
}

function win7blog_title_margin($index)
{
	if(is_numeric($index))
	{
		$index = 43 * ($index - 1);
		$c = 'margin-top:'.$index.'px';
		return $c;
	}
}

// Produces a list of pages in the header without whitespace
function win7blog_globalnav() {
	global $win7blog_options;
	$categories = str_replace( array( "\r", "\n", "\t" ), '', wp_list_categories('title_li=&sort_column=menu_order&echo=0&depth=1') );
	$pages = str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages('title_li=&sort_column=menu_order&echo=0&depth=1') );
	if( strstr($categories, "No categories") ) $categories = "";
	if( $win7blog_options["menubar_content"] == "Category" ) {
		$menu = $categories;
	} else if( $win7blog_options["menubar_content"] == "Page" ){
		$menu = $pages;
	} else {
		$menu = $categories . $pages;
	}
	$menu = '<ul id="menu"><li><a href= "'. get_bloginfo('url') . '/">' . __('Home', 'win7blog') . '</a></li>' . $menu . '</ul>';
	echo apply_filters( 'globalnav_menu', $menu ); // Filter to override default globalnav: globalnav_menu
}

// Produces an avatar image with the hCard-compliant photo class
function win7blog_commenter_link() {
	global $comment_ids;
	$commenter = get_comment_author_link();
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
	}
	$avatar_email = get_comment_author_email();
	$avatar_size = apply_filters( 'avatar_size', '40' ); // Available filter: avatar_size
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, $avatar_size ) );
	if ( $comment_floor = $comment_ids[get_comment_id()] )
		$comment_floor = '#'.$comment_floor;
	echo $avatar . ' <span class="comment_index">' .$comment_floor. '</span><span class="comment_author">' . $commenter . '</span>';
}

// Widget: Win7blog Recent Comments
function widget_win7blog_recent_comments($args) {
	global $win7blog_options;
	extract($args);
	$options = get_option('widget_win7blog_recent_comments');
	$title = empty($options['title']) ? __( 'Recent Comments', 'win7blog' ) : attribute_escape($options['title']);
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
				<?php global $wpdb, $comments, $comment;
					// Mini-function from blog.txt, edit by kami
					if( $win7blog_options["hide_author_reply"] == 'on' ) { $author_filter = "AND comment_author <> '". get_the_author()."'"; }
					$comments = $wpdb->get_results("SELECT comment_author, comment_author_url, comment_ID, comment_post_ID, SUBSTRING(comment_content,1,65) AS comment_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = ''".$author_filter." ORDER BY comment_date_gmt DESC LIMIT 10"); ?>
				<ul id="recent_comments"><?php
				if ( $comments ) : foreach ($comments as $comment) :
				echo  '<li class="recentcomments">' . sprintf('%1$s',
					'<a href="'. get_permalink($comment->comment_post_ID) . '#comment-' . $comment->comment_ID . '" title="' . $comment->comment_author . ' on ' . get_the_title($comment->comment_post_ID) . '">' . $comment->comment_excerpt . '</a>' ) . '</li>';
				endforeach; endif; ?></ul>
		<?php echo $after_widget; ?>
<?php
}

// Widget: Win7blog Recent Comments; element controls for customizing text within Widget plugin
function widget_win7blog_recent_comments_control() {
	$options = $newoptions = get_option('widget_win7blog_recent_comments');
	if ( $_POST['recentcomments-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['recentcomments-title'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_win7blog_recent_comments', $options );
	}
	$title = attribute_escape($options['title']);
?>
	<p><label for="recentcomments-title"><?php _e( 'Title:', 'win7blog' ) ?><input class="widefat" id="recentcomments-title" name="recentcomments-title" type="text" value="<?php echo $title; ?>" /></label></p>
	<input type="hidden" id="recentcomments-submit" name="recentcomments-submit" value="1" />
<?php
}

// Widgets plugin: intializes the plugin after the widgets above have passed snuff
function win7blog_widgets_init() {
	if ( !function_exists('register_sidebars') )
		return;

	$p = array(
		'before_widget'  => '<li id="%1$s" class="widget %2$s">',
		'after_widget'   => '</li>',
		'before_title'   => '<h3 class="widgettitle">',
		'after_title'    => '</h3>'
	);

	// Table for how many? Two? This way, please.
	register_sidebars( 2, $p );
	unregister_sidebar_widget('search'); // We're being Win7blog-specific; remove WP default

	// Win7blog RecentComments widget
	$widget_ops = array(
		'classname'    =>  'widget_recent_comments',
		'description'  =>  __( "A neat recent comments", "win7blog" )
	);
	wp_register_sidebar_widget( 'recent_comments', __( 'Recent Comments (Win7blog)', 'win7blog' ), 'widget_win7blog_recent_comments', $widget_ops );
	wp_register_widget_control( 'recent_comments', __( 'Recent Comments (Win7blog)', 'win7blog' ), 'widget_win7blog_recent_comments_control' );
}

// Translate, if applicable
load_theme_textdomain('win7blog', get_template_directory() . '/languages');

// Runs our code at the end to check that everything needed has loaded
add_action( 'init', 'win7blog_widgets_init' );

// Adds filters for the description/meta content in archives.php
add_filter( 'archive_meta', 'wptexturize' );
add_filter( 'archive_meta', 'convert_smilies' );
add_filter( 'archive_meta', 'convert_chars' );
add_filter( 'archive_meta', 'wpautop' );

$themename = "Win7blog";
$default_options = array(
	"menubar_content" => 'Category',
	"stickypost_style" => 'Simple',
	"hide_author_reply" => 'on',
	"show_child_floor" => 'off',
	"homepage_desc" => "",
	"homepage_keywords" => "",
	"hide_post_tags" => 'on',
);
$win7blog_options = get_option('win7blog');
if($win7blog_options !== false) {
	$win7blog_options += $default_options;
} else {
	$win7blog_options = $default_options;
}

function mytheme_add_admin() {
    global $themename, $win7blog_options, $default_options;
    if ( $_GET['page'] == basename(__FILE__) ) {
        if ( 'save' == $_REQUEST['action'] ) {
                foreach ($win7blog_options as $key => $value) {
					$win7blog_options[$key] = $_REQUEST[$key];
				}
                update_option('win7blog', $win7blog_options);
                header("Location: themes.php?page=functions.php&saved=true");
                die;
        } else if( 'reset' == $_REQUEST['action'] ) {
            update_option('win7blog', $default_options);
			header("Location: themes.php?page=functions.php&reset=true");
            die;
        }
    }
    add_theme_page($themename." Settings", __('Win7blog Options','win7blog'), 'edit_themes', basename(__FILE__), 'mytheme_admin');
}

function mytheme_admin() {
    global $themename, $win7blog_options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';

	include('settings.php');
}

function mytheme_admin_head() { ?>
<link href="<?php bloginfo('template_directory'); ?>/settings.css" rel="stylesheet" type="text/css" />
<?php }

add_action('admin_head', 'mytheme_admin_head');
add_action('admin_menu', 'mytheme_add_admin');
?>