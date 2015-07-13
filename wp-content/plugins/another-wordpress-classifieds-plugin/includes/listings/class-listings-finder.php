<?php

function awpcp_listings_finder() {
    return new AWPCP_ListingsFinder( $GLOBALS['wpdb'] );
}

class AWPCP_ListingsFinder {

    private $db;

    private $clauses = array();

    public function __construct( $db ) {
        $this->db = $db;
    }

    public function find( $user_query ) {
        $this->reset_query();

        $query = apply_filters( 'awpcp-find-listings-query', $this->normalize_query( $user_query ) );

        $select = $this->build_select_clause( $query );
        $where = $this->build_where_clause( $query );
        $limit = $this->build_limit_clause( $query );
        $order = $this->build_order_clause( $query );

        if ( $query['fields'] == 'count' ) {
            return $this->db->get_var( $this->prepare_query( "$select $where $order" ) );
        } else if ( $query['raw'] ) {
            return $this->db->get_results( $this->prepare_query( "$select $where $order $limit" ) );
        } else {
            $items = $this->db->get_results( $this->prepare_query( "$select $where $order $limit" ) );
            return array_map( array( 'AWPCP_Ad', 'from_object' ), $items );
        }
    }

    private function reset_query() {
        $this->clauses = array();
    }

    private function normalize_query( $user_query ) {
        $query = wp_parse_args( $user_query, array(
            'context' => 'default',

            'fields' => '*',
            'raw' => false,

            'id' => null,
            'title' => null,
            'keyword' => null,

            'category_id' => null,
            'exclude_category_id' => null,
            'include_listings_in_children_categories' => true,

            'user' => null,
            'user_id' => null,

            'contact_name' => null,

            'price' => null,
            'min_price' => null,
            'max_price' => null,

            'region' => '',
            'country' => '',
            'state' => '',
            'city' => '',
            'county' => '',
            'regions' => array(),

            'payment_status' => null,
            'payer_email' => null,

            'disabled_date' => null,
            'end_date' => null,

            'disabled' => null,
            'verified' => null,
            'featured' => null,
            'flagged' => null,
            'awaiting_approval' => null,

            'have_media_awaiting_approval' => null,

            'reviewed' => null,

            'limit' => 0,
            'offset' => 0,
            'orderby' => 'default',
            'order' => 'DESC',
        ) );

        if ( ! is_array( $query['context'] ) ) {
            $query['context'] = array( $query['context'] );
        }

        $query['regions'] = $this->normalize_regions_query( $query );
        $query['limit'] = $query['limit'] === 0 ? get_awpcp_option( 'adresultsperpage', 10 ) : $query['limit'];

        return $query;
    }

    private function normalize_regions_query( $query ) {
        // search for a listing associated with a Region (of any kind) whose
        // name matches the given search value.
        $query['regions'][] = array( 'country' => $query['region'] );
        $query['regions'][] = array( 'state' => $query['region'] );
        $query['regions'][] = array( 'city' => $query['region'] );
        $query['regions'][] = array( 'county' => $query['region'] );

        // search for a listing associated with region hierarchy that matches
        // the given search values.
        $query['regions'][] = array(
            'country' => empty( $query['country'] ) ? '' : array( '=', $query['country'] ),
            'state' => empty( $query['state'] ) ? '' : array( '=', $query['state'] ),
            'city' => empty( $query['city'] ) ? '' : array( '=', $query['city'] ),
            'county' => empty( $query['county'] ) ? '' : array( '=', $query['county'] ),
        );

        return awpcp_array_filter_recursive( $query['regions'] );
    }

    private function build_select_clause( $query ) {
        if ( $query['fields'] == 'count' ) {
            $fields = 'COUNT( DISTINCT listings.`ad_id` )';
        } else if ( $query['fields'] == '*' ) {
            $fields = 'DISTINCT listings.*';
        } else {
            $fields = $query['fields'];
        }

        return "SELECT $fields FROM <listings-table> AS listings <join>";
    }

