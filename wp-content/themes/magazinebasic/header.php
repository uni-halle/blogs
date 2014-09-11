<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<?php if(is_home() || is_single() || is_page()) { echo '<meta name="robots" content="index,follow" />'; } else { echo '<meta name="robots" content="noindex,follow" />'; } ?>
    
    <?php if (is_single() || is_page() ) : if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <meta name="description" content="<?php metaDesc(); ?>" />
    <?php csv_tags(); ?>
    <?php endwhile; endif; elseif(is_home()) : ?>
    <meta name="description" content="<?php bloginfo('description'); ?>" />
    <meta name="keywords" content="wordpress,wordpress themes,custom theme,magazine three columns,free,tinker priest media,blog,search engine optimization,seo,xhtml valid,css,html,php,mysql,web design,three columns,widget,sidebar,tags,gravatar" />
    <?php endif; ?>
    
    <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' | '; } ?><?php bloginfo('name'); if(is_home()) { echo ' | '; bloginfo('description'); } ?></title>

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
    <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/iestyles.css" />
<![endif]-->
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/script.js"></script>
	<?php wp_head(); ?>
</head>

<body>
<!-- begin header -->
<div id="header">
	<?php if (get_option('uwc_user_login') == "top" || get_option('uwc_user_login') == "topwidget") { ?>
	<div id="login">
    	<?php
			global $user_identity, $user_level;
			if (is_user_logged_in()) { ?>
            	<ul>
                <li><span style="float:left;">Als <strong><?php echo $user_identity ?></strong> angemeldet.</span></li>
				<li><a href="<?php bloginfo('url'); ?>/wp-admin">Kontrollcenter</a></li>
                <?php if ( $user_level >= 1 ) { ?>
                	<li class="dot"><a href="<?php bloginfo('url') ?>/wp-admin/post-new.php">Artikel schreiben</a></li>
				<?php } ?>
                <li class="dot"><a href="<?php bloginfo('url') ?>/wp-admin/profile.php">Profil</a></li>
				<li class="dot"><a href="<?php echo wp_logout_url() ?>&amp;redirect_to=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>" title="<?php _e('Log Out') ?>"><?php _e('Log Out'); ?></a></li>
                </ul>
            <?php 
			} else {
				echo '<ul>';
				echo '<li><a href="'; echo bloginfo("url"); echo '/wp-login.php">Anmelden</a></li>';
				if (get_option('users_can_register')) { ?>
					<li class="dot"><a href="<?php echo site_url('wp-login.php?action=register', 'login') ?>"><?php _e('Register') ?></a> </li>
                
            <?php 
				}
				echo "</ul>";
			} ?> 
    </div>
    <?php } ?>
	<?php if (get_option('uwc_search_header') == "yes") { ?>
    <div id="header-search">
    	<?php include(TEMPLATEPATH.'/searchform.php'); ?>
    </div>
    <?php } ?>
	<?php if (get_option('uwc_logo_header') == "yes" && get_option('uwc_logo')) { ?>
    <div id="title">
    	<a href="<?php bloginfo('url'); ?>/"><img src="<?php echo get_option('uwc_logo'); ?>" title="<?php bloginfo('name'); ?>" alt="<?php bloginfo('name'); ?>" /></a>
    </div>    	
    <?php } else { ?>
    <div id="title">
    	<a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a>
    </div>
    <?php } ?>
   	<div id="description">
    	<?php bloginfo('description'); ?>
    </div>

    <div id="navigation">
        <ul class="menu" id="menu">
        <li><a href="<?php bloginfo('url'); ?>">Startseite</a></li>
		<?php
        $cats = wp_list_categories('echo=0&title_li=');
        $cats = preg_replace('/title=\"(.*?)\"/','',$cats);
        echo $cats;
        ?>
        </ul>
    </div>
    <div id="sub-navigation">
    	<ul class="pages">
		<?php wp_list_pages('title_li=&depth=1'); ?>
        </ul>
        <ul>
        <li><a href="<?php bloginfo('url'); ?>?feed=rss2">RSS Feeds</a></li>
        </ul>
     </div>
</div>
<!-- end header -->


<div id="mainwrapper">
<?php
	if(get_option('uwc_site_sidebars') == "1" && get_option('uwc_sidebar_location') == "oneleft") {   	
		get_sidebar(1);
	}
	if(get_option('uwc_site_sidebars') == "2" && get_option('uwc_sidebar_location') == "twoseparate") {   	
		get_sidebar(1);
	}	
	if(get_option('uwc_site_sidebars') == "2" && get_option('uwc_sidebar_location') == "twoleft") {   	
		get_sidebar(1);
		include(TEMPLATEPATH.'/sidebar2.php');
	}
	if(get_option('uwc_site_sidebars') == "" && get_option('uwc_sidebar_location') == "") {   	
		get_sidebar(1);
	}
	?>
	<div id="leftcontent">
