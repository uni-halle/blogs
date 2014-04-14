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
            $query = sprintf($query, 'COUNT(id)');
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