    private function build_where_clause( $query ) {
        $conditions = array(
            $this->build_id_condition( $query ),
            $this->build_title_condition( $query ),
            $this->build_keyword_condition( $query ),
            $this->build_category_condition( $query ),
            $this->build_user_condition( $query ),
            $this->build_contact_condition( $query ),
            $this->build_price_condition( $query ),
            $this->build_regions_condition( $query ),
            $this->build_payment_condition( $query ),
            $this->build_dates_condition( $query ),
            $this->build_status_condition( $query ),
            $this->build_media_conditions( $query ),
            $this->build_meta_conditions( $query ),
        );

        $conditions = apply_filters( 'awpcp-find-listings-conditions', $conditions, $query );

        $flattened_conditions = $this->flatten_conditions( $conditions, 'OR' );
        $where_conditions = $this->group_conditions( $flattened_conditions, 'AND' );

        return sprintf( 'WHERE %s', $where_conditions );
    }

    private function build_id_condition( $query ) {
        $conditions = array();

        if ( $query['id'] ) {
            $conditions[] = $this->build_condition_with_in_clause( 'listings.`ad_id`', $query['id'] );
        }

        return $conditions;
    }

    private function build_title_condition( $query ) {
        $conditions = array();

        if ( ! empty( $query['title'] ) ) {
            $conditions[] = $this->db->prepare( "listings.`ad_title` LIKE '%%%s%%'", $query['title'] );
        }

        return $conditions;
    }

    private function build_keyword_condition( $query ) {
        $conditions = array();

        if ( ! empty( $query['keyword'] ) ) {
            $sql = '( ad_title LIKE \'%%%1$s%%\' OR ad_details LIKE \'%%%1$s%%\' )';
            $conditions[] = $this->db->prepare( $sql, $query['keyword'] );
        }

        return apply_filters( 'awpcp-find-listings-keyword-conditions', $conditions, $query );
    }

    private function build_category_condition( $query ) {
        $conditions = array();

        if ( $query['category_id'] ) {
            if ( $query['include_listings_in_children_categories'] ) {
                $category_conditions = array(
                    $this->build_condition_with_in_clause( 'listings.`ad_category_id`', $query['category_id'] ),
                    $this->build_condition_with_in_clause( 'listings.`ad_category_parent_id`', $query['category_id'] ),
                );

                $conditions[] = $this->group_conditions( $category_conditions, 'OR' );
            } else {
                $conditions[] = $this->build_condition_with_in_clause( 'listings.`ad_category_id`', $query['category_id'] );
            }
        }

        if ( $query['exclude_category_id'] ) {
            if ( $query['include_listings_in_children_categories'] ) {
                $category_conditions = array(
                    $this->build_condition_with_not_in_clause( 'listings.`ad_category_id`', $query['exclude_category_id'] ),
                    $this->build_condition_with_not_in_clause( 'listings.`ad_category_parent_id`', $query['exclude_category_id'] ),
                );

                $conditions[] = $this->group_conditions( $category_conditions, 'AND' );
            } else {
                $conditions[] = $this->build_condition_with_not_in_clause( 'listings.`ad_category_id`', $query['exclude_category_id'] );
            }
        }

        return $this->group_conditions( $conditions, 'AND' );
    }

    private function build_condition_with_in_clause( $column, $value, $placeholder = '%d' ) {
        return $this->build_condition_with_inclusion_operators( $column, $value, 'IN', '=', $placeholder );
    }

    private function build_condition_with_inclusion_operators( $column, $value, $inclusion_operator, $comparison_operator, $placeholder ) {
        if ( is_array( $value ) && ! empty( $value ) ) {
            if ( count( $value ) == 1 ) {
                $single_value = array_shift( $value );
                return $this->db->prepare( "$column $comparison_operator $placeholder", $single_value );
            } else {
                $multiple_values = array();

                foreach ( $value as $v ) {
                    $multiple_values[] = $this->db->prepare( "$placeholder", $v );
                }

                return "$column $inclusion_operator ( " . implode( ', ', $multiple_values ) . ' )';
            }
        } else if ( ! empty( $value ) ) {
            return $this->db->prepare( "$column $comparison_operator $placeholder", $value );
        } else {
            return '';
        }
    }

    private function build_condition_with_not_in_clause( $colum, $value, $placeholder = '%d' ) {
        return $this->build_condition_with_inclusion_operators( $colum, $value, 'NOT IN', '!=', $placeholder );
    }

