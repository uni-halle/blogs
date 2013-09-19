<?php
$art_config = parse_ini_file(TEMPLATEPATH."/config.ini", true);
$menu_source_options = array('Pages' => 'Pages', 'Categories' => 'Categories');
$sidebars_style_options = array('Block' => 'Block Style', 'Post' => 'Post Style', 'Simple' => 'Simple Text');
$options = array (
  array(
	'name' => __('Page', THEME_NS),
	'type' => 'heading'
  ),
  array(
	'id'   =>   'art_page_comments_allow',
    'name' =>   __('Comments Allow', THEME_NS),
    'std'  =>   art_ini_option('page.comments_allow'),
    'desc' =>   __('Yes', THEME_NS),
    'type' =>   'checkbox'
  ),
  array(  
    'name'   =>   __('Menu', THEME_NS),
    'type' =>   'heading'
  ),
  array(  
    'id'   =>   'art_menu_showHome',
    'name' =>   __('Show Home Item', THEME_NS),
    'std'  =>   art_ini_option('menu.showHome'),
    'desc' =>   __('Yes', THEME_NS),
    'type' =>   'checkbox'
  ),
  array(  
    'id'   =>   'art_menu_homeCaption',
    'name' =>   __('Home Item Caption', THEME_NS),
    'std'  =>   art_ini_option('menu.homeCaption'),
    'type' =>   'text'
  ),
  array(  
    'id'   =>   'art_menu_source',
    'name' =>   __('Default Horizontal Menu Source', THEME_NS),
    'std'  =>   art_ini_option('menu.source'),
    'type' =>   'select',
    'options' => $menu_source_options,
    'desc' =>   __('Displayed when Appearance > Menu > Primary menu is not set', THEME_NS),
  ),
  array(  
    'id'   =>   'art_vmenu_source',
    'name' =>   __('Default Vertical Menu Source', THEME_NS),
    'std'  =>   art_ini_option('vmenu.source'),
    'type' =>   'select',
    'options' => $menu_source_options,
    'desc' =>   __('Displayed when Appearance > Menu > Secondary menu is not set', THEME_NS),
  ),
  array(  
    'name'   =>   __('Post Thumbnails', THEME_NS),
    'type' =>   'heading'
  ),
  array(  
    'id'   =>   'art_metadata_thumbnail_auto',
    'name' =>   __('Use Auto Thumbnails', THEME_NS),
    'std'  =>   art_ini_option('metadata.thumbnail_auto'),
    'desc' =>   __('Generate post thumbnails automatically (use the first image from the post gallery)', THEME_NS),
    'type' =>   'checkbox'
  ),
  array(  
    'id'   =>   'art_metadata_thumbnail_width',
    'name' =>   __('Thumbnail Width', THEME_NS),
    'std'  =>   art_ini_option('metadata.thumbnail_width'),
    'desc' =>   __('(px)', THEME_NS),
    'type' =>   'numeric'
  ),
  array(  
    'id'   =>   'art_metadata_thumbnail_height',
    'name' =>   __('Thumbnail Height', THEME_NS),
    'std'  =>   art_ini_option('metadata.thumbnail_height'),
    'desc' =>   __('(px)', THEME_NS),
    'type' =>   'numeric'
  ),
  array(  
    'name'   =>   __('Post Excerpts', THEME_NS),
    'type' =>   'heading'
  ),
  array(  
    'id'   =>   'art_metadata_excerpt_auto',
    'name' =>   __('Use Auto Excerpts', THEME_NS),
    'std'  =>   art_ini_option('metadata.excerpt_auto'),
    'desc' =>   __('Generate post excerpts automatically (When neither more-tag nor post excerpt is used)', THEME_NS),
    'type' =>   'checkbox'
  ),
  array(  
    'id'   =>   'art_metadata_excerpt_words',
    'name' =>   __('Excerpt Length', THEME_NS),
    'std'  =>   art_ini_option('metadata.excerpt_words'),
    'desc' =>   __('(words)', THEME_NS),
    'type' =>   'numeric'
  ),
  array(  
    'id'   =>   'art_metadata_excerpt_min_remainder',
    'name' =>   __('Excerpt Balance', THEME_NS),
    'std'  =>   art_ini_option('metadata.excerpt_min_remainder'),
    'desc' =>   __('(words)', THEME_NS),
    'type' =>   'numeric'
  ),
  array(  
    'id'   =>   'art_metadata_excerpt_use_tag_filter',
    'name' =>   __('Apply Excerpt Tag Filter', THEME_NS),
    'std'  =>   art_ini_option('metadata.excerpt_use_tag_filter'),
    'desc' =>   __('Yes', THEME_NS),
    'type' =>   'checkbox'
  ),
  array(  
    'id'   =>   'art_metadata_excerpt_allowed_tags',
    'name' =>   __('Allowed Excerpt Tags', THEME_NS),
    'std'  =>   art_ini_option('metadata.excerpt_allowed_tags'),
    'desc' =>   __('Used when "Apply Excerpt Tag Filter" is enabled', THEME_NS),
    'type' =>   'widetext'
  ),
  array(  
    'name'   =>   __('Default Sidebar Style', THEME_NS),
    'type' =>   'heading'
  ),
  array(  
    'id'   =>   'art_sidebars_style_default',
    'name' =>   __('Primary Sidebar', THEME_NS),
    'std'  =>   art_ini_option('sidebars_style.default'),
    'type' =>   'select',
    'options' => $sidebars_style_options
  ),
  array(  
    'id'   =>   'art_sidebars_style_secondary',
    'name' =>   __('Secondary Sidebar', THEME_NS),
    'std'  =>   art_ini_option('sidebars_style.secondary'),
    'type' =>   'select',
    'options' => $sidebars_style_options
  ),
  array(  
    'id'   =>   'art_sidebars_style_top',
    'name' =>   __('Top Sidebars', THEME_NS),
    'std'  =>   art_ini_option('sidebars_style.top'),
    'type' =>   'select',
    'options' => $sidebars_style_options
  ),
  array(  
    'id'   =>   'art_sidebars_style_bottom',
    'name' =>   __('Bottom Sidebars', THEME_NS),
    'std'  =>   art_ini_option('sidebars_style.bottom'),
    'type' =>   'select',
    'options' => $sidebars_style_options
  ),
  array(  
    'id'   =>   'art_sidebars_style_footer',
    'name' =>   __('Footer Sidebars', THEME_NS),
    'std'  =>   art_ini_option('sidebars_style.footer'),
    'type' =>   'select',
    'options' => $sidebars_style_options
  ),
  array(  
    'name'   =>   __('Footer', THEME_NS),
    'type' =>   'heading'
  ),
  array(  
    'id'   =>   'art_footer_content',
    'name' =>   __('Footer Content', THEME_NS),
    'desc' =>   sprintf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>', THEME_NS), 'a, abbr, acronym, em, b, i, strike, strong, span'),
    'std'  =>  	str_replace('&quot;', '"', art_ini_option('footer.content')),
    'type' =>   'textarea'
  ),
  array(  
    'id'   =>   'art_footer_rss_show',
    'name' =>   __('Show RSS Icon', THEME_NS),
    'std'  =>   art_ini_option('footer.rss_show'),
    'desc' =>   __('Yes', THEME_NS),
    'type' =>   'checkbox'
  )
);

