<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

return array(
    'manage-capability' => array(
        'title'    => __('Edit/Delete Capabilities', AAM_KEY),
        'descr'    => AAM_Backend_View_Helper::preparePhrase('Allow to edit or delete any capability on the Capabilities tab. [Note!] For experienced users only. Changing or deleting capability may result in loosing access to some features or the entire website.', 'b'),
        'value'    => AAM_Core_Config::get('manage-capability', false),
        'category' => 'core'
    ),
    'backend-access-control' => array(
        'title'    => __('Backend Access Control', AAM_KEY),
        'descr'    => __('Allow AAM to manage access to backend resources. If there is no need to manage access to the website backend then keep this option unchecked as it may increase your webiste performance.', AAM_KEY),
        'value'    => AAM_Core_Config::get('backend-access-control', true),
        'category' => 'core'
    ),
    'frontend-access-control' => array(
        'title'    => __('Frontend Access Control', AAM_KEY),
        'descr'    => __('Allow AAM to manage access to frontend resources. If there is no need to manage access to the website frontend then keep this option unchecked as it may increase your webiste performance.', AAM_KEY),
        'value'    => AAM_Core_Config::get('frontend-access-control', true),
        'category' => 'core'
    ),
    'media-access-control' => array(
        'title'    => __('Media Files Access Control', AAM_KEY),
        'descr'    => sprintf(AAM_Backend_View_Helper::preparePhrase('Allow AAM to manage a physically access to all media files located in the defined by the system [uploads] folder. [Note!] This feature requires additional steps as described in %sthis article%s.', 'strong', 'strong'), '<a href="https://aamplugin.com/help/how-to-manage-wordpress-media-access" target="_blank">', '</a>'),
        'value'    => AAM_Core_Config::get('media-access-control', false),
        'category' => 'post'
    ),
    'check-post-visibility' => array(
        'title'    => __('Check Post Visibility', AAM_KEY),
        'descr'    => __('For performance reasons, keep this option uncheck if do not use LIST or LIST TO OTHERS access options on Posts & Pages tab. When it is checked, AAM will filter list of posts that are hidden for a user on both frontend and backend.', AAM_KEY),
        'value'    => AAM_Core_Config::get('check-post-visibility', true),
        'category' => 'post'
    ),
    'manage-hidden-post-types' => array(
        'title'    => __('Manage Hidden Post Types', AAM_KEY),
        'descr'    => __('By default AAM allows you to manage access only to public post types on Posts & Pages tab. By enabling this feature, you also will be able to manage access to hidden post types like revisions, navigation menus or any other custom post types that are not registered as public.', AAM_KEY),
        'value'    => AAM_Core_Config::get('manage-hidden-post-types', false),
        'category' => 'post'
    ),
    'render-access-metabox' => array(
        'title'    => __('Render Access Manager Metabox', AAM_KEY),
        'descr'    => __('Render Access Manager metabox on all post and category edit pages. Access Manager metabox is the quick way to manage access to any post or category without leaving an edit page.', AAM_KEY),
        'value'    => AAM_Core_Config::get('render-access-metabox', true),
        'category' => 'core'
    ),
    'show-access-link' => array(
        'title'    => __('Show Access Link', AAM_KEY),
        'descr'    => __('Show Access shortcut link under any post, page, custom post type, category, custom taxonomy title or user name.', AAM_KEY),
        'value'    => AAM_Core_Config::get('show-access-link', true),
        'category' => 'core'
    ),
);