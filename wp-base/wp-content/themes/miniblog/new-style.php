
<?php
	global $options;
	foreach ($options as $value) {
	if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
	}
?>

<?php
$mb_bubble_size_big = $mb_bubble_size+36;
?>

<!--Styles from Theme Options-->
<style type="text/css">

body{
	background: #<?php echo $mb_color_page_background;?>;
}

#bubble-container{
	width: <?php echo $mb_bubble_size_big;?>px;
}

#bubble-middle{
	width: <?php echo $mb_bubble_size;?>px;
}

.second-color{
	color: #<?php echo $mb_color_secondary;?>;
}

#navbar-container ul li.current_page_item a:link,
#navbar-container ul li.current_page_item a:visited,
#navbar-container ul li.current_page_item a:hover,
#navbar-container ul li.current_page_item a:active,
#navbar-container ul li.current_page_item a:focus{
	background: #<?php echo $mb_color_page_background;?> url(images/dark-border.png) top left repeat-x;
}

#tabnav li a:link,
#tabnav li a:visited{
	background: #<?php echo $mb_color_page_background;?>;
}

#tabnav li a:hover,
#tabnav li a.selected:link,
#tabnav li a.selected:visited{
	background: #<?php echo $mb_color_tab;?>;
}

.search-button{
	background: #<?php echo $mb_color_page_background;?>;
}

.post-comments a:link,
.post-comments a:visited{
	background: #<?php echo $mb_color_page_background;?>;
}

.post-comments a:hover{
	background: #<?php echo $mb_color_tab;?>;
}

.post-content p.post-more a:link,
.post-content p.post-more a:visited{
	color: #<?php echo $mb_color_secondary;?>;
}

.post-content h2{
	color: #<?php echo $mb_color_secondary;?>;
}

.comment-header-title a:link,
.comment-header-title a:visited{
	color: #<?php echo $mb_color_secondary;?>;
}

</style>