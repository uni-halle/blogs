<?php
function ocmx_upgrade_options (){
	global $options, $upgrade_options, $themename, $manualurl;
	if(isset($_FILES["ocmx_update_file"]))
		{
			include_once(TEMPLATEPATH."/ocmx/ocmx-upgrade.php");
			ocmx_theme_update();
		}
	ocmx_header();
		?>

	<h2>OCMX Theme Upgrades &amp; Hotfixes</h2>        
    
	<?php if(!$_GET['current_tab']) : $current_tab = "1"; else : $current_tab = $_GET['current_tab']; endif; ?>
                        
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="ocmx_form" method="post" enctype="multipart/form-data">
		<p class="submit"><input name="save" type="submit" value="Save changes" /></p>		
		<input type="hidden" name="save_options" value="1" />	
		<input type="hidden" name="general_options" value="1" />
        <div id="container">
			<div class="content_box">
                <div class="ocmx-tabs">                	
                    <ul class="tabs clearfix">
                    	<li <?php if($current_tab == 1){echo "class=\"selected\"";} ?>><a href="javascript:;" id="tab_href_1">Upgrade</a></li>
                    </ul>
				</div>
               	<div class="ocmx-content">
                    <div class="header"><div></div></div>
                    <div class="content clearfix">
                        <div <?php if($current_tab == 1){echo "class=\"selected_tab\"";} else {echo "class=\"no_display\"";} ?> id="tab-1">
                            <?php fetch_options($upgrade_options); ?>
                            <br class="clearboth" />
                        </div>
                    </div>	
                    <div class="footer"><div></div></div>
					
                </div>                
			</div>  
		</div>
		<p class="submit"><input name="save" type="submit" value="Save changes" /></p>							
		
		<div class="clearfix"></div>
	</form>

<div class="clearfix"></div>
 
 <?php
};
?>