			<div class="metabox-holder">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Restore AWPCP Pages', 'AWPCP') ?></span></h3>
					<div class="inside">

			<?php if (!empty($missing)): ?>

			<div class="error">
			<?php if ( ! empty( $missing['not-found'] ) ): ?>
				<p><?php _e( "The following pages are missing; the plugin is looking for a page with a particular ID but it can no longer found.", 'AWPCP' ); ?></p>

				<ul>
				<?php foreach ( $missing['not-found'] as $page ): ?>
				<?php $default = $awpcp->settings->get_option_default_value( $page->page ); ?>
				<?php $message = __( "Page: %s (Default name: %s, Stored page ID = %d).", 'AWPCP' ); ?>
				<?php $message = sprintf( $message, '<strong>' . get_awpcp_option( $page->page ) . '</strong>', $default, $page->id );  ?>
				<li><?php echo $message; ?></li>
				<?php endforeach ?>
				</ul>
			<?php endif; ?>

			<?php if ( ! empty( $missing['not-published'] ) ): ?>
				<p><?php _e( "The following pages are not published (did you move them to the Trash by accident?).", 'AWPCP' ); ?></p>

				<ul>
				<?php foreach ( $missing['not-published'] as $page ): ?>
				<?php $default = $awpcp->settings->get_option_default_value( $page->page ); ?>
				<?php $message = __( "Page: %s (Default name: %s, Stored page ID = %d, Current post status: %s).", 'AWPCP' ); ?>
				<?php $message = sprintf( $message, '<strong>' . get_awpcp_option( $page->page ) . '</strong>', $default, $page->id, $page->status );  ?>
				<li><?php echo $message; ?></li>
				<?php endforeach ?>
				</ul>
			<?php endif; ?>

			<?php if ( ! empty( $missing['not-referenced'] ) ): ?>
				<p><?php _e( "The plugin has no associated page ID for the following pages. Please contact customer support.", 'AWPCP' ); ?></p>

				<ul>
				<?php foreach ( $missing['not-referenced'] as $page ): ?>
				<?php $default = $awpcp->settings->get_option_default_value( $page->page ); ?>
				<?php $message = __( "Page: %s (Default name: %s).", 'AWPCP' ); ?>
				<?php $message = sprintf( $message, '<strong>' . get_awpcp_option( $page->page ) . '</strong>', $default );  ?>
				<li><?php echo $message; ?></li>
				<?php endforeach ?>
				</ul>
			<?php endif; ?>
			</div>

			<?php endif ?>

			<form method="post">
				<?php wp_nonce_field('awpcp-restore-pages'); ?>
				<div><?php _e('If you are having problems with your plugin pages, you can delete them and use the Restore button to have the plugin create them again.', 'AWPCP') ?></div>
				<input type="submit" value="<?php echo esc_attr( __( 'Restore Pages', 'AWPCP' ) ); ?>" class="button-primary" id="submit" name="restore-pages">
			</form>

					</div>
				</div>
			</div>
