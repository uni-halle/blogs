<?php
/*
 * General Section
 */
?>

<?php if ('wpctc_clear-cache-on-save' == $field['label_for']) : ?>
    <input type="checkbox" name="wpctc_settings[general][clear-cache-on-save]"
           id="wpctc_settings[general][clear-cache-on-save]"
           value="1" <?php if (isset($settings['general']['clear-cache-on-save'])) checked(1, $settings['general']['clear-cache-on-save']) ?>>
    <p class="description" style="display: inline;">If set, the cache will be cleared every time one of the WPCTC widgets is saved.</p>
<?php elseif ('wpctc_do-not-load-scripts' == $field['label_for']) : ?>
    <input type="checkbox" name="wpctc_settings[general][do-not-load-scripts]"
           id="wpctc_settings[general][do-not-load-scripts]"
           value="1" <?php if (isset($settings['general']['do-not-load-scripts'])) checked(1, $settings['general']['do-not-load-scripts']) ?>>
<?php endif; ?>