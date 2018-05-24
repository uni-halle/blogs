<div class="metabox-holder">

	<div class="postbox">
		<h3 class="hndle"><span><?php _e('Facebook Integration', 'another-wordpress-classifieds-plugin') ?></span></h3>
		<div class="inside">
			<div>
				<p><?php echo str_replace( '<a>',
					'<a href="https://developers.facebook.com/docs/web/tutorials/scrumptious/register-facebook-application/" target="_blank">',
					__( 'This configuration allows you to post ads to Facebook. You must have a Facebook Application created to use this feature. Read <a>How to Register and Configure a Facebook Application.</a>', 'another-wordpress-classifieds-plugin' ) );
				?></p>
                <p><?php esc_html_e( 'Add the following URL to the list of Valid OAuth Redirect URIs for the configuration of the Facebook Application:', 'another-wordpress-classifieds-plugin' ); ?></p>
                <pre><code><?php echo esc_html( $redirect_uri ); ?></code></pre>
			</div>
	     </div>
	</div>

	<?php if ( $current_step > 1 && $this->get_current_action() != 'diagnostics' ): ?>
	<div class="postbox">
		<h3 class="hndle"><span><?php _e( 'Diagnostics', 'another-wordpress-classifieds-plugin' ) ?></span></h3>
		<div class="inside">
			<form  method="post">
				<?php wp_nonce_field( 'awpcp-facebook-settings' ); ?>
				<?php echo __( 'If you are having trouble with Facebook integration, click "Diagnostics" to check your settings.', 'another-wordpress-classifieds-plugin' ); ?>
				<input type="submit" class="button-secondary" name="diagnostics" value="<?php _e( 'Diagnostics', 'another-wordpress-classifieds-plugin' ); ?>" />
			</form>
		</div>
	</div>
	<?php endif; ?>
</div>

<h3><?php _e( 'Facebook Integration', 'another-wordpress-classifieds-plugin' ); ?></h3>

<?php if ( isset( $errors ) && $errors ): ?>
<?php foreach ( $errors as $err ): ?>
	<?php echo awpcp_print_error( $err ); ?>
<?php endforeach; ?>
<?php endif; ?>

