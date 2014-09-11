<?php
class w8v_Table_UserMeta extends w8v_Table {
	public function name() {
		return $this->wpdb ()->usermeta;
	}
	public function key_fields() {
		return array ('user_id' );
	}
	public function get_authors() {
		return $this->select ( null, "`meta_key` like '%capabilities' AND (`meta_value` like '%administrator%' OR `meta_value` like '%editor%' OR `meta_value` like '%author%' OR `meta_value` like '%contributor%')", null, null, true );
	}
}
