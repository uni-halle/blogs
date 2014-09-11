<?php
/*
	good to use this instead of the $wpdb->query(): require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($createClientsTable);
*/
function install_ocmx()
	{
		global $wpdb;
		
		$comment_table = $wpdb->prefix . "ocmx_comment_meta";	
		if(!check_table_existance($main_table)) :
			
			$comment_meta_table = "CREATE TABLE `".$comment_table."` (			
			`commentId` MEDIUMINT(8) unsigned NOT NULL PRIMARY KEY,
			`twitter` VARCHAR(255),
			`block_user` TINYINT,
			`email_subscribe` TINYINT);";
			
			mysql_query($comment_meta_table);
			$comments_sql = "SELECT * FROM $wpdb->comments";
			$comments_query = $wpdb->get_results($comments_sql);
			
			foreach($comments_query as $comment) :
				if($comment->comment_subscribe == "Y") :
					$comment_subs = "1";
				else :
					$comment_subs = "0";
				endif;
				$meta_update = $wpdb->query
					($wpdb->prepare
						("INSERT INTO $comment_table
							(commentId, twitter, email_subscribe)
						VALUES
							(%d, %s, %s);", 
						$comment->comment_ID, "", $comment_subs)
					);
			endforeach;
		endif;
	}
	
function check_table_existance($new_table) {
	global $wpdb;
	
	foreach ($wpdb->get_col("SHOW TABLES",0) as $table ) {
		if ($table == $new_table) {
			return true;
		}
	}
	return false;
}
?>