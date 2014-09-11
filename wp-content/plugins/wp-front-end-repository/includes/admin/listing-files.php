<h2><?php _e('File Uploaded by Users', 'nm_file_uploader_pro')?></h2>
<?php

/*
delete file
*/
if(isset($_GET['fid']))
{
	//echo 'file deleted '.nmFileUploader::deleteFile($_GET['fid']);
	if(nmFileUploader::deleteFile($_GET['fid']))
	{
		echo '<div class="updated">';
		echo "<p>". get_option('nm_file_deleted_msg') ."</p>";
		echo '</div>';
	}
	
}


$arrFiles = nmFileUploader::getAllUserFiles();
?>
<div style="margin:5px;padding:5px;border:1px solid #CCC; background-color:#f5f5f5">
<h3><?php _e('Total files', 'nm_file_uploader_pro')?>: <?php echo count($arrFiles)?></h3>
<div class="user-uploaded-files">
<table width="100%" border="0" id="user-files" class="wp-list-table widefat fixed posts">
<thead>
	<tr>
        <th width="233" align="left" valign="middle">
        <strong><?php _e('File Name', 'nm_file_uploader_pro')?></strong></th>
        <th width="516" align="left" valign="middle">
        <strong><?php _e('File Meta', 'nm_file_uploader_pro')?></strong></th>
        <th width="71" align="center" valign="middle">
        <strong><?php _e('Date', 'nm_file_uploader_pro')?></strong></th>
        <th width="97" align="center" valign="middle">
        <strong><?php _e('Action', 'nm_file_uploader_pro')?></strong></th>
      </tr>
</thead>


<tbody>
<?php foreach($arrFiles as $file):
//print_r(parse_url($_SERVER['HTTP_REFERER']));

$params = array('fid'	=> $file -> fileID);
$urlDelete = nmFileUploader::fixRequestURI($params);

$user_info = get_userdata($file -> userID);

/*$uploads = wp_upload_dir();
if(file_exists($uploads['basedir'] . '/user_uploads/'. $file -> fileName))
	$urlDwnld = $uploads['baseurl'] . '/user_uploads/'. $file -> fileName;
else
  	$urlDwnld = $uploads['baseurl'] . '/user_uploads/'.$user_info -> user_login.'/' . $file -> fileName;*/

if($file -> userID != 0) 			//public upload
{
	$user_info = get_userdata($file -> userID);
	$user_name = $user_info -> user_login;
}

$urlDwnld = nmFileUploader::makeFileDownloadable($file -> fileName, 
															$file -> fileSize,	
															$user_name,
															$file -> bucketName);
$urlUserProfile = admin_url( 'profile.php?user_id='.$file->userID.'#nm-uploaded-files' );
?>
  <tr>
    <td><?php echo $urlDwnld?><br />
		<?php _e('uploaded by', 'nm_file_uploader_pro')?>: <a href="<?php echo $urlUserProfile?>"><?php echo $user_info -> user_login?></a> | <?php echo nmFileUploader::sizeInKB($file -> fileSize)?></td>
    <td>
    <a href="javascript:toggleDetail(<?php echo $file -> fileID?>)">View Detail</a><br />
    <span id="file-detail-<?php echo $file -> fileID?>" class="file-detail">
	<?php nmFileUploader::simplifyMeta($file -> fileMeta)?></span>
    </td>
    
    <td width="71" align="center"><?= date('d-M,y', strtotime($file -> fileUploadedOn))?></td>
    <td width="97" align="center">
    <a href="javascript:confirmFirst('<?= $urlDelete?>')">
	<?php echo "<img border=\"0\" src=".plugins_url( 'images/delete_16.png' , __FILE__)." />";	?>
    </a></td>
  </tr>
<?php endforeach;?>
  
</tbody>
</table>
</div>
<div style="clear:both"></div>
</div>


<script type="text/javascript">
//hiding file detail
jQuery(".file-detail").hide();

	function confirmFirst(url)
	{
		var a = confirm('Are you sure to delete this file?');
		if(a)
		{
			window.location = url;
		}
	}
	
	function toggleDetail(id)
  {
	jQuery(".file-detail").hide();
	jQuery("#file-detail-"+id).fadeToggle('fast');
	
  }

</script>