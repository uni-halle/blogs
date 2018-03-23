<?php global $graphene_settings;
/**
 * Register the settings for the theme. This is required for using the
 * WordPress Settings API.
*/
function graphene_settings_init(){
    // register options set and store it in graphene_settings db entry
	register_setting( 'graphene_options', 'graphene_settings', 'graphene_settings_validator' );
}
add_action( 'admin_init', 'graphene_settings_init' );


if ( ! file_exists( GRAPHENE_ROOTDIR . '/plus/setup.php' ) ) :
/**
 * This function generates the theme's options page in WordPress administration.
 *
 * @package Graphene
 * @since Graphene 1.0
*/
function graphene_options(){
    $theme_data = wp_get_theme( basename( GRAPHENE_ROOTDIR ) );
    ?>
    <div class="graphene-wrap">
    	<div class="header">
            <img src="<?php echo GRAPHENE_ROOTURI; ?>/admin/images/graphene-logo.png" alt="Graphene" width="115" height="83" />
            <h2>Graphene</h2>
            <p class="ver"><?php printf( __( 'Version %1$s by %2$s', 'graphene' ), $theme_data->Version, $theme_data->Author ); ?></p>
        </div>

        <div class="panels">
            <div class="panel panel-25 graphene-settings">
                <p class="icon"><i class="fa fa-sliders"></i></p>
                <p><a class="btn" href="<?php echo admin_url( 'customize.php' ); ?>"><?php _e( 'Customise Graphene settings', 'graphene' ); ?></a></p>
            </div>

            <div class="panel panel-25 support">
                <p class="icon"><i class="fa fa-comments-o"></i></p>
                <p>
                    <a class="btn" href="https://forum.graphene-theme.com/"><?php _e( 'Get community support', 'graphene' ); ?></a>
                    <a class="btn btn-purple" href="https://www.graphene-theme.com/priority-support/"><?php _e( 'Get Priority Support', 'graphene' ); ?></a>
                </p>
            </div>

            <div class="panel panel-50 graphene-plus">
                <?php 
                    $args = array(
                        'utm_campaign'  => 'graphene-plus',
                        'utm_source'    => 'graphene-theme',
                        'utm_content'   => '',
                        'utm_medium'    => 'options-page',
                    );
                    
                    $banners = array(
                        'plus-overview.jpg' => 'https://www.graphene-theme.com/graphene-plus/',
                        'plus-amp.jpg'      => 'https://www.graphene-theme.com/graphene-plus/accelerated-mobile-pages/',
                        'plus-bbpress.jpg'  => 'https://www.graphene-theme.com/graphene-plus/build-communities/',
                        'plus-options.jpg'  => 'https://www.graphene-theme.com/graphene-plus/stand-out-from-the-crowd/',
                    );
                    
                    $image = array_rand( $banners );
                    $args['utm_content'] = $image;

                    echo '<a href="' . add_query_arg( $args, $banners[$image] ) . '">';
                    echo '<img src="' . GRAPHENE_ROOTURI . '/admin/images/' . $image . '" alt="" width="871" height="300" />';
                    echo '</a>';
                ?>
            </div>

            <div class="panel panel-50 contribute">
                <p class="icon"><i class="fa fa-heart-o"></i></p>
                <p><?php _e( 'Graphene theme is a labour of love, but it could not survive on love alone. Help support the theme and ensure its continuous development.', 'graphene' ); ?></p>
                <p>
                    <a class="btn" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_btn_id=CBWQL2T6B797J"><i class="fa fa-paypal"></i> <?php _e( 'Make a contribution', 'graphene' ); ?></a>

                    <?php 
                        $locale = get_locale();
                        if ( $locale != 'en_US' ) {
                            require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
                            $translations = wp_get_available_translations();
                            
                            if ( $translations[$locale] ) {
                                $translation = $translations[$locale];
                                $lang_code = ( stripos( $translation['native_name'], '(' ) !== false ) ? strtolower( str_replace( '_', '-', $translation['language'] ) ) : $translation['iso'][1];
                                $url = esc_url( sprintf( 'https://translate.wordpress.org/locale/%s/default/wp-themes/graphene', $lang_code ) );
                                echo '<a class="btn purple" href="' . $url . '"><i class="fa fa-globe"></i>  ' . sprintf( __( 'Help translate into %s', 'graphene' ), $translation['native_name'] ) . '</a>';
                            }
                            
                        }
                    ?>
                </p>
            </div>

            <div class="panel panel-50 news">
                <h3><i class="fa fa-commenting-o"></i> <?php _e( 'News and announcement', 'graphene' ); ?></h3>
                <?php
                    $graphene_news = fetch_feed( array( 'https://www.graphene-theme.com/feed/rss2/' ) );
                    if ( ! is_wp_error( $graphene_news ) ) {
                        $maxitems = $graphene_news->get_item_quantity( 3 );
                        $news_items = $graphene_news->get_items( 0, $maxitems );
                    }
                ?>
                <ol class="graphene-news-list">
                    <?php if ( $maxitems == 0 ) : echo '<li>' . __( 'No news items.', 'graphene' ) . '</li>'; else :
                    foreach( $news_items as $news_item ) : ?>
                        <li>
                            <h4>
                                <a href='<?php echo esc_url( $news_item->get_permalink() ); ?>'><?php echo esc_html( $news_item->get_title() ); ?></a>
                                <span class="news-item-date"><?php echo $news_item->get_date( 'j F Y' ); ?></span>
                            </h4>
                            <?php echo wpautop( esc_html( graphene_truncate_words( strip_tags( $news_item->get_description() ), 40, ' [...]' ) ) ); ?>
                        </li>
                    <?php endforeach; endif; ?>
                </ol>
            </div>
        </div>
    </div>
    <?php    
}
endif;


/**
 * Admin notice
 */
function graphene_admin_notice_shortcodes() {
	global $graphene_settings, $current_user; $user_id = $current_user->ID;
	$screen = get_current_screen(); if ( $screen->id != $graphene_settings['hook_suffix'] ) return;

    /* Check that the user hasn't already clicked to ignore the message */
	if ( ! get_user_meta( $user_id, 'graphene_ignore_notice-shortcodes' ) ) : 
	?>
        <div class="update-nag">
        	<p>
            	<a class="alignright dismiss btn" style="margin-left: 20px" href="<?php echo esc_url( add_query_arg( 'graphene-dismiss-notice', 'shortcodes' ) ); ?>">Dismiss</a>
            	<strong>IMPORTANT:</strong> Message blocks and pullquote shortcodes have been removed from the Graphene theme as required by the WordPress Theme Review Team. To continue using them, download and install the <a href="http://www.graphene-theme.com/?ddownload=3403" target="_blank">Graphene Shortcodes</a> plugin. <a href="http://www.graphene-theme.com/announcement/graphene-1-9-4-update/">Learn more &raquo;</a>
            </p>
        </div>
	<?php
	endif;
}
// add_action( 'admin_notices', 'graphene_admin_notice_shortcodes' );


function graphene_dismiss_notice() {
	global $current_user; $user_id = $current_user->ID;
	
	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset( $_GET['graphene-dismiss-notice'] ) ) {
		 add_user_meta( $user_id, 'graphene_ignore_notice-' . trim( $_GET['graphene-dismiss-notice'] ), 'true', true );
	}
}
add_action( 'admin_init', 'graphene_dismiss_notice' );