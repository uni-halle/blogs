<?php

class AWPCP_Image {
    public function __construct($id, $ad_id, $name, $disabled, $is_primary) {
        $this->id = $id;
        $this->ad_id = $ad_id;
        $this->name = $name;
        $this->disabled = $disabled;
        $this->is_primary = $is_primary;
    }

    public static function create_from_object($object) {
        return new AWPCP_Image(
            $object->key_id,
            $object->ad_id,
            $object->image_name,
            $object->disabled,
            $object->is_primary
        );
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
