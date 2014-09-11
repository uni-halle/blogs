<?php 
/*
 * Listing all users with file stats
 */

if($_REQUEST['userid']){
	
	$browse_file = $repo -> plugin_path .'/includes/admin/nm-file-browser.php';
	if( file_exists($browse_file))
		include($browse_file);
	else
		_e('Fie not found '.$browse_file, $repo -> plugin_shortname);
	
	die(0);
}
?>


<?php 
$obj = new nmRepository();
global $repo;
?>
<h2><?php _e('File Statistics', 'nm_file_uploader_pro')?></h2>

<div id="pro-feature" style="display:none;padding:1.5em 1%; background-color:#ECECEC; font-size:22px; text-align:center;width:97%;margin:0 auto">
It is one of the PRO feature, please contact <a href="mailto:sales@najeebmedia.com">sales@najeebmedia.com</a>
</div>

<?php
/*
delete file
*/
if(isset($_GET['uname']))
{
	//echo 'file deleted '.$obj ->  deleteFile($_GET['fid']);
	if($obj ->  deleteAllUserFiles($_GET['uname'], $_GET['uid']))
	{
		echo '<div class="updated">';
		echo "<p>". get_option('nm_file_deleted_msg') ."</p>";
		echo '</div>';
	}
	
}


$arrFiles = $obj ->  getFilesStats();
/* echo '<pre>';
print_r($arrFiles);
echo '</pre>'; */
?>
<div style="margin:5px;padding:5px;border:1px solid #CCC; background-color:#f5f5f5">
<div class="user-uploaded-files">
<table width="100%" border="0" id="user-files" class="wp-list-table widefat fixed posts">
<thead>
	<tr>
    	<th width="56" align="center" valign="middle">
        <strong><?php _e('Sr No', 'nm_file_uploader_pro')?></strong></th>
        <th width="146" align="center" valign="middle">
        <strong><?php _e('User Name', 'nm_file_uploader_pro')?></strong></th>
        <th width="203" align="center" valign="middle">
        <strong><?php _e('Files Stats', 'nm_file_uploader_pro')?></strong></th>
        <th width="108" align="center" valign="middle">
        <strong><?php _e('Download All', 'nm_file_uploader_pro')?></strong></th>
        <th width="105" align="center" valign="middle">
        <strong><?php _e('Delete All', 'nm_file_uploader_pro')?></strong></th>
      </tr>
</thead>


<tbody>
<?php 
$srNo = 1;
foreach($arrFiles as $file):
//print_r(parse_url($_SERVER['HTTP_REFERER']));
$user_info = get_userdata($file -> userID);


$params = array('uname'		=> $user_info -> user_login,
				'uid'		=> $file -> userID);
$urlDelete = fixRequestURI($params);

/* $url_all_download = '/download-all.php?dname='. $user_info -> user_login.'&plugin_url='.$repo -> plugin_url'', __FILE__);
$urlDnldAll = $repo -> plugin_url$url_all_download, __FILE__); */

$urlDetail = fixRequestURI(array('userid' => $file -> userID, 'userlogin'	=> $file -> userName));
?>
  <tr>
   	<td width="56" align="center">
		<?php echo $srNo++;?>
    </td>
    <td><?php echo $file -> userName?><br>
    <a href="<?php echo $urlDetail?>"><?php _e('File Tree', $repo -> plugin_shortname)?></a>
    </td>
    
    <td>
    <?php echo $obj -> explodeFileData($file)?>
    </td>
    
    
    <td width="108" align="left" valign="middle">
	<a href="javascript:itIsPro()">
	<?php echo "<img border=\"0\" src=".$repo -> plugin_url.'/images/down_16.png'." />";	?>
    </a>
    </td>
    <td width="105" align="left" valign="middle">
    <a href="javascript:confirmFirst('<?php echo $urlDelete?>')">
	<?php echo "<img border=\"0\" src=".$repo -> plugin_url.'/images/delete_16.png'." />";	?>
    </a></td>
  </tr>
<?php endforeach;?>
  
</tbody>
</table>
</div>
<div style="clear:both"></div>
</div>

<script type="text/javascript">
function confirmFirst(url)
	{
		var a = confirm('WARNING: ALL file will be deleted. Are you sure to delete all files?');
		if(a)
		{
			window.location = url;
		}
	}
</script>