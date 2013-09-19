<?php
function ocmx_advert_options (){
	global $options, $themename, $manualurl, $auto_advert_options, $custom_advert_options;
	ocmx_header();
		?>
	<h2>OCMX Advert Management</h2>        
    
	<?php if(!$_GET['current_tab']) : $current_tab = "1"; else : $current_tab = $_GET['current_tab']; endif; ?>
                        
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="ocmx_form" method="post">
		<p class="submit"><input name="save" type="submit" value="Save changes" /></p>		
		<input type="hidden" name="save_options" value="1" />
        <div id="container">
			<div class="content_box">
                <div class="ocmx-tabs">                	
                    <ul class="tabs clearfix">
                    	<li <?php if($current_tab == 1){echo "class=\"selected\"";} ?>><a href="javascript:;" id="tab_href_1">Custom Ads</a></li>
                        <li <?php if($current_tab == 2){echo "class=\"selected\"";} ?>><a href="javascript:;" id="tab_href_2">3<sup>rd</sup> Party Ads</a></li>
                    </ul>
				</div>
               	<div class="ocmx-content">
                    <div class="header"><div></div></div>
                    <div class="content clearfix">
                        <div <?php if($current_tab == 1){echo "class=\"selected_tab\"";} else {echo "class=\"no_display\"";} ?> id="tab-1">    			
                             <?php fetch_options($custom_advert_options); ?>
                        </div>
                        <div <?php if($current_tab == 2){echo "class=\"selected_tab\"";} else {echo "class=\"no_display\"";} ?> id="tab-2">    			
                            <?php fetch_options($auto_advert_options); ?>
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
	}
?>