<?php
/**
 * The welcome screen content
 * @since Graphene 2.0
 */
function graphene_welcome() {
  	?>
	<div class="wrap">
		<div class="header">
			<img src="<?php echo GRAPHENE_ROOTURI; ?>/admin/images/graphene-logo.png" width="188" height="120" alt="Graphene" />
			<h2>Welcome to Graphene 2.0</h2>
			<p>Thank you for updating to the latest version! This is our biggest update yet for the theme, <br />designed to bring your website to more audience than ever.</p>
		</div>
		<div class="content">
			<div class="section">
				<img class="alignleft" src="<?php echo GRAPHENE_ROOTURI; ?>/admin/welcome/images/laptop-640.jpg" width="640" height="364" />

				<h3>Built for the modern world</h3>
				<p>Graphene 2.0 includes the most significant styling update since the theme's first public release, seven years ago.</p>
				<p>This time, we've placed our focus on readability and improving the user interface so that your website will look elegant, easy to navigate, and above all, easily readable and accessible.</p>
				<p>The design updates also leverage on the features and capabilities of modern browsers that simply were not available previously, such as vector-based icon fonts and flexbox CSS layout.</p>
			</div>

			<div class="section">
				<img class="alignright" src="<?php echo GRAPHENE_ROOTURI; ?>/admin/welcome/images/phone-320.jpg" width="320" height="200" />
				<img class="alignright" src="<?php echo GRAPHENE_ROOTURI; ?>/admin/welcome/images/tablet-320.jpg" width="320" height="200" />

				<h3>Adapts to any screen</h3>
				<p>Graphene 2.0 will automatically adapt to the various screen sizes, from the biggest PC monitors to the smallest phones. Your content will be optimised and look great on all devices, including search engines.</p>
				<p>We've also reworked major portions of the theme's code and image assets to ensure visuals stay sharp on high-resolution devices.</p>
			</div>

			<div class="section">
				<img class="alignleft" src="<?php echo GRAPHENE_ROOTURI; ?>/admin/welcome/images/customizer.gif" width="640" height="298" />

				<h3>Customize settings in real time</h3>
				<p>See the results of your customization in real time, including changing column widths and colours. Get the results you desired quickly and intuitively.</p>
				<p><a class="button" href="<?php echo admin_url( 'customize.php' ); ?>"><i class="fa fa-paint-brush"></i> Start customizing</a></p>
			</div>

			<div class="section">
				<img class="alignright" src="<?php echo GRAPHENE_ROOTURI; ?>/admin/welcome/images/tablet-gallery.jpg" width="640" height="439" />

				<h3>A host of many improvements</h3>
				<p>We've made many other smaller but significant improvements to this version too.</p>
				<p>For example, image galleries now look much better, navigation menus can now have icons and description, posts and comments navigation are much more intuitive.</p>
				<p>Not only that, new features have made it into Graphene 2.0 as well. You can now have author biography in single posts, pages with children have contextual navigation, and author profile page looks better with more relevant information.</p>
				<p>Under the hood, old and inefficient code has been replaced with better and improved alternatives. Scripts and fonts queueing and loading have been improved for better performance. Element styling has also been made generally more uniform and easier to customize.</p>
				<p><a class="button" href="https://www.graphene-theme.com/graphene-theme/changelog/">Full list of changes <i class="fa fa-long-arrow-right"></i></a></p>
			</div>

			<div class="section" style="padding: 0;border-bottom:1px solid #ddd;">
				<div class="panel-50">
					<p class="icon"><i class="fa fa-comments-o"></i></p>
					<p>In case you need any help with the new version, or have ideas and feedback to provide, head on to our support forum.</p>
					<p><a class="button" href="https://forum.graphene-theme.com/">Get community support</a></p>
				</div>
				<div class="panel-50">
					<p class="icon"><i class="fa fa-heart-o"></i></p>
					<p>Graphene theme is a labour of love, but it could not survive on love alone. Help support the theme and ensure its continuous development.</p>
					<p><a class="button" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CBWQL2T6B797J"><i class="fa fa-paypal"></i> Make a contribution</a></p>
				</div>
			</div>
		</div>
	</div>
  	<?php
}


/**
 * Registers the welcome screen admin page
 * @since Graphene 2.0
 */
function graphene_welcome_screen() {
	$welcome_screen = add_theme_page( 'Welcome to Graphene 2.0', 'Graphene 2.0', 'read', 'graphene-welcome-screen', 'graphene_welcome' );
	add_action( 'admin_print_styles-' . $welcome_screen, 'graphene_welcome_style' );
}
add_action( 'admin_menu', 'graphene_welcome_screen' );


/**
 * Add the stylesheet for the welcome page
 * @since Graphene 2.0
 */
function graphene_welcome_style(){
	wp_enqueue_style( 'graphene-welcome', GRAPHENE_ROOTURI . '/admin/welcome/welcome.css' );
	wp_enqueue_style( 'font-awesome', GRAPHENE_ROOTURI . '/fonts/font-awesome/css/font-awesome.min.css' );
}


/**
 * Remove the welcome screen from admin menus
 */
function graphene_welcome_remove_menus() {
    remove_submenu_page( 'themes.php', 'graphene-welcome-screen' );
}
add_action( 'admin_head', 'graphene_welcome_remove_menus' );


/**
 * Redirect to welcome screen when appropriate
 * @since Graphene 2.0
 */
function graphene_do_welcome_screen_redirect() {
	if ( ! get_transient( '_graphene_welcome_screen_redirect' ) ) return;

	delete_transient( '_graphene_welcome_screen_redirect' );
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) return;
	
	wp_safe_redirect( add_query_arg( array( 'page' => 'graphene-welcome-screen' ), admin_url( 'themes.php' ) ) );
}
add_action( 'admin_init', 'graphene_do_welcome_screen_redirect' );