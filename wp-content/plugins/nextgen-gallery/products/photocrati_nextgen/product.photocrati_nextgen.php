<?php

/***
{
Product: photocrati-nextgen
}
 ***/

define('NGG_CHANGE_OPTIONS_CAP', 'NextGEN Manage gallery');

class P_Photocrati_NextGen extends C_Base_Product
{
	static $modules;

    function define_modules()
    {
	    self::$modules = array(
            'photocrati-fs',
            'photocrati-i18n',
            'photocrati-validation',
            'photocrati-router',
            'photocrati-wordpress_routing',
            'photocrati-security',
            'photocrati-nextgen_settings',
            'photocrati-mvc',
            'photocrati-ajax',
            'photocrati-datamapper',
            'photocrati-nextgen-legacy',
            'photocrati-nextgen-data',
        );

        self::$modules = array_merge(self::$modules, array(
            'photocrati-dynamic_thumbnails',
            'photocrati-nextgen_admin',
            'photocrati-nextgen_gallery_display',
            'photocrati-frame_communication',
            'photocrati-attach_to_post',
            'photocrati-nextgen_addgallery_page',
            'photocrati-nextgen_other_options',
	        'photocrati-nextgen_pagination'
        ));

        if (!is_admin()) {
            self::$modules[] = 'photocrati-dynamic_stylesheet';
            self::$modules[] = 'photocrati-mediarss';
        }

        if (is_admin()) {
            self::$modules = array_merge(self::$modules, array(
                'photocrati-nextgen_pro_upgrade',
            ));
        }

        self::$modules = array_merge(self::$modules, array(
            'photocrati-cache',
            'photocrati-lightbox',
            'photocrati-nextgen_basic_templates',
            'photocrati-nextgen_basic_gallery',
            'photocrati-nextgen_basic_imagebrowser',
            'photocrati-nextgen_basic_singlepic',
            'photocrati-nextgen_basic_tagcloud',
            'photocrati-nextgen_basic_album',
            'photocrati-widget',
            'photocrati-third_party_compat',
            'photocrati-nextgen_xmlrpc'
        ));

        if (defined('WP_CLI') && WP_CLI)
            self::$modules = array_merge(self::$modules, array('photocrati-wpcli'));
    }

	function define()
	{
		parent::define(
			'photocrati-nextgen',
			'Photocrati NextGEN',
			'Photocrati NextGEN',
            NGG_PLUGIN_VERSION,
			'http://www.nextgen-gallery.com',
			'Photocrati Media',
			'http://www.photocrati.com'
		);

		$module_path = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'modules'));
		$this->get_registry()->set_product_module_path($this->module_id, $module_path);
		$this->define_modules();

		foreach (self::$modules as $module_name) $this->_get_registry()->load_module($module_name);

		include_once('class.nextgen_product_installer.php');
		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Product_Installer');
	}
}

new P_Photocrati_NextGen();
