<?php if ( ( $_GET['page'] != 'wptouch-admin-wizard' ) ) { ?>
	<?php if ( wptouch_show_renewal_notice() ) { ?>
		<div class="update-nag">
			<p><?php _e( 'Your WPtouch Pro license has expired. Renew now at a discount to continue receiving product updates and support.', 'wptouch-pro' ); ?><a href="http://www.wptouch.com/renew/?utm_campaign=renew-in-product&utm_medium=web&utm_source=wptouch" target="_blank"><?php _e( 'Renew Now','wptouch-pro' ); ?></a></p>
		</div>
	<?php } else { ?>
		<?php if ( wptouch_should_show_license_nag() && $_GET['page'] != 'wptouch-admin-license' ) { ?>
			<div class="error">
				<p>
				  	<?php echo sprintf( __( 'This copy of %s is currently unlicensed.', 'wptouch-pro' ), 'WPtouch Pro' ); ?>
				  	<?php if ( wptouch_should_show_activation_nag() ) { ?>
						<a href="<?php echo wptouch_get_license_activation_url(); ?>" class="button">
							<?php echo sprintf( __( 'Add a license %s', 'wptouch-pro' ), '&raquo;'); ?>
						</a>
				  	<?php } ?>
				</p>
			</div>
		<?php } ?>
	<?php } ?>
<?php } ?>

