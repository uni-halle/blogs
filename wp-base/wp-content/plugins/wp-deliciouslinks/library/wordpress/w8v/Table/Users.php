<?php
class w8v_Table_Users extends w8v_Table {
	public function name() {
		return $this->wpdb ()->users;
	}
	public function meta_name() {
	}
	private $_key_fields = null;
	public function key_fields() {
		if (null === $this->_key_fields) {
			$keys = $this->db ()->show_indexes ( $this->name () );
			$this->_key_fields = array ();
			foreach ( $keys as $key ) {
				if ($key ['Key_name'] == 'PRIMARY') {
					$this->_key_fields [] = $key ['Column_name'];
				}
			}
		}
		return $this->_key_fields;
	}
	public function get_ids() {
		return $this->select ( null, "`deleted`= 0 AND `user_status` = 0;" );
	}
	protected $meta = null;
	public function __construct($blog_id = null, $name = null) {
		parent::__construct ();
		$this->meta = new w8v_Table_UserMeta ( $blog_id, $name );
	}
}