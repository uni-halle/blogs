<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

return array(
    'frontend' => array(
        'list' => array(
            'title' => __('List', AAM_KEY),
            'descr' => __('Hide %s however access with a direct URL will be still allowed. When there are more than 500 posts, this option may not be applied immediately because, for performance reasons, AAM checks limited number of posts per request.', AAM_KEY) . sprintf(__(' %sSee in action.%s', AAM_KEY), "<a href='https://youtu.be/2jiu_CL6JJg' target='_blank'>", '</a>')
        ),
        'list_others' => array(
            'title' => __('List To Others', AAM_KEY),
            'descr' => __('Hide %s for all except author (whoever created %s or was assigned on the Author metabox). Access with a direct URL will be still allowed. When there are more than 500 posts, this option may not be applied immediately because, for performance reasons, AAM checks limited number of posts per request.', AAM_KEY)
        ),
        'read' => array(
            'title' => __('Read', AAM_KEY),
            'descr' => __('Restrict access to read %s. Any attempts to read, view or open %s will result in redirecting user based on the Access Denied Redirect rule.', AAM_KEY) . sprintf(__(' %sSee in action.%s', AAM_KEY), "<a href='https://youtu.be/1742nVeGvgs' target='_blank'>", '</a>')
        ),
        'read_others' => array(
            'title' => __('Read By Others', AAM_KEY),
            'descr' => __('Restrict access to read %s for all except author (whoever created %s or was assigned on the Author metabox). Any attempts to read, view or open %s will result in redirecting user based on the Access Denied Redirect rule.', AAM_KEY)
        ),
        'limit' => array(
            'title' => __('Limit', AAM_KEY),
            'sub'   => '<small>' . sprintf(__('Teaser message: %s', AAM_KEY), '<b data-preview="frontend.teaser" id="post-teaser"></b>' ) . ' <a href="#" class="change-teaser" data-ref="frontend.teaser" data-preview-id="post-teaser">' . __('change', AAM_KEY) . '</a></small>',
            'descr' => __('When checked, show defined teaser message instead of the %s content.', AAM_KEY)
        ),
        'comment' => array(
            'title' => __('Comment', AAM_KEY),
            'descr' => __('Restrict access to comment on %s when commenting feature is enabled.', AAM_KEY)
        ),
        'redirect' => array(
            'title' => __('Redirect', AAM_KEY),
            'sub'   => '<small>' . sprintf(__('Redirect to: %s', AAM_KEY), '<b data-preview="frontend.location" id="post-location"></b>' ) . ' <a href="#" class="change-location" data-ref="frontend.location" data-preview-id="post-location">' . __('change', AAM_KEY) . '</a></small>',
            'descr' => __('Redirect user to defined location when user tries to read the %s. Define either valid full URL or public page ID within the website. The REDIRECT option will be ignored if READ option checked and user will be redirected based on the Access Denied Redirect rule.', AAM_KEY)
        ),
        'protected' => array(
            'title' => __('Password Protected', AAM_KEY),
            'sub'   => '<small>' . sprintf(__('Password: %s', AAM_KEY), '<b data-preview="frontend.password" id="post-password"></b>' ) . ' <a href="#" class="change-password" data-ref="frontend.password" data-preview-id="post-password">' . __('change', AAM_KEY) . '</a></small>',
            'descr' => __('Add the password protection for the %s. Available with WordPress 4.7.0 or higher.', AAM_KEY)
        ),
        'expire' => array(
            'title' => __('Access Expiration', AAM_KEY),
            'sub'   => '<small>' . sprintf(__('Expires: %s', AAM_KEY), '<b data-preview="frontend.expire_datetime" id="post-expire"></b>' ) . ' <a href="#" class="change-expiration" data-ref="frontend.expire_datetime" data-preview-id="post-expire">' . __('change', AAM_KEY) . '</a></small>',
            'descr' => __('Define when access is expired for %s.', AAM_KEY) . sprintf(__('After the expiration date, the access to READ will be denied unless this behavior is overwritten in ConfigPress. For more information %scheck this article%s or ', AAM_KEY), "<a href='https://aamplugin.com/help/how-to-set-expiration-date-for-any-wordpress-content' target='_blank'>", '</a>') . sprintf(__(' %ssee in action.%s', AAM_KEY), "<a href='https://youtu.be/IgtgVoWs35w' target='_blank'>", '</a>')
        ),
    ),
    'backend' => array(
        'list' => array(
            'title' => __('List', AAM_KEY),
            'descr' => __('Hide %s however access with a direct URL is still allowed. When there are more than 500 posts, this option may not be applied immediately because, for performance reasons, AAM checks limited number of posts per request.', AAM_KEY)
        ),
        'list_others' => array(
            'title' => __('List To Others', AAM_KEY),
            'descr' => __('Hide %s for all except author (whoever created %s or was assigned on the Author metabox). Access with a direct URL is still allowed. When there are more than 500 posts, this option may not be applied immediately because, for performance reasons, AAM checks limited number of posts per request.', AAM_KEY)
        ),
        'edit' => array(
            'title' => __('Edit', AAM_KEY),
            'descr' => __('Restrict access to edit %s. Any attempts to edit %s will result in redirecting user based on the Access Denied Redirect rule.', AAM_KEY)
        ),
        'edit_others' => array(
            'title' => __('Edit By Others', AAM_KEY),
            'descr' => __('Restrict access to edit %s for all except author (whoever created %s or was assigned on the Author metabox). Any attempts to edit %s will result in redirecting user based on the Access Denied Redirect rule.', AAM_KEY)
        ),
        'delete' => array(
            'title' => __('Delete', AAM_KEY),
            'descr' => __('Restrict access to trash or permanently delete %s.', AAM_KEY)
        ),
        'delete_others' => array(
            'title' => __('Delete By Others', AAM_KEY),
            'descr' => __('Restrict access to trash or permanently delete %s for all except author (whoever created %s or was assigned on the Author metabox).', AAM_KEY)
        ),
        'publish' => array(
            'title' => __('Publish', AAM_KEY),
            'descr' => __('Restrict access to publish %s. User will be allowed only submit for review. Quick Edit inline action is also removed from the list page.', AAM_KEY)
        ),
        'publish_others' => array(
            'title' => __('Publish By Others', AAM_KEY),
            'descr' => __('Restrict access to publish %s for all except author (whoever created %s or was assigned on the Author metabox). User will be allowed only submit for review. Quick Edit inline action is also removed from the list page.', AAM_KEY)
        )
    )
);