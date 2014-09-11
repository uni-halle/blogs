<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
*/

$dirname = basename(dirname(__FILE__));
define('AAM_SECURITY_BASE_URL', AAM_BASE_URL . 'extension/' . $dirname);


//load the Extension Controller
require_once dirname(__FILE__) . '/extension.php';

return new AAM_Secure($this->getParent());