<?php

defined('WYSIJANLP') or die('Restricted access');

/**
 * This class help extending the campaigns pages with various hooks and filters
 * @WARNING:
 * This class will be changed to WYSIJANLP_help_extend_module" before releasing 2.6
 */
class WYSIJANLP_help_extend_statistics extends WYSIJA_help {

    function __construct() {
        if (method_exists('WYSIJA_help', 'WYSIJA_help')) parent::WYSIJA_help();
        else parent::__construct();
    }

    function hook_stats($filter, $hook_params) {
        $filter = $filter; // not in use
        return WYSIJA_module::execute_hook('hook_stats', $hook_params, WYSIJANLP);
    }

    function hook_newsletter_top($filter, $hook_params) {
        // return $filter . WYSIJA_module::execute_hook('hook_newsletter_top', $hook_params, WYSIJANLP);
        return WYSIJA_module::execute_hook('hook_newsletter_top', $hook_params, WYSIJANLP) . $filter;
    }
    
    function hook_newsletter_bottom($filter, $hook_params) {
        return $filter . WYSIJA_module::execute_hook('hook_newsletter_bottom', $hook_params, WYSIJANLP);
    }
    
    function hook_subscriber_left($filter, $hook_params) {
        return $filter . WYSIJA_module::execute_hook('hook_subscriber_left', $hook_params, WYSIJANLP);
    }

    function hook_subscriber_right($filter, $hook_params) {
        return $filter . WYSIJA_module::execute_hook('hook_subscriber_right', $hook_params, WYSIJANLP);
    }
    
    function hook_subscriber_bottom($filter, $hook_params) {
        return $filter . WYSIJA_module::execute_hook('hook_subscriber_bottom', $hook_params, WYSIJANLP);
    }    
    
    function hook_settings_super_advanced($filter, $hook_params) {
        return $filter . WYSIJA_module::execute_hook('hook_settings_super_advanced', $hook_params, WYSIJANLP);
    }   
    
    function hook_settings_before_save($filter, $hook_params) {
        return $filter . WYSIJA_module::execute_hook('hook_settings_before_save', $hook_params, WYSIJANLP);
    }       
    
    function custom_module_hook($filter, $module_name, $hook_name, $hook_params) {
        $filter = $filter; // not in use
        return WYSIJA_module::get_instance_by_name($module_name, WYSIJANLP)->$hook_name($hook_params);
    }
    
    
    

}
