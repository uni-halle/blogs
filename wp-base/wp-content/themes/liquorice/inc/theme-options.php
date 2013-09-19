<?php

add_action( 'admin_init', 'liquorice_options_init' );
add_action( 'admin_menu', 'liquorice_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function liquorice_options_init(){
	register_setting( 'liquorice_options', 'liquorice_theme_options', 'liquorice_options_validate' );
}

/**
 * Load up the menu page
 */
function liquorice_options_add_page() {
	add_theme_page( __( 'Theme Options', 'liquorice' ), __( 'Theme Options', 'liquorice' ), 'edit_theme_options', 'theme_options', 'liquorice_options_do_page' );
}

/**
 * Create the options page
 */
function liquorice_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;
	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'liquorice' ) . "</h2>"; ?>
		<p><?php _e( 'These options will let you setup the three social icons at the top of the theme. You can enter the URLs of your profiles to have the icons show up.', 'liquorice' ); ?></p>
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'liquorice' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'liquorice_options' ); ?>
			<?php $options = get_option( 'liquorice_theme_options' ); ?>

			<table class="form-table">

				<?php
				/**
				 * RSS Icon
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Hide RSS Icon?', 'liquorice' ); ?></th>
					<td>
						<input id="liquorice_theme_options[hiderss]" name="liquorice_theme_options[hiderss]" type="checkbox" value="1" <?php checked( '1', $options['hiderss'] ); ?> />
						<label class="description" for="liquorice_theme_options[hiderss]"><?php _e( 'Hide the RSS feed icon?', 'liquorice' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * Facebook Icon
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Enter your Facebook URL', 'liquorice' ); ?></th>
					<td>
						<input id="liquorice_theme_options[facebookurl]" class="regular-text" type="text" name="liquorice_theme_options[facebookurl]" value="<?php esc_attr_e( $options['facebookurl'] ); ?>" />
						<label class="description" for="liquorice_theme_options[facebookurl]"><?php _e( 'Leave blank to hide Facebook Icon', 'liquorice' ); ?></label>
					</td>
				</tr>
				
				<?php
				/**
				 * Twitter URL
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Enter your Twitter URL', 'liquorice' ); ?></th>
					<td>
						<input id="liquorice_theme_options[twitterurl]" class="regular-text" type="text" name="liquorice_theme_options[twitterurl]" value="<?php esc_attr_e( $options['twitterurl'] ); ?>" />
						<label class="description" for="liquorice_theme_options[twitterurl]"><?php _e( 'Leave blank to hide Twitter Icon', 'liquorice' ); ?></label>
					</td>
				</tr>
				
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'liquorice' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function liquorice_options_validate( $input ) {

	// Our checkbox value is either 0 or 1
	if ( ! isset( $input['hiderss'] ) )
		$input['hiderss'] = null;
		$input['hiderss'] = ( $input['hiderss'] == 1 ? 1 : 0 );

	// Our text option must be safe text with no HTML tags
	$input['twitterurl'] = wp_filter_nohtml_kses( $input['twitterurl'] );
	$input['facebookurl'] = wp_filter_nohtml_kses( $input['facebookurl'] );
	
	// Encode URLs
	$input['twitterurl'] = esc_url_raw( $input['twitterurl'] );
	$input['facebookurl'] = esc_url_raw( $input['facebookurl'] );
	
	return $input;
}