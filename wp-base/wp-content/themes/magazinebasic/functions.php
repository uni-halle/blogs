<?php

$themename = "Magazine Basic";
$shortname = "uwc";

$options = array (

	array(	"type" => "open"),
	
	array(	"name" => "Site Width",
			"desc" => "Select the width of your site.",
			"id" => $shortname."_site_width",
			"default" => "800",
			"type" => "site"),
			
	array(	"name" => "Number of Sidebars",
			"desc" => "How many sidebars would you like?",
            "id" => $shortname."_site_sidebars",
			"default" => "1",
            "type" => "sidebars"),
	
	array(  "name" => "Left Sidebar Width",
			"desc" => "What would you like your Left sidebar width to be?",
            "id" => $shortname."_sidebar_width1",
			"default" => "180",
            "type" => "left"),
			
	array(  "name" => "Right Sidebar Width",
			"desc" => "What would you like your right sidebar width to be?",
            "id" => $shortname."_sidebar_width2",
			"default" => "180",
            "type" => "right"),

	array(  "name" => "Sidebar Location",
			"desc" => "Where would you like your sidebars located?",
            "id" => $shortname."_sidebar_location",
			"default" => "oneleft",
            "type" => "location"),

	array(  "name" => "Header Logo",
			"desc" => "Would you like a logo in your header?",
            "id" => $shortname."_logo_header",
			"default" => "no",
            "type" => "logo"),	
			
	array(  "name" => "Logo or Blog Name Location",
			"desc" => "Where do you want your Logo or Blog Name located?",
            "id" => $shortname."_logo_location",
			"default" => "left",
            "type" => "logo-location"),	
			
	array(  "name" => "Search Bar",
			"desc" => "Would you like a Search Bar in your header?",
            "id" => $shortname."_search_header",
			"default" => "yes",
            "type" => "search"),
			
	array(  "name" => "User Login",
			"desc" => "Would you like to have a User Login section or widget?",
            "id" => $shortname."_user_login",
			"default" => "top",
            "type" => "login"),

	array(  "name" => "Featured Post Widget",
			"desc" => "Would you like to activate the Featured Post widget?",
            "id" => $shortname."_feature_widget",
			"default" => "yes",
            "type" => "feature"),

	array(  "name" => "Number of Posts",
			"desc" => "How many posts would you like to appear on the main page?",
            "id" => $shortname."_number_posts",
			"default" => "6",
            "type" => "posts"),

	array(  "name" => "Excerpt Word Limit",
			"desc" => "How many words do you want to appear in your main page post excerpts?",
            "type" => "excerpts"),

	array(  "id" => "uwc_excerpt_one",
			"default" => "100"),
	array(  "id" => "uwc_excerpt_two",
			"default" => "60"),
	array(  "id" => "uwc_excerpt_three",
			"default" => "40"),
				
	array(	"type" => "close")
	
);
add_action('admin_head', 'wp_admin_js');
function wp_admin_js() {
	if(stristr($_SERVER['REQUEST_URI'],'uwcheader.php')) { 
		echo '<script type="text/javascript" src="'; echo bloginfo('template_url'); echo '/js/header.js"></script>'."\n"; 
	}
	if(stristr($_SERVER['REQUEST_URI'],'uwcbasic.php')) { 
		echo '<script type="text/javascript" src="'; echo bloginfo('template_url'); echo '/js/basic.js"></script>'."\n";
	}
}

