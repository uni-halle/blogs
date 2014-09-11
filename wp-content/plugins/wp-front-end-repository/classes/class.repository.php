<?php
class nmRepository{

	/*
	 * variables
	 */
	
	var $tblName = 'nm_repositories';
	
	var $total_files = '';
	
	var $parent_tree;
	
	
	/*
	 * getting user files
	 */
	function getFiles($sharing, $orderby, $userid = false, $userlogin = false)
	{
		global $wpdb;
		
		if(!$userid){
			global $current_user;
			get_currentuserinfo();
			
			$userid = $current_user -> ID;
			$userlogin = $current_user -> user_login;
		} 
		
		$qry = '';
	
		$parent = (intval(@$_REQUEST['dir']) != '') ? intval(@$_REQUEST['dir']) : 0;
		$whereParent = "And fileParent = '$parent'";
	
		switch ($sharing){

			case 'my':
				$qry = "SELECT * FROM ".$wpdb->prefix . $this -> tblName."
				WHERE userID = ".$userid." $whereParent
				ORDER BY fileType, $orderby DESC";
				break;
				
				
			/* case 'shared':
				$qry = "SELECT * FROM ".$wpdb->prefix . $this -> tblName."
			WHERE fileShared LIKE '%". $current_user -> user_login. "%'
			OR fileShared LIKE '%". $current_user -> roles[0]. "%'
			ORDER BY $orderby DESC";
			break; */

			case 'all':
				$qry = "SELECT * FROM ".$wpdb->prefix . $this -> tblName."
				ORDER BY $orderby DESC";
				break;

			case 'search':
				$qry = "SELECT * FROM ".$wpdb->prefix . $this -> tblName."
				WHERE userID = ".intval(@$_REQUEST['s-user'])."
				AND fileName LIKE '%".sanitize_text_field(@$_REQUEST['s-filename'])."%'
				ORDER BY $orderby DESC";
				break;
			
		}
	
	
		$myrows = $wpdb->get_results($qry);
	
		/* $wpdb->show_errors();
		 $wpdb->print_error(); */
	
		//$this -> total_files = $this -> fileCount(count($myrows));
	
		return $myrows;
	}
	
	
	
	/*
	 * rendering Parent Tree
	* in nm-file-browser
	*/
	function renderTree($parentID, $prevDir = ''){
	
		global $wpdb, $repo;
		$parentDir = $prevDir;
	
		//$parentID = ($_REQUEST['dir'] != '') ? intval($_REQUEST['dir']) : 0;
	
		if ($parentID == ''){
			echo '<a href="'.get_permalink().'">Home</a>'.' &raquo; ';
			return false;
		}
	
		$dir = $wpdb -> get_row("select fileID, fileName, fileParent from ".$wpdb->prefix . $this -> tblName." where fileID = $parentID");
		/* $wpdb->show_errors();
		 $wpdb->print_error(); */
		//print_r($dir); exit;
	
		if($dir -> fileID != 0 && $dir -> fileID != '')
		{
			//echo $dir -> fileName;
			$parentDir[] = array('dir'	=> $dir -> fileName,
					'dir_id'	=> $dir -> fileID);
				
			$this -> renderTree($dir -> fileParent, $parentDir);
		}else{
	
			//reverse the array to make the parent tree
			$parentDir = array_reverse($parentDir);
			//print_r($parentDir);
			echo '<a href="'.get_homelink( @$_REQUEST['userid'], @$_REQUEST['userlogin'] ).'">'.__('Home', $repo -> plugin_shortname).'</a>'.' &raquo; ';
				
			foreach ($parentDir as $key => $val){
				//print_r($val);
				$urlDir = fixRequestURI(array('dir'	=> $val['dir_id']) );
				//echo '<a href="'.get_permalink().'?dir='.$val['dir_id'].'">'.$val['dir'].'</a> &raquo; ';
				echo '<a href="'.$urlDir.'">'.$val['dir'].'</a> &raquo; ';
			}
		}
	
	}
	
	
	