define('WP_VERSION', $wp_version);

remove_action('wp_head', 'wp_generator');

wp_enqueue_script('jquery');

if (is_singular() && comments_open() && (get_option('thread_comments') == 1)) {
 wp_enqueue_script('comment-reply'); 
}

define('THEME_NS', 'kubrick');
if (class_exists('xili_language')) {
  define('THEME_TEXTDOMAIN',THEME_NS);
  define('THEME_LANGS_FOLDER','/lang');
} else {
    load_theme_textdomain(THEME_NS, TEMPLATEPATH . '/lang');
}

require_once(TEMPLATEPATH . '/core/parser.php');
require_once(TEMPLATEPATH . '/core/navigation.php');
require_once(TEMPLATEPATH . '/core/sidebars.php');
require_once(TEMPLATEPATH . '/core/widgets.php');

function art_option($name) {
	global $options;
	$key = 'art_'.str_replace('.', '_', $name);
	$result = get_option($key);
  
	if (false === $result) {
		foreach ($options as $value) {
			if ($value['id'] == $key && isset($value['std'])) {
				return $value['std'];
			}
		}
	}

	return art_ini_option($name);
}

function art_ini_option($name){
  global $art_config;
  $separator = '.';
  $name = trim($name);
  if (strpos($name, $separator) === false) return false;
  $path = explode($separator, $name);
  $location = $path[0];
  if (isset($art_config[$location])){
    $group = $art_config[$location];
    $key = $path[1];
    if (isset($group[$key])){
      return $group[$key];
    }
  }
  return false;
}

