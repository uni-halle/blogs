<?php
/**
 * Theme update Documentation Page, Code kindly used from editor Theme by Mike at Arraythemes.com
 *
 * @package origin
 */

/**
 * Redirect to Getting Started page on theme activation
 */
function alienwp_redirect_on_activation() {
	global $pagenow;

	if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {

		wp_redirect( admin_url( "themes.php?page=origin-getting-started" ) );

	}
}
add_action( 'admin_init', 'alienwp_redirect_on_activation' );

/**
 * Load Getting Started styles in the admin
 *
 * since 1.0.0
 */
function alienwp_start_load_admin_scripts() {

	// Load styles only on our page
	global $pagenow;
	if( 'themes.php' != $pagenow )
		return;

	/**
	 * Getting Started scripts and styles
	 *
	 * @since 1.0
	 */

	// Getting Started javascript
	wp_enqueue_script( 'alienwp-getting-started', get_template_directory_uri() . '/admin/getting-started/getting-started.js', array( 'jquery' ), '1.0.0', true );

	// Getting Started styles
	wp_register_style( 'alienwp-getting-started', get_template_directory_uri() . '/admin/getting-started/getting-started.css', false, '1.0.0' );
	wp_enqueue_style( 'alienwp-getting-started' );

	// Thickbox
	add_thickbox();
}
add_action( 'admin_enqueue_scripts', 'alienwp_start_load_admin_scripts' );

/**
 * Adds a menu item for the Getting Started page
 *
 * since 1.0.0
 */
function license_menu() {
	add_theme_page(
		__( 'Theme Help', 'origin' ),
		__( 'Theme Help', 'origin' ),
		'manage_options',
		'origin-getting-started',
		'alienwp_getting_started'
	);
}
add_action( 'admin_menu', 'license_menu' );

/**
 * Outputs the markup used on the theme license page.
 *
 * since 1.0.0
 */
function alienwp_getting_started() {

	/**
	 * Retrieve help file and theme update changelog
	 *
	 * since 1.0.0
	 */

	// Theme info
	$theme = wp_get_theme( 'origin' );
?>

		<div class="wrap getting-started">
			<h2 class="notices"></h2>
			<div class="intro-wrap">
				<div class="intro">
					<h3><?php esc_html_e( 'Origin', 'origin' ); ?></h3>
					<h4><?php esc_html_e( 'Origin Theme Documentation', 'origin' ); ?></h4>
				</div>
			</div>

			<div class="panels">
				<ul class="inline-list">
					<li class="current"><a id="help-tab" href="#"><?php esc_html_e( 'Help File', 'origin' ); ?></a></li>
					<li><a id="themes-tab" href="#"><?php esc_html_e( 'More Themes &amp; Support', 'origin' ); ?></a></li>
				</ul>

				<div id="panel" class="panel">

					<!-- Help file panel -->
					<div id="help-panel" class="panel-left visible">

						<!-- Grab feed of help file -->
						<?php
							include_once( ABSPATH . WPINC . '/feed.php' );

							$rss = fetch_feed( 'https://alienwp.com/documentation/origin-theme-documentation/feed/?withoutcomments=1' );

							if ( ! is_wp_error( $rss ) ) :
							    $maxitems = $rss->get_item_quantity( 1 );
							    $rss_items = $rss->get_items( 0, $maxitems );
							endif;

							if ( ! is_wp_error( $rss ) ) :
								$rss_items_check = array_filter( $rss_items );
							endif;
						?>

						<!-- Output the feed -->
						<?php if ( is_wp_error( $rss ) || empty( $rss_items_check ) ) : ?>
							<p><?php esc_html_e( 'This help file feed seems to be temporarily down. You can always view the help file on AlienWP in the meantime.', 'origin' ); ?> <a href="https://alienwp.com/documentation/oxygen-theme-documentation/" title="View help file"><?php esc_html_e( 'Oxygen Help File &rarr;', 'origin' ); ?></a></p>
						<?php else : ?>
						    <?php foreach ( $rss_items as $item ) : ?>
								<?php echo $item->get_content(); ?>
						    <?php endforeach; ?>
						<?php endif; ?>
					</div>

					<!-- More themes -->
					<div id="themes-panel" class="panel-left">
						<div class="theme-intro">
							<div class="theme-intro-left">
								<p><?php _e( 'Minimal and simple, yet powerful and efficient themes for serious site owners and professional WordPress users. Get our entire collection of 18 Themes and Full Support for just $59', 'origin' ); ?></p>
							</div>
							<div class="theme-intro-right">
								<a class="button-primary club-button" href="<?php echo esc_url('https://alienwp.com/ref/9/'); ?>"><?php esc_html_e( 'Visit AlienWP', 'origin' ); ?> &rarr;</a>
							</div>
						</div>

						<div class="theme-list">
						<!-- Grab feed of help file -->
						<?php
							include_once( ABSPATH . WPINC . '/feed.php' );

							$rss = fetch_feed( 'https://alienwp.com/all-themes/feed/?withoutcomments=1' );

							if ( ! is_wp_error( $rss ) ) :
							    $maxitems = $rss->get_item_quantity( 1 );
							    $rss_items = $rss->get_items( 0, $maxitems );
							endif;

							if ( ! is_wp_error( $rss ) ) :
								$rss_items_check = array_filter( $rss_items );
							endif;
						?>

						<!-- Output the feed -->
						<?php if ( is_wp_error( $rss ) || empty( $rss_items_check ) ) : ?>
							<p><?php esc_html_e( 'This page feed seems to be temporarily down. You can always view the themes on AlienWP in the meantime.', 'origin' ); ?> <a href="https://alienwp.com/themes/" title="View help file"><?php esc_html_e( 'Oxygen Help File &rarr;', 'origin' ); ?></a></p>
						<?php else : ?>
						    <?php foreach ( $rss_items as $item ) : ?>
								<?php echo $item->get_content(); ?>
						    <?php endforeach; ?>
						<?php endif; ?>

						</div><!-- .theme-list -->
					</div><!-- .panel-left updates -->

					<div class="panel-right"><img src="<?php echo get_template_directory_uri() . '/admin/getting-started/logo.png'; ?>" class="alienlogo"/>
						<div class="panel-aside panel-club">
                        
							<a href="<?php echo esc_url('https://alienwp.com/ref/9/'); ?>"><img src="<?php echo get_template_directory_uri() . '/admin/getting-started/club.jpg'; ?>" alt="<?php esc_html_e( 'Join the Theme Club!', 'origin' ); ?>" /></a>

							<div class="panel-club-inside">
                            
								<h4><?php esc_html_e( 'Get our full collection of beautiful responsive themes for one price.', 'origin' ); ?></h4>

								<p><?php esc_html_e( 'Our Theme club costs just $59 and includes all 18 of our current themes, any new themes we release and amazing customer support should you need a hand with your theme.', 'origin' ); ?></p>

								<a class="button-primary club-button" href="<?php echo esc_url('https://alienwp.com/ref/9/'); ?>"><?php esc_html_e( 'Visit AlienWP', 'origin' ); ?> &rarr;</a>
							</div>
						</div>
					</div><!-- .panel-right -->
				</div><!-- .panel -->
			</div><!-- .panels -->
		</div><!-- .getting-started -->

	<?php
}