<?php
class w8v_Table_Blogs extends w8v_Table {
	public function name() {
		return $this->wpdb ()->blogs;
	}
	public function __construct() {
		parent::__construct ();
	}
	public function exists($id) {
		$results = $this->get ( $id );
		return ! empty ( $results );
	}
	public function get($id = null) {
		if (null !== $id) {
			$result = $this->get_row_by_clause ( "`blog_id`='{$id}'", 1 );
		} else {
			$result = parent::get ();
		}
		return $result;
	}
	public function get_by_domain($domain, $path = '/') {
		$path = $this->wpdb ()->escape ( $path );
		$domain = $this->wpdb ()->escape ( $domain );
		$sql = "SELECT *  FROM `%s` WHERE `domain` = '%s' and '%s' like concat(`path`,'%s')  ORDER BY CHAR_LENGTH(`path`) DESC, `site_id` ASC ,`blog_id` ASC LIMIT 1";
		$sql = sprintf ( $sql, $this->name (), $domain, $path, '%' );
		$results = $this->wpdb ()->get_results ( $sql, ARRAY_A );
		if ($results) {
			return $results [0];
		}
		return false;
	}
	public function move($blog_id, $site_id) {
		$w8v_Table_Sites = new w8v_Table_Sites ( );
		if (! $this->exists ( $blog_id )) {
			return false;
		}
		if (! $w8v_Table_Sites->exists ( $site_id )) {
			return false;
		}
		$site = $w8v_Table_Sites->get ( $site_id );
		$blog = $this->get ( $blog_id );
		if ($site_id == $blog ['site_id']) {
			return false;
		}
		$data = array ('site_id' => $site_id, 'domain' => $site ['domain'], 'path' => $site ['path'] );
		$where = array ('blog_id' => $blog_id );
		$this->wpdb ()->update ( $this->name (), $data, $where );
		$new_domain = $site ['domain'] . $site ['path'];
		$old_domain = $blog ['domain'] . $blog ['path'];
		$options_list = array ('siteurl', 'home', 'fileupload_url' );
		$wp_options = new w8v_Table_Options ( $blog_id );
		foreach ( $options_list as $option_name ) {
			$option_value = $wp_options->get ( $option_name );
			$new_value = str_replace ( $old_domain, $new_domain, $option_value );
			$new_value = str_replace ( $blog ['domain'], $site ['domain'], $new_value );
			$wp_options->set ( $option_name, $new_value );
		}
		return true;
	}
	public function add($site_id = 1, $name = 'New Blog', $path = '/') {
		if (! defined ( 'WP_INSTALLING' )) {
			define ( 'WP_INSTALLING', TRUE );
		}
		$w8v_Table_Sites = new w8v_Table_Sites ( );
		$site = $w8v_Table_Sites->get ( $site_id );
		$return = wpmu_create_blog ( $site ['domain'], $path, $name, get_current_user_id (), '', $site_id );
		return $return;
	}
	public function count_by_domain() {
		$w8v_Table_Sites = new w8v_Table_Sites ( );
		$sql = "
SELECT
	`%s`.`id` 'site_id',
	`%s`.`domain`,
	CONCAT(`%s`.`domain`,`%s`.`path`) 'url',
	count(`%s`.`site_id`) 'count'
FROM
	`%s`
	LEFT JOIN `%s`
		ON `%s`.`id` = `%s`.`site_id`
GROUP BY
	`site_id`,
	`domain`
ORDER BY
	`%s`.`id`
";
		$sql = sprintf (
		$sql,
		$w8v_Table_Sites->name (),
		$w8v_Table_Sites->name (),
		$w8v_Table_Sites->name (),
		$w8v_Table_Sites->name (),
		$this->name (),
		$w8v_Table_Sites->name (),
		$this->name (),
		$w8v_Table_Sites->name (),
		$this->name (),
		$w8v_Table_Sites->name ()
		);
		$results = $this->wpdb ()->get_results ( $sql, ARRAY_A );
		return $results;
	}
}