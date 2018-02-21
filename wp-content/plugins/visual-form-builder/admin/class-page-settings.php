<?php

/**
 * Class that controls the Settings page view
 *
 */
class Visual_Form_Builder_Page_Settings {
	/**
	 * [display description]
	 * @return [type] [description]
	 */
	public function display() {
		$vfb_settings = get_option( 'vfb-settings' );
	?>
	<div class="wrap">
		<h2><?php _e( 'Settings', 'visual-form-builder' ); ?></h2>
		<form id="vfb-settings" method="post">
			<input name="action" type="hidden" value="vfb_settings" />
			<?php wp_nonce_field( 'vfb-update-settings' ); ?>
			<h3><?php _e( 'Global Settings', 'visual-form-builder' ); ?></h3>
			<p><?php _e( 'These settings will affect all forms on your site.', 'visual-form-builder' ); ?></p>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e( 'CSS', 'visual-form-builder' ); ?></th>
					<td>
						<fieldset>
						<?php
							$disable = array(
								'always-load-css'     => __( 'Always load CSS', 'visual-form-builder' ),
								'disable-css'         => __( 'Disable CSS', 'visual-form-builder' ),	// visual-form-builder-css
							);

							foreach ( $disable as $key => $title ) :

								$vfb_settings[ $key ] = isset( $vfb_settings[ $key ] ) ? $vfb_settings[ $key ] : '';
						?>
							<label for="vfb-settings-<?php echo $key; ?>">
								<input type="checkbox" name="vfb-settings[<?php echo $key; ?>]" id="vfb-settings-<?php echo $key; ?>" value="1" <?php checked( $vfb_settings[ $key ], 1 ); ?> /> <?php echo $title; ?>
							</label>
							<br>
						<?php endforeach; ?>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php _e( 'Form Output', 'visual-form-builder' ); ?></th>
					<td>
						<fieldset>
						<?php
							$disable = array(
								'address-labels'      => __( 'Place Address labels above fields', 'visual-form-builder' ),	// vfb_address_labels_placement
							);

							foreach ( $disable as $key => $title ) :

								$vfb_settings[ $key ] = isset( $vfb_settings[ $key ] ) ? $vfb_settings[ $key ] : '';
						?>
							<label for="vfb-settings-<?php echo $key; ?>">
								<input type="checkbox" name="vfb-settings[<?php echo $key; ?>]" id="vfb-settings-<?php echo $key; ?>" value="1" <?php checked( $vfb_settings[ $key ], 1 ); ?> /> <?php echo $title; ?>
							</label>
							<br>
						<?php endforeach; ?>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="vfb-settings-spam-points"><?php _e( 'Spam word sensitivity', 'visual-form-builder' ); ?></label></th>
					<td>
						<?php $vfb_settings['spam-points'] = isset( $vfb_settings['spam-points'] ) ? $vfb_settings['spam-points'] : '4'; ?>
						<input type="number" min="1" name="vfb-settings[spam-points]" id="vfb-settings-spam-points" value="<?php echo $vfb_settings['spam-points']; ?>" class="small-text" />
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="vfb-settings-max-upload-size"><?php _e( 'Max Upload Size', 'visual-form-builder' ); ?></label></th>
					<td>
						<?php $vfb_settings['max-upload-size'] = isset( $vfb_settings['max-upload-size'] ) ? $vfb_settings['max-upload-size'] : '25'; ?>
						<input type="number" name="vfb-settings[max-upload-size]" id="vfb-settings-max-upload-size" value="<?php echo $vfb_settings['max-upload-size']; ?>" class="small-text" /> MB
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label for="vfb-settings-sender-mail-header"><?php _e( 'Sender Mail Header', 'visual-form-builder' ); ?></label></th>
					<td>
						<?php
						// Use the admin_email as the From email
						$from_email = get_site_option( 'admin_email' );

						// Get the site domain and get rid of www.
						$sitename = strtolower( $_SERVER['SERVER_NAME'] );
						if ( substr( $sitename, 0, 4 ) == 'www.' )
							$sitename = substr( $sitename, 4 );

						// Get the domain from the admin_email
						list( $user, $domain ) = explode( '@', $from_email );

						// If site domain and admin_email domain match, use admin_email, otherwise a same domain email must be created
						$from_email = ( $sitename == $domain ) ? $from_email : "wordpress@$sitename";

						$vfb_settings['sender-mail-header'] = isset( $vfb_settings['sender-mail-header'] ) ? $vfb_settings['sender-mail-header'] : $from_email;
						?>
						<input type="text" name="vfb-settings[sender-mail-header]" id="vfb-settings-sender-mail-header" value="<?php echo $vfb_settings['sender-mail-header']; ?>" class="regular-text" />
						<p class="description"><?php _e( 'Some server configurations require an existing email on the domain be used when sending emails.', 'visual-form-builder' ); ?></p>
					</td>
				</tr>
			</table>

			<?php submit_button( __( 'Save', 'visual-form-builder' ), 'primary', 'submit', false ); ?>
		</form>
	</div> <!-- .wrap -->
	<?php
	}
}
