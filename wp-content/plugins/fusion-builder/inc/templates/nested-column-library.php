<script type="text/template" id="fusion-builder-nested-column-library-template">
	<div class="fusion-builder-modal-top-container">
		<h2 class="fusion-builder-settings-heading">
				{{ fusionBuilderText.insert_columns }}
		</h2>
	</div>
	<div class="fusion-builder-modal-bottom-container">
		<a href="#" class="fusion-builder-modal-close"><span>{{ fusionBuilderText.cancel }}</span></a>
	</div>
	<div class="fusion-builder-main-settings fusion-builder-main-settings-full fusion-builder-main-settings-advanced">
		<div class="fusion-builder-all-elements-container">
				<?php echo fusion_builder_inner_column_layouts(); // WPCS: XSS ok. ?>
		</div>
	</div>
</script>
