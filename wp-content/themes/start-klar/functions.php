<?php


function chapter_with_sample ($content) {

	global $post;
	$anchor = '-'.$post->post_name;
	$sample = trim(implode(get_post_custom_values('kapitelbeispiel')));
	/*global $wp_query;
	$sample = print_r($wp_query,1);*/
	return $sample==''?$content:<<<html
<div class="mit-beispiel">
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

