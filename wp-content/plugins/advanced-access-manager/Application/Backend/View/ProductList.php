<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

return array(
    array(
        'title'       => '<span class="aam-highlight">AAM Plus Package</span>',
        'id'          => 'AAM Plus Package',
        'type'        => 'commercial',
        'price'       => '$30 <small>USD</small>',
        'description' => 'Our best selling extension that allows you to manage access to unlimited number of posts, pages or custom post types and define default access to ALL posts, pages, custom post types, categories or custom taxonomies. <a href="https://vasyltech.com/blog/manage-access-to-posts-and-pages" target="_blank">Read more.</a>',
        'storeURL'    => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FGAHULDEFZV4U',
        'status'      => AAM_Core_Repository::getInstance()->extensionStatus('AAM Plus Package'),
        'version'     => AAM_Core_Repository::getInstance()->getExtensionVersion('AAM Plus Package')
    ),
    array(
        'title'       => 'AAM Redirect',
        'id'          => 'AAM Redirect',
        'type'        => 'commercial',
        'price'       => '$20 <small>USD</small>',
        'new'         => true,
        'description' => 'Define custom redirect or "Access Denied" message for each role, individual user or visitors. <a href="http://vasyltech.com/blog/aam-redirect-extension" target="_blank">Read more.</a>',
        'storeURL'    => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QAM3P45X6PKUU',
        'status'      => AAM_Core_Repository::getInstance()->extensionStatus('AAM Redirect'),
        'version'     => AAM_Core_Repository::getInstance()->getExtensionVersion('AAM Redirect')
    ),
    array(
        'title'       => 'AAM Content Teaser',
        'id'          => 'AAM Content Teaser',
        'type'        => 'commercial',
        'price'       => '$10 <small>USD</small>',
        'new'         => true,
        'description' => 'Define custom teaser message for each role, individual user or visitors. <a href="http://vasyltech.com/blog/aam-content-teaser" target="_blank">Read more.</a>',
        'storeURL'    => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3NG4CZX3WEH7L',
        'status'      => AAM_Core_Repository::getInstance()->extensionStatus('AAM Content Teaser'),
        'version'     => AAM_Core_Repository::getInstance()->getExtensionVersion('AAM Content Teaser')
    ),
    array(
        'title'       => 'AAM Role Hierarchy',
        'id'          => 'AAM Role Hierarchy',
        'type'        => 'commercial',
        'price'       => '$15 <small>USD</small>',
        'new'         => true,
        'description' => 'Create complex role hierarchy and automatically inherit access settings from parent roles. <a href="https://vasyltech.com/blog/aam-role-hierarchy" target="_blank">Read more.</a>',
        'storeURL'    => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K8DMZ66SAW8VG',
        'status'      => AAM_Core_Repository::getInstance()->extensionStatus('AAM Role Hierarchy'),
        'version'     => AAM_Core_Repository::getInstance()->getExtensionVersion('AAM Role Hierarchy')
    ),
    array(
        'title'       => 'AAM Role Filter',
        'id'          => 'AAM Role Filter',
        'type'        => 'commercial',
        'price'       => '$5 <small>USD</small>',
        'description' => 'More advanced user and role administration. Based on user capabilities level, filter list of roles that user can manage. Also prevent from editing, promoting or deleting higher level users.',
        'storeURL'    => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G9V4BT3T8WJSN',
        'status'      => AAM_Core_Repository::getInstance()->extensionStatus('AAM Role Filter'),
        'version'     => AAM_Core_Repository::getInstance()->getExtensionVersion('AAM Role Filter')
    ),
     array(
        'title'       => 'AAM Complete Package',
        'id'          => 'AAM Complete Package',
        'type'        => 'commercial',
        'price'       => '$70 <small>USD</small>',
        'description' => 'Get list of all available premium extensions in one package and <strong>save $10 USD</strong>.',
        'storeURL'    => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=THJWEJR3URR8L',
        'status'      => AAM_Core_Repository::getInstance()->extensionStatus('AAM Complete Package'),
        'version'     => AAM_Core_Repository::getInstance()->getExtensionVersion('AAM Complete Package')
    ),
    array(
        'title'       => 'AAM Multisite',
        'id'          => 'AAM Multisite',
        'type'        => 'GNU',
        'license'     => 'AAMMULTISITE',
        'description' => 'Convenient way to navigate between different sites in the Network Admin Panel.',
        'status'      => AAM_Core_Repository::getInstance()->extensionStatus('AAM Multisite'),
        'version'     => AAM_Core_Repository::getInstance()->getExtensionVersion('AAM Multisite')
    ),
    array(
        'title'       => 'CodePinch',
        'id'          => 'WP Error Fix',
        'type'        => 'plugin',
        'description' => '<span class="aam-highlight">Highly recommended!</span> Patent-pending technology that provides solutions to PHP errors within hours, preventing costly maintenance time and keeping your WordPress site error. <a href="http://codepinch.io" target="_blank">Read more.</a>',
        'status'      => AAM_Core_Repository::getInstance()->pluginStatus('WP Error Fix')
    ),
    array(
        'title'       => 'ConfigPress',
        'id'          => 'ConfigPress',
        'type'        => 'plugin',
        'description' => 'Development tool with an easy way to manage all your website custom settings. <a href="https://vasyltech.com/config-press" target="_blank">Read more.</a>',
        'status'      => AAM_Core_Repository::getInstance()->pluginStatus('ConfigPress')
    )
);