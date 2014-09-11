<?php

class AWPCP_Category {

    public function __construct($id, $name, $icon='', $order=0, $parent=0) {
        $this->id = $id;
        $this->parent = $parent;
        $this->name = $name;
        $this->icon = $icon;
        $this->order = $order;
    }

    public static function create_from_object($object) {
        return new AWPCP_Category(
            $object->category_id,
            $object->category_name,
            awpcp_get_property( $object, 'category_icon', '' ),
            awpcp_get_property( $object, 'category_order', '' ),
            $object->category_parent_id
        );
    }

    public static function query($args=array()) {
        global $wpdb;

        extract(wp_parse_args($args, array(
            'fields' => '*',
            'where' => '1 = 1',
            'orderby' => 'category_name',
            'order' => 'asc',
            'offset' => 0,
            'limit' => 0
        )));

        $query = 'SELECT %s FROM ' . AWPCP_TABLE_CATEGORIES . ' ';

        if ($fields == 'count') {
            $query = sprintf($query, 'COUNT(category_id)');
            $limit = 0;
        } else {
            $query = sprintf($query, $fields);
        }

        $query.= sprintf('WHERE %s ', $where);
        $query.= sprintf('ORDER BY %s %s ', $orderby, strtoupper($order));

        if ($limit > 0) {
            $query.= sprintf('LIMIT %s, %s', $offset, $limit);
        }

        if ($fields == 'count') {
            return $wpdb->get_var($query);
        } else {
            $items = $wpdb->get_results($query);
            $results = array();

            foreach($items as $item) {
                $results[] = self::create_from_object($item);
            }

            return $results;
        }
    }

    public static function find($conditions=array()) {
        $where = array();

        if (isset($conditions['id']) && is_array($conditions['id']))
            $where[] = sprintf( 'category_id IN (%s)', join( ',', $conditions['id'] ) );
        else if (isset($conditions['id']))
            $where[] = sprintf('category_id  = %d', $conditions['id']);

        if (isset($conditions['parent']))
            $where[] = sprintf('category_parent_id = %d', (int) $conditions['parent']);

        return self::query(array('where' => join(' AND ', $where)));
    }

    public static function find_by_id($category_id) {
        $args = array('where' => sprintf('category_id = %d', $category_id));
        $results = self::query($args);
        return !empty($results) ? array_shift($results) : null;
    }

    private function _get_children_id($parents=array()) {
        global $wpdb;

        if (!is_array($parents)) {
            $parents = array($parents);
        } else if (empty($parents)) {
            return array();
        }

        $sql = 'SELECT category_id FROM ' . AWPCP_TABLE_CATEGORIES . ' ';
        $sql.= 'WHERE category_parent_id IN (' . join(',', $parents) . ')';

        $children = $wpdb->get_col($sql);
        return array_merge($children, $this->_get_children_id($children));
    }

    public function get_children_id() {
        return $this->_get_children_id($this->id);
    }
}

function awpcp_categories_collection() {
    global $wpdb;
    return new AWPCP_CategoriesCollection( $wpdb );
}

class AWPCP_CategoriesCollection {

    private $db;

    public function __construct( $db ) {
        $this->db = $db;
    }

    public function save( $category ) {
        if ( is_null( $category->id ) || strlen( $category->id ) === 0 || $category->id == 0 ) {
            return $this->save_new_category( $category );
        } else {
            return $this->save_category( $category );
        }
    }

    private function save_new_category( $category ) {
        if ( $this->validate_category( $category ) ) {
            $data = $this->get_category_data( $category );

            $rows_affected = $this->db->insert( AWPCP_TABLE_CATEGORIES, $data, array( '%s', '%d', '%d' ) );

            if ( $rows_affected === false ) {
                $this->throw_database_exception( __( 'There was an error trying to save the category to the database.', 'AWPCP' ) );
            }

            $category->id = $this->db->insert_id;

            return $rows_affected;
        } else {
            return false;
        }
    }

    private function validate_category( $category ) {
        if ( empty( $category->name ) ) {
            throw new AWPCP_Exception( __( 'The name of the Category is required.', 'AWPCP' ) );
        }
        if ( $category->id > 0 && $category->id == $category->parent ) {
            throw new AWPCP_Exception( __( 'The ID of the parent category and the ID of the category must be different.' ) );
        }
        return true;
    }

    private function get_category_data( $category ) {
        return array(
            'category_name' => $category->name,
            'category_parent_id' => $category->parent,
            'category_order' => $category->order,
        );
    }

    private function throw_database_exception( $message ) {
        if ( $this->db->last_error ) {
            throw new AWPCP_Exception( $message . ' ' . $this->db->last_error );
        } else {
            throw new AWPCP_Exception( $message );
        }
    }

    private function save_category( $category ) {
        $existing_category_data = $this->get_existing_category_data( $category->id );

        $rows_updated = $this->update_category( $category );

        try {
            $this->update_category_parent_information_in_ads_table( $category );
        } catch ( AWPCP_Exception $e ) {
            $this->rollback_category_modifications( $category, $existing_category_data );
            $result = $this->update_category( $category );
            throw new AWPCP_Exception( $e->getMessage() );
        }

        return $rows_updated;
    }

    private function get_existing_category_data( $category_id ) {
        $sql = 'SELECT * FROM ' . AWPCP_TABLE_CATEGORIES . ' WHERE category_id = %d';
        $row = $this->db->get_row( $this->db->prepare( $sql, $category_id ) );

        if ( $row === false ) {
            $this->throw_database_exception( __( 'There was an error trying to retrieve existing category information.', 'AWPCP' ) );
        }

        return $row;
    }

    private function update_category( $category ) {
        if ( $this->validate_category( $category ) ) {
            $data = $this->get_category_data( $category );
            $where = array( 'category_id' => $category->id );
            $format = array( '%s', '%d', '%d' );

            $rows_updated = $this->db->update( AWPCP_TABLE_CATEGORIES, $data, $where, $format );

            if ( $rows_updated === false ) {
                $this->throw_database_exception( __( 'There was an error trying to save the category to the database.', 'AWPCP' ) );
            }

            return $rows_updated;
        } else {
            return false;
        }
    }

    private function update_category_parent_information_in_ads_table( $category ) {
        $sql = 'UPDATE ' . AWPCP_TABLE_ADS . ' SET ad_category_parent_id = %d WHERE ad_category_id = %d';

        $rows_affected = $this->db->query( $this->db->prepare( $sql, $category->parent, $category->id ) );

        if ( $rows_affected === false ) {
            $this->throw_database_exception( __( 'There was an error trying to update category parent information in Ads table.', 'AWPCP' ) );
        }

        return $rows_affected;
    }

    private function rollback_category_modifications( $category, $previous_category_data ) {
        $category->name = $previous_category_data->category_name;
        $category->parent = $previous_category_data->category_parent_id;
        $category->order = $previous_category_data->category_order;
    }
}
