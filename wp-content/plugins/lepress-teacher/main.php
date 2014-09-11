<?php 
/*
Plugin Name: LePress Teacher
Plugin URI: http://trac.htk.tlu.ee/lepress/
Version: 1.06
Author: <a href="mailto:alluxxii@gmail.com">Alvar Hansen</a>, <a href="mailto:raido357@gmail.com">Raido Kuli</a>
Description: A plugin for a <a href="http://trac.htk.tlu.ee/lepress/">Centre for the Educational Technology</a>.
*/


if (!class_exists("LePressTeacher")){
    class LePressTeacher {
		public $version = 1.00;
		public $plugin_download_url = "http://trac.htk.tlu.ee/lepress/";

        function LePressTeacher() {
            //constructor
			return;
        }
		
		function category_deleted_handler($cat_id) {
			//if the post category is course
			if($this->get_is_course($cat_id)) {
				//delete by term id - therefore second parameter  true
				$this->remove_subscription($cat_id, true);
			}
		}

		function get_is_course( $term_id ){
			global $wpdb;
			$table_name = $wpdb->prefix . get_class( $this ) . "_course_categories";
			$term_id = $wpdb->escape( $term_id );
		    $sql = "SELECT is_course FROM $table_name WHERE term_id='$term_id'";
			if ($row = $wpdb->get_row( $sql )) {
				return ( 1 == $row->is_course ) ? true : false;
			} else return false;
		}

		function get_course_name( $term_id ){
			global $wpdb;
			$table_name = $wpdb->prefix . "terms";
			$term_id = $wpdb->escape( $term_id );
		    $sql = "SELECT name FROM $table_name WHERE term_id='$term_id'";
			if ( $row = $wpdb->get_row( $sql ) ){
				return $row->name;
			} else {
				return false;
			}
		}

		function get_course( $term_id ){
			global $wpdb;
			$course = array();
			$table_name = $wpdb->prefix . get_class( $this ) . "_course_categories";
			$term_id = (int)$wpdb->escape( $term_id );
		   $sql = "SELECT id, is_course, organis_name, teacher_first_name, teacher_last_name, teacher_email
						FROM $table_name
						WHERE term_id=$term_id";
			if ($row = $wpdb->get_row( $sql )) {
				$course['id'] = $row->id;
				$course['is_course'] = $row->is_course;
				$course['organis_name'] = $row->organis_name;
				$course['teacher_first_name'] = $row->teacher_first_name;
				$course['teacher_last_name'] = $row->teacher_last_name;
				$course['teacher_email'] = $row->teacher_email;
				return $course;
			} else return false;
		}


		function get_course_organisation_name( $term_id ) {
			global $wpdb;
			$table_name = $wpdb->prefix . get_class( $this ) . "_course_categories";
			$term_id = $wpdb->escape( $term_id );
		    $sql = "SELECT organis_name FROM $table_name WHERE term_id='$term_id'";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return $row->organis_name;
			} else return false;
		}

		function create_category( $catID ) {
			global $wpdb;
			$is_course = ( empty( $_POST['category_is_course'] ) ) ? '0' : '1';
			if( $is_course == '1' ){
				$orginisation_name = $wpdb->escape( $_POST['category_orginisation_name'] );
				$table_name = $wpdb->prefix . get_class($this) . "_course_categories";
				$insert = "INSERT INTO " . $table_name .
				" (term_id, is_course, organis_name) " .
				"VALUES ('" . $catID . "','" . $is_course . "','" . $orginisation_name . "')";

				$results = $wpdb->query( $insert );
			}
        }

		function update_category( $catID, $is_course, $organisation_name, $first_name, $last_name, $email, $description = null, $parent = null, $manage_course_form = false) {
			global $wpdb;
			$catID = $wpdb->escape( $catID );
			$is_course = $wpdb->escape( $is_course );
			$organisation_name = $wpdb->escape( $organisation_name );
			$first_name = $wpdb->escape( $first_name );
			$last_name = $wpdb->escape( $last_name );
			$email = $wpdb->escape( $email );
			$description = $wpdb->escape($description);
			$parent = $wpdb->escape($parent == -1?0:$parent);
			$table_name = $wpdb->prefix . get_class( $this ) . "_course_categories";

			if ($wpdb->get_var("SELECT term_id FROM $table_name WHERE term_id='$catID'")) {
				$subscribers = $this->get_subscribed_users_full_data($catID);
				
				if($this->get_is_course($catID) != $is_course && count($subscribers) <= 0) {
						$wpdb->query("UPDATE $table_name SET is_course='$is_course' WHERE term_id='$catID'");
				} 

					if(get_term_by('term_id', $catID, 'category')) {
						if($description) {
							wp_update_term($catID, 'category', array('description' => $description));
						}
						
						if($parent) {
							wp_update_term($catID, 'category', array('parent' => $parent));
						}
					if($organisation_name > -1) {
						$wpdb->query("UPDATE $table_name
						SET organis_name='$organisation_name',
							teacher_first_name='$first_name', teacher_last_name='$last_name', teacher_email='$email'
						WHERE term_id='$catID'");
					}
				}
				
			} else {
				if($organisation_name == -1) {
					$organisation_name = "";
				}
				$wpdb->query("INSERT INTO $table_name (term_id, is_course, organis_name, teacher_first_name, teacher_last_name, teacher_email) VALUES ('$catID', '$is_course', '$organisation_name', '$first_name', '$last_name', '$email')");
			}
		}

		function get_courses_names() {
			global $wpdb;
			$names = array();
			$terms_table_name = $wpdb->prefix . "terms";
			$course_table_name = $wpdb->prefix . get_class( $this ) . "_course_categories";

			$sql = "SELECT $terms_table_name.term_id as termID, $terms_table_name.name AS name
					FROM $terms_table_name, $course_table_name
					WHERE $course_table_name.is_course = 1 AND $course_table_name.term_id = $terms_table_name.term_id
					ORDER BY name ASC";
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				$id = $row->termID;
				$name = $row->name;
				$names[$id] = $name;
			}
			return $names;
		}

		function get_subscribed_users_full_data( $termId ) {
			if(!is_numeric($termId)) {
				return false;	
			}
			global $wpdb;
			$users = array();
			$termId = $wpdb->escape( $termId );
			//if( $termId < 0 ) return $users;
			$users_table_name = $wpdb->prefix . get_class( $this ) . "_users";
			$subs_table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			
			if($termId > 0) {
				$term_id_sql = $subs_table_name.".term_id = ".$termId." AND ";
			}

			$sql = "SELECT $users_table_name.id AS userID,  $users_table_name.first_name AS fName,
				$users_table_name.last_name AS lName, $users_table_name.wp_url AS wpURL,
				$users_table_name.email AS email, $subs_table_name.id as subsID, $subs_table_name.accept_key as accept_key
				FROM $users_table_name, $subs_table_name
				WHERE 	".$term_id_sql." $subs_table_name.user_id = $users_table_name.id AND
				$subs_table_name.accepted = 1
				ORDER BY $users_table_name.last_name ASC";
				
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				$id = $row->userID;
				$fName = $row->fName;
				$lName = $row->lName;
				$wpURL = $row->wpURL;
				$email = $row->email;
				$users[$id]["id"] = $id;
				$users[$id]["fName"] = $fName;
				$users[$id]["lName"] = $lName;
				$users[$id]["wpURL"] = $wpURL;
				$users[$id]["email"] = $email;
				$users[$id]["subsId"] = $row->subsID;
				$users[$id]["accept_key"] = $row->accept_key;
			}
			return $users;
		}

		function get_course_users_ids( $term_id ){
			global $wpdb;
			$users = array();
			$table_name = $wpdb->prefix . get_class($this) . "_subscribed_users";
			$term_id = $wpdb->escape( $term_id );
			$sql = "SELECT user_id FROM $table_name WHERE term_id=$term_id AND accepted=1";
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				$id = $row->user_id;
				$users[$id]['id'] = $id;
			}
			return $users;
		}

		function get_user( $user_id ){
			global $wpdb;
			$table_name = $wpdb->prefix . get_class($this) . "_users";
			$user_id = $wpdb->escape( $user_id );
			$sql = "SELECT id, first_name, last_name, wp_url, email FROM $table_name WHERE id=$user_id";
			if ( $row = $wpdb->get_row( $sql ) ) {
				$user = array();
				$user['id'] = $row->id;
				$user['first_name'] = $row->first_name;
				$user['last_name'] = $row->last_name;
				$user['wp_url'] = $row->wp_url;
				$user['email'] = $row->email;
				return $user;
			} else return false;
		}

		function get_user_id( $siteurl, $email = null ) {
			global $wpdb;
			$table_name = $wpdb->prefix . get_class($this) . "_users";
			$siteurl = $wpdb->escape( $siteurl );
			$sql = "";
			if( $email != null ){
				$email = $wpdb->escape( $email );
				$sql = "SELECT id FROM $table_name WHERE wp_url='$siteurl' AND email='$email'";
			}else{
				$sql = "SELECT id FROM $table_name WHERE wp_url='$siteurl'";
			}
			if ( $row = $wpdb->get_row( $sql ) ) {
				return $row->id;
			} else return false;
		}

		function get_user_email( $id ) {
			global $wpdb;
			$table_name = $wpdb->prefix . get_class($this) . "_users";
			$id = $wpdb->escape( $id );
			$sql = "SELECT email FROM $table_name WHERE id='$id'";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return $row->email;
			} else return false;
		}

		function add_user( $fname, $lname, $siteurl, $email ) {
			global $wpdb;
			$fname = $wpdb->escape( $fname );
			$lname = $wpdb->escape( $lname );
			$siteurl = $wpdb->escape( $siteurl );
			$email = $wpdb->escape( $email );
			$table_name = $wpdb->prefix . get_class($this) . "_users";
			$insert = "INSERT INTO " . $table_name .
			" (first_name, last_name, wp_url, email) " .
            "VALUES ('" . $fname . "','" . $lname . "','" . $siteurl . "','" . $email . "')";

			$wpdb->query( $insert );
		}
		
		function update_user($fname, $lname, $siteurl, $email, $student_key, $term_id) {
			global $wpdb;
			if($this->get_user_id($siteurl)) {
				if($this->check_communication_key_validity($term_id, $siteurl, $student_key)) {
					$fname = $wpdb->escape( $fname );
					$lname = $wpdb->escape( $lname );
					$siteurl = $wpdb->escape( $siteurl );
					$email = $wpdb->escape( $email );
					$table_name = $wpdb->prefix . get_class($this) . "_users";
					$update = "UPDATE " . $table_name . " SET first_name = '".$fname."', last_name = '".$lname."', email = '".$email."' WHERE wp_url = '".$siteurl."' ";
					$wpdb->query( $update );
				}
			}
		}

		function get_subscription( $subscription_id ) {
			global $wpdb;
			$subscription_id = $wpdb->escape( $subscription_id );
			if ( $subscription_id < 0 ) return false;
			$table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			$subscription = array();
			$sql = "SELECT id, term_id, user_id, accepted, accept_key
					FROM $table_name
					WHERE id = $subscription_id";
			if ( $row = $wpdb->get_row( $sql ) ) {
				$subscription['id'] = $row->id;
				$subscription['term_id'] = $row->term_id;
				$subscription['user_id'] = $row->user_id;
				$subscription['accepted'] = $row->accepted;
				$subscription['accept_key'] = $row->accept_key;
				return $subscription;
			} else return false;
		}

		//done
		function get_unsubscribed_users() {
			global $wpdb;
			$users = array();
			$terms_table_name = $wpdb->prefix . "terms";
			$users_table_name = $wpdb->prefix . get_class( $this ) . "_users";
			$subs_table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";

			$sql = "SELECT $subs_table_name.id as id, $users_table_name.id AS userID,
				$users_table_name.first_name AS fName, $users_table_name.email AS user_email, $users_table_name.last_name AS lName,
				$terms_table_name.name AS courseName
				FROM $subs_table_name, $users_table_name, $terms_table_name
				WHERE $subs_table_name.term_id = $terms_table_name.term_id AND
				$subs_table_name.user_id = $users_table_name.id AND
				$subs_table_name.accepted = 0
				ORDER BY userID ASC";
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				$id = $row->id;
				$users[$id]["id"] = $id;
				$users[$id]["userId"] = $row->userID;
				$users[$id]["fName"] = $row->fName;
				$users[$id]["lName"] = $row->lName;
				$users[$id]["courseName"] = $row->courseName;
				$users[$id]["user_email"] = $row->user_email;
			}
			return $users;
		}
		//done
		function add_subscription( $accepted, $userID, $termID, $key ) {
			global $wpdb;

			$table_name;
			$termID = $wpdb->escape( $termID );
			$key = $wpdb->escape( $key );
			$table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			
			$user_terms = $wpdb->get_results("SELECT term_id FROM $table_name WHERE user_id='$userID'", OBJECT);
			//If user has already subscribed to given course, return false;
			foreach($user_terms as $user_term) {
				if($user_term->term_id === $termID) {
					return false;
					break;	
				}
			}
			
			if( $accepted ){
				$insert = "INSERT INTO " . $table_name .
				" (term_id, user_id, accept_key, accepted) " .
				"VALUES (" . $termID . ", " . $userID . ",'" . $key . "', 1" . ")";
			} else {
				$insert = "INSERT INTO " . $table_name .
				" (term_id, user_id, accept_key, accepted) " .
				"VALUES (" . $termID . ", " . $userID . ",'" . $key . "', 0" . ")";
			}


			$wpdb->query( $insert );
		}
		//done
		function accept_subscription( $subscription_id ) {
			global $wpdb;
			$subscription_id = $wpdb->escape( $subscription_id );
			$table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			$subscription_id = $wpdb->escape( $subscription_id );

			$update = "UPDATE $table_name SET accepted='1' WHERE id=$subscription_id";
			$wpdb->query( $update );
		}
		//done
		
		function get_user_id_by_subscription_id($subscription_id, $ext_term_id = false) {
			global $wpdb;
			$subscription_id = $wpdb->escape( $subscription_id );
			$table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			if(!$ext_term_id) {
				$sql = "SELECT user_id FROM $table_name WHERE id = $subscription_id LIMIT 1";
			} else {
				$sql = "SELECT user_id FROM $table_name WHERE term_id = $subscription_id LIMIT 1";
			}
			$row = $wpdb->get_row( $sql );
			if($row) {
				return $row->user_id;
			}
			return false;
		}
		//done
		
		function does_user_have_subscriptions($user_id) {
			global $wpdb;
			$user_id = $wpdb->escape( $user_id );
			$table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			$sql = "SELECT id FROM $table_name WHERE user_id = $user_id LIMIT 1";
			$row = $wpdb->get_row( $sql );
			if($row) {
				return $row->id;
			}
			return false;
		}
		//done
		
		function remove_subscription( $subscription_id, $ext_term_id = false ) {
			global $wpdb;

			$subscription_id = $wpdb->escape( $subscription_id);
			$user_id = $this->get_user_id_by_subscription_id($subscription_id, $ext_term_id);
			$table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			
			if(!$ext_term_id) {
				$sql = "DELETE FROM $table_name WHERE id = $subscription_id";
			} else {
				$sql = 	"DELETE FROM $table_name WHERE term_id = $subscription_id";
			}
			
			$wpdb->query( $sql );
			
			//DELETE user data
			if($user_id) {
				//if user has no more subscriptions left, delete user data
				if(!$this->does_user_have_subscriptions($user_id)) {
					$table_name = $wpdb->prefix . get_class( $this ) . "_users";
					$sql = "DELETE FROM $table_name WHERE id = $user_id";
					$wpdb->query( $sql );
				
					$table_name = $wpdb->prefix . get_class( $this ) . "_filed_assignments";
					$sql = "DELETE FROM $table_name WHERE user_id = $user_id";
					$wpdb->query( $sql );
				}
			}
			
			//Delete out assignments, which are not on any course
			$assignments = $this->get_all_assignments();
				
				foreach($assignments as $post_id) {
					$cat_data = get_the_category($post_id);
					$cat_id = $cat_data[0]->term_id;
					//if the post category is not a course, delete if from cache table
					if(!$this->get_is_course($cat_id)) {
						$this->remove_assignment($post_id);	
					}
				}
				
			//Clean out leftover course categories
			$term_ids = $this->get_all_course_categories_ids();
			foreach($term_ids as $term_id) {
					
					if(!get_term_by('term_id', $term_id, 'category')) {
						$table_name = $wpdb->prefix . get_class( $this ) . "_course_categories";
						$sql = "DELETE FROM $table_name WHERE term_id = $term_id";
						$wpdb->query( $sql );
					}
			}
		}
		
		function get_all_course_categories_ids() {
			global $wpdb;
			
			$term_ids = array();
			$table_name = $wpdb->prefix . get_class( $this ) . "_course_categories";
			$sql = "SELECT ".$table_name.".term_id as term_out_id FROM ".$table_name;
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
					array_push($term_ids, $row->term_out_id);
			}
			
			return $term_ids;
		}
		
		function get_all_assignments() {
			global $wpdb;
			
			$assignments_ids = array();
			
			$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";
			$sql = "SELECT post_id FROM ".$table_name;
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
					array_push($assignments_ids, $row->post_id);
			}
			
			return $assignments_ids;
		}

		function check_key_validity( $termID, $invite_key ) {  //check invite key
			global $wpdb;

			$group_table_name = $wpdb->prefix . get_class( $this ) . "_group_invites";
			$personal_table_name = $wpdb->prefix . get_class( $this ) . "_personal_invites";
			$invite_key = $wpdb->escape( $invite_key );
			$termID = $wpdb->escape( $termID );

			$sql = "SELECT id FROM $group_table_name WHERE term_id=$termID AND invite_key='$invite_key'";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return true;
			} else {
				$sql = "SELECT id FROM $personal_table_name WHERE term_id=$termID AND invite_key='$invite_key'";
				if ( $row = $wpdb->get_row( $sql ) ) {
					return true;
				} else {
					return false;
				}
			}
		}

		function check_communication_key_validity( $termID, $wp_url, $key ) {  //check communication key
			global $wpdb;

			$users_table_name = $wpdb->prefix . get_class( $this ) . "_users";
			$subs_table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			$wp_url = $wpdb->escape( $wp_url );
			$invite_key = $wpdb->escape( $key );
			$termID = $wpdb->escape( $termID );

			$sql = "SELECT subs.id as ID, users.wp_url, subs.term_id, subs.accept_key
					FROM $subs_table_name AS subs, $users_table_name AS users
					WHERE subs.user_id = users.id AND users.wp_url = '$wp_url' AND subs.term_id=$termID AND subs.accept_key='$invite_key'";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return $row->ID;
			} else {
				return false;
			}
		}

		function remove_personal_invite( $term_id, $invite_key ) {
			global $wpdb;

			//remove invite
			$term_id = $wpdb->escape( $term_id );
			$invite_key = $wpdb->escape( $invite_key );
			$invites_table_name = $wpdb->prefix . get_class( $this ) . "_personal_invites";
			$sql = "DELETE FROM " . $invites_table_name .
			" WHERE term_id = " . $term_id . " AND invite_key='" . $invite_key . "')";


			$wpdb->query( $sql );
		}
		
		function getStudentNameByCommentId($comment_id) {
			global $wpdb;
			$comment_id = $wpdb->escape( $comment_id );
			$users_table = $wpdb->prefix . get_class( $this ) . "_users";
			$filed_assignments_table = $wpdb->prefix . get_class( $this ) . "_filed_assignments";
			
			$sql = "SELECT user_id FROM ".$filed_assignments_table." WHERE comment_id = '".$comment_id."'";
			$row = $wpdb->get_row( $sql );
			if($row) {
				$sql = "SELECT first_name, last_name FROM ".$users_table." WHERE id='".$row->user_id."'";
				$row = $wpdb->get_row($sql);
				return $row;
			}
			return false;
		}
		
		function get_post_from_student_blog($post_url) {
			$post_url = add_query_arg(array('action' => 'LePressStudent_getPost'), $post_url);
			$ch = curl_init($post_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			$data = curl_exec($ch);
			curl_close($ch);
			
			if (preg_match('/<LePressResponse>(.*?)<\/LePressResponse>./sm',$data, $matches) ) {
				$xml_str = $matches[1];
				try {
					$xml = @new SimpleXMLElement($xml_str, LIBXML_NOERROR);		
				} catch (Exception $e) {
					return false;
				}
				if($xml instanceof SimpleXMLElement) {
						return $xml;
				}
			}
			return false;
		}
		
		//not done
		function notify_subscription_user_web ( $subscription_id, $accepted ) {
			global $wpdb;

			$response = "";
			$subscription_id = $wpdb->escape( $subscription_id );
			$users_table_name = $wpdb->prefix . get_class( $this ) . "_users";
			$subs_table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";

			$sql = "SELECT $users_table_name.wp_url AS wpURL, $subs_table_name.term_id AS termId,
				$subs_table_name.accept_key AS acceptKey
				FROM $subs_table_name, $users_table_name
				WHERE $subs_table_name.user_id = $users_table_name.id AND $subs_table_name.id = $subscription_id";
			$row = $wpdb->get_row( $sql );

			$wpURL = $row->wpURL;
			$termId = $row->termId;
			$acceptKey = $row->acceptKey;

			$ch = curl_init( $wpURL . "/?action=LePressStudentVersionCheck" );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			$data = curl_exec($ch);
			curl_close($ch);
			if ( preg_match('/<LePressResponse>(.*?)<\/LePressResponse>./sm',$data, $matches) ) {
				$version = $matches[1];
				//need to check version?

				$notifyUrl = $wpURL;
				if ( $accepted ) {
					$notifyUrl .= "/?action=LePressStudent_subscription_accept&id=" . $termId . "&key=" . $acceptKey;
				} else {
					$notifyUrl .= "/?action=LePressStudent_subscription_decline&id=" . $termId . "&key=" . $acceptKey;
				}
				$ch = curl_init( $notifyUrl );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				$data = curl_exec($ch);
				curl_close($ch);
				preg_match('/<LePressResponse>(.*?)<\/LePressResponse>./sm',$data, $matches);
				$response = $matches[1];
				preg_match('/<Message>(.*?)<\/Message>/',$response, $matches);
				$message = $matches[1];
				preg_match('/<Code>(.*?)<\/Code>/',$response, $matches);
				$code = $matches[1];
				switch ( $message ) {
					case 'Command recieved':
						return $code;
						break;
					default:
						return false;
						break;
				}
				return true;
			} else {
				//$response = 'No LePressStudent plugin found at ' . $wpURL;
				return false;
			}
			return false;
		}

		function create_key( $length ) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$key = "";
			for ($i=0; $i<$length; $i++) {
				$key .= substr( $chars, rand( 0, strlen( $chars ) - 1), 1 );
			}
			return $key;
		}

		function invite_key_exists( $key ) {
			global $wpdb;

			$group_table_name = $wpdb->prefix . get_class( $this ) . "_group_invites";
			$personal_table_name = $wpdb->prefix . get_class( $this ) . "_personal_invites";
			$key = $wpdb->escape( $key );

			$sql = "SELECT id FROM $group_table_name WHERE invite_key='$key'";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return true;
			} else {
				$sql = "SELECT id FROM $personal_table_name WHERE invite_key='$key'";
				if ( $row = $wpdb->get_row( $sql ) ) {
					return true;
				} else {
					return false;
				}
			}
		}

		function generate_key( $course_id, $group ) {
			global $wpdb;
			$key = $this->create_key(5);
			$table_name = "";

			while ( $this->invite_key_exists( $key ) ){
				$key = $this->create_key(5);
			}

			if ( $group ) {
				$table_name = $wpdb->prefix . get_class( $this ) . "_group_invites";
			} else {
				$table_name = $wpdb->prefix . get_class( $this ) . "_personal_invites";
			}

			$insert = "INSERT INTO " . $table_name .
				" (term_id, invite_key) " .
				"VALUES ( $course_id , '$key' )";

			$wpdb->query( $insert );
			return $key;
		}

		function add_assignement( $postID, $start, $end ) {
			global $wpdb;

			$postID = $wpdb->escape( $postID );
			$start = $wpdb->escape( $start );
			$end = $wpdb->escape( $end );
			$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";
			$insert = "INSERT INTO " . $table_name .
			" (post_id, start, end) " .
			"VALUES (" . $postID . ", '" . $start . "', '" . $end . "')";


			$wpdb->query( $insert );
			//echo "added assignement $insert";
		}
		
		//remove assignment from database
		
		function remove_assignment($post_id) {
			global $wpdb;
						
			$post_id = $wpdb->escape( $post_id );
			$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";
			$delete = "DELETE FROM " . $table_name . " WHERE post_id = '".$post_id."'";
			$wpdb->query( $delete);
		}

		function get_assignments_names( $term_id, $assignment_id = false) {
			$names = array();
			if ( $term_id < 0 ) return $names;
			
			$lepress_posts = get_posts('numberposts=-1&cat='.$term_id);
			
			if(count($lepress_posts) > 0) {
				foreach($lepress_posts as $post) {
					$cat_ids = get_the_category($post->ID);
					foreach($cat_ids as $cat) {
						if($term_id === $cat->cat_ID) {
							$assignment = $this->getAssignment($post->ID, $assignment_id);
							if(is_array($assignment)) {
								$id = $assignment['id'];
								if($id) {
										$names[$id]['id'] = $id;
										$names[$id]['name'] = $post->post_title;
										$names[$id]['post_id'] = $post->ID;
										$names[$id]['start'] = date( 'j.n.Y', strtotime(  $assignment['start'] ) );
										$names[$id]['end'] = date( 'j.n.Y', strtotime(  $assignment['end']) );
								}
							}
						}
					}
				}
			}
			
			return $names;
		}

		function getAssignment ( $post_id, $assignment_id = false) {
			global $wpdb;

			$assignments_table_name = $wpdb->prefix . get_class( $this ) . "_assignments";

			$sql = "SELECT id, start, end FROM $assignments_table_name WHERE post_id = $post_id";
			if($assignment_id) {
				$sql .= ' AND id="'.$wpdb->escape($assignment_id).'"';	
			}
			if ( $row = $wpdb->get_row( $sql ) ) {
				return array('id'=> $row->id, 'start' => $row->start, 'end' => $row->end);
			} else {
				return false;
			}
		}


		function post_is_assignment ( $post_id ) {
			global $wpdb;

			$assignments_table_name = $wpdb->prefix . get_class( $this ) . "_assignments";

			$sql = "SELECT id FROM $assignments_table_name WHERE post_id = $post_id";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return true;
			} else {
				return false;
			}
		}

		function assignment_start ( $post_id ) {
			global $wpdb;
			if ( $post_id < 0 ) return false;
			$assignments_table_name = $wpdb->prefix . get_class( $this ) . "_assignments";

			$sql = "SELECT start
					FROM $assignments_table_name
					WHERE post_id = $post_id";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return date( 'j.n.Y', strtotime( $row->start ) );
			} else return false;
		}

		function assignment_end ( $post_id ) {
			global $wpdb;
			if ( $post_id < 0 ) return false;
			$assignments_table_name = $wpdb->prefix . get_class( $this ) . "_assignments";

			$sql = "SELECT end
					FROM $assignments_table_name
					WHERE post_id = $post_id";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return date( 'j.n.Y', strtotime( $row->end ) );
			} else return false;
		}


		function get_assessment( $post_id, $user_id ){
			global $wpdb;
			$post_id = (int)$post_id;
			$user_id = (int)$user_id;
			$user = $this->get_user( $user_id );
			$user['wp_url'];
			$comments = get_comments('post_id=' . $post_id . '&orderby=comment_date_gmt&order=DESC');
			foreach( $comments as $comment ){
				if( $comment->comment_type == 'trackback' ){
					//get trackebacker wp blog url
					$agent = explode( ';' , $comment->comment_agent );
					$blog_url = '';
					for( $i = 0; $i < count( $agent ); $i++){
						if ( strcasecmp( substr( ltrim( $agent[$i] ), 0, 7 ), 'http://' ) == 0 ){
							//if agent information starts with 'http://' then it is users blog url
							$blog_url = ltrim( $agent[$i] );
						}
					}
					if( $blog_url != '' && $blog_url == $user['wp_url'] ){	//we need to know blog url and it needs to be this users
						$comment_id = $comment->comment_ID;
						$feedback_id = false;
						$feedback_url = false;
						$grade = false;
						$table_name = $wpdb->prefix . get_class( $this ) . "_filed_assignments";
						$sql = "SELECT id, grade, feedback_url
								FROM $table_name
								WHERE comment_id = $comment->comment_ID AND user_id=$user_id";
						if ( $row = $wpdb->get_row( $sql ) ) {
							$feedback_id = $row->id;
							$feedback_url = $row->feedback_url;
							$grade = is_null($row->grade) ? NULL : $row->grade;
							
						}
						
						$assessment = array();
						if($feedback_id) {
							$assessment['id'] = $comment_id;
							$assessment['comment_url'] = $comment->comment_author_url;
							$assessment['first_name'] = $user['first_name'];
							$assessment['last_name'] = $user['last_name'];
							$assessment['wp_url'] = $user['wp_url'];
							$assessment['email'] = $user['email'];
							$assessment['feedback_id'] = $feedback_id;
							$assessment['feedback_url'] = $feedback_url;
							$assessment['grade'] = $grade;
							$assignment_end_date = $this->assignment_end($post_id);
							
							if(date("Y-m-d", strtotime($comment->comment_date)) > date("Y-m-d", strtotime($assignment_end_date))) {
							     $assessment['submission_time'] = '<span style="color:red; font-weight: bold;">'.date("d.m.Y H:i", strtotime($comment->comment_date)).'</span>';
							} else {
							     $assessment['submission_time'] = date("d.m.Y H:i", strtotime($comment->comment_date));
							}
							
						}
						return $assessment;
					}else{
						continue;
					}
				}
			}
			return false;
		}

		function is_comment_assessment( $comment_id ) {
			global $wpdb;
			$id = (int)$comment_id;
			$comment = get_comment($id);

			//check if coment is on any course
			$categories = get_the_category( $comment->comment_post_ID );
			$courses_ids = array();
			foreach( $categories as $category ){
				if( $this->get_is_course( $category->term_id ) ){
					array_push( $courses_ids, $category->term_id );
				}
			}
			if( count( $courses_ids ) == 0 ){	//then this comment's post is not in course
				return false;
			}

			//get trackebacker wp blog url
			$agent = explode( ';' , $comment->comment_agent );
			$blog_url = '';
			for( $i = 0; $i < count( $agent ); $i++){
				if ( strcasecmp( substr( ltrim( $agent[$i] ), 0, 7 ), 'http://' ) == 0 ){
					//if agent information starts with 'http://' then it is users blog url
					$blog_url = ltrim( $agent[$i] );
					break;
				}
			}
			if( $blog_url == '' ){	//we need to know blog url
				return false;
			}
			//echo 'url: ' . $blog_url;

			//check if there is user with that wp url and if he/she is subscribed to a course where this comments post is
			$blog_url = $wpdb->escape( $blog_url );
			$courses_sql_str = "(" . implode( ',', $courses_ids ) . ')';
			$users_table_name = $wpdb->prefix . get_class( $this ) . "_users";
			$subs_table_name = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			$subscriptions = array();
			$sql = "SELECT $users_table_name.id as user_id, $subs_table_name.id AS subs_id
				FROM $users_table_name, $subs_table_name
				WHERE $subs_table_name.user_id = $users_table_name.id AND
				$subs_table_name.accepted = 1 AND
				$users_table_name.wp_url = '$blog_url' AND
				$subs_table_name.term_id IN $courses_sql_str";
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				$subscriptions[$id]["user_id"] = $row->user_id;
				$subscriptions[$id]["subs_id"] = $row->subs_id;
			}
			if( count( $subscriptions ) == 0){	//user isnt subscribed to this course
				return false;
			}
			return $subscriptions[$id]["user_id"];
		}

		function add_assesment( $comment_id, $user_id, $trackback_url ) {
			global $wpdb;

			$comment_id = $wpdb->escape( $comment_id );
			$user_id = $wpdb->escape( $user_id );
			$trackback_url = $wpdb->escape( $trackback_url );
			$table_name = $wpdb->prefix . get_class( $this ) . "_filed_assignments";
			$insert = "INSERT INTO " . $table_name .
			" (comment_id, user_id, feedback_url) " .
			"VALUES (" . $comment_id . ", " . $user_id . ", '" . $trackback_url . "')";

			$wpdb->query( $insert );
		}

		function set_assesment_grade( $comment_id, $grade ){
			global $wpdb;
			$comment_id = $wpdb->escape( $comment_id );
			$grade = $wpdb->escape( $grade );
			$table_name = $wpdb->prefix . get_class( $this ) . "_filed_assignments";

			$wpdb->query("UPDATE $table_name SET grade='$grade' WHERE comment_id=$comment_id");
		}

		function update_assesment_dates( $assesment_id, $start, $end ){
			global $wpdb;
			$assesment_id = (int)$wpdb->escape( $assesment_id );
			$start = $wpdb->escape( date( 'Y-n-j', strtotime( $start ) ) );
			$end = $wpdb->escape( date( 'Y-n-j', strtotime( $end ) ) );
			$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";

			$wpdb->query("UPDATE $table_name SET start='$start', end='$end' WHERE id=$assesment_id");
		}

		function send_assesment_feedback( $comment_id, $feedback_content ){
			global $wpdb;
			$comment_id = $wpdb->escape( $comment_id );
			$feedback_content = $wpdb->escape( $feedback_content );
			$table_name = $wpdb->prefix . get_class( $this ) . "_filed_assignments";
			$sql = "SELECT id, grade, feedback_url
					FROM $table_name
					WHERE comment_id = $comment_id";
			if ( $row = $wpdb->get_row( $sql ) ) {
				$feedback_id = $row->id;
				$grade = $row->grade;
				$trackback_url = $row->feedback_url;
				$trackback_url_has_query_params = @parse_url($trackback_url, PHP_URL_QUERY);
				if($trackback_url_has_query_params == NULL) {
					$trackback_url .= '?grade=' . $grade;
				}else{
					$trackback_url .= '&grade=' . $grade;
				}
				$comment = get_comment( $comment_id );
				$post_id = $comment->comment_post_ID;
				trackback( $trackback_url, 'Your homework grade is ' . $grade, $feedback_content, $post_id );
				return true;
			}else{
				//feedback could not be sent
				return false;
			}
		}

		function get_ungraded_assignments_count(){
			global $wpdb;
			$table_name = $wpdb->prefix . get_class( $this ) . "_filed_assignments";
			$table_name_subscr = $wpdb->prefix . get_class( $this ) . "_subscribed_users";
			
			$sql = "SELECT comment_id
					FROM $table_name, $table_name_subscr
					WHERE grade IS NULL and $table_name_subscr.user_id = $table_name.user_id GROUP BY comment_id";
			
			$total = 0;
			$result = $wpdb->get_results($sql, OBJECT);
			foreach($result as  $row)  {
				$comment = get_comment($row->comment_id);
				if($comment) {
					$post = get_post($comment->comment_post_ID);
					if($post) {
						if($this->post_is_assignment($comment->comment_post_ID) && $post->post_status != "trash") {
							$total++;
						}
					}
				}
			}
			return $total;
		}
		

		function get_assignments_that_ended ( $days ) {
			global $wpdb;

			$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";

			$sql = "SELECT id, post_id FROM $table_name WHERE end < sysdate() - interval $days day";
			$rows = $wpdb->get_results( $sql, OBJECT );
			$assignments = array();
			foreach ($rows as $row){
				$id = $row->id;
				$assignments[$id]["id"] = $row->id;
				$assignments[$id]["post_id"] = $row->post_id;
			}
			return $assignments;
		}

		function get_default_course_id(){
			global $wpdb;
			$course = array();
			$terms_table_name = $wpdb->prefix . "terms";
			$table_name = $wpdb->prefix . get_class( $this ) . "_course_categories";
		   $sql = "SELECT  $table_name.term_id as term_out_id
						FROM $table_name, $terms_table_name WHERE  $table_name.term_id = $terms_table_name.term_id AND is_course=1
						LIMIT 1";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return $row->term_out_id;
			}else{
				return false;
			}
		}

		//end of functions with directly database

		//done
		function notify_subscription_user_email ( $subscription_id, $accepted, $responseText ) {
			global $wpdb;

			$subscription = $this->get_subscription( $subscription_id );
			$course_name = $this->get_course_name( $subscription['term_id'] );
			$to = $this->get_user_email( $subscription['user_id'] );
			$subject = 'Subject not set';
			$message = __( 'Dear Student!' ) . "\r\n";

			if ( $accepted ) {
				$subject = __( 'You have been accepted to a ' ) . $course_name . ' ' . __( 'course' );
				$message.= sprintf( __( 'You have been accepted to a course. Teacher response: %s' ), "\r\n" . $responseText );
			}else {
				$subject = __( 'You have been rejected to a ' ) . $course_name . ' ' . __( 'course' );
				$message.= sprintf( __( 'You have been rejected to a course. Teacher response: %s' ), "\r\n" . $responseText );
			}
			wp_mail($to, $subject, $message);
		}

		function invite_students_email( $emails, $course_ids, $key, $teacher_message ) {
			global $wpdb;

			$subject = __( 'You have been invited to a course' );
			$message = __( 'Dear Student!' );
			
			
			foreach($course_ids as $course_id) {
				$course_name = $this->get_course_name( $course_id );
				$course_url = urldecode(get_category_link( $course_id ));	
			
				$message .= "\r\n". "\r\n".sprintf( __( 'I invite you to join %s course.' ), $course_name) . "\r\n\r\n";
				$message .= __( 'Message from teacher: ' ) . $teacher_message . "\r\n\r\n";
				$message .= __( 'URL for this course is: ' ) . $course_url . "\r\n";
				
				if($key == 1) {
					//personal key
					$invite_key = $this->generate_key( $course_id, false );
					$key_info = "\r\n".__( 'When subscribing to a course, use following key: ' ) . $invite_key . "\r\n";
					$message .= $key_info;
				}
				
				if($key == 2) {
					//group key
					$invite_key = $this->generate_key( $course_id, true );
					$key_info = "\r\n".__( 'When subscribing to a course, use following key: ' ) . $invite_key . "\r\n";
					$message .= $key_info;
				}
				
				if($key) {
				 	$key_txt = "and key into appropriate fields";
				} else {
					$key_txt = "in appropriate field";	
				}
			}
			
				$download_note = __("\r\n".'Please go to Subscribtions page of your LePress dashboard in your WordPress blog and enter course URL '.$key_txt.' of Subscribe to Course form and then submit the form.')."\r\n";
				
				$download_note .= "\r\n" . __( 'To subscribe to course(s), you need a LePress plugin for your wordpress blog.' ) . "\r\n";
				$download_note .= __( 'You can download this plugin from here: ' ) . $this->plugin_download_url;
			
				foreach($emails as $to) {
					$msg = $message . $download_note;
					wp_mail($to, $subject, $msg);
				}			
		}
		
	function post_deleted_handler($post_id) {
		$cat_data = get_the_category($post_id);
		//if the post category is course - then it's obviously assignment
		foreach($cat_data as $cat) {
			if($this->get_is_course($cat->term_id)) {
				$this->remove_assignment($post_id);
				break;
			}
		}
	}
	
	function comment_deleted_handler($comment_ids) {					
			$comment_ids = implode(',', $comment_ids);	
			global $wpdb;
			
			$table_name_filed = $wpdb->prefix . get_class( $this ) . "_filed_assignments";
			$delete_filed = "DELETE FROM " . $table_name_filed . " WHERE comment_id IN (".$comment_ids.")";
			$wpdb->query( $delete_filed);
	}


	function post_assignment_handler() {
		$action = $_REQUEST['action'] != '-1' ? $_REQUEST['action'] : $_REQUEST['action2'];
		switch($action) {
			case 'mark_as_assignment':
					foreach($_REQUEST['post'] as $post_id) {
						if(!$this->post_is_assignment ($post_id)) {
							$categories = get_the_category($post_id);
							foreach($categories as $category) {
								if($this->get_is_course($category->term_id)) {
									$this->add_assignement($post_id, date('Y-m-d'), date('Y-m-d', strtotime('+1 day')));
									break;
								}
							}
						}
					}
				break;
			case 'mark_as_not_assignment':
					foreach($_REQUEST['post'] as $post_id) {
						if($this->post_is_assignment ($post_id)) {
							$this->remove_assignment($post_id);
						}
					}
				break;	
		}	
	}
	
		function LePress_columns($defaults) {
		    $defaults['lepress_assignment'] = __('Assignment');
		    return $defaults;
		}
		
		function custom_column_handler($column_name, $post_id) {
		    if( $column_name == 'lepress_assignment' ) {
		        if($this->post_is_assignment($post_id)) {
		        	_e('Yes');	
		        } else {
		        	_e('No');	
		        }
		    }
		}
		function admin_head_Code() {
			if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == "write_assignment_page" ) {
			     			 		 
				wp_enqueue_script( 'common' );
				wp_enqueue_script( 'jquery-color' );
				wp_print_scripts('editor');
				if (function_exists('add_thickbox')) add_thickbox();
				wp_print_scripts('media-upload');
				if (function_exists('wp_tiny_mce')) wp_tiny_mce();
				wp_admin_css();
				wp_enqueue_script('utils');
				do_action("admin_print_styles-post-php");
				do_action('admin_print_styles');
			}
			
		$break = explode('/', $_SERVER["SCRIPT_NAME"]);
		$pfile = $break[count($break) - 1]; 
		
		if($pfile == "edit.php" && $_GET['post_type'] == "post") {	
			echo '<script type="text/javascript">
				jQuery(document).ready(function($){
					$("select[name=action] > option:last").after("<option disabled=\'disabled\'>-- LePress assignment --</option>");
					$("select[name=action] > option:last").after("<option value=\'mark_as_assignment\'>Assignment</option>");
					$("select[name=action] > option:last").after("<option value=\'mark_as_not_assignment\'>Not assignment</option>");
					
					$("select[name=action2] > option:last").after("<option disabled=\'disabled\'>-- LePress assignment --</option>");
					$("select[name=action2] > option:last").after("<option value=\'mark_as_assignment\'>Assignment</option>");
					$("select[name=action2] > option:last").after("<option value=\'mark_as_not_assignment\'>Not assignment</option>");

				});
			</script>';
		
		}

	   }

		function awaiting_mod( $awaiting_what ){
			$count = 0;
			if( $awaiting_what == "awaiting authorisation" ){
				$count = count( $this->get_unsubscribed_users() );
			}elseif( $awaiting_what == "new assignments" ){
				$count = $this->get_ungraded_assignments_count();
			}

			$text = '<span id="awaiting-mod" class="count-' . $count . '">
								<span class="pending-count">' . $count . '</span>
							</span>';

			return $text;
		}

		function manage_courses_menu(){
			add_menu_page( 'LePress Teacher', 'LePress', 3, __FILE__, array( &$this, 'manage_courses_page' ) );
			add_submenu_page( __FILE__, 'Courses Managing', 'Courses', 3, __FILE__, array( &$this, 'manage_courses_page' ) );
			add_submenu_page( __FILE__, 'Subscriptions', 'Subscriptions' . $this->awaiting_mod( 'awaiting authorisation' ), 3, 'manage_subscriptions_page', array( &$this, 'manage_subscriptions_page' ) );
			add_submenu_page( __FILE__, 'Write assignment', 'Write assignment', 3, 'write_assignment_page', array( &$this, 'write_assignment_page' ) );
			add_submenu_page( __FILE__, 'Assignments', 'Manage assignments' . $this->awaiting_mod( 'new assignments' ), 3, 'manage_assignments_page', array( &$this, 'manage_assignments_page' ) );
		}

		function rss2_feed_item(){
			global $post;
			if ( $this->post_is_assignment( $post->ID ) == true ) {
				echo '<assignment>';
				echo '<start>' . $this->assignment_start( $post->ID ) . '</start>';
				$end = $this->assignment_end( $post->ID );
				if ( $end ) {
					echo '<end>' . $end . '</end>';
				}
				echo '</assignment>';
				echo '<trackback>' . get_trackback_url() . '</trackback>';
				echo '<id>' . $post->ID . '</id>';
			}
			//echo '<test>' . $post->ID . '</test>';
		}

		function install () {
			global $wpdb;

			$table_name = $wpdb->prefix . get_class($this) . "_course_categories";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  term_id bigint(20) NOT NULL,
					  is_course bigint(1) DEFAULT '0' NOT NULL,
					  organis_name VARCHAR(200),
					  teacher_first_name VARCHAR(200),
					  teacher_last_name VARCHAR(200),
					  teacher_email VARCHAR(200),
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				//require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}

			$table_name = $wpdb->prefix . get_class($this) . "_users";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  first_name VARCHAR(200),
					  last_name VARCHAR(200),
					  wp_url VARCHAR(2000),
					  email VARCHAR(200),
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}

			$table_name = $wpdb->prefix . get_class($this) . "_subscribed_users";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  term_id bigint(20) NOT NULL,
					  user_id bigint(20) NOT NULL,
					  accepted int(1),
					  accept_key VARCHAR(6),
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}

			$table_name = $wpdb->prefix . get_class($this) . "_group_invites";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  term_id bigint(20) NOT NULL,
					  invite_key VARCHAR(5) NOT NULL,
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}

			$table_name = $wpdb->prefix . get_class($this) . "_personal_invites";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  term_id bigint(20) NOT NULL,
					  wp_url VARCHAR(2000) NOT NULL,
					  invite_key VARCHAR(5) NOT NULL,
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}

			$table_name = $wpdb->prefix . get_class($this) . "_assignments";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  post_id bigint(20) NOT NULL,
					  start date NOT NULL,
					  end date,
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}

			$table_name = $wpdb->prefix . get_class($this) . "_filed_assignments";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  comment_id bigint(20) NOT NULL,
					  user_id bigint(20) NOT NULL,
					  grade VARCHAR(2) NULL,
					  feedback_url VARCHAR(2000) NOT NULL,
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			//if you add table table here, dont forget to add it to uninstall

			wp_schedule_event(time(), 'daily', 'scheduled_old_assignment_check_hook');

		}

		function manage_courses_page() {
			$action = ( isset( $_REQUEST['action'] ) ) ? $_REQUEST['action'] : '';
			switch ( $action ) {
				case 'edit':
					include( 'edit_category.php' );
					break;
				case 'editedcat':
					$cat_ID = (int) ( isset( $_REQUEST['cat_ID'] ) ) ? $_REQUEST['cat_ID'] : null;
					$is_course = ( empty( $_REQUEST['category_is_course'] ) ) ? '0' : '1';
					$organisation_name = ( isset( $_REQUEST['category_organisation_name'] ) ) ? $_REQUEST['category_organisation_name'] : '';
					$first_name = ( isset( $_REQUEST['teacher_f_name'] ) ) ? trim($_REQUEST['teacher_f_name']) : '';
					$last_name = ( isset( $_REQUEST['teacher_l_name'] ) ) ? trim($_REQUEST['teacher_l_name']) : '';
					$email = ( isset( $_REQUEST['teacher_email'] ) ) ? trim($_REQUEST['teacher_email']) : '';
					$desc = ( isset( $_REQUEST['description'] ) ) ? $_REQUEST['description'] : '';
					$parent = ( isset( $_REQUEST['parent'] ) ) ? $_REQUEST['parent'] : '';
					if ($cat_ID && $this->get_course($cat_ID)){
						if(!empty($first_name) && !empty($last_name)  && !empty($email)) {
							$this->update_category( $cat_ID, $is_course, $organisation_name, $first_name, $last_name, $email, $desc, $parent );
							include( 'manage_courses_page.php' );
						} else {
							$validate_failed = true;
							include('edit_category.php');
						}
					}else echo __('You cannot change data if category is not course.');
					break;
				default:
					include( 'manage_courses_page.php' );
			}
		}

		function getSubsStudents() {	//for xmlhttprequest
			if( isset( $_REQUEST['id'] ) ){
				echo "<table id='getSubsStudentsData'>";
				foreach ( $this->get_subscribed_users_full_data( $_REQUEST['id'] ) as $userId ) {
					echo "<tr>";
					echo "<td>" . $userId["subsId"] . "</td>";
					echo "<td>" . $userId["fName"] . " " . $userId["lName"] . "</td>";
					echo "<td>".$userId["email"]."</td>";
					echo "<td><a href=\"admin.php?page=manage_assignments_page&course_id=".$_REQUEST["id"]."\">View assignments</a></td>";
					echo "<td><a href='" . $userId["wpURL"] . "'>" . $userId["wpURL"] . "</a></td>";
					echo "<td><a href='mailto:" . $userId["email"] . "'>Send e-mail</a></td>";
					echo "<td><a href='?page=manage_subscriptions_page&#038;action=removeSubscription&#038;id=" .
						$userId["subsId"] . "&amp;s_course=".$_REQUEST['id']."'>Delete</a></td>";
					echo "</tr>";
				}
				echo "<table>";
			}
		}

		function get_widget_calendar( $course_id, $month, $year, $widget_id ){
			$assignments = $this->get_assignments_names( $course_id );
			$this_month_assignments = array();
			$return_str = '';
			$assignments_next_month = false;
			$assignments_prev_month = false;

			$timestamp = mktime(0,0,0,$month,1,$year);
			$maxday = date( 't',$timestamp );
			$month_name = date( 'F',$timestamp );
			$thismonth = getdate( $timestamp );
			$startday = $thismonth['wday'];

			//sort assignments
			foreach( $assignments as $assignment ){
				if ( date( 'n', strtotime( $assignment['end'] ) ) == $month && date( 'Y', strtotime( $assignment['end'] ) ) == $year ){
					//array_push( $this_month_assignments[ date( 'j', strtotime( $assignment['end'] ) ) ], $assignment );
					if( isset( $this_month_assignments[ date( 'j', strtotime( $assignment['end'] ) ) ] ) ){
						array_push( $this_month_assignments[ date( 'j', strtotime( $assignment['end'] ) ) ], $assignment );
					}else{
						$this_month_assignments[ date( 'j', strtotime( $assignment['end'] ) ) ] = array();
						array_push( $this_month_assignments[ date( 'j', strtotime( $assignment['end'] ) ) ], $assignment );
					}
				}
				if ( ( date( 'n', strtotime( $assignment['end'] ) ) < $month && date( 'Y', strtotime( $assignment['end'] ) ) <= $year )
						|| date( 'Y', strtotime( $assignment['end'] ) ) < $year ){
					$assignments_prev_month = true;
				}
				if ( ( date( 'n', strtotime( $assignment['end'] ) ) > $month && date( 'Y', strtotime( $assignment['end'] ) ) >= $year )
						|| date( 'Y', strtotime( $assignment['end'] ) ) > $year ){
					$assignments_next_month = true;
				}
			}
			//end of sorting


			$return_str .= '<table>';
			$return_str .= '<tr><td colspan="7" style="text-align: center;">' . __( $month_name ) . ', ' . $year . '<td></tr>';
			$return_str .= '<tr><td align="center">M</td><td align="center">T</td><td align="center">W</td><td align="center">T</td><td align="center">F</td><td align="center">S</td><td align="center">S</td></tr>';
			for ($i=0; $i<($maxday+$startday); $i++) {
				if(($i % 7) == 1 ) $return_str .= "<tr>";
				if($i < $startday){
					$return_str .= "<td></td>";
				}else{
					$today_border = '';
					if(($i-$startday + 1) == date('j') && $month == date('n') && $year == date('Y')) {
						$today_border = "border: 1px solid red;";
					}
					if( isset( $this_month_assignments[($i - $startday + 1)] ) ){
						$return_str .= '<td align="center" style="width: 20px; '.$today_border.'"><a href="#" rel="widget-' . $widget_id . '-calendar-' . ($i - $startday + 1) . '" onclick="return false;">' . ($i - $startday + 1) . '</a>';
						$return_str .= '<div class="calendar_event" id="widget-' . $widget_id . '-calendar-' . ($i - $startday + 1) . '"
							style="text-align: left; position:absolute;display:none;background:#ffffff; border:1px solid;" >';
						foreach( $this_month_assignments[($i - $startday + 1)] as $assignment ){
							$return_str .= '<a style="margin: 3px 3px 2px 2px;" href="' . get_permalink( $assignment['post_id'] ) .
								'"><img src="' . plugin_dir_url( __FILE__ ) .
								'images/icons/pencilicon.png" alt="assessment" width="15" height="15" />' .
								$assignment['name'] . '</a><br/>';
						}
						$return_str .= '</div></td>';
					}else{
						$return_str .= '<td align="center" style="width: 20px; '.$today_border.'">' . ($i - $startday + 1) . '</td>';
					}
				}
				if(($i % 7) == 0 ) $return_str .= "</tr>";
			}
			$return_str .= '<tr>';
			if( $assignments_prev_month == true ){
				$prev_month = $month - 1;
				$prev_year = $year;
				if( $prev_month <= 0 ){
					$prev_month = 12;
					$prev_year --;
				}
				$return_str .= '<td align="center" colspan="3"><a href="#" onclick="get_calendar(\''.$widget_id.'\',' . $course_id.
					',' . $prev_month. ',' . $prev_year . ',\''.plugin_dir_url(__FILE__).'ajax.php\');return false;" >' . __( 'prev' ) . '</a></td>';
			}else{
				$return_str .= '<td></td>';
			}
			
			$return_str .= '<td></td>';
			if( $assignments_next_month == true ){
				$next_month = $month + 1;
				$next_year = $year;
				if( $next_month > 12 ){
					$next_month = 1;
					$next_year ++;
				}
				$return_str .= '<td align="center" colspan="3"><a href="#" onclick="get_calendar(\''.$widget_id.'\',' . $course_id.
					',' . $next_month. ',' . $next_year . ',\''. plugin_dir_url(__FILE__).'ajax.php\');return false;" >' . __( 'next' ) . '</a></td>';
			}else{
				$return_str .= '<td></td>';
			}
			$return_str .= '</tr></table>';
			return $return_str;
		}

		function get_widget_body( $widget_id, $instance) {
			global $wp_query;

			$course_id = -1;
			if ( isset( $_REQUEST['course_id'] ) ){
				$course_id = $_REQUEST['course_id'];
			}elseif ( $wp_query->query_vars['cat'] ){
				$course_id = $wp_query->query_vars['cat'];
			}elseif ( $wp_query->query_vars['category_name'] ){
				$course_id = get_cat_ID( $wp_query->query_vars['category_name'] );
			}else{
				$course_id = $this->get_default_course_id();
			}
			
			if(!$this->get_is_course($course_id) && isSet($course_id)) {
				$course_id = $this->get_default_course_id();	
			}
			
			//if no courses exist
			if( $course_id == false ){return __('No active courses');}
			
			$return_str = '';
			$return_str = $debug;

			//dropdown box with list of courses
			$return_str .= '<a href="';
			if( isset( $course_id ) ){
				$return_str .= get_category_link( $course_id );
			}
			$return_str .= '" ><img src="' . plugin_dir_url( __FILE__ ) .
					'/images/icons/bookicon.png" alt="assessment" width="20" height="20" hspace="3" vspace="3" align="left" /></a>';
			$return_str .= '<select name="course_id" id="widget-'. $widget_id . '-course_id'
					. '" onChange="change_course(\'' . $widget_id . '\', \'' .plugin_dir_url(__FILE__).'ajax.php?collapse_students='.$instance['collapse_students'].'\') ">';
			foreach( $this->get_courses_names() as $id => $name ) {
				$return_str .= '<option value=' . $id;
				if( isset( $course_id ) && $course_id == $id ){
					$return_str .= ' selected="selected"';
				}
				$return_str .= '>' . $name . '</option>';
			}
			$return_str .= '</select>';

			//calendar
			$return_str .= '<div id="widget-'. $widget_id . '-calendar" >';
			if( isset( $course_id ) ) {
				$return_str .= $this->get_widget_calendar( $course_id, date('n'), date('Y'), $widget_id );
			}
			$return_str .= '</div>';

			
			if($instance['collapse_students'] == 1) {
					$collapsed = "none";	
					$sign = "+";
				} else {
					$collapsed = "block";	
					$sign = "-";
				}

			//course students
			$return_str .= '<h2>' . '<img src="' . plugin_dir_url( __FILE__ ) .
					'images/icons/groupicon.png" width="20" height="20" hspace="3" align="absmiddle" />
					<span style="cursor: pointer;" onclick="jQuery(\'div[id=lepress_workgroup]\').slideToggle(\'fast\'); if(this.innerHTML == \''. __( 'Participants' ) . ' +\') { this.innerHTML = \''. __( 'Participants' ) . ' -\'; } else { this.innerHTML = \''. __( 'Participants' ) . ' +\'; }">'. __( 'Participants' ) . ' '.$sign.'</span>
					</h2>';
			if( isset( $course_id ) ) {				
			
				foreach( $this->get_subscribed_users_full_data( $course_id ) as $student ) {
					
					
					$return_str .= '<div class="vcard" id="lepress_workgroup" style="display:'.$collapsed.';">';
					$return_str .= '<a class="email" href="mailto:' . str_replace('@', '[at]', $student['email']). '" >';
					$return_str .= '<img src="' . plugin_dir_url( __FILE__ ) .
						'images/icons/emailicon.png" alt="email" width="20" height="20" hspace="3" align="absmiddle" /></a>';
					$return_str .= ' <a class="fn url" href="' . $student['wpURL'] . '" title="' . __( 'Personal blog' ) . '" >' . $student['lName']
							. ' ' . $student['fName'] . '</a>';
					$return_str .= '</div>';
				}
			}
			//course assignements

			$return_str .= '<dl class="vcalendar"><h2>' . '<img src="' . plugin_dir_url( __FILE__ ) .
				'images/icons/craduateicon.png" width="20" height="20" hspace="3" align="absmiddle" />'
				. __( 'Assignments' ) . '</h2>';
			if ( isset( $course_id ) ){
				
				if($instance['collapse_assignments'] == 1) {
					$collapsed = "none";	
					$sign = "+";
				} else {
					$collapsed = "block";	
					$sign = "-";
				}
				
				foreach( $this->get_assignments_names( $course_id ) as $assignment ) {
					$salt = time();
					$return_str .= '<div class="vevent"><dt><a class="url"  style="font-weight: bold;" href="' . get_permalink( $assignment['post_id'] ) .
						'"><img src="' . plugin_dir_url( __FILE__ ) .
						'images/icons/pencilicon.png" alt="assessment" width="15" height="15" /> ' .
						$assignment['end'] . ' <span class="summary">' . $assignment['name'] . '</span></a> <a href="#" style="font-size: 10pt;" onclick="this.innerHTML == \'+\'? this.innerHTML = \'-\': this.innerHTML = \'+\'; jQuery(\'#'.md5($assignment['name'].$salt).'\').slideToggle(\'fast\'); return false;">'.$sign.'</a>';
					$return_str .= '<abbr class="dtstart" title="' . $assignment['start'] . '"></abbr>';
					$return_str .= '<abbr class="dtend" title="' . $assignment['end'] . '"></abbr></dt>';
					
					$return_str .= '<div id="'.md5($assignment['name'].$salt).'" style="display:'.$collapsed.'">';
				
					
					foreach( $this->get_course_users_ids( $course_id ) as $userId ){
						$post_id = $assignment['post_id'];
						$assessment = $this->get_assessment( $post_id , $userId['id'] );
						if( $assessment != false ){
							$return_str .= '<a href="' . $assessment['comment_url'] .
								'"><img src="' . plugin_dir_url( __FILE__ ) .
								'images/icons/assready.png" alt="' . $assessment['comment_url'] .
								'" width="15" height="15"  align="absmiddle" /></a>';

							if( $assessment['grade'] != false ){
								$return_str .= ' <a href="' . $assessment['comment_url'] .
								'"><img src="' . plugin_dir_url( __FILE__ ) .
								'images/icons/green.png" alt="' . $assessment['comment_url'] .
								'" width="15" height="15"  align="absmiddle" /></a> ';
							}else{
								$return_str .= ' <a href="'.get_bloginfo('siteurl').'/wp-admin/admin.php?page=manage_assignments_page&course_id='.$course_id.'"><img src="' . plugin_dir_url( __FILE__ ) .
								'images/icons/red.png" alt="' . __( 'Send feedback' ) .
								'" width="15" height="15"  align="absmiddle" /></a> ';
							}
						
							$return_str .= '<a href="' . $assessment['wp_url'] . '">'.$assessment['last_name'] . ' ' . $assessment['first_name'].'</a><br/>';

							$return_str .= '';
						}
						
					}
					$return_str .= '</div>';
					$return_str .= '</div>';
				}
			}
			$return_str .= '</dl>';	//end of assignments
			
			return $return_str;
		}

		function uninstall() {
			global $wpdb;
			$list_of_tables_to_drop = Array( '_course_categories', '_users','_subscribed_users', /*'_unsubscribed_users', */
				'_group_invites', '_personal_invites', '_assignments', '_filed_assignments' );

			foreach ( $list_of_tables_to_drop as $table_name ) {
				$full_table_name = $wpdb->prefix . get_class($this) . $table_name;
				$sql = "DROP TABLE " . $full_table_name;
				$wpdb->query( $sql );
			}

			wp_clear_scheduled_hook( 'scheduled_old_assignment_check_hook' );
		}

        function runInit() {
            if(isSet($_REQUEST)) {
                if($_REQUEST['page'] == "write_assignment_page" || $_REQUEST['page'] == 'manage_assignments_page') {
                    //First register our script
        			 wp_register_script( 'epoch-calendar', plugins_url('/js/epoch_classes.js', __FILE__));	 
        			 wp_register_script( 'epoch-calendar_init', plugins_url('/js/load_epoch_calendar.js', __FILE__));	 
        			//and then load it
        			 wp_enqueue_script( 'epoch-calendar');
        			 wp_enqueue_script( 'epoch-calendar_init');
        			 
        			  //First register our stylesheet
        			 wp_register_style( 'LePressTeacherCss-epoch-calendar', plugins_url('/js/epoch_styles.css', __FILE__));	
        			 //and then load it
        			 wp_enqueue_style( 'LePressTeacherCss-epoch-calendar');
                }
            }
        }
		function write_assignment_page() {
			include( 'write_assignement_page.php' );
		}

		function manage_assignments_page() {
			include( 'manage_assignments_page.php' );
		}

		function manage_subscriptions_page() {
			include( 'manage_subscriptions_page.php' );
		}

		function wp_head() {
			global $wp_query;
			global $current_user;
			get_currentuserinfo();
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script('LePressTeacherWidgetScript');	//load javascript for widget
			
			if ( isset( $_REQUEST['action'] ) && ($wp_query->is_category || is_404())) {
				
				switch ( $_REQUEST['action'] ) {
					case "LePressTeacherUpdateProfile":
						//basic check here, if parameter siteurl is not empty
						if(!empty($_REQUEST['siteurl'])) {
							if($_REQUEST['siteurl'] ==  get_bloginfo('siteurl')) {
							 		return;	
							 	}
							//all the other security check done by update_user method
							$this->update_user($_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['siteurl'], $_REQUEST['email'], $_REQUEST['student_key'], $_REQUEST['term_id']);
						}
						break;		
						
					case "LePressStudentGetClassmates":
						//basic check here, if parameter siteurl is not empty
						if(!empty($_REQUEST['siteurl'])) {
							if($_REQUEST['siteurl'] ==  get_bloginfo('siteurl')) {
							 		return;	
							 }
							//check key validity
							if($this->check_communication_key_validity($_REQUEST['term_id'], $_REQUEST['siteurl'], $_REQUEST['student_key'])) {
									$subscribed_users = $this->get_subscribed_users_full_data($_REQUEST['term_id']);				
									echo '<LepressTeacherClassmates><?xml version="1.0" encoding="UTF-8"?>';
									echo '<classmates>';
									foreach($subscribed_users as $key => $subscriber) {
											echo '<mate id="'.$key.'">';
											echo '<firstname>'.$subscriber['fName'].'</firstname>';
											echo '<lastname>'.$subscriber['lName'].'</lastname>';
											echo '<wp_url>'.$subscriber['wpURL'].'</wp_url>';
											echo '<email>'.$subscriber['email'].'</email>';
											echo '</mate>';
									}
									echo '</classmates>';
									echo '</LepressTeacherClassmates>';
							}
						}
						break;
						
				case "LePressStudentGetCourseUpdate":
						//basic check here, if parameter siteurl is not empty
						if(!empty($_REQUEST['siteurl'])) {
								if($_REQUEST['siteurl'] ==  get_bloginfo('siteurl')) {
							 		return;	
							 	}
							//check key validity
							echo '<?xml version="1.0" encoding="UTF-8"?><LepressTeacherCourseUpdate>';
							if($this->check_communication_key_validity($_REQUEST['term_id'], $_REQUEST['siteurl'], $_REQUEST['student_key'])) {
									$course = $this->get_course($_REQUEST['term_id']);		
									if($course) {	
										echo '<courseData id="'.$course['id'].'">';
											echo '<status>1</status>';
											echo '<teacher>';
											echo '<firstname>'.$course['teacher_first_name'].'</firstname>';
											echo '<lastname>'.$course['teacher_last_name'].'</lastname>';
											echo '<email>'.$course['teacher_email'].'</email>';
											echo '<organization>'.$course['organis_name'].'</organization>';
											echo '</teacher>';
									
										echo '</courseData>';
									}  else {
										echo '<courseData>';
										echo '<status>-1</status>';
										echo '</courseData>';
									}
									
							} else {
										echo '<courseData>';
										echo '<status>-1</status>';
										echo '</courseData>';
									}
						echo '</LepressTeacherCourseUpdate>';
						}
						break;

						
					case "LePressTeacherSubscribe":
						if ( isset( $_REQUEST['siteurl'] ) &&
							 isset( $_REQUEST['fname'] ) && isset( $_REQUEST['lname'] ) &&
							 isset( $_REQUEST['email'] ) ) {
							 	
							 	if($_REQUEST['siteurl'] ==  get_bloginfo('siteurl')) {
							 		//Disable subscription to your own blog
							 		$response = "You cannot subscribe to your own course";
							 		echo "<LePressResponse><Message>" . $response . "</Message></LePressResponse>";
							 		return;	
							 	}
							//$course_id = $this->get_course_id_by_slug( $_REQUEST['slug'] );
							if ( $wp_query->query_vars['cat'] ){
								$course_id = $wp_query->query_vars['cat'];
							}else{
								$course_id = get_cat_ID( $wp_query->query_vars['category_name'] );
							}
							if ( $course_id && $this->get_is_course( $course_id ) ) {
								if ( isset ( $_REQUEST['inviteKey'] ) && $_REQUEST['inviteKey'] != '' ) {
									//key is set if LePressTeacher has send invitation or requester has a personal or group key for specific course
									if ( $this->check_key_validity( $course_id, $_REQUEST['inviteKey'] ) ) {
										//invite key is found in database
										$user_id;
										//check if user with this WP siteurl and e-mail exists in database, if it does user ID will be returned, otherwise new user will be created
										if ( !( $user_id = $this->get_user_id( $_REQUEST['siteurl'], $_REQUEST['email'] ) ) ) {
											$this->add_user( $_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['siteurl'], $_REQUEST['email'] );
											$user_id = $this->get_user_id( $_REQUEST['siteurl'], $_REQUEST['email'] );
										}
										$course = $this->get_course( $course_id );
										//subscribe to course
										$alreadySubcribed = $this->add_subscription( true, $user_id, $course_id, $_REQUEST['acceptKey'] );
										if($alreadySubscribed) {
											$response = "<Message>You are already subscribed to that course</Message>";
										} else {
										//remove invitation from database
										$this->remove_personal_invite( $course_id, $_REQUEST['inviteKey'] );
										$response = "<Message>Added to Course</Message>" .
													"<CourseName>" . $this->get_course_name( $course_id ) . "</CourseName>" .
													"<O_name>" . $this->get_course_organisation_name( $course_id ) . "</O_name>" .
													"<Email>" . $course['teacher_email'] . "</Email>" .
													"<F_name>" . $course['teacher_first_name'] . "</F_name>" .
													"<L_name>" . $course['teacher_last_name'] . "</L_name>" .
													"<Course_id>" . $course_id .  "</Course_id>" .
													"<Feed_url>" . get_category_feed_link( $course_id, "rss2" ) . "</Feed_url>" .
													"<Course_url>" . get_category_link( $course_id ) . "</Course_url>";
										}
									} else {
										$response = "<Message>Bad inviteKey</Message>";
									}
								} else {
									//subscriber doesn't have invite key, subscription request will be made
									$user_id;
									//check if user with this WP siteurl and e-mail exists in database, if it does user ID will be returned, otherwise new user will be created
									if ( !( $user_id = $this->get_user_id( $_REQUEST['siteurl'], $_REQUEST['email'] ) ) ) {
										$this->add_user( $_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['siteurl'], $_REQUEST['email'] );
										$user_id = $this->get_user_id( $_REQUEST['siteurl'], $_REQUEST['email'] );
									}
									$course = $this->get_course( $course_id );
									//subscription request is made
									$alreadySubscribed = $this->add_subscription( false, $user_id, $course_id, $_REQUEST['acceptKey'] );
									if($alreadySubscribed) {
											$response = "<Message>You are already subscribed to that course</Message>";
									} else {
									$response = "<Message>Subscription request made</Message>" .
												"<CourseName>" . $this->get_course_name( $course_id ) . "</CourseName>" .
												"<O_name>" . $this->get_course_organisation_name( $course_id ) . "</O_name>" .
												"<Email>" . $course['teacher_email'] . "</Email>" .
												"<F_name>" . $course['teacher_first_name'] . "</F_name>" .
												"<L_name>" . $course['teacher_last_name'] . "</L_name>" .
												"<Course_id>" . $course_id .  "</Course_id>" .
												"<Feed_url>" .  get_category_feed_link( $course_id, "rss2" ) . "</Feed_url>" .
												"<Course_url>" . get_category_link( $course_id ) . "</Course_url>";
									}
								}
							} else {
								$response = "<Message>No course with that id: $course_id</Message>";
							}
						}else{
							$response = "<Message>Seems like you have one of following parameters not set in your Wordpress: siteurl, first name, last name, email.</Message>";
						}
						echo "<LePressResponse>" . $response . "</LePressResponse>";
						break;
					case "LePressTeacherUnsubscribe":
						if ( isset( $_REQUEST['siteurl'] ) && $_REQUEST['siteurl'] != '' &&
								isset( $_REQUEST['id'] ) && $_REQUEST['id'] != '' &&
								isset ( $_REQUEST['key'] ) && $_REQUEST['key'] != '' ) {
							//neccesary fields exist
						
							$subscription_id = $this->check_communication_key_validity( $_REQUEST['id'], $_REQUEST['siteurl'], $_REQUEST['key'] );
							
							if( $subscription_id != false ){
								//echo 'validation is good';
								$this->remove_subscription(intval($subscription_id));
								$subscription_id_exist = $this->check_communication_key_validity( $_REQUEST['id'], $_REQUEST['siteurl'], $_REQUEST['key'] );
								if(!$subscription_id_exist) {
									echo "<LePressResponse><Message>Successfully unsubscribed</Message><Code>1</Code></LePressResponse>";
								} else {
									echo "<LePressResponse><Message>Unsubscribing failed - validation failed</Message><Code>0</Code></LePressResponse>";
								}
							}else{
								//doesn't exist such data on teacher blog, return true - to delete
								echo "<LePressResponse><Message>Successfully unsubscribed</Message><Code>1</Code></LePressResponse>";
							}
						}else{
							//something happend, cancel
							echo "<LePressResponse><Message>Unsubscribing failed - parameters missing</Message><Code>0</Code></LePressResponse>";
						}
						break;
					case "LePressTeacherVersionCheck":
						echo "<LePressResponse>" . $this->version . "</LePressResponse>";
						break;
				}
			}
		}

		function trackback_received( $comment_id ){
			$user_id = $this->is_comment_assessment( $comment_id ); //returns false or user_id
			if ( $user_id != false && isset( $_REQUEST['trackback_url'] ) ){
				$trackback_url = $_REQUEST['trackback_url'];
				$this->add_assesment( $comment_id, $user_id, $trackback_url );
			}
		}

		function check_old_assignments(){
			global $LePressTeacher_plugin;
			$assignments = $LePressTeacher_plugin->get_assignments_that_ended( 90 );
			foreach( $assignments as $assignment ){
				$my_post = array();
				$my_post['ID'] = $assignment['post_id'];
				$my_post['post_status'] = 'private';

				// Update the post into the database
				wp_update_post( $my_post );
			}
		}
    }
}

