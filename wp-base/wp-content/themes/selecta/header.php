<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<?php if(is_single()) :
	global $wpdb;
	setup_postdata($post);
	echo "\n<meta name=\"keywords\" content=\"".fetch_post_tags($post->ID)."\" />";
	echo "\n<meta name=\"description\" content=\"".str_replace("\n", "", $post->post_excerpt)."\" />";
endif; ?>

<title><?php bloginfo('name'); ?><?php wp_title(" - ", true, "left"); ?></title>
<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" type="text/css" />
<?php if(isset($_GET["use_colour"])) :?>
	<link href="<?php bloginfo('template_directory'); ?>/color-styles/<?php echo $_GET["use_colour"]; ?>/style.css" rel="stylesheet" type="text/css" />
<?php elseif(isset($_COOKIE["ocmx_theme_style"])) :?>
	<link href="<?php bloginfo('template_directory'); ?>/color-styles/<?php echo $_COOKIE["ocmx_theme_style"]; ?>/style.css" rel="stylesheet" type="text/css" />
<?php elseif(get_option("ocmx_theme_style") !== "") : ?>
	<link href="<?php bloginfo('template_directory'); ?>/color-styles/<?php echo get_option("ocmx_theme_style"); ?>/style.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<?php if(get_option("ocmx_rss_url")) : ?>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php echo get_option("ocmx_rss_url"); ?>">
<?php else : ?>
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo("rss2_url"); ?>">
<?php endif; ?>

<script src="<?php bloginfo('template_directory'); ?>/scripts/jquery.js" type="text/javascript" language="javascript1.5"></script>
<script src="<?php bloginfo('template_directory'); ?>/scripts/selecta_jquery.js" type="text/javascript" language="javascript1.5"></script>

<?php wp_head(); ?>
</head>
<body>
<!--[if lt IE 7]>
	<div class="no_ie">
		<h1>Please note, your browser is out of date!</h1>
		<p class="error">We highly recommend that you upgrade your browser. Our suggestion: <a href="http://www.getfirefox.com">Mozilla Firefox</a>.</p>
	</div>
<![endif]-->
<div id="header-container">
	<div class="container-header-dark-normal"><span></span></div>
	<div id="header" class="clearfix">
		<h1 class="logo">
			<?php if(get_option("ocmx_custom_logo")) : ?>
                <a href="<?php bloginfo('url'); ?>" class="logo"><img src="<?php echo get_bloginfo("wpurl"); ?>/wp-content/uploads/<?php echo get_option("ocmx_custom_logo"); ?>" alt="<?php bloginfo('name'); ?>" /></a>
            <?php else : ?>
                <a href="<?php bloginfo('url'); ?>" class="logo"><img src="<?php bloginfo('template_directory'); ?>/images/logo.png" alt="<?php bloginfo('name'); ?>" /></a>
            <?php endif; ?>
		</h1>
		<ul id="menu">
			<li class="parent-item"><a class="parent-link" href="<?php bloginfo('url'); ?>">Home</a></li>
            <?php ocmx_menu(); ?>
		</ul>
	</div>
	<div class="container-footer-dark-dark"><span></span></div>
</div>
<?php 
global $is_archive; ?>
    <div id="feature-post-container" class="clearfix">
        <div id="feature-post" class="clearfix">
			<?php if($is_archive) :?>
            	<h1>Archives Section</h1>
				<?php
					$last_month = "";
					$month_count=1;
					$fetch_archive = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE post_status='publish' AND post_type = 'post' GROUP BY $wpdb->posts.ID ORDER BY post_date DESC");
					foreach($fetch_archive as $archive_data) :
						if(date("m Y", strtotime($archive_data->post_date)) !== $last_month) :?>
							<a  href="#" id="archive-href-<?php echo $month_count; ?>" class="archive-date"><?php echo date("M Y", strtotime($archive_data->post_date)); ?></a>
				<?php		$month_count++; 
						endif;
						$last_month = date("m Y", strtotime($archive_data->post_date));
					endforeach; ?>
			<?php elseif(is_category()) : ?>
	            <h1><?php single_cat_title(); ?></h1>
			<?php elseif (is_search()) : ?>
		    	<h1>Your Search Results for "<em><?php the_search_query(); ?></em>"</h1>
			<?php elseif(is_page()) : ?>
                <?php if (have_posts()) : while (have_posts()) : the_post();    	        
                    setup_postdata($post); ?>
                            <h1><?php the_title(); ?></h1>
                <?php endwhile; endif; ?>
            <?php elseif(is_single() || is_page()) : ?>
                <?php if (have_posts()) : while (have_posts()) : the_post();    	        
                        setup_postdata($post);
                        //Fetch the Custom Metas for this post
                        $get_thumbnail = get_post_meta($post->ID, "other_media", true);;
                        $get_post_video = get_post_meta($post->ID, "main_video", true);
                 ?>	
                        <div class="post">
							<div class="feature-title">
                            	<h4 class="date"><?php echo date('d M Y', strtotime($post-> post_date)); ?></h4>
	                            <h2 class="title"><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h2>
							</div>
                            <div class="container-header-light-dark"><span></span></div>
                            <div class="copy clearfix">
                                <?php if($get_post_video !== "") :
                                    $get_post_video = preg_replace("/(width\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 940 $2 wmode=\"transparent\" ", $get_post_video);
                                    $get_post_video = preg_replace("/(height\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 480 $2", $get_post_video);
                               ?>
                                    <div class="media">
                                        <?php echo $get_post_video; ?>
                                    </div>
                                <?php elseif ($get_thumbnail !== "") :  ?>
                                    <div class="media"><img src="<?php bloginfo('template_directory'); ?>/functions/timthumb.php?src=<?php echo $get_thumbnail ?>&amp;w=940&amp;h=&amp;zc=1" alt="<?php the_title(); ?>" />
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="container-footer-light-dark"><span></span></div>
                        </div>
                <?php endwhile; endif; ?>
            <?php elseif(is_front_page() && is_active_sidebar(1)) :
                if (function_exists('dynamic_sidebar') && dynamic_sidebar(1) );
            endif; ?>
        </div>
    </div>
<div id="content-container">