<?php if (!$download): ?>
	<?php $page_id = 'awpcp-admin-debug' ?>
	<?php $page_title = awpcp_admin_page_title( __( 'Debug', 'another-wordpress-classifieds-plugin' ) ); ?>

	<?php include(AWPCP_DIR . '/admin/templates/admin-panel-header.tpl.php') ?>
<?php endif ?>

		<?php echo awpcp_html_admin_second_level_heading( array( 'content' => __( 'Are you seeing 404 Not Found errors?', 'another-wordpress-classifieds-plugin' ) ) ); ?>

		<?php $message = __( "If you are seeing multiple 404 Not Found errors in your website, it is possible that some Rewrite Rules are missing or corrupted. Please click the button bellow to navigate to the <permalinks-settings-link>Permalinks Settings</a> page. Opening that page in your browser will flush the Rewrite Rules in your site. WordPress will then ask all installed and active plugins to register their rules and those 404 Not Found errors should be gone. If that's not the case, please contact <support-link>customer support</a>.", 'another-wordpress-classifieds-plugin' ); ?>
		<?php $message = str_replace( '<support-link>', '<a href="http://awpcp.com/contact/">', $message ); ?>
		<?php $message = str_replace( '<permalinks-settings-link>', '<a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '">', $message ); ?>
		<p><?php echo $message ?></p>

		<p><a class="button-primary" href="<?php echo esc_url( admin_url( 'options-permalink.php' ) ); ?>"><?php echo _x( 'Flush Rewrite Rules', 'debug page', 'another-wordpress-classifieds-plugin' ); ?></a></p>

		<?php echo awpcp_html_admin_second_level_heading( array( 'content' => __( 'Debug Information', 'another-wordpress-classifieds-plugin' ) ) ); ?>

		<?php $msg = _x('This information can help AWPCP Developers to debug possible problems. If you are submitting a bug report please <strong><a href="%s">Download the Debug Information</a></strong> and attach it to your bug report or take a minute to copy the information below to <a href="http://fpaste.org" target="_blank">http://fpaste.org</a> and provide the resulting URL in your report.', 'debug page', 'another-wordpress-classifieds-plugin') ?>
		<p><?php echo sprintf( $msg, esc_url( add_query_arg( 'download', 'debug page', awpcp_current_url() ) ) ); ?></p>

		<?php $title_pages = _x('AWPCP Pages', 'debug page', 'another-wordpress-classifieds-plugin') ?>
		<?php $title_php_info = _x('PHP Info', 'debug page', 'another-wordpress-classifieds-plugin') ?>
		<?php $title_settings = _x('AWPCP Settings', 'debug page', 'another-wordpress-classifieds-plugin') ?>
		<?php $title_rules = _x('Rewrite Rules', 'debug page', 'another-wordpress-classifieds-plugin') ?>

		<h2 class="nav-tab-wrapper">
			<a class="nav-tab" href="#awpcp-debug-awpcp-pages"><?php echo $title_pages; ?></a>
			<a class="nav-tab" href="#awpcp-debug-php-info"><?php echo $title_php_info; ?></a>
			<a class="nav-tab" href="#awpcp-debug-awpcp-settings"><?php echo $title_settings; ?></a>
			<a class="nav-tab" href="#awpcp-debug-rewrite-rules"><?php echo $title_rules; ?></a>
		</h2>

		<div class="metabox-holder">

		<div id="awpcp-debug-awpcp-pages" class="postbox">
		    <?php echo awpcp_html_postbox_handle( array( 'heading_tag' => 'h3', 'content' => $title_pages ) ); ?>
		    <div class="inside">
				<table>
					<thead>
						<tr>
							<th><?php _e('Stored ID', 'another-wordpress-classifieds-plugin') ?></th>
							<th><?php _e('Reference', 'another-wordpress-classifieds-plugin') ?></th>
							<th><?php _e('Title', 'another-wordpress-classifieds-plugin') ?></th>
						</tr>
					</thead>
					<tbody>
				<?php foreach( $plugin_pages_info as $page_ref => $info ): ?>
					<?php $page = isset( $plugin_pages[ $info[ 'page_id' ] ] ) ? $plugin_pages[ $info[ 'page_id' ] ] : null; ?>
						<tr>
							<td class="align-center"><?php echo $info['page_id']; ?></td>
							<td class="align-center"><?php echo $page_ref; ?></td>
							<td><?php echo $page ? $page->post_title : __( 'Page not found', 'another-wordpress-classifieds-plugin' ); ?></td>
						</tr>
				<?php endforeach ?>
					</tbody>
				</table>
		    </div>
	    </div>

		<div id="awpcp-debug-awpcp-settings" class="postbox">
		    <?php echo awpcp_html_postbox_handle( array( 'heading_tag' => 'h3', 'content' => $title_settings ) ); ?>
		    <div class="inside">
		    	<table>
					<thead>
						<tr>
							<th><?php _e('Option Name', 'another-wordpress-classifieds-plugin') ?></th>
							<th><?php _e('Option Value', 'another-wordpress-classifieds-plugin') ?></th>
						</tr>
					</thead>
					<tbody>
				<?php foreach($options as $name => $value): ?>
                    <?php if ( ! $debug_info->blacklisted( $name ) ): ?>
                    <?php $value = $debug_info->sanitize( $name, $value ) ?>
						<tr>
                            <th scope="row"><?php echo $name; ?></th>
                            <td><?php echo esc_html( $value ); ?></td>
						</tr>
                    <?php endif; ?>
                <?php endforeach ?>
					</tbody>
				</table>
		    </div>
	    </div>

		<div id="awpcp-debug-rewrite-rules" class="postbox">
		    <?php echo awpcp_html_postbox_handle( array( 'heading_tag' => 'h3', 'content' => $title_rules ) ); ?>
		    <div class="inside">
				<table>
					<thead>
						<tr>
							<th><?php _e('Pattern', 'another-wordpress-classifieds-plugin') ?></th>
							<th><?php _e('Replacement', 'another-wordpress-classifieds-plugin') ?></th>
						</tr>
					</thead>
					<tbody>
				<?php foreach($rules as $pattern => $rule): ?>
						<tr>
							<td><?php echo $pattern ?></td>
							<td><?php echo $rule ?></td>
						</tr>
				<?php endforeach ?> 
					</tbody>
				</table>
		    </div>
	    </div>

		<div id="awpcp-debug-php-info" class="postbox">
		    <?php echo awpcp_html_postbox_handle( array( 'heading_tag' => 'h3', 'content' => $title_php_info ) ); ?>
		    <div class="inside">
				<table>
					<tbody>
						<tr>
							<th scope="row"><?php _ex('PHP Version', 'debug page', 'another-wordpress-classifieds-plugin') ?></th>
							<td scope="row"><?php echo phpversion() ?></td>
						</tr>
						<tr>
							<th scope="row"><?php _ex('cURL', 'debug page', 'another-wordpress-classifieds-plugin') ?></th>
							<td><?php echo awpcp_get_curl_info(); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php _ex("cURL's alternate CA info (cacert.pem)", 'debug page', 'another-wordpress-classifieds-plugin') ?></th>
							<td><?php echo file_exists(AWPCP_DIR . '/cacert.pem') ? _x('Exists', 'alternate CA info for cURL', 'another-wordpress-classifieds-plugin') : _x('Missing', 'alternate CA info for cURL', 'another-wordpress-classifieds-plugin'); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php _ex('PayPal Connection', 'debug page', 'another-wordpress-classifieds-plugin') ?></th>
							<?php $response = awpcp_paypal_verify_received_data(array(), $errors) ?>
							<?php if ($response === 'INVALID'): ?>
							<td><?php _ex('Working', 'debug page', 'another-wordpress-classifieds-plugin')	?></td>
							<?php else: ?>
							<td>
								<?php _ex('Not Working', 'debug page', 'another-wordpress-classifieds-plugin') ?><br/>
								<?php foreach ( (array) $errors as $error ): ?>
								<?php echo $error ?><br/>
								<?php endforeach ?>
							</td>
							<?php endif ?>
						</tr>
					</tbody>
				</table>
		    </div>
	    </div>

	    </div>

<?php if (!$download): ?>
		</div><!-- end of .awpcp-main-content -->
	</div><!-- end of .page-content -->
</div><!-- end of #page_id -->
<?php endif ?>
