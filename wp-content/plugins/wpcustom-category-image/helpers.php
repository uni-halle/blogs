<?php

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!function_exists('___template')) {
    function ___template($name, $params = array(), $echo_html = true)
    {
        $filename = WPCCI_PATH_TEMPLATES . $name . '.php';

        if (! file_exists($filename)) {
            return;
        }

        foreach ($params as $param => $value) {
            $$param = $value;
        }

        ob_start();
        include $filename;
        $html = ob_get_contents();
        ob_end_clean();

        if (! $echo_html) {
            return $html;
        }

        echo $html;
    }
}


if (!function_exists('category_image')) {
    function category_image($params = array(), $echo = false)
    {
        $image_header = WPCustomCategoryImage::get_category_image($params);

        if (!$echo) {
            return $image_header;
        }

        echo $image_header;
    }
}


if (!function_exists('category_image_src')) {
    function category_image_src($params = array(), $echo = false)
    {
        $image_header = WPCustomCategoryImage::get_category_image($params, true);

        if (!$echo) {
            return $image_header;
        }

        echo $image_header;
    }
}

if (!function_exists('wpci_instance'))
{
    function wpci_instance()
    {
        return WPCustomCategoryImage::getInstance();
    }
}

if (!function_exists('wpcci_error')) {
    function wpcci_error($message, $errno)
    {
        $action = isset($_GET['action']) ? trim($_GET['action']) : null;

        if (!is_null($action) && $action === 'error_scrape') {
            die($message);
        }

        trigger_error($message, $errno);
    }
}
