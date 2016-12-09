<div id="errors">
	
</div>
<div class="accordion" id="display_settings_accordion">
<?php foreach($tabs as $tab): ?>
	<?php echo $tab ?>
<?php endforeach ?>
</div>
<p class="wp-core-ui">
	<input type="button" class="button button-primary button-large" id="save_displayed_gallery" value="<?php if ($displayed_gallery->id()) { _e('Save Changes', 'nggallery'); } else { _e('Insert Displayed Gallery', 'nggallery'); } ?>"/>
</p>
