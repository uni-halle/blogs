<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

return array(
    'AAM_COMPLETE_PACKAGE' => array(
        'title'       => 'AAM Complete Package',
        'id'          => 'AAM_COMPLETE_PACKAGE',
        'type'        => 'commercial',
        'description' => 'Get list of all available premium extensions in one package. Any additional premium extensions in the future will be available for free. Get complete package today and instantly save over 50%.',
        'storeURL'    => 'https://aamplugin.com/complete-package',
        'version'     => (defined('AAM_COMPLETE_PACKAGE') ? constant('AAM_COMPLETE_PACKAGE') : null)
    ),
    'AAM_PLUS_PACKAGE' => array(
        'title'       => 'AAM Plus Package',
        'id'          => 'AAM_PLUS_PACKAGE',
        'type'        => 'commercial',
        'description' => 'Our best selling extension that allows you to manage access to unlimited number of posts, pages, custom post types, categories, custom hierarchical taxonomies or define the default access to all.',
        'storeURL'    => 'https://aamplugin.com/extension/plus-package',
        'version'     => (defined('AAM_PLUS_PACKAGE') ? constant('AAM_PLUS_PACKAGE') : null)
    ),
    'AAM_IP_CHECK' => array(
        'title'       => 'AAM IP Check',
        'id'          => 'AAM_IP_CHECK',
        'type'        => 'commercial',
        'new'         => true,
        'description' => 'Manage access to your website based on a visitor geo-location, refered host or IP address.',
        'storeURL'    => 'https://aamplugin.com/extension/ip-check',
        'version'     => (defined('AAM_IP_CHECK') ? constant('AAM_IP_CHECK') : null)
    ),
    'AAM_ROLE_HIERARCHY' => array(
        'title'       => 'AAM Role Hierarchy',
        'id'          => 'AAM_ROLE_HIERARCHY',
        'type'        => 'commercial',
        'description' => 'Create complex role hierarchy and automatically inherit access settings from parent roles.',
        'storeURL'    => 'https://aamplugin.com/extension/role-hierarchy',
        'version'     => (defined('AAM_ROLE_HIERARCHY') ? constant('AAM_ROLE_HIERARCHY') : null)
    ),
    'AAM_ROLE_FILTER' => array(
        'title'       => 'AAM Role Filter',
        'id'          => 'AAM_ROLE_FILTER',
        'type'        => 'commercial',
        'description' => 'Based on user levels, restrict access to manage list of roles and users that have higher user level.',
        'storeURL'    => 'https://aamplugin.com/extension/role-filter',
        'version'     => (defined('AAM_ROLE_FILTER') ? constant('AAM_ROLE_FILTER') : null)
    ),
    'AAM_PAYMENT' => array(
        'title'       => 'AAM Payment',
        'id'          => 'AAM_PAYMENT',
        'type'        => 'commercial',
        'new'         => true,
        'description' => 'Start selling access to your posts, categories or user levels.',
        'storeURL'    => 'https://aamplugin.com/extension/payment',
        'version'     => (defined('AAM_PAYMENT') ? constant('AAM_PAYMENT') : null)
    ),
    'AAM_MULTISITE' => array(
        'title'       => 'AAM Multisite',
        'id'          => 'AAM_MULTISITE',
        'type'        => 'GNU',
        'license'     => 'AAMMULTISITE',
        'description' => 'Convenient way to navigate between different sites in the Network Admin Panel.',
        'version'     => (defined('AAM_MULTISITE') ? constant('AAM_MULTISITE') : null)
    ),
    'AAM_CONFIGPRESS' => array(
        'title'       => 'AAM ConfigPress',
        'id'          => 'AAM_CONFIGPRESS',
        'type'        => 'GNU',
        'license'     => 'AAMCONFIGPRESS',
        'description' => 'Extension to manage AAM core functionality with advanced configuration settings.',
        'version'     => (defined('AAM_CONFIGPRESS') ? constant('AAM_CONFIGPRESS') : null)
    ),
    'AAM_USER_ACTIVITY' => array(
        'title'       => 'AAM User Activities',
        'id'          => 'AAM_USER_ACTIVITY',
        'type'        => 'GNU',
        'license'     => 'AAMUSERACTIVITY',
        'description' => 'Track any kind of user or visitor activity on your website. <a href="https://aamplugin.com/help/how-to-track-any-wordpress-user-activity" target="_blank">Read more.</a>',
        'version'     => (defined('AAM_USER_ACTIVITY') ? constant('AAM_USER_ACTIVITY') : null)
    ),
);