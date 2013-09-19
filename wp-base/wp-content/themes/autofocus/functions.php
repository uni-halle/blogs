<?php

// Generates semantic classes for BODY element
function sandbox_body_class( $print = true ) {
	global $wp_query, $current_user;
	
	// It's surely a WordPress blog, right?
	$c = array('wordpress');

	// Applies the time- and date-based classes (below) to BODY element
	sandbox_date_classes(time(), $c);

	// Generic semantic classes for what type of content is displayed
	is_home()       ? $c[] = 'home'       : null;
	is_archive()    ? $c[] = 'archive'    : null;
	is_date()       ? $c[] = 'date'       : null;
	is_search()     ? $c[] = 'search'     : null;
	is_paged()      ? $c[] = 'paged'      : null;
	is_attachment() ? $c[] = 'attachment' : null;
	is_404()        ? $c[] = 'four04'     : null; // CSS does not allow a digit as first character

	// Special classes for BODY element when a single post
	if ( is_single() ) {
		$postID = $wp_query->post->ID;
		the_post();

		// Adds 'single' class and class with the post ID
		$c[] = 'single postid-' . $postID;

		// Adds classes for the month, day, and hour when the post was published
		if ( isset($wp_query->post->post_date) )
			sandbox_date_classes(mysql2date('U', $wp_query->post->post_date), $c, 's-');

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$c[] = 's-category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag )
				$c[] = 's-tag-' . $tag->slug;

		// Adds MIME-specific classes for attachments
		if ( is_attachment() ) {
			$the_mime = get_post_mime_type();
			$boring_stuff = array("application/", "image/", "text/", "audio/", "video/", "music/");
				$c[] = 'attachment-' . str_replace($boring_stuff, "", "$the_mime");
		}

		// Adds author class for the post author
		$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_login()));
		rewind_posts();
	}

	// Author name classes for BODY on author archives
	else if ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}

	// Category name classes for BODY on category archvies
	else if ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->slug;
	}

	// Tag name classes for BODY on tag archives
	else if ( is_tag() ) {
		$tags = $wp_query->get_queried_object();
		$c[] = 'tag';
		$c[] = 'tag-' . $tags->slug; // Does not work; however I try to return the tag I get a false. Grrr.
	}

	// Page author for BODY on 'pages'
	else if ( is_page() ) {
		$pageID = $wp_query->post->ID;
		the_post();
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));
		rewind_posts();
	}

	// For when a visitor is logged in while browsing
	if ( $current_user->ID )
		$c[] = 'loggedin';

	// Paged classes; for 'page X' classes of index, single, etc.
	if ( ( ( $page = $wp_query->get("paged") ) || ( $page = $wp_query->get("page") ) ) && $page > 1 ) {
		$c[] = 'paged-'.$page.'';
		if ( is_single() ) {
			$c[] = 'single-paged-'.$page.'';
		} else if ( is_page() ) {
			$c[] = 'page-paged-'.$page.'';
		} else if ( is_category() ) {
			$c[] = 'category-paged-'.$page.'';
		} else if ( is_tag() ) {
			$c[] = 'tag-paged-'.$page.'';
		} else if ( is_date() ) {
			$c[] = 'date-paged-'.$page.'';
		} else if ( is_author() ) {
			$c[] = 'author-paged-'.$page.'';
		} else if ( is_search() ) {
			$c[] = 'search-paged-'.$page.'';
		}
	}

	// Separates classes with a single space, collates classes for BODY
	$c = join(' ', apply_filters('body_class',  $c));

	// And tada!
	return $print ? print($c) : $c;
}

// Generates semantic classes for each post DIV element
function sandbox_post_class( $print = true ) {
	global $post, $sandbox_post_alt;

	// hentry for hAtom compliace, gets 'alt' for every other post DIV, describes the post type and p[n]
	$c = array('hentry', "p$sandbox_post_alt", $post->post_type, $post->post_status);

	// Author for the post queried
	$c[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));

	// Category for the post queried
	foreach ( (array) get_the_category() as $cat )
		$c[] = 'category-' . $cat->slug;

	// Tags for the post queried
	foreach ( (array) get_the_tags() as $tag )
		$c[] = 'tag-' . $tag->slug;

	// For password-protected posts
	if ( $post->post_password )
		$c[] = 'protected';

	// Applies the time- and date-based classes (below) to post DIV
	sandbox_date_classes(mysql2date('U', $post->post_date), $c);

	// If it's the other to the every, then add 'alt' class
	if ( ++$sandbox_post_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for post DIV
	$c = join(' ', apply_filters('post_class', $c));

	// And tada!
	return $print ? print($c) : $c;
}

