		<div id="fgj2wp_database_info">
			<h3><?php _e('WordPress database', 'fg-joomla-to-wordpress') ?></h3>
			<?php foreach ( $data['database_info'] as $data_row ): ?>
				<?php print $data_row; ?><br />
			<?php endforeach; ?>
		</div>
