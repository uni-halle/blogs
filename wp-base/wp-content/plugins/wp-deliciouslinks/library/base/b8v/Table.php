<?php
class b8v_Table extends b8v_Base {
	protected static $_instance = null;
	public function instance() {
		if (null === self::$_instance) {
			self::$_instance = new self ( );
		}
		return self::$_instance;
	}
	public function execute($sql) {
		throw new exception ( 'Not implamented yet.' );
	}
	public function like(&$like) {
		$return = "";
		if (null !== $like) {
			$return = " LIKE '%s'";
			$return = sprintf ( $return, $like );
		}
		$like = $return;
	}
	public function where(&$where = null) {
		$return = "";
		if (null !== $where) {
			$return = " WHERE %s ";
			$return = sprintf ( $return, $where );
		}
		$where = $return;
	}
	public function limit(&$limit = null) {
		$return = "";
		if (null !== $limit) {
			$this->obj_name ( $limit );
			$return = " LIMIT %s ";
			$return = sprintf ( $return, $limit );
		}
		$limit = $return;
	}
	public function from(&$from = null) {
		$return = "";
		if (null === $from) {
			$from = $this->name ();
		}
		$this->obj_name ( $from );
		$return = " FROM %s ";
		$return = sprintf ( $return, $from );
		$from = $return;
	}
	public function obj_name(&$obj) {
		$return = '`%s`';
		$return = sprintf ( $return, $obj );
		$obj = $return;
	}
	public function field(&$field = '*') {
		if ($field != '*') {
			$pattern = '|\<.*\>|Ui';
			$field=preg_replace($pattern,'',$field);
			$field=urlencode($field);
			$pattern = '|%[0-9a-fA-F][0-9a-fA-F]|Ui';
			$safe=array('[',']');
			preg_match_all($pattern,$field, $matches, PREG_SET_ORDER );
			foreach($matches as $match)
			{
				$dmatch=urldecode($match[0]);
				if(!in_array($dmatch,$safe))
				{
					$field = str_replace($match[0],'',$field);
				}
			}
			$field=urldecode($field);
			$field=substr($field,0,200);
			$this->obj_name ( $field );
		}
	}
	public function show_tables($like = null) {
		$this->like ( $like );
		$sql = "SHOW TABLES%s;";
		$sql = sprintf ( $sql, $like );
		$return = $this->execute ( $sql );
		return $return;
	}
	public function show_databases($like = null) {
		$this->like ( $like );
		$sql = "SHOW DATABASES%s;";
		$sql = sprintf ( $sql, $like );
		$return = $this->execute ( $sql );
		return $return;
	}
	public function show_columns($from = null, $like = null) {
		if (null === $from) {
			$from = $this->name ();
		}
		$this->from ( $from );
		$this->like ( $like );
		$sql = "SHOW COLUMNS%s%s;";
		$sql = sprintf ( $sql, $from, $like );
		$return = $this->execute ( $sql );
		return $return;
	}
	public function show_indexes($from) {
		$this->from ( $from );
		$sql = "SHOW INDEXES%s;";
		$sql = sprintf ( $sql, $from );
		$return = $this->execute ( $sql );
		return $return;
	}
	public function show_create_database($database) {
		$this->obj_name ( $database );
		$sql = "SHOW CREATE DATABASE %s;";
		$sql = sprintf ( $sql, $database );
		$return = $this->execute ( $sql );
		return $return;
	}
	public function count_records($field, $where = null, $from = null) {
		$this->from ( $from );
		$this->where ( $where );
		$this->field ( $field );
		$sql = "SELECT COUNT(%s) 'count'%s%s";
		$sql = sprintf ( $sql, $field, $from, $where );
		$return = $this->execute ( $sql );
		return $return [0] ['count'];
	}
	public function show_create_table($table) {
		$this->obj_name ( $table );
		$sql = "SHOW CREATE TABLE %s;";
		$sql = sprintf ( $sql, $table );
		$return = $this->execute ( $sql );
		return $return;
	}
	public function show_create_view($view) {
		$this->obj_name ( $view );
		$sql = "SHOW CREATE VIEW %s;";
		$sql = sprintf ( $sql, $view );
		$return = $this->execute ( $sql );
		return $return;
	}
	public function truncate($table) {
		$this->obj_name ( $table );
		$sql = "TRUNCATE TABLE %s";
		$sql = sprintf ( $sql, $table );
		$return = $this->execute ( $sql );
		return $return;
	}
	private $_name = null;
	protected function name() {
		return $this->prefix () . $this->_name;
	}
	protected function create_table($fields, $keys) {
		$sql = "CREATE TABLE IF NOT EXISTS `" . $this->name () . "` (\n";
		$comma = "";
		foreach ( ( array ) $fields as $field_name => $field_def ) {
			$sql .= $comma;
			$sql .= "`" . $field_name . "` " . $field_def ['type'];
			if (array_key_exists ( 'null', $field_def ) && ! $field_def ['null']) {
				$sql .= " NOT NULL ";
			}
			if (array_key_exists ( 'extra', $field_def )) {
				$sql .= " " . $field_def ['extra'];
			}
			$comma = ",\n";
		}
		if (array_key_exists ( 'primary', $keys )) {
			$sql .= $comma . " PRIMARY KEY (`" . $this->keys ['primary'] . "`)";
		}
		$sql .= ")";
		$return = $this->execute ( $sql );
	}
	protected function alter_table($key, $type) {
		$this->field($key);
		$sql = "ALTER TABLE `%s` ADD %s %s";
		$sql = sprintf($sql, $this->name (),$key,$type);
		$return = $this->execute ( $sql );
	}
	protected function set_name($name = null) {
		if (null == $name) {
			$name = 'test';
		}
		$this->_name = $name;
	}
	public function insert($data) {
		$fields = array ();
		$values = array ();
		foreach ( ( array ) $data as $key => $value ) {
			$this->field($key);
			$fields [] = $key;
			$values [] = "'" . addslashes ( $value ) . "'";
		}
		$sql = "INSERT INTO `" . $this->name () . "`\n";
		$fields = implode ( ',', $fields );
		if ($fields != '') {
			$values = implode ( ',', $values );
			$sql .= "(" . $fields . ")\n";
			$sql .= "values (" . $values . ")\n";
			$return = $this->execute ( $sql );
		}
	}
}