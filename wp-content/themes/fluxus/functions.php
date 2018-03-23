<?php
/*----------------------------------------------------------------------------------------------------*\
	G E N E R A L L Y
\*----------------------------------------------------------------------------------------------------*/



// hide blockquote-pages from menu
/*
function nav_menu_hiddenpages( $args ) {
	$hiddenpages = array();
	foreach(get_pages(array("public" => "true")) as $page){
		if(pathinfo(get_page_template_slug( $page->ID ))['filename'] == 'page-blockquote'){
			$hiddenpages[] = $page->ID;
		}
	}
	$args['exclude'] = implode(',', $hiddenpages);
	return $args;
}
add_filter( 'wp_nav_menu_args', 'nav_menu_hiddenpages' );
*/



// german php output
setlocale(LC_TIME, "de_DE.UTF-8");

// security
add_filter('login_errors', create_function('$a', "return null;"));

// auto-discovery feed in header
add_theme_support('automatic-feed-links');

// wordpress generates the title automatically
add_theme_support( 'title-tag' );

// post-thumbnail support
add_theme_support('post-thumbnails');



//load scripts
add_action( 'wp_enqueue_scripts', 'fluxus_enqueue' );
function fluxus_enqueue() {
	////////// fluxus.js on header because of imagelightbox
	wp_enqueue_script('fluxus', get_template_directory_uri().'/js/fluxus.js', array('jquery'), 'r3', false);
	wp_enqueue_script('masonry');
}



// activate or deactivate built-in features
//get_template_part("includes/postlist/postlist", "");
get_template_part("includes/fluxusnotes/fluxusnotes", "");
get_template_part("includes/contentgallery/contentgallery", "");
get_template_part("includes/imagelightbox/imagelightbox", "");
get_template_part("includes/accordion/accordion", "");
get_template_part("includes/childlist/childlist", "");
get_template_part("includes/breadcrump/breadcrump", "");
get_template_part("includes/hideemail/hideemail", "");



// register widget-areas
add_action( 'widgets_init', 'fluxus_widgets_init' );
function fluxus_widgets_init() {
	register_sidebar( array( 
		'name' => 'Sidebar oben', 
		'id' => 'sidebar-top', 
		'description' => 'Rutscht bei kleinem Bildschirm nach oben.', 
		'before_widget' => '<aside class="sidebar__widget widget typo %2$s" id="%1$s">', 
		'after_widget' => "</aside>", 
		'before_title' => "<h4 class='widget__title'>", 
		'after_title' => "</h4>",) );
	register_sidebar( array( 
		'name' => 'Sidebar unten', 
		'id' => 'sidebar-bottom', 
		'description' => 'Rutscht bei kleinem Bildschirm nach unten.', 
		'before_widget' => '<aside class="sidebar__widget widget typo %2$s" id="%1$s">', 
		'after_widget' => "</aside>", 
		'before_title' => "<h4 class='widget__title'>", 
		'after_title' => "</h4>",) );
	register_sidebar( array( 
		'name' => 'Widgets im Footer', 
		'id' => 'widgetsfooter', 
		'description' => 'Bereich im Footer', 
		'before_widget' => '<aside class="footer__widget widget typo %2$s" id="%1$s">', 
		'after_widget' => "</aside>", 
		'before_title' => "<h4 class='widget__title'>", 
		'after_title' => "</h4>",) );
}





/*----------------------------------------------------------------------------------------------------*\
	F L U X U S - F U N C T I O N S
\*----------------------------------------------------------------------------------------------------*/



//function to check if post is completly shown in post-list
function is_complete( $post ) {
	if( has_excerpt( $post->ID ) || preg_match("/<!--more-->/i", $post->post_content)) { 
		return false; 
	} else { 
		return true; 
		}
}



// add the page-template-name and front-page-marker as class to menu item
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($classes) {
	$front_page_class = "";
	foreach($classes as $class) {
		if(preg_match("/page-item-/", $class)) {
			$page_id = preg_replace("/page-item-/", "", $class);
			$page_template = basename(get_page_template_slug( $page_id ), ".php");
		}
	}
	if($page_template) { $classes[] = "template-" . $page_template; }
	if(get_option( 'page_on_front' ) && get_option( 'page_on_front' ) == $page_id) { $classes[] = "front-page"; }
	return $classes;
}



/*----------------------------------------------------------------------------------------------------*\
	C O N T E N T
\*----------------------------------------------------------------------------------------------------*/



// clear default wordpress gallery stuff
add_filter( 'use_default_gallery_style', '__return_false' );



// prevent scrolling when using the more-link
function remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}
add_filter( 'the_content_more_link', 'remove_more_link_scroll' );



// mark paragraphs containing images
/*
add_filter('the_content', 'add_class_with_image');
function add_class_with_image($content){
	$content = preg_replace('/<p(.*<img.*\/>.*<\/p>)/iU', '<p class="with-image"\1', $content);
	return $content;
}
*/
/*
add_filter('the_content', 'add_class_with_image');
function add_class_with_image($content){
	$content = preg_replace('/(<a.*<img.*\/>.*<\/a>/iU', '<a class="with-image"\1', $content);
	return $content;
}
*/



function imgstart( $atts ) {
	return "<div class='imgblock'>";
}
add_shortcode( 'imgstart', 'imgstart' );

function imgend( $atts ) {
	return "</div>";
}
add_shortcode( 'imgend', 'imgend' );



