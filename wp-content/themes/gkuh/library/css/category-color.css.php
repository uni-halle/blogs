<?php

// remove error reporting so that we don't break the CSS in debug mode
$reporting_level = error_reporting();
error_reporting(0);

// get all the categories
$categories = get_categories( array(
	'hide_empty'               => 0
));

echo '/* This file is generated automatically if a category is edited in the admin section. Each category that is given a color puts out the code in here. See function save_category_color_css in functions.php and category-color.css.php for the generating functions */

';

foreach( $categories as $category ){
	// get the color
	$cat_color = rl_color($category->term_id);

	// create a CSS block only if the color is set
	if( ! empty($cat_color) && $cat_color != '#' ) : ?>

/* <?php echo $category->name; ?> */
.cc-<?php echo $category->term_id; ?>, .cc-<?php echo $category->term_id; ?>-hov:hover, .cc-<?php echo $category->term_id; ?>-af::after, .cc-<?php echo $category->term_id; ?>-bf::before {
	color: <?php echo $cat_color; ?> !important;
}
.cc-<?php echo $category->term_id; ?>-bgrd, .cc-<?php echo $category->term_id; ?>-bgrd-hov:hover, .cc-<?php echo $category->term_id; ?>-bgrd-af::after, .cc-<?php echo $category->term_id; ?>-bgrd-bf::before {
	background-color: <?php echo $cat_color; ?> !important;
}
.cc-<?php echo $category->term_id; ?>-fill, .cc-<?php echo $category->term_id; ?>-fill-hov:hover, .cc-<?php echo $category->term_id; ?>-fill-bf::before, .cc-<?php echo $category->term_id; ?>-fill-af::after {
	fill: <?php echo $cat_color; ?> !important;
}
.cc-<?php echo $category->term_id; ?>-border, .cc-<?php echo $category->term_id; ?>-border-hov:hover, .cc-<?php echo $category->term_id; ?>-border-bf::before, .cc-<?php echo $category->term_id; ?>-border-af::after {
	border-color: <?php echo $cat_color; ?> !important;
}

	<?php
	endif;
}

// re-enable the error reporting
error_reporting($reporting_level);
?>