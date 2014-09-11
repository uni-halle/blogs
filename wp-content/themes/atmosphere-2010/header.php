<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'atmosphere' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	wp_head();
?>
</head>

<body <?php body_class(); ?>>

<?php global $themeOptions; ?>

<?php /* Add in our Right Hand Social Menu */ ?>
<?php get_template_part( 'social', 'vertical' ); ?>

<div id="wrapper" class="hfeed">
	<div id="wrapper-jpg"><!-- Add our Background Image -->

		<div id="header">
			<div id="masthead">

				<?php /* Get our second navigation bar */ ?>
				<?php get_template_part( 'navigation', 2 ); ?>
				
				<?php $shortname = 'drcms'; ?>
				<?php $style = ''; ?>
				<?php if( $themeOptions[$shortname. '_hide_titles'] ) $style = ' style="display: none;" '; ?>
				<div id="branding" role="banner">
					<?php $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
					<<?php echo $heading_tag; ?> id="site-title" <?php echo $style; ?>>
						<span>
							<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</span>
					</<?php echo $heading_tag; ?>>
					<div id="site-description" <?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
				</div><!-- #branding -->

				<?php if( !$themeOptions[$shortname. '_hide_lower_menu'] ) : ?>				
					<div id="access" role="navigation">
					  	<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'atmosphere' ); ?>"><?php _e( 'Skip to content', 'atmosphere' ); ?></a></div>
						<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
					</div><!-- #access -->
				<?php endif; ?>

			</div><!-- #masthead -->
		</div><!-- #header -->
		</div><!--wrapper-jpg -->

	<div id="main">
		<!-- Added to keep columns inline -->
		<div id="inner-wrapper">