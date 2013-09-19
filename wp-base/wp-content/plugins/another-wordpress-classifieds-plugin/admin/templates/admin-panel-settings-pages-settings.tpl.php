			<h3><?php _e('Restore AWPCP Pages', 'AWPCP') ?></h3>

			<?php if (!empty($missing)): ?>

			<div class="error">
				<p><?php _e("The following pages are missing or the plugin can't find them", 'AWPCP') ?></p>

				<ul>

			<?php foreach ($missing as $page): ?>
			<?php $default = $awpcp->settings->get_option_default_value($page->page) ?>
			<?php if ($page->id > 0): ?>
				<?php $message = __("<strong>%s</strong> (%s page): The plugin is looking for a page with ID = %d.", 'AWPCP') ?>
				<?php $message = sprintf($message, get_awpcp_option($page->page), $default, $page->id) ?>
			<?php else: ?>
				<?php $message = __("<strong>%s</strong> (%s page).", 'AWPCP') ?>
				<?php $message = sprintf($message, get_awpcp_option($page->page), $default) ?>
			<?php endif ?>
				<li><?php echo $message ?></li>
			<?php endforeach ?>	

				</ul>
			</div>

			<?php endif ?>

			<div>
				<form method="post">
					<?php wp_nonce_field('awpcp-restore-pages'); ?>
					<?php _e('If you are having problems with your plugin pages, you can delete them and use the Restore button to have the plugin create them again.', 'AWPCP') ?>
					<input type="submit" value="Restore" class="button-primary" id="submit" name="restore-pages">
				</form>
			</div>