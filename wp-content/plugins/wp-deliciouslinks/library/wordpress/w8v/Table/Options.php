<?php
class w8v_Table_Options extends w8v_Table {
	public function post($key = null) {
		$key = $this->key ( $key );
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			$post = $_POST;
			return $this->set ( $post, $key );
		}
		return $this->get ( $key );
	}
	public function get($key = null) {
		$key = $this->key ( $key );
		$return = '';
		if (null === $this->blog_id ()) {
			$return = get_option ( $key );
		} else {
			if ($this->blog_id () == 0) {
			
			} else {
				$return = get_blog_option ( $this->blog_id (), $key );
			}
		}
		if (! is_array ( $return )) {
			$return = array ();
		}
		array_walk_recursive ( $return, array ($this, 'prepare' ) );
		$defaults = $this->defaults ();
		$return = $return + $defaults;
		return $return;
	}
	public function prepare(&$value) {
		$value = stripslashes($value);
	}
	public function defaults() {
		return array ();
	}
	protected function prepare_value($value) {
		return $value;
	}
	public function set($value, $key = null) {
		$key = $this->key ( $key );
		$value = $this->prepare_value ( $value );
		if (null === $this->blog_id ()) {
			update_option ( $key, $value );
		} else {
			if ($this->blog_id () == 0) {
				update_site_option ( $key, $value );
			} else {
				update_blog_option ( $this->blog_id (), $key, $value );
			}
		}
		return $this->get ();
	}
	public function key($key = null) {
		if (null === $key) {
			$key = $this->key;
		}
		if (is_array ( $key )) {
			$key = implode ( '_', $key );
		}
		$key = strtolower ( $key );
		return $key;
	}
	public function set_key($key) {
		$this->key = $key;
	}
	public function delete($key = null) {
		$key = $this->key ( $key );
		if (null === $this->blog_id ()) {
			delete_option ( $key );
		} else {
			if ($this->blog_id () == 0) {
				delete_site_option ( $key, $value );
			} else {
				delete_blog_option ( $this->blog_id (), $key, $value );
			}
		}
		delete_option ( $key );
	}
	private $key = null;
	public function __construct($key = null, $blog_id = null) {
		$this->set_key ( $key );
		parent::__construct ( $blog_id, 'options' );
	}
}