<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>
			<?php if (is_front_page()) { 
			echo bloginfo('name');
			echo ' | ';
			echo bloginfo('description');
			} elseif (is_404()) {
			echo '404 Not Found';
			} elseif (is_category()) {
			echo wp_title('');
			echo ' | ';
			echo bloginfo('name');
			} elseif (is_search()) {
			echo 'Suchergebnisse';
			echo ' | ';
			echo bloginfo('name');
			} elseif ( is_day() || is_month() || is_year() ) {
			echo 'Archiv:'; wp_title('');
			} else {
			echo wp_title('');
			echo ' | ';
			echo bloginfo('name');
			}
			?>
		</title>

		<!-- Dynamic Description stuff -->
		<meta name="description" content="<?php if (have_posts() && is_single() OR is_page() && !is_front_page()):while(have_posts()):the_post();
		$out_excerpt = str_replace(array("\r\n", "\r", "\n"), "", get_the_excerpt());
		echo apply_filters('the_excerpt_rss', $out_excerpt);
		endwhile;
		else:
		bloginfo('name');
		echo ' - Der Blog-Dienst des Rechenzentrums f&uuml;r Studierende, Mitarbeiter und Institutionen der Martin-Luther-Universit&auml;t Halle-Wittenberg';
		endif; ?>" />
		
	<!-- Block the hungry search robots on some dynamic pages -->
	<?php if(is_search() || is_archive() ) { ?>
	<meta name="robots" content="noindex, nofollow" /> 
    <?php }?>
	
	<!-- This brings in the delicious styles -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url') ?>" type="text/css" media="screen" />
	<!--[if lt IE 9]>
		<link rel="stylesheet" media="screen" type="text/css" href="<?php bloginfo('template_directory') ?>/style/css/ie-old.css"  />
	<![endif]-->
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<link rel="shortcut icon" href="<?php bloginfo('template_directory') ?>/style/images/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="<?php bloginfo('template_directory') ?>/style/images/favicon.ico" type="image/x-icon" />
	
	<!-- Mobile stuff -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- For iPhone 4 with high-resolution Retina display: -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('template_directory') ?>/style/images/apple-touch-icon-114x114-precomposed.png">
	<!-- For first-generation iPad: -->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('template_directory') ?>/style/images/apple-touch-icon-72x72-precomposed.png">
	<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
	<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_directory') ?>/style/images/apple-touch-icon-precomposed.png">
	
	<?php wp_get_archives('type=monthly&format=link'); ?>
	
	<!-- Lets just hope plugin authors wont throw in too much scripts here in the head hook -->
	
	<?php 
	if ( is_single() ) wp_enqueue_script( 'comment-reply' );
	wp_enqueue_script('thickbox');
	wp_head(); 
	?>
	

</head>

