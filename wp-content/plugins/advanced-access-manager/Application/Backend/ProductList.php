<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

return array(
    array(
        'title' => 'AAM Dev License',
        'id' => 'AAM Development License',
        'type' => 'commercial',
        'cost'  => '$150',
        'currency' => 'USD',
        'description' => __('Development license gives you an ability to download all the available extensions and use them to up to 5 life domains.', AAM_KEY),
        'storeURL' => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZX9RCWU6BTE52',
        'status' => AAM_Core_Repository::getInstance()->extensionStatus('AAM Development License')
    ),
    array(
        'title' => 'AAM Plus Package',
        'id' => 'AAM Plus Package',
        'type' => 'commercial',
        'cost'  => '$30',
        'currency' => 'USD',
        'description' => __('Unlock limitations related to Posts and Pages feature. Extend basic AAM functionality with Page Categories and ability to manage access to your comments (AAM Plus Package adds new capabilities to the default list of WordPress capabilities like Edit Comments, Delete Comments, Spam Comments etc.)', AAM_KEY),
        'storeURL' => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FGAHULDEFZV4U',
        'status' => AAM_Core_Repository::getInstance()->extensionStatus('AAM Plus Package')
    ),
    array(
        'title' => 'AAM Role Filter',
        'id' => 'AAM Role Filter',
        'type' => 'commercial',
        'cost'  => '$5',
        'currency' => 'USD',
        'description' => __('Extension for more advanced user and role administration. Based on user\'s highest level capability, filter list of roles with higher level. Also prevent from editing, promoting or deleting higher level users.', AAM_KEY),
        'storeURL' => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G9V4BT3T8WJSN',
        'status' => AAM_Core_Repository::getInstance()->extensionStatus('AAM Role Filter')
    ),
    array(
        'title' => 'AAM Utilities',
        'id' => 'AAM Utilities',
        'type' => 'GNU',
        'license' => 'AAMUTILITIES',
        'description' => __('Various useful tools for AAM like caching or clear all settings.', AAM_KEY),
        'status' => AAM_Core_Repository::getInstance()->extensionStatus('AAM Utilities')
    ),
    array(
        'title' => 'AAM Multisite',
        'id' => 'AAM Multisite',
        'type' => 'GNU',
        'license' => 'AAMMULTISITE',
        'description' => __('Convenient way to navigate between different sites in the Network Admin Panel. This extension adds additional widget to the AAM page that allows to switch between different sites.', AAM_KEY),
        'status' => AAM_Core_Repository::getInstance()->extensionStatus('AAM Multisite')
    ),
    array(
        'title' => 'AAM Post Filter',
        'id' => 'AAM Post Filter',
        'type' => 'GNU',
        'license'  => 'AAMPOSTFILTER',
        'description' => AAM_Backend_Helper::preparePhrase('[WARNING!] Please use with caution. This is a supportive exension for the post access option [List]. It adds additional post filtering to fix the issue with large amount of post. [Turned on caching] is strongly recommended.', 'strong', 'strong', 'strong'),
        'status' => AAM_Core_Repository::getInstance()->extensionStatus('AAM Post Filter')
    )
);