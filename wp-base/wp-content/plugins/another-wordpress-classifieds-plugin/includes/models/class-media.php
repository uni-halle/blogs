<?php

class AWPCP_Media {

    public function __construct( $id, $ad_id, $name, $path, $mime_type, $enabled, $is_primary, $created ) {
        $this->id = $id;
        $this->ad_id = $ad_id;
        $this->name = $name;
        $this->path = $path;
        $this->mime_type = $mime_type;
        $this->enabled = $enabled;
        $this->is_primary = $is_primary;
        $this->created = $created;
    }

    public static function create_from_object( $object ) {
        return new AWPCP_Media(
            $object->id,
            $object->ad_id,
            $object->name,
            $object->path,
            $object->mime_type,
            $object->enabled,
            $object->is_primary,
            $object->created
        );
    }

    public function is_image() {
        return in_array( $this->mime_type, awpcp_get_image_mime_types() );
    }

    public function is_primary() {
        return (bool) $this->is_primary;
    }

    public function get_url( $size = 'thumbnail' ) {
        $uploads_directories = awpcp_get_uploads_directories();
        $files_dir = $uploads_directories['files_dir'];

        $images = trailingslashit( AWPCPUPLOADURL );
        $thumbnails = trailingslashit( AWPCPTHUMBSUPLOADURL );

        $basename = $this->name;

        $original = $images . $basename;
        $thumbnail = $thumbnails . $basename;
        $suffix = empty( $size ) ? '.' : "-$size.";

        $info = pathinfo( $original );

        if ( $size == 'original' ) {
            $alternatives = array( $original );
        } else if ( $size == 'large' ) {
            $alternatives = array(
                str_replace( ".{$info['extension']}", "$suffix{$info['extension']}", $original ),
                $original
            );
        } else {
            $alternatives = array(
                str_replace( ".{$info['extension']}", "$suffix{$info['extension']}", $thumbnail ),
                $thumbnail,
                $original
            );
        }

        foreach ( $alternatives as $path ) {
            if ( file_exists( str_replace( AWPCPUPLOADURL, $files_dir, $path ) ) ) {
                return $path;
            }
        }

        return false;
    }

    public function get_icon_url() {
        $url = AWPCP_URL . '/resources/images/page_white_picture.png';
        return apply_filters( 'awpcp-get-file-icon-url', $url, $this );
    }

    public static function find($conditions=array()) {
        global $wpdb;

        $where = array();

        if (isset($conditions['id']))
            $where[] = $wpdb->prepare('key_id = %d', $conditions['id']);
        if (isset($conditions['ad_id']))
            $where[] = $wpdb->prepare('ad_id = %d', $conditions['ad_id']);
        if (empty($where))
            $where[] = '1 = 1';

        $query = 'SELECT * FROM ' . AWPCP_TABLE_ADPHOTOS . ' ';
        $query.= 'WHERE ' . join(' AND ', $where);

        $items = $wpdb->get_results($query);

        if ($items === false) return array();

        $images = array();
        foreach ($items as $item) {
            $images[] = self::create_from_object($item);
        }

        return $images;
    }

    public static function find_by_id($id) {
        $results = self::find(array('id' => $id));
        if (empty($results))
            return null;
        return array_shift($results);
    }

    public static function find_by_ad_id($ad_id) {
        return self::find(array('ad_id' => $ad_id)); 
    }

    public function save() {
        global $wpdb;

        $data = array(
            'key_id' => $this->id,
            'ad_id' => $this->ad_id,
            'image_name' => $this->name,
            'disabled' => $this->disabled,
            'is_primary' => $this->is_primary
        );

        $format = array(
            'key_id' => '%d',
            'ad_id' => '%d',
            'image_name' => '%s',
            'disabled' => '%d',
            'is_primary' => '%d'
        );

        if ($this->id) {
            $where = array('key_id' => $this->id);
            $result = $wpdb->update(AWPCP_TABLE_ADPHOTOS, $data, $where, $format);
        } else {
            $result = $wpdb->insert(AWPCP_TABLE_ADPHOTOS, $data, $format);
            $this->id = $wpdb->insert_id;
        }

        return $result === false ? false : true;
    }

    public function delete() {
        global $wpdb;

        $info = pathinfo(AWPCPUPLOADDIR . "{$this->name}");
        $filename = preg_replace("/\.{$info['extension']}/", '', $info['basename']);

        $filenames = array(
            AWPCPUPLOADDIR . "{$info['basename']}",
            AWPCPUPLOADDIR . "{$filename}-large.{$info['extension']}",
            AWPCPTHUMBSUPLOADDIR . "{$info['basename']}",
            AWPCPTHUMBSUPLOADDIR . "{$filename}-primary.{$info['extension']}",
        );

        foreach ($filenames as $filename) {
            if (file_exists($filename)) @unlink($filename);
        }

        $query = 'DELETE FROM ' . AWPCP_TABLE_ADPHOTOS . ' WHERE key_id = %d';
        $result = $wpdb->query($wpdb->prepare($query, $this->id));

        return $result === false ? false : true;
    }

    public function disable() {
        $this->disabled = 1;
        return $this->save();
    }

    public function enable() {
        $this->disabled = 0;
        return $this->save();
    }
}
