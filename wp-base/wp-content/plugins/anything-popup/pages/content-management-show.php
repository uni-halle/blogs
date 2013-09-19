<?php
// Form submitted, check the data
if (isset($_POST['frm_pop_display']) && $_POST['frm_pop_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$pop_success = '';
	$pop_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".AnythingPopupTable."
		WHERE `pop_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong>Oops, selected details doesn't exist (1).</strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('pop_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".AnythingPopupTable."`
					WHERE `pop_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$pop_success_msg = TRUE;
			$pop_success = __('Selected record was successfully deleted.', AnythingPopup_UNIQUE_NAME);
		}
	}
	
	if ($pop_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $pop_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php echo AnythingPopup_TITLE; ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=anything-popup&amp;ac=add">Add New</a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".AnythingPopupTable."` order by pop_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/anything-popup/pages/setting.js"></script>
		<form name="frm_pop_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="pop_group_item[]" /></th>
			<th scope="col">Id</th>
			<th scope="col">Short code</th>
            <th scope="col">Popup title</th>
			<th scope="col">Window width</th>
			<th scope="col">Window height</th>
			<th scope="col">Header color</th>
			<th scope="col">Border color</th>
			<th scope="col">Font color</th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="pop_group_item[]" /></th>
			<th scope="col">Id</th>
			<th scope="col">Short code</th>
            <th scope="col">Popup title</th>
			<th scope="col">Window width</th>
			<th scope="col">Window height</th>
			<th scope="col">Header color</th>
			<th scope="col">Border color</th>
			<th scope="col">Font color</th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['pop_id']; ?>" name="pop_group_item[]"></td>
						<td><?php echo $data['pop_id']; ?></td>
						<td>[AnythingPopup id="<?php echo $data['pop_id']; ?>"]
						<div class="row-actions">
							<span class="edit"><a title="Edit" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=anything-popup&amp;ac=edit&amp;did=<?php echo $data['pop_id']; ?>">Edit</a> | </span>
							<span class="trash"><a onClick="javascript:_pop_delete('<?php echo $data['pop_id']; ?>')" href="javascript:void(0);">Delete</a></span> 
						</div>
						</td>
						<td><?php echo stripslashes($data['pop_title']); ?></td>
						<td><?php echo $data['pop_width']; ?></td>
						<td><?php echo $data['pop_height']; ?></td>
						<td><?php echo $data['pop_headercolor']; ?></td>
						<td><?php echo $data['pop_bordercolor']; ?></td>
						<td><?php echo $data['pop_header_fontcolor']; ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="9" align="center">No records available.</td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('pop_form_show'); ?>
		<input type="hidden" name="frm_pop_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=anything-popup&amp;ac=add">Add New</a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo AnythingPopup_FAV; ?>">Help</a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3>Plugin configuration option</h3>
	<ol>
		<li>Drag and drop the widget.</li>
		<li>Add the plugin in the posts or pages using short code.</li>
		<li>Add directly in to the theme using PHP code.</li>
	</ol>
	<p class="description"><?php echo AnythingPopup_LINK; ?></p>
	</div>
</div>