<?php
if ( function_exists('register_sidebar') )
    register_sidebar(array(
	    'before_widget' => '<li>',
	    'after_widget' => '</li>',
	    'before_title' => '<h2><span>',
	    'after_title' => '</span></h2>',
    ));
register_sidebar(array('name'=>'sidebar2',
    'before_widget' => '<li>',
    'after_widget' => '</li>',
    'before_title' => '<h2><span>',
    'after_title' => '</span></h2>',
));
register_sidebar(array('name'=>'extra-content-one',
    'before_widget' => '<li>',
    'after_widget' => '</li>',
    'before_title' => '<h2><span>',
    'after_title' => '</span></h2>',
));
register_sidebar(array('name'=>'extra-content-two',
    'before_widget' => '<li>',
    'after_widget' => '</li>',
    'before_title' => '<h2><span>',
    'after_title' => '</span></h2>',
));
register_sidebar(array('name'=>'extra-content-three',
    'before_widget' => '<li>',
    'after_widget' => '</li>',
    'before_title' => '<h2><span>',
    'after_title' => '</span></h2>',
));

function images($num = 1, $width = null, $height = null, $class = '', $displayLink = true) {
	global $more;
	$more = 1;
	if($width) { $size = ' width="'.$width.'px"'; }
	if($height) { $size .= ' height="'.$height.'px"'; }
	if($class) { $class = ' class="'.$class.'"'; }
	if($displayLink != false) { $link = '<a href="'.get_permalink().'">'; $linkend = '</a>'; }
	$content = get_the_content();
	$count = substr_count($content, '<img');
	$start = 0;
	for($i=1;$i<=$count;$i++) {
		$imgBeg = strpos($content, '<img', $start);
		$post = substr($content, $imgBeg);
		$imgEnd = strpos($post, '>');
		$postOutput = substr($post, 0, $imgEnd+1);
		$replace = array('/width="[^"]*" /','/height="[^"]*" /','/class="[^"]*" /');
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

?>