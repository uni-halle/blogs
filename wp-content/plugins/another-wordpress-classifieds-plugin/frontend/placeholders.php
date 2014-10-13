<?php

/**
 * @since 3.0
 */
function awpcp_content_placeholders() {
    static $placeholders = null;

    if (is_array($placeholders)) return $placeholders;

    /* placeholders available prior to AWPCP 3.0 */
    $legacy_placeholders = array(
        // single ad placeholders
        'ad_title' => array(
            'callback' => 'awpcp_do_placeholder_title',
        ),
        'ad_categoryurl' => array(
            'callback' => 'awpcp_do_placeholder_category_url',
        ),
        'ad_categoryname' => array(
            'callback' => 'awpcp_do_placeholder_category_name',
        ),
        'adcontact_name' => array(
            'callback' => 'awpcp_do_placeholder_contact_name',
        ),
        'adcontactphone' => array(
            'callback' => 'awpcp_do_placeholder_contact_phone',
        ),
        'codecontact' => array(
            'callback' => 'awpcp_do_placeholder_contact_url',
        ),
        'awpcpvisitwebsite' => array(
            'callback' => 'awpcp_do_placeholder_website_link',
        ),
        'addetails' => array(
            'callback' => 'awpcp_do_placeholder_details',
        ),
        'location' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'city' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'state' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'village' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'country' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'aditemprice' => array(
            'callback' => 'awpcp_do_placeholder_price',
        ),
        'ad_startdate' => array(
            'callback' => 'awpcp_do_placeholder_legacy_dates',
        ),
        'ad_postdate' => array(
            'callback' => 'awpcp_do_placeholder_legacy_dates',
        ),

        'featureimg' => array(
            'callback' => 'awpcp_do_placeholder_images',
        ),
        'awpcpshowadotherimages' => array(
            'callback' => 'awpcp_do_placeholder_images',
        ),

        'awpcpextrafields' => array(
            'callback' => 'awpcp_do_placeholder_extra_fields',
        ),
        'showadsense1' => array(
            'callback' => 'awpcp_do_placeholder_adsense',
        ),
        'showadsense2' => array(
            'callback' => 'awpcp_do_placeholder_adsense',
        ),
        'showadsense3' => array(
            'callback' => 'awpcp_do_placeholder_adsense',
        ),
        'awpcpadviews' => array(
            'callback' => 'awpcp_do_placeholder_legacy_views',
        ),

        'flagad' => array(
            'callback' => 'awpcp_do_placeholder_flag_link',
        ),

        'tweetbtn' => array(
            'callback' => 'awpcp_do_placeholder_twitter_button',
        ),
        'sharebtn' => array(
            'callback' => 'awpcp_do_placeholder_facebook_button',
        ),

        // listings [only] placeholders
        'url_showad' => array(
            'callback' => 'awpcp_do_placeholder_url',
        ),
        'addetailssummary' => array(
            'callback' => 'awpcp_do_placeholder_excerpt',
        ),
        'awpcp_city_display' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'awpcp_state_display' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'awpcp_country_display' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'awpcp_display_price' => array(
            'callback' => 'awpcp_do_placeholder_price',
        ),
        'awpcpadpostdate' => array(
            'callback' => 'awpcp_do_placeholder_legacy_dates',
        ),
        'imgblockwidth' => array(
            'callback' => 'awpcp_do_placeholder_images',
        ),
        'awpcp_image_name_srccode' => array(
            'callback' => 'awpcp_do_placeholder_images',
        ),

        'awpcp_display_adviews' => array(
            'callback' => 'awpcp_do_placeholder_legacy_views',
        ),
    );

    /* new placeholders added in AWPCP 3.0 */
    $placeholders = array(
        // common placeholders
        'url' => array(),
        'title' => array(),
        'title_link' => array(
            'callback' => 'awpcp_do_placeholder_title',
        ),
        'category_url' => array(),
        // 'category_link' => array(),
        'category_name' => array(),
        'parent_category_url' => array(),
        'parent_category_name' => array(),
        'categories' => array(),
        'details' => array(),
        'excerpt' => array(),
        'contact_name' => array(),
        'contact_phone' => array(
            'callback' => 'awpcp_do_placeholder_contact_phone',
        ),
        'contact_url' => array(),
        'website_link' => array(),
        'website_url' => array(),
        // 'city' => array(),
        // 'state' => array(),
        // 'country' => array(),
        'county' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'region' => array(
            'callback' => 'awpcp_do_placeholder_location',
        ),
        'price' => array(),
        'start_date' => array(
            'callback' => 'awpcp_do_placeholder_dates',
        ),
        'end_date' => array(
            'callback' => 'awpcp_do_placeholder_dates',
        ),
        'posted_date' => array(
            'callback' => 'awpcp_do_placeholder_dates',
        ),
        'last_updated_date' => array(
            'callback' => 'awpcp_do_placeholder_dates',
        ),
        'renewed_date' => array(
            'callback' => 'awpcp_do_placeholder_dates',
        ),

        'featured_image' => array(
            'callback' => 'awpcp_do_placeholder_images',
        ),

        'views' => array(),

        'extra_fields' => array(
            'callback' => 'awpcp_do_placeholder_extra_fields',
        ),

        'twitter_button' => array(),
        'twitter_button_url' => array(),
        'facebook_button' => array(),
        'facebook_button_url' => array(),

        // single ad [only] placeholders
        'images' => array(),
        'adsense' => array(),
        'flag_link' => array(),

        // listings [only] placeholders
        'thumbnail_width' => array(
            'callback' => 'awpcp_do_placeholder_images',
        ),
    );

    $placeholders = array_merge($legacy_placeholders, $placeholders);
    $placeholders = apply_filters('awpcp-content-placeholders', $placeholders);

    foreach ($placeholders as $placeholder => $params) {
        if (!isset($placeholders[$placeholder]['callback'])) {
            $placeholders[$placeholder]['callback'] = "awpcp_do_placeholder_{$placeholder}";
        }
    }
    krsort($placeholders);

    return $placeholders;
}

