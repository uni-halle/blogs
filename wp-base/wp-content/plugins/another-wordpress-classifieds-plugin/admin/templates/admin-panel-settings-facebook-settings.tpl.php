<div class="metabox-holder">
	<div class="postbox">
		<h3 class="hndle"><span><?php _e('Facebook Integration', 'AWPCP') ?></span></h3>
		<div class="inside">
			<div>
				<?php echo str_replace( '<a>',
					'<a href="https://developers.facebook.com/docs/web/tutorials/scrumptious/register-facebook-application/" target="_blank">',
					__( 'This configuration allows you to post ads to Facebook. You must have a Facebook Application created to use this feature. Read <a>How to Register and Configure a Facebook Application.</a>', 'AWPCP' ) );
				?>
			</div>
		</div>
	</div>
	<?php if ( $current_step > 1 && $this->get_current_action() != 'diagnostics' ): ?>
	<div class="postbox">
		<h3 class="hndle"><span><?php _e( 'Diagnostics', 'AWPCP' ) ?></span></h3>
		<div class="inside">
			<form  method="post">
				<?php wp_nonce_field( 'awpcp-facebook-settings' ); ?>
				If you are having trouble with Facebook integration, click "Diagnostics" to check your settings.
				<input type="submit" class="button-secondary" name="diagnostics" value="<?php _e( 'Diagnostics', 'AWPCP' ); ?>" />
			</form>
		</div>
	</div>
	<?php endif; ?>
</div>

<?php echo awpcp_print_message( __( 'Facebook Integration is in beta. Please let us know if you experience any problems or have feature suggestions.', 'AWPCP' ), array( 'updated', 'below-h2', 'highlight' ) ); ?>

<h3><?php _e( 'Facebook Integration', 'AWPCP' ); ?></h3>

<?php if ( isset( $errors ) && $errors ): ?>
<?php foreach ( $errors as $err ): ?>
	<?php echo awpcp_print_error( $err ); ?>
<?php endforeach; ?>
<?php endif; ?>

<form  method="post" class="facebook-integration-settings">
	<?php wp_nonce_field( 'awpcp-facebook-settings' ); ?>

	<div class="section app-config">
		<h4><?php _e( '1. Application Information', 'AWPCP'); ?></h4>

		<p><?php
			echo str_replace( '<a>',
					 		  '<a href="https://developers.facebook.com/apps/" target="_blank">',
						 	  __( 'You can find your application information in the <a>Facebook Developer Apps</a> page.', 'AWPCP' ) );
		?></p>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label><?php _e( 'App Id', 'AWPCP' ); ?></label>
				</th>
				<td>
					<input type="text" name="app_id" value="<?php echo esc_attr( $config['app_id'] ); ?>" /> <br />
					<span class="description">
						<?php _e( 'An application identifier associates your site, its pages, and visitor actions with a registered Facebook application.', 'AWPCP' ); ?>
					</span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e( 'App Secret', 'AWPCP' ); ?></label>
				</th>
				<td>
					<input type="text" name="app_secret" value="<?php echo esc_attr( $config['app_secret'] ); ?>" /> <br />
					<span class="description">
						<?php _e( 'An application secret is a secret shared between Facebook and your application, similar to a password.', 'AWPCP' ); ?>
					</span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" value="<?php _e( 'Save App Settings', 'AWPCP' ); ?>" class="button-primary" name="save_config" />
				</td>
			</tr>
		</table>
	</div>

	<div class="section user-token <?php echo $current_step < 2 ? 'disabled' : ''; ?>">
		<h4><?php _e( '2. User Authorization', 'AWPCP'); ?></h4>
		<?php if ( $current_step < 2 ): ?>
		<p><?php _e( 'This settings section is not available yet. Please fill out required fields above and save your settings.', 'AWPCP' ); ?></p>
		<?php else: ?>
			<p><?php _e( 'AWPCP needs to get an authorization token from Facebook to work correctly. You\'ll be redirected to Facebook to login. AWPCP does not store or obtain any personal information from your profile.', 'AWPCP' ); ?></p>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label><?php _e( 'User Access Token', 'AWPCP' ); ?></label>
					</th>
					<td>
						<input type="text" name="user_token" value="<?php echo esc_attr( $config['user_token'] ); ?>" /> <?php echo str_replace( '<a>', '<a href="' . $login_url . '">', __(' or <a>obtain an access token from Facebook</a>.', 'AWPCP' ) ); ?><br />
						<span class="description">
							<?php _e( 'You can manually enter your user access token (if you know it) or log in to Facebook to get one.', 'AWPCP' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value="<?php _e( 'Save Token Value', 'AWPCP' ); ?>" class="button-primary" name="save_config" />
					</td>
				</tr>
			</table>
		<?php endif; ?>
	</div>

	<div class="section page-token <?php echo $current_step < 3 ? 'disabled' : ''; ?>">
		<h4><?php _e( '3. Page Selection', 'AWPCP'); ?></h4>
		<?php if ( $current_step < 3 ): ?>
		<p><?php _e( 'This settings section is not available yet. Please fill out required fields above and save your settings.', 'AWPCP' ); ?></p>
		<?php else: ?>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label><?php _e( 'Facebook Page', 'AWPCP' ); ?></label>
					</th>
					<td>
						<?php if ( $pages ): ?>
							<?php foreach( $pages as $page ): ?>
								<label>
									<input type="radio" name="page" value="<?php echo esc_attr( $page['id'] . '|' . $page['access_token'] ); ?>" <?php echo $page['access_token'] == $config['page_token'] ? 'checked="checked"' : ''; ?> /> <?php echo esc_html( $page['name'] ); ?> <?php echo isset( $page['profile'] ) && $page['profile'] ? __( '(Your own profile page)', 'AWPCP' ) : ''; ?>
								</label><br />
							<?php endforeach; ?>
						<?php else: ?>
							<p><?php _e( 'There are no Facebook pages available for you to select. Please make sure you are connected to the internet and have granted the Facebook application the correct permissions. Click "Diagnostics" if you are in doubt.', 'AWPCP' ); ?></p>
						<?php endif; ?>
					</td>
				</tr>
				<?php if ( $config['page_token'] ): ?>
				<tr>
					<th scope="row">
						<label><?php _e( 'Page Token (not editable)', 'AWPCP' ); ?></label>
					</th>
					<td>
						<input type="text" disabled="disabled" editable="false" value="<?php echo $config['page_token']; ?>" size="60" />
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td colspan="2">
						<input type="submit" value="<?php _e( 'Save Page Selection', 'AWPCP' ); ?>" class="button-primary" name="save_config" />	
					</td>
				</tr>
			</table>		
		<?php endif; ?>
	</div>

</form>
