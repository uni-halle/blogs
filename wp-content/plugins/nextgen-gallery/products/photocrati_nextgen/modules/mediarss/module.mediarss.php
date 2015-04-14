<?php
/***
{
		Module: photocrati-mediarss,
		Depends: { photocrati-router, photocrati-nextgen_gallery_display }
}
***/
class M_MediaRss extends C_Base_Module
{
	function define()
	{
		parent::define(
			'photocrati-mediarss',
			'MediaRss',
			'Generates MediaRSS feeds of image collections',
			'0.4',
			'http://www.nextgen-gallery.com',
			'Photocrati Media',
			'http://www.photocrati.com'
		);
	}

	function _register_utilities()
	{
		$this->get_registry()->add_utility(
			'I_MediaRSS_Controller', 'C_MediaRSS_Controller'
		);
	}

    function _register_hooks()
    {
        add_action('ngg_routes', array(&$this, 'define_routes'));
    }

    function define_routes($router)
    {
        $app = $router->create_app('/nextgen-mediarss');
        $app->route(
            '/',
            array(
                'controller' => 'I_MediaRSS_Controller',
                'action'  => 'index',
                'context' => FALSE
            )
        );
    }

    function get_type_list()
    {
        return array(
            'C_Mediarss_Controller' => 'class.mediarss_controller.php'
        );
    }

}

new M_MediaRss();