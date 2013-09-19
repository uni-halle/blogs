<?php
class w8v_Table_Comments extends w8v_Table {
	public function name() {
		return $this->wpdb ()->comments;
	}
}