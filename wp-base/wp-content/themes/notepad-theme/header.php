<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php if (is_home()) {
	echo bloginfo('name');
} elseif (is_404()) {
	echo '404 Not Found';
} elseif (is_category()) {
	echo 'Category:'; wp_title('');
} elseif (is_tag()) {
	echo 'Tag:'; wp_title('');
} elseif (is_search()) {
	echo 'Search Results for: ' . $s;
} elseif ( is_day() || is_month() || is_year() ) {
	echo 'Archives:'; wp_title('');
} else {
	echo wp_title('');
}
?></title>

<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" type="text/css" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body>
<div id="header">
	<h1 id="logo"><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
	<p class="description"><?php bloginfo('description'); ?></p>

	<?php include (TEMPLATEPATH . "/socialmedia.php"); ?>

	<ul id="nav">
		<li<?php if (is_home()) { echo ' class="current_home"'; }?>><a href="<?php echo get_option('home'); ?>">Home</a></li>
		<?php wp_list_pages('sort_column=menu_order&number=5&title_li='); ?>
	</ul>
	<?php include (TEMPLATEPATH . '/searchform.php'); ?>
</div>
<!--/header -->
<div id="wrapper">