$art_current_page_template = 'page'; 
function art_page_template($templateName = null){
    global $art_current_page_template;
    if ($templateName !== null) {
        $art_current_page_template = $templateName;
    }
    return $art_current_page_template;
}

$art_template_variables = null;
function art_page_variables($variables = null){
    global $art_template_variables;
    if ($art_template_variables == null){
      $art_template_variables = array(
        'template_url'     =>   get_bloginfo('template_url') . '/',
        'logo_url'         =>   get_option('home'),
        'logo_name'        =>   get_bloginfo('name'),
        'logo_description' =>   get_bloginfo('description'),
        'menu_items'       =>   art_get_menu_auto('primary-menu', art_option('menu.source'), art_option('menu.showSubitems')),
        'sidebar1'         =>   art_get_sidebar('default'),
        'sidebarTop'       =>   art_get_sidebar('top'),
        'sidebarBottom'    =>   art_get_sidebar('bottom'),
        'sidebar2'         =>   art_get_sidebar('secondary'),
        'sidebarFooter'    =>   art_get_sidebar('footer'),
        'footerRSS'        =>   art_get_footer_rss(),
        'footerText'       =>   art_get_footer_text()
        );
    }
    if (is_array($variables)) {
      $art_template_variables = array_merge($art_template_variables, $variables);
    }
    return $art_template_variables;
}

function art_get_footer_text() {
	$footer_content = art_option('footer.content');
	$footer_content = str_replace('%YEAR%', date('Y'), $footer_content);
	$footer_content = str_replace('\r\n', "\r\n", $footer_content);
	return $footer_content;
}

function art_get_footer_rss(){
  $result = '';
  if(art_option('footer.rss_show')){
    $result = art_parse(art_option('footer.rss_link'), array(
      'rss_url'   =>   get_bloginfo('rss2_url'), 
      'rss_title' =>   sprintf(__('%s RSS Feed', THEME_NS), get_bloginfo('name'))
      ));
  }
  return $result;
}

function art_get_post_thumbnail($post_id = false){
	global $post, $id;
  $post_id = (int)$post_id;
  if (!$post_id) $post_id = $id;
  $is_post_list = !is_single() && !is_page();
  $width = art_option('metadata.thumbnail_width');
  $height = art_option('metadata.thumbnail_height');
  $size = array($width, $height);
  if (!$is_post_list) {
    $size = 'medium';
  }
	$result = '';
	$title = get_the_title();
	if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
		ob_start();
		the_post_thumbnail($size, array('class' => 'alignleft', 'alt' => '', 'title' => $title));
		$result = ob_get_clean();
	} else {
		$postimage = get_post_meta($post->ID, 'post-image', true);
		if ($postimage) {
			$result = '<img src="'.$postimage.'" alt="" width="'.$width.'" height="'.$height.'" title="'.$title.'" class="wp-post-image alignleft" />';
		} else if (art_option('metadata.thumbnail_auto') && $is_post_list) {
            $attachments = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));
            if($attachments) {
              $attachment = array_shift($attachments);
              $img = wp_get_attachment_image_src($attachment->ID, $size);
              if (isset($img[0])) {
                $result = '<img src="'.$img[0].'" alt="" width="'.$img[1].'" height="'.$img[2].'" title="'.$title.'" class="wp-post-image alignleft" />';
              }
            }
        } 
    }  
	if($result !== ''){
		$result = '<a href="'.get_permalink($post->ID).'" title="'.$title.'">'.$result.'</a>';
	}
	return $result;
}