// Define the num val for 'alt' classes (in post DIV and comment LI)
$sandbox_post_alt = 1;

// Generates semantic classes for each comment LI element
function sandbox_comment_class( $print = true ) {
	global $comment, $post, $sandbox_comment_alt;

	// Collects the comment type (comment, trackback),
	$c = array($comment->comment_type);

	// Counts trackbacks (t[n]) or comments (c[n])
	if ($comment->comment_type == 'trackback') {
		$c[] = "t$sandbox_comment_alt";
	} else {
		$c[] = "c$sandbox_comment_alt";
	}

	// If the comment author has an id (registered), then print the log in name
	if ( $comment->user_id > 0 ) {
		$user = get_userdata($comment->user_id);

		// For all registered users, 'byuser'; to specificy the registered user, 'commentauthor+[log in name]'
		$c[] = "byuser comment-author-" . sanitize_title_with_dashes(strtolower($user->user_login));
		// For comment authors who are the author of the post
		if ( $comment->user_id === $post->post_author )
			$c[] = 'bypostauthor';
	}

	// If it's the other to the every, then add 'alt' class; collects time- and date-based classes
	sandbox_date_classes(mysql2date('U', $comment->comment_date), $c, 'c-');
	if ( ++$sandbox_comment_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for comment LI
	$c = join(' ', apply_filters('comment_class', $c));

	// Tada again!
	return $print ? print($c) : $c;
}

// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function sandbox_date_classes($t, &$c, $p = '') {
	$t = $t + (get_option('gmt_offset') * 3600);
	$c[] = $p . 'y' . gmdate('Y', $t); // Year
	$c[] = $p . 'm' . gmdate('m', $t); // Month
	$c[] = $p . 'd' . gmdate('d', $t); // Day
	$c[] = $p . 'h' . gmdate('H', $t); // Hour
}

// For category lists on category archives: Returns other categories except the current one (redundant)
function sandbox_cats_meow($glue) {
	$current_cat = single_cat_title('', false);
	$separator = "\n";
	$cats = explode($separator, get_the_category_list($separator));

	foreach ( $cats as $i => $str ) {
		if ( strstr($str, ">$current_cat<") ) {
			unset($cats[$i]);
			break;
		}
	}

	if ( empty($cats) )
		return false;

	return trim(join($glue, $cats));
}

// For tag lists on tag archives: Returns other tags except the current one (redundant)
function sandbox_tag_ur_it($glue) {
	$current_tag = single_tag_title('', '',  false);
	$separator = "\n";
	$tags = explode($separator, get_the_tag_list("", "$separator", ""));

	foreach ( $tags as $i => $str ) {
		if ( strstr($str, ">$current_tag<") ) {
			unset($tags[$i]);
			break;
		}
	}

	if ( empty($tags) )
		return false;

	return trim(join($glue, $tags));
}

// Nice Tag Cloud
function widget_nice_tagcloud($args) {
    extract($args);
?>
        <?php echo $before_widget; ?>
            <?php echo $before_title
                . 'Tag Cloud'
                . $after_title; ?>

<?php if ( function_exists('wp_tag_cloud') ) : ?>
					<p>
						<?php wp_tag_cloud('orderby=count&order=DESC'); ?>
					</p>
<?php endif; ?>
				
        <?php echo $after_widget; ?>
<?php
}
register_sidebar_widget('Nice Tag Cloud',
    'widget_nice_tagcloud');


