<?php

class A_MVC_Router extends Mixin
{
	/**
	 * First tries to find the static file in the 'static' folder
	 * @param string $path
	 * @param string $module
	 * @return string
	 */
	function get_static_url($path, $module=FALSE)
	{
        $retval = '';

		// Determine the base url
		$base_url = $this->object->get_base_url('plugins');
		$base_url = $this->object->remove_url_segment('/index.php', $base_url);

		// Find the module directory
		$fs = $this->object->get_registry()->get_utility('I_Fs');
        $path = $fs->find_static_abspath($path, $module);


        // Convert the path to a relative path
        $original_length = strlen($path);
        $roots = array('plugins', 'plugins_mu', 'templates', 'stylesheets');
        $found_root = FALSE;
        foreach ($roots as $root) {
            $path = str_replace($fs->get_document_root($root), '', $path);
            if (strlen($path) != $original_length) {
                $found_root = $root;
                break;
            }
        }

        if ($found_root) {
            $retval = $this->object->join_paths(
                $this->object->get_base_url($found_root),
                str_replace("\\", '/', $path)
            );
        }

        else {
            //TODO: What do we do here?
        }

        return $retval;
	}
}