function uwc_head() {
	if (get_option('uwc_logo_header') == "yes") {
		list($w, $h) = getimagesize(get_option('uwc_logo'));
		$height = $h/2+20;
	} else {
		$height= 35;
	}
	echo "<style type='text/css'>\n";
	if (get_option('uwc_site_width') == "1024") { 
		echo "	body { width: 1024px }\n";
 		if (get_option('uwc_sidebar_width1') == "360") { 
			echo "	#sidebar { width: 360px; }\n";
			echo "	#sidebar .side-widget { width: 340px; }\n";
		} else {
			echo "	#sidebar { width: 180px; }\n";
			echo "	#sidebar .side-widget { width: 160px; }\n";
		}
		if (get_option('uwc_sidebar_width2') == "360") { 
			echo "	#secondsidebar { width: 360px; }\n";
			echo "	#secondsidebar .side-widget { width: 340px; }\n";
		} else {
			echo "	#secondsidebar { width: 180px; }\n";
			echo "	#secondsidebar .side-widget { width: 160px; }\n";
	}
		if (get_option('uwc_site_sidebars') == "2") { 
			$content = 1024 - get_option('uwc_sidebar_width1') - get_option('uwc_sidebar_width2') - 62;
			echo "	#leftcontent { width: ".$content."px; }\n";
		} else {
			$content = 1024 - get_option('uwc_sidebar_width1') - 47;
			echo "	#leftcontent { width: ".$content."px; }\n";
		}
	} else { 
		echo "	body { width: 800px }\n";
		echo "	#sidebar { width: 180px; }\n";
		echo "	#sidebar .side-widget { width: 160px; }\n";
		echo "	#secondsidebar { width: 180px; }\n";
		echo "	#secondsidebar .side-widget { width: 160px; }\n";
		if (get_option('uwc_site_sidebars') == "2") { 
			$content = 800 - 422;
			echo "	#leftcontent { width: ".$content."px; }\n";
		} else {
			$content = 800 - 221;
			echo "	#leftcontent { width: ".$content."px; }\n";
		}
	}
	if (get_option('uwc_logo_location') == "middle") {
		echo "	#title { text-align: center }\n";
		echo "	#description { clear:both;text-align: center; }\n";		
		echo "	#header-search { display:none; }\n";
	} elseif(get_option('uwc_logo_location') == "right") {
		echo "	#title { float: right; }\n";
		echo "	#description { clear:right;float: right; }\n";	
		echo "	#header-search { float: left;margin:". $height . "px 0 0 15px; }\n";
	} else {
		echo "	#title { float: left; }\n";
		echo "	#description { clear:left;float: left; }\n";
		echo "	#header-search { float: right;margin:". $height . "px 15px 0 0; }\n";
	}
	echo"</style>\n";
}
add_action('wp_head', 'uwc_head');

function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == 'uwcbasic.php' || $_GET['page'] == 'uwcheader.php' || $_GET['page'] == 'uwcfeatures.php' ) {

        if ( 'save' == $_REQUEST['action'] ) {
				
                foreach ($options as $value) {
                    if( !isset( $_REQUEST[ $value['id'] ] ) ) {  } else { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } }
				if(stristr($_SERVER['REQUEST_URI'],'&saved=true')) {
					$location = $_SERVER['REQUEST_URI'];
					} else {
					$location = $_SERVER['REQUEST_URI'] . "&saved=true";		
					}
					
				if ($_FILES["file"]["type"]){
					  	$directory = dirname(__FILE__) . "/uploads/";				
						move_uploaded_file($_FILES["file"]["tmp_name"],
						$directory . $_FILES["file"]["name"]);
						update_option('uwc_logo', get_settings('siteurl'). "/wp-content/themes/". get_settings('template')."/uploads/". $_FILES["file"]["name"]);
						}
						
                header("Location: $location");
				die;
        } 
    }
	// Set all default options
	foreach ($options as $default) {
		if(get_option($default['id'])=="") {
			update_option($default['id'],$default['default']);
		}
	}
	/*
	// Delete all default options
	foreach ($options as $default) {
		delete_option($default['id'],$default['default']);
	}
	*/	
	
	add_menu_page('Page title', 'Magazine Basic', 10, 'uwcbasic.php', 'mytheme_admin');
	add_submenu_page('uwcbasic.php', 'Page title', 'Layout', 10, 'uwcbasic.php', 'mytheme_admin');
	add_submenu_page('uwcbasic.php', 'Page title', 'Header', 10, 'uwcheader.php', 'mytheme_admin');
	add_submenu_page('uwcbasic.php', 'Page title', 'Features', 10, 'uwcfeatures.php', 'mytheme_admin');
}

