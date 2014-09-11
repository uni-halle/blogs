<?php
class w8v_Info extends b8v_Info {
	public function wp_user($field = null) {
		global $current_user;
		wp_get_current_user ();
		if (0 == $current_user->ID && $field != 'ID') {
			return "";
		} else {
			if (null === $field) {
				return $current_user;
			} else {
				return $current_user->$field;
			}
		
		}
		return "";
	}
}