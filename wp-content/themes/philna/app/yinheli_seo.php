<?
/* seo functions
yinheli 定义的函数.主要用于seo的优化.
*/
//关键字和描述部分的处理
function yinheli_meta_dec(){
$options = get_option('philna_options');
	global $post,$wp_query;
		$keywords = array();
	/*
	关键字
	*/
	if(is_home()) :
	$keywords[]=$options['keywords'];
	endif;
	if(is_single()) :
		$post_keywords = get_post_meta($post->ID, 'Keywords', true);
		if($post_keywords) $keywords[] = $post_keywords;

		 if($philna_settings['seo_cats'] && !$post_keywords) :
			$cats = get_the_category();
			if($cats) :
				foreach($cats as $cat) :
					$keywords[] = $cat->name;
				endforeach;
			endif;
		endif;
		if(!$post_keywords) :
			$wp_query->in_the_loop = true;
			$tags = get_the_tags();
			if($tags) :
				foreach($tags as $tag) :
					$keywords[] = $tag->name;
				endforeach;
			endif;
			$wp_query->in_the_loop = false;
		endif;

	/*
	* Check for custom field key Keywords
	*/
	elseif(is_page()) :
		$post_keywords = get_post_meta($post->ID, 'Keywords', true);
		if($post_keywords) :
			$keywords[] = $post_keywords;
		endif;
	endif;

	if(!empty($keywords)) :
		$keywords = join(', ', $keywords);
		echo '<meta name="keywords" content="' . wp_specialchars($keywords, 1) . '" />' . "\n";
	endif;
	/*
	描述
	*/
	// If on home page
	if(is_home() || is_front_page()) :
		$meta_desc = $options['description'];
			if(is_paged()) :
			$meta_desc="";
			endif;
	elseif(is_single() || is_page()) :
		$meta_desc = get_post_meta($post->ID, 'Description', $single = true);
		if(!$meta_desc) :
			$meta_desc =mb_substr(strip_tags($post->post_content),0,210);
		endif;
	elseif(is_category()) :
		$meta_desc = stripslashes(strip_tags(category_description()));
	elseif(is_author()) :
		$meta_auth = get_userdata(get_query_var('author'));
		$meta_desc = str_replace(array('"'), '&quot;', $meta_auth->description);
	endif;

	if($meta_desc && strlen($meta_desc) > 1) :
		$meta_desc = wp_specialchars($meta_desc, 1);
		echo '<meta name="description" content="' . $meta_desc . '" />' . "\n";
	endif;
if($options['authorname']!=='') echo '<meta name="author" content="'.$options['authorname'].'" />' . "\n";
echo '<meta name="robots" content="index,follow" />' . "\n";
}
//头部标题的显示
function yinheli_head_title(){
if(is_home() && !is_paged ())$tag = 'h1';
else $tag = 'h2';
	
$name=get_bloginfo('name');
$url=get_bloginfo('url');
$dec=get_bloginfo('description');

$out='<'.$tag.' id="blog-title"> ';
$out.='<a href="'.$url.'" title="'.$name." - ".$dec. '">'.$name.'</a>';
$out.='</' . $tag . '>';
echo $out;
}
//对于搜索引擎的现实限制
function is_bot(){
$bots = array('Google Bot' => 'googlebot', 'Google Bot' => 'google', 'MSN' => 'msnbot', 'Alex' => 'ia_archiver', 'Lycos' => 'lycos', 'Ask Jeeves' => 'jeeves', 'Altavista' => 'scooter', 'AllTheWeb' => 'fast-webcrawler', 'Inktomi' => 'slurp@inktomi', 'Turnitin.com' => 'turnitinbot', 'Technorati' => 'technorati', 'Yahoo' => 'yahoo', 'Findexa' => 'findexa', 'NextLinks' => 'findlinks', 'Gais' => 'gaisbo', 'WiseNut' => 'zyborg', 'WhoisSource' => 'surveybot', 'Bloglines' => 'bloglines', 'BlogSearch' => 'blogsearch', 'PubSub' => 'pubsub', 'Syndic8' => 'syndic8', 'RadioUserland' => 'userland', 'Gigabot' => 'gigabot', 'Become.com' => 'become.com','Bot'=>'bot','Spider'=>'spider','yinheli'=>'dFirefox');
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			foreach ($bots as $name => $lookfor) { 
				if (stristr($useragent, $lookfor) !== false) { 
					return true;
					break;
				}
			}
}
//浏览器标题部分的现实
function yinheli_title(){
global $post, $wp_query;
$options = get_option('philna_options');
if($options['b_title_add'] !==''){
$b_title_pre=' - ';
$b_title_add=' '.$options['b_title_add'].' ';
}
// Check if is paged
	if((($page = $wp_query->get('paged')) || ($page = $wp_query->get('page'))) && $page > 1)
		$paged = $page;	
	/*
	* Make compatible with plugins
	* All-in-One SEO Pack
	* HeadSpace2
	*/
	if(class_exists('All_in_One_SEO_Pack') || class_exists('HeadSpace2_Admin')) :
		if(is_front_page() || is_home()) :
			echo get_bloginfo('name') . ': ' . get_bloginfo('description');
		else :
			wp_title('');
		endif;
	else :
	// Front page or Home
		if(is_front_page() || is_home()) :
			echo bloginfo('name'); echo ' - '; echo bloginfo('description');echo $b_title_pre.$b_title_add;
			if(is_paged()) :
				printf('- Page %1$s', $paged);
			endif;
	// Attachments
		elseif(is_attachment()) :
			single_post_title();
			if($paged) :
				printf('- Page %1$s', $paged);
			endif;
	// Single posts or pages
		elseif(is_single() || is_page()) :
wp_title('',true);echo $b_title_pre.$b_title_add;
			if($paged) :
				printf('- Page %1$s', $paged);
			endif;
	// Categories
		elseif(is_category()) :
			single_cat_title();echo $b_title_pre.$b_title_add;
			if(is_paged()) :
				printf('- Page %1$s', $paged);
			endif;
	// Tags
		elseif(is_tag()) :
			single_tag_title();
			if(is_paged()) :
				printf('- Page %1$s', $paged);
			endif;
	// Search results
		elseif(is_search()) :
			printf(__('Search results for &quot;%1$s&quot;','philna'), attribute_escape(get_search_query()));echo $b_title_pre.$b_title_add;
			if(is_paged()) :
				printf('- Page %1$s', $paged);
			endif;
	// Daily archives
		elseif(is_day()) :
			printf('Archive for %1$s', get_the_time(__('F jS, Y','philna')));
			if(is_paged()) :
				printf('- Page %1$s', $paged);
			endif;
	// Weekly archives
		elseif(get_query_var('w')) :
			printf('Archive for week %1$s of %2$s', get_the_time(__('W','philna')), get_the_time(__('Y','philna')));
			if(is_paged()) :
				printf('- Page %1$s', $paged);
			endif;
	// Monthly archives
		elseif(is_month()) :
			printf('Archive for %1$s', single_month_title(' ', false));echo $b_title_pre.$b_title_add;
			if(is_paged()) :
				printf('- Page %1$s', $paged);
			endif;
	// Yearly archives
		elseif(is_year()) :
			printf('Archive for %1$s', get_the_time(__('Y','philna')));
			if(is_paged()) :
				printf('- Page %1$s', $paged);
			endif;
	// Comments popuup
		elseif(is_comments_popup()) :
			printf('Comment on &quot;%1$s&quot;', single_post_title(false,false));
	// 404
		elseif(is_404()) :
			echo '404 Not Found'."I'm sorry";
	// Anything else
		else :
			echo wp_title('',true);
		endif;
	endif;
}
function is_first_page(){
global $post, $wp_query;
	$page = $wp_query->get('paged');
	if(empty($page) || $page==1) return true;
	
}
function has_next_page(){
global $wpdb, $wp_query;
$max_page = $wp_query->max_num_pages;
if(!empty($max_page) && $max_page>1){
 return true;
 }else{
return false;
}
}
add_filter('wp_title', create_function('$a, $b','return str_replace(" $b ","",$a);'), 10, 2);
remove_action('wp_head', 'wp_generator'); 
?>
