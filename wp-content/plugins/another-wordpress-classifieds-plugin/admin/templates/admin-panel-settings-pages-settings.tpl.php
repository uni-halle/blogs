			<div class="metabox-holder">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Restore AWPCP Pages', 'another-wordpress-classifieds-plugin') ?></span></h3>
					<div class="inside">

			<?php
				if ( ! empty( $restored_pages ) ){
					$message = __( 'The following pages were restored: <pages-list>.', 'another-wordpress-classifieds-plugin' );
					$pages_names = array_map( 'awpcp_get_option', awpcp_get_properties( $restored_pages, 'page' ) );
					$pages_list = '<strong>' . implode( '</strong>, <strong>', $pages_names ) . '</strong>' ;
					echo awpcp_print_message( str_replace( '<pages-list>', $pages_list, $message ) );
				}
			?>

			<?php if (!empty($missing)): ?>

			<div class="error">
			<?php if ( ! empty( $missing['not-found'] ) ): ?>
				<p><?php _e( "The following pages are missing; the plugin is looking for a page with a particular ID but it seems that the page was permanently deleted.", 'another-wordpress-classifieds-plugin' ); ?></p>

				<ul>
				<?php foreach ( $missing['not-found'] as $page ): ?>
				<?php $default = $awpcp->settings->get_option_default_value( $page->page ); ?>
				<?php $message = __( "Page: %s (Default name: %s, Stored page ID = %d).", 'another-wordpress-classifieds-plugin' ); ?>
				<?php $message = sprintf( $message, '<strong>' . get_awpcp_option( $page->page ) . '</strong>', $default, $page->id );  ?>
				<li><?php echo $message; ?></li>
				<?php endforeach ?>
				</ul>
			<?php endif; ?>

			<?php if ( ! empty( $missing['not-published'] ) ): ?>
				<p><?php _e( "The following pages are not published (did you move them to the Trash by accident?).", 'another-wordpress-classifieds-plugin' ); ?></p>

				<ul>
				<?php foreach ( $missing['not-published'] as $page ): ?>
				<?php $default = $awpcp->settings->get_option_default_value( $page->page ); ?>
				<?php $message = __( "Page: %s (Default name: %s, Stored page ID = %d, Current post status: %s).", 'another-wordpress-classifieds-plugin' ); ?>
				<?php $message = sprintf( $message, '<strong>' . get_awpcp_option( $page->page ) . '</strong>', $default, $page->id, $page->status );  ?>
				<li><?php echo $message; ?></li>
				<?php endforeach ?>
				</ul>
			<?php endif; ?>

			<?php if ( ! empty( $missing['not-referenced'] ) ): ?>
				<p><?php _e( "The plugin has no associated page ID for the following pages. Please contact customer support.", 'another-wordpress-classifieds-plugin' ); ?></p>

				<ul>
				<?php foreach ( $missing['not-referenced'] as $page ): ?>
				<?php $default = $awpcp->settings->get_option_default_value( $page->page ); ?>
				<?php $message = __( "Page: %s (Default name: %s).", 'another-wordpress-classifieds-plugin' ); ?>
				<?php $message = sprintf( $message, '<strong>' . get_awpcp_option( $page->page ) . '</strong>', $default );  ?>
				<li><?php echo $message; ?></li>
				<?php endforeach ?>
				</ul>
			<?php endif; ?>
			</div>

			<?php endif ?>

			<form method="post">
				<?php wp_nonce_field('awpcp-restore-pages'); ?>
				<p><?php _e( 'Use the button below to have the plugin attempt to find the necessary pages. If you continue to have problems or seeing page related warnings above, you can delete affected plugin pages and use the Restore Pages button to have the plugin create them again.', 'another-wordpress-classifieds-plugin') ?></p>
				<input type="submit" value="<?php echo esc_attr( __( 'Restore Pages', 'another-wordpress-classifieds-plugin' ) ); ?>" class="button-primary" id="submit" name="restore-pages">
			</form>

					</div>
				</div>
			</div>
