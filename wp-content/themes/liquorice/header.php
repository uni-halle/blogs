<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class=" ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="ie7"> <![endif]-->
<!--[if (gt IE 7)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?> <?php if ( !wp_title('', true, 'left') ); { ?> | <?php bloginfo('description'); ?> <?php } ?></title>
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link href='http://fonts.googleapis.com/css?family=Lobster&subset=latin' rel='stylesheet' type='text/css'>
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>" />
	<link rel="alternate" type="application/atom+xml" title="Atom 1.0" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
<?php
    wp_head();
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
  ?>
</head>

<body <?php body_class(); ?> >

<div id="canvas">  
<?php $options = get_option( 'liquorice_theme_options' ); ?>

	<div id="social-icons">
		<?php if ( $options['twitterurl'] != '' ) : ?>
			<a href="<?php echo $options['twitterurl']; ?>" class="twitter"><?php _e( 'Twitter', 'liquorice' ); ?></a>
		<?php endif; ?>

		<?php if ( $options['facebookurl'] != '' ) : ?>
			<a href="<?php echo $options['facebookurl']; ?>" class="facebook"><?php _e( 'Facebook', 'liquorice' ); ?></a>
		<?php endif; ?>

		<?php if ( ! $options['hiderss'] ) : ?>
			<a href="<?php bloginfo( 'rss2_url' ); ?>" class="rss"><?php _e( 'RSS Feed', 'liquorice' ); ?></a>
		<?php endif; ?>
	</div><!-- #social-icons-->


 
    <ul class="skip">
      <li><a href="#main-menu">Skip to navigation</a></li>
      <li><a href="#primaryContent">Skip to main content</a></li>
      <li><a href="#secondaryContent">Skip to secondary content</a></li>
      <li><a href="#footer">Skip to footer</a></li>
    </ul>

    <div id="header-wrap">
   		<div id="header"> 
	<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'h4'; ?>
				<<?php echo $heading_tag; ?> id="site-title">
					<span>
						<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</span>
				</<?php echo $heading_tag; ?>>
				<div id="site-description"><?php bloginfo( 'description' ); ?></div>   
      <!--by default your pages will be displayed unless you specify your own menu content under Menu through the admin panel-->
		<div class="main-menu" id="main-menu"><?php wp_nav_menu( array( 'sort_column' => 'menu_order', 'container_class' => 'menu-header' ) ); ?></div>
  	 </div> <!-- end #header-->
      
 </div> <!-- end #header-wrap-->
 

    <div id="primaryContent">