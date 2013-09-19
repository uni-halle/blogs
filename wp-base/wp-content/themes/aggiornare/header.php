<?php
/**
 * @package WordPress
 * @subpackage Aggiornare
 */
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style.php" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lte IE 8]>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/iestyle.css" type="text/css" media="screen" />
<![endif]-->

<style type="text/css">
img, div { behavior: url(<?php bloginfo('stylesheet_directory'); ?>/images/iepngfix.htc) }
</style> 

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="preloaded-images">
   <img src="<?php bloginfo('stylesheet_directory'); ?>/images/logoHover.jpg" width="1" height="1" alt="Image 01" />
</div>
<div id="color">

	<div id="wrapper">
	
		<div id="top">
	
			<div id="siteIdentification">
			<?php $tagline = get_option('aggiornare_tagline'); ?>
				<h1><a href="<?php bloginfo('url'); ?>"><?php echo bloginfo('name'); ?></a></h1>
				<?php if($tagline) { echo '<p class="tagline">';
				bloginfo('description');
				echo '</p>'; } ?>
			</div>
			
			<div id="main">
			
				<div id="navigation">
					<ul>
						<?php 
						$navigation = get_option('aggiornare_navigation');
						if(!$navigation) {
							wp_list_pages('title_li=&sort_column=menu_order');
						} else {
							wp_list_pages('title_li=&sort_column=menu_order&depth=3&include='.$navigation.'');
						} ?>
					</ul>
				</div>