// Widget: Search; to match the Sandbox style and replace Widget plugin default
function widget_sandbox_search($args) {
	extract($args);
	if ( empty($title) )
		$title = __('Search', 'sandbox');
?>
			<?php echo $before_widget ?>
				<?php echo $before_title ?><label for="s"><?php echo $title ?></label><?php echo $after_title ?>
				<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="20" tabindex="1" />
						<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Search', 'sandbox') ?>" tabindex="2" />
					</div>
				</form>
			<?php echo $after_widget ?>

<?php
}

// Widget: Meta; to match the Sandbox style and replace Widget plugin default
function widget_sandbox_meta($args) {
	extract($args);
	if ( empty($title) )
		$title = __('Meta', 'sandbox');
?>
			<?php echo $before_widget; ?>
				<?php echo $before_title . $title . $after_title; ?>
				<ul>
					<?php wp_register() ?>
					<li><?php wp_loginout() ?></li>
					<?php wp_meta() ?>
				</ul>
			<?php echo $after_widget; ?>
<?php
}

// Widget: RSS links; to match the Sandbox style
function widget_sandbox_rsslinks($args) {
	extract($args);
	$options = get_option('widget_sandbox_rsslinks');
	$title = empty($options['title']) ? __('RSS Links', 'sandbox') : $options['title'];
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
				<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All posts', 'sandbox') ?></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> <?php _e('Comments RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All comments', 'sandbox') ?></a></li>
			</ul>
		<?php echo $after_widget; ?>
<?php
}

// Widget: RSS links; element controls for customizing text within Widget plugin
function widget_sandbox_rsslinks_control() {
	$options = $newoptions = get_option('widget_sandbox_rsslinks');
	if ( $_POST["rsslinks-submit"] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST["rsslinks-title"]));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_sandbox_rsslinks', $options);
	}
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
?>
			<p><label for="rsslinks-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="rsslinks-title" name="rsslinks-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="rsslinks-submit" name="rsslinks-submit" value="1" />
<?php
}

// Widgets plugin: intializes the plugin after the widgets above have passed snuff
function sandbox_widgets_init() {
	if ( !function_exists('register_sidebars') )
		return;

	// Uses H3-level headings with all widgets to match Sandbox style
	$p = array(
		'before_title' => "<h3 class='widgettitle'>",
		'after_title' => "</h3>\n",
	);

	// Table for how many? Two? This way, please.
	register_sidebars(2, $p);

	// Finished intializing Widgets plugin, now let's load the Sandbox default widgets
	register_sidebar_widget(__('Search', 'sandbox'), 'widget_sandbox_search', null, 'search');
	unregister_widget_control('search');
	register_sidebar_widget(__('Meta', 'sandbox'), 'widget_sandbox_meta', null, 'meta');
	unregister_widget_control('meta');
	register_sidebar_widget(array(__('RSS Links', 'sandbox'), 'widgets'), 'widget_sandbox_rsslinks');
	register_widget_control(array(__('RSS Links', 'sandbox'), 'widgets'), 'widget_sandbox_rsslinks_control', 300, 90);
}

// Translate, if applicable
load_theme_textdomain('sandbox');

// Runs our code at the end to check that everything needed has loaded
add_action('init', 'sandbox_widgets_init');

// Adds filters so that things run smoothly
add_filter('archive_meta', 'wptexturize');
add_filter('archive_meta', 'convert_smilies');
add_filter('archive_meta', 'convert_chars');
add_filter('archive_meta', 'wpautop');

// Remember: the Sandbox is for play.
?>
<?php

// Thanks very much to Thin & Light (http://thinlight.org/) for this custom function!
function tl_excerpt($text, $excerpt_length = 25) {
	$text = str_replace(']]>', ']]&gt;', $text);
	$text = strip_tags($text);
	$text = preg_replace("/\[.*?]/", "", $text);
	$words = explode(' ', $text, $excerpt_length + 1);
	if (count($words) > $excerpt_length) {
		array_pop($words);
		array_push($words, '...');
		$text = implode(' ', $words);
	}
	
	return apply_filters('the_excerpt', $text);
}

function tl_post_excerpt($post) {
	$excerpt = ($post->post_excerpt == '') ? (tl_excerpt($post->post_content))
			: (apply_filters('the_excerpt', $post->post_excerpt));
	return $excerpt;
}

