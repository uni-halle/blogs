<?php
class w8v_Table_Posts extends w8v_Table {
	public static function privatePages($title = null) {
		global $wpdb;
		$search_title = "";
		if (! is_null ( $title )) {
			$search_title = " post_title = '$title' AND ";
		}
		$posts = get_results ( "SELECT ID,post_title FROM $wpdb->posts WHERE $search_title post_type='page' AND post_status = 'private' ORDER BY post_title" );
		return $posts;
	}
	public static function get_by_title($post_title, $type = 'post') {
		global $wpdb;
		$post = $wpdb->get_var ( $wpdb->prepare ( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='%s'", $post_title, $type ) );
		if ($post) {
			return get_post ( $post );
		}
		return NULL;
	}
	public static function get_post_by_title($post_title, $output = OBJECT) {
		throw new exception ( 'get_post_by_title depreciated use get_by_title' );
	}
	public function get_published_page_ids() {
		$sql = "SELECT `ID` FROM `%s` WHERE `post_type`='page' AND `post_status` = 'publish';";
		$sql = sprintf ( $sql, $this->name () );
		$results = $this->wpdb ()->get_results ( $sql, ARRAY_A );
		return $results;
	
	}
	public function get_published($fields = null) {
		$fields = $this->explode_fields ( $fields );
		$sql = "SELECT %s FROM `%s` WHERE `post_status` = 'publish';";
		$sql = sprintf ( $sql, $fields, $this->name () );
		$results = $this->wpdb ()->get_results ( $sql, ARRAY_A );
		return $results;
	}
	public function get_published_post_ids() {
		$sql = "SELECT `ID` FROM `%s` WHERE `post_type`='post' AND `post_status` = 'publish';";
		$sql = sprintf ( $sql, $this->name () );
		$results = $this->wpdb ()->get_results ( $sql, ARRAY_A );
		return $results;
	}
	public function get_published_posts($fields) {
		$fields = $this->explode_fields ( $fields );
		$sql = "SELECT %s FROM `%s` WHERE `post_type`='post' AND `post_status` = 'publish';";
		$sql = sprintf ( $sql, $fields, $this->name () );
		$results = $this->wpdb ()->get_results ( $sql, ARRAY_A );
		return $results;
	}
	public function get_published_post_after_date($date, $fields = null) {
		$fields = $this->explode_fields ( $fields );
		$sql = "SELECT %s FROM `%s` WHERE `post_type`='post' AND `post_status` = 'publish' AND `post_modified` >= '%s';";
		$sql = sprintf ( $sql, $fields, $this->name (), $date );
		$results = $this->wpdb ()->get_results ( $sql, ARRAY_A );
		return $results;
	}
	public static function get_page_by_title($post_title) {
		throw new exception ( 'dreciated' );
		return get_page_by_title ( $post_title );
	}
	public function __construct($blog_id = null) {
		parent::__construct ( $blog_id, 'posts' );
	}
}