function art_get_the_content($more_link_text = null, $stripteaser = 0) {
	$content = get_the_content($more_link_text, $stripteaser);
	$content = apply_filters('the_content', $content);
	return $content;
}

function art_get_post_content() {
  global $post;
  ob_start();
  if(is_single() || is_page()) {
    echo art_get_the_content(__('Read the rest of this entry &raquo;', THEME_NS)); 
    wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); 
	} else {
    echo art_get_the_excerpt(__('Read the rest of this entry &raquo;', THEME_NS), 
      get_permalink($post->ID), 
      art_option('metadata.excerpt_words'), 
      art_option('metadata.excerpt_use_tag_filter') ? explode(',',art_option('metadata.excerpt_allowed_tags')) : null, 
      art_option('metadata.excerpt_min_remainder'),  
      art_option('metadata.excerpt_auto'));
	}
	return ob_get_clean();
}

function art_get_the_excerpt($read_more_tag, $perma_link_to = '', $all_words = 100,  $allowed_tags = null, $min_remainder = 5, $auto = false) {
  global $post, $id;
  $more_token = '%%art-more%%';
  $show_more_tag = false;
  if (function_exists('post_password_required') && post_password_required($post)){
    return get_the_excerpt();
  }
  if (has_excerpt($id)) {
      $the_contents = get_the_excerpt();
  $show_more_tag = strlen($post->post_content) > 0;
  } else {
    $the_contents = art_get_the_content($more_token);
    if($the_contents != '') {
      if ($allowed_tags !== null) {
        $allowed_tags = '<' .implode('><',$allowed_tags).'>';
        $the_contents = strip_tags($the_contents, $allowed_tags);
      }
      $the_contents = strip_shortcodes($the_contents);
      if (strpos($the_contents, $more_token) !== false) {
        return str_replace($more_token, $read_more_tag, $the_contents);
      }
      if($auto && is_numeric($all_words)) {
        $token = "%art_tag_token%";
        $content_parts = explode($token, str_replace(array('<', '>'), array($token.'<', '>'.$token), $the_contents));
        $content = array();
        $word_count = 0;
        foreach($content_parts as $part)
        {
          if (strpos($part, '<') !== false || strpos($part, '>') !== false){
            $content[] = array('type'=>'tag', 'content'=>$part);
          } else {
             $all_chunks = preg_split('/([\s]+)/', $part, -1, PREG_SPLIT_DELIM_CAPTURE);
             foreach($all_chunks as $chunk) {
                if('' != trim($chunk)) {
                  $content[] = array('type'=>'word', 'content'=>$chunk);
                  $word_count += 1;
                } elseif($chunk != '') {
                  $content[] = array('type'=>'space', 'content'=>$chunk);
                }
             }
          }
        
        }
        if(($all_words < $word_count) && ($all_words + $min_remainder) <= $word_count) {
          $show_more_tag = true;
          $current_count = 0;
          $the_contents = '';
          foreach($content as $node) {
            
            if($node['type'] == 'word') {
              $current_count += 1;
            } 
            $the_contents .= $node['content'];
            if ($current_count == $all_words){
              break;
            }
          }
        }  
      }
    }
  }
  $the_contents = force_balance_tags($the_contents);
  $the_contents = str_replace('< ![CDATA', '<![CDATA', $the_contents);

  if ($show_more_tag) {
    $the_contents = $the_contents.' <a class="more-link" href="'.$perma_link_to.'">'.$read_more_tag.'</a>';
  }
  return $the_contents;
}

function art_get_post_title() {
    global $post;
	$post_title = get_the_title();
    $art_page_title_show = get_post_meta($post->ID, '_art_page_title_show', true);
    if ($art_page_title_show == 'false' || $post_title == ''){
        return '';
    }
    return art_parse_template("post_title", array(
      'post_link'       =>   get_permalink($post->ID),
	    'post_link_title' =>   sprintf(__('Permanent Link to %s', THEME_NS), the_title_attribute('echo=0')),
	    'post_title'      =>   get_the_title(),
	    'template_url'    =>   get_bloginfo('template_url')
	    ));
}