<form  id="wptouch-settings-form" method="post" action=""<?php if ( strpos( $_SERVER['REQUEST_URI'], 'wptouch-admin-license' ) !== false ) echo ' autocomplete="off"'; ?>>
	<?php if ( ( $_GET['page'] != 'wptouch-admin-wizard' ) ) { ?>
	<h2 class="logo-title">
		<img src="<?php echo WPTOUCH_ADMIN_URL; ?>/images/title-icon.png" alt="Logo image" />
		<?php echo WPTOUCH_PRODUCT_NAME; ?>
		<?php echo WPTOUCH_VERSION; ?>
		<span class="title-arrow">›</span>
		<?php if ( is_rtl() ) { ?>
		<span class="title-grey"><bdi><?php wptouch_admin_the_menu_friendly_name(); ?></bdi></span>
		<?php } else { ?>
		<span class="title-grey"><?php wptouch_admin_the_menu_friendly_name(); ?></span>
		<?php } ?>

		<div id="admin-spinner" class="wpt-spinner"></div>
		<?php if ( ( $_GET['page'] != 'wptouch-admin-touchboard' ) && ( $_GET['page'] != 'wptouch-admin-license' ) ) { ?>
			<input type="submit" name="wptouch-preview-theme" id="wptouch-preview-theme" class="button-primary" value="<?php _e( 'Preview Theme', 'wptouch-pro' ); ?>" data-url="<?php wptouch_bloginfo( 'url' ); ?>/?wptouch_preview_theme=enabled" />
			<input type="hidden" name="wptouch-admin-nonce" value="<?php echo wp_create_nonce( 'wptouch-post-nonce' ); ?>" />
		<?php } ?>
	</h2>
	<?php } ?>

	<div id="wptouch-settings-area" class="<?php wptouch_admin_panel_classes( array( 'wptouch-clearfix' ) ); ?>">
		<?php if ( is_array( $panel_options ) ) { ?>
			<nav id="wptouch-admin-menu" <?php if ( count ( $panel_options ) <= 0 ) echo 'style="display: none;"'; ?>>
				<?php if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'wptouch-multisite' ) { ?>
					<h2><?php _e( 'Setup', 'wptouch-pro' ); ?></h2>
					<ul>
						<li><a href="#" class="wptouch-addon-multisite<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'wptouch-addon-multisite' ) ) { echo ' active'; } ?>" data-page-slug="wptouch-addon-multisite"><?php _e( 'General Options', 'wptouch-pro' ); ?></a></li>
					</ul>
					<?php if ( wptouch_has_multisite_support_licensed() ) { ?>
					<h2><?php _e( 'Tools', 'wptouch-pro' ); ?></h2>
					<ul>
						<li><a href="#" class="wptouch-addon-deployment<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'wptouch-addon-deployment' ) ) { echo ' active'; } ?>" data-page-slug="wptouch-addon-deployment"><?php _e( 'Deployment', 'wptouch-pro' ); ?></a></li>
					</ul>
					<?php } ?>
				<?php } else { ?>
				<?php
						$hide_keys = array( 'Updates Available', 'General', 'Compatibility', 'Customize Theme', 'Devices', 'Themes', 'Extensions', 'Customizer', 'Theme Settings', 'Menu Settings', 'Subscribe to Newsletter' );
						if ( !defined( 'WPTOUCH_IS_FREE' ) ) { $hide_keys[] = 'Go Pro'; }
						$show_keys = array_diff( array_keys( $panel_options ), $hide_keys );
					?>
					<h2><?php _e( 'Setup', 'wptouch-pro' ); ?></h2>
					<ul>
						<?php if ( !defined( 'WPTOUCH_IS_FREE' ) && ( !wptouch_is_controlled_network() || ( wptouch_is_controlled_network() && is_network_admin() ) ) ) { ?>
							<?php global $wptouch_pro; ?>
							<?php if ( $wptouch_pro->theme_upgrades_available() || $wptouch_pro->extension_upgrades_available() || wptouch_is_update_available() != WPTOUCH_VERSION ) { ?>
								<li><a href="#" class="updates-available<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'updates-available' ) ) { echo ' active'; } ?>" data-page-slug="updates-available"><?php _e( 'Updates Available', 'wptouch-pro' ); ?></a></li>
							<?php } ?>
						<?php } ?>

						<?php if ( defined( 'WPTOUCH_IS_FREE' ) )  { ?>
							<li><a href="#" class="setup-general-general<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-general-general' ) ) { echo ' active'; } ?>" data-page-slug="setup-general-general"><?php _e( 'General', 'wptouch-pro' ); ?></a></li>
							<li><a href="#" class="<?php wptouch_multisite_page_classes( 'site-compat' ); ?> setup-compat<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-compat' ) ) { echo ' active'; } ?>" data-page-slug="setup-compat"><?php _e( 'Site Compatibility', 'wptouch-pro' ); ?></a></li>
							<li><a href="#" class="setup-devices<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-devices' ) ) { echo ' active'; } ?>" data-page-slug="setup-devices"><?php _e( 'Devices', 'wptouch-pro' ); ?></a></li>
							<li><a href="#" class="menu-icons-manage-icon-sets<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'menu-icons-manage-icon-sets' ) ) { echo ' active'; } ?>" data-page-slug="menu-icons-manage-icon-sets"><?php _e( 'Menu Settings', 'wptouch-pro' ); ?></a></li>
								<?php if ( wptouch_admin_use_customizer() ) { ?>
									<li><a href="#" class="<?php wptouch_multisite_page_classes( 'themes' ); ?> foundation-page-theme-customizer<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'foundation-page-theme-customizer' ) ) { echo ' active'; } ?>" data-page-slug="foundation-page-theme-customizer"><?php _e( 'Customize Theme', 'wptouch-pro' ); ?></a></li>
                                <li><a href="#" class="free-newsletter-signup<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'free-newsletter-signup' ) ) { echo ' active'; } ?>" data-page-slug="free-newsletter-signup"><?php _e( 'Subscribe to Newsletter', 'wptouch-pro' ); ?></a></li>
								<?php } ?>
						<?php } elseif ( !is_network_admin() ) { ?>
							<?php if ( wptouch_can_show_page( 'general' ) ) { ?>
							<li><a href="#" class="<?php wptouch_multisite_page_classes( 'general' ); ?> setup-general-general<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-general-general' ) ) { echo ' active'; } ?>" data-page-slug="setup-general-general"><?php _e( 'General', 'wptouch-pro' ); ?></a></li>
							<?php } ?>

							<?php if ( wptouch_can_show_page( 'site-compat' ) ) { ?>
							<li><a href="#" class="<?php wptouch_multisite_page_classes( 'site-compat' ); ?> setup-compat<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-compat' ) ) { echo ' active'; } ?>" data-page-slug="setup-compat"><?php _e( 'Site Compatibility', 'wptouch-pro' ); ?></a></li>
							<?php } ?>

							<?php if ( wptouch_can_show_page( 'devices' ) ) { ?>
							<li><a href="#" class="<?php wptouch_multisite_page_classes( 'devices' ); ?> setup-devices<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-devices' ) ) { echo ' active'; } ?>" data-page-slug="setup-devices"><?php _e( 'Devices', 'wptouch-pro' ); ?></a></li>
							<?php } ?>

							<?php if ( wptouch_can_show_page( 'menu' ) ) { ?>
							<li><a href="#" class="<?php wptouch_multisite_page_classes( 'menu' ); ?> menu-icons-manage-icon-sets<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'menu-icons-manage-icon-sets' ) ) { echo ' active'; } ?>" data-page-slug="menu-icons-manage-icon-sets"><?php _e( 'Menu Settings', 'wptouch-pro' ); ?></a></li>
							<?php } ?>
						<?php } ?>
					</ul>

					<?php if ( !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
						<?php if ( wptouch_can_show_page( 'themes' ) ) { ?>
						<h2><?php _e( 'Themes', 'wptouch-pro' ); ?></h2>
						<ul>
							<li><a href="#" class="<?php wptouch_multisite_page_classes( 'themes' ); ?> setup-themes-browser<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-themes-browser' ) ) { echo ' active'; } ?>" data-page-slug="setup-themes-browser"><?php _e( 'Themes', 'wptouch-pro' ); ?></a></li>
							<?php if ( !is_network_admin() ) { ?>
								<?php if ( isset( $panel_options[ 'Theme Settings' ]->sections ) && count( $panel_options[ 'Theme Settings' ]->sections ) > 0 ) { ?>
									<li><a href="#" class="<?php wptouch_multisite_page_classes( 'themes' ); ?> foundation-page-theme-settings<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'foundation-page-theme-settings' ) ) { echo ' active'; } ?>" data-page-slug="foundation-page-theme-settings"><?php _e( 'Theme Settings', 'wptouch-pro' ); ?></a></li>
								<?php } ?>
								<?php if ( wptouch_admin_use_customizer() ) { ?>
									<li><a href="#" class="<?php wptouch_multisite_page_classes( 'themes' ); ?> foundation-page-theme-customizer<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'foundation-page-theme-customizer' ) ) { echo ' active'; } ?>" data-page-slug="foundation-page-theme-customizer"><?php _e( 'Customize Theme', 'wptouch-pro' ); ?></a></li>
								<?php } ?>
							<?php } ?>
						</ul>
						<?php } ?>

						<?php if ( wptouch_can_show_page( 'extensions' ) ) { ?>
						<h2><?php _e( 'Extensions', 'wptouch-pro' ); ?></h2>
						<ul>
							<li><a href="#" class="<?php wptouch_multisite_page_classes( 'extensions' ); ?> setup-addons-browser<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-addons-browser' ) ) { echo ' active'; } ?>" data-page-slug="setup-addons-browser"><?php _e( 'Extensions', 'wptouch-pro' ); ?></a></li>
							<?php
								array_multisort(array_map('strtolower', $show_keys), $show_keys);
								foreach( $show_keys as $page_name ) {
								$page_info = $panel_options[ $page_name ];
							?>
								<?php if ( isset( $page_info->sections ) && is_array( $page_info->sections ) && count( $page_info->sections ) ) { ?>
									<?php if ( is_network_admin() ) { ?>
										<?php if ( $page_info->slug == 'wptouch-addon-multisite' || $page_info->slug == 'wptouch-addon-multisite-deploy' ) { ?>
											<li><a href="#" class="<?php wptouch_multisite_page_classes( 'extensions' ); ?> <?php echo $page_info->slug; ?><?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == $page_info->slug ) ) { echo ' active'; } ?>" data-page-slug="<?php echo $page_info->slug; ?>"><?php echo $page_name; ?></a></li>
										<?php } ?>
									<?php } else { ?>
										<?php if ( $page_info->slug != 'wptouch-addon-multisite' && $page_info->slug != 'wptouch-addon-multisite-deploy' ) { ?>
											<li><a href="#" class="<?php wptouch_multisite_page_classes( 'extensions' ); ?> <?php echo $page_info->slug; ?><?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == $page_info->slug ) ) { echo ' active'; } ?>" data-page-slug="<?php echo $page_info->slug; ?>"><?php echo $page_name; ?></a></li>
										<?php } ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						</ul>
						<?php } ?>
					<?php } else { ?>
						<h2><?php _e( 'Available for WPtouch Pro', 'wptouch-pro' ); ?></h2>
						<ul>
							<li><a href="#" class="<?php wptouch_multisite_page_classes( 'themes' ); ?> setup-themes-browser<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-themes-browser' ) ) { echo ' active'; } ?>" data-page-slug="setup-themes-browser"><?php _e( 'Themes', 'wptouch-pro' ); ?></a></li>
							<li><a href="#" class="<?php wptouch_multisite_page_classes( 'extensions' ); ?> setup-addons-browser<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == 'setup-addons-browser' ) ) { echo ' active'; } ?>" data-page-slug="setup-addons-browser"><?php _e( 'Extensions', 'wptouch-pro' ); ?></a></li>
						</ul>
					<?php } ?>
				<?php } ?>
			</nav>
		<?php } ?>

		<div id="wptouch-settings-content" class="wptouch-clearfix">
		<?php if ( wptouch_admin_is_custom_page() ) { ?>
			<?php wptouch_admin_render_custom_page(); ?>
		<?php } else { ?>
			<?php if ( is_array( $panel_options ) ) { unset( $panel_options[ 'Customizer' ] ); if ( !defined( 'WPTOUCH_IS_FREE' ) ) { unset( $panel_options[ 'Go Pro' ] ); } ?>
				<?php foreach( $panel_options as $page_name => $page_info ) { ?>
					<?php $page_info = apply_filters( 'wptouch_settings_page_before_render', $page_info, $page_name ); ?>
					<div class="wptouch-settings-sub-page" class="wptouch-clearfix" id="<?php echo $page_info->slug; ?>" style="<?php if ( isset( $_COOKIE['wptouch-4-admin-menu'] ) && ( $_COOKIE['wptouch-4-admin-menu'] == $page_info->slug ) ) { echo 'display: block;'; } else { echo 'display: none;'; } ?>">
					<?php foreach( $page_info->sections as $section ) { ?>
						<?php if ( wptouch_section_has_visible_settings( $section ) ) { ?>
							<div class="wptouch-section"<?php if ( $section->name ) { ?> id="section-<?php echo $section->slug; ?>"<?php } ?>>
							<?php if ( $section->name ) { ?>
								<h3><?php echo $section->name; ?> </h3>
								<?php if ( $section->description ) { ?>
								<p class="description"><?php echo $section->description; ?></p>
								<?php } ?>
								<ul class="padded">
								<?php foreach( $section->settings as $setting ) { ?>
									<?php if ( wptouch_should_show_setting( $setting ) ) { ?>
										<li class="wptouch-setting setting-<?php echo $setting->type; ?>" id="setting-<?php echo wptouch_convert_to_class_name( $setting->name ); ?>">
											<?php wptouch_admin_render_setting( $setting ); ?>
										</li>
									<?php } ?>
								<?php } ?>
								</ul>
							<?php } else { ?>
								<?php // custom areas ?>
								<?php foreach( $section->settings as $setting ) { ?>
									<?php wptouch_admin_render_special_setting( $setting ); ?>
								<?php } ?>
							<?php } ?>
							</div><!-- section -->
						<?php } ?>
					<?php } ?>
					<?php do_action( 'wptouch_after_settings_subpage_' . $page_info->slug ); ?>
					</div><!-- wptouch-settings-sub-page -->
				<?php } ?>
			<?php } ?>
			</div>
		<?php } ?>
		<?php if ( defined( 'WPTOUCH_IS_FREE' ) ) { ?>
			<input type="submit" name="wptouch-reset-3" id="reset" class="reset-button button-primary" value="<?php _e( 'Reset Settings', 'wptouch-pro' ); ?>" />
		<?php } ?>
		</div>
	</div>

</form>
