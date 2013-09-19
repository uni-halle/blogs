<?php 
/*  
Plugin Name: LePress Student
Plugin URI: http://trac.htk.tlu.ee/lepress/
Version: 1.03
Author: <a href="mailto:alluxxii@gmail.com">Alvar Hansen</a>, <a href="mailto:raido357@gmail.com">Raido Kuli</a>
Description: A plugin for a <a href="http://trac.htk.tlu.ee/lepress/">Centre for the Educational Technology</a>.
*/


if ( !class_exists( "LePressStudent" ) ) {
    class LePressStudent {
		public $version = 1.00;
		
        function LePressStudent() { 
            //constructor
			return;
        }
        
        function LePress_columns($defaults) {
		    $defaults['lepress_assignment_answer'] = __('Assignment answer');
		    return $defaults;
		}
		
		function custom_column_handler($column_name, $post_id) {
		    if( $column_name == 'lepress_assignment_answer' ) {
		        if($assignment = $this->get_assignment($post_id, $by_answer_post_id = true)) {
		        	_e('Yes');
		        	echo ' - <a href="'.$assignment['post_url'].'" target="_blank">'.__("View assignment").'</a>';	
		        } else {
		        	_e('No');	
		        }
		    }
		}
		
		function create_key ( $length ) {
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$key = "";
			for ($i=0; $i<$length; $i++) {
				$key .= substr( $chars, rand( 0, strlen( $chars ) - 1), 1 );
			}
			return $key;
		}
		
		function profile_updated() {
			if(isSet($_POST)) {
				$fname = $_POST['first_name'];
				$lname = $_POST['last_name'];
				$email = $_POST['email'];
				
				if(!empty($email) && !empty($fname) && !empty($lname)) {					
					$subscribed_courses = $this->get_subscribed_courses();
					
					//Send out update info to all the subscribed courses - teacher blog database update.
					foreach($subscribed_courses as $course) {
						
						$params = array('action' => 'LePressTeacherUpdateProfile',
								'siteurl' => urlencode(get_bloginfo('siteurl')),
								'email' => urlencode($email),
								'fname' => urlencode($fname),
								'lname' => urlencode($lname),
								'student_key' => urlencode($course['acceptKey']),
								'term_id' => urlencode($course['term_id']));
					
						$url = $course['courseURL'];
						$subscriptionUrl = add_query_arg( $params, $url );
						
						$ch = curl_init( $subscriptionUrl );
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
						$data = curl_exec($ch);
						curl_close($ch);
					}
				}
			}
		}
		
		function download_classmates() {
			$subscribed_courses = $this->get_subscribed_courses();
			
			foreach($subscribed_courses as $course) {			
				$course_id = $course['courseId'];			
				$params = array('action' => 'LePressStudentGetClassmates',
						'siteurl' => urlencode(get_bloginfo('siteurl')),
						'student_key' => urlencode($course['acceptKey']),
						'term_id' => urlencode($course['term_id']));
			
				$url = $course['courseURL'];
				$subscriptionUrl = add_query_arg( $params, $url );
				
				$ch = curl_init( $subscriptionUrl );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				$data = curl_exec($ch);
				curl_close($ch);
				
				//preg match XML node from HTML
				preg_match('/<LepressTeacherClassmates>(.*?)<\/LepressTeacherClassmates>./sm',$data, $matches);
				$xml = $matches[1];
				
				//for each classmates
				
				$xmlObj = @simplexml_load_string($xml);
				
				if($xmlObj) {
					//IF we have response XML from Teacher blog, delete old classmates and add new ones.
					$this->remove_all_course_classmates( $course_id );
					//add new classmates
					foreach($xmlObj as $mate) {
						$fname = $mate->firstname;
						$lname = $mate->lastname;
						$email = $mate->email;
						$wp_url = $mate->wp_url;
						//TODO accept_key check ??
						$this->add_classmate($course_id, $fname, $lname, $email, $wp_url);
					}
				}
			}
			
		}
		
		
		function download_courses() {
			
			//Update SQL table, if archived column doesn't exist, add it
			//TODO have to be removed in future releases. ??
			
			global $wpdb;
			
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			$sql = "SELECT archived FROM ".$table_name; 
			$row = $wpdb->get_row($sql);
			if($row  == NULL) {
				$update = "ALTER TABLE ".$table_name." ADD archived TINYINT";
				$update_success = $wpdb->query($update);	
			}
			
			$subscribed_courses = $this->get_subscribed_courses();
		
			foreach($subscribed_courses as $course) {			
				//print_r($course);
				$course_id = $course['courseId'];			
				$params = array('action' => 'LePressStudentGetCourseUpdate',
						'siteurl' => urlencode(get_bloginfo('siteurl')),
						'student_key' => urlencode($course['acceptKey']),
						'term_id' => urlencode($course['term_id']));
			
				$url = $course['courseURL'];
				$subscriptionUrl = add_query_arg( $params, $url );
				
				$ch = curl_init( $subscriptionUrl );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				$data = curl_exec($ch);
				curl_close($ch);
				//preg match XML node from HTML
				preg_match('/<LepressTeacherCourseUpdate>(.*?)<\/LepressTeacherCourseUpdate>./sm',$data, $matches);
				$xml = $matches[1];
				//for each classmates
				
				if($xml) {		
					$xmlObj = @simplexml_load_string($xml);
					if($xmlObj) {	
						if($xmlObj->teacher) {
							foreach($xmlObj->teacher as $teacher) {
								$status = intval($xmlObj->status);
								if($status == 1) {
									$fname = $teacher->firstname;
									$lname = $teacher->lastname;
									$email = $teacher->email;
									$organis_name = $teacher->organis_name;
									//TODO accept_key check ??
									$this->update_course($course['term_id'], $fname, $lname, $organis_name, $email);
								}
								break;
							}
						}
						if(intval($xmlObj->status) == -1) {
							$this->subscribed_course_declined($course['term_id'], $course['acceptKey']);
						}
					} 
				} 
			}
			
		}
		
		function get_classmate_by_url($wp_url) {
			global $wpdb;
			
			$wp_url = $wpdb->escape($wp_url);
			$table_name = $wpdb->prefix . get_class($this) . "_classmates";
			$sql = "SELECT id FROM ".$table_name." WHERE wp_url = '".$wp_url."'";
			$row = $wpdb->get_row($sql);
			if($row) {
				if($row->id > 0) {
					return true;	
				}	
				return false;
			}
			return false;
		}
		
		
		function update_course($term_id, $fname, $lname, $organis_name, $email) {
			global $wpdb;
			if($this->get_course($term_id, true)) {
				$fname = $wpdb->escape( $fname );
				$lname = $wpdb->escape( $lname );
				$email = $wpdb->escape( $email );
				$organis_name = $wpdb->escape($organis_name);
				$term_id = $wpdb->escape( $term_id );
	
				$table_name = $wpdb->prefix . get_class($this) . "_courses";
				$sql = "UPDATE ".$table_name." SET organis_name='".$organis_name."', first_name='".$fname."', last_name='".$lname."', email='".$email."' WHERE external_term_id = '".$term_id."'";
				$wpdb->query($sql);
			}
		}
		
		function get_course( $id, $by_ext_term_id = false) {
			global $wpdb;
			$id = $wpdb->escape( $id );
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			
			if(!$by_ext_term_id) {
				$sql = "SELECT id, external_term_id, course_name, wp_url, course_url, email, feed_url, acceptKey, organis_name, first_name, last_name 
					FROM $table_name 
					WHERE id = $id";
			} else {
				$sql = "SELECT id, external_term_id, course_name, wp_url, course_url, email, feed_url, acceptKey, organis_name, first_name, last_name 
					FROM $table_name 
					WHERE external_term_id = $id";	
			}
			
			if ( $row = $wpdb->get_row( $sql ) ) {
				$id = $row->id;
				$subscription = array();
				$subscription["courseId"] = $id;
				$subscription["external_term_id"] = $row->external_term_id;
				$subscription["courseName"] = $row->course_name;
				$subscription["wp_url"] = $row->wp_url;
				$subscription["course_url"] = $row->course_url;
				$subscription["teacherEmail"] = $row->email;
				$subscription["feedURL"] = $row->feed_url;
				$subscription["acceptKey"] = $row->acceptKey;
				$subscription["organis_name"] = $row->organis_name;
				$subscription["first_name"] = $row->first_name;
				$subscription["last_name"] = $row->last_name;
				return $subscription;
			}else{
				return false;
			}
		}
		
		function get_course_by_url( $url ) {
			global $wpdb;
			$url = $wpdb->escape( $url );
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			
			$sql = "SELECT id, external_term_id, course_name, wp_url, course_url, email, feed_url, acceptKey, organis_name, first_name, last_name 
					FROM $table_name 
					WHERE course_url = \"$url\" AND archived IS NULL";
			if ( $row = $wpdb->get_row( $sql ) ) {
				$id = $row->id;
				$subscription = array();
				$subscription["courseId"] = $id;
				$subscription["external_term_id"] = $row->external_term_id;
				$subscription["courseName"] = $row->course_name;
				$subscription["wp_url"] = $row->wp_url;
				$subscription["course_url"] = $row->course_url;
				$subscription["teacherEmail"] = $row->email;
				$subscription["feedURL"] = $row->feed_url;
				$subscription["acceptKey"] = $row->acceptKey;
				$subscription["organis_name"] = $row->organis_name;
				$subscription["first_name"] = $row->first_name;
				$subscription["last_name"] = $row->last_name;
				return $subscription;
			}else{
				return false;
			}
		}
		
		function get_subscribed_courses() {
			global $wpdb;
			$subscriptions = array();
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			
			$sql = "SELECT id, course_name, course_url, email, feed_url, acceptKey, external_term_id 
					FROM $table_name 
					WHERE accepted = 1 AND archived IS NULL
					ORDER BY id ASC";
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				$id = $row->id;
				$subscriptions[$id]["courseId"] = $id;
				$subscriptions[$id]["courseName"] = $row->course_name;
				$subscriptions[$id]["courseURL"] = $row->course_url;
				$subscriptions[$id]["teacherEmail"] = $row->email;
				$subscriptions[$id]["feedURL"] = $row->feed_url;
				$subscriptions[$id]["acceptKey"] = $row->acceptKey;
				$subscriptions[$id]["term_id"] = $row->external_term_id;
			}
			return $subscriptions;
		}
		
		function get_archived_courses() {
			global $wpdb;
			$subscriptions = array();
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			
			$sql = "SELECT id, course_name, course_url, email, feed_url, acceptKey, external_term_id 
					FROM $table_name 
					WHERE accepted = 1 AND archived = 1
					ORDER BY id ASC";
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				$id = $row->id;
				$subscriptions[$id]["courseId"] = $id;
				$subscriptions[$id]["courseName"] = $row->course_name;
				$subscriptions[$id]["courseURL"] = $row->course_url;
				$subscriptions[$id]["teacherEmail"] = $row->email;
				$subscriptions[$id]["feedURL"] = $row->feed_url;
				$subscriptions[$id]["acceptKey"] = $row->acceptKey;
				$subscriptions[$id]["term_id"] = $row->external_term_id;
			}
			return $subscriptions;
		}
		
		
		function get_default_course_id(){
			global $wpdb;
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			
			$sql = "SELECT id 
					FROM $table_name 
					WHERE accepted = 1 AND archived IS NULL
					LIMIT 1";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return $row->id;
			}else{
				return false;
			}
		}
		
		function get_unsubscribed_courses() {
			global $wpdb;
			$subscriptions = array();
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			
			$sql = "SELECT id, course_name, course_url, email
					FROM $table_name 
					WHERE accepted = 0 AND archived IS NULL
					ORDER BY id ASC";
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				$id = $row->id;
				$subscriptions[$id]["courseId"] = $id;
				$subscriptions[$id]["courseName"] = $row->course_name;
				$subscriptions[$id]["courseURL"] = $row->course_url;
				$subscriptions[$id]["teacherEmail"] = $row->email;
			}
			return $subscriptions;
		}
		
		function add_subscribed_course( $id, $course_name, $wp_url, $email, $acceptKey, $feed_url, $course_url, $first_name, $last_name, $organisation_name ) {
			global $wpdb;
			$id = $wpdb->escape( $id );
			$course_name = $wpdb->escape( $course_name );
			$wp_url = $wpdb->escape( $wp_url );
			$email = $wpdb->escape( $email );
			$feed_url = $wpdb->escape( $feed_url );
			$course_url = $wpdb->escape( $course_url );
			$first_name = $wpdb->escape( $first_name );
			$acceptKey = $wpdb->escape($acceptKey);
			$last_name = $wpdb->escape( $last_name );
			$organisation_name = $wpdb->escape( $organisation_name );
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			
			$sql = "SELECT course_url FROM ".$table_name." WHERE course_url='".$course_url." 'AND archived IS NULL";
			$course_data = $wpdb->get_results($sql, OBJECT);
			foreach($course_data as $course_url_db) {
				if($course_url_db->course_url === $course_url) {
					return false;
					break;
				}
			}

			
			$insert = "INSERT INTO " . $table_name .
			" (external_term_id, course_name, wp_url, feed_url, course_url, email, accepted, first_name, last_name, organis_name, acceptKey) " .
			"VALUES (" . $id . ",'" . $course_name . "','" . $wp_url . "','" . $feed_url . "','" . 
			$course_url . "','" . $email . "', 1, '" . $first_name . "', '" . $last_name . "', '" . $organisation_name . "', '".$acceptKey."')";

			$result = $wpdb->query( $insert );
			return $result;
		}
		
		function add_classmate( $course_id, $first_name, $last_name, $email, $wp_url ){
			global $wpdb;
			$course_id = (int)$course_id;
			$first_name = $wpdb->escape( $first_name );
			$last_name = $wpdb->escape( $last_name );
			$email = $wpdb->escape( $email );
			$wp_url = $wpdb->escape( $wp_url );
			$table_name = $wpdb->prefix . get_class($this) . "_classmates";
			$insert = "INSERT INTO " . $table_name .
			" ( course_id, first_name, last_name, email, wp_url ) " .
			"VALUES (" . $course_id . ", '" . $first_name . "', '" . $last_name . "', '" . $email . "', '" . $wp_url . "')";

			$wpdb->query( $insert );
		}
		
		function add_unsubscribed_course( $id, $course_name, $wp_url, $email , $key, $feed_url, $course_url, $first_name, $last_name, $organisation_name ) {
			global $wpdb;
			$id = $wpdb->escape( $id );
			$course_name = $wpdb->escape( $course_name );
			$wp_url = $wpdb->escape( $wp_url );
			$email = $wpdb->escape( $email );
			$feed_url = $wpdb->escape( $feed_url );
			$course_url = $wpdb->escape( $course_url );
			$first_name = $wpdb->escape( $first_name );
			$last_name = $wpdb->escape( $last_name );
			$organisation_name = $wpdb->escape( $organisation_name );
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			
			$sql = "SELECT course_url FROM ".$table_name." WHERE course_url='".$course_url."' AND archived != 1";
			$course_data = $wpdb->get_results($sql, OBJECT);
			foreach($course_data as $course_url_db) {
				if($course_url_db->course_url === $course_url) {
					return false;
					break;
				}
			}
			
			$insert = "INSERT INTO " . $table_name .
			" (external_term_id, course_name, wp_url, feed_url, course_url, email, accepted, acceptKey, first_name, last_name, organis_name) " .
            "VALUES (" . $id . ",'" . $course_name . "','" . $wp_url . "','" . $feed_url . "','" . 
			$course_url . "','" . $email . "',0,'" . $key . "', '" . $first_name . "', '" . $last_name . "', '" . $organisation_name . "')";

			$result = $wpdb->query( $insert );
			return $result;
		}
		
		function add_assignment( $course_id, $post_id, $start, $end, $title, $post_url, $trackback_url  ){
			global $wpdb;
			$course_id = $wpdb->escape( $course_id );
			$post_id = $wpdb->escape( $post_id );
			$start = $wpdb->escape( $start );
			$end = $wpdb->escape( $end );
			$title = $wpdb->escape( $title );
			$post_url = $wpdb->escape( $post_url );
			$trackback_url = $wpdb->escape( $trackback_url );
			$table_name = $wpdb->prefix . get_class($this) . "_assignments";
			
			if ( !$wpdb->get_var("SELECT id FROM $table_name WHERE course_id='$course_id' AND post_id='$post_id'")) {
				$wpdb->query("INSERT INTO $table_name (course_id, post_id, start, end, title, post_url, feedback_url, grade) 
							  VALUES ($course_id, $post_id, '$start', '$end', '$title', '$post_url', '$trackback_url', '')");
			}//else this assignment is already in students database
			
		}
		
		function get_assignments( $course_id ) {
			global $wpdb;
			$course_id = $wpdb->escape( $course_id );
			$assignments = array();
			$table_name = $wpdb->prefix . get_class($this) . "_assignments";
			
			$sql = "SELECT id, answer_post_id, start, end, title, post_url, feedback_url, grade
					FROM $table_name 
					WHERE course_id = $course_id
					ORDER BY id ASC";
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				$id = $row->id;
				$assignments[$id]["id"] = $id;
				$assignments[$id]["answer_post_id"] = $row->answer_post_id;
				$assignments[$id]["start"] = date( 'j.n.Y', strtotime( $row->start ) );
				$assignments[$id]["end"] = date( 'j.n.Y', strtotime( $row->end ) );
				$assignments[$id]["title"] = $row->title;
				$assignments[$id]["post_url"] = $row->post_url;
				$assignments[$id]["feedback_url"] = $row->feedback_url;
				$assignments[$id]["grade"] = $row->grade;
			}
			return $assignments;
		}
		
		function get_assignment( $id, $by_answer_post_id = false) {
			global $wpdb;
			$id = $wpdb->escape( $id );
			$assignment = array();
			$table_name = $wpdb->prefix . get_class($this) . "_assignments";
			
			$column = "id";
			if($by_answer_post_id) {
				$column = "answer_post_id";	
			}
			
			$sql = "SELECT id, course_id, answer_post_id, start, end, title, post_url, feedback_url, grade
					FROM $table_name 
					WHERE $column = $id";
			if ( $row = $wpdb->get_row( $sql ) ) {
				$id = $row->id;
				$assignment["id"] = $id;
				$assignment["course_id"] = $row->course_id;
				$assignment["answer_post_id"] = $row->answer_post_id;
				$assignment["start"] = date( 'j.n.Y', strtotime( $row->start ) );
				$assignment["end"] = date( 'j.n.Y', strtotime( $row->end ) );
				$assignment["title"] = $row->title;
				$assignment["post_url"] = $row->post_url;
				$assignment["feedback_url"] = $row->feedback_url;
				$assignment["grade"] = $row->grade;
				return $assignment;
			}else{
				return false;
			}
		}
		
		function remove_assignment($post_id) {
			global $wpdb;
			$id = $wpdb->escape( $post_id );
			$table_name = $wpdb->prefix . get_class($this) . "_assignments";
			
			$sql = "DELETE
					FROM $table_name 
					WHERE answer_post_id = $id";	
			$wpdb->query($sql);
		}
		
		function get_classmates( $course_id = false) {
			global $wpdb;
			$course_id = (int)$course_id;
			$students = array();
			$table_name = $wpdb->prefix . get_class($this) . "_classmates";
			
			$sql = "SELECT id, first_name, last_name, email, wp_url FROM $table_name ";
			if($course_id) {
				$sql .= "WHERE course_id = $course_id";
			}
			$sql .= " ORDER BY last_name ASC";
			
			$rows = $wpdb->get_results( $sql, OBJECT );
			foreach ($rows as $row){
				array_push( $students, array( 'id' => $row->id, 'first_name' => $row->first_name, 
					'last_name' => $row->last_name, 'email' => $row->email, 'wp_url' => $row->wp_url ) );
			}
			return $students;
		}
		
		function get_uncomplete_assignments_count() {
			global $wpdb;
			$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";
			$sql = "SELECT count(*) as total
					FROM $table_name 
					WHERE answer_post_id IS NULL";
			if ( $row = $wpdb->get_row( $sql ) ) {
				$total = (int)$row->total;
				return $total;
			}else{
				return 0;
			}
		}
		
		function subscribed_course_accepted( $id, $key ) {
			global $wpdb;
			$id = $wpdb->escape( $id );
			$key = $wpdb->escape( $key );
			$table_name = $wpdb->prefix . get_class( $this ) . "_courses";
			if ($wpdb->get_var("SELECT id FROM $table_name WHERE acceptKey='$key' AND external_term_id='$id'")) {
				$wpdb->query("UPDATE $table_name SET accepted='1' WHERE external_term_id='$id' AND acceptKey='$key'");
				return true;
			} else {
				return false;
			}
		}
		
		function subscribed_course_declined( $id, $key ) {
			global $wpdb;
			$id = $wpdb->escape( $id );
			$key = $wpdb->escape( $key );
			$table_name = $wpdb->prefix . get_class( $this ) . "_courses";
			if ($course_id = $wpdb->get_var("SELECT id FROM $table_name WHERE acceptKey='$key' AND external_term_id='$id'")) {
				$accepted = $wpdb->get_var("SELECT accepted FROM $table_name WHERE acceptKey='$key' AND external_term_id='$id'");
				if($accepted == 1) {
					$wpdb->query("UPDATE $table_name SET archived = 1 WHERE external_term_id='$id' AND acceptKey='$key'");
				} else {
					$wpdb->query("DELETE FROM $table_name WHERE external_term_id='$id' AND acceptKey='$key'");
				}
				//assignments clean out
				$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";
				if ($wpdb->get_var("SELECT id FROM $table_name WHERE course_id='$course_id'")) {
				    $wpdb->query("DELETE FROM $table_name WHERE course_id='$course_id' AND answer_post_id IS NULL");
				    $any_assignments = $wpdb->get_var("SELECT id FROM $table_name WHERE course_id='$course_id AND answer_post_id IS NOT NULL'");
				    if(!$any_assignments) {
				    	//Delete course data, no need for archiving, when theres no assignments
				    	$wpdb->query("DELETE FROM $table_name WHERE external_term_id='$id' AND acceptKey='$key'");
				    }
				}
				//clean out classmates
				
				$table_name = $wpdb->prefix . get_class( $this ) . "_classmates";
				if ($wpdb->get_var("SELECT id FROM $table_name WHERE course_id='$course_id'")) {		
					 $wpdb->query("DELETE FROM $table_name WHERE course_id='$course_id'");
				}
				return true;
			} else {
				return false;
			}
		}
		
		
		function remove_all_course_classmates( $course_id ) {
			global $wpdb;
			$course_id = (int)$course_id;
			$table_name = $wpdb->prefix . get_class( $this ) . "_classmates";
			$wpdb->query("DELETE FROM $table_name WHERE course_id=$course_id");
		}
		
		function add_assesment( $post_id, $assignment_id ){
			global $wpdb;
			
			$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";
			
			if ($wpdb->get_var("SELECT id FROM $table_name WHERE id=$assignment_id")) {
				$wpdb->query("UPDATE $table_name SET answer_post_id=$post_id WHERE id=$assignment_id");
				do_trackbacks($post_id);
				return true;
			} else {
				return false;
			}
		}
		
		function remove_all_assignments($course_id) {
			global $wpdb;
		    
			$course_id = $wpdb->escape( $course_id );
			$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";
			$delete = "DELETE FROM " . $table_name . " WHERE answer_post_id IS NULL AND course_id = ".$course_id;
			$wpdb->query( $delete);
		}
		
		function set_grade( $comment_id, $grade ) {
			global $wpdb;
			$comment_id = $wpdb->escape( $comment_id );
			if( $comment_id < 0 ){return false;}
			$grade = $wpdb->escape( $grade );
			
			$comment = get_comment( $comment_id );
			$answer_post_id = $comment->comment_post_ID;
			$table_name = $wpdb->prefix . get_class( $this ) . "_assignments";
			if ($wpdb->get_var("SELECT id FROM $table_name WHERE answer_post_id=$answer_post_id")) {
				$wpdb->query("UPDATE $table_name SET grade='$grade' WHERE answer_post_id=$answer_post_id");
				return true;
			} else {
				return false;
			}
		}
		
		function check_communication_key_validity( $term_id, $key )	{
			global $wpdb;
			$term_id = (int)$term_id;
			$key = $wpdb->escape( $key );
			$table_name = $wpdb->prefix . get_class( $this ) . "_courses";
			$sql = "SELECT id FROM $table_name WHERE acceptKey='$key' AND external_term_id='$term_id'";
			if ( $row = $wpdb->get_row( $sql ) ) {
				return (int)$row->id;
			} else {
				return false;
			}
		}
		
		function subscribe_course( $url, $message, $key ) {
			global $wpdb;
			global $current_user;
			get_currentuserinfo();
			
			if(!empty($current_user->first_name) && !empty($current_user -> last_name)) {
				
				$response = "";
				
				//connect to $url and check if LePressTeacher plugin exist and get plugin version
				$ch = curl_init( add_query_arg('action', 'LePressTeacherVersionCheck',  $url ) );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				$data = curl_exec($ch);
				curl_close($ch);
				if ( preg_match('/<LePressResponse>(.*?)<\/LePressResponse>./sm',$data, $matches) ) {
					$version = $matches[1];
					//need to check version?
				
					//try to subscribe to given course
					$acceptKey = $this->create_key(6);
					$params = array('action' => 'LePressTeacherSubscribe',
									'siteurl' => urlencode(get_bloginfo('siteurl')),
									'email' => urlencode($current_user->user_email),
									'lname' => urlencode($current_user->user_lastname),
									'fname' => urlencode($current_user->user_firstname),
									'acceptKey' => urlencode($acceptKey),
									'inviteKey' => urlencode($key));
					$subscriptionUrl = add_query_arg( $params, $url );
					
					$ch = curl_init( $subscriptionUrl );
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
					$data = curl_exec($ch);
					curl_close($ch);
					preg_match('/<LePressResponse>(.*?)<\/LePressResponse>./sm',$data, $matches);
					$response = ( isset( $matches[1] ) ) ? $matches[1] : 'No response';
					preg_match('/<Message>(.*?)<\/Message>/',$response, $matches);
					$message = ( isset( $matches[1] ) ) ? $matches[1] : 'No message';
					
					switch ( $message ) {
						case 'Subscription request made':
							//add unsubscribed course
							preg_match('/<CourseName>(.*?)<\/CourseName>./sm',$response, $matches);
							$courseName = $matches[1];
							preg_match('/<Email>(.*?)<\/Email>/',$response, $matches);
							$email = $matches[1];
							preg_match('/<Course_id>(\d+)<\/Course_id>/',$response, $matches);
							$id = $matches[1];
							preg_match('/<Feed_url>(.*?)<\/Feed_url>/',$response, $matches);
							$feed = $matches[1];
							preg_match('/<Course_url>(.*?)<\/Course_url>/',$response, $matches);
							$course_url = $matches[1];
							preg_match('/<F_name>(.*?)<\/F_name>/',$response, $matches);
							$first_name = $matches[1];
							preg_match('/<L_name>(.*?)<\/L_name>/',$response, $matches);
							$last_name = $matches[1];
							preg_match('/<O_name>(.*?)<\/O_name>/',$response, $matches);
							$organisation_name = $matches[1];
							$response_ = $this->add_unsubscribed_course( $id, $courseName, $url, $email, $acceptKey, $feed, $course_url, $first_name, $last_name, $organisation_name );
							if( !$response_ ){
								$message .= ' but error on adding query to local database.' . mysql_error();
							}
							break;
						case 'Added to Course':
							//add subscribed course
							preg_match('/<CourseName>(.*?)<\/CourseName>./sm',$response, $matches);
							$courseName = $matches[1];
							preg_match('/<Email>(.*?)<\/Email>./sm',$response, $matches);
							$email = $matches[1];
							preg_match('/<Course_id>(\d+)<\/Course_id>/',$response, $matches);
							$id = $matches[1];
							preg_match('/<Feed_url>(.*?)<\/Feed_url>/',$response, $matches);
							$feed = $matches[1];
							preg_match('/<Course_url>(.*?)<\/Course_url>/',$response, $matches);
							$course_url = $matches[1];
							preg_match('/<F_name>(.*?)<\/F_name>/',$response, $matches);
							$first_name = $matches[1];
							preg_match('/<L_name>(.*?)<\/L_name>/',$response, $matches);
							$last_name = $matches[1];
							preg_match('/<O_name>(.*?)<\/O_name>/',$response, $matches);
							$organisation_name = $matches[1];
							$response_ = $this->add_subscribed_course( $id, $courseName, $url, $email, $acceptKey, $feed, $course_url, $first_name, $last_name, $organisation_name );
							if( !$response_ ){
								$message .= ' but error on adding query to local database.' . mysql_error();
							}
							break;
						
					}
				}
			} else {
				 $message = __("You cannot subscribe to course before filling out your profile. Firstname and/or lastname is not set.").' <a href="profile.php" style="text-decoration: none;" class="row-title" title="'.__("Edit profile").'">'.__("Edit profile").'</a>';
					}
			
			return $message;
		}
		
		function unsubscribe_course( $subscription_id, $ext_term_id = false ) {
			global $wpdb;
			global $current_user;
			get_currentuserinfo();
			$response = "";
			
			//connect to $url and check if LePressTeacher plugin exist and get plugin version
			$subscription = $this->get_course( $subscription_id, $ext_term_id);
			
			if( $subscription != false ){
				$id = $subscription['external_term_id'];
				$key = $subscription['acceptKey'];
				if(!$ext_term_id) {
					$params = array('action' => 'LePressTeacherUnsubscribe',
									'siteurl' => urlencode(get_bloginfo('siteurl')),
									'id' => urlencode($id),
									'key' => urlencode($key));
					$url = add_query_arg( $params, $subscription['wp_url']);
					$ch = curl_init( $url );
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					$data = curl_exec($ch);
					curl_close($ch);
				}
				preg_match('/<LePressResponse>(.*?)<\/LePressResponse>./sm',$data, $matches);
				preg_match('/<Code>(.*?)<\/Code>./sm',$matches[1], $status_code);
				
				preg_match('/<LePressResponse>(.*?)<\/LePressResponse>./sm',$data, $matches);
				$response = ( isset( $matches[1] ) ) ? $matches[1] : 'No response';
				preg_match('/<Code>(.*?)<\/Code>/',$response, $matches);
				$status_code = ( isset( $matches[1] ) ) ? $matches[1] : 'No code';
				
		
				if($status_code == 1) {
					$this->subscribed_course_declined( $id, $key); //useing existing function to remove subscription from database
					return true;
				}
			}
			return false;
		}
		
		function download_assignments(){
			
			//check rss feeds of subscribed courses
			foreach( $this->get_subscribed_courses() as $id /*for each subscribed course*/ ) {
								
				$url =  str_replace('&amp;', '&', $id['feedURL'] );
				$ch = curl_init( $url );
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				$data = curl_exec($ch);
				$xml = false;
				try {
					$xml = new SimpleXmlElement( $data, LIBXML_NOERROR);
				} catch (Exception $e) {
					return false;
				}
				$posts = array();		
				$received_post_ids = array();
				
				if($xml) {
					foreach ($xml->channel->item as $item)
					{
							$post = array();
							$post['title'] = $item->title;
							$post['link'] = $item->link;
							if( $item->assignment ){
								$start = strtotime( $item->assignment->start );
								$start_d = date('d', $start);
								$start_m = date('m', $start);
								$start_y = date('Y', $start);
								$start_db_formated = $start_y . '-' . $start_m . '-' . $start_d;
								$post['start'] = $start_db_formated;
								
								$end = strtotime( $item->assignment->end );
								$end_d = date('d', $end);
								$end_m = date('m', $end);
								$end_y = date('Y', $end);
								$end_db_formated = $end_y . '-' . $end_m . '-' . $end_d;
								$post['end'] = $end_db_formated;
								$post['trackback'] = $item->trackback;
								$post['id'] = $item->id;
								
								array_push($received_post_ids, $post['id']);
								
								if(!$this->get_assignment($post['id'], $by_answer_post_id = true)) {
									$this->add_assignment( $id['courseId'], $post['id'], $start_db_formated, 
												$end_db_formated, $post['title'], $post['link'], $post['trackback'] );
								} else {
								    //After that run trackbacks
									do_trackbacks($post['id']);	
								}
								 //Update assignments date
								 $this->updateAssignment($post['id'], $post['start'], $post['end'], $post['trackback'], $post['title']);
							}
					}
				}
				
				//clean out non existent assignments, which have no answer
				if(count($received_post_ids) > 0) {
					global $wpdb;
					$ids = $wpdb->escape( implode(',', $received_post_ids) );
					$course_id = $wpdb->escape($id['courseId']);
					$table_name = $wpdb->prefix . get_class($this) . "_assignments";
					$clean = "DELETE FROM ".$table_name." WHERE post_id NOT IN (".$ids.") AND answer_post_id IS NULL AND course_id=".$course_id."";
					$wpdb->query($clean);
				} else {
					//delete old assignments data, which don't have answer yet.
					// empty feed, so no assignments should be kept
					$this->remove_all_assignments($id['courseId']);	
				}
				
			}
		}
		
		function updateAssignment($post_id, $start_date, $end_date, $trackback_url, $title) {
		       global $wpdb;
		       $table_name = $wpdb->prefix . get_class($this) . "_assignments";
		       $sql = 'UPDATE '.$table_name.' SET start="'.$start_date.'", end="'.$end_date.'", feedback_url="'.$trackback_url.'", title="'.$title.'" WHERE post_id='.$post_id.'';
		       $wpdb->query($sql);
		} 
		
		function getAssignments() { //this is for ajax request
			global $LePressStudent_plugin, $wpdb;
			if( isset( $_REQUEST['id'] ) ){
				$id = $wpdb->escape( $_REQUEST['id'] );
				echo '<select id="getAssignmentsData">';
				foreach ( $LePressStudent_plugin->get_assignments( $id ) as $assignment ) {
					echo '<option value=' . $assignment['id'] . ' url="' . $assignment['post_url'] . '" >';
					echo $assignment['title'];
					echo '</option>';
				}
				echo "<select>";
			}
		}
		
	function post_deleted_handler($post_id) {
		if($assignment = $this->get_assignment($post_id, $by_answer_post_id = true)) {
			$this->remove_assignment($post_id);
		}
	}
		
		
		function wp_head() {
			
			wp_enqueue_script('LePressStudentWidgetScript');	//load javascript for widget
			
			if ( isset( $_REQUEST['action'] ) ) {
				switch ( $_REQUEST['action'] ) {
					case "LePressStudentVersionCheck":
							echo "<LePressResponse>" . $this->version . "</LePressResponse>";
						break;
					case "LePressStudent_subscription_accept":
							if ( isset( $_REQUEST['id'] ) && isset( $_REQUEST['key'] ) ) {
								$response = $this->subscribed_course_accepted( $_REQUEST['id'], $_REQUEST['key'] );
								echo "<LePressResponse><Message>" . "Command recieved" . "</Message><Code>" . $response . "</Code></LePressResponse>";
							}
						break;
					case "LePressStudent_subscription_decline":
							if ( isset( $_REQUEST['id'] ) && isset( $_REQUEST['key'] ) ) {
								$response = $this->subscribed_course_declined( $_REQUEST['id'], $_REQUEST['key'] );
								echo "<LePressResponse><Message>" . "Command recieved" . "</Message><Code>" . $response . "</Code></LePressResponse>";
							}
						break;
					case 'LePressStudent_getPost':
						if(isSet($_REQUEST['p'])) {
							$post = get_post($_REQUEST['p']);
						} else {
							global $post;
						}
						
						if($post) {
							echo "<LePressResponse><post><title>".htmlspecialchars($post->post_title)."</title><content>".htmlspecialchars($post->post_content)."</content><code>1</code></post></LePressResponse>";
						} else {
							echo "<LePressResponse><post><code>-1</code></post></LePressResponse>";
						}
						break;
				}
				
			}
		}
		
		
		function trackback_received( $comment_id ) {
			$comment = get_comment( $comment_id, ARRAY_A );
			if ( $comment['comment_type'] == 'trackback' ) {
				if( isset( $_REQUEST['grade'] ) ){
					$grade = $_REQUEST['grade'];
					$this->set_grade( $comment_id, $grade );
					$comment['comment_author_url'] = '';
					wp_update_comment( $comment );
				}
			}
		}
		
		function awaiting_mod( $awaiting_what ){
			$count = 0;
			if( $awaiting_what == "subscribed courses" ){
				$count = count( $this->get_subscribed_courses() );
			}elseif( $awaiting_what == "new assignments" ){
				$count = $this->get_uncomplete_assignments_count();
			}
			
			$text = '<span id="awaiting-mod" class="count-' . $count . '">
								<span class="pending-count">' . $count . '</span>
							</span>';
			
			return $text;
		}
		
		function admin_head() {
			
			if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == "manage_assignments_page_stnt" ) {
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
		}
		
		function get_widget_calendar( $course_id, $month, $year, $widget_id ){
			$assignments = $this->get_assignments( $course_id );
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
			$return_str .= '<tr><td colspan="7">' . __( $month_name ) . ', ' . $year . '<td></tr>';
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
						$return_str .= '<div class="calendar_event" id="widget-' . $widget_id . '-calendar-' . ($i - $startday + 1) . '"  style="text-align:left; position:absolute;display:none;background:#ffffff; border:1px solid;">';
						foreach( $this_month_assignments[($i - $startday + 1)] as $assignment ){
							
							if(empty($assignment['answer_post_id'])) {
							  $icon = 'pencilicon.png';
							  $url = 'wp-admin/admin.php?page=manage_assignments_page_stnt&id='.$assignment['id'].'&action=write_submission';
							} else {
								$icon = 'assready.png';
								$url = $assignment['post_url'].'#comments';
							}
							
							$return_str .= '<a  style="margin: 3px 3px 2px 2px;" href="' . $url . 
								'"><img src="' . plugin_dir_url( __FILE__ ) . 
								'images/icons/'.$icon.'" alt="assessment" width="15" height="15" /> ' .
								$assignment['title'] . '</a><br />';
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
				$return_str .= '<td align="center" colspan="3"><a href="#" onclick="get_calendar_stnt(\''.$widget_id.'\',' . $course_id. 
					',' . $prev_month. ',' . $prev_year . ',\''. plugin_dir_url( __FILE__ ).'ajax.php\');return false;" >' . __( 'prev' ) . '</a></td>';
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
				$return_str .= '<td align="center" colspan="3"><a href="#" onclick="get_calendar_stnt(\''.$widget_id.'\',' . $course_id. 
					',' . $next_month. ',' . $next_year . ',\''.plugin_dir_url( __FILE__ ).'ajax.php\');return false;" >' . __( 'next' ) . '</a></td>';
			}else{
				$return_str .= '<td></td>'; 
			}
			$return_str .= '</tr></table>';
			return $return_str;
		}
		
		function get_widget_body( $widget_id, $instance) {
			global $wp_query;
			
			//$course_id = 1;
			if ( isset( $_REQUEST['course_id'] ) ){
				$course_id = $_REQUEST['course_id'];
			}else{
				$course_id = $this->get_default_course_id();
			}
			if( $course_id == false ){return __('No active courses');}
			$return_str = '';
			
			//dropdown box with list of courses
			$return_str .= '<a href="';
			if( isset( $course_id ) ){
				$course = $this->get_course( $course_id );
				$return_str .= $course['course_url'];
			}
			$return_str .= '" ><img src="' . plugin_dir_url( __FILE__ ) . 
					'/images/icons/bookicon.png" alt="assessment" width="20" height="20" hspace="3" vspace="3" align="left" /></a>';
			$return_str .= '<select name="course_id" id="widget-'. $widget_id . '-course_id' 
					. '" onChange="change_course_stnt(\'' . $widget_id . '\', \'' . plugin_dir_url( __FILE__ ).'ajax.php?collapse_students='.$instance['collapse_students'].'\') ">';
			foreach( $this->get_subscribed_courses() as $course ) {
				$return_str .= '<option value=' . $course['courseId'];
				if( isset( $course_id ) && $course_id == $course['courseId'] ){
					$return_str .= ' selected="selected"';
				}
				$return_str .= '>' . $course['courseName'] . '</option>';
			}
			$return_str .= '</select>';
			
			//calendar
			$return_str .= '<div id="widget-'. $widget_id . '-calendar" >';
			if( isset( $course_id ) ) {
				$return_str .= $this->get_widget_calendar( $course_id, date('n'), date('Y'), $widget_id );
			}
			$return_str .= '</div>';
			
			//course teacher
			$return_str .= '<h2>' . '<img src="' . plugin_dir_url( __FILE__ ) . 
					'images/icons/teachericon.png" width="20" height="20" hspace="3" align="absmiddle" />' 
					. __( 'Teacher' ) . '</h2>';
			if( isset( $course_id ) ) {
				$teacher = $this->get_course( $course_id );
				$return_str .= '<div class="vcard">';
				$return_str .= '<a class="email" href="mailto:' . str_replace('@', '[at]', $teacher['teacherEmail']) . '" >';
				$return_str .= '<img src="' . plugin_dir_url( __FILE__ ) . 
					'images/icons/emailicon.png" alt="email" width="20" height="20" hspace="3" align="absmiddle" /></a>';
				$return_str .= ' <a class="fn url" href="' . $teacher['wp_url'] . '" title="' .
						__( 'Personal blog' ) . '" >' . $teacher['first_name'] 
						. ' ' . $teacher['last_name'] . '</a>';
				$return_str .= '<abbr class="org" title="' . $teacher['organis_name'] . '"></abbr>';
				$return_str .= '</div>';
			}
			
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
				foreach( $this->get_classmates( $course_id ) as $student ) {
					$return_str .= '<div class="vcard" id="lepress_workgroup" style="display:'.$collapsed.'">';
					$return_str .= '<a class="email" href="mailto:' . str_replace('@', '[at]', $student['email']) . '" >';
					$return_str .= '<img src="' . plugin_dir_url( __FILE__ ) . 
						'images/icons/emailicon.png" alt="email" width="20" height="20" hspace="3" align="absmiddle" /></a>';
					$return_str .= ' <a class="fn url" href="' . $student['wp_url'] . '" title="' .
							__( 'Personal blog' ) . '" >' . $student['last_name'] 
							. ' ' . $student['first_name'] . '</a>';
					$return_str .= '</div>';
				}
			}
			//course assignements
			
			$return_str .= '<dl class="vcalendar"><h2>' . '<img src="' . plugin_dir_url( __FILE__ ) . 
				'images/icons/craduateicon.png" width="20" height="20" hspace="3" align="absmiddle" />' 
				. __( 'Assignments' ) . '</h2>';
			if ( isset( $course_id ) ){
				foreach( $this->get_assignments( $course_id ) as $assignment ) {
					
					if(empty($assignment['answer_post_id'])) {
					  $icon = 'pencilicon.png';
					  $url = get_bloginfo('siteurl').'/wp-admin/admin.php?page=manage_assignments_page_stnt&id='.$assignment['id'].'&action=write_submission';
					  $title = 'Click here to implement you submission for this assignment';
					} else {
						$icon = 'assready.png';
						$url = $assignment['post_url'].'#comments';
						$title = 'This assignment is completed';
					}
					$return_str .= '<div class="vevent"><dt><a class="url" title="'.$title.'" href="' . $url . 
						'"><img src="' . plugin_dir_url( __FILE__ ) . 
						'images/icons/'.$icon.'" alt="assessment" width="15" height="15" /> ' .
						$assignment['end'] . ' <span class="summary">' . $assignment['title'] . '</span></a>';
					$return_str .= '<abbr class="dtstart" title="' . $assignment['start'] . '"></abbr>';
					$return_str .= '<abbr class="dtend" title="' . $assignment['end'] . '"></abbr></dt>';
					$return_str .= '</div>';
				}
			}
			$return_str .= '</dl>';	//end of assignments
			
			//graduation link
			$return_str .= '<h2>' . __( 'Graduation' ) . '</h2>';
			if( isset( $course_id ) ) {
				$return_str .= '<div>';
				$return_str .= '<a  href="' . get_bloginfo( 'siteurl' ) . '/wp-admin/admin.php?page=LePressStudent/main.php' . '" >';
				$return_str .= '<img src="' . plugin_dir_url( __FILE__ ) . 
					'images/icons/craduateicon.png" alt="graduation" width="20" height="20" hspace="3" align="absmiddle" />';
				$return_str .= __( 'My Progress' ) .'</a>';
				$return_str .= '</div>';
			}
			
			return $return_str;
		}
		
		function manage_courses_menu(){
			add_menu_page( 'LePress Student', 'LePress Student', 8, __FILE__, 
				array( &$this, 'manage_subscriptions_page' ) );
			add_submenu_page( __FILE__, 'Subscriptions', 'Subscriptions' . $this->awaiting_mod( 'subscribed courses' ), 8, __FILE__, 
				array( &$this, 'manage_subscriptions_page' ) );
			add_submenu_page( __FILE__, 'Assignments', 'Assignments' . $this->awaiting_mod( 'new assignments' ), 8, 'manage_assignments_page_stnt', 
				array( &$this, 'manage_assignments_page' ) );
		}
		
		function manage_subscriptions_page() {
			include( 'manage_subscriptions_page.php' );
		}
		
		function manage_assignments_page() {
			global $wp_query;
			if( isset( $_REQUEST['action'] ) && ( $_REQUEST['action'] == 'write_submission' || 
					$_REQUEST['action'] == 'postAssesment' ) ){
				include( 'write_assignment_page.php' );
			}else{
				include( 'manage_assignments_page.php' );
			}
		}
		
		function write_assesment_page() {
			include( 'write_assignment_page.php' );
		}
	
		function install () {
			global $wpdb;
			$table_name = $wpdb->prefix . get_class($this) . "_courses";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  external_term_id bigint(20) NOT NULL,
					  course_name VARCHAR(200),
					  organis_name VARCHAR(200),
					  wp_url VARCHAR(2000),
					  feed_url VARCHAR(2000),
					  course_url VARCHAR(2000),
					  email VARCHAR(200),
					  acceptKey VARCHAR(6),
					  accepted bigint(1) NOT NULL,
					  first_name VARCHAR(200),
					  last_name VARCHAR(200),
					  archived TINYINT, 
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			
			$table_name = $wpdb->prefix . get_class($this) . "_classmates";
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  course_id bigint(20) NOT NULL,
					  first_name VARCHAR(200),
					  last_name VARCHAR(200),
					  email VARCHAR(200),
					  wp_url VARCHAR(2000),
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			
			$table_name = $wpdb->prefix . get_class($this) . '_assignments';
			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
				$sql = "CREATE TABLE " . $table_name . " (
					  id bigint(20) NOT NULL AUTO_INCREMENT,
					  course_id bigint(20) NOT NULL,
					  answer_post_id bigint(20),
					  post_id bigint(20) NOT NULL,
					  start date NOT NULL,
					  end date,
					  title VARCHAR(2000)NOT NULL,
					  post_url VARCHAR(2000)NOT NULL,
					  feedback_url VARCHAR(2000)NOT NULL,
					  grade VARCHAR(2),
					  UNIQUE KEY id (id)
					) DEFAULT CHARSET=utf8;";

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
		}
		
		function uninstall() {
			global $wpdb;
			$list_of_tables_to_drop = Array( '_courses', '_assignments', '_classmates' );
			$courses = $this->get_subscribed_courses();
			
			//send out unsubscription to subscribed courses (teachers blogs)
			foreach($courses as $course) {
				$this->unsubscribe_course( $course['courseId']);
			}
						
			foreach ( $list_of_tables_to_drop as $table_name ) {
				$full_table_name = $wpdb->prefix . get_class($this) . $table_name;
				$sql = "DROP TABLE " . $full_table_name;
				$wpdb->query( $sql );
			}
		}
		
		
	}
}

