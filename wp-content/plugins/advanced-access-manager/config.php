<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

//AAM Version for Update purpose
define('AAM_VERSION', '2.8.5');

define('AAM_BASE_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);

$base_url = WP_PLUGIN_URL . '/' . basename(AAM_BASE_DIR) . '/';
if (strpos($base_url, 'https') === 0) {
    $base_url = str_replace('https:', '', $base_url);
} elseif (strpos($base_url, 'http') === 0) {
    $base_url = str_replace('http:', '', $base_url);
}

define('AAM_BASE_URL', $base_url);

define('AAM_TEMPLATE_DIR', AAM_BASE_DIR . 'view/html/');
define('AAM_LIBRARY_DIR', AAM_BASE_DIR . 'library/');
define('AAM_TEMP_DIR', WP_CONTENT_DIR . '/aam/');
define('AAM_MEDIA_URL', AAM_BASE_URL . 'media/');

define('AAM_APPL_ENV', (getenv('APPL_ENV') ? getenv('APPL_ENV') : 'production'));
//Rest API
if (AAM_APPL_ENV === 'production') {
    define('WPAAM_REST_API', 'http://rest.wpaam.com');
} else {
    define('WPAAM_REST_API', 'http://wpaam.localhost/');
}

/**
 * Autoloader for project Advanced Access Manager
 *
 * Try to load a class if prefix is mvb_
 *
 * @param string $class_name
 */
function aam_autoload($class_name) {
    $chunks = explode('_', strtolower($class_name));
    $prefix = array_shift($chunks);

    if ($prefix === 'aam') {
        $base_path = AAM_BASE_DIR . '/application';
        $path = $base_path . '/' . implode('/', $chunks) . '.php';
        if (file_exists($path)) {
            require($path);
        }
    }
}

spl_autoload_register('aam_autoload');

//make sure that we have always content dir
if (!file_exists(AAM_TEMP_DIR)) {
    if (@mkdir(AAM_TEMP_DIR)) {
        //silence the directory
        file_put_contents(AAM_TEMP_DIR . '/index.php', '');
    }  else {
        aam_Core_Console::add(__('Failed to create wp-content/aam folder', 'aam'));
    }
} elseif(!is_writable(AAM_TEMP_DIR)){
    aam_Core_Console::add(__('Folder wp-content/aam is not writable', 'aam'));
}

load_plugin_textdomain('aam', false, basename(AAM_BASE_DIR) . '/lang');