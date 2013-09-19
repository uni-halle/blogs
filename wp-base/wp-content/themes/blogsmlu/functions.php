<?php

//load the theme translations
load_theme_textdomain('blogsmlu', get_template_directory() . '/languages' );

//replace and insert default jQuery
function jquery_from_google_ajax_libs() {
	if(!is_admin()):
  	wp_deregister_script( 'jquery' );
  	wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js', null, '1.6');
  endif;
}
add_filter('init','jquery_from_google_ajax_libs');

function add_jquery_backup_and_no_conflict() { ?>
<script>!window.jQuery && document.write(unescape('%3Cscript src="<?php bloginfo('template_directory'); ?>/style/js/libs/jquery.min.js"%3E%3C/script%3E'))</script>
<script>jQuery.noConflict();</script>
<?php }
add_filter('wp_head', 'add_jquery_backup_and_no_conflict', 9);

//3.0 Automatische Feeds
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'automatic-feed-links' );
}

//2.9 Post Thumbnails
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support('post-thumbnails');
}

//3.0 Menu Management
if (function_exists('add_theme_support')) {
    add_theme_support('menus');
}

function register_my_menus() {
	register_nav_menu( 'header-menu', __( 'Header Menu' ) );
}
add_action( 'init', 'register_my_menus' );

//3.0 Custom Background Support
if (function_exists('add_custom_background')) {
	
	add_custom_background('mlublogs_custom_background');
	
	function mlublogs_custom_background() {
		$background = get_background_image();
	    $color = get_background_color();
	    if ( ! $background && ! $color )
	        return;
	 
	    $style = $color ? "background-color: #$color;" : '';
	 
	    if ( $background ) {
	        $image = " background-image: url('$background');";
	 
	        $repeat = get_theme_mod( 'background_repeat', 'repeat' );
	        if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
	            $repeat = 'repeat';
	        $repeat = " background-repeat: $repeat;";
	 
	        $position = get_theme_mod( 'background_position_x', 'left' );
	        if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
	            $position = 'left';
	        $position = " background-position: top $position;";
	 
	        $attachment = get_theme_mod( 'background_attachment', 'scroll' );
	        if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
	            $attachment = 'scroll';
	        $attachment = " background-attachment: $attachment;";
	 
	        $style .= $image . $repeat . $position . $attachment;
	    }
	?>
	<style type="text/css">
		body { <?php echo trim( $style ); ?> -moz-background-size: auto; -webkit-background-size: auto; -o-background-size: auto; background-size: auto;  }
	</style>
	<?php
	}
	
}

//3.0 Custom Header Image
//this disables text color adjustments
define( 'HEADER_TEXTCOLOR', '' );
define( 'NO_HEADER_TEXT', true );

define( 'HEADER_IMAGE', '%s/style/images/headers/Bonsai.jpg' );

define( 'HEADER_IMAGE_WIDTH', apply_filters( 'blogsmlu_header_image_width', 860 ) );
define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'blogsmlu_header_image_height', 150 ) );
set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

function blogsmlu_header_style() {
	//Check and apply this only if a header image has been set
	if (get_header_image())  {
		?><style type="text/css">
	    	#header { 
	    		background: url(<?php header_image(); ?>);
	    		border-top: 1px solid #fff;
				border-top: 1px solid rgba(255,255,255,.9);
				-webkit-box-shadow: 0 1px 8px rgba(51, 51, 51, .4);
				-moz-box-shadow: 0 1px 8px rgba(51, 51, 51, .4);
				box-shadow: 0 1px 8px rgba(51, 51, 51, .4);
				-webkit-border-top-left-radius: 10px;
				-webkit-border-top-right-radius: 10px;
				-moz-border-radius-topleft: 10px;
				-moz-border-radius-topright: 10px;
				border-top-left-radius: 10px;
				border-top-right-radius: 10px;
	    	}
	
			#blogname {
				background: #fff;
				background: rgba(255,255,255,.7);
				padding: 10px 0px 10px 10px;
				margin-left: -10px;
				text-shadow: 0 1px 0 rgba(255,255,255,.7);
				-webkit-box-shadow: -5px 1px 8px rgba(51, 51, 51, .4);
				-moz-box-shadow: -5px 1px 8px rgba(51, 51, 51, .4);
				box-shadow: -5px 1px 8px rgba(51, 51, 51, .4);
			}
	    </style>
	    <!--[if lt IE 9]>

		   <style type="text/css">
		
		   #blogname {
		   		background:none;
		       	-ms-filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#B2FFFFFF,endColorstr=#B2FFFFFF);
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#B2FFFFFF,endColorstr=#B2FFFFFF);
				zoom: 1;
		    } 
		
		    </style>
		
		<![endif]-->
	<?php }
}

