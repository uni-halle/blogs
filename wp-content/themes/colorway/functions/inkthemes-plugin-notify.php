<?php

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function inkthemes_plugins_notify() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // Option tree plugin from the WordPress Plugin Repository.
            array(
            'name' => 'ColorWay Sites', // The plugin name.
            'slug' => 'colorway-sites', // The plugin slug (typically the folder name).
            'source' => 'https://wordpress.org/plugins/colorway-sites/', // The plugin source.
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
            'version' => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation' => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url' => '', // If set, overrides default API URL and points to an external URL.
            'is_callable' => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        ),
    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id' => 'tgmpa', // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '', // Default absolute path to bundled plugins.
        'menu' => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug' => 'themes.php', // Parent menu slug.
        'capability' => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices' => true, // Show admin notices or not.
        'dismissable' => true, // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '', // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false, // Automatically activate plugins after installation or not.
        'message' => '', // Message to output right before the plugins table.
        'strings' => array(
            'page_title' => __('Install Recommended Plugins', 'colorway'),
            'menu_title' => __('Install Plugins', 'colorway'),
            'installing' => /* translators: %s - plugin name, %s - location. */__('Installing Plugin: %s', 'colorway'),
            'oops' => __('Something went wrong with the plugin API.', 'colorway'),
            'notice_can_install_required' => /* translators: %s - plugin name. */_n_noop(
                    'This theme recommends the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'colorway'
            ), // %1$s = plugin name(s).
            'notice_can_install_recommended' => /* translators: %s - plugin name. */ _n_noop(
                    'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'colorway'
            ), // %1$s = plugin name(s).
            'notice_cannot_install' => /* translators: %s - plugin name. */ _n_noop(
                    'Sorry, but you do not have the correct permissions to install the %1$s plugin.', 'Sorry, but you do not have the correct permissions to install the %1$s plugins.', 'colorway'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update' => /* translators: %s - plugin name. */ _n_noop(
                    'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'colorway'
            ), // %1$s = plugin name(s).
            'notice_ask_to_update_maybe' => /* translators: %s - plugin name. */ _n_noop(
                    'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'colorway'
            ), // %1$s = plugin name(s).
            'notice_cannot_update' => /* translators: %s - plugin name. */ _n_noop(
                    'Sorry, but you do not have the correct permissions to update the %1$s plugin.', 'Sorry, but you do not have the correct permissions to update the %1$s plugins.', 'colorway'
            ), // %1$s = plugin name(s).
            'notice_can_activate_required' => /* translators: %s - plugin name. */ _n_noop(
                    'This theme recommends the following plugin to add lead form on landing page is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'colorway'
            ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => /* translators: %s - plugin name. */ _n_noop(
                    'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'colorway'
            ), // %1$s = plugin name(s).
            'notice_cannot_activate' => /* translators: %s - plugin name. */ _n_noop(
                    'Sorry, but you do not have the correct permissions to activate the %1$s plugin.', 'Sorry, but you do not have the correct permissions to activate the %1$s plugins.', 'colorway'
            ), // %1$s = plugin name(s).
            'install_link' => _n_noop(
                    'Begin installing plugin', 'Begin installing plugins', 'colorway'
            ),
            'update_link' => _n_noop(
                    'Begin updating plugin', 'Begin updating plugins', 'colorway'
            ),
            'activate_link' => _n_noop(
                    'Begin activating plugin', 'Begin activating plugins', 'colorway'
            ),
            'return' => __('Return to Required Plugins Installer', 'colorway'),
            'plugin_activated' => __('Plugin activated successfully.', 'colorway'),
            'activated_successfully' => __('The following plugin was activated successfully:', 'colorway'),
            'plugin_already_active' => /* translators: %s - plugin name. */ __('No action taken. Plugin %1$s was already active.', 'colorway'), // %1$s = plugin name(s).
            'plugin_needs_higher_version' => /* translators: %s - plugin name. */ __('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'colorway'), // %1$s = plugin name(s).
            'complete' => /* translators: %s - plugin name. */ __('All plugins installed and activated successfully. %1$s', 'colorway'), // %s = dashboard link.
            'contact_admin' => __('Please contact the administrator of this site for help.', 'colorway'),
            'nag_type' => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        ),
    );


    tgmpa($plugins, $config);
}
