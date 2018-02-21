<?php
class installerDbUpdaterGmp {
	static public function runUpdate() {
		self::update_105();
		self::update_109();
		self::update_117();
	}
	public static function update_105() {
		if(!dbGmp::exist('@__modules', 'code', 'csv')) {
			dbGmp::query("INSERT INTO `@__modules` (id, code, active, type_id, params, has_tab, label, description)
				VALUES (NULL, 'csv', 1, 1, '', 0, 'csv', 'csv')");
		}
	}
	public static function update_109() {
		if(!dbGmp::exist('@__modules', 'code', 'gmap_widget')) {
			dbGmp::query("INSERT INTO `@__modules` (id, code, active, type_id, params, has_tab, label, description)
				VALUES (NULL, 'gmap_widget', 1, 1, '', 0, 'gmap_widget', 'gmap_widget')");
		}
	}
	public static function update_117() {
		dbGmp::query("UPDATE @__options SET value_type = 'array' WHERE code = 'infowindow_size' LIMIT 1");
	}
}