<body <?php body_class(); ?>>
	
	<div id="top">
		<div id="topbar">
			
			<!-- Check if theres a custom menu, if not use the old page listing -->
			<?php if ( has_nav_menu( 'header-menu' ) ) { ?>
				
				<div class="topmenu">
					<?php wp_nav_menu(  array( 'theme_location' => 'header-menu' ));  ?>
				</div>
				
			<?php } else { ?>
			
				<?php wp_page_menu('show_home=0&menu_class=topmenu&sort_column=menu_order'); ?>
			
			<?php } ?>
			
			<ul id="subscribe">
				<li><a class="rss" href="<?php bloginfo('rss2_url'); ?>" title="<?php bloginfo('name'); ?> RSS-Feed abonnieren">RSS-Feed abonnieren</a></li>
				<li><a class="twitter" href="https://twitter.com/mlublogs" rel="me">Twitter</a></li>
				<li><a class="facebook" href="http://www.facebook.com/pages/blogsURZ/156259138175" rel="me">Facebook</a></li>
			</ul>
			
		</div>
	</div>
	
	<?php 
		$path = $_SERVER['REQUEST_URI'];
		if ($path == '/') {
	 ?>
	<div id="teaser-wrap">
		
		<div id="teaser">
		
			<h1>Der Blog-Dienst f&uuml;r Studierende, Lehrende, Mitarbeiter <span class="amp">&amp;</span> Institutionen der Martin-Luther-Universit&auml;t Halle-Wittenberg</h1>
			<p id="allinfo">
				<a class="button small" href="/dienst/" title="Blogs@MLU Dienst Features">Gute Gr&uuml;nde</a>
				<a class="button small" href="/dienst/nutzungsbedingungen/" title="Nutzungsbedingungen zum Blogs@MLU Blog-Dienst">Nutzungsbedingungen</a>
			</p>
			<ul id="newblog">
				<li><span class="number">1</span>Anmelden<span>Anmelden mit eurem 5-stelligen Nutzerkennzeichen</span></li>
				<li><span class="number">2</span>Blog anlegen<span>Blog-Name und Blog-URL angeben</span></li>
				<li><span class="number">3</span>Bloggen<span>Und los geht's!</span></li>
				<li id="last"><a class="button" href="<?php echo site_url('/new-blog.php', 'https'); ?>" title="Neuen Blog bei Blogs@URZ anlegen">Neuen Blog Starten &#8594;</a></li>
			</ul>

		</div>
	
	</div><!-- end #teaser-wrap -->
	
	<?php } ?>
	
	<div id="header">
	
		<div id="blogname">
			<h2><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h2>
			<p><?php bloginfo('description'); ?></p>
		</div>
		<?php include('admin-menu.php'); ?>
	</div>
	
	<?php if ($path == '/') { ?>
	
		<div id="community-wrap">
		<div id="community">
		
			<div id="posts" class="tabbox">
			
				<ul class="tab-menu">
					<li><a href="#frisch" class="selected">Frisches aus den Blogs</a><span class="infopopup" title="Aus allen Blogs in unserem Dienst, geordnet nach Aktualit&auml;t, ein Post pro Blog - Hallo Welt-Artikel, Beitr&auml;ge aus Haupt-Blog und aus nicht-&ouml;ffentlichen Blogs ausgeschlossen"></span></li>
					<li><a href="#frischportal">Frisches aus dem Portal</a><span class="infopopup" title="Letzte Ausgew&auml;hlte Beitr&auml;ge aus unserem Blog-Portal, geordnet nach Aktualit&auml;t. Beinhaltet auch Beitr&auml;ge aus Blogs, die nicht in unserem Dienst sind."></span></li>
				</ul>
				
				<ul class="panel show" id="frisch">
					<?php 
					if (function_exists('ahp_recent_posts')) {
						ahp_recent_posts(9, 600, 114, 8, '<li class="recent-post">','</li>');
					}
					?>
				</ul>
				
				<ul class="panel" id="frischportal">
					<?php
					include_once(ABSPATH.WPINC.'/rss.php');
					$feed = @fetch_rss('http://feeds2.feedburner.com/mlublogs_rss');
					$items = array_slice($feed->items, 0, 9);
					?>
					
					<?php if (!empty($items)) : ?>
					<?php foreach ($items as $item) : 
					$excerpt = substr(wp_filter_kses($item['description']), 0, 65); 
					$pubdate = substr($item['pubdate'], 4, 12); ?>
					
					<li class="recent-post">
						<a class="post-title" href="<?php echo $item['link']; ?>"><?php echo $item['title']; ?></a>
						<span class="post-excerpt"><?php echo $excerpt; ?> ...</span>
						<span class="meta-line"><?php echo $pubdate; ?> | <?php echo $item['author']; ?></span>
					</li>			
					
					<?php endforeach; ?>
					<?php endif; ?>
				</ul>

			</div><!-- end #posts -->
			
			<div id="blogs" class="tabbox">
				
				<ul class="tab-menu">
					<li><a href="#frischeblogs" class="selected">Frische Blogs</a><span class="infopopup" title="Zuletzt erstellte Blogs, Neue oben - nicht-&ouml;ffentliche Blogs ausgeschlossen"></span></li>
					<li><a href="#active">Aktivste Blogs</a><span class="infopopup" title="Aktivste Blogs in unserem Dienst im Hinblick auf Artikel-Anzahl, Blog mit meisten Artikeln oben - Haupt-Blog und nicht-&ouml;ffentliche Blogs ausgeschlossen"></span></li>
				</ul>
				
				<p class="panel show" id="frischeblogs">
					<?php $recent_blogs=get_recent_blogs(8);
					foreach($recent_blogs as $recent_blog):
						$blog_url="";
						if( defined( "VHOST" ) && constant( "VHOST" ) == 'yes' )
							$blog_url="http://".$recent_blog->domain.$recent_blog->path;
						else
						$blog_url = "http://".$recent_blog->domain.$recent_blog->path;
						$blog_name = get_blog_option($recent_blog->blog_id,"blogname");
						$blog_description = get_blog_option($recent_blog->blog_id,"blogdescription")
					?>
					<a href="<?php echo $blog_url;?>"><?php echo $blog_name?><span><?php echo $blog_description ?></span></a>
				
				<?php endforeach;?>
				
				</p>
				
				<p class="panel" id="active">
					<?php $active_blogs = get_most_active_blogs(6, false);
					
					if( is_array( $active_blogs ) ) { 
						foreach( $active_blogs as $details ) { ?>
						<a href="http://<?php echo $details[ 'domain' ] . $details[ 'path' ] ?>"><?php echo get_blog_option( $details[ 'blog_id' ], 'blogname' ) ?>
						<span><?php echo get_blog_option($details['blog_id'],'blogdescription') ?></span>
						<span class="post-count"><?php echo get_blog_option($details['blog_id'],'post_count') ?> Artikel</span></a>
					
					<?php }
					} ?>
				</p>
					
			</div><!-- end #blogs -->
			
			<?php $stats = get_sitestats(); ?>
			<p id="statistic">Bisher tummeln sich <a href="/blogliste/" title="Blog-Liste Blogs@MLU"><span><?php echo $stats['blogs']?></span> Blogs</a> und <span><?php echo $stats['users']?></span> Benutzer in unserem Dienst. Insgesamt wurden <span><?php mlublogs_post_count(); ?></span> Artikel verfasst.</p>

		</div><!-- end #community -->
		</div><!-- end #community-wrap -->
		
	<?php } ?>
		
	<div id="wrapper" class="clearfix">