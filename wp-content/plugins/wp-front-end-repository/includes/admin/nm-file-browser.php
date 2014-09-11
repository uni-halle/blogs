<?php
/*
 * this template will display the files and directories
 */

global $repo;
?>

<h2><?php _e('Files by '.@$_REQUEST['userlogin'], $repo -> plugin_shortname)?></h2>
<div id="nmuploader-wrapper">
	
	<?php
	$sharedType	= isset($_REQUEST['sharing']) ? @$_REQUEST['sharing'] : 'my';
	$orderBy	= isset($_REQUEST['orderby']) ? @$_REQUEST['orderby'] : 'fileUploadedOn';
	
	$sharedType = isset($_REQUEST['s-user']) ? 'search' : $sharedType;
	
	$arrFiles = $repo -> obj -> getFiles('my', $orderBy, @$_REQUEST['userid'], @$_REQUEST['userlogin']);
	
	$repo -> files_per_page = ( get_option('nm_fileshare_file_limit') == 0) ? 5 : get_option('nm_fileshare_file_limit');

	$repo -> total_pages = ceil(count($arrFiles) / $repo -> files_per_page);
	
	$searchFormAction = fixRequestURI(array('do'	=> 'search')).'#nm-files';

	$urlReset = substr($searchFormAction, 0, strpos($searchFormAction, '?'));
	//echo $urlReset;
	//pa($arrFiles);
	?>

	
	<div id="parent-tree"><?php $repo -> obj -> renderTree(intval(@$_REQUEST['dir']))?></div>
	<a name="nm-files"></a>
	<ul id="nmuploader-top">
		<li>
			<ul>
			<?php if(@$_REQUEST['s-user']):?>
				<li id="reset-search" style="float:right">
				<a href="<?php echo $urlReset?>"><?php _e('Clear search', $repo -> plugin_shortname)?></a></li>
			<?php else:?>
			
			<?php endif;?>
			
			<?php 
			/* if(nmFileUploaderActions::$userRole == 'administrator'){
				
				if(!$_REQUEST['s-user'])
					echo '<li id="sharing-all"><a href="'.$urlAllFile.'">'.__('All files', $repo -> plugin_shortname).'</a></li>';
				
				$args = array('name'                    => 's-user',
						'show'                    => 'display_name',);
				
				echo '<li class="search">';
				echo '<form id="form-searchfile" method="get" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';
				wp_dropdown_users($args);
				
				echo '<input type="search" name="s-filename" />';
				echo '<input type="submit" value="Search" />';
				echo '</form>';
				echo '</li>';
			} */
			?>
			</ul>
		</li>
	</ul>

	<?php
	/*
	 * checking if files are in database
	 */
	if($arrFiles){
	?>

	<ul id="nmuploader-container">
		<form id="frm_nm_files" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])?>#download-zip" method="post">
			<input type="hidden" name="plugin_url" value="<?php echo plugins_url('', __FILE__)?>" />
			<?php 
			$uploader_row_count = 0;
			foreach($arrFiles as $file):

			$urlDelete = plugins_url('', __FILE__);

			/* $user_name = '';
			if($file -> userID != 0) 			//public upload
			{
				$user_info = get_userdata($file -> userID);
				$user_name = $user_info -> user_login;
			} */

			$bg_color = '';
			
			$urlDetail = $repo -> obj -> makeFileTitleLink($file -> fileID,
					$file -> fileName,
					$file -> fileSize,
					$file -> fileUploadedOn,
					$file -> fileType,
					$file -> fileParent,
					$file -> userName);


			$uploader_row_count++;
			$page_number = ceil($uploader_row_count / $repo -> files_per_page);

			$extImage = strtolower(str_replace('.', '', $file -> fileType)). '.png';
			$urlExtImage = $repo -> plugin_url .'/images/ext/48px/'.$extImage;
			

			$isOwner = ($current_user -> user_login == $file -> userName) ? true : false;
			?>


			<li class="nm-c-p-<?php echo $page_number?>">
				<ul class="nmuploader-row" id="nmuploader-<?php echo $file -> fileID?>">
					
					<li><img width=48 src="<?php echo $urlExtImage?>" title="<?php echo $file -> fileName?>" />
					</li>
					
					<?php if($file -> fileType == '1dir'):?>
					
					<li class="meta">
					<span id="meta-smart-<?php echo $file -> fileID?>"><?php echo $urlDetail?><br />
						<span class="nm-file-meta-more"> <?php _e('Owner:', $repo -> plugin_shortname)?> 
						</span>
					</span>
					</li>
					
					<?php else:?>

					<li class="meta">
					<span id="meta-smart-<?php echo $file -> fileID?>"><?php echo $urlDetail?><br />
						<span class="nm-file-meta-more"> 
						<?php echo stripslashes($file -> fileDetail)?>
						</span>
					</span>
					
					<!-- for file meta/detail loading -->
					<span id="meta-all-<?php echo $file -> fileID?>" class="nm-file-meta-all">
					</span>
					</li>
					
					<?php endif;?>
					
					
					<li class="tool">
					<a href="javascript:confirmFirst('<?php echo $file -> fileID?>', '<?php echo $file -> fileType?>')" title="Delete"> 
					<?php echo '<img id="del-file-'.$file -> fileID.'" border="0" src="'.$repo -> plugin_url . '/images/delete_16.png'.'" />';	?>
					</a>
					</li>


	</ul>
	</li>

	<div class="fix_height"></div>
	<?php endforeach;?>
	</form>
	</ul>

	
	<div class="fix_height"></div>
	
	<?php }else{			/* if($arrFiles){ */
		?>
	<ul id="nmuploader-container">
	<li class="no-file-found">
			<span><?php _e('No file here yet', 'nm_file_uploader_pro')?></span>
	</li>
	</ul>
	<?php }
?>
</div>