function mytheme_admin() {
    global $themename, $shortname, $options;
?>
<div class="wrap">
<h2><?php echo $themename; ?> settings
<?php if(stristr($_SERVER['REQUEST_URI'],'uwcheader.php')) { echo '- Header'; } elseif(stristr($_SERVER['REQUEST_URI'],'uwcfeatures.php')) {
	echo '- Features'; } else {	echo '- Layout'; }  ?>
</h2>
<?php
if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
?>
<form method="post" id="myForm" enctype="multipart/form-data">
<div id="poststuff" class="metabox-holder">

<div id="side-info-column" class="inner-sidebar">
<div id='side-sortables' class='meta-box-sortables'>
<div id="linksubmitdiv" class="postbox " >
<h3 class='hndle'><span>Current Saved Settings </span></h3>
<div class="inside">
<div class="submitbox" id="submitlink">

<div id="minor-publishing">

<div id="misc-publishing-actions">
<div class="misc-pub-section misc-pub-section-last">
	<ul style="padding:10px 0 0 5px;">
<?php if(stristr($_SERVER['REQUEST_URI'],'uwcbasic.php')) { ?>
        <li>Site Width: <strong><?php echo get_option('uwc_site_width'); ?>px</strong></li>
        <li>Sidebars: <strong><?php echo get_option('uwc_site_sidebars'); ?></strong></li>
        <?php if (get_option('uwc_site_width') == "1024") { ?>
        <li>Left Sidebar Width: <strong><?php echo get_option('uwc_sidebar_width1'); ?>px</strong></li>
        <?php if (get_option('uwc_site_sidebars') == "2") { ?>
        <li>Right Sidebar Width: <strong><?php echo get_option('uwc_sidebar_width2'); ?>px</strong></li>
        <?php } ?>
        <?php } ?>
        <li>Sidebar Location: <strong>
		<?php
			if(get_option('uwc_sidebar_location') == "oneleft" || get_option('uwc_sidebar_location') == "oneright") {
				$barbar = true; 
			}
			if(get_option('uwc_site_sidebars') == "1" && $barbar != true) {
				echo "<span style='color:#ff0000;'>UNDEFINED</span>";
			} elseif(get_option('uwc_site_sidebars') == "2" && $barbar == true) { 
				echo "<span style='color:#ff0000;'>UNDEFINED</span>";
			} else {
				if(get_option('uwc_sidebar_location') == "twoleft" || get_option('uwc_sidebar_location') == "oneleft") { echo "Left"; } elseif(get_option('uwc_sidebar_location') == "tworight" || get_option('uwc_sidebar_location') == "oneright") { 
					echo "Right"; 
				} else {
					echo "Separate"; 
				}
			}
		?>
        </strong></li>
        <li>Number of Posts: <strong><?php echo get_option('uwc_number_posts'); ?></strong></li>
        <li>Row One Limit: <strong><?php echo get_option('uwc_excerpt_one'); ?> words</strong></li>
        <li>Row Two Limit: <strong><?php echo get_option('uwc_excerpt_two'); ?> words</strong></li>
        <li>Row Three Limit: <strong><?php echo get_option('uwc_excerpt_three'); ?> words</strong></li>

<?php } ?>
<?php if(stristr($_SERVER['REQUEST_URI'],'uwcheader.php')) { ?>
        <li>Header Logo: <strong><?php echo ucwords(get_option('uwc_logo_header')); ?></strong></li>
        <li><?php if (get_option('uwc_logo_header') == "yes") { echo "Logo"; } else { echo "Blog Name"; } ?> Location: <strong><?php echo ucwords(get_option('uwc_logo_location')); ?></strong></li>
        <li>Search Bar: <strong><?php if(get_option('uwc_logo_location') == "middle") { echo "No"; } else { echo ucwords(get_option('uwc_search_header')); } ?></strong></li>
<?php } ?>
<?php if(stristr($_SERVER['REQUEST_URI'],'uwcfeatures.php')) { ?>
        <li>User Login: <strong><?php if(get_option('uwc_user_login') == "topwidget") { echo "Top & Widget"; } else { echo ucwords(get_option('uwc_user_login')); } ?></strong></li>
        <li>Featured Post Widget: <strong><?php echo ucwords(get_option('uwc_feature_widget')); ?></strong></li>
<?php } ?>
   	</ul>
</div>
</div>

</div>

<div id="major-publishing-actions">

<div id="delete-action">
</div>

<div id="publishing-action">
    <input name="save" type="submit" class="button-primary" value="Save changes" />    
	<input type="hidden" name="action" value="save" />
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
</div>
</div>
</div></div>

<div id="post-body" class="has-sidebar">
<div id="post-body-content" class="has-sidebar-content">

<?php
if(stristr($_SERVER['REQUEST_URI'],'uwcheader.php')) {
	foreach ($options as $value) { 
		switch ( $value['type'] ) {

			case "logo":
			?>
			<div id="logodiv" class="stuffbox">
				<h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
				<div class="inside">
					<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="yes"<?php if ( get_settings( $value['id'] ) == "yes") { echo " checked"; } ?> onClick="showMe()" />&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="no"<?php if ( get_settings( $value['id'] ) == "no") { echo " checked"; } ?> onClick="showMe()" />&nbsp;No
		   			<p><small><?php echo $value['desc']; ?></small></p>
                    <div id="headerLogo">
                        Choose a file to upload: <input type="file" name="file" id="file" />
                        <?php if(get_option('uwc_logo')) { echo '<div><img src="'; echo get_option('uwc_logo'); echo '"  style="margin-top:10px;border:1px solid #aaa;padding:10px;" /></div>'; } ?> 
	        		</div>
            	</div>
            </div>
   			<?php
			break;
			
			case "logo-location":
			?>
			<div id="locationdiv" class="stuffbox">
				<h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
				<div class="inside">
					<table>
						<tr>
							<td style="padding-right: 15px;">
								<img src="<?php bloginfo('template_url'); ?>/images/admin/logoleft.png" alt="Left" />
							</td>
							<td style="padding-right: 15px;">
								<img src="<?php bloginfo('template_url'); ?>/images/admin/logoright.png" alt="Right" />
							</td>
							<td style="padding-right: 15px;">
								<img src="<?php bloginfo('template_url'); ?>/images/admin/logomiddle.png" alt="Centered" />
							</td>
						</tr>
						<tr>
							<td align="center" style="padding-right: 15px;">
								<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="left"<?php if ( get_settings( $value['id'] ) == "left") { echo " checked"; } ?> onClick="showMe()" />
							</td>
							<td align="center" style="padding-right: 15px;">
								<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="right"<?php if ( get_settings( $value['id'] ) == "right") { echo " checked"; } ?> onClick="showMe()" />
							</td>
							<td align="center" style="padding-right: 15px;">
								<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="middle"<?php if ( get_settings( $value['id'] ) == "middle") { echo " checked"; } ?> onClick="showMe()" />
							</td>
						</tr>
					</table>
		   			<p><small><?php echo $value['desc']; ?></small></p>
				</div>
             </div>
			<?php break;
			
			case "search":
			?>
			<div id="searchdiv" class="stuffbox">
				<h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
				<div class="inside"<span id="searchHeader"><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="yes"<?php if ( get_settings( $value['id'] ) == "yes") { echo " checked"; } ?> />&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="no"<?php if ( get_settings( $value['id'] ) == "no") { echo " checked"; } ?> />&nbsp;No</span><span id="noSearch"><em><strong>A Search Bar cannot be shown in the header when Centered is selected for the Logo or Blog Name location.</strong></em></span>
                <p><small><?php echo $value['desc']; ?></small></p>
            	</div>
            </div>
			<?php
			break;	
				
		}
	}	
	?>
    			</div></div>
<?php	
} elseif(stristr($_SERVER['REQUEST_URI'],'uwcfeatures.php')) {

	foreach ($options as $value) { 
		switch ( $value['type'] ) {

			case "login":
			?>
			<div id="locationdiv" class="stuffbox">
				<h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
				<div class="inside"><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="top"<?php if ( get_settings( $value['id'] ) == "top") { echo " checked"; } ?> />&nbsp;Top&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="widget"<?php if ( get_settings( $value['id'] ) == "widget") { echo " checked"; } ?> />&nbsp;Widget&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="topwidget"<?php if ( get_settings( $value['id'] ) == "topwidget") { echo " checked"; } ?> />&nbsp;Top & Widget&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="none"<?php if ( get_settings( $value['id'] ) == "none") { echo " checked"; } ?> />&nbsp;None&nbsp;&nbsp;&nbsp;&nbsp;
                <p><small><?php echo $value['desc']; ?></small></p>
            	</div>
            </div>
			<?php break;

			case "feature":
			?>
			<div id="locationdiv" class="stuffbox">
				<h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
				<div class="inside"><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="yes"<?php if ( get_settings( $value['id'] ) == "yes") { echo " checked"; } ?> />&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="no"<?php if ( get_settings( $value['id'] ) == "no") { echo " checked"; } ?> />&nbsp;No&nbsp;&nbsp;&nbsp;&nbsp;
                <p><small><?php echo $value['desc']; ?></small></p>
            	</div>
            </div>
			<?php break;
		}
	}
	?>
    			</div></div>
<?php	
} else {
	foreach ($options as $value) { 
    	switch ( $value['type'] ) {
	
			case "site":
			?>
			<div id="locationdiv" class="stuffbox">
				<h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
				<div class="inside"><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="800"<?php if ( get_settings( $value['id'] ) == "800") { echo " checked"; } ?> onClick="showMe()" />&nbsp;800px&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="1024"<?php if ( get_settings( $value['id'] ) == "1024") { echo " checked"; } ?> onClick="showMe()" />&nbsp;1024px
                <p><small><?php echo $value['desc']; ?></small></p>
               	</div>
            </div>

			<?php 
			break;
			
			case "sidebars":
			?>
			<div id="locationdiv" class="stuffbox">
				<h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
				<div class="inside"><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="1"<?php if ( get_settings( $value['id'] ) == "1") { echo " checked"; } ?> onClick="showMe()" />&nbsp;1&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="2"<?php if ( get_settings( $value['id'] ) == "2") { echo " checked"; } ?> onClick="showMe()" />&nbsp;2
                <p><small><?php echo $value['desc']; ?></small></p>
            	</div>
            </div>
			<?php 
			break;
			
			case "left":
			?>
			<div id="leftWidth">
                <div id="locationdiv" class="stuffbox">
                    <h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
                    <div class="inside"><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="180"<?php if ( get_settings( $value['id'] ) == "180") { echo " checked"; } ?> />&nbsp;180px&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="360"<?php if ( get_settings( $value['id'] ) == "360") { echo " checked"; } ?> />&nbsp;360px
                    <p><small><?php echo $value['desc']; ?></small></p>
                    </div>
                </div>
			</div>
			<?php
			break;
			   
			case "right":
			?>
			<div id="rightWidth">
                <div id="locationdiv" class="stuffbox">
                    <h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
                    <div class="inside"><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="180"<?php if ( get_settings( $value['id'] ) == "180") { echo " checked"; } ?> />&nbsp;180px&nbsp;&nbsp;&nbsp;&nbsp;<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="360"<?php if ( get_settings( $value['id'] ) == "360") { echo " checked"; } ?> />&nbsp;360px
                    <p><small><?php echo $value['desc']; ?></small></p>
                    </div>
                </div>
			</div>           
			<?php
			break;
	
			case "location":
			?>
			<div id="locationdiv" class="stuffbox">
              	<h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
                	<div class="inside">
					<div id="oneSidebar">
					<table>
						<tr>
							<td style="padding-right: 15px;">
								<img src="<?php bloginfo('template_url'); ?>/images/admin/oneleft.png" alt="One Left" />
							</td>
							<td style="padding-right: 15px;">
								<img src="<?php bloginfo('template_url'); ?>/images/admin/oneright.png" alt="One Right" />
							</td>
						</tr>
						<tr>
							<td align="center" style="padding-right: 15px;">
								<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="oneleft"<?php if ( get_settings( $value['id'] ) == "oneleft") { echo " checked"; } ?> />
							</td>
							<td align="center" style="padding-right: 15px;">
								<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="oneright"<?php if ( get_settings( $value['id'] ) == "oneright") { echo " checked"; } ?> />
							</td>
						</tr>
					</table>
				</div>
				<div id="twoSidebar">
					<table>
						<tr>
							<td style="padding-right: 15px;">
								<img src="<?php bloginfo('template_url'); ?>/images/admin/twoleft.png" alt="Two Left" />
							</td>
							<td style="padding-right: 15px;">
								<img src="<?php bloginfo('template_url'); ?>/images/admin/tworight.png" alt="Two Right" />
							</td>
							<td style="padding-right: 15px;">
								<img src="<?php bloginfo('template_url'); ?>/images/admin/twoseparate.png" alt="Two Separate" />
							</td>
						</tr>
						<tr>
							<td align="center" style="padding-right: 15px;">
								<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="twoleft"<?php if ( get_settings( $value['id'] ) == "twoleft") { echo " checked"; } ?> />
							</td>
							<td align="center" style="padding-right: 15px;">
								<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="tworight"<?php if ( get_settings( $value['id'] ) == "tworight") { echo " checked"; } ?> />
							</td>
							<td align="center" style="padding-right: 15px;">
								<input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="radio" value="twoseparate"<?php if ( get_settings( $value['id'] ) == "twoseparate") { echo " checked"; } ?> />
							</td>
						</tr>
					</table>
				</div>
				<p><small><?php echo $value['desc']; ?></small></p>
                    </div>
                </div>
			<?php break;

			case "posts":
			?>
            <div id="postsdiv" class="stuffbox">
                <h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
                <div class="inside"><input  name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" size="3" type="text" value="<?php echo get_option( $value['id'] ); ?>" />
                <p><small><?php echo $value['desc']; ?></small></p>
                </div>
            </div>
			<?php
			break;
			
			case "excerpts":
			?>
            <div id="excerptsdiv" class="stuffbox">
                <h3><label for="link_url"><?php echo $value['name']; ?></label></h3>
                <div class="inside">
                Row One: <input  name="uwc_excerpt_one" id="uwc_excerpt_one" size="3" type="text" value="<?php echo get_option('uwc_excerpt_one'); ?>" />&nbsp;&nbsp;
                Row Two: <input  name="uwc_excerpt_two" id="uwc_excerpt_two" size="3" type="text" value="<?php echo get_option('uwc_excerpt_two'); ?>" />&nbsp;&nbsp;
                Row Three +: <input  name="uwc_excerpt_three" id="uwc_excerpt_three" size="3" type="text" value="<?php echo get_option('uwc_excerpt_three'); ?>" />
                <p><small><?php echo $value['desc']; ?></small></p>
                </div>
            </div>
			<?php
			break;			
		} 
	}
	?>
    			</div></div>
<?php	
}
?>



</form>
</div>
<?php
}