    private function build_user_condition( $query ) {
        $conditions = array();

        if ( ! empty( $query['user_id'] ) ) {
            $conditions[] = $this->db->prepare( 'listings.`user_id` = %d', $query['user_id'] );
        }

        if ( ! empty( $query['user'] ) ) {
            $user_conditions = array();

            $users_join = 'INNER JOIN ' . $this->db->users . ' ON ( listings.`user_id` = ID )';
            $this->add_join_clause( $users_join );
            $user_conditions[] = sprintf( "user_login LIKE '%%%s%%'", esc_sql( $query['user'] ) );

            $meta_query = $this->get_meta_sql(
                'user_name_meta',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'first_name',
                        'value' => esc_sql( $query['user'] ),
                        'compare' => 'LIKE',
                        'type' => 'CHAR',
                    ),
                    array(
                        'key' => 'last_name',
                        'value' => esc_sql( $query['user'] ),
                        'compare' => 'LIKE',
                        'type' => 'CHAR',
                    ),
                ),
                'user',
                $this->db->users,
                'ID'
            );
            $this->add_join_clause( $meta_query['join'] );
            $user_conditions[] = $meta_query['where'];

            $conditions[] = $this->group_conditions( $user_conditions, 'OR' );
        }

        return $conditions;
    }

    private function get_meta_sql( $name, $meta_query, $type, $primary_table, $primary_id_column ) {
        $query = get_meta_sql( $meta_query, $type, $primary_table, $primary_id_column );

        if ( function_exists( '_get_meta_table' ) ) {
            $meta_table = _get_meta_table( $type );

            $query['join'] = str_replace( $meta_table, $name, $query['join'] );
            $query['where'] = str_replace( $meta_table, $name, $query['where'] );

            $query['join'] = str_replace( "JOIN $name ON" , "JOIN $meta_table AS $name ON", $query['join'] );
        }

        return array(
            'join' => $query['join'],
            'where' => $this->clean_meta_query_condition( $query['where'] ),
        );
    }

    private function clean_meta_query_condition( $condition ) {
        $condition = preg_replace( "/(?:^ AND )|\n|\t/", '', $condition );
        $condition = preg_replace( '/\(\s*\(/', '(', $condition );
        $condition = preg_replace( '/\)\s*\)/', ')', $condition );

        return $condition;
    }

    private function build_contact_condition( $query ) {
        $conditions = array();

        if ( ! empty( $query['contact_name'] ) ) {
            $conditions[] = $this->db->prepare( 'listings.`ad_contact_name` = %s', $query['contact_name'] );
        }

        return $conditions;
    }

    private function build_price_condition( $query ) {
        $conditions = array();

        if ( strlen( $query['price'] ) ) {
            $conditions[] = $this->db->prepare( 'listings.`ad_item_price` = %d', $query['price'] * 100 );
        }

        if ( strlen( $query['min_price'] ) ) {
            $conditions[] = $this->db->prepare( 'listings.`ad_item_price` >= %d', $query['min_price'] * 100 );
        }

        if ( strlen( $query['max_price'] ) ) {
            $conditions[] = $this->db->prepare( 'listings.`ad_item_price` <= %d', $query['max_price'] * 100 );
        }

        return $this->group_conditions( $conditions, 'AND' );
    }

    private function build_regions_condition( $query ) {
        $conditions = array();

        if ( empty( $query['regions'] ) ) {
            return $conditions;
        }

        $this->add_join_clause(
            'INNER JOIN <listing-regions-table> AS listing_regions ON listings.`ad_id` = listing_regions.`ad_id`'
        );

        foreach ( $query['regions'] as $region ) {
            $region_conditions = array();

            foreach ( $region as $field => $search ) {
                // add support for exact search, passing a search values defined as array( '=', <region-name> ).
                if ( is_array( $search ) && count( $search ) == 2 && $search[0] == '=' ) {
                    $region_conditions[] = $this->db->prepare( "listing_regions.`$field` = %s", trim( $search[1] ) );
                } else if ( ! is_array( $search ) ) {
                    $region_conditions[] = $this->db->prepare( "listing_regions.`$field` LIKE '%%%s%%'", trim( $search ) );
                }
            }

            $conditions[] = $region_conditions;
        }

        return $this->flatten_conditions( $conditions, 'AND' );
    }

    private function add_join_clause( $clause ) {
        $this->clauses['join'][] = $clause;
    }

    private function build_payment_condition( $query ) {
        $conditions = array();

        if ( ! empty( $query['payment_status'] ) ) {
            if ( isset( $query['payment_status']['compare'] ) && $query['payment_status']['compare'] == 'not' ) {
                $conditions[] = $this->build_condition_with_not_in_clause( 'payment_status', $query['payment_status']['values'], '%s' );
            } else {
                $conditions[] = $this->build_condition_with_in_clause( 'payment_status', $query['payment_status'], '%s' );
            }
        }

        if ( ! empty( $query['payer_email'] ) ) {
            $conditions[] = $this->db->prepare( 'listings.`payer_email` = %s', $query['payer_email'] );
        }

        return $conditions;
    }

    private function build_dates_condition( $query ) {
        $conditions = array_merge(
            $this->build_date_condition( 'disabled_date', $query['disabled_date'] ),
            $this->build_date_condition( 'ad_enddate', $query['end_date'] )
        );

        return $conditions;
    }

    private function build_date_condition( $column_name, $sub_query ) {
        $conditions = array();

        if ( $sub_query == 'NULL' ) {
            $conditions[] = "$column_name IS NULL";
        } else if ( isset( $sub_query['compare'] ) ) {
            if ( $sub_query['compare'] == '<' ) {
                $conditions[] = $this->db->prepare( "$column_name < %s", $sub_query['value'] );
            }
        }

        return $conditions;
    }

    private function build_status_condition( $query ) {
        $conditions = array();

        if ( $query['disabled'] ) {
            $conditions[] = 'disabled = 1';
        } else if ( ! is_null( $query['disabled'] ) ) {
            $conditions[] = 'disabled = 0';
        }

        if ( $query['verified'] ) {
            $conditions[] = 'verified = 1';
        } else if ( ! is_null( $query['verified'] ) ) {
            $conditions[] = 'verified = 0';
        }

        if ( $query['featured'] ) {
            $conditions[] = 'listings.`is_featured_ad` = 1';
        } else if ( ! is_null( $query['featured'] ) ) {
            $conditions[] = 'listings.`is_featured_ad` = 0';
        }

        if ( $query['flagged'] ) {
            $conditions[] = 'listings.`flagged` = 1';
        } else if ( ! is_null( $query['flagged'] ) ) {
            $conditions[] = 'listings.`flagged` = 0';
        }

        return $this->group_conditions( $conditions, 'AND' );
    }

    private function build_media_conditions( $query ) {
        $conditions = array();

        if ( ! is_null( $query['have_media_awaiting_approval'] ) ) {
            $sql = 'INNER JOIN <media-table> AS listing_media ON ( listing_media.`ad_id` = listings.`ad_id` AND listing_media.`status` = %s )';
            $sql = $this->db->prepare( $sql, AWPCP_Media::STATUS_AWAITING_APPROVAL );
            $this->add_join_clause( $sql );
        }

        return $conditions;
    }

    private function build_meta_conditions( $query ) {
        if ( ! is_null( $query['reviewed'] ) ) {
            $meta_query = $this->get_meta_sql(
                'reviewed_meta',
                array(
                    'meta_query' => array(
                        'key' => 'reviewed',
                        'value' => $query['reviewed'],
                        'type' => 'UNSIGNED',
                        'compare' => '=',
                    ),
                ),
                'awpcp_ad',
                'listings',
                'ad_id'
            );
            $this->add_join_clause( $meta_query['join'] );

            $conditions[] = $meta_query['where'];
        } else {
            $conditions = array();
        }

        return $conditions;
    }

    private function flatten_conditions( $conditions, $connector = 'OR' ) {
        $flattened_conditions = array();

        foreach ( $conditions as $index => $condition ) {
            if ( ! empty( $condition ) ) {
                $flattened_conditions[] = $this->group_conditions( $condition, $connector );
            }
        }

        return $flattened_conditions;
    }

    private function group_conditions( $conditions, $connector = 'OR' ) {
        $conditions_count = count( $conditions );

        if ( is_array( $conditions ) && $conditions_count >= 1 ) {
            if ( $conditions_count > 1 ) {
                return '( ' . implode( " $connector ", $conditions ) . ' )';
            } else if ( $conditions_count == 1 ) {
                return array_pop( $conditions );
            }
        } else if ( ! is_array( $conditions ) ) {
            return $conditions;
        } else {
            return '';
        }
    }

    private function build_limit_clause( $query ) {
        if ( $query['limit'] > 0 ) {
            return sprintf( 'LIMIT %d, %d', $query['offset'], $query['limit'] );
        } else {
            return '';
        }
    }

    private function build_order_clause( $query ) {
        if ( ! is_null( $query['orderby'] ) ) {
            return $this->build_order_by_clause( $query['orderby'], $query['order'] );
        } else {
            return '';
        }
    }

    private function build_order_by_clause( $orderby, $order ) {
        $basedate = 'CASE WHEN renewed_date IS NULL THEN ad_startdate ELSE GREATEST(ad_startdate, renewed_date) END';
        $is_paid = 'CASE WHEN ad_fee_paid > 0 THEN 1 ELSE 0 END';

        switch ( $orderby ) {
            case 1:
                $parts = array( "$basedate DESC" );
                break;
            case 2:
                $parts = array( 'ad_title ASC' );
                break;
            case 3:
                $parts = array( "$is_paid DESC", "$basedate DESC" );
                break;
            case 4:
                $parts = array( "$is_paid DESC", 'ad_title ASC' );
                break;
            case 5:
                $parts = array( 'ad_views DESC', 'ad_title ASC' );
                break;
            case 6:
                $parts = array( 'ad_views DESC', "$basedate DESC" );
                break;
            case 7:
                $parts = array( 'ad_item_price DESC', "$basedate DESC" );
                break;
            case 8:
                $parts = array( 'ad_item_price ASC', "$basedate DESC" );
                break;
            case 9:
                $parts = array( "$basedate ASC" );
                break;
            case 10:
                $parts = array( 'ad_title DESC' );
                break;
            case 11:
                $parts = array( 'ad_views ASC', "ad_title ASC" );
                break;
            case 12:
                $parts = array( 'ad_views ASC', "$basedate ASC" );
                break;
            case 'title':
                $parts = array( 'ad_title %1$s' );
                break;
            case 'start-date':
                $parts = array( 'ad_startdate %1$s' );
                break;
            case 'end-date':
                $parts = array( 'ad_enddate %1$s' );
                break;
            case 'renewed-date':
                $parts = array( $basedate . ' %1$s', 'ad_startdate %1$s', 'ad_id %1$s' );
                break;
            case 'status':
                $parts = array( 'disabled %1$s', 'ad_startdate %1$s', 'ad_id %1$s' );
                break;
            case 'payment-term':
                $parts = array( 'adterm_id %1$s', 'ad_startdate %1$s', 'ad_id %1$s' );
                break;
            case 'payment-status':
                $parts = array( 'payment_status %1$s', 'ad_startdate %1$s', 'ad_id %1$s' );
                break;
            case 'featured-ad':
                $parts = array( 'is_featured_ad %1$s', 'ad_startdate %1$s', 'ad_id %1$s' );
                break;
            case 'owner':
                $parts = array( 'user_id %1$s', 'ad_startdate %1$s', 'ad_id %1$s' );
                break;
            case 'random':
                $parts = array( 'RAND() %1$s' );
                break;
            default:
                $parts = array( 'ad_postdate DESC', 'ad_title ASC' );
                break;
        }

        $parts = array_filter( apply_filters( 'awpcp-ad-order-conditions', $parts, $orderby, $order ) );

        return sprintf( 'ORDER BY %s', sprintf( implode( ', ', $parts ), $order ) );
    }

    private function prepare_query( $query ) {
        if ( ! empty( $this->clauses['join'] ) ) {
            $query = str_replace( '<join>', implode( ' ', $this->clauses['join'] ), $query );
        } else {
            $query = str_replace( '<join>', '', $query );
        }

        $query = str_replace( '<listings-table>', AWPCP_TABLE_ADS, $query );
        $query = str_replace( '<listing-regions-table>', AWPCP_TABLE_AD_REGIONS, $query );
        $query = str_replace( '<media-table>', AWPCP_TABLE_MEDIA, $query );

        return $query;
    }

    public function count( $query ) {
        return $this->find( array_merge( $query, array( 'fields' => 'count' ) ) );
    }
}
