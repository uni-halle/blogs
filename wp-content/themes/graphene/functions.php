<?php
/**
 * Graphene WordPress Theme, Copyright 2010-2013 Syahir Hakim
 * Graphene is distributed under the terms of the GNU GPL version 3
 *
 * Graphene functions and definitions
 *
 * @package Graphene
 * @since Graphene 1.0
 */
define( 'GRAPHENE_ROOTDIR', dirname( __FILE__ ) );
define( 'GRAPHENE_ROOTURI', get_template_directory_uri() );

if ( file_exists( GRAPHENE_ROOTDIR . '/plus/setup.php' ) ) define( 'GRAPHENE_PLUS', true );
else define( 'GRAPHENE_PLUS', false );

 
/**
 * Before we do anything, let's get the mobile extension's init file if it exists
*/
$mobile_path = dirname( dirname( __FILE__ ) ) . '/graphene-mobile/includes/theme-plugin.php';
if ( file_exists( $mobile_path ) ) { include( $mobile_path ); }


/**
 * Load the various theme files
*/
global $graphene_settings;
require( GRAPHENE_ROOTDIR . '/admin/options-init.php'	);
require( GRAPHENE_ROOTDIR . '/admin/editor.php'	);
require( GRAPHENE_ROOTDIR . '/inc/scripts.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/utils.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/head.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/footer.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/menus.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/widgets.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/loop.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/social.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/stacks.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/infinite-scroll.php' 	);
require( GRAPHENE_ROOTDIR . '/inc/comments.php' 		);
require( GRAPHENE_ROOTDIR . '/inc/slider.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/panes.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/user.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/plugins.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/compat.php' 			);
require( GRAPHENE_ROOTDIR . '/inc/functions.php' 		);
require( GRAPHENE_ROOTDIR . '/inc/setup.php' 			);
require( GRAPHENE_ROOTDIR . '/vendors/menu-item-custom-fields/menu-item-custom-fields.php' );


/**
 * Graphene Plus
 */
if ( GRAPHENE_PLUS ) include( GRAPHENE_ROOTDIR . '/plus/setup.php' );