add_action('admin_menu', 'mytheme_add_admin'); 
?>
<?php include(TEMPLATEPATH.'/functions/widget_login.php'); ?>
<?php
if (get_option('uwc_feature_widget') == "yes") {
	include(TEMPLATEPATH.'/functions/widget_feature.php'); 
}
?>
<?php
if (function_exists("register_sidebar")) {
register_sidebar(array(
'name' => 'Sidebar One',
	'before_widget' => '<div class="side-widget">',
	'after_widget' => '</div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>',
));

if (get_option('uwc_site_sidebars') == "2") {
	register_sidebar(array(
	'name' => 'Sidebar Two',
	'before_widget' => '<div class="side-widget">',
	'after_widget' => '</div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>',
	));
	}
}

function csv_tags() {
    $posttags = get_the_tags();
    foreach((array)$posttags as $tag) {
        $csv_tags .= $tag->name . ',';
    }
    echo '<meta name="keywords" content="'.$csv_tags.'" />';
}

function theme_excerpt($num) {
	global $more;
	$more = 1;
	$link = get_permalink();
	$limit = $num+1;
	$excerpt = explode(' ', strip_tags(get_the_content()), $limit);
	array_pop($excerpt);
	$excerpt = implode(" ",$excerpt).'...<a href="'.$link.'" class="readmore"> &raquo;</a>';
	echo '<p>'.$excerpt.'</p>';
	$more = 0;
}

