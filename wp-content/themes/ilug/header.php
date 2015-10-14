<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>
<?php
	global $page, $paged;
	
	if ( is_home() || is_front_page() ) {
		echo "ILUG - Institut für Leistungsdiagnostik und Gesundheitsförderung";
	} else {
			// @todo qtranslate-x verwenden
			// echo qtrans_use($q_config['language'], $post->post_title, true) . " - ";
	
		// wp_title( '|', true, 'right' );
 
		echo "b bloginfo ";

		bloginfo( 'name' );	
 
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";
 
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'starkers' ), max( $paged, $page ) );
	}
    ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
 
<script src="<?php bloginfo('template_directory'); ?>/js/modernizr-1.6.min.js"></script>
 
<?php
    /* We add some JavaScript to pages with the comment form
     * to support sites with threaded comments (when in use).
     */
    if ( is_singular() && get_option( 'thread_comments' ) )
        wp_enqueue_script( 'comment-reply' );
 
    /* Always have wp_head() just before the closing </head>
     * tag of your theme, or you will break many plugins, which
     * generally use this hook to add elements to <head> such
     * as styles, scripts, and meta tags.
     */
    wp_head();
?>
	<script src='<?php bloginfo('template_directory'); ?>/js/sizeswitch.js'></script>
	
	<!-- Links sollen immer das eigene Fenster nutzen, sofern nicht anders angegeben -->
	<base target="_self">
	<!-- Viewport fuer Mobiloptimierung setzen -->
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
	
	<!-- Scripte fuer Maps einbinden -->
    <script src="https://ilug.uni-halle.de/wp-content/themes/ilug/js/OpenLayers.js"></script>
    <script src='https://ilug.uni-halle.de/wp-content/themes/ilug/js/openmaps.js'></script>
</head>
 
<body id='body' onresize='resizer();' <?php body_class(); ?>>
    <div id="page">
	