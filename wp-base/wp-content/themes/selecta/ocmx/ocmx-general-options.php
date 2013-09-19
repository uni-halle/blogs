<?php
function ocmx_general_options (){
	global $options, $general_site_options, $themename, $manualurl, $social_media_options, $auto_advert_options, $custom_advert_options;
	ocmx_header();
		?>

	<h2>OCMX Theme Options</h2>        
    
	<?php if(!$_GET['current_tab']) : $current_tab = "1"; else : $current_tab = $_GET['current_tab']; endif; ?>
                        
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="ocmx_form" method="post" enctype="multipart/form-data">
		<p class="submit"><input name="save" type="submit" value="Save changes" /></p>		
		<input type="hidden" name="save_options" value="1" />	
		<input type="hidden" name="general_options" value="1" />
        <div id="container">
			<div class="content_box">
                <div class="ocmx-tabs">                	
                    <ul class="tabs clearfix">
                    	<li <?php if($current_tab == 1){echo "class=\"selected\"";} ?>><a href="javascript:;" id="tab_href_1">General Options</a></li>
                        <li <?php if($current_tab == 2){echo "class=\"selected\"";} ?>><a href="javascript:;" id="tab_href_2">Social Options</a></li>
                        <li <?php if($current_tab == 3){echo "class=\"selected\"";} ?>><a href="javascript:;" id="tab_href_3">Menu Structure</a></li>
                    </ul>
				</div>
               	<div class="ocmx-content">
                    <div class="header"><div></div></div>
                    <div class="content clearfix">
                        <div <?php if($current_tab == 1){echo "class=\"selected_tab\"";} else {echo "class=\"no_display\"";} ?> id="tab-1">
                            <?php fetch_options($general_site_options); ?>
                            <br class="clearboth" />
                        </div>
                        <div <?php if($current_tab == 2){echo "class=\"selected_tab\"";} else {echo "class=\"no_display\"";} ?> id="tab-2">   
                            <?php fetch_options($social_media_options); ?>
                            <br class="clearboth" />		
                        </div>
                        <div <?php if($current_tab == 3){echo "class=\"selected_tab\"";} else {echo "class=\"no_display\"";} ?> id="tab-3">
                            <?php					
                                $defaults = array('type' => 'post', 'child_of' => 0, 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false);
                                menu_options($defaults); 
                            ?>
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