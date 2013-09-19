<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<li>',
        'after_widget' => '</li>',
        'before_title' => '<div class="sideBarTitle">
								<h2>',
        'after_title' => '		</h2>
							</div>',
		'name' =>'sidebar'
    ));	
	
function string_limit_words($string, $word_limit)
{
$words = explode(' ', $string, ($word_limit + 1));
if(count($words) > $word_limit)
array_pop($words);
return implode(' ', $words);
}

add_filter( 'comments_template', 'legacy_comments' );
function legacy_comments( $file ) {
	if ( !function_exists('wp_list_comments') )
		$file = TEMPLATEPATH . '/legacy.comments.php';
	return $file;
}


?>