function art_get_post_icon($name){
    return art_parse(art_option('metadata.'.$name), array('template_url' => get_bloginfo('template_url')));
}

if (!function_exists('get_the_date')) {
	function get_the_date($format = 'F jS, Y') {
		return get_the_time(__($format, THEME_NS));
	}
}

function art_get_post_metadata($name) {
    global $post;
    $list = art_option('metadata.'.$name);
    $title = ($name == 'header' && art_option('metadata.title'));
    if (!$title && $list == "") return;
    $list_array =  explode(",", $list);
    $result = array();
    for($i = 0; $i < count($list_array); $i++){
        $icon = $list_array[$i];
        switch($icon){
            case 'date':
                if(is_page()) break;
                $result[] = art_get_post_icon($icon) . get_the_date();
            break;
            case 'author':
                 if(is_page()) break;
                 ob_start();
                 the_author_posts_link();
                 $result[] = art_get_post_icon($icon) . __('Author', THEME_NS) .' '. ob_get_clean();
            break;
            case 'category':
                if(is_page()) break;
                 $result[] = art_get_post_icon($icon) .sprintf(__('Posted in %s', THEME_NS), get_the_category_list(', '));
            break;
            case 'tag':
                if(is_page() || !get_the_tags()) break;
                ob_start();
                the_tags(__('Tags:', THEME_NS) . ' ', ', ', ' ');
                $result[] = art_get_post_icon($icon) . ob_get_clean();
            break;
            case 'comments':
            if(is_page() || is_single()) break;
                ob_start();
                comments_popup_link(__('No Comments &#187;', THEME_NS), __('1 Comment &#187;', THEME_NS), __('% Comments &#187;', THEME_NS), '', __('Comments Closed', THEME_NS) );
                $result[] = art_get_post_icon($icon) . ob_get_clean();
            break;
            case 'edit':
                if (!current_user_can('edit_post', $post->ID)) break;
                ob_start();
                 edit_post_link(__('Edit', THEME_NS), '');
                $result[] = art_get_post_icon($icon) . ob_get_clean();
            break;
        }
    }
    $post_title = art_get_post_title();
    if ( ($title && strlen($post_title) > 0) || count($result) > 0 )
        return art_parse_template("post_metadata".$name, array(
        'post_title'         =>   $post_title,
        'post'.$name.'icons' =>   implode(art_option('metadata.separator'), $result)));
    return  '';
}

function art_post(){
  the_post(); 
  $class = function_exists('get_post_class') ? implode(' ', get_post_class()) : '';
	$id = get_the_ID();
	if($id != ''){
    $id = 'post-' . $id;
	}
	art_post_box('', art_get_post_content(), $id, $class, array(
    'post_title'          =>   art_option('metadata.title') ? '' : art_get_post_title(),
	  'post_thumbnail'      =>   art_get_post_thumbnail(),
		'post_metadataheader' =>   art_get_post_metadata('header'),
		'post_metadatafooter' =>   art_get_post_metadata('footer')
	));
}

function art_post_box($title, $content, $id = '', $class = '', $args = array()){
  if ($title != "") {
		$title = '<h2 class="art-postheader">'. $title . '</h2>';
	}
	if (art_option('metadata.title')) {
		$content = $title . $content;
		$title = '';
	}
	if ($class != '') {
    $class = ' ' .$class;
	}
	if($id != ''){
    $id = 'id="' . $id. '"';
	}
	echo art_parse_template("post", array_merge(array(
		'post_class'          =>   $class,
		'post_id'             =>   $id,
		'post_thumbnail'      =>   '',
		'post_title'          =>   $title,
		'post_metadataheader' =>   '',
		'post_content'        =>   $content,
		'post_metadatafooter' =>   ''), $args));
}

