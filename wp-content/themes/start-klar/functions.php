<?php


function chapter_with_sample ($content) {

	global $post;
        #$content='<h3>'.$post->menu_order.'</h3>';
	$anchor = '-'.$post->post_name;
	$sample = trim(implode(get_post_custom_values('kapitelbeispiel')));
	/*global $wp_query;
	$sample = print_r($wp_query,1);*/
	return (excerpt_state()||$sample=='')?$content:<<<html
<div class="mit-beispiel" id="tabs$anchor">
<ul>
	<li><a href="#inhalt$anchor">Inhalt</a></li>
	<li><a href="#beispiel$anchor">Beispiel</a></li>
</ul>
<div class="clearfix" id="inhalt$anchor">
$content
</div>
<div class="clearfix" id="beispiel$anchor">
$sample
</div>
</div>
html
;
}
add_filter('the_content','chapter_with_sample');



function add_more_tag_to_preview($content){
	$link=get_permalink();
	if(excerpt_state()) {
		$content=implode('.',array_slice(explode('.',$content),0,2));
		if($content) $content.='. ';
	}
	return $content.<<<html
<a href="$link" class="rel relArticle">Weiterlesen...</a>
html
;
};
function add_create_comment_link($content) {
	$link=get_permalink();
	$commentCount=wp_count_comments()->approved;
	return $content.<<<html
 <a href="$link#comments" class="rel relComments">Bisher $commentCount Kommentar(e)</a>
html
//.$content
;
}

function excerpt_state($show=null) {
	static $state=false;
	isset($show)&&$state=$show;
	return $state;
}

function show_sub_categories($catDescription) {
	static $c=0;
	if (!(is_category()||is_tax())) return $catDescription;
	if (did_action('__after_loop')) return array_shift(explode(".",$catDescription)).'...';
	$catsHTML='';
	foreach (get_categories(array('parent'=>get_query_var('cat'),'hide_empty'=>false)) as $subCat) {
		extract((array)$subCat);
		$description=array_shift(explode("\n",$description));
		$link = esc_url(get_category_link($term_id));
		$catsHTML.=<<<html
<section>
<h2 class="format-icon"><a href="$link">$name</a></h2>
<div class="archive-meta"><p>$description <a href="$link" class="rel relCategory" title="alle $count Artikel unter '$name' lesen">Weiterlesen...</a></p></div>
</section>
html
;
	}
	static $b = true;
	if($b) {
		$b=false;
		if($catsHTML!='') {
			add_filter('tc_show_excerpt',function(){return excerpt_state(true); });
			add_filter('get_the_excerpt','add_more_tag_to_preview');
			add_action('__before_article',function(){ static $b=true; if ($b): $b=false; ?><h1>Kapitel-Übersicht</h1><?php endif; });
		} else {
			add_filter('the_content','add_create_comment_link');
		}
	}
	return $catDescription.($catsHTML!=''?('<hr class="featurette-divider">'.$catsHTML):'');
}
if (!is_admin()) add_filter('category_description','show_sub_categories');


add_filter('widget_categories_args',function($args) {
	return array_merge($args,array(
		@hide_empty=>false,
		@exclude=>1,
		@orderby=>@id
	));
});


function manual_taxo_order( $query ) {
    // not an admin page and is the main query
    if ( !is_admin() && $query->is_main_query() ) {
        if ( is_category() || is_tax() ) {
            $query->set( 'orderby', 'menu_order' );
            $query->set( 'order', 'asc' );
        }
    }
}
add_action( 'pre_get_posts', 'manual_taxo_order' );

add_action ('__footer',function(){
global $wp_query;
$q=print_r($wp_query->request,1);
?>
<footer class="mlu container"><a href="http://www.uni-halle.de"><img src="//blogs.urz.uni-halle.de/startklar/files/2015/06/MLU_Halle-Wittenberg_Siegel-e1433410810656.png" alt="Martin-Luther-Universität Halle-Wittenberg"></a></footer>
<?php
},20);
