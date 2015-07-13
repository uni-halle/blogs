<div class="wrap metabox-holder">
	<h2>Facebook Like Button Settings</h2>
	<?php settings_errors(); ?>
		<form id="AGLBSettingsForm" method="post" action="options.php">
			<div id="poststuff" class="metabox-holder has-right-sidebar">
				<div id="post-body" class="has-sidebar">
					<div id="post-body-content" class="has-sidebar-content">
						<?php do_meta_boxes( 'AGPressGraph', 'normal', null ); ?>
					</div>
				</div>
				<div id="side-info-column" class="inner-sidebar">
					<?php do_meta_boxes('AGPressGraph', 'side', null); ?>
				</div>	
			</div>
			<?php settings_fields( "AGPressGraph" ) ?>
		</form>
		<?php
		
		
	?>
</div>