function art_not_found_msg($caption = null, $content = null){
  if ($caption === null){
    $caption = __('Not Found', THEME_NS);
  }
  if($content === null){
    $content = '<p class="center">' .  __('Sorry, but you are looking for something that isn&#8217;t here.', THEME_NS) . '</p>'
        .  "\r\n" . art_get_search();
  }
	art_post_box($caption, $content);
}

function art_get_block($title, $content, $id = '', $class = '' , $name = "block"){
  if (str_replace(array('&nbsp;', '', '\n', '\r', '\t'), '', $title) != ''){
    $title = art_parse_template($name . '_header', array('caption' => $title));
  }
  if ($id != ''){
    $id = 'id="' . $id . '"';
  }
  return art_parse_template($name, array(
    'id'   => $id,
    'class'   => $class,
    'header' => $title,
    'content' => $content,
  ));
}

function art_get_search() {
    return art_parse_template("search", 
        array(
            'url'    =>   get_bloginfo('url'),
            'button' =>   __('Search', THEME_NS),
            'query'  =>   get_search_query()
        ));
}

function art_page_navi($title = '', $comment = false, $description = '') {
    $prev_link = null;
    $next_link = null;
    if($comment){
        $prev_link = get_previous_comments_link(__('Newer Entries &raquo;', THEME_NS));
        $next_link = get_next_comments_link(__('&laquo; Older Entries', THEME_NS));
    } elseif (is_single() || is_page()) {
        $next_link = get_previous_post_link('&laquo; %link');
        $prev_link = get_next_post_link('%link &raquo;');
    } else {
        $prev_link = get_previous_posts_link(__('Newer Entries &raquo;', THEME_NS));
        $next_link = get_next_posts_link(__('&laquo; Older Entries', THEME_NS));
    }
    
    $content = '';
    if ($prev_link || $next_link) {
        $content = art_parse_template("pagination", 
            array(
                'next_link' =>  $next_link,
                'prev_link' => $prev_link
            ));
    }
    if (!$content && !$title) return;
    art_post_box($title, $description . $content);
}

if (!function_exists('get_previous_comments_link')) {
	function get_previous_comments_link($label)
	{
		ob_start();
		previous_comments_link($label);
		return ob_get_clean();
	}
}

if (!function_exists('get_next_comments_link')) {
	function get_next_comments_link($label)
	{
		ob_start();
		next_comments_link($label);
		return ob_get_clean();
	}
}

if (!function_exists('get_previous_posts_link')) {
	function get_previous_posts_link($label)
	{
		ob_start();
		previous_posts_link($label);
		return ob_get_clean();
	}
}

if (!function_exists('get_next_posts_link')) {
	function get_next_posts_link($label)
	{
		ob_start();
		next_posts_link($label);
		return ob_get_clean();
	}
}

if (!function_exists('get_previous_post_link')) {
	function get_previous_post_link($label)
	{
		ob_start();
		previous_post_link($label);
		return ob_get_clean();
	}
}

if (!function_exists('get_next_post_link')) {
	function get_next_post_link($label)
	{
		ob_start();
		next_post_link($label);
		return ob_get_clean();
	}
}

if (!function_exists('get_the_author_meta')) {
	function get_the_author_meta($field = '', $user_id = false) {
		if (!user_id) {
			global $authordata;
		} else {
			$authordata = get_userdata($user_id);
		}
		
		$field = strtolower($field);
		$user_field = 'user_' . $field;
		
		if ( 'id' == $field ) {
			$value = isset($authordata->ID) ? (int) $authordata->ID : 0;
		} elseif (isset($authordata->$user_field)) {
			$value = $authordata->$user_field;
		} else {
			$value = isset($authordata->$field) ? $authordate->$field : '';
		}
		
		return apply_filters('get_the_author_' . $field, $value, $user_id);
	}
}

function art_get_comment_author_link(){
    ob_start();
    comment_author_link();    
    return ob_get_clean();
}

function art_get_edit_comment_link(){
    ob_start();
    edit_comment_link('('.__('Edit', THEME_NS).')','  ','');
    return  ob_get_clean();
}

function art_get_comment_text(){
    ob_start();
    comment_text();
    return  ob_get_clean();
}

