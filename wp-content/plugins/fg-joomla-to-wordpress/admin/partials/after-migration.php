		<table class="form-table">
			<tr>
				<th scope="row" colspan="2"><h3><?php _e('After the migration', 'fg-joomla-to-wordpress'); ?></h3></th>
			</tr>
			<tr>
				<th scope="row"><?php _e('If you have links between articles, you need to modify internal links.', 'fg-joomla-to-wordpress'); ?></th>
				<td>
					<form id="form_modify_links" method="post">
						<?php wp_nonce_field( 'modify_links', 'fgj2wp_nonce' ); ?>
						<?php submit_button( __('Modify internal links', 'fg-joomla-to-wordpress'), 'primary', 'modify_links' ); ?>
					</form>
				</td>
			</tr>
		</table>