/**
 * @since 3.0
 */
function awpcp_do_placeholders($ad, $content, $context) {
    $original_content = $content;

    // remove old $quers/ placeholders
    $content = str_replace('$quers/', '', $content);

    $placeholders = awpcp_content_placeholders();
    $placeholders_names = array_keys($placeholders);
    $pattern = sprintf('\$%s', join('|\$', array_map('preg_quote', $placeholders_names)));

    preg_match_all("/$pattern/s", $content, $matches);

    $processed = array();
    foreach ($matches[0] as $match) {
        if (isset($processed[$match])) continue;

        $placeholder = trim($match, '$');
        $callback = $placeholders[$placeholder]['callback'];

        if ( is_callable( $callback ) ) {
            $replacement = call_user_func($callback, $ad, $placeholder, $context);
            $content = str_replace($match, $replacement, $content);
            $processed[$match] = true;
        } else {
        }
    }

    return $content;
}

/**
 * @since 3.0
 */
function awpcp_do_placeholder_url($ad, $placeholder) {
    return esc_url( awpcp_get_listing_renderer()->get_view_listing_url( $ad ) );
}

/**
 * @since 3.0
 */
function awpcp_do_placeholder_title($ad, $placeholder) {
    $url = awpcp_get_listing_renderer()->get_view_listing_url( $ad );

    $replacements['ad_title'] = sprintf( '<a href="%s">%s</a>', esc_attr( $url ), esc_html( $ad->get_title() ) );
    $replacements['title'] = esc_html( $ad->get_title() );
    $replacements['title_link'] = $replacements['ad_title'];

    return $replacements[$placeholder];
}

/**
 * @since 3.0
 */
function awpcp_do_placeholder_category_name($ad, $placeholder) {
    return esc_html( stripslashes( get_adcatname( $ad->ad_category_id ) ) );
}