function blogsmlu_admin_header_style() {
	?><style type="text/css">
	#headimg {
		background: url(<?php header_image(); ?>);
		border-top: 1px solid #fff;
		border-top: 1px solid rgba(255,255,255,.9);
		-webkit-box-shadow: 0 1px 8px rgba(51, 51, 51, .4);
		-moz-box-shadow: 0 1px 8px rgba(51, 51, 51, .4);
		box-shadow: 0 1px 8px rgba(51, 51, 51, .4);
		-webkit-border-top-left-radius: 10px;
		-webkit-border-top-right-radius: 10px;
		-moz-border-radius-topleft: 10px;
		-moz-border-radius-topright: 10px;
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
	}
	</style>
<?php }

add_custom_image_header( 'blogsmlu_header_style', 'blogsmlu_admin_header_style');

// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
register_default_headers( array (
	'bonsai' => array (
		'url' => '%s/style/images/headers/Bonsai.jpg',
		'thumbnail_url' => '%s/style/images/headers/Bonsai_Thumb.jpg',
		'description' => __( 'Bonsai', 'blogsmlu' )
	),
	'bonsairetro' => array (
		'url' => '%s/style/images/headers/Bonsai_Retro.jpg',
		'thumbnail_url' => '%s/style/images/headers/Bonsai_Retro_Thumb.jpg',
		'description' => __( 'Bonsai Retro', 'blogsmlu' )
	),
	'connected' => array (
		'url' => '%s/style/images/headers/connected.jpg',
		'thumbnail_url' => '%s/style/images/headers/connected_thumb.jpg',
		'description' => __( 'Connected', 'blogsmlu' )
	),
	'typewriter' => array (
		'url' => '%s/style/images/headers/typewriter.jpg',
		'thumbnail_url' => '%s/style/images/headers/typewriter_thumb.jpg',
		'description' => __( 'Typewriter', 'blogsmlu' )
	),
	'walltype' => array (
		'url' => '%s/style/images/headers/walltype.jpg',
		'thumbnail_url' => '%s/style/images/headers/walltype_thumb.jpg',
		'description' => __( 'Wall Type', 'blogsmlu' )
	),
	'wiese' => array (
		'url' => '%s/style/images/headers/wiese.jpg',
		'thumbnail_url' => '%s/style/images/headers/wiese_thumb.jpg',
		'description' => __( 'Wiese', 'blogsmlu' )
	)
) );

// Add CSS to Visual Editor
if (function_exists('add_editor_style')) {
	add_editor_style('style/css/editor.css');
}

//Widgetize with multiple sidebars
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name'=>'Sidebar',
	    'before_widget' => '<div class="sidelist">',
	    'after_widget' => '</div>',
	));
	register_sidebar(array(
		'name'=>'Footerbar',
	    'before_widget' => '<div class="footerlist">',
	    'after_widget' => '</div>',
	    'before_title'  => '<h4 class="widgettitle">',
    	'after_title'   => '</h4>'
	));
}

//Fetch recent comments
function recent_comments() {
  global $wpdb;
  $sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,30) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 5";
  $comments = $wpdb->get_results($sql);
  $recentComments = $pre_HTML;
  $recentComments .= "\n<ol>";
  foreach ($comments as $comment) {
    $recentComments .= "\n<li class=\"recentcomments\"><a href=\"" . get_permalink($comment->ID)."#comment-" . $comment->comment_ID . "\" title=\"on ".$comment->post_title . "\">" . strip_tags($comment->com_excerpt)."...</a></li>";
  }
  $recentComments .= "\n</ol>";
  $recentComments .= $post_HTML;
  echo $recentComments ;
}

//Ping separation
function list_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment; ?>
       <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>"><?php comment_author_link() ?></li>
<?php
}

// No self-pings
if ( !function_exists('noself_ping') ) {
	function noself_ping(&$links) {
		$home = get_option('home');
		foreach($links as $l => $link)
			if ( 0 === strpos($link, $home) )
				unset($links[$l]);
	}
	add_action( 'pre_ping', 'noself_ping' );
}

//Exclude Pings from comment count
add_filter('get_comments_number', 'comment_count', 0);
function comment_count( $count ) {
        if ( ! is_admin() ) {
                global $id;
                $comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
                return count($comments_by_type['comment']);
        } else {
                return $count;
        }
}

//Wrap ampersands with a special class
function style_ampersands($text) {
	$amp_finder = "/(\s|&nbsp;)(&|&amp;|&\#38;|&#038;)(\s|&nbsp;)/";
    return preg_replace($amp_finder, '\\1<span class="amp">&amp;</span>\\3', $text);
}

add_filter('comment_text', 'style_ampersands', 11);
add_filter('single_post_title', 'style_ampersands', 11);
add_filter('the_title', 'style_ampersands', 11);
add_filter('the_content', 'style_ampersands', 11);
add_filter('the_excerpt', 'style_ampersands', 11);
add_filter('widget_text', 'style_ampersands', 11);


