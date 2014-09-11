<?php
	if (!defined('WP_CONTENT_DIR')) die;
	if( function_exists( 'add_meta_box' )) {
		add_meta_box($this->pluginslug.'Settings1', 'General settings', array(&$this, 'tw_options_box'), $this->pluginslug, 'normal');
		?>
			<div id="<?php echo $this->pluginslug; ?>-mbox-general" class="wrap">
				<?php screen_icon('options-general'); ?>
				<h2><?php echo $this->pluginlabel; ?></h2>
				<?php 
				wp_nonce_field($this->pluginslug.'-mbox-general');
				wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false );
				wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); 
				?>
				<div id="poststuff" class="metabox-holder">
					<div id="post-body" class="has-sidebar">
						<div id="post-body-content" class="">
							<?php do_meta_boxes($this->pluginslug, 'normal', null); ?>
						</div>
					</div>
					<p style="clear:both;"></p>
				</div>
			</div>
		<?php
	}
?>