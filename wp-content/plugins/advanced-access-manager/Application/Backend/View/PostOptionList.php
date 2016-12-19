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
            'descr' => __('Hide %s.', AAM_KEY)
        ),
        'list_others' => array(
            'title' => __('List To Others', AAM_KEY),
            'descr' => __('Hide %s for all except author.', AAM_KEY)
        ),
        'read' => array(
            'title' => __('Read', AAM_KEY),
            'descr' => __('Restrict access to read %s.', AAM_KEY)
        ),
        'read_others' => array(
            'title' => __('Read By Others', AAM_KEY),
            'descr' => __('Restrict access to read for all except author.', AAM_KEY)
        ),
        'limit' => array(
            'title' => __('Limit', AAM_KEY),
            'descr' => __('If checked, show defined in the Content Teaser tab teaser message instead of %s content.', AAM_KEY)
        ),
        'comment' => array(
            'title' => __('Comment', AAM_KEY),
            'descr' => __('Restrict access to comment on %s if commenting feature is enabled.', AAM_KEY)
        )
    ),
    'backend' => array(
        'list' => array(
            'title' => __('List', AAM_KEY),
            'descr' => __('Hide %s.', AAM_KEY)
        ),
        'list_others' => array(
            'title' => __('List To Others', AAM_KEY),
            'descr' => __('Hide %s for all except author.', AAM_KEY)
        ),
        'edit' => array(
            'title' => __('Edit', AAM_KEY),
            'descr' => __('Restrict access to edit %s.', AAM_KEY)
        ),
        'edit_others' => array(
            'title' => __('Edit By Others', AAM_KEY),
            'descr' => __('Restrict access to edit %s for all except author.', AAM_KEY)
        ),
        'delete' => array(
            'title' => __('Delete', AAM_KEY),
            'descr' => __('Restrict access to trash or permanently delete %s.', AAM_KEY)
        ),
        'delete_others' => array(
            'title' => __('Delete By Others', AAM_KEY),
            'descr' => __('Restrict access to trash or permanently delete %s for all except author.', AAM_KEY)
        ),
        'publish' => array(
            'title' => __('Publish', AAM_KEY),
            'descr' => __('Restrict access to publish %s. Quick Edit inline action is also removed.', AAM_KEY)
        ),
        'publish_others' => array(
            'title' => __('Publish By Others', AAM_KEY),
            'descr' => __('Restrict access to publish %s for all except author. Quick Edit inline action is also removed.', AAM_KEY)
        )
    )
);