/**
 * @since 3.0
 */
function awpcp_do_placeholder_category_url($ad, $placeholder) {
    return url_browsecategory($ad->ad_category_id);
}

/**
 * @since 3.2
 */
function awpcp_do_placeholder_parent_category_name( $ad, $placeholder ) {
    if ( $ad->ad_category_parent_id > 0 ) {
        $parent_category_name = stripslashes( get_adcatname( $ad->ad_category_parent_id ) );
        $parent_category_name = esc_html( $parent_category_name );
    } else {
        $parent_category_name = '';
    }

    return $parent_category_name;
}

/**
 * @since 3.2
 */
function awpcp_do_placeholder_parent_category_url( $ad, $placeholder ) {
    return $ad->ad_category_parent_id > 0 ? url_browsecategory( $ad->ad_category_parent_id ) : '';
}

/**
 * @since 3.3
 */
function awpcp_do_placeholder_categories( $listing, $placeholder ) {
    $categories_ids = array_filter( array( $listing->ad_category_id, $listing->ad_category_parent_id ) );
    $categories = awpcp_categories_collection()->find( array( 'id' => $categories_ids ) );

    $links = array( 'parent-category' => '', 'category' => '' );

    foreach ( $categories as $category ) {
        if ( $listing->ad_category_parent_id == $category->id ) {
            $category_type = 'parent-category';
        } else {
            $category_type = 'category';
        }

        $link = '<a href="<category-url>"><category-name></a>';
        $link = str_replace( '<category-url>', esc_attr( url_browsecategory( $category->id ), $link ) );
        $link = str_replace( '<category-name>', esc_html( $category->name ), $link );

        $links[ $category_type ] = $link;
    }

    $output = '<span class="awpcp-listing-categories"><categories></span>';
    $output = str_replace( '<categories>', implode( ' / ', array_filter( $links ) ), $output );

    return $output;
}

/**
 * @since 3.0
 */
