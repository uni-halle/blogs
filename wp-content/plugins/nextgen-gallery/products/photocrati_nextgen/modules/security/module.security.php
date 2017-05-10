<?php
class M_Security extends C_Base_Module
{
	function define($id = 'pope-module',
                    $name = 'Pope Module',
                    $description = '',
                    $version = '',
                    $uri = '',
                    $author = '',
                    $author_uri = '',
                    $context = FALSE)
	{
		parent::define(
			'photocrati-security',
			'Security',
			'Provides utilities to check for credentials and security',
			'0.3',
			'https://www.imagely.com/wordpress-gallery-plugin/nextgen-gallery/',
			'Imagely',
			'https://www.imagely.com'
		);

		if (isset($GLOBALS['_COOKIE_NG_COPY'])) {
			$_COOKIE = $GLOBALS['_COOKIE_NG_COPY'];
		}
	}

	function _register_adapters()
	{
		$this->get_registry()->add_adapter('I_Component_Factory', 'A_Security_Factory');
	}

	function _register_utilities()
	{
		$this->get_registry()->add_utility('I_Security_Manager', 'C_WordPress_Security_Manager');
	}

    function get_type_list()
    {
        return array(
            'A_Security_Factory' => 'adapter.security_factory.php',
            'C_Security_Actor' => 'class.security_actor.php',
            'C_Security_Manager' => 'class.security_manager.php',
            'C_Security_Token' => 'class.security_token.php',
            'C_Wordpress_Security_Actor' => 'class.wordpress_security_actor.php',
            'C_Wordpress_Security_Manager' => 'class.wordpress_security_manager.php',
            'C_Wordpress_Security_Token' => 'class.wordpress_security_token.php'
        );
    }

}

new M_Security();
