<?php
global $repo;

if(!$repo -> setupUserDirectory())
	die('Error while creating Directory');

?>
<!-- loading custom css here -->
<style>
<?php //echo get_option('nm_fileshare_custom_css')?>
</style>

<div id="nm-upload-container">
<div><a href="javascript:showContainers('file')">Upload File</a> | <a href="javascript:showContainers('dir')">Create Directory</a></div>
<div id="error"></div>
<div id="container-file" style="display:none">
<h2 class="uploader-titles"><?php _e('Upload Files', $repo->plugin_shortname)?></h2>
<form method="post" onsubmit="return validate()" id="frm_upload">
<input type="hidden" name="file-name" id="file-name">


<p class="nm-uploader-area">

	<span><?php _e('Select file(s) to upload, once files uploaded click on Save button below', $repo->plugin_shortname)?></span>
  <input id="file_upload" name="file_upload" type="file" />
  <span id="upload-response"></span>
</p>
  
<?php //nmFileShare::renderInput()?>

<ul class="nm-file-meta">
	<li class="caption"><?php _e('File description', $repo -> plugin_shortname)?></li>
    <li class="inputs">
    <textarea name="nm-file-detail"></textarea>	
    </li>
</ul>
  
<ul class="nm-file-meta">
	<li class="caption">&nbsp;</li>
    <li class="inputs">
    <input class="nm-submit-button" type="submit" value="<?php _e('Save', $repo->plugin_shortname)?>" name="nm-submit" id="nm-upload">
    <div id="working-area" style="display:none">
		<?php
			echo "<img src=".$repo -> plugin_url .'/images/loading.gif'." />";
		?>
    </div>	
    </li>
</ul>
</form>

</div>

<div id="container-dir" style="display: none">
<h2 class="uploader-titles"><?php _e('Directory', $repo->plugin_shortname)?></h2>
<form action="" id="nm-frm-directory" onsubmit="return false">
<input type="hidden" name="parent-directory" value="<?php echo $_REQUEST['dir']?>">
<ul class="nm-file-meta">
	<li class="caption"><?php _e('Type directory name', $repo->plugin_shortname)?></li>
    <li class="inputs">
    <input type="text" name="directory-name" />
    	
    </li>
    
    <li class="caption">&nbsp;</li>
    <li class="inputs">
    <input type="submit" value="<?php _e('Create', $repo->plugin_shortname)?>">
    <div id="working-area-directory" style="display:none">
		<?php
			echo "<img src=".$repo -> plugin_url .'/images/loading.gif'." />";
		?>
    </div>	
    </li>
</ul>

</form>
</div>
</div>

<div class="fix_height"></div>

<script type="text/javascript">
		setupUploader();
</script>