function art_get_comment_reply_link($args, $depth){
    ob_start();
    comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'])));
    return  ob_get_clean();
}

function art_comment($comment, $args, $depth)
{
	 $GLOBALS['comment'] = $comment; ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
<?php  art_post_box('',  art_parse_template("comment", array(
		'get_avatar'          =>   get_avatar($comment, $size='48'),
		'comment_author_link' =>   art_get_comment_author_link(),
		'status'              =>   $comment->comment_approved == '0' ?  '<em>' . __('Your comment is awaiting moderation.', THEME_NS) . '</em><br />' : '',
		'get_comment_link'    =>   htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ,
		'get_comment_date'    =>   sprintf(__('%1$s at %2$s', THEME_NS), get_comment_date(),  get_comment_time()),
		'edit_comment_link'   =>   art_get_edit_comment_link(),
		'comment_text'        =>   art_get_comment_text(),
		'comment_reply_link'  =>   art_get_comment_reply_link($args, $depth)))); ?>      
     </div>
<?php
}

add_filter('comments_template', 'legacy_comments');  
function legacy_comments($file) {  
    if(!function_exists('wp_list_comments')) : // WP 2.7-only check  
    $file = TEMPLATEPATH.'/legacy-comments.php';  
    endif;  
    return $file;  
} 

if ( function_exists('add_theme_support') ) {
	add_theme_support('post-thumbnails');
  add_theme_support('nav-menus');
  add_theme_support('automatic-feed-links');
}
 
if (function_exists('register_nav_menus')) {
    register_nav_menus(
		array(
			'primary-menu'   =>   __( 'Primary Menu', THEME_NS),
			'secondary-menu' =>   __( 'Secondary Menu', THEME_NS)
		)
	);
}
 
 
if (!function_exists('esc_attr')) {
	function esc_attr( $text ) {
		return attribute_escape($text);
	}
} 

if (!function_exists('esc_html')) {
	function esc_html( $text ) {
		return $text;
	}
} 
 
function art_add_admin() {
  add_theme_page(__('Artisteer Options', THEME_NS), __('Artisteer Options', THEME_NS), 'edit_themes', basename(__FILE__), 'art_admin');
} 
add_action('admin_menu', 'art_add_admin');

