<?php

if (file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
} else {
	require_once(AWPCP_DIR . '/includes/helpers/wp-list-table.php');
}


class AWPCP_List_Table extends WP_List_Table {
	var $_screen;
	var $_columns;
    var $_sortable;

	function __construct( $screen, $columns = array(), $sortable = array()) {
		if ( is_string( $screen ) )
			$screen = convert_to_screen( $screen );

		$this->_screen = $screen;
        $this->_sortable = $sortable;

		if ( !empty( $columns ) ) {
			$this->_columns = $columns;
			add_filter( 'manage_' . $screen->id . '_columns', array( &$this, 'get_columns' ), 0 );
		}
	}

	function get_column_info() {
		$columns = get_column_headers( $this->_screen );
		$hidden = get_hidden_columns( $this->_screen );
		$sortable = $this->_sortable;

		return array( $columns, $hidden, $sortable );
	}

	function get_columns() {
		return $this->_columns;
	}
}
