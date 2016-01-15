<?php
	$settings = array(
		'bncid' => wptouch_get_settings( 'bncid' ),
		'foundation' => wptouch_get_settings( 'foundation' ),
		'wptouch_pro' => wptouch_get_settings( 'wptouch_pro')
	);

	global $wptouch_pro;

	if ( !defined( 'WPTOUCH_IS_FREE' ) )  {
		wptouch_check_api( true );
	}
?>

<script type="text/javascript">
	// Set the default
	var bncHasLicense = 0;
</script>

<div id="wptouch-wizard-container">

<?php if ( !defined( 'WPTOUCH_IS_FREE' ) && isset( $settings[ 'wptouch_pro' ]->upgrade_from_free ) && $settings[ 'wptouch_pro' ]->upgrade_from_free == true ) { ?>
		<details>Free Upgrade</details>
		<section class="free-upgrade">
			<h2><?php _e( 'Thanks for Upgrading!','wptouch-pro' ); ?></h2>
			<br />
			<img src="<?php echo WPTOUCH_ADMIN_URL . '/images/team.jpg'; ?>" style="max-width: 90%;" alt="<?php _e( 'Ornamental Image' ); ?>" />
			<p><?php _e( 'From all of us at BraveNewCode, we want to say thanks. You keep us working hard to make this product great.', 'wptouch-pro' ); ?></p>
			<p><?php _e( 'Click the arrow below to get started with setup.', 'wptouch-pro' ); ?></p>
		</section>
<?php } ?>

	<?php if ( wptouch_can_cloud_install() ) { ?>
		<details>Language</details>
		<section class="language">
			<h2><?php _e( 'Choose a Language','wptouch-pro' ); ?></h2>
			<p class="set-language"><?php _e( 'Setting Language...', 'wptouch-pro' ); ?></p>
			<img src="<?php echo WPTOUCH_ADMIN_URL . '/images/wizard/wizard-globe.png'; ?>" class="animated" alt="<?php _e( 'Ornamental Image' ); ?>" />
			<br />
			<select name="force_locale" id="language-force_locale">
				<?php
					$languages = wptouch_admin_get_languages();
					foreach ( $languages as $locale => $name ) {
						if ( is_network_admin() ) {
							$current_language = $settings[ 'wptouch_pro' ]->force_network_locale;
						} else {
							$current_language = $settings[ 'wptouch_pro' ]->force_locale;
						}
				?>
					<option value="<?php echo $locale; ?>" <?php if ( $locale == $current_language ) { echo ' selected'; } ?>><?php echo $name; ?></option>
				<?php } ?>
			</select>
		</section>
	<?php } ?>

	<details>Welcome</details>
	<section class="welcome">
		<img src="<?php echo WPTOUCH_ADMIN_URL . '/images/wizard/wizard-logo.png'; ?>" alt="<?php _e( 'Ornamental Image' ); ?>" />
		<h2><?php echo wptouchize_it( __( 'Welcome to WPtouch Pro 4','wptouch-pro' ) ); ?></h2>
		<p><?php echo wptouchize_it( __( 'The most elegant and powerful way to create great mobile experiences for your website visitors. This guide will help you configure important WPtouch Pro settings before customizing your theme.','wptouch-pro' ) ); ?></p>
	</section>

	<?php if ( !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
		<details>Activate License</details>
		<section class="activate-license">
			<?php if ( !wptouch_has_license() ) { ?>
				<span class="unlicensed">
					<h2><?php _e( 'Activate License','wptouch-pro' ); ?></h2>
					<p><?php _e( 'Adding a license is required to activate product updates and support, and provides access to more themes and extensions.','wptouch-pro' ); ?></p>

					<p class="license-issue" style="display:none"><!-- Ajax populated --></p>

					<table id="license-inputs">
						<tr>
							<td colspan="2">
								<div id="license-settings-area">
									<input type="text" placeholder="<?php _e( 'Account E-Mail Address', 'wptouch-pro'  ); ?>" id="license_email" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' ); ?>" value="<?php if ( $settings[ 'bncid' ]->bncid ) echo $settings[ 'bncid' ]->bncid; else ''; ?>" />

									<input type="text" placeholder="<?php _e( 'Product License Key', 'wptouch-pro' ); ?>" id="license_key" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' ); ?>" value="<?php if ( $settings[ 'bncid' ]->wptouch_license_key ) echo $settings[ 'bncid' ]->wptouch_license_key; else ''; ?>" />

									<div id="activate-license">
										<a href="#" class="activate button"><?php _e( 'Activate License', 'wptouch-pro' ); ?></a>
									</div>
								</div>
							</td>
						</tr>
					</table>
					<p><?php echo wptouchize_it( sprintf( __( 'You can find your license key in the purchase receipt e-mail we sent you when you purchased WPtouch Pro. %s If you have lost your license e-mail, or are having issues activating WPtouch Pro please send an e-mail to %s','wptouch-pro' ), '<br /><br />', '<a href="mailto:support@wptouch.com">support@wptouch.com</a>' ) ); ?></p>
				</span>
				<?php } ?>
				<span class="activated" <?php if ( !wptouch_has_license() ) { echo 'style="display:none"'; } ?>>
					<h2><?php _e( 'License Activation Complete','wptouch-pro' ); ?></h2>
					<i class="icon-ok-circle animated"></i>
					<p><?php echo sprintf( __( 'Thank you for purchasing a %s licence!', 'wptouch-pro' ), 'WPtouch Pro' ); ?></p>
				</span>
		</section>
		<?php if ( is_network_admin() ) { ?>
			<details>Multisite</details>
			<section class="multisite-panel">
				<h2><?php _e( 'Multisite Network Activation','wptouch-pro' ); ?></h2>
				<p><?php echo wptouchize_it( __( 'Since you have network activated WPtouch Pro, you can configure the way themes and extensions are controlled in your environment.', 'wptouch-pro' ) ); ?></p>
				<i class="icon-theme-settings"></i>
				<p><?php echo wptouchize_it( __( 'By default network activations of WPtouch Pro mean that the Network Admin controls theme and extension downloads and updates. You can also choose to allow sub-sites to behave like independent installations, and control themes and extensions themselves.','wptouch-pro' ) ); ?></p>
				<div class="checkbox-wrap">
					<?php echo wptouchize_it( __( 'Control theme and extension downloads and updates','wptouch-pro' ) ); ?>
					<input type="checkbox" class="checkbox" name="multisite_control" id="multisite_control" checked />
					<label for="multisite_control"></label>
				</div>
			</section>
		<?php } ?>

		<?php if ( !wptouch_can_cloud_install() && !is_network_admin() ) { ?>
			<details>Download Upload Themes</details>
			<section class="down-up-theme download-upload">
				<h2><?php _e( 'Download / Upload a Theme', 'wptouch-pro' ); ?></h2>
				<p><?php echo sprintf( __( 'We were unable to install automatically for you on this server. Instead, you can download and upload manually. To learn more about enabling automatic downloads, %sread this article on support.wptouch.com%s.', 'wptouch-pro' ), '<a href="//support.wptouch.com">', '</a>' ); ?></p>
				<ul>
					<?php
						$themes = $wptouch_pro->get_available_themes( true );
						$current_theme = $wptouch_pro->get_current_theme_info();
						foreach ( $themes as $theme ) {
					?>
						<?php if ( $theme->name != 'Foundation' && isset( $theme->download_url ) ) { ?>
							<li data-type="theme">
								<?php if ( isset( $theme->screenshot ) ) { ?><img src="<?php echo $theme->screenshot; ?>"><?php } ?>
								<h3><?php echo $theme->name; ?></h3>
								<?php if ( $theme->location == 'cloud' ) { ?>
									<button class="button download" data-download-link="<?php echo $theme->download_url; ?>"><?php _e( 'Download', 'wptouch-pro' ); ?></button>
								<?php } else { ?>
									<span><?php _e( 'Installed', 'wptouch-pro' ); ?></span>
								<?php } ?>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
				<p class="upload"><span data-loaded-text="<?php _e( 'Theme Uploaded!', 'wptouch-pro' ); ?>" data-text="<?php _e( 'Upload A Theme', 'wptouch-pro' ); ?>"><?php _e( 'Upload A Theme', 'wptouch-pro' ); ?></span><button id="upload_theme" class="button upload" data-text="<?php _e( 'Upload', 'wptouch-pro' ); ?>" data-loading-text="<?php _e( 'Uploading...', 'wptouch-pro' ); ?>"><?php _e( 'Upload', 'wptouch-pro' ); ?></button>
				<p class="small"><?php _e( 'You can activate your theme in the next step', 'wptouch-pro' ); ?></p>
			</section>
		<?php } ?>

		<?php if ( !is_network_admin() ) { ?>
			<details>Choose a Theme</details>
			<section class="choose-a-theme">
				<h2><?php _e( 'Choose a Theme', 'wptouch-pro' ); ?></h2>
				<p><?php echo sprintf( __( 'Select a theme which best suits your website. %s Themes are flexible— colors, fonts, layout and more can be changed for each theme.', 'wptouch-pro' ), '<br />' ); ?></p>
				<ul>
					<?php
						$themes = $wptouch_pro->get_available_themes( wptouch_can_cloud_install() );
						$current_theme = $wptouch_pro->get_current_theme_info();
						foreach ( $themes as $theme ) {
					?>
						<?php if ( $theme->name != 'Foundation' && !isset( $theme->buy_url ) && wptouch_is_network_available( 'theme', $theme ) ) { ?>
							<li class="one-theme <?php if ( ( $current_theme && $current_theme->name == $theme->name ) || count( $themes ) == 2 ) { echo 'active'; } ?>">
								<?php if ( isset( $theme->screenshot ) ) { ?><img src="<?php echo $theme->screenshot; ?>"><?php } ?>
								<h3><?php echo $theme->name; ?></h3>
								<input type="radio" name="activate_theme" value="<?php echo $theme->name; ?>" <?php if ( ( $current_theme && $current_theme->name == $theme->name ) || count( $themes ) == 2 ) { ?> checked<?php } ?>>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			</section>
		<?php } ?>

		<?php if ( !wptouch_can_cloud_install() && !is_network_admin() ) { ?>
			<details>Download Upload Extensions</details>
			<section class="down-up-extension download-upload">
				<h2><?php _e( 'Download / Upload Extensions', 'wptouch-pro' ); ?></h2>
				<p><?php echo sprintf( __( 'We were unable to install automatically for you on this server. Instead, you can download and upload manually. To learn more about enabling automatic downloads, %sread this article on support.wptouch.com%s.', 'wptouch-pro' ), '<a href="//support.wptouch.com">', '</a>' ); ?></p>
				<ul>
					<?php
						$extensions = $wptouch_pro->get_available_addons( true );
						foreach ( $extensions as $extension ) {
					?>
						<?php if ( isset( $extension->download_url ) ) { ?>
							<li data-type="extension">
								<?php if ( isset( $extension->screenshot ) ) { ?><img src="<?php echo $extension->screenshot; ?>"><?php } ?>
								<h3><?php echo $extension->name; ?></h3>
								<?php if ( $extension->location == 'cloud' ) { ?>
									<button class="button download" data-download-link="<?php echo $extension->download_url; ?>"><?php _e( 'Download', 'wptouch-pro' ); ?></button>
								<?php } else { ?>
									<span><?php _e( 'Installed', 'wptouch-pro' ); ?></span>
								<?php } ?>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
				<p class="upload"><span data-loaded-text="<?php _e( 'Extension Uploaded!', 'wptouch-pro' ); ?>" data-text="<?php _e( 'Upload Extension', 'wptouch-pro' ); ?>"><?php _e( 'Upload Extension', 'wptouch-pro' ); ?></span><button id="upload_extension" class="button upload" data-text="<?php _e( 'Upload', 'wptouch-pro' ); ?>" data-loading-text="<?php _e( 'Uploading...', 'wptouch-pro' ); ?>"><?php _e( 'Upload', 'wptouch-pro' ); ?></button>
				<p class="small"><?php _e( 'You can activate extensions in the next step', 'wptouch-pro' ); ?></p>
			</section>
		<?php } ?>

		<?php
			if ( !is_network_admin() ) {

				if ( wptouch_is_controlled_network() ) {
					$extensions = $wptouch_pro->get_available_addons( false );
				} else {
					$extensions = $wptouch_pro->get_available_addons( wptouch_can_cloud_install() );
				}

				if ( count( $extensions ) > 0 ) {
		?>
			<details>Activate Extensions</details>
			<section class="activate-extensions">
				<h2><?php _e( 'Activate Extensions', 'wptouch-pro' ); ?></h2>
				<p><?php echo wptouchize_it( __( 'Extensions are like mini-plugins that help you extend WPtouch Pro. You can also manage Extensions at any time from the WPtouch Pro settings panel.', 'wptouch-pro' ) ); ?></p>
				<ul>
					<?php
						foreach ( $extensions as $extension ) {
					?>
						<?php if (  !isset( $extension->buy_url ) ) { ?>
							<li>
								<?php if ( isset( $extension->screenshot ) ) { ?><img src="<?php echo $extension->screenshot; ?>"><?php } ?>
								<h3><?php echo $extension->name; ?></h3>
								<p><?php echo $extension->description; ?></p>
								<div class="checkbox-wrap">
									<input type="checkbox" class="checkbox" name="activate_extension[]" id="extension-<?php echo str_replace( ' ', '-', $extension->name ); ?>" value="<?php echo $extension->name; ?>" <?php if ( isset( $settings[ 'wptouch_pro' ]->active_addons ) && array_key_exists( $extension->name, $settings[ 'wptouch_pro' ]->active_addons ) ) { echo 'checked'; } ?>/>
									<label for="extension-<?php echo str_replace( ' ', '-', $extension->name ); ?>"></label>
								</div>
							</li>
						<?php } ?>
					<?php } ?>
				</ul>
			</section>
		<?php
				}
			}
		?>
	<?php } ?><!-- !WPTOUCH_IS_FREE -->

	<?php if ( !is_network_admin() ) { ?>
		<?php if ( !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
			<details>Home and Blog</details>
			<section class="home-blog">
				<?php $wp_homepage = get_option( 'show_on_front' ); ?>
					<?php if ( $wp_homepage == 'posts' ) { ?>
					<h2><?php _e( 'Posts Page', 'wptouch-pro' ); ?></h2>
				<?php } else { ?>
					<h2><?php _e( 'Home Page & Posts', 'wptouch-pro' ); ?></h2>
				<?php } ?>
				<p><?php echo wptouchize_it( __( 'By default WPtouch Pro uses your WordPress settings for its homepage and blog settings. You can choose a different setup here.', 'wptouch-pro' ) ); ?></p>

				<table>
					<tr>
					<?php if ( $wp_homepage == 'page' ) { ?>
						<td class="home_page">
							<img src="<?php echo WPTOUCH_ADMIN_URL . '/images/wizard/home.jpg'; ?>" alt="<?php _e( 'Ornamental Image' ); ?>" />
							<?php
								ob_start();
								wp_dropdown_pages();
								$contents = ob_get_contents();
								ob_end_clean();

								$contents = str_replace( "id='page_id'", 'id="homepage_redirect_wp_target"><option class="level-0" value="none" >' . __( 'WordPress Reading Settings', 'wptouch-pro' ) . '</option>', $contents );
								$contents = str_replace( "name='page_id'", 'name="homepage_redirect_wp_target"', $contents );
								if ( $settings[ 'wptouch_pro' ]->homepage_landing == 'select' ) {
									$value_string = 'value="' . $settings[ 'wptouch_pro' ]->homepage_redirect_wp_target . '"';
									$contents = str_replace( $value_string, $value_string . ' selected', $contents );
								}
								echo $contents;
							?>
							<p><?php _e( 'Mobile home page', 'wptouch-pro' ); ?></p>
						</td>
					<?php } ?>
						<td class="blog_page">
							<img src="<?php echo WPTOUCH_ADMIN_URL . '/images/wizard/blog.jpg'; ?>" alt="<?php _e( 'Ornamental Image' ); ?>" />
							<?php
								ob_start();
								wp_dropdown_pages();
								$latest_post_options = ob_get_contents();
								ob_end_clean();

								$latest_post_options = str_replace( "id='page_id'>", 'id="page_id"><option class="level-0" value="none" >' . __( 'WordPress Reading Settings', 'wptouch-pro' ) . '</option>', $latest_post_options );
								$latest_post_options = str_replace( 'page_id', 'latest_posts_page', $latest_post_options );

								echo $latest_post_options;
							?>
							<p><?php _e( 'Mobile posts listing', 'wptouch-pro' ); ?></p>
						</td>
					</tr>
				</table>
			</section>

			<details>Analytics</details>
			<section class="analytics">
				<h2><?php _e( 'Google Analytics', 'wptouch-pro' ); ?></h2>
				<p><?php echo wptouchize_it( __( 'WPtouch Pro supports analytics and usage statistics collection from Google Analytics.', 'wptouch-pro' ) ); ?></p>
				<p><?php echo wptouchize_it( __( 'WPtouch Pro can scan your site html and add it automatically.', 'wptouch-pro' ) ); ?></p>

				<i class="icon-chart-pie animated"></i>

				<button name="scan-analytics" id="wizard-scan-analytics" class="button" data-loading-text="<?php _e( 'Searching...', 'wptouch-pro' ); ?>"><?php _e( 'Scan for Analytics Code', 'wptouch-pro' ); ?></button>

				<div id="analytics_code" style="display: none">
					<input type="text" name="analytics_google_id" id="analytics_google_id">
				</div>
			</section>
		<?php } ?>

		<details>WPtouch Love</details>
		<section class="show-love">
			<h2><?php echo wptouchize_it( __( 'WPtouch Love', 'wptouch-pro' ) ); ?></h2>
			<p><?php echo wptouchize_it( __( "Share your love for WPtouch Pro by including a small text link in your website's footer. Including the link helps us find new customers who will love WPtouch Pro like you do!", 'wptouch-pro' ) ); ?></p>
			<i class="icon-heart animated infinite heartbeat"></i>
			<div class="checkbox-wrap">
				<?php echo wptouchize_it( __( 'Show powered by WPtouch Pro','wptouch-pro' ) ); ?>
				<input type="checkbox" class="checkbox" name="wptouch_love" id="wptouch_love" <?php if ( !defined( 'WPTOUCH_IS_FREE' ) )  { echo 'checked'; } ?> />
				<label for="wptouch_love"></label>
			</div>
		</section>
	<?php } ?>

	<details>WPtouch Support</details>
	<section class="step-9 support">
		<h2><?php echo wptouchize_it( __( 'WPtouch Pro Support', 'wptouch-pro' ) ); ?></h2>
		<p><?php echo wptouchize_it( __( 'WPtouch Pro 4 includes great support resources to help you get the most out of it.', 'wptouch-pro' ) ); ?></p>
		<table>
			<tr>
				<td>
					<a href="//support.wptouch.com" target="_blank"><img src="<?php echo WPTOUCH_ADMIN_URL; ?>/images/wptouch_support.png" alt="<?php _e( 'Ornamental Image' ); ?>" /></a>
					<h4><a href="//support.wptouch.com" target="_blank"><?php echo 'support.wptouch.com'; ?></a></h4>
					<p><?php _e( 'Find support guides, file tickets and access our knowledgebase here.', 'wptouch-pro' ); ?></p>
				</td>
				<td>
					<a href="//www.wptouch.com/account" target="_blank"><img src="<?php echo WPTOUCH_ADMIN_URL; ?>/images/wptouch_account.png" alt="<?php _e( 'Ornamental Image' ); ?>" /></a>
					<h4><a href="//www.wptouch.com/account" target="_blank"><?php echo 'wptouch.com/account'; ?></a></h4>
					<p><?php echo wptouchize_it( __( 'Access your WPtouch Pro account to manage your license, access support resources, or upgrade your license.', 'wptouch-pro' ) ); ?></p>
				</td>
			</tr>
		</table>
	</section>

	<details>Setup Complete</details>
	<section class="complete">
		<h2><?php _e( 'Setup Complete!', 'wptouch-pro' ); ?></h2>
		<?php if ( defined( 'WPTOUCH_IS_FREE' ) ) { ?>
			<p><?php echo wptouchize_it( __( ' Next you can configure your theme.', 'wptouch-pro' ) ); ?></p>
		<?php } elseif ( !is_network_admin() ) { ?>
			<p><?php echo wptouchize_it( __( ' Next you can customize your theme, or configure advanced settings.', 'wptouch-pro' ) ); ?></p>
		<?php } else { ?>
			<p><?php echo wptouchize_it( __( ' Next you can configure network settings.', 'wptouch-pro' ) ); ?></p>
		<?php } ?>
		<table>
			<tr>
				<?php if ( !is_network_admin() && !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
					<td>
						<i class="icon-theme-customizer"></i>
						<a href="<?php echo admin_url( 'customize.php' ); ?>" id="exit_wizard_customizer" class="button"><?php _e( 'Customize your Theme', 'wptouch-pro' ); ?></a>
					</td>
				<?php } ?>
				<td>
					<i class="icon-theme-settings"></i>
					<a href="<?php echo wptouch_admin_url( 'admin.php?page=wptouch-admin-general-settings' ); ?>" id="exit_wizard_settings" class="button">
						<?php echo wptouchize_it( __( 'Configure Settings', 'wptouch-pro' ) ); ?>
					</a>
				</td>
			</tr>
		</table>
	</section>

</div><!-- wizard-container -->