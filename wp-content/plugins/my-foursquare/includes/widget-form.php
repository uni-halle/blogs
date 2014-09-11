<?php
$option = get_option("foursquare_option");
$title = isset($option['title']) ? $option['title'] : "My Foursquare";
?>
<table>
	<tr>
		<td>
			<br><b>Settings </b><br> 
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			Foursquare Username: 
		</td>
	</tr>
	<tr>
		<td>
			<input type="text" name="foursquare[user]" value="<?php echo $option['user'];?>" class="widefat">
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			Foursquare Password: 
		</td>
	</tr>
	<tr>
		<td>
			<input type="password" name="foursquare[pass]" value="<?php echo $option['pass'];?>" class="widefat">
		</td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td>
			I want to show my:
		</td>
	</tr>

	<tr>
		<td>

			<input type="checkbox" name="foursquare[badge]" value="1" <?php if($option['badge'] == 1) echo "checked='checked'";?> onclick="">Badges<br>
			<input type="checkbox" name="foursquare[mayor]" value="1" <?php if($option['mayor'] == 1) echo "checked='checked'";?>>Mayorships<br>
			<input type="checkbox" name="foursquare[location]" value="1" <?php if($option['location'] == 1) echo "checked='checked'";?>>Last Checkin<br>
			<input type="checkbox" name="foursquare[gmap]" value="1" <?php if($option['gmap'] == 1) echo "checked='checked'";?>>Last Checkin on a Map<br>
		</td>
	</tr>
	
	<tr><td>&nbsp;</td></tr>

	<tr>
		<td>
			Widget Width
		</td>
	</tr>
	
	<tr>
		<td>
			<div id='badge_wide_div'>
		    		<select name='foursquare[no_badges_wide]' id='foursquare[no_badges_wide]'>
		    			<option value='4' <?php if($option['no_badges_wide'] == 4) echo "selected='selected'";?>>4 Badges Wide</option>
		    			<option value='3' <?php if($option['no_badges_wide'] == 3) echo "selected='selected'";?>>3 Badges Wide</option>
		    		</select>
                       </div>
               </td>
     </tr>

	<tr><td>&nbsp;</td></tr>
       
	<tr>
		<td> 
		      <input name="foursquare[fs_confirm]" type="checkbox" id="fs_confirm" <?php if($option['fs_confirm']){?>checked="checked"<?php }?>/>
		      <a href="http://www.myfoursquare.net/terms">
				Agree to Terms of Use
		      </a>
  		  </td>
  	</tr>

	<input type="hidden" name="choice" value="save_foursquare_option">
</table>
<style type="text/css">
	.widefat {
		width:200px;
	}
</style>
<script type="text/javascript">
	function show_text(id) {
		alert(id.value);
		var check = (id.checked) ? '' : 'none';
		if(id.value==1) {
			if(document.getElementById('badge_wide_div')) {
				alert(document.getElementById('badge_wide_div').style.display);
				document.getElementById('badge_wide_div').style.display = check;
				alert(document.getElementById('badge_wide_div').style.display);
			}
		}
	}
</script>