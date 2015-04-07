<?php

class A_WordPress_Router extends Mixin
{
	function initialize()
	{
		// Set context to path if subdirectory install
		$parts = parse_url($this->object->get_base_url(FALSE));
		if (isset($parts['path'])) {
            $parts = explode('/index.php', $parts['path']);
			$this->object->context = array_shift($parts);
		}


		$this->object->add_post_hook(
			'get_url',
			'Construct url for WordPress, considering permalinks',
			get_class(),
			'_modify_url_for_wordpress'
		);
	}

	function _modify_url_for_wordpress()
	{
		// Get the method to be returned
		$retval = $this->object->get_method_property(
			$this->method_called,
			ExtensibleObject::METHOD_PROPERTY_RETURN_VALUE
		);

		// Determine whether the url is a directory or file on the filesystem
		// If so, then we do NOT need /index.php as part of the url
		$base_url = $this->object->get_base_url();
		$filename = str_replace(
			$base_url,
			$this->get_registry()->get_utility('I_Fs')->get_document_root(),
			$retval
		);

		if ($retval && @file_exists($filename) && $retval != $base_url) {

			// Remove index.php from the url
			$retval = $this->object->remove_url_segment('/index.php', $retval);

			// Static urls don't end with a slash
			$retval = untrailingslashit($retval);

			// Set retval to the new url being returned
			$this->object->set_method_property(
				$this->method_called,
				ExtensibleObject::METHOD_PROPERTY_RETURN_VALUE,
				$retval
			);
		}

		return $retval;
	}

	function _add_index_dot_php_to_url($url)
	{
		if (strpos($url, '/index.php') === FALSE) {
			$pattern = get_option('permalink_structure');
			if (!$pattern OR strpos($pattern, '/index.php') !== FALSE) {
				$url = $this->object->join_paths($url, '/index.php');
			}
		}

		return $url;
	}


    function get_base_url($site_url = FALSE)
    {
        $retval             = NULL;
        $add_index_dot_php  = TRUE;

        switch ($site_url) {
            case $site_url === TRUE:
            case 'site':
                $retval = site_url();
                break;
            case $site_url === FALSE:
            case 'home':
                $retval = home_url();
                break;
            case 'plugins':
            case 'plugin':
                $retval = plugins_url();
                $add_index_dot_php = FALSE;
                break;
            case 'plugins_mu':
            case 'plugin_mu':
                $retval = WPMU_PLUGIN_URL;
                $retval = set_url_scheme($retval);
                $retval = apply_filters( 'plugins_url', $retval, '', '');
                $add_index_dot_php = FALSE;
                break;
            case 'templates':
            case 'template':
            case 'themes':
            case 'theme':
                $retval = get_template_directory_uri();
                $add_index_dot_php = FALSE;
                break;
            case 'styles':
            case 'style':
            case 'stylesheets':
            case 'stylesheet':
                $retval = get_stylesheet_directory_uri();
                $add_index_dot_php = FALSE;
                break;
            case 'content':
                $retval = content_url();
                $add_index_dot_php = FALSE;
                break;
            case 'root':
                $retval = get_option('home');
                if (is_ssl())
                    $scheme = 'https';
                else
                    $scheme = parse_url($retval, PHP_URL_SCHEME);
                $retval = set_url_scheme($retval, $scheme);
                break;
            case 'gallery':
            case 'galleries':
                $root_type = defined('NGG_GALLERY_ROOT_TYPE') ? NGG_GALLERY_ROOT_TYPE : 'site';
                $add_index_dot_php = FALSE;
                if ($root_type === 'content')
                    $retval = content_url();
                else
                    $retval = site_url();
                break;
            default:
                $retval = site_url();
        }

        if ($add_index_dot_php)
            $retval = $this->_add_index_dot_php_to_url($retval);

        if ($this->object->is_https())
            $retval = preg_replace('/^http:\\/\\//i', 'https://', $retval, 1);

        return $retval;
    }
}