function art_admin() {
    global $options;
    $br = "\n";
?>
<div class="wrap">
  <div id="icon-themes" class="icon32"><br></div>
	<h2><?php _e('Artisteer Options', THEME_NS); ?></h2>
<?php 
  if ( isset($_REQUEST['Submit']) )  { 
      foreach ($options as $value) {
        update_option( $value['id'], stripslashes($_REQUEST[ $value['id'] ]) ); 
      }
    echo '<div id="message" class="updated fade"><p><strong>'. __('Settings saved.', THEME_NS) .'</strong></p></div>'.$br; 
  } 
  if ( isset($_REQUEST['Reset']) )  { 
    foreach ($options as $value) {
        delete_option( $value['id']); 
    }
    echo '<div id="message" class="updated fade"><p><strong>'. __('Settings restored.', THEME_NS) . '</strong></p></div>'.$br;
  } 
	echo '<form method="post">'.$br;
  $in_form_table = false;
  foreach ($options as $value) {
    $type = $value['type'];
    $name = $value['name'];
    $desc = $value['desc'];
    if ($type == 'heading'){
      if ($in_form_table) {
        echo '</table>'.$br;
        $in_form_table = false;
      }
      echo '<h3>'.$name.'</h3>'.$br;
      if ($desc) {
        echo "\n".'<p class="description">'.$desc.'</p>'.$br;
      }
    } else {
      if (!$in_form_table) {
        echo '<table class="form-table">'.$br;
        $in_form_table = true;
      }
      echo '<tr valign="top">'.$br;
      echo '<th scope="row">'.$name.'</th>'.$br;
      echo '<td>'.$br;
      $id = $value['id'];
      $val = get_option($id);
      if ($val === false) {
        $val = $value['std'];
      }
      switch ($type) {
          case 'numeric':
            echo '<input  name="'.$id.'" id="'.$id.'" type="text" value="'.absint($val).'" class="small-text" />'.$br;
          break;
          case 'select':
            echo '<select name="'.$id.'" id="'.$id.'">'.$br;
              foreach ($value['options'] as $key => $option) { 
                $selected = ($val == $key ? ' selected="selected"' : '');
                echo '<option'.$selected.' value="'.$key.'">'.esc_html(__($option, THEME_NS)).'</option>'.$br;
              }              
            echo '</select>'.$br;
          break;
          case 'textarea':
            echo '<textarea name="'.$id.'" id="'.$id.'" rows="10" cols="50" class="large-text code">'.esc_html($val).'</textarea><br />'.$br;
          break;
          case "radio":
            foreach ($value['options'] as $key=>$option) {
              $checked = ( $key == $val ? 'checked="checked"' : '');
              echo '<input type="radio" name="'.$id.'" id="'.$id.'" value="'.esc_attr($key).'" '.$checked.'/>'.esc_html($option).'<br />'.$br;
            }
          break;
          case "checkbox":
              $checked =  ($val ? 'checked="checked" ' : ''); 
              echo '<input type="checkbox" name="'.$id.'" id="'.$id.'" value="true" '.$checked.'/>'.$br;
          break;
          default:
            $class = 'regular-text';
            if ($type == 'numeric'){
              $type = 'text';
              $class = 'small-text';
              $val = absint($val);
            }
            if ($type == 'widetext') {
              $class = 'large-text';
            }
            echo '<input  name="'.$id.'" id="'.$id.'" type="'.$type.'" value="'.esc_attr($val).'" class="'.$class.'" />'.$br;
          break;
      }
      if ($desc) {
        echo '<span class="description">'.$desc.'</span>'.$br;
      }
      echo '</td>'.$br;
      echo '</tr>'.$br;
    }
  }
  if ($in_form_table) {
    echo '</table>'.$br;
  }
?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php echo esc_attr(__('Save Changes', THEME_NS)) ?>" />
		<input name="Reset" type="submit" class="button-secondary" value="<?php echo esc_attr(__('Reset to Default', THEME_NS)) ?>" />
	</p>
	</form>
  </div>
<?php
}

/* Define the art page title box */
add_action('add_meta_boxes', 'art_page_title_box');

/* Save art page title show status */
add_action('save_post', 'art_page_title_show_save');


function art_page_title_box() {
    add_meta_box( 'art_page_title_box_id',
                  __( 'Page Options', 'kubrick' ),
                  'art_page_title_inner_custom_box',
                  'page',
                  'side',
                  'high'
                 );
}


/* Prints the art page title box content */
function art_page_title_inner_custom_box($post) {

    // Use nonce for verification
    wp_nonce_field( plugin_basename(__FILE__), 'art_page_title_noncename' );

    $art_page_title_show = 'true';
    if ( isset($post) && (get_post_meta($post->ID, '_art_page_title_show', true) != '') ){
        $art_page_title_show = get_post_meta($post->ID, '_art_page_title_show', true);
    }

    // The actual fields for data entry
    echo '<p class="meta-options">
              <label for="art_page_title_show">
                  <input type="checkbox" id="art_page_title_show" name="art_page_title_show" ';

    if ($art_page_title_show == 'true') {
        echo 'checked="checked" ';
    }
    echo 'value="' . $art_page_title_show . '" />
                  Show page title
              </label>
          </p>';
}

/* When the post is saved, saves our data */
function art_page_title_show_save($post_id) {

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times

    if ( !wp_verify_nonce( $_POST['art_page_title_noncename'], plugin_basename(__FILE__) )) {
        return $post_id;
    }

    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;

    // Check permissions
    if ( 'page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id ) ){
        return $post_id;
    }

    // OK, we're authenticated: we need to find and save the data

    $art_page_title_show = 'false';
    if (isset($_POST['art_page_title_show'])){
        $art_page_title_show = 'true';
    }

    update_post_meta($post_id, '_art_page_title_show', $art_page_title_show);

    return $art_page_title_show;
}

