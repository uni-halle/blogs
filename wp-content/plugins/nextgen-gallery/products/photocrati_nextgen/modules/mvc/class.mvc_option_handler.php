<?php

class C_Mvc_Option_Handler
{
	function get($option, $default=NULL)
    {
        $retval = $default;

        switch ($option) {
            case 'mvc_template_dir':
            case 'mvc_template_dirname':
                $retval = '/templates';
                break;
            case 'mvc_static_dirname':
            case 'mvc_static_dir':
                $retval = '/static';
                break;
        }

        return $retval;
	}
}