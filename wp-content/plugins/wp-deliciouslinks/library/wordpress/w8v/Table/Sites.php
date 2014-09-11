<?php
class w8v_Table_Sites extends w8v_Table {
	public function name() {
		return $this->wpdb ()->site;
	}
	public function __construct() {
		parent::__construct ();
	}
	public function exists_by_domain($domain, $path = '/') {
		$results = $this->get_row_by_clause ( "`domain`='{$domain}' AND `path`='{$path}'" );
		return ! empty ( $results );
	}
	public function exists($id) {
		$results = $this->get ( $id );
		return ! empty ( $results );
	}
	public function get($id) {
		$result = $this->get_row_by_clause ( "`id`='{$id}'" );
		return $result;
	}
	public function add($domain, $path = '/', $create_blog = true) {
		$return = false;
		if (! $this->exists_by_domain ( $domain, $path )) {
			$result = $this->wpdb ()->insert ( $this->name (), array ('domain' => $domain, 'path' => $path ) );
			$site_id = $this->wpdb ()->insert_id;
			if ($site_id) {
				if ($create_blog) {
					$w8v_Table_Blogs = new w8v_Table_Blogs ( );
					$blog_id = $w8v_Table_Blogs->add ( $site_id );
				}
				$options = array ('admin_email', 'admin_user_id', 'allowed_themes', 'allowedthemes', 'banned_email_domains', 'first_post', 'limited_email_domains', 'site_admins', 'welcome_email' );
				$prime_site_meta = new w8v_Table_SiteMeta ( 1 );
				$new_site_meta = new w8v_Table_SiteMeta ( $site_id );
				foreach ( $options as $option ) {
					$new_site_meta->set ( $option, $prime_site_meta->get ( $option ) );
				}
				$new_site_meta->set ( 'site_name', $domain );
				$new_site_meta->set ( 'dashboard_blog', $blog_id );
			}
			$return = true;
		}
		return $return;
	}
	public function remove($id) {
		$sql = sprintf ( "DELETE FROM `%s` WHERE `id`=%d", $this->name (), $id );
		$this->wpdb ()->query ( $sql );
		$sql = sprintf ( "DELETE FROM `%smeta` WHERE `site_id`=%d", $this->name (), $id );
		$this->wpdb ()->query ( $sql );
		return true;
	}
}