class LePressStudent_Widget extends WP_Widget {
	
	function LePressStudent_Widget() {
		// outputs the content of the widget
		$widget_ops = array('classname' => 'LePressStudent_Widget', 'description' => __( "Widget for LePress Student plugin.") );
		$this->WP_Widget('lepressStudent_widget', __('LePress Student'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		global $LePressStudent_plugin, $wp_query;
		// widget actual processes    
		extract($args);
		$title = apply_filters( 'widget_title', __('LePress Student') );

		# Before the widget
		echo $before_widget;

		# The title
		if ( $title )
		echo $before_title . $title . $after_title;

		# Make the widget body
		echo '<div id='. $this->get_field_id('body') .'>';
		echo $LePressStudent_plugin->get_widget_body( $this->id , $instance);
		echo '</div>';
		# After the widget
		echo $after_widget;

	}

	function form($instance) {
		// outputs the options form on admin
		//Defaults
      $instance = wp_parse_args( (array) $instance, array( 'collapse_students' => 1) );

      # Output the options
      echo '<p style="text-align:right;">'.
				'<label>' . __('Collapse list of the students:') .
				'<input id="' . $this->get_field_id('collapse_students') . '" name="' .
				$this->get_field_name('collapse_students') . '" type="hidden" value="0"/> '.
				' <input id="' . $this->get_field_id('collapse_students') . '" name="' .
				$this->get_field_name('collapse_students') . '" type="checkbox" value="1" ';
				if( $instance['collapse_students'] == 1 ){
					echo 'checked ';
				}
		echo '/>' .'</p>';
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
		return $new_instance;
	}
}

if (class_exists("LePressStudent")) { 
	global $LePressStudent_plugin;
	$version = explode('.',PHP_VERSION);
	if( (int)$version[0] >= 5 ){
		$LePressStudent_plugin = new LePressStudent();
	}else{
		add_action( 'admin_menu', 'lepress_student_before_install_error_menu', 1);
	}
} 

function lepress_student_before_install_error_menu() {
	add_menu_page( 'LePress Student', 'LePress', 8, __FILE__, 'lepress_student_before_install_error_page' );
}

function lepress_student_before_install_error_page() {
	$version = explode('.',PHP_VERSION);
	echo 'You are using PHP version ' . $version[0] . '. Unfortunatelly only PHP version 5+ is supported';
}

//Actions and Filters    
if (isset($LePressStudent_plugin)) { 
	
	//Actions 
	register_activation_hook( __FILE__, array(&$LePressStudent_plugin, 'install'));
	register_deactivation_hook( __FILE__, array(&$LePressStudent_plugin, 'uninstall'));
	
	add_action( 'admin_menu', array(&$LePressStudent_plugin, 'manage_courses_menu'), 1);
	add_action( 'wp_head', array(&$LePressStudent_plugin, 'wp_head'), 1);
	add_action( 'admin_head', array( &$LePressStudent_plugin, 'admin_head' ), 1 );
	add_action( 'trackback_post', array(&$LePressStudent_plugin, 'trackback_received'), 1);
	
	//profile update
	add_action('profile_update', array(&$LePressStudent_plugin, 'profile_updated'), 1);
	
	add_action('wp_ajax_getAssignments', array(&$LePressStudent_plugin, 'getAssignments'), 1);
	
	//init widget
	add_action('widgets_init', create_function('', 'return register_widget("LePressStudent_Widget");'), 1);
	wp_register_script('LePressStudentWidgetScript', plugin_dir_url( __FILE__ ) . '/js/widget.js', array( 'jquery' ) );
	
	add_filter('manage_posts_columns', array( &$LePressStudent_plugin, 'LePress_columns'));
	add_action('manage_posts_custom_column', array( &$LePressStudent_plugin, 'custom_column_handler'), 10, 2);
	
	//Post deletion via WP menu
	 add_action('deleted_post', array( &$LePressStudent_plugin, 'post_deleted_handler'), 1, 1);
	 
	 //Get data
	$LePressStudent_plugin->download_assignments();
	$LePressStudent_plugin->download_courses();
	$LePressStudent_plugin->download_classmates();
}

?>