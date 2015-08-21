<?php

class AWPCP_FormFieldsTable extends WP_List_Table {

    private $page;
    private $request;

    public function __construct( $page, $request ) {
        parent::__construct( array( 'plural' => 'awpcp-form-fields-table' ) );

        $this->page = $page;
        $this->request = $request;
    }

    public function prepare( $items, $total_items ) {
        $this->items = $items;

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page' => $total_items,
        ) );

        $this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
    }

    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => _x( 'Name', 'form field name', 'AWPCP' ),
            'slug' => _x( 'Slug', 'form field slug', 'AWPCP' ),
        );

        return $columns;
    }

    public function column_cb($item) {
        return '<input type="checkbox" value="' . $item->get_slug() . '" name="selected[]" />';
    }

    public function column_name( $item ) {
        $handle = '<div class="awpcp-sortable-handle"><div class="spinner awpcp-spinner awpcp-form-fields-table-spinner"></div></div>';
        return $handle . $item->get_name() . $this->row_actions( array( '' => '' ) );
    }

    public function column_slug( $item ) {
        return $item->get_slug();
    }

    public function single_row($item) {
        static $row_class = '';

        $row_class = ( $row_class == '' ? ' class="alternate"' : '' );

        // the 'field-' part in the id attribute is important. The jQuery UI Sortable plugin relies on that
        // to build a serialized string with the current order of fields.
        echo '<tr id="field-' . $item->get_slug() . '" data-id="' . $item->get_slug() . '"' . $row_class . '>';
        echo $this->single_row_columns( $item );
        echo '</tr>';
    }
}
