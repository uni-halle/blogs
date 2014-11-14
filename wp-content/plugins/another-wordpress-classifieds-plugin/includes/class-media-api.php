<?php

function awpcp_media_api() {
    return AWPCP_MediaAPI::instance();
}

class AWPCP_MediaAPI {
    private static $instance = null;

    private function __construct() {}

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new AWPCP_MediaAPI();
        }
        return self::$instance;
    }

    private function translate( $object ) {
        $properties = array(
            'id' => awpcp_get_property( $object, 'id', null ),
            'ad_id' => awpcp_get_property( $object, 'ad_id', null ),
            'name' => awpcp_get_property( $object, 'name', null ),
            'path' => awpcp_get_property( $object, 'path', null ),
            'mime_type' => awpcp_get_property( $object, 'mime_type', null ),
            'enabled' => awpcp_get_property( $object, 'enabled', null ),
            'status' => awpcp_get_property( $object, 'status', null ),
            'is_primary' => awpcp_get_property( $object, 'is_primary', null ),
        );

        $data = array();
        foreach ( $properties as $name => $value ) {
            if ( ! is_null( $value ) ) {
                $data[ $name ] = $value;
            }
        }

        return $data;
    }

    public function create( $args ) {
        extract( wp_parse_args( $args, array(
            'enabled' => true,
            'status' => null,
            'is_primary' => false,
        ) ) );

        $image_mime_types = awpcp_get_image_mime_types();

        if ( is_null( $status ) ) {
            if ( ! awpcp_current_user_is_admin() && in_array( $mime_type, $image_mime_types ) && get_awpcp_option( 'imagesapprove' ) ) {
                $status = AWPCP_Media::STATUS_AWAITING_APPROVAL;
            } else {
                $status = AWPCP_Media::STATUS_APPROVED;
            }
        }

        $data = compact( 'ad_id', 'name', 'path', 'mime_type', 'enabled', 'status', 'is_primary' );

        if ( $insert_id = $this->save( $data ) ) {
            return $this->find_by_id( $insert_id );
        } else {
            return null;
        }
    }

    /**
     * @return      an integer (new row id) if a new row was added to the table.
     *              true if the media was properly updated.
     *              false if the data couldn't be saved.
     * @since 3.0.2
     */
    public function save( $data ) {
        global $wpdb;

        if ( is_object( $data ) ) {
            $data = $this->translate( $data );
        }

        $data = $this->sanitize( $data );

        if ( isset( $data[ 'id' ] ) ) {
            $result = $wpdb->update( AWPCP_TABLE_MEDIA, $data, array( 'id' => $data[ 'id' ] ) );
            $result = $result !== false;
        } else {
            $result = $wpdb->insert( AWPCP_TABLE_MEDIA, $data );
            $result = $result === false ? false : $wpdb->insert_id;
        }

        return $result;
    }

    private function sanitize( $data ) {
        if ( ! isset( $data['created'] ) || ! awpcp_is_mysql_date( $data['created'] ) ) {
            $data['created'] = awpcp_datetime();
        }

        $data['enabled'] = absint( $data['enabled'] );
        $data['is_primary'] = absint( $data['is_primary'] );

        return $data;
    }

    public function delete( $media ) {
        global $wpdb;

        $info = awpcp_utf8_pathinfo( AWPCPUPLOADDIR . $media->name );
        $filename = preg_replace( "/\.{$info['extension']}/", '', $info['basename'] );

        $filenames = array(
            AWPCPUPLOADDIR . "{$info['basename']}",
            AWPCPUPLOADDIR . "{$filename}-large.{$info['extension']}",
            AWPCPTHUMBSUPLOADDIR . "{$info['basename']}",
            AWPCPTHUMBSUPLOADDIR . "{$filename}-primary.{$info['extension']}",
        );

        foreach ( $filenames as $filename ) {
            if ( file_exists( $filename ) ) {
                @unlink( $filename );
            }
        }

        $query = 'DELETE FROM ' . AWPCP_TABLE_MEDIA . ' WHERE id = %d';
        $result = $wpdb->query( $wpdb->prepare( $query, $media->id ) );

        return $result === false ? false : true;
    }

    public function enable( $media ) {
        $media->enabled = true;
        return $this->save( $media );
    }

    public function disable( $media ) {
        $media->enabled = false;
        return $this->save( $media );
    }

    /**
     * @since 3.2.2
     */
    public function approve( $media ) {
        $media->status = AWPCP_Media::STATUS_APPROVED;
        return $this->save( $media );
    }

    /**
     * @since 3.2.2
     */
    public function reject( $media ) {
        $media->status = AWPCP_Media::STATUS_REJECTED;
        return $this->save( $media );
    }

    public function set_ad_primary_image( $ad, $media ) {
        global $wpdb;

        $query = 'UPDATE ' . AWPCP_TABLE_MEDIA . ' SET is_primary = 0 WHERE ad_id = %d';

        if ( $wpdb->query( $wpdb->prepare( $query, $ad->ad_id ) ) === false ) {
            return false;
        }

        $query = 'UPDATE ' . AWPCP_TABLE_MEDIA . ' SET is_primary = 1 WHERE ad_id = %d AND id = %d';
        $query = $wpdb->prepare( $query, $ad->ad_id, $media->id );

        return $wpdb->query( $query ) !== false;
    }

    public function get_ad_primary_image( $ad ) {
        global $wpdb;

        $image_mime_types = awpcp_get_image_mime_types();

        $results = $this->query( array(
            'ad_id' => $ad->ad_id,
            'is_primary' => true,
            'enabled' => true,
            'status' => AWPCP_Media::STATUS_APPROVED,
            'mime_type' => $image_mime_types,
        ) );

        if ( empty( $results ) ) {
            $results = $this->query( array(
                'ad_id' => $ad->ad_id,
                'enabled' => true,
                'status' => AWPCP_Media::STATUS_APPROVED,
                'mime_type' => $image_mime_types,
                'order' => array( 'id ASC' ),
            ) );
        }

        return empty( $results ) ? null : AWPCP_Media::create_from_object( $results[0] );
    }

    public function query( $args=array() ) {
        global $wpdb;

        extract( wp_parse_args( $args, array(
            'fields' => '*',
            'id' => false,
            'ad_id' => false,
            'mime_type' => false,
            'enabled' => null,
            'status' => null,
            'is_primary' => null,
            'order' => array( 'id ASC' ),
        ) ) );

        /*---------------------------------------------------------------------
         * Conditions
         */

        $conditions = array();

        if ( false !== $id ) {
            $conditions[] = $wpdb->prepare( 'id = %d', intval( $id ) );
        }

        if ( false !== $ad_id ) {
            $conditions[] = $wpdb->prepare( 'ad_id = %d', intval( $ad_id ) );
        }

        if ( is_array( $mime_type ) && ! empty( $mime_type ) ) {
            $conditions[] = "mime_type IN ('" . join( "', '", $mime_type ) . "')";
        } else if ( ! empty( $mime_type ) ) {
            $conditions[] = $wpdb->prepare( 'mime_type = %s', $mime_type );
        }

        if ( ! is_null( $status ) ) {
            $conditions[] = $wpdb->prepare( 'status = %s', $status );
        }

        if ( ! is_null( $enabled ) ) {
            $conditions[] = $wpdb->prepare( 'enabled = %d', (bool) $enabled );
        }

        if ( ! is_null( $is_primary ) ) {
            $conditions[] = $wpdb->prepare( 'is_primary = %d', (bool) $is_primary );
        }

        if ( empty( $conditions ) ) {
            $conditions[] = '1 = 1';
        }

        /*---------------------------------------------------------------------
         * Fields, Order
         */

        if ( $fields == 'count' ) {
            $fields = 'COUNT(*)';
        }

        $query = 'SELECT ' . $fields . ' FROM ' . AWPCP_TABLE_MEDIA . ' ';
        $query.= 'WHERE ' . join( ' AND ', $conditions ) . ' ';
        $query.= 'ORDER BY ' . join( ', ', $order );


        if ( $fields == 'COUNT(*)' ) {
            return $wpdb->get_var( $query );
        } else {
            $media = array();
            foreach ( $wpdb->get_results( $query ) as $item ) {
                $media[] = AWPCP_Media::create_from_object( $item );
            }
        }

        return $media;
    }

    public function find_by_id( $id ) {
        $results = self::query( array( 'id' => $id ) );
        return empty( $results ) ? null : array_shift( $results );
    }

    public function find_by_ad_id( $ad_id, $args=array() ) {
        return self::query( array_merge( $args, array( 'ad_id' => $ad_id ) ) );
    }

    public function count_images_by_ad_id( $ad_id ) {
        $mime_types = awpcp_get_image_mime_types();
        return self::query( array( 'fields' => 'count', 'ad_id' => $ad_id, 'mime_type' => $mime_types ) );
    }

    public function find_images_by_ad_id( $ad_id, $args=array() ) {
        $mime_types = awpcp_get_image_mime_types();

        return self::query( array_merge( $args, array(
            'ad_id' => $ad_id,
            'mime_type' => $mime_types,
        ) ) );
    }

    /**
     * @since 3.2.2
     */
    public function find_public_images_by_ad_id( $ad_id ) {
        $args = array(
            'status' => AWPCP_Media::STATUS_APPROVED,
            'enabled' => 1,
            'order' => array( 'is_primary ASC', 'name ASC' ),
        );

        return $this->find_images_by_ad_id( $ad_id, $args );
    }

    /**
     * @since 3.2.2
     */
    public function find_images_awaiting_approval_by_ad_id( $ad_id ) {
        $args = array(
            'status' => AWPCP_Media::STATUS_AWAITING_APPROVAL,
            'order' => array( 'is_primary ASC', 'name ASC' ),
        );

        return $this->find_images_by_ad_id( $ad_id, $args );
    }

    /**
     * @since 3.3
     */
    public function listing_has_primary_image( $listing ) {
        $image = $this->get_ad_primary_image( $listing );

        if ( ! is_null( $image ) ) {
            return $image->is_primary;
        } else {
            return false;
        }
    }
}
