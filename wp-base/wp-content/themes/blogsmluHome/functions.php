<?php
/**
 * @package WordPress
 * @subpackage blogsmluHome
 */

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

// Add CSS to Visual Editor
if (function_exists('add_editor_style')) {
	add_editor_style('../blogsmlu/style/css/editor.css');
}

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

// Add the Contact Form 7 scripts on selected pages and remove style sheet
//http://coding.cglounge.com/2009/06/optimize-style-contact-form-7-wordpress/
function add_wpcf7_scripts() {
	if ( is_page('kontakt') )
		wpcf7_enqueue_scripts();
}
if ( ! is_admin() && WPCF7_LOAD_JS )
	remove_action( 'init', 'wpcf7_enqueue_scripts' );
add_action( 'wp', 'add_wpcf7_scripts' );

function remove_wpcf7_stylesheet() {
	remove_action( 'wp_head', 'wpcf7_wp_head' );
}
add_action( 'init' , 'remove_wpcf7_stylesheet' );

//custom excerpt
function trim_excerpt($num) {
	$limit = $num+1;
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt)."...";
	echo $excerpt;
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

//Use first images of posts as thumbnails
function post_images($num = 1, $width = null, $height = null, $class = '', $displayLink = true) {
	global $more;
	$more = 1;
	if($width) { $size = ' width="'.$width.'px"'; }
	if($height) { $size .= ' height="'.$height.'px"'; }
	if($class) { $class = ' class="'.$class.'"'; }
	if($displayLink != false) { $link = '<a href="'.get_permalink().'">'; $linkend = '</a>'; }
	$content = get_the_content();
	$content = apply_filters('the_content', $content);
	$count = substr_count($content, '<img');
	$start = 0;
	for($i=1;$i<=$count;$i++) {
		$imgBeg = strpos($content, '<img', $start);
		$post = substr($content, $imgBeg);
		$imgEnd = strpos($post, '>');
		$postOutput = substr($post, 0, $imgEnd+1);
		$replace = array('/width="[^"]*" /','/height="[^"]*" /','/class="alignleft" /');
		$postOutput = preg_replace($replace, '',$postOutput);
		$image[$i] = $postOutput;
		$start=$imgBeg+$imgEnd+1;
	}
	if($num == 'all') {
		$x = count($image);
		for($i = 1;$i<=$x; $i++) {
			if(stristr($image[$i],'<img')) { 
			$theImage = str_replace('<img', '<img'.$size.$class, $image[$i]);
			echo $link.$theImage.$linkend;	
			}
		}
	} else {
		if(stristr($image[$num],'<img')) { 
			$theImage = str_replace('<img', '<img'.$size.$class, $image[$num]);
			echo $link.$theImage.$linkend;
		}
	}
	$more = 0;
}

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