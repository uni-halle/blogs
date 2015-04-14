<div data-notification-name="<?php esc_attr_e($notice_name)?>" class="ngg_admin_notice <?php esc_attr_e($css_class)?>">
	<p><?php echo $html ?></p>
	<?php if ($is_dismissable): ?>
	<p><a class='dismiss' href="#"><?php esc_html_e(__('Dismiss', 'nggallery')) ?></a></p>
	<?php endif ?>
</div>