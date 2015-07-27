<?php
/**
    Copyright (C) <2013-2014>  Vasyl Martyniuk <support@wpaam.com>

    This program is commercial software: you are not allowed to redistribute it 
    and/or modify. Unauthorized copying of this file, via any medium is strictly 
    prohibited.
    For any questions or concerns contact Vasyl Martyniuk <support@wpaam.com>
 */
 
 //define extension constant
 define('AAM_PLUS_PACKAGE_EXTENSION', true);

$dirname = basename(dirname(__FILE__));
define('AAM_PLUS_BASE_URL', AAM_BASE_URL . 'extension/' . $dirname);

//run activate first if exists
$activation_file = dirname(__FILE__) . '/activation.php';
if (file_exists($activation_file)) {
    require_once $activation_file;
    $activation = new AAM_Extension_Plus_Activation($this->getParent());
    if ($activation->run()) {
        @unlink($activation_file);
    }
}

require_once dirname(__FILE__) . '/extension.php';

return new AAM_Extension_Plus($this->getParent());