function previous_post_excerpt($in_same_cat = false, $excluded_categories = '') {
	if ( is_attachment() )
		$post = &get_post($GLOBALS['post']->post_parent);
	else
		$post = get_previous_post($in_same_cat, $excluded_categories);

	if ( !$post )
		return;
	$post = &get_post($post->ID);
	echo tl_post_excerpt($post);
}

function next_post_excerpt($in_same_cat = false, $excluded_categories = '') {
	$post = get_next_post($in_same_cat, $excluded_categories);

	if ( !$post )
		return;
	$post = &get_post($post->ID);
	echo tl_post_excerpt($post);
}

// Much thanks goes out to Rob Bredow (http://www.185vfx.com) for this AWESOME plugin!
function remove_first_image ($content) {
   if (!is_page() && !is_feed() && !is_feed()) {
        $content = preg_replace('/^<p><img(.*?)>/i', "<p><!-- Image removed by Remove First Image Plugin -->", $content, 1);
        $content = preg_replace('/^<img(.*?)>/i', "<!-- Image removed by Remove First Image Plugin -->", $content, 1);
        $content = preg_replace('/^<p><a(.*?)><img(.*?)><\/a>/i', '<p><!-- Link and image removed by Remove First Image Plugin -->', $content);
		$content = preg_replace('/^<a(.*?)><img(.*?)><\/a>/i', '<p><!-- Link and image removed by Remove First Image Plugin -->', $content);
   } return $content;
}
add_filter('the_content', 'remove_first_image');

//	Custom IE Style Sheet
function childtheme_iefix() { ?>
    <!--[if lte IE 8]>
		<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('stylesheet_directory') ?>/css/ie.css" />
		<script src="http://ie8-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
    <![endif]-->
    <!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('stylesheet_directory') ?>/css/ie6.css" />
		<script src="http://ie6-js.googlecode.com/svn/version/2.0(beta3)/IE6.js" type="text/javascript"></script>
    <![endif]-->
<?php }
add_action('wp_head', 'childtheme_iefix');

// Custom the_excerpt formatting / hides [caption] short codes
function the_autofocus_excerpt($text) { // Fakes an excerpt if needed
	global $post;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text, "<style>");
		$text = preg_replace("/\[.*?]/", "", $text);
		$excerpt_length = 25;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '&hellip;');
			$text = implode(' ', $words);
		}
	}
	return $text;
}

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'the_autofocus_excerpt');

// Produces a list of pages in the header without whitespace -- er, I mean negative space.
function sandbox_globalnav() {
    echo '<div id="menu"><ul><li class="page_item"><a href="'. get_settings('home') .'/" title="'. get_bloginfo('name') .'" rel="home">Home</a></li>';
    $menu = wp_list_pages('title_li=&sort_column=menu_order&echo=0'); // Params for the page list in header.php
    echo str_replace(array("\r", "\n", "\t"), '', $menu);
    echo '<li class="page_item"><a href="'. get_bloginfo_rss('rss2_url') .'">RSS</a></li></ul></div>';
}

