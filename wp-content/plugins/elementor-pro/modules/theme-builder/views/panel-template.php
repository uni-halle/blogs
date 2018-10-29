<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<script type="text/template" id="tmpl-elementor-theme-builder-conditions-view">
	<div class="elementor-template-library-blank-icon">
		<i class="fa fa-paper-plane"></i>
	</div>
	<div class="elementor-template-library-blank-title">{{{ title }}}</div>
	<div class="elementor-template-library-blank-message">{{{ description }}}</div>
	<div id="elementor-theme-builder-conditions">
		<div id="elementor-theme-builder-conditions-controls"></div>
	</div>
	<div id="elementor-theme-builder-conditions__footer">
		<button id="elementor-theme-builder-conditions__publish" class="elementor-button elementor-button-success">
			<span class="elementor-state-icon">
				<i class="fa fa-spin fa-circle-o-notch"></i>
			</span>
			<span id="elementor-theme-builder-conditions__publish__title"></span>
		</button>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-theme-builder-conditions-repeater-row">
	<div class="elementor-theme-builder-conditions-repeater-row-controls"></div>
	<div class="elementor-repeater-row-tool elementor-repeater-tool-remove">
		<i class="eicon-close"></i>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-theme-builder-button-preview">
	<i class="fa fa-eye tooltip-target" aria-hidden="true"  data-tooltip="<?php esc_attr_e( 'Preview Changes', 'elementor-pro' ); ?>"></i>
	<span class="elementor-screen-only">
		<?php echo __( 'Preview Changes', 'elementor-pro' ); ?>
	</span>
	<div class="elementor-panel-footer-sub-menu-wrapper">
		<div class="elementor-panel-footer-sub-menu">
			<div id="elementor-panel-footer-theme-builder-button-preview-settings" class="elementor-panel-footer-sub-menu-item">
				<i class="fa fa-wrench" aria-hidden="true"></i>
				<span class="elementor-title"><?php echo __( 'Settings', 'elementor-pro' ); ?></span>
			</div>
			<div id="elementor-panel-footer-theme-builder-button-open-preview" class="elementor-panel-footer-sub-menu-item">
				<i class="fa fa-external-link" aria-hidden="true"></i>
				<span class="elementor-title"><?php echo __( 'Preview', 'elementor-pro' ); ?></span>
			</div>
		</div>
	</div>
</script>
