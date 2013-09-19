<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title>
		<?php if (is_home()) { 
		echo bloginfo('name');
		echo ' | ';
		echo bloginfo('description');
		} elseif (is_page('blog')) {
		echo wp_title('');
		echo ' | ';
		echo bloginfo('name');
		} elseif (is_category()) {
		echo wp_title('');
		echo ' | ';
		echo bloginfo('name');
		} elseif (is_search()) {
		echo 'Search Results';
		echo ' | ';
		echo bloginfo('name');
		} elseif ( is_day() || is_month() || is_year() ) {
		echo 'Archives:'; wp_title('');
		} else {
		echo wp_title('');
		echo ' | ';
		echo bloginfo('name');
		}
		?>
	</title>
	
	<!-- Dynamic Description stuff -->
	<meta name="description" content="<?php if (have_posts() && is_single() OR is_page()):while(have_posts()):the_post();
	$out_excerpt = str_replace(array("\r\n", "\r", "\n"), "", get_the_excerpt());
	echo apply_filters('the_excerpt_rss', $out_excerpt);
	endwhile;
	else:
	bloginfo('name');
	echo ' - ';
	bloginfo('description');
	endif; ?>" />
	
	<!-- Dynamic Keywords stuff from the post tags -->
	<meta name="keywords" content="
	<?php if (have_posts() && (has_tag()) && is_single() OR is_page()):while(have_posts()):the_post();
	$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC'));
	$xt = 1;
	foreach ($tags as $tag) {
		if ($xt <= 20) {
		echo $tag->name.", ";
		}
	$xt++;
	}
	endwhile;
	else: ?>
	blogs, mlu, uni-halle, halle, wissenschaft, blogging, Martin-Luther-Universit&auml;t'
	<?php endif; ?>" />
	
	<!-- Block the hungry search robots on some dynamic pages -->
	<?php if(is_search() || is_archive() ) { ?>
	<meta name="robots" content="noindex, nofollow" />
    <?php }?>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="http://www.uni-halle.de/im/sod/common/img/favicon.ico" type="image/x-icon" />
<link rel="icon" href="http://www.uni-halle.de/im/sod/common/img/favicon.ico" type="image/x-icon" />

<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/ie.css" media="screen" />
<![endif]-->

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div class="container">
		<div class="edge"></div>

		<div class="masthead-container">
			<div class="search-container">
				<div class="search">
					<?php include ('searchform.php'); ?>
				</div><!-- end search -->
			</div>

			<h2 class="logo"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h2>
			<p class="description"><?php bloginfo('description'); ?></p>
			
			<div class="tools">
				<ul>
				<li class="rss"><a href="<?php bloginfo('rss2_url'); ?>">RSS</a></li>
				</ul>
				
			</div>

		</div><!-- end masthead container -->