	/*
	 ** making file name with URL
	*/
	function makeFileTitleLink($fileid, $files, $filesize, $date, $type, $parent, $username)
	{
		$html = '';
		if($type == '1dir'){
				
			$dirURL = fixRequestURI(array('dir' => $fileid));
			$html .= '<a href="'.$dirURL.'" title="'.$files.'" class="nm-link-title">'.$files.'</a>';
		}else{
	
			$arrFiles = explode(',', $files);
	
	
			foreach($arrFiles as $f)
			{
				//$html .= '<a href="javascript:loadFileDetail('.$fileid.')" title="'.$f.'" class="nm-link-title">'.$f.' ('.sizeInKB($filesize).')</a>';
				
				$fileLink = $this -> getParentPath($username, $parent, true);
				$fileLink .= $f;
				$html .= '<a href="'.$fileLink.'" title="'.$f.'" class="nm-link-title">'.$f.' ('.sizeInKB($filesize).')</a>';
				
			}
	
			$html .= ' - <span class="nm-file-meta-more">'.time_difference($date).'</span>';
		}
			
		return $html;
	}
	
	
	/*
	 * Uploading file to repositry
	 */
	function uploadFile($username, $parentID){

		/* $upload_dir = wp_upload_dir();
		 $path_folder = $upload_dir['basedir'].'/user_uploads/'.$username.'/'; */

		$dirPath = $this -> getParentPath($username, $parentID);
		//echo $dirPath; exit;

		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $dirPath;
			$purifyFileName = preg_replace("![^a-z0-9.]+!i", "_", $_FILES['Filedata']['name']);
			$targetFile = rtrim($targetPath,'/') . '/' .$purifyFileName;

			if(move_uploaded_file($tempFile,$targetFile)){
				echo $purifyFileName;
			}

			else{
				echo 'Error in file uploading';
			}
		}
	}
	
	/*
	 * Now saving file data in DB
	 * after uploading file
	 */
	
	function saveFile($filename, $filedetail, $parentID){
	
		
		
		global $current_user, $wpdb;
		get_currentuserinfo();
	
		$user_name = '';
		$user_id = '';
		if(is_user_logged_in())
		{
			$user_name = $current_user -> user_login;
			$user_id = $current_user -> ID;
		}
	
		$upload_dir = wp_upload_dir();
		$filePath = $this -> getParentPath($user_name, $parentID);
		//$filePath .= $this -> file_name;
		
		
	
		$dt = array(
				'fileName'			=> $filename,
				'fileDetail'		=> $filedetail,
				'userID'			=> $user_id,
				'userName'			=> $user_name,
				'fileType'			=> substr(strrchr($filename,'.'),1),
				'fileSize'			=> getFileSize($filePath, $filename),
				'fileParent'		=> $parentID,
				'fileUploadedOn'	=> current_time('mysql')
		);
	
	
		$wpdb -> insert($wpdb->prefix . $this -> tblName,
				$dt,
				array('%s', '%s', '%d', '%s', '%s', '%d', '%s', '%s')
		);
		
	
		/*$wpdb->show_errors();
		 $wpdb->print_error();*/
	
		if($wpdb->insert_id)
		{
			/* if(get_option('nm_fileshare_send_email'))
				$this -> sendEmail($filename, $filemeta); */
			return true;
		}
		else
		{
			$wpdb->show_errors();
			$wpdb->print_error();
			return false;
		}
	
	
	}
	
	
	/*
	 * creating directroy physically
	 */
	
	function createDirectory($directoryName, $parentID){
	
		global $current_user, $wpdb;
		get_currentuserinfo();
	
		$directoryName = preg_replace("![^a-z0-9]+!i", "_", $directoryName);
		
	
		$user_name = '';
		$user_id = '';
		if(is_user_logged_in())
		{
			$user_name = $current_user -> user_login;
			$user_id = $current_user -> ID;
		}
	
	
		if( !$this -> createDirectory_physically($user_name, $parentID, $directoryName) )
			die('Error while creating directory');
		//$filePath .= $this -> file_name;
		
	
		//making it private
		$dt = array(
				'fileName'			=> $directoryName,
				'userID'			=> $user_id,
				'userName'			=> $user_name,
				'fileType'			=> '1dir',
				'fileSize'			=> NULL,
				'fileParent'		=> $parentID,
				'fileUploadedOn'	=> current_time('mysql')
		);
	

		
		$wpdb -> insert($wpdb->prefix . $this -> tblName,
				$dt,
				array('%s', '%d', '%s', '%s', '%d', '%s', '%s')
		);
		
		
	
		$wpdb->show_errors();
		 $wpdb->print_error();
	
		if($wpdb->insert_id)
		{
			/* if(get_option('nm_fileshare_send_email'))
				$this -> sendEmail($filename, $filemeta); */
			return true;
		}
		else
		{
			$wpdb->show_errors();
			$wpdb->print_error();
			return false;
		}
	
	}
	
	/*
	 * creating physical directory
	*/
	
	function createDirectory_physically($userName, $parentID, $newDir)
	{
		$dirPath = $this -> getParentPath($userName, $parentID);
	
		$dirPath = $dirPath . $newDir;
	
		//echo 'path '.$dirPath; exit;
		if(!is_dir($dirPath))
		{
			if(mkdir($dirPath, 0777, true))
				return true;
			else
				return false;
		}
		else
		{
			return true;
		}
	}
	
	
	
	/*
	 * generating dir path
	 */
	function getParentPath($userName, $parentID, $isURL = false){
	
	
		$dirPath = '';
		if($parentID == 0){ 	//it's root then
				
			$upload_dir = wp_upload_dir();
			if($isURL)
				$dirPath = $upload_dir['baseurl'].'/user_uploads/'.$userName.'/';
			else 
				$dirPath = $upload_dir['basedir'].'/user_uploads/'.$userName.'/';
		}else{
				
			/*
			 * following function get the parent Tree and
			* SAve it in satic var: $this -> $parent_tree
			*/
			$this -> getDirectoryParent($parentID);
				
			$upload_dir = wp_upload_dir();
			if($isURL)
				$dirPath = $upload_dir['baseurl'].'/user_uploads/'.$userName.'/'.$this -> parent_tree.'/';
			else
				$dirPath = $upload_dir['basedir'].'/user_uploads/'.$userName.'/'.$this -> parent_tree.'/';
		}
	
		return $dirPath;
	}
	
	
	
	/*
	 * get the current dir Parent
	 * also set/save parent tree to : $parent_tree
	 */
	function getDirectoryParent($parentID, $prevDir=''){
	
		global $wpdb;
		$parentDir = $prevDir;
	
	
		$dir = $wpdb -> get_row("select fileID, fileName, fileParent from ".$wpdb->prefix . $this -> tblName." where fileID = $parentID");
		/* $wpdb->show_errors();
			$wpdb->print_error(); */
		//print_r($dir); exit;
	
		if($dir -> fileID != 0 && $dir -> fileID != '')
		{
			$parentDir[] = $dir -> fileName;
			$this -> getDirectoryParent($dir -> fileParent, $parentDir);
		}else{
				
			//reverse the array to make the parent tree
			$parentDir = array_reverse($parentDir);
			//print_r($parentDir);
			$this -> parent_tree = implode('/', $parentDir);
		}
	
	}
	
	
	/*
	 * deleting file
	 */
	
	public function deleteFile($fileid, $parentID)
	{
		global $wpdb;
		global $current_user;
		get_currentuserinfo();
	
		$rsObj = $wpdb->get_row("SELECT fileName FROM ".$wpdb->prefix . $this -> tblName." WHERE fileID = ".$fileid);
		$res = $wpdb->query($wpdb -> prepare(
				"
				DELETE FROM ".$wpdb->prefix . $this -> tblName."
				WHERE fileID = %d",
				$fileid
		));
	
		if($res){
			$upPath = wp_upload_dir();
			$arrFiles = explode(',', $rsObj->fileName);
	
			foreach($arrFiles as $f)
			{
				$path = $this -> getParentPath($current_user -> user_login, $parentID);
				$path .= $f;
				$res = @unlink($path);
				return true;
			}
		}
	
		//return $res;
	}
	
	
	/*
	 ** Deleting all files of a user
	*/
	public function deleteAllUserFiles($userName, $userID)
	{
		global $wpdb;
	
		$res = $wpdb->query("DELETE FROM ".$wpdb->prefix . $this -> tblName."
				WHERE userID = $userID"
		);
		
		$upPath = wp_upload_dir();
		$dirPath = $upPath['basedir']."/user_uploads/".$userName;
		
		$this -> deleteAllDirectoresOfUser($dirPath);
		
		
	}
	
	
	function deleteAllDirectoresOfUser($dirPath){
		
		if (! is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteAllDirectoresOfUser($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
	
	/*
	 deleting Dir
	*/
	public function deleteDir($fileid, $parentID)
	{
		global $wpdb;
		global $current_user;
		get_currentuserinfo();

		$rsObj = $wpdb->get_row("SELECT fileName FROM ".$wpdb->prefix . $this -> tblName." WHERE fileID = ".$fileid);
		$res = $wpdb->query($wpdb -> prepare(
				"
				DELETE FROM ".$wpdb->prefix . $this -> tblName."
				WHERE fileID = %d",
				$fileid
		));

		if($res){

			$dir = $this -> getParentPath($current_user -> user_login, $parentID);

			$dir .= $rsObj->fileName;
			echo 'dir '.$dir;
			exec('rm -r '.$dir.'/');
		}
		//return $res;
	}
	
	/*
	 ** Getting files stats for admin
	*/
	
	function getFilesStats()
	{
		global $wpdb;
	
		$qry = "select userID, userName,
		COUNT(IF(fileType = '1dir', fileType, NULL)) Directories,
		COUNT(IF(fileType != '1dir', fileType, NULL)) Files,
		SUM(IF(fileType != '1dir', fileSize, NULL)) FileSize 
		FROM ".$wpdb->prefix . $this -> tblName." group by userID";

		$myrows = $wpdb->get_results( $qry );
		
		/* $wpdb->show_errors();
		$wpdb->print_error(); */
		
		return $myrows;
	}
	
	
	/*
	 * following function breaking the file
	 * information into presentation form
	 */
	function explodeFileData($fileObj){
		
		global $repo;
		
		$html = '';
				
		$html .= __('Directories: ', $repo -> plugin_shortname).$fileObj -> Directories;
		$html .= '<br>';
		$html .= __('Files: ', $repo -> plugin_shortname).$fileObj -> Files.' ('.sizeInKB($fileObj->FileSize).')';
		
		return $html;
	}
	
	
	
	
	/*
	 ** sending emails
	*/
	function sendEmail($file_name, $file_meta = '')
	{
		global $current_user;
		get_currentuserinfo();
	
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	
	
		$from = $current_user -> data -> user_email;
		$to = get_bloginfo('admin_email');
	
		$subject = 'File uploaded by '.$current_user -> data -> user_login;
		$message = 'A file is uploaded by '.$current_user -> data -> user_login;
		$message .= "<br /><strong>Filename: </strong>$file_name";
		$message .= "<br /><br /><strong>File meta: </strong><br />".$this -> get_simplifyMeta($file_meta);
	
		$headers .= "From: $from\r\n";
	
		$uploaded_by = $current_user -> data -> user_email;
	
		mail($to, $subject, $message, $headers);
		mail($uploaded_by, $subject, $message, $headers);
	
	}
	
}