// Post Attachment image function. Image URL for CSS Background. 
function the_post_image_url($size=large) {
	
	global $post;
	$linkedimgurl = get_post_meta ($post->ID, 'image_url', true);

	if ( $images = get_children(array(
		'post_parent' => get_the_ID(),
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
	{
		foreach( $images as $image ) {
			$attachmenturl=wp_get_attachment_image_src($image->ID, $size);
			$attachmenturl=$attachmenturl[0];
			$attachmentimage=wp_get_attachment_image( $image->ID, $size );

			echo ''.$attachmenturl.'';
		}
		
	} elseif ( $linkedimgurl ) {

		echo $linkedimgurl;

	} elseif ( $linkedimgurl && $images = get_children(array(
		'post_parent' => get_the_ID(),
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
	{
		foreach( $images as $image ) {
			$attachmenturl=wp_get_attachment_image_src($image->ID, $size);
			$attachmenturl=$attachmenturl[0];
			$attachmentimage=wp_get_attachment_image( $image->ID, $size );

			echo ''.$attachmenturl.'';
		}
		
	} else {
		echo '' . get_bloginfo ( 'stylesheet_directory' ) . '/img/no-attachment.gif';
	}
}

// Post Attachment image function. Direct link to file. 
function the_post_image($size=thumbnail) {
	
	global $post;
	$linkedimgtag = get_post_meta ($post->ID, 'image_tag', true);

	if ( $images = get_children(array(
		'post_parent' => get_the_ID(),
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
		{
		foreach( $images as $image ) {
			$attachmenturl=wp_get_attachment_url($image->ID);
			$attachmentimage=wp_get_attachment_image( $image->ID, $size );

			echo ''.$attachmentimage.'';
		}
		
	} elseif ( $linkedimgtag ) {

		echo $linkedimgtag;

	} elseif ( $linkedimgtag && $images = get_children(array(
		'post_parent' => get_the_ID(),
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
		{
		foreach( $images as $image ) {
			$attachmenturl=wp_get_attachment_url($image->ID);
			$attachmentimage=wp_get_attachment_image( $image->ID, $size );

			echo ''.$attachmentimage.'';
		}
		
	} else {
		echo '<img src="' . get_bloginfo ( 'stylesheet_directory' ) . '/img/no-attachment-large.gif" />';
	}
}

//Setup Images for Attachment functions 
function image_setup($postid) {
	global $post;
	$post = get_post($postid);

	// get url
	if ( !preg_match('/<img ([^>]*)src=(\"|\')(.+?)(\2)([^>\/]*)\/*>/', $post->post_content, $matches) ) {
		return false;
	}

	// url setup
	$post->image_url = $matches[3];
	if ( !$post->image_url = preg_replace('/\?w\=[0-9]+/','', $post->image_url) )
		return false;

	$post->image_url = clean_url( $post->image_url, 'raw' );
	
	delete_post_meta($post->ID, 'image_url');
	delete_post_meta($post->ID, 'image_tag');

	add_post_meta($post->ID, 'image_url', $post->image_url);
	add_post_meta($post->ID, 'image_tag', '<img src="'.$post->image_url.'" />');

}

add_action('publish_post', image_setup);
add_action('publish_page', image_setup);

// Post Attachment image function for Attachment Pages. 
function the_attachment_image($size=large) {
	$attachmenturl=wp_get_attachment_url($image->ID);
	$attachmentimage=wp_get_attachment_image( $image->ID, $size );

	echo ''.$attachmentimage.'';
}

// Post Attachment image function for Attachment Pages. 
function link_to_attachment($size=large) {
	if ( $attachs = get_children(array(
		'post_parent' => get_the_ID(),
		'post_type' => 'attachment',
		'numberposts' => 1,
		'post_mime_type' => 'image',)))
	{
		foreach( $attachs as $attach ) {
			$attachmentlink=get_attachment_link($attach->ID);

			echo '<a href="' . $attachmentlink . '">View EXIF Data</a>';
		}
	}
}

// Grab EXIF Data from Attachments http://www.walkernews.net/2009/04/13/turn-on-wordpress-feature-to-display-photo-exif-data-and-iptc-information/
function grab_exif_data() {
	$imgmeta = wp_get_attachment_metadata($id);

	/* 
	// Convert the shutter speed retrieve from database to fraction DOES NOT WORK ON THE LIVE SERVER FOR SOME REASON >:-|
	if ((1 / $imgmeta['image_meta']['shutter_speed']) > 1) {
		if ((number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1)) == 1.3
		or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.5
		or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.6
		or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 2.5) {
			$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1, '.', '') . " second";
		} else {
			$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 0, '.', '') . " second";
		}

	} else {
		$pshutter = $imgmeta['image_meta']['shutter_speed'] . " seconds";
	}*/

	// Start to display EXIF and IPTC data of digital photograph
	echo "<ul><li<span class=\"exif-title\">Date Taken:</span> " . date("d-M-Y H:i:s", $imgmeta['image_meta']['created_timestamp'])."</li>";
	echo "<li<span class=\"exif-title\">Copyright:</span> " . $imgmeta['image_meta']['copyright']."</li>";
	echo "<li<span class=\"exif-title\">Credit:</span> " . $imgmeta['image_meta']['credit']."</li>";
	echo "<li<span class=\"exif-title\">Title:</span> " . $imgmeta['image_meta']['title']."</li>";
	echo "<li<span class=\"exif-title\">Caption:</span> " . $imgmeta['image_meta']['caption']."</li>";
	echo "<li<span class=\"exif-title\">Camera:</span> " . $imgmeta['image_meta']['camera']."</li>";
	echo "<li<span class=\"exif-title\">Focal Length:</span> " . $imgmeta['image_meta']['focal_length']."mm</li>";
	echo "<li<span class=\"exif-title\">Aperture:</span> f/" . $imgmeta['image_meta']['aperture']."</li>";
	echo "<li<span class=\"exif-title\">ISO:</span> " . $imgmeta['image_meta']['iso']."</li>";
	// echo "<li<span class=\"exif-title\">Shutter Speed:</span> " . $pshutter . "</li></ul>";
}
// add_action('exif_data','grab_exif_data');

// Removes 'p' tags from excerpts. 
remove_filter('the_excerpt', 'wpautop');

// Fixes Next and Previous ATTACHMENT links
function ps_previous_image_link( $f ) {
    $i = ps_adjacent_image_link( true );
	if ( $i ) {
		echo str_replace("%link", $i, $f);
	}
}

// Next ATTACHMENT link
function ps_next_image_link( $f ) {
    $i = ps_adjacent_image_link( false );
	if ( $i ) {
		echo str_replace("%link", $i, $f);
	}
}

// Previous ATTACHMENT link
function ps_adjacent_image_link($prev = true) {
    global $post;
    $post = get_post($post);
    $attachments = array_values(get_children(Array('post_parent' => $post->post_parent,
	      'post_type' => 'attachment',
	      'post_mime_type' => 'image',
	      'orderby' => 'menu_order ASC, ID ASC')));

    foreach ( $attachments as $k => $attachment ) {
        if ( $attachment->ID == $post->ID ) {
            break;
		}
	}

    $k = $prev ? $k - 1 : $k + 1;

    if ( isset($attachments[$k]) ) {
        return wp_get_attachment_link($attachments[$k]->ID, 'thumbnail', true);
	}
	else {
		return false;
	}
}

// Overides default FULL size images size
$GLOBALS['content_width'] = 800;

add_filter('the_content_rss', 'do_shortcode');

// Custom callback to list comments in the your-theme style
function custom_comments($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;
  ?>
  	<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
  		<div class="comment-author vcard"><?php commenter_link() ?></div>
  		<div class="comment-meta"><?php printf(__('Posted %1$s at %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Permalink to this comment">#</a>', 'your-theme'),
  					get_comment_date(),
  					get_comment_time(),
  					'#comment-' . get_comment_ID() );
  					edit_comment_link(__('Edit', 'your-theme'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
  <?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'your-theme') ?>
          <div class="comment-content">
      		<?php comment_text() ?>
  		</div>
		<?php // echo the comment reply link
			if($args['type'] == 'all' || get_comment_type() == 'comment') :
				comment_reply_link(array_merge($args, array(
					'reply_text' => __('Reply','your-theme'), 
					'login_text' => __('Log in to reply.','your-theme'),
					'depth' => $depth,
					'before' => '<div class="comment-reply-link">', 
					'after' => '</div>'
				)));
			endif;
		?>
<?php } // end custom_comments

// Custom callback to list pings
function custom_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
        ?>
    		<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
    			<div class="comment-author"><?php printf(__('By %1$s on %2$s at %3$s', 'your-theme'),
    					get_comment_author_link(),
    					get_comment_date(),
    					get_comment_time() );
    					edit_comment_link(__('Edit', 'your-theme'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
    <?php if ($comment->comment_approved == '0') _e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n', 'your-theme') ?>
            <div class="comment-content">
    			<?php comment_text() ?>
			</div>
<?php } // end custom_pings

// Produces an avatar image with the hCard-compliant photo class
function commenter_link() {
	$commenter = get_comment_author_link();
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
	}
	$avatar_email = get_comment_author_email();
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 50 ) );
	echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
} // end commenter_link

?>