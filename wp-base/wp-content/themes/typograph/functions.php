<?php
// Produces a list of pages in the header without whitespace -- er, I mean negative space.
function globalnav() {
	echo "<div id=\"menu\">\n\t\t\t<ul>\n";
	wp_list_pages('title_li=&sort_column=menu_order');
	echo "\t\t\t</ul>\n\t\t</div>\n";
}


// Generates semantic classes for each post DIV element
function typograph_post_class( $print = true ) {
	global $post, $post_alt;

	// hentry for hAtom compliace, gets 'alt' for every other post DIV, describes the post type and p[n]
	$c = array('hentry', "p$post_alt", $post->post_type, $post->post_status);

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
	date_classes(mysql2date('U', $post->post_date), $c);

	// If it's the other to the every, then add 'alt' class
	if ( ++$post_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for post DIV
	$c = join(' ', apply_filters('typograph_post_class', $c));

	// And tada!
	return $print ? print($c) : $c;
}

// Define the num val for 'alt' classes (in post DIV and comment LI)
$post_alt = 1;

// Generates semantic classes for each comment LI element
function typograph_comment_class( $print = true ) {
	global $comment, $post, $comment_alt;

	// Collects the comment type (comment, trackback),
	$c = array($comment->comment_type);

	// Counts trackbacks (t[n]) or comments (c[n])
	if ($comment->comment_type == 'trackback') {
		$c[] = "t$comment_alt";
	} else {
		$c[] = "c$comment_alt";
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
	date_classes(mysql2date('U', $comment->comment_date), $c, 'c-');
	if ( ++$comment_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for comment LI
	$c = join(' ', apply_filters('typograph_comment_class', $c));

	// Tada again!
	return $print ? print($c) : $c;
}

// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function date_classes($t, &$c, $p = '') {
	$t = $t + (get_option('gmt_offset') * 3600);
	$c[] = $p . 'y' . gmdate('Y', $t); // Year
	$c[] = $p . 'm' . gmdate('m', $t); // Month
	$c[] = $p . 'd' . gmdate('d', $t); // Day
	$c[] = $p . 'h' . gmdate('H', $t); // Hour
}

// For category lists on category archives: Returns other categories except the current one (redundant)
function cats_meow($glue) {
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
function tag_ur_it($glue) {
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


// Widget: Search; to match the Sandbox style and replace Widget plugin default
function widget_search($args) {
	extract($args);
	if ( empty($title) )
		$title = __('Search');
?>
			<?php echo $before_widget ?>
				<?php echo $before_title ?><label for="s"><?php echo $title ?></label><?php echo $after_title ?>
				<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="10" tabindex="1" />
						<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find') ?>" tabindex="2" />
					</div>
				</form>
			<?php echo $after_widget ?>

<?php
}

// Widget: Meta; to match the Sandbox style and replace Widget plugin default
function widget_meta($args) {
	extract($args);
	if ( empty($title) )
		$title = __('Meta');
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
function widget_rsslinks($args) {
	extract($args);
	$options = get_option('widget_rsslinks');
	$title = empty($options['title']) ? __('RSS Links') : $options['title'];
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
				<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All posts') ?></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> <?php _e('Comments RSS feed'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All comments') ?></a></li>
			</ul>
		<?php echo $after_widget; ?>
<?php
}

// Widget: RSS links; element controls for customizing text within Widget plugin
function widget_rsslinks_control() {
	$options = $newoptions = get_option('widget_rsslinks');
	if ( $_POST["rsslinks-submit"] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST["rsslinks-title"]));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_rsslinks', $options);
	}
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
?>
			<p><label for="rsslinks-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="rsslinks-title" name="rsslinks-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="rsslinks-submit" name="rsslinks-submit" value="1" />
<?php
}

// Widgets plugin: intializes the plugin after the widgets above have passed snuff
function widgets_init() {
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
	register_sidebar_widget(__('Search'), 'widget_search', null, 'search');
	unregister_widget_control('search');
	register_sidebar_widget(__('Meta'), 'widget_meta', null, 'meta');
	unregister_widget_control('meta');
	register_sidebar_widget(array(__('RSS Links'), 'widgets'), 'widget_rsslinks');
	register_widget_control(array(__('RSS Links'), 'widgets'), 'widget_rsslinks_control', 300, 90);
}

// Translate, if applicable
load_theme_textdomain('sandbox');

// Runs our code at the end to check that everything needed has loaded
add_action('init', 'widgets_init');

// Adds filters so that things run smoothly
add_filter('archive_meta', 'wptexturize');
add_filter('archive_meta', 'convert_smilies');
add_filter('archive_meta', 'convert_chars');
add_filter('archive_meta', 'wpautop');

// Remember: the Sandbox is for play.
?>