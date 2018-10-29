<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<script type="text/template" id="tmpl-elementor-panel-global-widget">
	<div id="elementor-global-widget-locked-header" class="elementor-nerd-box elementor-panel-nerd-box">
		<i class="elementor-nerd-box-icon elementor-panel-nerd-box-icon eicon-nerd"></i>
		<div class="elementor-nerd-box-title elementor-panel-nerd-box-title"><?php echo __( 'Your Widget is Now Locked', 'elementor-pro' ); ?></div>
		<div class="elementor-nerd-box-message elementor-panel-nerd-box-message"><?php echo __( 'Edit this global widget to simultaneously update every place you used it, or unlink it so it gets back to being regular widget.', 'elementor-pro' ); ?></div>
	</div>
	<div id="elementor-global-widget-locked-tools">
		<div id="elementor-global-widget-locked-edit" class="elementor-global-widget-locked-tool">
			<div class="elementor-global-widget-locked-tool-description"><?php echo __( 'Edit global widget', 'elementor-pro' ); ?></div>
			<button class="elementor-button elementor-button-success"><?php echo __( 'Edit', 'elementor-pro' ); ?></button>
		</div>
		<div id="elementor-global-widget-locked-unlink" class="elementor-global-widget-locked-tool">
			<div class="elementor-global-widget-locked-tool-description"><?php echo __( 'Unlink from global', 'elementor-pro' ); ?></div>
			<button class="elementor-button"><?php echo __( 'Unlink', 'elementor-pro' ); ?></button>
		</div>
	</div>
	<div id="elementor-global-widget-loading" class="elementor-hidden">
		<i class="fa fa-spin fa-circle-o-notch"></i>
	</div>
</script>

<script type="text/template" id="tmpl-elementor-panel-global-widget-no-templates">
	<i class="elementor-nerd-box-icon elementor-panel-nerd-box-icon eicon-nerd"></i>
	<div class="elementor-nerd-box-title elementor-panel-nerd-box-title"><?php echo __( 'Save Your First Global Widget', 'elementor-pro' ); ?></div>
	<div class="elementor-nerd-box-message elementor-panel-nerd-box-message"><?php echo __( 'Save a widget as global, then add it to multiple areas. All areas will be editable from one single place.', 'elementor-pro' ); ?></div>
</script>