<form  method="post" class="facebook-integration-settings">
	<?php wp_nonce_field( 'awpcp-facebook-settings' ); ?>

	<div class="section app-config">
		<h4><?php _e( '1. Application Information', 'another-wordpress-classifieds-plugin'); ?></h4>

		<p><?php
			echo str_replace( '<a>',
					 		  '<a href="https://developers.facebook.com/apps/" target="_blank">',
						 	  __( 'You can find your application information in the <a>Facebook Developer Apps</a> page.', 'another-wordpress-classifieds-plugin' ) );
		?></p>

		<table class="form-table">
			<tr>
				<th scope="row">
					<label><?php _e( 'App Id', 'another-wordpress-classifieds-plugin' ); ?></label>
				</th>
				<td>
					<input type="text" name="app_id" value="<?php echo esc_attr( $config['app_id'] ); ?>" /> <br />
					<span class="description">
						<?php _e( 'An application identifier associates your site, its pages, and visitor actions with a registered Facebook application.', 'another-wordpress-classifieds-plugin' ); ?>
					</span>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label><?php _e( 'App Secret', 'another-wordpress-classifieds-plugin' ); ?></label>
				</th>
				<td>
					<input type="text" name="app_secret" value="<?php echo esc_attr( $config['app_secret'] ); ?>" /> <br />
					<span class="description">
						<?php _e( 'An application secret is a secret shared between Facebook and your application, similar to a password.', 'another-wordpress-classifieds-plugin' ); ?>
					</span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" value="<?php _e( 'Save App Settings', 'another-wordpress-classifieds-plugin' ); ?>" class="button-primary" name="save_config" />
				</td>
			</tr>
		</table>
	</div>

	<div class="section user-token <?php echo $current_step < 2 ? 'disabled' : ''; ?>">
		<h4><?php _e( '2. User Authorization', 'another-wordpress-classifieds-plugin'); ?></h4>
		<?php if ( $current_step < 2 ): ?>
		<p><?php _e( 'This settings section is not available yet. Please fill out required fields above and save your settings.', 'another-wordpress-classifieds-plugin' ); ?></p>
		<?php else: ?>
			<p><?php _e( 'AWPCP needs to get an authorization token from Facebook to work correctly. You\'ll be redirected to Facebook to login. AWPCP does not store or obtain any personal information from your profile.', 'another-wordpress-classifieds-plugin' ); ?></p>

			<?php
			/*
				Choosing Public is important because:

				- http://stackoverflow.com/a/19653226/201354
				- https://github.com/drodenbaugh/awpcp/issues/1288#issuecomment-134198377
			*/ ?>
			<p><?php _e( "Please choose Public as the audience for posts made by the application, even if you are just testing the integration. Facebook won't allow us to post content in some cases if you choose something else.", 'another-wordpress-classifieds-plugin' ); ?></p>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label><?php _e( 'User Access Token', 'another-wordpress-classifieds-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="user_token" value="<?php echo esc_attr( $config['user_token'] ); ?>" /> <?php echo str_replace( '<a>', '<a href="' . $login_url . '">', __(' or <a>obtain an access token from Facebook</a>.', 'another-wordpress-classifieds-plugin' ) ); ?><br />
						<span class="description">
							<?php _e( 'You can manually enter your user access token (if you know it) or log in to Facebook to get one.', 'another-wordpress-classifieds-plugin' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value="<?php _e( 'Save Token Value', 'another-wordpress-classifieds-plugin' ); ?>" class="button-primary" name="save_config" />
					</td>
				</tr>
			</table>
		<?php endif; ?>
	</div>

	<div class="section page-token <?php echo $current_step < 3 ? 'disabled' : ''; ?>">
		<h4><?php _e( '3. Page and Group Selection', 'another-wordpress-classifieds-plugin'); ?></h4>
		<?php if ( $current_step < 3 ): ?>
		<p><?php _e( 'This settings section is not available yet. Please fill out required fields above and save your settings.', 'another-wordpress-classifieds-plugin' ); ?></p>
		<?php else: ?>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label><?php _e( 'Facebook Page', 'another-wordpress-classifieds-plugin' ); ?></label>
					</th>
					<td>
						<?php if ( $pages ): ?>
								<label>
									<input type="radio" name="page" value="none" <?php echo empty( $config['page_id'] ) ? 'checked="checked"' : ''; ?> /> <?php echo __( 'None (Do not sent Ads to a Facebook Page)', 'another-wordpress-classifieds-plugin' ); ?>
								</label><br />
							<?php foreach( $pages as $page ): ?>
								<label>
									<input type="radio" name="page" value="<?php echo esc_attr( $page['id'] . '|' . $page['access_token'] ); ?>" <?php echo $page['id'] == $config['page_id'] ? 'checked="checked"' : ''; ?> /> <?php echo esc_html( $page['name'] ); ?> <?php echo isset( $page['profile'] ) && $page['profile'] ? __( '(Your own profile page)', 'another-wordpress-classifieds-plugin' ) : ''; ?>
								</label><br />
							<?php endforeach; ?>
						<?php else: ?>
							<p><?php _e( 'There are no Facebook pages available for you to select. Please make sure you are connected to the internet and have granted the Facebook application the correct permissions. Click "Diagnostics" if you are in doubt.', 'another-wordpress-classifieds-plugin' ); ?></p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php _e( 'Facebook Group', 'another-wordpress-classifieds-plugin' ); ?></label>
					</th>
					<td>
						<?php if ( $groups ): ?>
								<?php $group_id = isset( $config['group_id'] ) ? $config['group_id'] : ''; ?>
								<label>
									<input type="radio" name="group" value="none" <?php echo empty( $group_id ) ? 'checked="checked"' : ''; ?> /> <?php echo __( 'None (Do not sent Ads to a Facebook Group)', 'another-wordpress-classifieds-plugin' ); ?>
								</label><br />
							<?php foreach( $groups as $group ): ?>
								<label>
									<input type="radio" name="group" value="<?php echo esc_attr( $group['id'] ); ?>" <?php echo $group['id'] == $group_id ? 'checked="checked"' : ''; ?> /> <?php echo esc_html( $group['name'] ); ?>
								</label><br />
							<?php endforeach; ?>
						<?php else: ?>
							<p><?php _e( 'There are no Facebook groups available for you to select. Please make sure you are connected to the internet and have granted the Facebook application the correct permissions. Click "Diagnostics" if you are in doubt.', 'another-wordpress-classifieds-plugin' ); ?></p>
                            <p><?php _e( 'As of April 4, 2018, all applications (new and existing) need to go through <a href="https://developers.facebook.com/docs/apps/review" rel="nofollow">App Review</a> in order to get access to the <a href="https://developers.facebook.com/docs/graph-api/reference/user/groups/" rel="nofollow">Groups API</a>. That means that unless you <a href="https://developers.facebook.com/docs/facebook-login/review" rel="nofollow">submit your app for review</a> (ask for the <code>user_managed_groups</code> permission), AWPCP won\'t be able to display the list of groups you manage and won\'t be able to post classifieds to those groups.', 'another-wordpress-classifieds-plugin' ); ?></p>
						<?php endif; ?>
					</td>
				</tr>
				<?php if ( $config['page_token'] ): ?>
				<tr>
					<th scope="row">
						<label><?php _e( 'Page Token (not editable)', 'another-wordpress-classifieds-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" disabled="disabled" editable="false" value="<?php echo $config['page_token']; ?>" size="60" />
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td colspan="2">
						<input type="submit" value="<?php _e( 'Save Page and Group Selection', 'another-wordpress-classifieds-plugin' ); ?>" class="button-primary" name="save_config" />
					</td>
				</tr>
			</table>
		<?php endif; ?>
	</div>

</form>