function awpcp_do_placeholder_details($ad, $placeholder) {
    static $replacements = array();

    if (isset($replacements[$ad->ad_id])) {
        return $replacements[$ad->ad_id][$placeholder];
    }

    $placeholders['addetails'] = apply_filters('awpcp-ad-details', stripslashes_deep($ad->ad_details));

    if (get_awpcp_option('hyperlinkurlsinadtext')) {
        $pattern = '#(?<!")(http://[^\s]+)(?!")#';
        $nofollow = get_awpcp_option('visitwebsitelinknofollow') ? 'rel="nofollow"' : '';
        $link = sprintf('<a %s href="$1">$1</a>', $nofollow);
        $placeholders['addetails'] = preg_replace($pattern, $link, $placeholders['addetails']);
    }

    $placeholders['addetails'] = nl2br($placeholders['addetails']);
    $placeholders['details'] = $placeholders['addetails'];

    $replacements[$ad->ad_id] = $placeholders;

    return $replacements[$ad->ad_id][$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_excerpt($ad, $placeholder) {
    $word_count = get_awpcp_option( 'words-in-listing-excerpt' );
    $details = stripslashes( $ad->ad_details );

    $replacements['addetailssummary'] = wp_trim_words( $details, $word_count, '' );
    $replacements['excerpt'] = wp_trim_words( $details, $word_count );

    return $replacements[$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_contact_name($ad, $placeholder) {
    if ( get_awpcp_option( 'hidelistingcontactname' ) == 1 && ! is_user_logged_in() ) {
        $contact_name = __( 'Seller', 'AWPCP' );
    } else {
        $contact_name = $ad->ad_contact_name;
    }

    return esc_html( stripslashes( $contact_name ) );
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_website_url($ad, $placeholder) {
    return $ad->websiteurl;
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_website_link($ad, $placeholder) {
    if ( ( get_awpcp_option( 'displaywebsitefieldreqpriv' ) != 1 || is_user_logged_in() ) && !empty( $ad->websiteurl ) ) {
        $nofollow = get_awpcp_option('visitwebsitelinknofollow') ? 'rel="nofollow"' : '';
        $escaped_label = esc_html( __( 'Visit Website', 'AWPCP' ) );
        $escaped_url = awpcp_esc_attr( $ad->websiteurl );

        $content = '<br/><a %s href="%s" target="_blank">%s</a>';
        $content = sprintf( $content, $nofollow, $escaped_url, $escaped_label );
        $replacements['awpcpvisitwebsite'] = $content;

        $content = '<a %s href="%s" target="_blank">%s</a>';
        $content = sprintf( $content, $nofollow, $escaped_url, $escaped_label );
        $replacements['website_link'] = $content;
    } else {
        $replacements['awpcpvisitwebsite'] = '';
        $replacements['website_link'] = '';
    }

    return $replacements[$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_price($ad, $placeholder) {
    $price = empty($ad->ad_item_price) ? 0 : ($ad->ad_item_price / 100);

    $show_price_field = get_awpcp_option( 'displaypricefield' ) == 1;
    $user_can_see_price_field = is_user_logged_in() || get_awpcp_option( 'price-field-is-restricted' ) == 0;

    if ( get_awpcp_option( 'hide-price-field-if-empty' ) && $price <= 0 ) {
        $show_price_field = false;
    }

    $replacements = array();

    if ( $show_price_field && $user_can_see_price_field && $price >= 0 ) {
        $escaped_label = esc_html( __( 'Price', 'AWPCP' ) );
        $escaped_currency = esc_html( awpcp_format_money( $price ) );
        // single ad
        $content = '<div class="showawpcpadpage"><label>%s</label>: <strong>%s</strong></div>';
        $replacements['aditemprice'] = sprintf($content, $escaped_label, $escaped_currency);
        // listings
        $replacements['awpcp_display_price'] = sprintf('%s: %s', $escaped_label, $escaped_currency);

        $replacements['price'] = $escaped_currency;
    }

    return awpcp_array_data( $placeholder, '', $replacements );
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_dates($ad, $placeholder) {
    $replacements['start_date'] = awpcp_datetime( 'awpcp-date', $ad->ad_startdate );
    $replacements['end_date'] = awpcp_datetime( 'awpcp-date', $ad->ad_enddate );
    $replacements['posted_date'] = awpcp_datetime( 'awpcp-date', $ad->ad_postdate );
    $replacements['last_updated_date'] = awpcp_datetime( 'awpcp-date', $ad->ad_last_updated );
    $replacements['renewed_date'] = awpcp_datetime( 'awpcp-date', $ad->renewed_date );

    return $replacements[$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_images($ad, $placeholder) {
    global $wpdb;
    global $awpcp_imagesurl;

    static $replacements = array();

    if (isset($replacements[$ad->ad_id])) {
        return $replacements[$ad->ad_id][$placeholder];
    }

    $placeholders = array(
        'featureimg' => '',
        'awpcpshowadotherimages' => '',
        'images' => '',
        'awpcp_image_name_srccode' => '',
    );

    $url = awpcp_get_listing_renderer()->get_view_listing_url( $ad );
    $thumbnail_width = get_awpcp_option('displayadthumbwidth');

    if (get_awpcp_option('imagesallowdisallow') == 1) {
        $images_uploaded = $ad->count_image_files();
        $primary_image = awpcp_media_api()->get_ad_primary_image( $ad );

        if ($primary_image) {
            $large_image = $primary_image->get_url( 'large' );
            $thumbnail = $primary_image->get_url( 'primary' );

            if (get_awpcp_option('show-click-to-enlarge-link', 1)) {
                $link = '<a class="thickbox enlarge" href="%s">%s</a>';
                $link = sprintf($link, $large_image, __('Click to enlarge image.', 'AWPCP'));
            } else {
                $link = '';
            }

            // single ad
            $content = '<div class="awpcp-ad-primary-image">';
            $content.= '<a class="thickbox thumbnail" href="%s">';
            $content.= '<img class="thumbshow" src="%s"/>';
            $content.= '</a>%s';
            $content.= '</div>';

            $placeholders['featureimg'] = sprintf($content, esc_attr($large_image),
                                                            esc_attr($thumbnail),
                                                            $link);

            // listings
            $content = '<a href="%s"><img src="%s" width="%spx" border="0" alt="%s" /></a>';
            $content = sprintf($content, $url, $thumbnail, $thumbnail_width, awpcp_esc_attr($ad->ad_title));

            $placeholders['awpcp_image_name_srccode'] = $content;
        }

        if ($images_uploaded >= 1) {
            $results = awpcp_media_api()->find_public_images_by_ad_id( $ad->ad_id );

            $columns = get_awpcp_option('display-thumbnails-in-columns', 0);
            $rows = $columns > 0 ? ceil(count($results) / $columns) : 0;
            $shown = 0;

            $images = array();
            foreach ($results as $image) {
                $large_image = $image->get_url( 'large' );
                $thumbnail = $image->get_url( 'thumbnail' );

                if ($columns > 0) {
                    $css = join(' ', awpcp_get_grid_item_css_class(array(), $shown, $columns, $rows));
                } else {
                    $css = '';
                }

                $content = '<li class="%s">';
                $content.= '<a class="thickbox" href="%s">';
                $content.= '<img class="thumbshow" src="%s" />';
                $content.= '</a>';
                $content.= '</li>';

                $images[] = sprintf($content, esc_attr($css),
                                              esc_attr($large_image),
                                              esc_attr($thumbnail));

                $shown = $shown + 1;
            }

            $placeholders['awpcpshowadotherimages'] = join('', $images);

            $content = '<ul class="awpcp-single-ad-images">%s</ul>';
            $placeholders['images'] = sprintf($content, $placeholders['awpcpshowadotherimages']);
        }
    }

    // fallback thumbnail
    if ( get_awpcp_option( 'imagesallowdisallow' ) == 1 && empty( $placeholders['awpcp_image_name_srccode'] ) ) {
        $thumbnail = sprintf('%s/adhasnoimage.png', $awpcp_imagesurl);
        $content = '<a href="%s"><img src="%s" width="%spx" border="0" alt="%s" /></a>';
        $content = sprintf($content, $url, $thumbnail, $thumbnail_width, awpcp_esc_attr($ad->ad_title));

        $placeholders['awpcp_image_name_srccode'] = $content;
    }

    $placeholders['featured_image'] = $placeholders['featureimg'];
    $placeholders['imgblockwidth'] = "{$thumbnail_width}px";
    $placeholders['thumbnail_width'] = "{$thumbnail_width}px";

    $replacements[$ad->ad_id] = $placeholders;

    return $replacements[$ad->ad_id][$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_views($ad, $placeholder) {
    return $ad->ad_views;
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_legacy_dates($ad, $placeholder) {
    $replacements['ad_startdate'] = awpcp_datetime( 'awpcp-date', $ad->ad_startdate );
    $replacements['ad_postdate'] = awpcp_datetime( 'awpcp-date', $ad->ad_postdate );
    $replacements['awpcpadpostdate'] = sprintf('%s<br/>', $replacements['ad_postdate']);

    return $replacements[$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_location($ad, $placeholder) {
    $regions = AWPCP_Ad::get_ad_regions_names( $ad->ad_id );

    $cities = array();
    $states = array();
    $villages = array();
    $countries = array();
    $places = array();

    if ( get_awpcp_option( 'show-city-field-before-county-field' ) ) {
        $order = array( 'country', 'state', 'city', 'county' );
    } else {
        $order = array( 'country', 'state', 'county', 'city' );
    }

    foreach ( $regions as $region ) {
        if ( !empty( $region['city'] ) ) {
            $cities[] = stripslashes_deep( $region['city'] );
        }
        if ( !empty( $region['county'] ) ) {
            $villages[] = stripslashes_deep( $region['county'] );
        }
        if ( !empty( $region['state'] ) ) {
            $states[] = stripslashes_deep( $region['state'] );
        }
        if ( !empty( $region['country'] ) ) {
            $countries[] = stripslashes_deep( $region['country'] );
        }

        $place = array();
        foreach( $order as $field ) {
            if ( ! empty( $region[ $field ] ) ) {
                $place[] = stripslashes_deep( $region[ $field ] );
            }
        }

        $places[] = $place;
    }

    if ( !empty( $cities ) ) {
        $replacements['city'] = join( ', ', $cities );
    }
    if ( !empty( $states ) ) {
        $replacements['state'] = join( ', ', $states );
    }
    if ( !empty( $villages ) ) {
        $replacements['county'] = join( ', ', $villages );
        $replacements['village'] = $replacements['county'];
    }
    if ( !empty( $countries ) ) {
        $replacements['country'] = join( ', ', $countries );
    }

    $location = array();
    foreach ( $places as $place ) {
        $location[] = join( ', ', $place );
    }
    $location = join( '; ', $location );

    if ( !empty( $location ) ) {
        $replacements['location'] = sprintf( '<br/><label>%s</label>: %s', __( 'Location', 'AWPCP' ), $location );
        $replacements['region'] = $location;
    } else {
        $replacements['location'] = '';
        $replacements['region'] = '';
    }

    if (!empty($replacements['city'])) {
        $replacements['awpcp_city_display'] = sprintf('%s<br/>', $replacements['city']);
    } else {
        $replacements['awpcp_city_display'] = '';
    }

    if (!empty($replacements['state'])) {
        $replacements['awpcp_state_display'] = sprintf('%s<br/>', $replacements['state']);
    } else {
        $replacements['awpcp_state_display'] = '';
    }

    if (!empty($replacements['country'])) {
        $replacements['awpcp_country_display'] = sprintf('%s<br/>', $replacements['country']);
    } else {
        $replacements['awpcp_country_display'] = '';
    }

    return $replacements[$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_legacy_views($ad, $placeholder) {
    if (get_awpcp_option('displayadviews')) {
        // single ad
        $views = get_numtimesadviewd($ad->ad_id);
        $text = _n('This Ad has been viewed %d time.', 'This Ad has been viewed %d times.', $views, 'AWPCP');
        $replacements['awpcpadviews'] = sprintf('<div class="adviewed">%s</div>', sprintf($text, $views));

        // listings
        $content = sprintf(__('Total views: %d', 'AWPCP'), $views);
        $replacements['awpcp_display_adviews'] = sprintf('%s<br/>', $content);
    } else {
        $replacements['awpcpadviews'] = '';
        $replacements['awpcp_display_adviews'] = '';
    }

    return $replacements[$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_extra_fields($ad, $placeholder, $context) {
    global $hasextrafieldsmodule;

    if ($hasextrafieldsmodule == 1) {
        $single = $context === 'single' ? true : false;
        $replacements['awpcpextrafields'] = display_x_fields_data( $ad->ad_id, $single );
    } else {
        $replacements['awpcpextrafields'] = '';
    }

    $replacements['extra_fields'] = $replacements['awpcpextrafields'];

    return $replacements[$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_contact_url($ad, $placeholder) {
    return awpcp_get_reply_to_ad_url($ad->ad_id, $ad->ad_title);
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_contact_phone($ad, $placeholder) {
    if (!empty($ad->ad_contact_phone)) {
        $phone = $ad->ad_contact_phone;

        if ( get_awpcp_option( 'displayphonefieldpriv') == 1 && !is_user_logged_in() ) {
            $allowed = intval( strlen( $phone ) * 0.4 );
            $phone = substr($phone, 0, $allowed) . str_repeat('X', strlen( $phone ) - $allowed );
        }

        $content = sprintf( '<br/><label>%s</label>: %s',
                            __('Phone', 'AWPCP'),
                            $phone );
        $replacements['adcontactphone'] = $content;
        $replacements['contact_phone'] = $phone;
    } else {
        $replacements['adcontactphone'] = '';
        $replacements['contact_phone'] = '';
    }

    return $replacements[$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_adsense($ad, $placeholder) {
    static $replacements = array();

    if (isset($replacements[$ad->ad_id])) {
        return $replacements[$ad->ad_id][$placeholder];
    }

    if (get_awpcp_option('useadsense')) {
        $content = '<div class="cl-adsense">%s</div>';
        $placeholders['adsense'] = sprintf($content, get_awpcp_option('adsense'));
    } else {
        $placeholders['adsense'] = '';
    }

    $placeholders['showadsense1'] = '';
    $placeholders['showadsense2'] = '';
    $placeholders['showadsense3'] = '';

    switch (get_awpcp_option('adsenseposition')) {
        case 1:
            $placeholders['showadsense1'] = $placeholders['adsense'];
            break;
        case 2:
            $placeholders['showadsense2'] = $placeholders['adsense'];
            break;
        case 3:
            $placeholders['showadsense3'] = $placeholders['adsense'];
            break;
    }

    $replacements[$ad->ad_id] = $placeholders;

    return $replacements[$ad->ad_id][$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_flag_link($ad, $placeholder) {
    $content = '<a id="flag_ad_link" href="#" data-ad="%d">%s</a>';
    $replacements['flagad'] = sprintf($content, $ad->ad_id, __('Flag Ad', 'AWPCP'));
    $replacements['flag_link'] = $replacements['flagad'];

    return $replacements[$placeholder];
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_twitter_button($ad, $placeholder) {
    $url = awpcp_do_placeholder_twitter_button_url( $ad, 'twitter_button_url' );

    $button = '<div class="tw_button awpcp_tweet_button_div">';
    $button.= '<a href="' . $url . '" rel="nofollow" class="twitter-share-button" target="_blank">';
    $button.= __('Tweet This', 'AWPCP');
    $button.= '</a>';
    $button.= '</div>';

    return $button;
}


/**
 * @since 3.2.2
 */
function awpcp_do_placeholder_twitter_button_url( $ad, $placeholder ) {
    $url = awpcp_get_listing_renderer()->get_view_listing_url( $ad );

    return add_query_arg(array(
        'url' => urlencode( $url ),
        'text' => urlencode($ad->get_title()),
    ), 'http://twitter.com/share');
}


/**
 * @since 3.0
 */
function awpcp_do_placeholder_facebook_button($ad, $placeholder) {
    $href = awpcp_do_placeholder_facebook_button_url( $ad, 'facebook_button_url' );

    $button = '<div class="tw_button awpcp_tweet_button_div">';
    $button.= '<a href="%s" class="facebook-share-button" title="%s" target="_blank"></a>';
    $button.= '</div>';

    return sprintf($button, $href, __('Share on Facebook', 'AWPCP'));
}


/**
 * @since 3.2.2
 */
function awpcp_do_placeholder_facebook_button_url( $ad, $placeholder ) {
    $info = awpcp_get_ad_share_info( $ad->ad_id );
    return sprintf( 'http://www.facebook.com/sharer/sharer.php?u=%s', urlencode( $info['url'] ) );
}


/**
 * @since 3.0
 */
function awpcp_replace_content_placeholders($content, $replacements) {
    $placeholders = awpcp_content_placeholders();

    // make sure placeholders with longer names appear first.
    krsort($replacements);

    foreach ($replacements as $placeholder => $value) {
        if (!isset($placeholders[$placeholder])) continue;

        foreach ($placeholders[$placeholder]['aliases'] as $alias) {
            $content = str_replace("\${$alias}", "$value", $content);
        }
    }

    return $content;
}