// add image-format-class for inline content images
function fluxus_inlineimage_class($class, $id, $align, $size) {
	$thumbnailsource = wp_get_attachment_image_src($id, $size);
	if($thumbnailsource[1] > $thumbnailsource[2]) { $class .= ' qf'; } 
		elseif($thumbnailsource[1] < $thumbnailsource[2]) { $class .= ' hf'; }
		else { $class .= ' qu'; }
	return $class;
}
add_filter('get_image_tag_class', 'fluxus_inlineimage_class', 0, 4);



function add_imagetext($content) {

	$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
	$dom = new DOMDocument();
	@$dom->loadHTML($content);

	foreach ($dom->getElementsByTagName('img') as $node) {
		$classes = $node->getAttribute('class');
		$classesArray = explode( ' ', $classes );
		foreach($classesArray as $key => $class){
			if(strpos($class, 'wp-image-') !== false) {
				$id = str_replace('wp-image-', '', $class);
				if(get_post_meta( $id, '_imagetext', true )){
					$node->setAttribute('imagetext', wpautop(get_post_meta( $id, '_imagetext', true )) );
				}
			}
		}
	}
	$newHtml = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $dom->saveHTML()));
	return $newHtml;
}
add_filter('the_content', 'add_imagetext');





// append imagetext-div for imagelightbox for inline images !!! – those images need a caption !!!
add_filter( 'img_caption_shortcode', 'tune_imgbox', 10, 3 );
function tune_imgbox( $output, $attr, $content ) {
	// default 
	$defaults = array( 'id' => '', 'align' => 'alignnone', 'width' => '', 'caption' => '' );
	// merge default and user attributes
	$attr = shortcode_atts( $defaults, $attr );
	// write attributes – no width!
	$attributes = ( !empty( $attr['id'] ) ? 'id="' . esc_attr( $attr['id'] ) . '" ' : '' );
	$attributes .= ( !empty( $attr['width'] ) ? 'style="width: ' . esc_attr( $attr['width'] ) . 'px" ' : '' );
	$attributes .= 'class="wp-caption ';
	if( preg_match("/size-full/", do_shortcode( $content ) ) ) { $attributes .= 'size-full '; }
	elseif( preg_match("/size-large/", do_shortcode( $content ) ) )  { $attributes .= 'size-large '; }
	elseif( preg_match("/size-medium/", do_shortcode( $content ) ) )  { $attributes .= 'size-medium '; }
	elseif( preg_match("/size-thumbnail/", do_shortcode( $content ) ) )  { $attributes .= 'size-thumbnail '; }
	$attributes .= esc_attr( $attr['align'] ) . '"';
	// ausgabe
	
	$att_id = explode("_", $attr['id'])[1];
	
	$output = '<div ' . $attributes .'>';
	$output .= do_shortcode( $content );
	//$output .= '<p class="wp-caption-text">' . $attr['caption'] . '</p>';
	$output .= "<div class='imagelightbox__imgtext' style='display: none;'>" . wpautop(get_post_meta( $att_id, '_imagetext', true )) . "</div>";
	$output .= '</div>';
	return $output;
}





/*----------------------------------------------------------------------------------------------------*\
	B A C K E N D
\*----------------------------------------------------------------------------------------------------*/



/*
// remove thumbnail metabox on pages
add_action( 'init', 'pagethumbnail_remove' );
function pagethumbnail_remove() {
	remove_post_type_support( 'page', 'thumbnail' );
}
*/



// connect media-files with posts or pages
add_filter( 'media_row_actions', 'fluxus_add_attach', 10, 3 );
function fluxus_add_attach( $actions, $post, $detached ) {
	if ( current_user_can( 'edit_post', $post->ID ) ) {
			$actions['attach'] = '<a href="#the-list" onclick="findPosts.open( \'media[]\',\''.$post->ID.'\' );return false;" class="hide-if-no-js">'.__( 'Attach' ).'</a>';
			return $actions;
			}
		}



// custom styles
// insert 'styleselect' into the $buttons array
add_filter( 'mce_buttons_2', 'my_mce_buttons' );
function my_mce_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
// define custom formats
add_filter( 'tiny_mce_before_init', 'my_mce_formats' ); 
function my_mce_formats( $init_array ) {
	$style_formats = array(
		array(
			'title' => 'Titelabsatz Schrägstrich',
			'selector' => 'p, h1, h2, h3, h4, h5, h6',
			'classes' => 'titleblock'
		),
		array(
			'title' => 'Titelabsatz groß',
			'selector' => 'p, h1, h2, h3, h4, h5, h6',
			'classes' => 'titleblock--big'
		),
		array(
			'title' => 'kleiner Text',
			'inline' => 'small'
		),
		array(
			'title' => 'kein Textumfluss',
			'selector' => 'p, h1, h2, h3, h4, h5, h6',
			'classes' => 'clear-both',
			'styles' => array(
				'clear' => 'both'
			)
		)
	);
	$init_array['style_formats'] = json_encode( $style_formats );
	return $init_array;
}



// show template column on pages-list
add_filter( 'manage_pages_columns', 'page_column_views' );
function page_column_views( $columns ) {
	unset( $columns['author'] );
	unset( $columns['comments'] );
	unset( $columns['date'] );
	$columns['page-layout'] = __('Template');
	$columns['author'] = __('Author');
	$columns['date'] = __('Date');
	return $columns;
}
add_action( 'manage_pages_custom_column', 'page_custom_column_views', 5, 2 );
function page_custom_column_views( $column_name, $id ) {
	if ( $column_name === 'page-layout' ) {
		 $set_template = get_post_meta( get_the_ID(), '_wp_page_template', true );
		 $templates = get_page_templates();
		 ksort( $templates );
		 foreach ( array_keys( $templates ) as $template ) :
			if ( $set_template == $templates[$template] ) echo $template;
		 endforeach;
	}
}



function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');



?>