class LePressTeacher_Widget extends WP_Widget {

	function LePressTeacher_Widget() {
		// outputs the content of the widget
		$widget_ops = array('classname' => 'LePressTeacher_Widget', 'description' => __( "Widget for LePress Teacher plugin.") );
		$this->WP_Widget('lepressteacher_widget', __('LePress Teacher'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $LePressTeacher_plugin, $wp_query;
		if( $wp_query->is_category == false && $instance['visible_everywhere'] == 0 ) return ;
		// widget actual processes
		extract($args);
		$title = apply_filters( 'widget_title', __('LePress Teacher') );

		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Make the widget body
		echo '<div id='. $this->get_field_id('body') .'>';
		echo $LePressTeacher_plugin->get_widget_body( $this->id, $instance);
		echo '</div>';
		# After the widget
		echo $after_widget;

	}

	function form($instance) {
		// outputs the options form on admin
		//Defaults
      $instance = wp_parse_args( (array) $instance, array( 'visible_everywhere'=>1, 'collapse_students' => 1, 'collapse_assignments' => 1) );

      # Output the options
      echo '<p style="text-align:right;"><label>' . __('Visible everywhere:') .
				' <input id="' . $this->get_field_id('visible_everywhere') . '" name="' .
				$this->get_field_name('visible_everywhere') . '" type="radio" value="1" ';
				if( $instance['visible_everywhere'] == 1 ){
					echo 'checked ';
				}
		echo '/></label><br/>' .
				'<label>' . __('Visible only at category page:') .
				' <input id="' . $this->get_field_id('visible_everywhere') . '" name="' .
				$this->get_field_name('visible_everywhere') . '" type="radio" value="0" ';
				if( $instance['visible_everywhere'] == 0 ){
					echo 'checked ';
				}
		echo '/></label><br/>' .
				'<label>' . __('Collapse list of the students:') .
				'<input id="' . $this->get_field_id('collapse_students') . '" name="' .
				$this->get_field_name('collapse_students') . '" type="hidden" value="0"/> '.
				' <input id="' . $this->get_field_id('collapse_students') . '" name="' .
				$this->get_field_name('collapse_students') . '" type="checkbox" value="1" ';
				if( $instance['collapse_students'] == 1 ){
					echo 'checked ';
				}
		echo '/></label><br/>' .
				'<label>' . __('Collapse assignments student list:') .
				'<input id="' . $this->get_field_id('collapse_assignments') . '" name="' .
				$this->get_field_name('collapse_assignments') . '" type="hidden" value="0"/> '.
				' <input id="' . $this->get_field_id('collapse_assignments') . '" name="' .
				$this->get_field_name('collapse_assignments') . '" type="checkbox" value="1" ';
				if( $instance['collapse_assignments'] == 1 ){
					echo 'checked ';
				}
		echo '/>' .'</p>';
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		return $new_instance;
	}
}


if (class_exists("LePressTeacher")) {
	global $LePressTeacher_plugin;
	$version = explode('.',PHP_VERSION);
	if( (int)$version[0] >= 5 ){
		$LePressTeacher_plugin = new LePressTeacher();
	}else{
		add_action( 'admin_menu', 'lepress_teacher_before_install_error_menu', 1);
	}
}

function lepress_teacher_before_install_error_menu() {
	add_menu_page( 'LePress Teacher', 'LePress', 3, __FILE__, 'lepress_teacher_before_install_error_page' );
}

function lepress_teacher_before_install_error_page() {
	$version = explode('.',PHP_VERSION);
	echo 'You are using PHP version ' . $version[0] . '. Unfortunatelly only PHP version 5+ is supported';
}

//Actions and Filters
if (isset($LePressTeacher_plugin)) {
	global $LePressTeacher_plugin;
	
	//Actions
	register_activation_hook( __FILE__, array(&$LePressTeacher_plugin, 'install'));
	register_deactivation_hook( __FILE__, array(&$LePressTeacher_plugin, 'uninstall'));

    //init
    add_action('init', array(&$LePressTeacher_plugin, 'runInit'),1);
	add_action( 'create_category', array( &$LePressTeacher_plugin, 'create_category' ), 1 );
	add_action( 'admin_head', array( &$LePressTeacher_plugin, 'admin_head_Code' ), 1 );
	add_action( 'admin_menu', array( &$LePressTeacher_plugin, 'manage_courses_menu' ), 1 );
	add_action( 'wp_head', array( &$LePressTeacher_plugin, 'wp_head' ), 1 );
	add_action( 'rss2_item', array( &$LePressTeacher_plugin, 'rss2_feed_item' ) );
	add_action( 'trackback_post', array( &$LePressTeacher_plugin, 'trackback_received'), 1 );
	add_action(	'wp_ajax_getSubsStudents', array( &$LePressTeacher_plugin, 'getSubsStudents' ), 1 );

	add_action( 'scheduled_old_assignment_check_hook', array( &$LePressTeacher_plugin, 'check_old_assignments' ), 10, 3 );
	//init widget
	add_action('widgets_init', create_function('', 'return register_widget("LePressTeacher_Widget");'), 1);
	wp_register_script('LePressTeacherWidgetScript', plugin_dir_url( __FILE__ ) . '/js/widget.js', array( 'jquery' ) );
	
	//Category deletion via WP categories menu
	add_action('delete_category', array( &$LePressTeacher_plugin, 'category_deleted_handler'), 1, 1);
	//Post deletion via WP menu
	 add_action('delete_post', array( &$LePressTeacher_plugin, 'post_deleted_handler'), 1, 1);
	 //Comment deletion
	 add_action('deleted_comment',  array( &$LePressTeacher_plugin, 'comment_deleted_handler'), 1, 1);
	
	//Post assignment bulk actions
	if(isSet($_REQUEST['action'])) {
		$LePressTeacher_plugin->post_assignment_handler();
	}

	add_filter('manage_posts_columns', array( &$LePressTeacher_plugin, 'LePress_columns'));
	add_action('manage_posts_custom_column', array( &$LePressTeacher_plugin, 'custom_column_handler'), 10, 2);
}


?>