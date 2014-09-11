<?php
function ocmx_install_options (){
	global $options, $general_site_options, $themename, $manualurl, $social_media_options, $auto_advert_options, $custom_advert_options;
	ocmx_header();
		?>

	<h2>OCMX-Live Theme Options</h2>        
    
	<?php if(!$_GET['current_tab']) : $current_tab = "1"; else : $current_tab = $_GET['current_tab']; endif; ?>
                        
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="ocmx_form" method="post" enctype="multipart/form-data">
		<input type="hidden" name="save_options" value="1" />	
		<input type="hidden" name="general_options" value="1" />
        <div id="container">
			<div class="content_box">
                <div class="ocmx-tabs">                	
                    <ul class="tabs clearfix">
                    	<li class="selected"><a href="#">Install OCMX</a></li>
                    </ul>
				</div>
               	<div class="ocmx-content">
                    <div class="header"><div></div></div>
                    <div class="content clearfix">
                        <h3>Welcome to your Obox Theme Options</h3>
                        <p>Thank you for purchasing this Obox Signature Series Theme. Due to the customized functionality this theme provides, we must take a moment to install the your new features.</p>
                        <p>
                            <strong>The following options will be added to your theme:</strong>
                            <ul>
                                <li>OCMX General Options</li>
                                <li>OCMX Social Media Widgets and Links</li>
                                <li>Advert Management</li>
                                <li>Advances Comment Functionality and Storage</li>
                            </ul>
                        </p>
                        <br />
						<p>Click <a href="<?php  $_SERVER['REQUEST_URI']; ?>admin.php?page=functions.php&amp;install_ocmx=1" class="std_link">here</a> to begin the install, it shouldn't take more than a minute.</p>
                    </div>	
                    <div class="footer"><div></div></div>
					
                </div>                
			</div>  
		</div>				
		
		<div class="clearfix"></div>
	</form>

<div class="clearfix"></div>
 
 <?php

};
?>