<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php if ( ! empty( $_POST ) && ! wp_verify_nonce( $_REQUEST['wp_create_nonce'], 'content-management-show-nonce' ) )  { die('<p>Security check failed.</p>'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_pop_display']) && $_POST['frm_pop_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
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
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'anything-popup'); ?></strong></p></div><?php
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
			$pop_success = __('Selected record was successfully deleted.', 'anything-popup');
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
    <h2><?php _e('Anything Popup', 'anything-popup'); ?>
	<a class="add-new-h2" href="<?php echo ANYTHGPOPUP_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'anything-popup'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".AnythingPopupTable."` order by pop_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo ANYTHGPOPUP_PLUGIN_URL; ?>/pages/setting.js"></script>
		<form name="frm_pop_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Id', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Short code code', 'anything-popup'); ?></th>
            <th scope="col"><?php _e('Popup title', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Width', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Height', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Header color', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Border color', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Font color', 'anything-popup'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Id', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Short code code', 'anything-popup'); ?></th>
            <th scope="col"><?php _e('Popup title', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Width', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Height', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Header color', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Border color', 'anything-popup'); ?></th>
			<th scope="col"><?php _e('Font color', 'anything-popup'); ?></th>
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
						<td><?php echo $data['pop_id']; ?></td>
						<td>[AnythingPopup id="<?php echo $data['pop_id']; ?>"]
						<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo ANYTHGPOPUP_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['pop_id']; ?>"><?php _e('Edit', 'anything-popup'); ?></a> | </span>
						<span class="trash"><a onClick="javascript:_pop_delete('<?php echo $data['pop_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'anything-popup'); ?></a></span> 
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
				?><tr><td colspan="8" align="center"><?php _e('No records available.', 'anything-popup'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('pop_form_show'); ?>
		<input type="hidden" name="frm_pop_display" value="yes"/>
		<input type="hidden" name="wp_create_nonce" id="wp_create_nonce" value="<?php echo wp_create_nonce( 'content-management-show-nonce' ); ?>"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo ANYTHGPOPUP_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'anything-popup'); ?></a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo AnythingPopup_FAV; ?>"><?php _e('Help', 'anything-popup'); ?></a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3><?php _e('Plugin configuration option', 'anything-popup'); ?></h3>
	<ol>
		<li><?php _e('Drag and drop the widget.', 'anything-popup'); ?></li>
		<li><?php _e('Add the plugin in the posts or pages using short code.', 'anything-popup'); ?></li>
		<li><?php _e('Add directly in to the theme using PHP code.', 'anything-popup'); ?></li>
	</ol>
	<p class="description">
		<?php _e('Check official website for more information', 'anything-popup'); ?>
		<a target="_blank" href="<?php echo AnythingPopup_FAV; ?>"><?php _e('click here', 'anything-popup'); ?></a><br />
	</p>
	</div>
</div>