// Show Home Item when wp_nav_menu is used
function mlublogs_addHomeMenuLink($menuItems, $args) {
	if('header-menu' == $args->theme_location)
	{
		if ( is_front_page() )
			$class = 'class="current_page_item"';
		else
			$class = '';	

		$homeMenuItem = '<li ' . $class . '>' .
						$args->before .
						'<a href="' . home_url( '/' ) . '" title="Home">' .
							$args->link_before .
							'Home' .
							$args->link_after .
						'</a>' .
						$args->after .
						'</li>';

		$menuItems = $homeMenuItem . $menuItems;
	}

	return $menuItems;
}
add_filter( 'wp_nav_menu_items', 'mlublogs_addHomeMenuLink', 10, 2 );


//Highlight search terms
function hls_set_query() { 
	$query = attribute_escape(get_search_query());
	
	if(strlen($query) > 0){ 
		echo ' <script type="text/javascript"> var hls_query = "'.$query.'"; </script> ';
	}
} 
add_action('wp_print_scripts', 'hls_set_query');

//Autor Info Box with Shortcode
function authorbox() {
	?>
		<h3 id="author-head">&Uuml;ber <?php the_author_meta('display_name'); ?></h3>
			
			<ul id="authorinfo" class="grey-box">
				
				<li class="avatar"><?php echo get_avatar( get_the_author_meta('email'), 60); ?></li>
				
				<?php if ( get_the_author_meta( 'unirole1' ) ) { ?>
					<li class="unirole1"><?php the_author_meta('unirole1'); ?></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'unirole2' ) ) { ?>
					<li class="unirole2"><?php the_author_meta('unirole2'); ?></li>
				<?php } ?>
				
				<li class="description"><?php the_author_meta( 'description' ); ?></li>
				
				<?php if ( get_the_author_meta( 'user_url' ) ) { ?>
					<li><span>Website:</span> <a class="url" rel="author" href="<?php the_author_meta('user_url'); ?>"><?php the_author_meta('user_url'); ?></a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'twitter' ) ) { ?>
					<li><span>Twitter:</span> <a class="url" rel="author" href="https://twitter.com/<?php the_author_meta('twitter'); ?>"><?php the_author_meta('twitter'); ?></a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'facebook' ) ) { ?>
					<li><span>Facebook:</span> <a class="url" rel="author" href="<?php the_author_meta('facebook'); ?>"><?php the_author_meta('display_name'); ?>'s Profil</a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'studivz' ) ) { ?>
					<li><span>StudiVZ:</span> <a class="url" rel="author" href="<?php the_author_meta('studivz'); ?>"><?php the_author_meta('display_name'); ?>'s Profil</a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'studip' ) ) { ?>
					<li><span>Stud.IP:</span> <a class="url" href="http://studip.uni-halle.de/about.php?username=<?php get_the_author_meta( 'studip' ) ?>" title="<?php the_author_meta('display_name'); ?> auf Stud.IP"><?php the_author_meta('display_name'); ?>'s Homepage</a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'xing' ) ) { ?>
					<li><span>XING:</span> <a class="url" rel="author" href="<?php the_author_meta('xing'); ?>"><?php the_author_meta('display_name'); ?>'s Profil</a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'linkedin' ) ) { ?>
					<li><span>LinkedIn:</span> <a class="url" rel="author" href="<?php the_author_meta('linkedin'); ?>"><?php the_author_meta('display_name'); ?>'s Profil</a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'flickr' ) ) { ?>
					<li><span>Flickr:</span> <a class="url" rel="author" href="<?php the_author_meta('flickr'); ?>"><?php the_author_meta('display_name'); ?>'s Photos</a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'jabber' ) ) { ?>
					<li><span>Jabber/GTalk:</span> <a class="url" href="<?php the_author_meta('jabber'); ?>"><?php the_author_meta('jabber'); ?></a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'aim' ) ) { ?>
					<li><span>AIM:</span> <a class="url" href="aim:addbuddy?screenname=<?php the_author_meta('aim'); ?>"><?php the_author_meta('aim'); ?></a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'yim' ) ) { ?>
					<li><span>Yahoo IM:</span> <a class="url" href="ymsgr:addfriend?<?php the_author_meta('yim'); ?>"><?php the_author_meta('yim'); ?></a></li>
				<?php } ?>
				
				<?php if ( get_the_author_meta( 'icq' ) ) { ?>
					<li><span>ICQ:</span> <a class="url" href="<?php the_author_meta('icq'); ?>"><?php the_author_meta('icq'); ?></a></li>
				<?php } ?>
			</ul>
<?php
}
add_shortcode('autorinfo', 'authorbox');

?>