function metaDesc() {
	$content = strip_tags(get_the_content());
	if (strlen($content) < 155) {
		echo $content;
	} else {
		$desc = substr($content,0,155);
		echo $desc."...";
	}
}

function getImage($num) {
	global $more;
	$more = 1;
	$link = get_permalink();
	$content = get_the_content();
	$count = substr_count($content, '<img src=');
	$start = 0;
	for($i=1;$i<=$count;$i++) {
		$imgBeg = strpos($content, '<img', $start);
		$post = substr($content, $imgBeg);
		$imgEnd = strpos($post, '>');
		$postOutput = substr($post, 0, $imgEnd+1);
		$result = preg_match('/width="([0-9]*)" height="([0-9]*)"/', $postOutput, $matches);
		if ($result) {
			$pagestring = $matches[0];
			$image[$i] = str_replace($pagestring, "", $postOutput);
		} else {
			$image[$i] = $postOutput;
		}
		$start=$imgEnd+1;
	}
	if(stristr($image[$num],'<img src=')) { echo '<a href="'.$link.'">'.$image[$num]."</a>"; }
	$more = 0;
}

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
         <?php echo get_avatar($comment,$size='36',$default='<path_to_url>' ); ?>

         <?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

      <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars(get_comment_link( $comment->comment_ID )) ?>">
<?php printf(__('%1$s at %2$s'), get_comment_date(),get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?></div>

      <?php comment_text() ?>
		<?php if($args['max_depth']!=$depth) { ?>
      <div class="reply">
         <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
      <?php } ?>
     <div class="commentnumber">#<?php comment_ID(); ?></div>

     </div>
<?php
}
?>