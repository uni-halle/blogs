<?php
//ini_set('display_errors', '1');
//error_reporting(E_ALL);
/*
Plugin Name: DMSGuestbook
Plugin URI: http://danielschurter.net/
Description: Create and customize your own guestbook.
Version: 1.17.4
Author: Daniel M. Schurter
Author URI: http://danielschurter.net/
*/

/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Deactivate the role by setting "0" if the admin panel doesn't appear. | Default: 1 */
define('ROLE', "1");

/* All single, alphanumeric (a-z, 0-9) option fields are quoted with base64 by setting 1. | Default: 0
These fields are affected:
- formposlink
- additional_option_title
- mandatory_char
- forwardchar
- backwardchar
*/
define('BASE64', "0");

/* DMSGuestbook version */
define('DMSGUESTBOOKVERSION', "1.17.4");
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	
	$_REQUEST['restore_options'] 	= (isset($_REQUEST['restore_options'])) ? $_REQUEST['restore_options'] : '';
	
	/* menu (DMSGuestbook, Manage) */
	add_action('admin_menu', 'add_dmsguestbook');

	/* language */
	$currentLocale = get_locale();
	if(!empty($currentLocale)) {
	$moFile = dirname(__FILE__) . "/language/mo/" . $currentLocale . ".mo";
	if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('dmsguestbook', $moFile);
	}

	function add_dmsguestbook() {
		$options = create_options();
		/* set the rights to administrator if option db is empty */
		if($options["role1"]=="" || $options["role2"]=="" || $options["role3"]=="") {
		 $options["role1"]="Administrator";
		 $options["role2"]=="Administrator";
		 $options["role3"]=="Administrator";
		}

		$Role1 = CheckRole($options["role1"],0);
		$Role2 = CheckRole($options["role2"],0);
		$Role3 = CheckRole($options["role3"],0);

		/* If role isn't set change it to 0 */
		if(ROLE == 0) {
		$Role1 = 0;
		$Role2 = 0;
		$Role3 = 0;
		}

		$maxRole = max($Role1,$Role2,$Role3);

		add_menu_page(__('Options', 'dmsguestbook'), __('<span style=\'font-size:12px;\'>DMSGuestbook</span>', 'dmsguestbook'), $maxRole,
		'dmsguestbook', 'dmsguestbook_meta_description_option_page', '../wp-content/plugins/dmsguestbook/img/guestbook.png');

		if(current_user_can("level_" . $Role2) || ROLE == 0) {
		add_submenu_page( 'dmsguestbook' , __('Entries', 'dmsguestbook'), __('Entries', 'dmsguestbook'), $Role2,
		'Entries', 'dmsguestbook2_meta_description_option_page');
		}

		if(current_user_can("level_" . $Role3) || ROLE == 0) {
		add_submenu_page( 'dmsguestbook' , __('Spam', 'dmsguestbook'), __('Spam', 'dmsguestbook'), $Role3,
		'Spam', 'dmsguestbook5_meta_description_option_page');
		}

		if(current_user_can("level_" . $Role1) || ROLE == 0) {
		add_submenu_page( 'dmsguestbook' , __('phpinfo', 'dmsguestbook'), __('phpinfo', 'dmsguestbook'), $Role1,
		'phpinfo', 'dmsguestbook3_meta_description_option_page');
		}
	}



	/* create db while the activation process */
	add_action('activate_dmsguestbook/admin.php', 'dmsguestbook_install');

	/* version */
	add_action('wp_head', 'addversion');
	function addversion() {
		echo "<meta name=\"DMSGuestbook\" content=\"".DMSGUESTBOOKVERSION."\" />\n";
	}

	/* restore options*/
	if($_REQUEST['restore_options']==1 && $_REQUEST['restore_data']!="") {
	$restore = str_replace("\r\n", "[br]", $_REQUEST['restore_data']);
	update_option("DMSGuestbook_options", $restore);
	message("<b>" . __("Options have been saved", "dmsguestbook") . "...</b>", 300, 800);
	}

	if($_REQUEST['restore_options']==1 && $_REQUEST['restore_data']=="") {
	message("<b>" . __("Options were not saved, text box is empty", "dmsguestbook") . "...</b>", 300, 800);
	}


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

/* DMSGuestbook adminpage main function */

function dmsguestbook_meta_description_option_page() {

	$dmsguestbook_options 			= (isset($dmsguestbook_options)) ? $dmsguestbook_options : '';
	$num_rows_option 				= (isset($num_rows_option)) ? $num_rows_option : '';
	$_SESSION['missing_options'] 	= (isset($_SESSION['missing_options'])) ? $_SESSION['missing_options'] : '';
	$_SESSION['fixed_update']	 	= (isset($_SESSION['fixed_update'])) ? $_SESSION['fixed_update'] : '';
	$_REQUEST['dbs'] 				= (isset($_REQUEST['dbs'])) ? $_REQUEST['dbs'] : '';  	
	$_REQUEST['basic'] 				= (isset($_REQUEST['basic'])) ? $_REQUEST['basic'] : '';
	$_REQUEST['advanced'] 			= (isset($_REQUEST['advanced'])) ? $_REQUEST['advanced'] : '';
	$_REQUEST['action'] 			= (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	$location 						= (isset($location)) ? $location : '';
	$fixed_update 					= (isset($fixed_update)) ? $fixed_update : '';
	
	$url=get_bloginfo('wpurl');

	/* initialize */
	$options 			= create_options();
	$options_name 		= default_options_array();

	/* global var for DMSGuestbook and option database */
	global $wpdb;

	$table_name = $wpdb->prefix . "dmsguestbook";
	$table_option = $wpdb->prefix . "options";
	$table_posts = $wpdb->prefix . "posts";
	update_db_fields();
?>

	<!-- header -->
	<div class="wrap">
    <h2>DMSGuestbook Option</h2>
    <ul>
    <li>1.) <?php echo __("Create a page where you want to display the DMSGuestbook.", "dmsguestbook"); ?></li>
    <li>2.) <?php echo __("Save the page and assign it under \"Guestbook settings\" -> \"Basic\".", "dmsguestbook"); ?></li>
    <li>3.) <?php echo __("Customize the guestbook to your desire!", "dmsguestbook"); ?></li>
    </ul>
	<br />

<?php

	/* if option(s) are missing */
	if(strlen($_SESSION['missing_options'])>0) {
		$_SESSION['fixed_update'] = get_option("DMSGuestbook_options") . $_SESSION["missing_options_fixed_update"];
			echo "<b style='width:100%;color:#cc0000;'>" . __("One or more options are missing.", "dmsguestbook") . "</b><br />";
			echo "<form name='form0' method='post' action='$location'>
  			<input name='action' value='fix_update' type='hidden' />
  			<input name='fixed' value='$fixed_update' type='hidden' />
  			<input class='button-secondary action' style='font-weight:bold; margin:10px 0px; width:250px;' type='submit' value='" . __("Update options database", "dmsguestbook") . "' />
			</form>";
	missing_options();
	unset($_SESSION["missing_options"]);
	}

	/* save the fixed options */
	if($_REQUEST['action']=="fix_update") {
	$restore = str_replace("\r\n", "[br]", $_SESSION['fixed_update']);
	update_option("DMSGuestbook_options", $restore);
	message("<b>" . __("Update database", "dmsguestbook") . "...</b>", 300, 800);
	echo "<meta http-equiv='refresh' content='0; URL=$location'>";
	}



	/* user can create new DMSGuestbook database if these failed during the installation. */
    if($_REQUEST['action']=="createnew") {
		$sql = $wpdb->query("CREATE TABLE " . $table_name . " (
	  	id mediumint(9) NOT NULL AUTO_INCREMENT,
	  	name varchar(50) DEFAULT '' NOT NULL,
	  	email varchar(50) DEFAULT '' NOT NULL,
	  	gravatar varchar(32) DEFAULT '' NOT NULL,
	  	url varchar(50) DEFAULT '' NOT NULL,
	  	date int(10) NOT NULL,
	  	ip varchar(15) DEFAULT '' NOT NULL,
	  	message longtext NOT NULL,
	  	guestbook int(2) DEFAULT '0' NOT NULL,
	  	spam int(1) DEFAULT '0' NOT NULL,
	  	additional varchar(50) NOT NULL,
	  	flag int(2) NOT NULL,
	  	UNIQUE KEY id (id)
	  	)" . mysql_real_escape_string($_REQUEST['collate']) . "");
	  	$abspath = str_replace("\\","/", ABSPATH);
	  	require_once($abspath . 'wp-admin/upgrade-functions.php');
	  	dbDelta($sql);
	  	message("<b>$table_name " . __("was created", "dmsguestbook") . "...</b>", 300, 800);
	}

	/* user can delete DMSGuestbook database after the confirmation */
	if($_REQUEST['action']=="delete" && $_REQUEST['delete']=="yes, i am sure") {
		$wpdb->query('DROP TABLE IF EXISTS ' . $table_name);
		$abspath = str_replace("\\","/", ABSPATH);
	  	require_once($abspath . 'wp-admin/upgrade-functions.php');
	  	message("<b>$table_name " . __("was deleted", "dmsguestbook") . "...</b>",300,800);
	}

	/* user can create DMSGuestbook option if the failed during the installation. */
	if($_REQUEST['action']=="createoption") {
		initialize_option();
	  	message("<b>" . __("DMSGuestbook options", "dmsguestbook") . "<br /></b><br />" . __("Don't forget to set the page id.", "dmsguestbook"),260,800);
		echo "<meta http-equiv='refresh' content='0; URL=$location'>";
	}

	/* user can delete all DMSGuestbook_ entries in DMSGuestbook option after confirmation. */
    if($_REQUEST['action']=="deleteoption" && $_REQUEST['confirm_delete_option']=="delete") {
		$wpdb->query('DELETE FROM ' . $table_option . ' WHERE option_name LIKE "DMSGuestbook_%"');
	  	$abspath = str_replace("\\","/", ABSPATH);
	  	require_once($abspath . 'wp-admin/upgrade-functions.php');
	  	message("<b>" . __("All DMSGuestbook options were deleted", "dmsguestbook") . "...</b>",300,800);
	}
	?>



<script type="text/javascript">
//<![CDATA[
function addLoadEvent(func) {if ( typeof wpOnload!='function'){wpOnload=func;}else{ var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}}
//]]>
</script>
<script type="text/javascript" src="../wp-content/plugins/dmsguestbook/js/jquery-1.7.2.js"></script>
<link rel="stylesheet" media="screen" type="text/css" href="../wp-content/plugins/dmsguestbook/js/colorpicker/css/colorpicker.css" />
<script type="text/javascript" src="../wp-content/plugins/dmsguestbook/js/colorpicker/js/colorpicker.js"></script>
<link type="text/css" href="../wp-content/plugins/dmsguestbook/js/jquery-ui/css/custom-theme/jquery-ui-1.8.5.custom.css" rel="Stylesheet" />
<link type="text/css" href="../wp-content/plugins/dmsguestbook/js/jquery-simple-tooltip-0.9.1/style.css" rel="Stylesheet" />
<script type="text/javascript" src="../wp-content/plugins/dmsguestbook/js/jquery-ui/js/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="../wp-content/plugins/dmsguestbook/js/jquery-simple-tooltip-0.9.1/jquery.simpletooltip-min.js"></script>
<script>
jQuery(document).ready(function(){
	jQuery( "#dmsguestbook-menu" ).accordion({
		clearStyle:true,
		collapsible:true,
		active:-1
	});

	jQuery("a.tooltiplink").simpletooltip({
		margin: 10
	});

	jQuery('#bordercolor1').ColorPicker({
		onChange: function (hsb, hex, rgb) {
		jQuery('#Color2_div').css('background-color', '#' + hex);
		jQuery('#bordercolor1').val(hex);
		}
	})
	jQuery('#bordercolor2').ColorPicker({
		onChange: function (hsb, hex, rgb) {
		jQuery('#Color3_div').css('background-color', '#' + hex);
		jQuery('#bordercolor2').val(hex);
		}
	})
	jQuery('#navigationcolor').ColorPicker({
		onChange: function (hsb, hex, rgb) {
		jQuery('#Color4_div').css('background-color', '#' + hex);
		jQuery('#navigationcolor').val(hex);
		}
	})
	jQuery('#separatorcolor').ColorPicker({
		onChange: function (hsb, hex, rgb) {
		jQuery('#Color1_div').css('background-color', '#' + hex);
		jQuery('#separatorcolor').val(hex);
		}
	})
	jQuery('#fontcolor1').ColorPicker({
		onChange: function (hsb, hex, rgb) {
		jQuery('#Color5_div').css('background-color', '#' + hex);
		jQuery('#fontcolor1').val(hex);
		}
	})
	jQuery('#captcha_color').ColorPicker({
		onChange: function (hsb, hex, rgb) {
		jQuery('#Color6_div').css('background-color', '#' + hex);
		jQuery('#captcha_color').val(hex);
		}
	})

});
</script>

<?php
$collaps_dashboard="<a href='admin.php?page=dmsguestbook'>
<img src='../wp-content/plugins/dmsguestbook/img/dashboard.png'><b>" . __("Dashboard", "dmsguestbook") . "</b></a>";
$collaps_dbs="<a href='admin.php?page=dmsguestbook&dbs=1'>
<img src='../wp-content/plugins/dmsguestbook/img/server.png'><b>" . __("Database settings", "dmsguestbook") . "</b></a>";
$collaps_basic="<a href='admin.php?page=dmsguestbook&basic=1'>
<img src='../wp-content/plugins/dmsguestbook/img/basic.png'><b>" . __("Guestbook settings", "dmsguestbook") . "</b></a>";
$collaps_advanced="<a href='admin.php?page=dmsguestbook&advanced=1'>
<img src='../wp-content/plugins/dmsguestbook/img/language.png'><b>" . __("Language settings", "dmsguestbook") . "</b></a>";
?>

<!-- table for DMSGuestbook and DMSGuestbook option environment-->
<table style="width:100%;">
		<tr>
			<td><?php echo $collaps_dashboard;?></td>
			<td><?php echo $collaps_dbs;?></td>
			<td><?php echo $collaps_basic?></td>
			<td><?php echo $collaps_advanced?></td>
		</tr>
</table>
<br /><br /><br />

<?php
/* dashboard */
if($_REQUEST['page']=="dmsguestbook" && ($_REQUEST['dbs']!=1 && $_REQUEST['basic']!=1 && $_REQUEST['advanced']!=1)) {

	$dashcolor="#21759B";
	function convert($convert) {
		if($convert==1) {
		return("Yes");
		}
		else
			{
			return("No");
			}
	}

	if(function_exists("gd_info")) {
	$gd_array = gd_info();
	$gd_version 			= $gd_array["GD Version"];
	$gd_freetype 			= convert($gd_array["FreeType Support"]);
	$gd_freetype_linkage 	= $gd_array["FreeType Linkage"];
	$gd_png		 			= convert($gd_array["PNG Support"]);
	}


	if(CheckAkismet() !="") {
	$akismet_notify = "Akismet: <span style='color:$dashcolor;'>" . __("Yes", "dmsguestbook") . "</span>";
	} else {
		   $akismet_notify = "Akismet: <span style='color:$dashcolor;'>" . __("No", "dmsguestbook") . "</span>";
		   }

	$abspath = str_replace("\\","/", ABSPATH);
	$sqlversion 			= $wpdb->get_var("SELECT VERSION()");
	$css_writable 			= convert(is_writable($abspath . "wp-content/plugins/dmsguestbook/dmsguestbook.css"));
	$ttf_readable			= convert(is_readable($abspath . "wp-content/plugins/dmsguestbook/captcha/xfiles.ttf"));
	if(ini_get('memory_limit')) {
	$memory_limit = ini_get('memory_limit');
	}
	else {
	     $memory_limit = "";
	     }

	$result_spam 		= $wpdb->query("SELECT * FROM $table_name WHERE spam = '1'");
	$result_post 		= $wpdb->query("SELECT * FROM $table_name WHERE spam = '0'");
	$result_approval 	= $wpdb->query("SELECT * FROM $table_name WHERE flag = '1'");

	echo "<table style='width:100%;' class='widefat comments' cellspacing='0'>
		<thead>
		<tr>
		<th style='padding:5px 5px 5px 5px;width:25%;'>" . __("Dashboard", "dmsguestbook") . "</th>
		<th style='padding:0px 5px 0px 5px;width:25%;'></th>
		<th style='padding:0px 5px 0px 5px;width:50%;'></th>
		</tr>
		</thead>
		<tr>
			<td style='padding:20px;'>
			<b style='font-size:16px;'><a href='admin.php?page=Entries'>$result_post</a> <span style='color:#008000;'>" . __("entries", "dmsguestbook") . "</span></b><br />
			<b style='font-size:16px;'><a href='admin.php?page=Entries&approval=1'>$result_approval</a> <span style='color:#ffa500;'>" . __("waiting for approval", "dmsguestbook") . "</span></b><br />
			<b style='font-size:16px;'><a href='admin.php?page=Spam'>$result_spam</a> <span style='color:#ff0000;'>" . __("spam", "dmsguestbook") . "</span></b><br />
			<div style='height:40px;'></div>
			<b style='font-size:14px;text-decoration:underline;'>" . __("Server Settings", "dmsguestbook") . "</b>
			<br />" .
			__("Server", "dmsguestbook") . ": <span style='color:$dashcolor;'>$_SERVER[SERVER_SOFTWARE]</span><br />" .
			__("MYSQL Server", "dmsguestbook") . ": <span style='color:$dashcolor;'>$sqlversion</span><br />" .
			__("Memory Limit", "dmsguestbook") . ": <span style='color:$dashcolor;'>$memory_limit</span><br />
			<br />
			<b style='font-size:14px;text-decoration:underline;'>" . __("Graphic Settings", "dmsguestbook") . "</b>
			<br />" .
			__("GD Version", "dmsguestbook") . ": </b><span style='color:$dashcolor;'>$gd_version</span><br />" .
			__("Freetype Support", "dmsguestbook") . ": <span style='color:$dashcolor;'>$gd_freetype</span><br />" .
			__("Freetype Linkage", "dmsguestbook") . ": <span style='color:$dashcolor;'>$gd_freetype_linkage</span><br />" .
			__("PNG Support", "dmsguestbook") . ": <span style='color:$dashcolor;'>$gd_png</span><br />
			<br />
			<b style='font-size:14px;text-decoration:underline;'>" . __("Permissions", "dmsguestbook") . "</b>
			<br />" .
			__("Database settings", "dmsguestbook") . ": <span style='color:$dashcolor;'>$options[role1]</span><br />" .
			__("Guestbook settings", "dmsguestbook") . ": <span style='color:$dashcolor;'>$options[role1]</span><br />" .
			__("Language settings", "dmsguestbook") . ": <span style='color:$dashcolor;'>$options[role1]</span><br />" .
			__("Post settings", "dmsguestbook") . ": <span style='color:$dashcolor;'>$options[role2]</span><br />" .
			__("Spam settings", "dmsguestbook") . ": <span style='color:$dashcolor;'>$options[role3]</span><br />" .
			__("phpinfo", "dmsguestbook") . ": <span style='color:$dashcolor;'>$options[role1]</span><br />
			</td>

			<td style='padding:20px'>
			<b style='font-size:14px;text-decoration:underline;'>" . __("Miscellaneous", "dmsguestbook") . "</b><br />
			$akismet_notify<br />" .
			__("CSS file writable", "dmsguestbook") . ": <span style='color:$dashcolor;'>$css_writable</span> <span style='font-size:8px;'>1)</span><br />" .
			__("xfiles.ttf readable", "dmsguestbook") . ": <span style='color:$dashcolor;'>$ttf_readable</span><br />
			<br />" .
			__("Captcha Image", "dmsguestbook") . ":<br /><img src='../wp-content/plugins/dmsguestbook/captcha/captcha.php' /> <span style='font-size:8px;'>2)</span><br />
			<br />
			<br />
			<div style='background-color:#eeeeee;padding:5px;'>
			<span style='font-size:8px;'>1)</span>" . __("If dmsguestbook.css exists and is writable, all CSS settings will be read from it.<br />Otherwise these settings will be loaded from the database.", "dmsguestbook") . "
			<br />
			<br />
			<span style='font-size:8px;'>2)</span>" .
			__("If you don't see the image here, check the xfiles.ttf and captcha.png permission in your captcha folder.", "dmsguestbook") . "</div>
			</td>

			<td style='padding:20px;'>";
			echo "<b style='font-size:14px;text-decoration:underline;'>" . __("News", "dmsguestbook") . "</b><br />";
			include_once(ABSPATH . WPINC . '/rss.php');
			unset($rss1);
			unset($items);
			$rss1 = fetch_rss('http://www.danielschurter.net/mainsite/category/DMSGuestbook/feed/');
			$maxitems = 3;
			@$items = array_slice($rss1->items, 0, $maxitems);

			echo "<ul>";
			if (empty($items)) {
				echo "<li>" . __("No items", "dmsguestbook") . "</li>";
			}
			else {
			     foreach ( $items as $item ) :
					echo "<li><a href='$item[link]' title='$item[title]' target='_blank'>$item[title]</a>&nbsp;&nbsp;<span style='color:#666666;font-size:10px;'>". mb_substr($item['pubdate'],5 ,12) . "</span><br />
					" . mb_substr($item['description'], 0, 80) . " [...]</li>";
			     endforeach;
			     }
			echo "</ul>
			<br /><b style='font-size:14px;text-decoration:underline;'>" . __("Infos", "dmsguestbook") . "</b><br />
			<ul>
				<li><a href='http://www.danielschurter.net/mainsite/2009/03/05/dmsguestbook-faq/' target='_blank'>" . __("FAQ", "dmsguestbook") . "</a></li>
				<li><a href='http://www.danielschurter.net/mainsite/2007/07/28/dmsguestbook-10/' target='_blank'>". __("Changelog", "dmsguestbook") . "</a></li>
			</ul>

			</td>

		</tr>
	</table>";
}


if($_REQUEST['dbs']==1) {

	$Role1 = CheckRole($options["role1"],0);
	if(!current_user_can("level_" . $Role1) && ROLE != 0) {
		CheckRole($options["role1"],1);
	exit;
	}

/* dmsguestbook datatbase*/
		// search prefix_dmsguestbook
        $result = $wpdb->query("SHOW TABLES LIKE '$table_name'");
		if ($result > 0) {
			/* if prefix_dmsguestbook does exist */
			$return_dmsguestbook_database = "<b style='color:#00bb00;'>" . sprintf(__("[Status OK] %s does exist.", "dmsguestbook"), $table_name) . "</b><br /><br />" .
  			sprintf(__("Type \"yes, i am sure\" in this textfield if you want delete %s", "dmsguestbook"), $table_name) . "<br />
  			<b>" . __("All guestbook data will be lost!", "dmsguestbook") . "</b><br />
  			<form name='form0' method='post' action='$location'>
  			<input type='text' name='delete' value='' /><br />
  			<input name='action' value='delete' type='hidden' />
  			<input class='button-secondary action' style='font-weight:bold; margin:10px 0px; width:250px;' type='submit' value='". sprintf(__("delete %s", "dmsguestbook"), $table_name) . "' />
			</form>";
		} else {
		    /* if prefix_dmsguestbook isn't exist */
			$return_dmsguestbook_database = "<b style='color:#bb0000;padding:5px;'>" . sprintf(__("%s isn't exist.", "dmsguestbook"), $table_name) . "</b><br /><br />
			<form name='form0' method='post' action='$location'>
				  <select name='collate'>
				  	<option value='DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci'>utf8_unicode_ci</option>
					<option value='DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci'>utf8_general_ci</option>
					<option value=''>". __("if you use mySQL 4.0.xx or lower", "dmsguestbook") . "</option>
				</select><br />
				<input name='action' value='createnew' type='hidden' />
				<input class='button-primary action' style='font-weight:bold; margin:10px 0px; width:300px;' type='submit' value='" . __("Create", "dmsguestbook") . " $table_name)' />
			</form>" .
			__("If you want use char like &auml;,&uuml;,&ouml;... and your mysql version is lower than 4.1, be sure the language
			setting is e.g. \"de-iso-8859-1\" or similar. Check this with your mysql graphical frontenend like phpmyadmin.", "dmsguestbook") . "<br />";
		}

	$return_dmsguestbook_database_error = "<br />" . sprintf(__("If there is something wrong with my %s table:", "dmsguestbook"), $table_name) . " <a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='../wp-content/plugins/dmsguestbook/default_sql.txt' target='_blank'>" . __("Help", "dmsguestbook") . "</a>";


/* dmsguestbook options*/
	/* search all DMSGuestbook option (inform the user about the old dmsguestbook entries) */
	$query_options = $wpdb->get_results("SELECT * FROM $table_option WHERE option_name LIKE 'DMSGuestbook_%'");
	$num_rows_option = $wpdb->num_rows;

	/* search to DMSGuestbook_options */
	$query_options1 = $wpdb->get_results("SELECT * FROM $table_option WHERE option_name LIKE 'DMSGuestbook_options'");
	$num_rows_option1 = $wpdb->num_rows;

		if($num_rows_option1==1) {
		$return_dmsguestbook_options = "<b style='color:#00bb00'>" . sprintf(__("[Status OK] \"DMSGuestbook_options\" found in %s.", "dmsguestbook"), $table_option) . "</b><br />";
		}

		if($num_rows_option1==0) {
		$return_dmsguestbook_options = "<b style='color:#bb0000'>" . sprintf(__("No \"DMSGuestbook_options\" found in %s.", "dmsguestbook"), $table_option) . "</b><br />";
		}

		if($num_rows_option >= 2) {
		$return_dmsguestbook_options = "<b style='color:#bb0000'>" . sprintf(__("Notice: You have some old \"DMSGuestbook_xxxx\" rows in your %s, but this have no functionality impact.", "dmsguestbook"), $table_option) ."</b>";
		}

		$return_dmsguestbook_options .= "<form name='form0' method='post' action='$location'
			<input name='action' value='createoption' type='hidden' />
			<input class='button-secondary action' style='font-weight:bold; margin:10px 0px; width:400px;' type='submit' value='" . __("Create new DMSGuestbook options", "dmsguestbook") . "' />
		</form>
		<br /><br />
		<form name='form0' method='post' action='$location'>" .
				sprintf(__("Type \"delete\" to remove all DMSGuestbook option entries from the %s table.", "dmsguestbook"), $table_option) . "<br />
				<input type='text' name='confirm_delete_option' value='' /><br />
				<input name='action' value='deleteoption' type='hidden' />
				<input class='button-secondary action' style='font-weight:bold; margin:10px 0px; width:400px;' type='submit' value='" . __("Delete DMSGuestbook options from the database", "dmsguestbook") . "' />
			</form>
	<br />" . sprintf(__("If there is something wrong with my<br />DMSGuestbook_options in %s:", "dmsguestbook"), $table_option) . " <a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='../wp-content/plugins/dmsguestbook/default_options.txt' target='_blank'>" . __("Help", "dmsguestbook") . "</a>";

/* backup */
		$return_dmsguestbook_options_backup = __("Copy this content to a text file in case if you need a backup.", "dmsguestbook") . "<br />
		<textarea style='width:450px; height:200px;' name='save_data'>" . get_option("DMSGuestbook_options") . "</textarea><br />
		<br />
		<br />" .
		__("Restore DMSGuestbook_options:<br />
		Open your DMSGuestbook option backup text file and put the content in the textfield below.<br />
		All data will be overwrite!", "dmsguestbook") . "
		<form action='$location' method='post'>
		<textarea style='width:450px; height:200px;' name='restore_data'></textarea><br />
		<input type='hidden' name='restore_options' value='1' />
		<input class='button-secondary action' style='font-weight:bold;' type='submit' value='" . __("Restore", "dmsguestbook") . "' onclick=\"return confirm('" . __("Would you really like to restore all data?", "dmsguestbook") . "');\" />
		</form>";



echo "<b style='font-size:20px;'>". __("Database settings", "dmsguestbook") . "</b><br />";
echo "<table width='100%' border='0'>";
echo "<tr><td>";
?>

<div id="dmsguestbook-menu">
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("DMSGuestbook Database", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_dmsguestbook_database; ?></li>
		<li><?php echo $return_dmsguestbook_database_error; ?></li>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("DMSGuestbook options", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_dmsguestbook_options; ?></li>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("DMSGuestbook options backup", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_dmsguestbook_options_backup; ?><li>
		</ul>
		</p>
	</div>
</div>

<?php
echo   "</div>
	</div>";
echo "</td></tr></table>";

?>
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
<?php
}
?>
<!-- end table for DMSGuestbook and DMSGuestbook option environment -->

<!-- main table with all DMSGuestbook options -->
<?php
		$submitbutton = "<input class='button-primary action' style='font-weight:bold;margin:10px 0px; width:100px;'
		type='submit' value='" . __("Save", "dmsguestbook") . "' name='csssave' onclick=\"document.getElementById('save')\" />";


if($num_rows_option==$dmsguestbook_options)
{

if($_REQUEST['basic']==1) {

	$Role1 = CheckRole($options["role1"],0);
	if(!current_user_can("level_" . $Role1) && ROLE != 0) {
		CheckRole($options["role1"],1);
	exit;
	}

reset($options);
while (list($key, $val) = each($options)) {

	if($key == "page_id") {
		$query_posts = $wpdb->get_results("SELECT ID, post_title, post_status FROM $table_posts WHERE post_type = 'page' ORDER BY id ASC");
		$num_rows_posts = $wpdb->num_rows;
		$part_page_id = explode(",", $options["page_id"]);
		$part_language = explode(",", $options["language"]);

		if($options["supergb"] == "" || $options["supergb"] == 0) {
		$checked = "checked";
		}

		$c=0;
		$data  = (isset($data)) ? $data : '';
		$data .= "<b style='color:#bb0000;'>" . __("You have to delete the assigned guestbooks by clicking on \"Clear all\" before you can set this again. Assigned guestbooks will not be released until you press the \"Save\" button.", "dmsguestbook") . "</b>";
		$data .= "<br /><br />" . __("To assign guestbook(s)", "dmsguestbook") . ":";
		$data .= "<table style='width:95%;' border:0px;><tr><td>";

		### Language
		unset($tmp);
		$tmp = (isset($tmp)) ? $tmp : '';
		$abspath = str_replace("\\","/", ABSPATH);
				if ($handle = opendir($abspath . 'wp-content/plugins/dmsguestbook/language/')) {
					$tmp .= "<select name='langselect' id='langselect'>";
    				while (false !== ($file = readdir($handle))) {
        				if ($file != "." && $file != ".." && $file != "README.txt" && $file != "mo") {
						$tmp_a[] = $file;
						}
    				}
					sort($tmp_a);
					for($x=0; $x<count($tmp_a); $x++) {
						$tmp .= "<option value='$tmp_a[$x]'>$tmp_a[$x]</option>";
				
					}
    				$tmp .= "</select>";
    			closedir($handle);
				}
		###

		$data .= "<table><tr>";
		$data .= "<th style='font-size:9px;background-color:#cccccc;padding:2px;'>" . __("ID", "dmsguestbook") . "</th>";
		$data .= "<th style='font-size:9px;background-color:#cccccc;padding:2px;'>" . __("Page", "dmsguestbook") . "</th>";
		$data .= "<th style='font-size:9px;background-color:#cccccc;padding:2px;'>" . __("Page status", "dmsguestbook") . "</th>";
		$data .= "<th style='font-size:9px;background-color:#cccccc;padding:2px;'>" . __("Guestbook", "dmsguestbook") . "<br /><br />" . __("Deactivate super guestbook ", "dmsguestbook") . "*<br /><input type='radio' name='supergb' value='0' $checked> </th>";
		$data .= "<th style='font-size:9px;background-color:#cccccc;padding:2px;'>" . __("Language", "dmsguestbook") . "<br />$tmp</th></tr>";

		foreach ($query_posts as $result) {
			$data .= "<tr><td style='font-size:9px;background-color:#dddddd;padding:2px;'>$result->ID</td><td style='font-size:9px;background-color:#eeeeee;padding:2px;'>" . $result->post_title . "</td> ";
			$data .= "<td style='font-size:9px;background-color:#dddddd;padding:2px;'><a href='page.php?action=edit&post=$result->ID'>$result->post_status</a></td>";
				for($v=0; $v<count($part_page_id); $v++) {
				unset($lang);
				$lang  = (isset($lang)) ? $lang : '';
					if($result->ID == $part_page_id[$v]) {
					$vv = $v +1;
					$set = "#" . $vv;
					$setnr = $vv;
					$disabled = "disabled";
					$v=count($part_page_id);
					$lang=$part_language[$vv-1];
					}
					else {
				     	$set = __("not selected", "dmsguestbook");
						$setnr = '';
						$disabled = "disabled";
				     	}
				}

				if($setnr == $options["supergb"] && ($options["supergb"] != 0 || $options["supergb"] != "") ) {
				$checked = "checked";
				}
				else {
						$checked = 0;
					 }

				$data .= "<td style='font-size:9px;background-color:#dddddd;padding:2px;'><input class='button-secondary action' style='width:70px;' id='pageid$c' name='pageid$c' type='action' value='$set' $disabled onclick=\"PageID('$result->ID', '$c')\"><br /><br />" . __("Activate this guestbook as super guestbook", "dmsguestbook") . "*<br /><input type='radio' name='supergb' value='$setnr' $checked></td>";

				$data .= "<td style='font-size:9px;background-color:#dddddd;padding:2px;'><input style='width:120px;font-size:9px;background-color:#dddddd;border:1px;padding:2px;' type='text' id='language$c' name='language$c' value='$lang' readonly></td></tr>";

			$c++;
		}
		$data .= "</table>";

		$data .= "<input type='hidden' name='page_id' id='page_id' value='$options[page_id]'>";
		$data .= "<input type='hidden' name='language' id='language' value='$options[language]'>";
		$data .= "<input type='hidden' name='countpageid' id='countpageid' value='1'>";
		$data .= "<input id='page_id_clear' name='page_id_clear' class='button-secondary action' style='width:150px;color:#bb0000;' type='action' value='" . __("Clear all", "dmsguestbook") . "' onclick=\"PageID_Clear('$num_rows_posts')\">";
		$data .= "<span style='color:#bb0000;'><br />* " . __("Would you like to display one particular guestbook in a different languages? Select your desired guestbook and mark the radio button. After that all pages with the assigned language will be bound with the guestbook that you've selected.", "dmsguestbook") . "</span></td>";

		$data .= "<script type='text/javascript'>";
		$data .= "
				function PageID(id, c) {
				var m = document.getElementById('countpageid').value;
  				var newpageid = document.getElementById('page_id').value;
  				newpageid = newpageid.concat(id + ',');
  				document.getElementById('page_id').value = newpageid;
  				document.getElementById('pageid' + c).value = '#' + m;
  				document.getElementById('pageid' + c).disabled = true;

  				document.getElementById('language' + c).value = document.getElementById('langselect').value;

  				var newlanguage = document.getElementById('language').value;
  				newlanguage = newlanguage.concat(document.getElementById('langselect').value + ',');
  				document.getElementById('language').value = newlanguage;

  				m++;
  				document.getElementById('countpageid').value = m;
  				}

  				function PageID_Clear(c) {
  				 	for (var i = 0; i < c; i++) {
  			     	document.getElementById('pageid' + i).disabled = false;
  				 	document.getElementById('pageid' + i).value = '" . __("Set", "dmsguestbook") . "';
  				 	document.getElementById('language' + i).value = '';
  				 	}
  				document.getElementById('page_id').value = '';
  				document.getElementById('language').value = '';
  				document.getElementById('countpageid').value = '1';
  				}
  				";
		$data .= "</script>";
		$tooltip = __("How to use:<br />1.) Reset all assigned guestbooks by clicking on \"Clear all\"<br />2.) Select your desired language<br /> 3.) Select your guestbook(s) by clicking \"Set\" in ascending order.<br />4.) Press the \"Save\" button", "dmsguestbook");

		$data .="<td style='text-align:right;'><a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='#tooltip_$key' class='tooltiplink'>?</a><div id='tooltip_$key' class='tooltip'>$tooltip</div></td>";
		$data .= "</tr></table><br />";
	$return_page_id = $data;
	}

	if($key == "step") {
		$label = __("Post per page", "dmsguestbook") . ":";
		$option = "1@3@5@10@15@20@25@30@35@40@45@50@60@70@80@90@100@";
		$value = $options["step"];
		$additional = "";
		$style = "";
		$tooltip = __("Number of entry in each page", "dmsguestbook");
		$jscript = "";
  		$return_step = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "messagetext_length") {
		$label = __("Message text length", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["messagetext_length"];
		$char_lenght = "";
		$additional = __(" chars", "dmsguestbook");
		$style = "width:50px;";
		$tooltip = __("Define the maximum allowed lenght each message text<br />Deactivate this feature to set 0", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_messagetext_length = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "width1") {
		$label = __("Guestbook width", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["width1"];
		$char_lenght = "";
		$additional = "%";
		$style = "width:50px;";
		$tooltip = __("Guestbook width in percent<br /><br />Variable: {width1}", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_width1 = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "width2") {
		$label = __("Separator width", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["width2"];
		$char_lenght = "";
		$additional = "%";
		$style = "width:50px;";
		$tooltip = __("Separator width in percent<br /><br />Variable: {width2}", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_width2 = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "position1") {
		$label = __("Guestbook position (x-axis)", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["position1"];
		$char_lenght = "";
		$additional = "px";
		$style = "width:50px;";
		$tooltip = __("Absolute guestbook position in pixel horizontal (x-axis)<br /><br />Variable: {position1}", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_position1 = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "position2") {
		$label = __("Guestbook position (y-axis)", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["position2"];
		$char_lenght = "";
		$additional = "px";
		$style = "width:50px;";
		$tooltip = __("Absolute guestbook position in pixel vertical (y-axis)<br /><br />Variable: {position2}", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_position2 = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "forwardchar") {
		$tooltip = __("Navigation char style<br /><br />e.g. < >", "dmsguestbook");
		$showtooltip="<b style='background-color:#bb1100;color:#fff;padding:3px;' onmouseover=\"Tip('$tooltip')\" onclick=\"UnTip()\">?</b>";
		$base64 = 1;
			/* If base64 is active */
			if(BASE64 == 1 && $base64 == 1) {
			$forwardchar = base64_decode($options['forwardchar']);
			$backwardchar = base64_decode($options['backwardchar']);
			}
			else {
			     $forwardchar = $options['forwardchar'];
			     $backwardchar = $options['backwardchar'];
			     }
		$return_forwardchar = "<li><table style='width:95%;' border='0'><colgroup><col width='40%'><col width='55%'><col width='5%'><colgroup><tr><td>" . __("Navigation char style", "dmsguestbook") . ":</td>
		<td><input style='width:50px;' type='text' name='backwardchar' value='$backwardchar' />
		<input style='width:50px;' type='text' name='forwardchar' value='$forwardchar' />
		<input type='hidden' name='base64-forwardchar' value='$base64' />
		<input type='hidden' name='base64-backwardchar' value='$base64' />
		</td>
		<td style='text-align:right;'>$showtooltip</td></tr></table>";
	}

	if($key == "navigationsize") {
		$label = __("Navigation char size", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["navigationsize"];
		$char_lenght = "";
		$additional = "px";
		$style = "width:50px;";
		$tooltip = __("Navigation font size in pixel<br /><br />Variable: {navigationsize}", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_navigationsize = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "formpos") {
		$label = __("Guestbook form position", "dmsguestbook") . ":";
		$option = "top@bottom@";
		$value = $options["formpos"];
		$additional = "";
		$style = "";
		$tooltip = __("Visible the guestbook input form on top or bottom", "dmsguestbook");
		$jscript = "";
  		$return_formpos = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "formposlink") {
		$label = __("Link text", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["formposlink"];
		$char_lenght = "";
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("Define a link text if you selected \"bottom\"", "dmsguestbook");
		$jscript = "";
		$base64 = 1;
		$return_formposlink = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "sortitem") {
		$label = __("Sort guestbook items", "dmsguestbook") . ":";
		$option = "ASC@DESC@";
		$value = $options["sortitem"];
		$additional = "";
		$style = "";
  		$tooltip = __("DESC = Newer post first<br />ASC = Older post first", "dmsguestbook");
  		$jscript = "";
  		$return_sortitem = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

  	if($key == "dbid") {
  		$label = __("Database id", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["dbid"];
		$additional = "";
		$style = "";
		$tooltip = __("Use the database id to consecutively numbered each guestbook entry", "dmsguestbook");
		$jscript = "";
  		$return_dbid = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "form_template") {
		unset($tmp);
		$tmp  = (isset($tmp)) ? $tmp : '';
		$abspath = str_replace("\\","/", ABSPATH);
				if ($handle = opendir($abspath . 'wp-content/plugins/dmsguestbook/template/form/')) {
    				while (false !== ($file = readdir($handle))) {
        				if ($file != "." && $file != "..") {
           				$tmp .= "$file" . "@";
        				}
    				}
    			closedir($handle);
				}
		$label = __("Form template", "dmsguestbook") . ":";
		$option = $tmp;
		$value = $options["form_template"];
		$additional = "";
		$style = "";
		$tooltip = __("Create your own input form template and use it is on your guestbook site<br /><br />See an examle in \"/template/form/default.tpl\"", "dmsguestbook");
		$jscript = "";
  		$return_form_template = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "post_template") {
		unset($tmp);
		$tmp  = (isset($tmp)) ? $tmp : '';
		$abspath = str_replace("\\","/", ABSPATH);
				if ($handle = opendir($abspath . 'wp-content/plugins/dmsguestbook/template/post/')) {
    				while (false !== ($file = readdir($handle))) {
        				if ($file != "." && $file != "..") {
           				$tmp .= "$file" . "@";
        				}
    				}
    			closedir($handle);
				}
		$label = __("Post template", "dmsguestbook") . ":";
		$option = $tmp;
		$value = $options["post_template"];
		$additional = "";
		$style = "";
		$tooltip = __("Create your own guestbook post template and use it is on your guestbook site<br /><br />See an examle in \"/template/post/default.tpl\"", "dmsguestbook");
		$jscript = "";
  		$return_post_template = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "nofollow") {
  		$label = __("rel=\"nofollow\" tag for posted url's", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["nofollow"];
		$additional = "";
		$style = "";
		$tooltip = __("Activate the nofollow tag for posted url's<br /><a href=\"http://en.wikipedia.org/wiki/Nofollow\" target=\"_blank\">http://en.wikipedia.org/wiki/Nofollow</a>", "dmsguestbook");
		$jscript = "";
  		$return_nofollow = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "additional_option") {
		unset($tmp);
		$tmp  = (isset($tmp)) ? $tmp : '';
		$abspath = str_replace("\\","/", ABSPATH);
				if ($handle = opendir($abspath . 'wp-content/plugins/dmsguestbook/module/')) {
    				while (false !== ($file = readdir($handle))) {
        				if ($file != "." && $file != ".." && $file != "README.txt") {
           				$tmp .= "$file" . "@";
        				}
    				}
    			closedir($handle);
				}
		$label = __("Additional selectbox", "dmsguestbook") . ":";
		$option = "none@" . $tmp;
		$value = $options["additional_option"];
		$additional = "";
		$style = "";
		$tooltip = __("Define a selectbox and fill this with your own values.<br />See some examples in your \"dmsguestbook/module\" folder.", "dmsguestbook");
		$jscript = "";
  		$return_additional_option = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "additional_option_title") {
		$label = __("Additional selectbox title", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["additional_option_title"];
		$char_lenght = "";
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("This text will be shown on your input form guestbook page.<br />You could leave this textfield blank by using space character.", "dmsguestbook");
		$jscript = "";
		$base64 = 1;
		$return_additional_option_title = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "show_additional_option") {
  		$label = __("Show additional value", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["show_additional_option"];
		$additional = "";
		$style = "";
		$tooltip = __("Show additional text in each guestbook post.<br />You could edit the appearance in \"template/post/default.tpl\"<br />The default setting will be set the additional text on the footer of guestbook post.", "dmsguestbook");
		$jscript = "";
  		$return_show_additional_option = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "separatorcolor") {
		$label = __("Separator color", "dmsguestbook") . ":";
		$value = $options["separatorcolor"];
		$char_lenght = 6;
		$id = 1;
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("Separator between header and body in each entry<br /><br />Variable: {separatorcolor}", "dmsguestbook");
		$return_separatorcolor = ColorInput($key, $label, $id, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "bordercolor1") {
		$label = __("Outside border color", "dmsguestbook") . ":";
		$value = $options["bordercolor1"];
		$char_lenght = 6;
		$id = 2;
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("Color of the outside box<br /><br />Variable: {bordercolor1}", "dmsguestbook");
		$jscript = "";
		$return_bordercolor1 = ColorInput($key, $label, $id, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "bordercolor2") {
		$label = __("Textfield border color", "dmsguestbook") . ":";
		$value = $options["bordercolor2"];
		$char_lenght = 6;
		$id = 3;
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("Color of all textfield borders<br /><br />Variable: {bordercolor2}", "dmsguestbook");
		$jscript = "";
		$return_bordercolor2 = ColorInput($key, $label, $id, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "navigationcolor") {
		$label = __("Navigation char color", "dmsguestbook") . ":";
		$value = $options["navigationcolor"];
		$char_lenght = 6;
		$id = 4;
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("Define the navigation color<br /><br />Variable: {navigationcolor}", "dmsguestbook");
		$jscript = "";
		$return_navigationcolor = ColorInput($key, $label, $id, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "fontcolor1") {
		$label = __("Font color", "dmsguestbook") . ":";
		$value = $options["fontcolor1"];
		$char_lenght = 6;
		$id = 5;
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("Overall font color<br /><br />Variable: {fontcolor1}", "dmsguestbook");
		$jscript = "";
		$return_fontcolor1 = ColorInput($key, $label, $id, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "captcha_color") {
		$label = __("Antispam image text color", "dmsguestbook") . ":";
		$value = $options["captcha_color"];
		$char_lenght = 6;
		$id = 6;
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("Antispam image text color<br /><br />Variable: {captcha_color}", "dmsguestbook");
		$jscript = "";
		$return_captcha_color = ColorInput($key, $label, $id, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "dateformat") {
		$label = __("Date / Time format", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["dateformat"];
		$char_lenght = "";
		$additional = "";
		$style = "width:200px;";
		$tooltip = __("More infos", "dmsguestbook") . ": <a href=\"http://www.php.net/manual/en/function.strftime.php\" target=\"_blank\">http://www.php.net/manual/en/function.strftime.php</a>";
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_dateformat = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "setlocale") {
		$label = __("Setlocale", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["setlocale"];
		$char_lenght = "";
		$additional = "";
		$style = "width:80px;";
		$tooltip = __("Set your language: e.g. en_EN, de_DE, fr_FR, it_IT, de, ge ...<br />(must be installed on your system)", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_setlocale = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "offset") {
		$label = __("Offset", "dmsguestbook") . ":";
		$option = "-12@-11@-10@-9@-8@-7@-6@-5@-4@-3@-2@-1@0@+1@+2@+3@+4@+5@+6@+7@+8@+9@+10@+11@+12@";
		$value = $options["offset"];
		$additional = "";
		$style = "";
		$tooltip = __("Time offset: Use this offset if your Wordpress installation is not in the same country where you live.<br />e.g. You live in London and the Wordpress installation is on a server in Chicago.<br />If You want to show the date in GMT (Greenwich Mean Time), set the offset -6 and check the correct time below.<br /><br /> Notice: Don't use the %z or %Z parameter if your offset isn\"t 0.", "dmsguestbook");
		$jscript = "";
  		$return_offset = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "send_mail") {
  		$label = __("Send a mail", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["send_mail"];
		$additional = "";
		$style = "";
		$tooltip = __("Receive a notification email when user write an new guestbook post", "dmsguestbook");
		$jscript = "";
  		$return_send_mail = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "mail_adress") {
		$label = __("Email address", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["mail_adress"];
		$char_lenght = "";
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("The email address which the message to be sent is<br />Multiple email adresses are allowed, split these with the \";\" separator.<br />e.g. test1@example.com;test2@example.com;test3@example.com", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_mail_adress = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "mail_method") {
		$label = __("Send method", "dmsguestbook") . ":";
		$option = "Mail@SMTP@";
		$value = $options["mail_method"];
		$additional = "";
		$style = "";
		$tooltip = __("Use PHP internal mail function if your server supporting this.<br />A SMTP server could be need username and password as authentification which you must known.", "dmsguestbook");
		$jscript = "onChange=\"smtpContainer();\"";
  		$return_mail_send_method = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "smtp_host") {
		$label = __("SMTP host", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["smtp_host"];
		$char_lenght = "";
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("The SMTP server which do you want to connect.", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_smtp_host = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "smtp_port") {
		$label = __("SMTP port", "dmsguestbook") . ":";
		$option = "25@465@587@";
		$value = $options["smtp_port"];
		$additional = "";
		$style = "";
		$tooltip = __("25 = standard port<br />465 = SMTP over SSL port<br />587 = Alternative SMTP port<br /><br />Check your mail documentation for further information.", "dmsguestbook");
		$jscript = "";
  		$return_smtp_port = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "smtp_username") {
		$label = __("SMTP username", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["smtp_username"];
		$char_lenght = "";
		$additional = "";
		$style = "width:150px;";
		$tooltip = __("SMTP username if is needed.<br /><br />Check your mail documentation for further information.", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_smtp_username = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "smtp_password") {
		$label = __("SMTP password", "dmsguestbook") . ":";
		$type = "password";
		$entries = 0;
		$value = $options["smtp_password"];
		$char_lenght = "";
		$additional = "";
		$style = "width:140px;";
		$tooltip = __("SMTP password if is needed.<br /><br />Check your mail documentation for further information.", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_smtp_password = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "smtp_auth") {
  		$label = __("SMTP authentification", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["smtp_auth"];
		$additional = "";
		$style = "";
		$tooltip = __("SMTP authentification if is needed. <br /><br />Check your mail documentation for further information.", "dmsguestbook");
		$jscript = "";
  		$return_smtp_auth = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "smtp_ssl") {
  		$label = __("SMTP SSL", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["smtp_ssl"];
		$additional = "";
		$style = "";
		$tooltip = __("SMTP SSL (secure socket layer) if is needed.<br /><br />Check your mail documentation for further information.", "dmsguestbook");
		$jscript = "";
  		$return_smtp_ssl = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "akismet") {
		$CheckAkismet = CheckAkismet();
			if($CheckAkismet != "") {
			$akismet_description = sprintf(__("DMSGuestbook has found an Akismet key %s", "dmsguestbook"),$CheckAkismet);
			}
			else {
			     $akismet_description = __("No WordPress API key for Akismet was found! Activate the Akismet plugin and create a key. More information under: <a href=\"http://akismet.com/\" target=\"_blank\">http://akismet.com/</a> and <a href=\"http://en.wordpress.com/api-keys/\" target=\"_blank\">http://en.wordpress.com/api-keys/</a>", "dmsguestbook");
			     }

  		$label = __("Akismet", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["akismet"];
		$additional = "";
		$style = "";
		$tooltip = __("More infos", "dmsguestbook") . ":<a href=\"http://akismet.com/\" target=\"_blank\">http://akismet.com</a>";
		$jscript = "onClick=\"akismetContainer();\"";
  		$return_akismet = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
  		$return_akismet_key = "$akismet_description";
	}

	if($key == "akismet_action") {
  		$label = __("Move spam to the spam folder@Block guestbook post if spam is found on it@", "dmsguestbook") . ":";
		$entries = "1";
		$value = $options["akismet_action"];
		$additional = "";
		$style = "";
		$tooltip = __("What should Akismet do if spam are detected?", "dmsguestbook");
		$jscript = "";
  		$return_akismet_action = RadioBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}


	if($key == "require_antispam") {
  		$label = __("Antispam off:@Antispam image:@Antispam mathematic figures:@reCAPTCHA:@", "dmsguestbook");
		$entries = "3";
		$value = $options["require_antispam"];
		$additional = "";
		$style = "";
		$tooltip = __("Image:<br /><img src=\"../wp-content/plugins/dmsguestbook/captcha/captcha.php\" /><br />If you don't see the image here, check the xfiles.ttf and captcha.png permission in your captcha folder<br /><br />Mathematic figures:<br />4 + 9 = 13<br /><br />reCAPTCHA: <a href=\"http://recaptcha.net\" target=\"_blank\">learn more about reCAPTCHA</a>", "dmsguestbook");
		$jscript = "onClick=\"recaptchaKeys();\"";
  		$return_require_antispam = RadioBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "recaptcha_publickey") {
		$label = __("reCAPTCHA public key", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["recaptcha_publickey"];
		$char_lenght = "";
		$additional = "";
		$style = "width:140px;";
		$tooltip = __("Enter here you reCAPTCHA public key.", "dmsguestbook") . "<br /><a href=\"http://recaptcha.net/\" target=\"_blank\">" . __("Learn more about reCAPTCHA", "dmsguestbook") . "</a>";
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_recaptcha_publickey = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "recaptcha_privatekey") {
		$label = __("reCAPTCHA private key", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["recaptcha_privatekey"];
		$char_lenght = "";
		$additional = "";
		$style = "width:140px;";
		$tooltip = __("Enter here you reCAPTCHA private key.", "dmsguestbook") . "<br /><a href=\"http://recaptcha.net/\" target=\"_blank\">" . __("Learn more about reCAPTCHA", "dmsguestbook") . "</a>";
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_recaptcha_privatekey = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "antispam_key") {
	$value=RandomAntispamKey();
		$label = __("Antispam key", "dmsguestbook") . ":";
		$type = "hidden";
		$entries = 0;
		$value = RandomAntispamKey();
		$char_lenght = 20;
		$additional = $value;
		$style = "width:0px;";
		$tooltip = __("Set a random key to prevent spam.<br />Every page refresh will create a new key which can be saved.<br />It's used for: Antispam image & Antispam mathematic figures.", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_antispam_key = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "require_email") {
  		$label = __("Email", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["require_email"];
		$additional = "";
		$style = "";
  		$tooltip = __("User must fill out the email text field", "dmsguestbook");
  		$jscript = "";
  		$return_require_email = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "require_url") {
  		$label = __("Website", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["require_url"];
		$additional = "";
		$style = "";
		$tooltip = __("User must fill out the website text field", "dmsguestbook");
		$jscript = "";
  		$return_require_url = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "mandatory_char") {
		$label = __("Mandatory char", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["mandatory_char"];
		$char_lenght = 1;
		$additional = "";
		$style = "width:20px;";
		$tooltip = __("Mandatory char were to display on guestbook input form", "dmsguestbook");
		$jscript = "";
		$base64 = 1;
		$return_mandatory_char = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "show_email") {
  		$label = __("Show email", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["show_email"];
		$additional = "";
		$style = "";
		$tooltip = __("Visible email for everyone in each post", "dmsguestbook");
		$jscript = "";
  		$return_show_email = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "show_url") {
  		$label = __("Show website", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["show_url"];
		$additional = "";
		$style = "";
		$tooltip = __("Visible website for everyone in each post", "dmsguestbook");
		$jscript = "";
  		$return_show_url = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "show_ip") {
  		$label = __("Show ip address", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["show_ip"];
		$additional = "";
		$style = "";
		$tooltip = __("Visible ip for everyone in each post", "dmsguestbook");
		$jscript = "";
  		$return_show_ip = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "ip_mask") {
		$label = __("Mask ip address", "dmsguestbook") . ":";
		$option = "*.123.123.123@*.*.123.123@*.*.*.123@123.123.123.*@123.123.*.*@123.*.*.*@";
		$value = $options["ip_mask"];
		$additional = "";
		$style = "";
  		$tooltip = __("Mask ip adress if this is visible", "dmsguestbook");
  		$jscript = "";
  		$return_ip_mask = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "email_image_path") {
			$part1=explode("/", $options["email_image_path"]);
			$image=end($part1);
		$label = __("Email image path", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["email_image_path"];
		$char_lenght = "";
		$additional = "";
		$style = "width:200px;";
		$tooltip = __("Email image path", "dmsguestbook") . "<br /><a href=\"$options[email_image_path]\" target=\"_blank\">$options[email_image_path]</a><br /><br />" . __("Actually image:", "dmsguestbook") . "<img src=\"$options[email_image_path] \">";
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_email_image_path = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "website_image_path") {
			$part1=explode("/", $options["website_image_path"]);
			$image=end($part1);
		$label = __("Website image path", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["website_image_path"];
		$char_lenght = "";
		$additional = "";
		$style = "width:200px;";
		$tooltip = __("Website image path", "dmsguestbook") . " :<br /><a href=\"$options[website_image_path]\" target=\"_blank\">$options[website_image_path]</a><br /><br />" . __("Actually image:", "dmsguestbook") . "<img src=\"$options[website_image_path] \">";
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_website_image_path = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "admin_review") {
  		$label = __("Admin must every post review", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["admin_review"];
		$additional = "";
		$style = "";
		$tooltip = __("Admin must review every post before this can display on the page.<br />You can edit the guestbook review status under \"Entries\".", "dmsguestbook");
		$jscript = "";
  		$return_admin_review = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "url_overruled") {
		$label = __("URL overrule", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$value = $options["url_overruled"];
		$additional = "";
		$style = "width:200px;";
		$tooltip = __("You can overrule this link if you have trouble with the guestbook form submit.", "dmsguestbook") . "<br /><br />" . __("Example", "dmsguestbook") . ":<br />$url/?p=3<br />$url/?page_id=3<br />$url/3/<br />$url/" . __("YourGuestBookName", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_url_overruled = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "gravatar") {
  		$label = __("User can use Gravatar", "dmsguestbook") . ":";
		$entries = "0";
		$value = $options["gravatar"];
		$additional = "";
		$style = "";
		$tooltip = __("More infos", "dmsguestbook") . ":<a href=\'http://en.gravatar.com\' target=\'_blank\'>http://en.gravatar.com</a>";
		$jscript = "";
  		$return_gravatar = CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript);
	}

	if($key == "gravatar_rating") {
		$label = __("Gravatar rating", "dmsguestbook") . ":";
		$option = "G@PG@R@X@";
		$value = $options["gravatar_rating"];
		$additional = "";
		$style = "";
  		$tooltip = __("You can specify a rating of G, PG, R, or X.<br />[G] A G rated gravatar is suitable for display on all websites with any audience type.<br />[PG] PG rated gravatars may contain rude gestures, provocatively dressed individuals, the lesser swear words, or mild violence.<br />[R] R rated gravatars may contain such things as harsh profanity, intense violence, nudity, or hard drug use.<br />[X] X rated gravatars may contain hardcore sexual imagery or extremely disturbing violence.", "dmsguestbook");
  		$jscript = "";
  		$return_gravatar_rating = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "gravatar_size") {
		$label = __("Gravatar size", "dmsguestbook") . ":";
		$type = "text";
		$entries = 0;
		$char_lenght = 3;
		$value = $options["gravatar_size"];
		$additional = "px";
		$style = "width:30px;";
		$tooltip = __("Image size in pixel", "dmsguestbook");
		$jscript = "";
		$base64 = 0; /* Do not use this unless you edit the preg_replace() in the create_options() function and $var_* in dmsguestbook.php */
		$return_gravatar_size = OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64);
	}

	if($key == "role1") {
		$label = __("DMSGuestbook settings", "dmsguestbook") . ":";
		$option = "Administrator@Editor@Author@Contributor@Subscriber@";
		$value = $options["role1"];
		$additional = "";
		$style = "";
  		$tooltip = __("This role affects with", "dmsguestbook") .":<br />-" . __("Database settings", "dmsguestbook") . "<br />-" . __("Guestbook settings", "dmsguestbook") . "<br />-" . __("Language settings", "dmsguestbook") . "<br />-" . __("phpinfo", "dmsguestbook");
  		$jscript = "";
  		$return_role1 = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "role2") {
		$label = __("Entries", "dmsguestbook") . ":";
		$option = "Administrator@Editor@Author@Contributor@Subscriber@";
		$value = $options["role2"];
		$additional = "";
		$style = "";
  		$tooltip = __("This role affects with", "dmsguestbook") . ":<br />-" . __("Guestbook entries (show, edit, delete)", "dmsguestbook");
  		$jscript = "";
  		$return_role2 = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "role3") {
		$label = __("Spam") . ":";
		$option = "Administrator@Editor@Author@Contributor@Subscriber@";
		$value = $options["role3"];
		$additional = "";
		$style = "";
  		$tooltip = __("This role affects with", "dmsguestbook") . ":<br />-" . __("Spam entries (show, edit, delete)", "dmsguestbook");
  		$jscript = "";
  		$return_role3 = SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript);
	}

	if($key == "css") {
		$part1 = explode("@", $options["css"]);

		$options["css"] = str_replace("[br]", "\r\n", $options["css"]);
		$part1 = explode("@", $options["css"]);

		$part11 = explode("@", $_SESSION['csscontainer']);

		if(count($part11) > count($part1)) {
		$newone="<b style='color:#bb1100;'>" . __("NEW!", "dmsguestbook") . "</b><br />";
		echo "<b style='font-size:12px;color:#bb1100;'>
		--------------------------------------------------<br />";
		echo __("New CSS entries found, press the save button to save it.", "dmsguestbook") . "<br />";
		echo "--------------------------------------------------</b><br /><br />";
		}

		$restore_css  = (isset($restore_css)) ? $restore_css : '';
		$t  = (isset($t)) ? $t : '';
		if(count($part11) < count($part1)) {
		$restore_css = 1;
		echo "<b style='font-size:12px;color:#bb1100;'>
		--------------------------------------------------<br />";
		echo _("Some CSS entries are missing!", "dmsguestbook") . "<br />" . __("Press the save button to restore CSS settings.", "dmsguestbook") . "<br />";
		echo "--------------------------------------------------</b><br /><br />";
		}

	   $tooltip = "{width1} = " . __("Guestbook width", "dmsguestbook") . "<br />{width2} = " . __("Separator width", "dmsguestbook") . "<br />{position1} = " . __("Relative guestbook position (left to right)", "dmsguestbook") . "<br />{separatorcolor} = " . __("Separator between header and body in each entry", "dmsguestbook") . "<br />{bordercolor1} = " . __("Border of the outside box") . "<br />{bordercolor2} = " . __("Color of all textfield border", "dmsguestbook") . "<br />{navigationcolor} = " . __("Navigation color", "dmsguestbook") . "<br />{fontcolor1} = " . __("Overall font color", "dmsguestbook") . "<br />{navigationsize} = " . __("Navigation char size", "dmsguestbook") . "<br />{captcha_color} = " . __("Antispam image text color", "dmsguestbook") . "<br /><br />" . __("Stylesheet (CSS) Help & Tutorials:<br />English: <a href=\"http://www.html.net/tutorials/css/\" target=\"_blank\">http://www.html.net/tutorials/css/</a><br />German: <a href=\"http://www.css4you.de/\" target=\"_blank\">http://www.css4you.de/</a><br />Or ask Google and friends :-)", "dmsguestbook");


			$return_css  = (isset($return_css)) ? $return_css : '';
			$return_css .= "<table border='0'><colgroup><col width='50'><col width='210'><col width='50'><colgroup>";
					$xx = 0;
					for($x=0; $x<count($part11)-1; $x++) {
    				$part2 = explode("|", $part1[$xx]);
    				$part22 = explode("|", $part11[$x]);

    					if(trim($part2[1]) == trim($part22[1])) {
    					$yxc[$x] = "<div style='font-size:0.9em;'>Description: $part2[0]<br />
    					CSS class: $part2[1]</div><input type='hidden' name='cssdescription$x' value='$part2[0]' />
    					<input type='hidden' name='cssname$x' value='$part2[1]' />";
						$xx++;
						}
						else
							{
								if($restore_css!=1) {
								$yxc[$x] = "<div style='font-size:0.9em;'>" . __("Description", "dmsguestbook") . ": $part22[0]<br />
    							CSS class: $part22[1]</div><input type='hidden' name='cssdescription$x' value='$part22[0]' />
    							<input type='hidden' name='cssname$x' value='$part22[1]' />";
								$xx -1;
								$t[]=$x;
								}
							}
					}

					$xx=0;
					for($x=0; $x<count($part11)-1; $x++) {
					$part2 = explode("|", $part1[$xx]);
					$part22 = explode("|", $part11[$x]);
					$y=$x+1;

			$showtooltip="<a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='#tooltip_css_$x' class='tooltiplink'>?</a><div id='tooltip_css_$x' class='tooltip'>$tooltip</div>";

			if(!$t) {$t[]=99999;}
			if(!in_array($x, $t) && $restore_css!=1) {
    		$return_css	.= "<tr><td style='background-color:#fff;text-align:center;'>($y)</td>
    		<td>$yxc[$x]<textarea name='css$x' cols='50' rows='5' >$part2[2]</textarea></td><td>$showtooltip</td></tr>";

						unset($css_submitbutton);
						$css_submitbutton  = (isset($css_submitbutton)) ? $css_submitbutton : '';
						if($x==4 OR $x==9 OR $x==14 OR $x==19 OR $x==24 OR $x==29) {
						$css_submitbutton = "<br /><br />" . $submitbutton . "<br /><br />";
						}

			$return_css .= "<tr><td></td><td>$css_submitbutton</td></tr>";
			$xx++;
			}
			else {
			     	if($restore_css!=1) {
				 	$return_css .= "<tr><td style='text-align:center;'><br />($y)</td>
    			 	<td>$yxc[$x]<textarea name='css$x' cols='50' rows='5' >$part22[2]</textarea></td><td>$showtooltip</td></tr>";

						unset($css_submitbutton);
						if($x==4 OR $x==9 OR $x==14 OR $x==19 OR $x==24 OR $x==29) {
						$css_submitbutton = "<br /><br />" . $submitbutton . "<br /><br />";
						}

				 	$return_css .= "<tr><td></td><td>$css_submitbutton</td></tr>";
			     	$xx-1;
			     	}
			     }


			if($restore_css==1) {
			$return_css .= "<input type='hidden' name='cssdescription$x' value='$part22[0]' />";
			$return_css .= "<input type='hidden' name='cssname$x' value='$part22[1]' />";
			$return_css .= "<input type='hidden' name='css$x' value='$part22[2]' />";
			}


					}
			$return_css .= "</table>";
	}

	if($key == "css_customize") {
			$options["css_customize"] = str_replace("[br]", "\r\n", $options["css_customize"]);

			$return_css_customize = "<table style='width:95%;' border='0'>";
    				$yxc = "<div style='font-size:0.9em;'>" . __("Custom CSS", "dmsguestbook") . ":</div>";

			$tooltip = __("Class heredity:<br /><br />e.g.<br /><b>a.</b>css_navigation_char<b>:hover</b> {color:#ff0000;}<br />All url link with css_navigation_char (navigation link)<br />become hover color red when user drag over it<br /><br /><b>td</b>.css_guestbook_message_nr_name {background-color:#00ff00;}<br />All td with css_guestbook_message_nr_name (guestbook name & id)<br />become background color green<br /><br />", "dmsguestbook");
			$showtooltip="<b style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;' onmouseover=\"Tip('$tooltip')\" onclick=\"UnTip()\">?</b>";

    		$return_css_customize .= "<tr><td>$yxc <textarea name='css_customize' cols='55' rows='15' >$options[css_customize]</textarea></td><td>$showtooltip</td></tr>";
			$return_css_customize .= "</table>";
	}
}







echo "<b style='font-size:20px;'>" . __("Guestbook settings", "dmsguestbook") . "</b><br />";
echo "<table width='100%' border='0'>";
echo "<tr><td>";

echo "<form name='form1' method='post' action='$location'>";
echo $submitbutton;
echo "<div id='outer'>";
?>

<div id="dmsguestbook-menu">
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Basic", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_page_id; ?></li>
		<li><?php echo $return_step; ?></li>
		<li><?php echo $return_formpos; ?></li>
		<li><?php echo $return_formposlink; ?></li>
		<li><?php echo $return_messagetext_length; ?></li>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Extended"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_position1; ?></li>
		<li><?php echo $return_position2; ?></li>
		<li><?php echo $return_width1; ?></li>
		<li><?php echo $return_width2; ?></li>
		<li><?php echo $return_forwardchar; ?></li>
		<li><?php echo $return_navigationsize; ?></li>
		<li><?php echo $return_show_email; ?></li>
		<li><?php echo $return_show_url; ?></li>
		<li><?php echo $return_show_ip; ?></li>
		<li><?php echo $return_ip_mask; ?></li>
		<li>&nbsp;</li>
		<li><?php echo $return_sortitem; ?></li>
		<li><?php echo $return_dbid; ?></li>
		<li><?php echo $return_form_template; ?></li>
		<li><?php echo $return_post_template; ?></li>
		<li><?php echo $return_nofollow; ?></li>
		<li><?php echo $return_additional_option; ?></li>
		<li><?php echo $return_additional_option_title; ?></li>
		<li><?php echo $return_show_additional_option; ?></li>
		</ul>
		</p>

	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Color", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_bordercolor1; ?></li>
		<li><?php echo $return_bordercolor2; ?></li>
		<li><?php echo $return_navigationcolor; ?></li>
		<li><?php echo $return_separatorcolor; ?></li>
		<li><?php echo $return_fontcolor1; ?></li>
		<li><?php echo $return_captcha_color; ?></li>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Time / Date", "dmsguestbook"); ?></a></h3>
	<?php
	setlocale(LC_TIME, $options["setlocale"]);
	$offset = mktime(date("H")+$options["offset"], date("i"), date("s"), date("m")  , date("d"), date("Y"));
 	$time_example = htmlentities(strftime($options["dateformat"], $offset), ENT_QUOTES);
	?>
	<div>
		<p>
		<ul>
		<li><?php echo $return_dateformat; ?></li>
		<li><?php echo $return_setlocale; ?></li>
		<li><?php echo $return_offset; ?></li>
		<li>&nbsp;</li>
		<li><?php echo __("Example", "dmsguestbook") . " : $time_example"; ?></li>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Email Notification", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_send_mail; ?></li>
		<li><?php echo $return_mail_adress; ?></li>
		<li><?php echo $return_mail_send_method; ?></li>
		<li>&nbsp;</li>
		<span style='display:none;' id='smtp_container'>
		<li><?php echo $return_smtp_host; ?></li>
		<li><?php echo $return_smtp_port; ?></li>
		<li>&nbsp;</li>
		<li><?php echo $return_smtp_auth; ?></li>
		<li><?php echo $return_smtp_ssl; ?></li>
		<li><?php echo $return_smtp_username; ?></li>
		<li><?php echo $return_smtp_password; ?></li>
		</span>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Captcha / Akismet", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_require_antispam; ?></li>
		<span style='display:none;' id='recaptcha_keys'>
		<li><?php echo $return_recaptcha_publickey; ?></li>
		<li><?php echo $return_recaptcha_privatekey; ?></li>
		<li>&nbsp;</li>
		</span>
		<li><?php echo $return_antispam_key; ?></li>
		<li>&nbsp;</li>
		<li>&nbsp;</li>
		<li><?php echo $return_akismet; ?></li>
		<span style='display:none;' id='akismet_container'>
		<li><?php echo $return_akismet_key; ?></li>
		<li>&nbsp;</li>
		<li><?php echo $return_akismet_action; ?></li>
		</span>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Mandatory", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><table style='width:95%;' border='0'><colgroup><col width='40%'><col width='55%'><col width='5%'><colgroup><tr><td><?php echo __("Name", "dmsguestbook"); ?>:</td>
		    <td><input type='checkbox' checked disabled></td><td style='text-align:right;'><a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='#tooltip_mandatory_name' class='tooltiplink'>?</a><div id='tooltip_mandatory_name' class='tooltip'><?php echo __("User must fill out name text field", "dmsguestbook"); ?></div></td></tr></li>
		<li><table style='width:95%;' border='0'><colgroup><col width='40%'><col width='55%'><col width='5%'><colgroup><tr><td><?php echo __("Message", "dmsguestbook"); ?>:</td>
		    <td><input type='checkbox' checked disabled></td><td style='text-align:right;'><a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='#tooltip_mandatory_message' class='tooltiplink'>?</a><div id='tooltip_mandatory_message' class='tooltip'><?php echo __("User must fill out message text field", "dmsguestbook"); ?></div></td></tr></li>
		<li><?php echo $return_require_email; ?></li>
		<li><?php echo $return_require_url; ?></li>
		<li><?php echo $return_mandatory_char; ?></li>
		</ul>
		</p>



	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Gravatar", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_gravatar; ?></li>
		<li><?php echo $return_gravatar_rating; ?></li>
		<li><?php echo $return_gravatar_size; ?></li>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Miscellaneous", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_email_image_path; ?></li>
		<li><?php echo $return_website_image_path; ?></li>
		<li><?php echo $return_admin_review; ?></li>
		<li><?php echo $return_url_overruled; ?></li>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("Role", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo $return_role1; ?></li>
		<li><?php echo $return_role2; ?></li>
		<li><?php echo $return_role3; ?></li>
		<?php
		$abspath = str_replace("\\","/", ABSPATH);
			if(is_writable($abspath . "wp-content/plugins/dmsguestbook/dmsguestbook.css")) {
			$css_notice = "<b>dmsguestbook.css </b><i style='color:#00bb00;font-style:normal;'>" . __("is writable", "dmsguestbook") . ".</i>";
			} else {
				$css_notice = "<b>dmsguestbook.css </b><i style='color:#bb0000;font-style:normal;'>" . __("is not writable or doesn't exist", "dmsguestbook") . ".</i>";
				}
		?>
		</ul>
		</p>
	</div>
	<h3 style="font-weight:bold;"><a href="#"><?php echo __("CSS", "dmsguestbook"); ?></a></h3>
	<div>
		<p>
		<ul>
		<li><?php echo __("If dmsguestbook.css exist and is writable, all CSS settings will be read from it.<br />Otherwise these settings will be loaded from the database.", "dmsguestbook"); ?><br /><?php echo $css_notice; ?><br /><br />
		</li>
		<li><?php echo $return_css; ?></li>
		<li><?php echo $return_css_customize; ?></li>
		<li>
			<b><?php echo __("Settings for custom CSS", "dmsguestbook"); ?></b><br />
			<br />
			<i><?php echo __("Mouse hover color", "dmsguestbook"); ?></i><br />
			a.css_navigation_char:hover {text-decoration:none; color:#{navigationcolor};}<br />
			a.css_navigation_select:hover {text-decoration:none; color:#bb1100;}<br />
			a.css_navigation_notselect:hover {text-decoration:none; color:#000000;}<br />
			<br />
			<i><?php echo __("Email and url image properties", "dmsguestbook"); ?></i><br />
			img.css_post_url_image {border:0px;}<br />
			img.css_post_email_image {border:0px;}<br />
		</li>
		</ul>
		</p>
	</div>
</div>

				<!-- Check SMTP is on or not-->
				<script type="text/javascript">
					smtpContainer();
						function smtpContainer() {
						var mail_method = document.getElementById('mail_method').value;

						if(mail_method == "SMTP") {
						document.getElementById('smtp_container').style.display="block";
						}

						if(mail_method == "Mail") {
						document.getElementById('smtp_container').style.display="none";
						}
					}
				</script>

			<!-- Check reCAPTCHA is on or not-->
				<script type="text/javascript">
					recaptchaKeys();
						function recaptchaKeys() {

						if(document.form1.require_antispam[3].checked == true) {
						document.getElementById('recaptcha_keys').style.display="block";
						}

						if(document.form1.require_antispam[3].checked == false) {
						document.getElementById('recaptcha_keys').style.display="none";
						}
					}
				</script>

			<!-- Check Akismet is on or not-->
				<script type="text/javascript">
					akismetContainer();
						function akismetContainer() {
						var akismet = document.getElementById('akismet').checked;

						if(akismet == "1") {
						document.getElementById('akismet_container').style.display="block";
						}

						if(akismet == "0") {
						document.getElementById('akismet_container').style.display="none";
						}
					}
				</script>
<?php
echo   "</div>
	</div>";
echo "</td></tr></table>";


echo "<table border='0'><colgroup><col width='100%' span='2'></colgroup><tr>";
echo "<td><input id='save' name='action' value='insert' type='hidden' />";
echo "<input class='button-primary action' style='font-weight:bold; margin:10px 0px; width:100px;' type='submit' value='" . __("Save", "dmsguestbook") . "' />";
echo "</form></td>";

	 	#restore default settings button -->
		echo "<td><form name='form3' method='post' action='$location'>
		<input name='action' value='default_settings' type='hidden' />
		<input class='button-secondary action' style='font-weight:bold; margin:10px 0px;' type='submit'
		value='" . __("Restore default settings - All data will be replaced", "dmsguestbook") . "' onclick=\"return confirm('" . __("Would you really like to restore all data?", "dmsguestbook") . "');\" />
     	</form></td>";

echo "</tr></table>";
?>
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
<?php
}
?>

<!-- language -->
<?php
if($_REQUEST['advanced']==1) {

	

	$Role1 = CheckRole($options["role1"],0);
	if(!current_user_can("level_" . $Role1) && ROLE != 0) {
		CheckRole($options["role1"],1);
	exit;
	}

	clearstatcache();
	$color3=settablecolor(3,0);
	unset($buffer);
	
	$buffer 				= (isset($buffer)) ? $buffer : '';
	$save_advanced_button 	= (isset($save_advanced_button)) ? $save_advanced_button : '';
	$valid_file 			= (isset($valid_file)) ? $valid_file : '';
	$_REQUEST['file'] 		= (isset($_REQUEST['file'])) ? $_REQUEST['file'] : '';
	
	echo "<b style='font-size:20px;'>" . __("Language settings", "dmsguestbook") . "</b><br />";
	$abspath = str_replace("\\","/", ABSPATH);

		if ($handle = opendir($abspath . 'wp-content/plugins/dmsguestbook/language/')) {
    		/* language */
    		while (false !== ($file = readdir($handle))) {
        		if ($file != "." && $file != ".." && $file != "mo") {
        			if($file=="README.txt") {
           			echo "<a style='color:#bb0000;' href='admin.php?page=dmsguestbook&advanced=1&folder=language/&file=$file'>$file</a>, ";
        			}
        			else 	{
        					echo "<a href='admin.php?page=dmsguestbook&advanced=1&folder=language/&file=$file'>$file</a>, ";
        					}
        		}
    		}
    		echo "<br />";
    		closedir($handle);
		}


if($_REQUEST['file']!="") {

	clearstatcache();

	/* check the file variable for language text file */
	if(preg_match('/^[a-z0-9_]+\.+(txt)/i', "$_REQUEST[file]")==1) {
	$file=$_REQUEST['file'];

	if(file_exists($abspath . "wp-content/plugins/dmsguestbook/language/" . $file)) {
	$folder="language/";
	$valid_file=1;
	$save_advanced_button = check_writable($folder, $file);
	} else
	  		{
	  		$valid_file=0;
	  		echo "<br /><b>" . __("File not found!", "dmsguestbook") . "</b>";
	  		}
	}


	/* error handling */
	if($file=="") {
	echo "<br /><b>" . __("Not a valid file, must be a language file with .txt prefix.") . "</b>";
	}


		if ($valid_file==1) {
		$handle = @fopen ($abspath . "wp-content/plugins/dmsguestbook/" . $folder . $file, "r");
			while (!feof($handle)) {
    		$buffer .= fgets($handle, 4096);
			}
		fclose ($handle);
		}


}

	$showfiledata = htmlentities($buffer, ENT_QUOTES);

?>
	<br />
	<table style="border:0px solid #000000; width:100%;background-color:#<?php echo $color3; ?>;" cellspacing="0" cellpadding="0">
	  <tr>
		<form name="form0" method="post" action="<?php echo $location;?>">
		<td><textarea style="width:99%; height:500px;" name="advanced_data"><?php echo $showfiledata;?></textarea></td>
	  </tr>
		<input name="action" value="save_advanced_data" type="hidden" />
	  	<input name="folder" value="<?php echo $folder; ?>" type="hidden" />
	  	<input name="file" value="<?php echo $file; ?>" type="hidden" />
	  <tr>
		<td style="text-align:center;"><?php echo $save_advanced_button;?></td>
  	  </tr>
  		</form>
	  </tr>
	 </table>
<?php
}
?>
	 </div>
<?php
	}
}	/* end of DMSGuestbook adminpage main function */



	/* check the old HTTP_POST_VARS and new $_POST var */
	if(!empty($HTTP_POST_VARS)) {
	$POSTVARIABLE   = $HTTP_POST_VARS;
	}
	else {
		 $POSTVARIABLE = $_POST;
		 }
		 
	$POSTVARIABLE['action'] 						= (isset($POSTVARIABLE['action'])) ? $POSTVARIABLE['action'] : '';
	$POSTVARIABLE['base64-supergb'] 				= (isset($POSTVARIABLE['base64-supergb'])) ? $POSTVARIABLE['base64-supergb'] : '';
	$POSTVARIABLE['base64-page_id'] 				= (isset($POSTVARIABLE['base64-page_id'])) ? $POSTVARIABLE['base64-page_id'] : '';
	$POSTVARIABLE['base64-step'] 					= (isset($POSTVARIABLE['base64-step'])) ? $POSTVARIABLE['base64-step'] : '';
	$POSTVARIABLE['base64-separatorcolor'] 			= (isset($POSTVARIABLE['base64-separatorcolor'])) ? $POSTVARIABLE['base64-separatorcolor'] : '';
	$POSTVARIABLE['base64-bordercolor1'] 			= (isset($POSTVARIABLE['base64-bordercolor1'])) ? $POSTVARIABLE['base64-bordercolor1'] : '';
	$POSTVARIABLE['base64-bordercolor2'] 			= (isset($POSTVARIABLE['base64-bordercolor2'])) ? $POSTVARIABLE['base64-bordercolor2'] : '';
	$POSTVARIABLE['base64-navigationcolor'] 		= (isset($POSTVARIABLE['base64-navigationcolor'])) ? $POSTVARIABLE['base64-navigationcolor'] : '';
	$POSTVARIABLE['base64-fontcolor1'] 				= (isset($POSTVARIABLE['base64-fontcolor1'])) ? $POSTVARIABLE['base64-fontcolor1'] : '';
	$POSTVARIABLE['base64-require_email'] 			= (isset($POSTVARIABLE['base64-require_email'])) ? $POSTVARIABLE['base64-require_email'] : '';
	$POSTVARIABLE['base64-require_url'] 			= (isset($POSTVARIABLE['base64-require_url'])) ? $POSTVARIABLE['base64-require_url'] : '';
	$POSTVARIABLE['base64-require_antispam'] 		= (isset($POSTVARIABLE['base64-require_antispam'])) ? $POSTVARIABLE['base64-require_antispam'] : '';
	$POSTVARIABLE['base64-akismet'] 				= (isset($POSTVARIABLE['base64-akismet'])) ? $POSTVARIABLE['base64-akismet'] : '';
	$POSTVARIABLE['base64-akismet_action'] 			= (isset($POSTVARIABLE['base64-akismet_action'])) ? $POSTVARIABLE['base64-akismet_action'] : '';
	$POSTVARIABLE['base64-show_url'] 				= (isset($POSTVARIABLE['base64-show_url'])) ? $POSTVARIABLE['base64-show_url'] : '';
	$POSTVARIABLE['base64-show_email'] 				= (isset($POSTVARIABLE['base64-show_email'])) ? $POSTVARIABLE['base64-show_email'] : '';
	$POSTVARIABLE['base64-show_ip'] 				= (isset($POSTVARIABLE['base64-show_ip'])) ? $POSTVARIABLE['base64-show_ip'] : '';
	$POSTVARIABLE['base64-ip_mask'] 				= (isset($POSTVARIABLE['base64-ip_mask'])) ? $POSTVARIABLE['base64-ip_mask'] : '';
	$POSTVARIABLE['base64-captcha_color'] 			= (isset($POSTVARIABLE['base64-captcha_color'])) ? $POSTVARIABLE['base64-captcha_color'] : '';
	$POSTVARIABLE['base64-offset'] 					= (isset($POSTVARIABLE['base64-offset'])) ? $POSTVARIABLE['base64-offset'] : '';
	$POSTVARIABLE['base64-formpos'] 				= (isset($POSTVARIABLE['base64-formpos'])) ? $POSTVARIABLE['base64-formpos'] : '';
	$POSTVARIABLE['base64-send_mail'] 				= (isset($POSTVARIABLE['base64-send_mail'])) ? $POSTVARIABLE['base64-send_mail'] : '';
	$POSTVARIABLE['base64-mail_method'] 			= (isset($POSTVARIABLE['base64-mail_method'])) ? $POSTVARIABLE['base64-mail_method'] : '';
	$POSTVARIABLE['base64-smtp_port'] 				= (isset($POSTVARIABLE['base64-smtp_port'])) ? $POSTVARIABLE['base64-smtp_port'] : '';
	$POSTVARIABLE['base64-smtp_auth'] 				= (isset($POSTVARIABLE['base64-smtp_auth'])) ? $POSTVARIABLE['base64-smtp_auth'] : '';
	$POSTVARIABLE['base64-smtp_ssl'] 				= (isset($POSTVARIABLE['base64-smtp_ssl'])) ? $POSTVARIABLE['base64-smtp_ssl'] : '';
	$POSTVARIABLE['base64-sortitem'] 				= (isset($POSTVARIABLE['base64-sortitem'])) ? $POSTVARIABLE['base64-sortitem'] : '';
	$POSTVARIABLE['base64-dbid'] 					= (isset($POSTVARIABLE['base64-dbid'])) ? $POSTVARIABLE['base64-dbid'] : '';
	$POSTVARIABLE['base64-language'] 				= (isset($POSTVARIABLE['base64-language'])) ? $POSTVARIABLE['base64-language'] : '';
	$POSTVARIABLE['base64-admin_review'] 			= (isset($POSTVARIABLE['base64-admin_review'])) ? $POSTVARIABLE['base64-admin_review'] : '';
	$POSTVARIABLE['base64-gravatar'] 				= (isset($POSTVARIABLE['base64-gravatar'])) ? $POSTVARIABLE['base64-gravatar'] : '';
	$POSTVARIABLE['base64-gravatar_rating'] 		= (isset($POSTVARIABLE['base64-gravatar_rating'])) ? $POSTVARIABLE['base64-gravatar_rating'] : '';
	$POSTVARIABLE['base64-form_template'] 			= (isset($POSTVARIABLE['base64-form_template'])) ? $POSTVARIABLE['base64-form_template'] : '';
	$POSTVARIABLE['base64-post_template'] 			= (isset($POSTVARIABLE['base64-post_template'])) ? $POSTVARIABLE['base64-post_template'] : '';
	$POSTVARIABLE['base64-nofollow'] 				= (isset($POSTVARIABLE['base64-nofollow'])) ? $POSTVARIABLE['base64-nofollow'] : '';
	$POSTVARIABLE['base64-additional_option'] 		= (isset($POSTVARIABLE['base64-additional_option'])) ? $POSTVARIABLE['base64-additional_option'] : '';
	$POSTVARIABLE['base64-show_additional_option'] 	= (isset($POSTVARIABLE['base64-show_additional_option'])) ? $POSTVARIABLE['base64-show_additional_option'] : '';
	$POSTVARIABLE['base64-role1'] 					= (isset($POSTVARIABLE['base64-role1'])) ? $POSTVARIABLE['base64-role1'] : '';
	$POSTVARIABLE['base64-role2'] 					= (isset($POSTVARIABLE['base64-role2'])) ? $POSTVARIABLE['base64-role2'] : '';
	$POSTVARIABLE['base64-role3'] 					= (isset($POSTVARIABLE['base64-role3'])) ? $POSTVARIABLE['base64-role3'] : '';
	$POSTVARIABLE['base64-css'] 					= (isset($POSTVARIABLE['base64-css'])) ? $POSTVARIABLE['base64-css'] : '';
	$POSTVARIABLE['base64-css_customize'] 			= (isset($POSTVARIABLE['base64-css_customize'])) ? $POSTVARIABLE['base64-css_customize'] : '';

	
	/* write DMSGuestbook option in wordpress options database */
	if ($POSTVARIABLE['action'] == 'insert')
	{
	$url=get_bloginfo('wpurl');
	$options = create_options();
		$save_options = default_options_array();
		unset($save_to_db);
		unset($save_to_dmsguestbook_css);
		$save_to_db = (isset($save_to_db)) ? $save_to_db : '';
		
		while (list($key, $val) = each($save_options)) {
			$POSTVARIABLE[$key] 	= (isset($POSTVARIABLE[$key])) ? $POSTVARIABLE[$key] : '';
			if($POSTVARIABLE[$key]==""){$POSTVARIABLE[$key]=0;}

			/* Convert text to base64 if is selected */
			if(BASE64 == 1 & $POSTVARIABLE["base64-$key"] == 1) {
			$POSTVARIABLE[$key] = base64_encode($POSTVARIABLE[$key]);
			}

				if($key=="css") {
					$cssdata = (isset($cssdata)) ? $cssdata : '';
					$part = explode("@", $_SESSION['csscontainer']);
					for($y=0; $y<count($part)-1; $y++) {
					$POSTVARIABLE["cssdescription$y"] = str_replace("@", "", $POSTVARIABLE["cssdescription$y"]);
					$POSTVARIABLE["cssdescription$y"] = str_replace("|", "", $POSTVARIABLE["cssdescription$y"]);
					$POSTVARIABLE["cssname$y"] = str_replace("@", "", $POSTVARIABLE["cssname$y"]);
					$POSTVARIABLE["cssname$y"] = str_replace("|", "", $POSTVARIABLE["cssname$y"]);
					$POSTVARIABLE["css$y"] = str_replace("@", "", $POSTVARIABLE["css$y"]);
					$POSTVARIABLE["css$y"] = str_replace("|", "", $POSTVARIABLE["css$y"]);
					$cssdata.= $POSTVARIABLE["cssdescription$y"] . "|" . $POSTVARIABLE["cssname$y"] . "|" . $POSTVARIABLE["css$y"] . "@";
					}


     				$cssdata = str_replace("\r\n", "[br]", $cssdata);
				$save_to_db.="<" . $key . ">" . htmlentities($cssdata, ENT_QUOTES) . "</" . $key . ">\r\n";
				}
				elseif($key=="css_customize") {
					$css_customize = str_replace("\r\n", "[br]", $POSTVARIABLE["css_customize"]);
					$save_to_db.="<" . $key . ">" . htmlentities($css_customize, ENT_QUOTES) . "</" . $key . ">\r\n";
				}
				else {
						if($key == "page_id") {
							$multi_gb = explode(",", $POSTVARIABLE[$key]);
							unset($POSTVARIABLE[$key]);
							$POSTVARIABLE[$key] 	= (isset($POSTVARIABLE[$key])) ? $POSTVARIABLE[$key] : '';
							$multi_gb = array_unique($multi_gb);
								for($m=0; $m<count($multi_gb); $m++) {
									if(is_numeric($multi_gb[$m])) {
									$POSTVARIABLE[$key] .= $multi_gb[$m] . ",";
									}
								}

							$multi_lang = explode(",", $POSTVARIABLE['language']);
							unset($POSTVARIABLE['language']);
							$POSTVARIABLE['language'] = (isset($POSTVARIABLE['language'])) ? $POSTVARIABLE['language'] : '';
								for($m=0; $m<count($multi_lang); $m++) {
									if(is_string($multi_lang[$m])) {
									$POSTVARIABLE['language'] .= $multi_lang[$m] . ",";
									}
								}
							$POSTVARIABLE['language'] = rtrim($POSTVARIABLE['language'], ",");


							$POSTVARIABLE[$key] = rtrim($POSTVARIABLE[$key], ",");
							$save_to_db.="<" . $key . ">" . sprintf("%s",$POSTVARIABLE[$key]) . "</" . $key . ">\r\n";
							}
						elseif ($key == "width1") {
							$save_to_db.="<" . $key . ">" . sprintf("%d",$POSTVARIABLE[$key]) . "</" . $key . ">\r\n";
							}
						elseif ($key == "width2") {
							$save_to_db.="<" . $key . ">" . sprintf("%d",$POSTVARIABLE[$key]) . "</" . $key . ">\r\n";
							}
						elseif ($key == "position1") {
							$save_to_db.="<" . $key . ">" . sprintf("%d",$POSTVARIABLE[$key]) . "</" . $key . ">\r\n";
							}
						elseif ($key == "position2") {
							$save_to_db.="<" . $key . ">" . sprintf("%d",$POSTVARIABLE[$key]) . "</" . $key . ">\r\n";
							}
						elseif ($key == "navigationsize") {
							$save_to_db.="<" . $key . ">" . sprintf("%d",$POSTVARIABLE[$key]) . "</" . $key . ">\r\n";
							}
						else {
				     		$save_to_db.="<" . $key . ">" . htmlentities($POSTVARIABLE[$key], ENT_QUOTES) . "</" . $key . ">\r\n";
				     		}
				     }
		}
		$save_to_db = str_replace("\"", "&amp;quot;", $save_to_db);
		update_option("DMSGuestbook_options", mysql_real_escape_string($save_to_db));
		message("<b>" . __("saved", "dmsguestbook") . "...</b>",300,800);

		/* save to dmsguestbook.css if is writable */
		$abspath = str_replace("\\","/", ABSPATH);
			if(is_writable($abspath . "wp-content/plugins/dmsguestbook/dmsguestbook.css")) {
				$notice ="/*\n" . __("Use the DMSGuestbook admin interface for change these css settings.\nDon't edit this file direct, your change could be overwrite by the DMSGuestbook admin.\nIf dmsguestbook.css is exist and writable, all CSS settings will be read from it.\nOtherwise these settings will be load from the database.", "dmsguestbook") . "\n*/\n\n";
				$csscode = make_css();
				$handle = fopen($abspath . "wp-content/plugins/dmsguestbook/dmsguestbook.css", "w");
				fwrite($handle, $notice . $csscode);
				fclose($handle);
			}
	}
	/* end of write DMSGuestbook option in wordpress options database */



	/* reset DMSGuestbook */
	if ($POSTVARIABLE['action'] == 'default_settings') {
	default_option();
	}

	/* save advanced */
	if ($POSTVARIABLE['action'] =='save_advanced_data') {
	$abspath = str_replace("\\","/", ABSPATH);

	/* check the folder variable */
	if($POSTVARIABLE['folder']=="language/"){
	$folder="language/";
	} else {$folder="";}

	/* check the file variable xxxx.txt */
	if(preg_match('/^[a-z0-9]+\.+(txt)/i', $POSTVARIABLE['file'])==1) {
	$file=$POSTVARIABLE['file'];
	} else {$file="";}

		clearstatcache();
		if (file_exists($abspath . "wp-content/plugins/dmsguestbook/" . $folder . $file)) {
		$handle = fopen($abspath . "wp-content/plugins/dmsguestbook/" . $folder . $file, "w");

		$writetofile = str_replace("\\", "", $POSTVARIABLE['advanced_data']);

		fwrite($handle, $writetofile);
		fclose($handle);
		message("<b>" . __("saved", "dmsguestbook") . "...</b>",300,800);
		} else {message("<br /><b>" . __("File not found!", "dmsguestbook") . "</b>",300,800);}
	}



/* manage guestbook entries */
function dmsguestbook2_meta_description_option_page() {

		$_REQUEST['guestbook'] 	= (isset($_REQUEST['guestbook'])) ? $_REQUEST['guestbook'] : '';
		$_REQUEST['htmleditor'] = (isset($_REQUEST['htmleditor'])) ? $_REQUEST['htmleditor'] : '';
		$_REQUEST['approval'] 	= (isset($_REQUEST['approval'])) ? $_REQUEST['approval'] : '';
		$_REQUEST['tinymce'] 	= (isset($_REQUEST['tinymce'])) ? $_REQUEST['tinymce'] : '';
		$_REQUEST['search'] 	= (isset($_REQUEST['search'])) ? $_REQUEST['search'] : '';
		$_REQUEST['from'] 		= (isset($_REQUEST['from'])) ? $_REQUEST['from'] : '';
		$location 				= (isset($location)) ? $location : '';
		$gbadditional2 			= (isset($gbadditional2)) ? $gbadditional2 : '';
		$search_param 			= (isset($search_param)) ? $search_param : '';
		$bgcolor 				= (isset($bgcolor)) ? $bgcolor : '';
		$date2 					= (isset($date2)) ? $date2 : '';
		$editor					= (isset($editor)) ? $editor : '';
		
		// all guestbooks are selected when this site is loading
		if($_REQUEST['guestbook']=="") {$_REQUEST['guestbook']="all";}

		$options=create_options();

		// check Akismet is activated
		$CheckAkismet = CheckAkismet();
			if($CheckAkismet != "" && $options['akismet'] == 1) {
			}

?>
		<div class="wrap">
		<h2><?php echo __("Entries", "dmsguestbook"); ?></h2>

<?php

		/* maximum guestbook entries were displayed on page */
		if($_REQUEST['tinymce']==1) {
		$gb_step=1;
		$editor = "WHERE id = '$_REQUEST[id]'";
		}

		if($_REQUEST['htmleditor']==1) {
		$gb_step=1;
		$editor = "WHERE id = '$_REQUEST[id]'";
		}

		if($_REQUEST['approval']==1) {
		$flag="AND flag='1'";
		} else {
			   $flag="";
			   }

		if($_REQUEST['htmleditor']!=1 && $_REQUEST['tinymce']!=1) {
		$gb_step=$options["step"];

			if($_REQUEST['search']!="") {
			$_REQUEST['search'] = preg_replace("/[\<\>\"\'\`\´]+/i", "", $_REQUEST['search']);

				if($_REQUEST['guestbook']=="all") {
				$search_param ="WHERE spam = '0' AND (name LIKE '$_REQUEST[search]' OR email LIKE '$_REQUEST[search]' OR url LIKE '$_REQUEST[search]' OR ip
				LIKE '$_REQUEST[search]' OR message LIKE '$_REQUEST[search]')";
				} else {
					   $search_param ="WHERE guestbook = '" . sprintf("%d", $_REQUEST['guestbook']) . "' AND spam = '0' AND (name LIKE '$_REQUEST[search]' OR email LIKE '$_REQUEST[search]'
					   OR url LIKE '$_REQUEST[search]' OR ip LIKE '$_REQUEST[search]' OR message LIKE '$_REQUEST[search]')";
					   }
			}
			else {
			     $search_param="";
			     	if($_REQUEST['guestbook']=="all") {
			     	$editor = "WHERE spam = '0'";
			     	} else {
			     		   $editor = "WHERE guestbook = '" . sprintf("%d", $_REQUEST['guestbook']) . "' AND spam = '0'";
			     		   }
			     }
		}


			/* if some option(s) data are missing*/
			
			$options[9999] = (isset($options[9999])) ? $options[9999] : '';
			if($options[9999]) {
			$gb_step="50";
			$options["sortitem"]="DESC";
			$options["dateformat"]="%a, %e %B %Y %H:%M:%S %z";
			$options["setlocale"]="en_EN";
			}

		/* initialize */
		if($_REQUEST['from']=="") {$_REQUEST['from']=0; $_REQUEST['select']=1;}

		/* global var for DMSGuestbook */
		global $wpdb;
		$table_name = $wpdb->prefix . "dmsguestbook";
		$table_posts = $wpdb->prefix . "posts";

		/* count all search database entries / mysql_query */
    	$query0 = $wpdb->get_results("SELECT * FROM $table_name $search_param $editor $flag");
    	$num_rows0 = $wpdb->num_rows;

		/* read all search guestbook entries */
		$query1 = $wpdb->get_results("SELECT * FROM $table_name $search_param $editor $flag ORDER BY id " . sprintf("%s", $options["sortitem"]) . " LIMIT
		" . sprintf("%d", $_REQUEST['from']) . "," . sprintf("%d", $gb_step) . ";");
		$num_rows1 = $wpdb->num_rows;
?>
		<br />
		<br />
		<table>
		<tr>
		<td style="vertical-align: top;">
		<table style="background-color:#fff; border:1px solid #aaaaaa; width:450px; padding:5px;">
		<tr>
		<td><form name="search" method="post" action="<?php echo $location ?>">
		<input style="width:250px;" type="text" name="search" value="<?php echo $_REQUEST['search']; ?>" />
		<input type="hidden" name="guestbook" value="<?php echo $_REQUEST['guestbook']; ?>" />
		<input class="button-secondary action" style="font-weight:bold;" type="submit" value="<?php echo __("Search", "dmsguestbook"); ?>" />
		<input class="button-secondary action" style="font-weight:bold;" type="button" value="<?php echo __("Clear", "dmsguestbook"); ?>" onClick="document.search.search.value = ''"; />
	 	</form></td>
	 	</tr>
	 	<tr>
	 	<td><?php echo __("Search in: Name, Message, IP, Website and Email Fields.<br />Use % to specify search patterns. e.g. %fox% or %fox or fox%", "dmsguestbook"); ?></td>
	 	</tr>
	 	</table>
		</td>

		<td style="width:20px;"></td>
		<?php if($_REQUEST['guestbook'] == "all") {$active="all";} else {$active=$_REQUEST['guestbook']+1;} ?>
		<td style="vertical-align: top;">
		<table style="background-color:#fff; border:1px solid #aaaaaa; width:400px; padding:5px;">
		<tr><td><b style="font-size:14px;"><?php echo __("Active: Guestbook", "dmsguestbook"); ?> <?php echo $active; ?></b></td></tr>
		<?php
			$multi_page_id = explode(",", $options["page_id"]);
			echo "<tr><td><a href='$location?page=Entries&guestbook=all'><b>" . __("Guestbook: All", "dmsguestbook") . "</b></a></td></tr>";

			$guestbook_count=1;
			for($m=0; $m<count($multi_page_id); $m++) {
			$query_posts = $wpdb->get_results("SELECT ID, post_title FROM $table_posts WHERE ID = $multi_page_id[$m] ORDER BY id ASC");
				foreach ($query_posts as $result) {
				echo "<tr><td><a href='$location?page=Entries&guestbook=$m'><b>" . __("Guestbook", "dmsguestbook") . "#" . $guestbook_count . "</b></a> (" . __("Page", "dmsguestbook") . ": $result->post_title || " . __("ID", "dmsguestbook") . ": $result->ID)</td></tr>";
				}
			$guestbook_count++;
			}
		?>
		</table>
		</td>
		</tr>
		</table>

		<br /><br />
		<div style="width:<?php echo $options["width1"] . "%" ;?>; text-align:center;">
		<div style="font-size:11px;">(<?php echo $num_rows0;?>)</div>

<?php

		$y=0;
		for($q=0; $q<$num_rows0; ($q=$q+$gb_step))
		{
		$y++;
			if($_REQUEST['select']==$y) {
			echo "<a style='color:#bb1100; text-decoration:none;' href='admin.php?page=Entries&from=$q&select=$y&guestbook=$_REQUEST[guestbook]&search=$_REQUEST[search]&approval=$_REQUEST[approval]'> $y</a>";
			}
			else {
				 echo "<a style='color:#000000; text-decoration:none;' href='admin.php?page=Entries&from=$q&select=$y&guestbook=$_REQUEST[guestbook]&search=$_REQUEST[search]&approval=$_REQUEST[approval]'> $y</a>";
				 }
		}
		echo "</div>
		<br /><br />";




	# overview
	if($_REQUEST['tinymce']!=1 && $_REQUEST['htmleditor']!=1) {
		echo "
		<form name='myForm' method='post' action='$location'>
		<p>
		<select name='action'>
		<option value='-1'>" . __("Bulk Action", "dmsguestbook") . "</option>
		<option value='markasspam'>" . __("Mark as Spam", "dmsguestbook") . "</option>
		<option value='deletepost2'>" . __("Delete", "dmsguestbook") . "</option>
		<option value='setvisible'>" . __("Set post visible", "dmsguestbook") . "</option>
		<option value='sethidden'>" . __("Set post hidden", "dmsguestbook") . "</option>
		</select>

		<input class='button-secondary action' type='submit' value='" . __("Apply", "dmsguestbook") ."'
		onclick=\"return confirm('" . __("Would you really like to do this?", "dmsguestbook") . "');\" /></p>

	    <table class='widefat comments' cellspacing='0'>
		<thead>
		<tr>
		<th style='padding:7px 7px 7px 0px; width:20px;'><input type='checkbox' id='selectall1' name='selectall1' onClick=\"AllSelectboxes1();\"></th>
		<th style='padding:0px 5px 0px 5px; width:20px;'>" . __("ID", "dmsguestbook") . "</th>
	 	<th style='padding:0px 5px 0px 5px; width:100px;'>" . __("Author", "dmsguestbook") . "</th>
	 	<th style='padding:0px 5px 0px 5px; width:300px;'>" . __("Comment", "dmsguestbook") . "</th>
		<th style='padding:0px 5px 0px 5px; width:200px;'>" . __("Action", "dmsguestbook") . "</th>
		</tr>
		</thead>";
	}

			if($num_rows0 == 0) {
			echo "<tr><td></td><td></td><td></td><td style='text-align:center;'><b>" . __("No entries found.", "dmsguestbook") . "</b></td><td></td></tr>";
			}

			setlocale(LC_TIME, $options["setlocale"]);
			foreach ($query1 as $result) {
	 			// build the data / time variable
				$sec=date("s", "$result->date");
				$min=date("i", "$result->date");
				$hour=date("H", "$result->date");
				$day=date("d", "$result->date");
				$month=date("m", "$result->date");
				$year=date("Y", "$result->date");
				$date = htmlentities(strftime ($options["dateformat"], mktime ($hour, $min, $sec, $month, $day, $year)));

				$gbname 	= preg_replace("/[\\\\\"=\(\)\{\}]+/i", "", stripslashes($result->name));
				$gbemail 	= preg_replace("/[^a-z-0-9-_\.@]+/i", "", $result->email);
				$gburl 		= preg_replace("/[^a-z-0-9-_,.:?&%=\/]+/i", "", $result->url);
				$gbip 		= preg_replace("/[^0-9\.]+/i", "", $result->ip);
				$gbmsg 		= preg_replace("/(\<\/textarea\>)||(\\\\)/i", "", stripslashes($result->message));
				$gbguestbook= preg_replace("/[^0-9]+/i", "", $result->guestbook);
				$gbadditional= $result->additional;

				$guestbook = ($result->guestbook +1);

				if($result->email=="") {
				$email = "";
				} else {
					   $email = "<a href='mailto:$result->email'>$result->email</a><br />";
					   }

				if($gburl=="http://" || $gburl=="http://") {
				$concat_name_url = $gbname;
				} else {
					   $concat_name_url = "<a href='$gburl' target='_blank'>$gbname</a>";
					   }

				if($gbadditional !="") {
				$gbadditional2 = "<br />\"$gbadditional\"";
				}

		if($_REQUEST['tinymce']!=1 && $_REQUEST['htmleditor']!=1) {
			if($result->flag == 1) {
			$adminreview="<a style='color:#D98500;' href='admin.php?page=Entries&action=adminreview&flag=0&id=$result->id&from=$_REQUEST[from]&select=$_REQUEST[select]&guestbook=$_REQUEST[guestbook]&search=$_REQUEST[search]&approval=$_REQUEST[approval]'>[" . __("Visible", "dmsguestbook") . "]</a> | ";
			$adminreview_color="style='background-color:#E5CDCD;'";
			}
			else {
			     $adminreview = "<a href='admin.php?page=Entries&action=adminreview&flag=1&id=$result->id&from=$_REQUEST[from]&select=$_REQUEST[select]&guestbook=$_REQUEST[guestbook]&search=$_REQUEST[search]&approval=$_REQUEST[approval]'>[" . __("Hidden", "dmsguestbook") . "]</a> | ";
			     $adminreview_color="";
			     }

			echo "<tr>";
			echo "<td $adminreview_color><input type='checkbox' id='selectpost' name='selectpost[]' value='$result->id'></td>";
			echo "<td $adminreview_color>$result->id</td>";
			echo "<td $adminreview_color><b>$concat_name_url</b><br />$email" . "$gbip<br />" . __("Guestbook", "dmsguestbook") . " #$guestbook" . "$gbadditional2</td>";
			echo "<td $adminreview_color><span style='color:#777777;'>" . __("Submitted on", "dmsguestbook") . " </span>$date<br />$gbmsg</td>";

			$action_tinymce = "<a href='admin.php?page=Entries&tinymce=1&guestbook=$_REQUEST[guestbook]&id=$result->id'>" . __("Edit", "dmsguestbook") . " (TinyMCE)</a>";
			$action_htmleditor = "<a href='admin.php?page=Entries&htmleditor=1&guestbook=$_REQUEST[guestbook]&id=$result->id'>" . __("Edit", "dmsguestbook") . " (HTML)</a>";
			$action_spam = "<a href='admin.php?page=Entries&action=markasspam&guestbook=$_REQUEST[guestbook]&id=$result->id'>" . __("Spam") . "</a>";
			$action_delete = "<a style='color:#D54E21;' href='admin.php?page=Entries&action=deletepost&guestbook=$_REQUEST[guestbook]&id=$result->id' onclick=\"return confirm('" . __("Would you really like to delete this dataset?", "dmsguestbook") . "');\">" . __("Delete", "dmsguestbook") . "</a>";
			echo "<td $adminreview_color>";
			echo "$adminreview $action_tinymce | $action_htmleditor | $action_spam | $action_delete";
			echo "</td>";
			echo "</tr>";
		}

			}
		echo "</form>";

	if($_REQUEST['tinymce']!=1 && $_REQUEST['htmleditor']!=1) {
		echo "<thead>
		<tr>
		<th style='padding:7px 7px 7px 0px; width:20px;'><input type='checkbox' id='selectall2' name='selectall2' onClick=\"AllSelectboxes2();\"></th>
		<th style='padding:0px 5px 0px 5px; width:20px;'>" . __("ID", "dmsguestbook") . "</th>
	 	<th style='padding:0px 5px 0px 5px; width:100px;'>" . __("Author", "dmsguestbook") . "</th>
	 	<th style='padding:0px 5px 0px 5px; width:300px;'>" . __("Comment", "dmsguestbook") . "</th>
		<th style='padding:0px 5px 0px 5px; width:200px;'>" . __("Action", "dmsguestbook") . "</th>
		</tr>
		</thead>";
	}




	if(($_REQUEST['tinymce']==1 || $_REQUEST['htmleditor']==1) && $result->id !="" & $result->spam ==0) {
		echo "
		<table class='widefat comments' cellspacing='0'>

		<thead>
		<tr>
	 	<th style='padding:0px 5px 0px 5px; width:450px;'>" . __("Author", "dmsguestbook") . "</th>
	 	<th style='padding:0px 5px 0px 5px;'>" . __("Comment", "dmsguestbook") . "</th>
		<th style='padding:0px 5px 0px 5px; width:50px;'>" . __("Action", "dmsguestbook") . "</th>
		</tr>
		</thead>";

		echo "<a class='button-secondary action' href='admin.php?page=Entries&guestbook=$_REQUEST[guestbook]&id=$result->id'>" . __("Back", "dmsguestbook") . "</a><br /><br />";
		echo "
		<tr>
	 			<form name='edit_form' method='post' action='$location'>
	 			<td style='font-size:10px; border:1px solid #eeeeee; background-color:#$bgcolor'>
	 			<table border='0'>
	 			<tr><td><b>" . __("ID", "dmsguestbook") . ":</b></td><td>$result->id</td></tr>
	 			<tr><td><b>" . __("Guestbook", "dmsguestbook") . ": </b></td><td><select name='gb_guestbook'>";

                    $display_gb_count = $gbguestbook+1;

                    echo "<option value='$gbguestbook' selected>" . __("Assigned to: Guestbook", "dmsguestbook") . " $display_gb_count</option>";
                    $c=1;
                    $multi_page_id = explode(",", $options["page_id"]);
                    for($m=0; $m<count($multi_page_id); $m++) {
                        if($gbguestbook != $m) {
                        echo "<option value='$m'>" . __("Switch to: Guestbook", "dmsguestbook") . "$c</option>";
                        }
                    $c++;
                    }
    				echo "</select></td></tr>";

				if($result->flag == 1) {$check = "checked"; } else {$check="";}
                echo "<tr><td><b>" . __("Admin review", "dmsguestbook") . ":</b></td><td><input type='checkbox' name='gb_flag' value='1' $check /> " . __("If the Admin review checkbox is activated, the post will not be shown on the guestbook page.", "dmsguestbook") . "</td></tr>";

	 			echo "<tr><td style='font-size:10px;'><b>" . __("Date", "dmsguestbook") . ":</b></td>
	 			<td style='font-size:10px;'>$date<br />" .
	 			__("Day.Month.Year,Hour:Minute:Second", "dmsguestbook") . "<br />
	 			<input style='font-size:10px; width:200px;' type='text' name='gb_date' value='$date2' /><br />" .
	 			__("(DD.MM.YYYY,HH:MM:SS)", "dmsguestbook") . "</td></tr>
	 			<input type='hidden' name='hidden_date' value='$date' />";


				echo "
				<tr><td><b>" . __("Name", "dmsguestbook") . ": </b></td><td><input style='font-size:10px;' type='text' name='gb_name' value='$gbname' /></td></tr>
	 			<tr><td style='font-size:10px;'><b>" . __("Email", "dmsguestbook") . ":</b> </td> <td><input style='font-size:10px; width:200px;'
	 			type='text' name='gb_email' value='$gbemail' /></td></tr>
	 			<tr><td style='font-size:10px;'><b>" . __("Website", "dmsguestbook") . ":</b> </td> <td><input style='font-size:10px; width:200px;'
	 			type='text' name='gb_url' value='$gburl' /><br />" . __("Don't remove the \"http(s)://\" tag.", "dmsguestbook") . "</td></tr>
	 			<tr><td style='font-size:10px;'><b>" . __("Additional", "dmsguestbook") . ":</b> </td> <td><input style='font-size:10px; width:200px; background-color:#eee;'
	 			type='text' id='gb_additional' name='gb_additional' value='$gbadditional' readonly />
	 			<input class='button-secondary action' style='font-weight:bold; color:#bb0000; margin:10px 0px;' type='button'
	 			value='X' onclick='deleteAdditional();' /></td></tr>
	 			<tr><td style='font-size:10px;'><b>" . __("IP", "dmsguestbook") . ":</b></td> <td><input style='font-size:10px; width:200px; background-color:#eee;'
	 			type='text' id='gb_ip' name='gb_ip' value='$gbip' maxlength='15' readonly />
	 			<input class='button-secondary action' style='font-weight:bold; color:#bb0000; margin:10px 0px;' type='button'
	 			value='X' onclick='deleteIP();' />
	 			<a style='font-size:10px;' href='http://www.ripe.net/whois?searchtext=$result->ip' target='_blank'>[" . __("query", "dmsguestbook") . "]</a>
	 			</td></tr>
				</table>

	 			<td style='border:1px solid #eeeeee; background-color:#$bgcolor'>";
	 				if($_REQUEST['htmleditor']==1) {
						echo "
						<script type=\"text/javascript\" src=\"../wp-content/plugins/dmsguestbook/js/quicktags/quicktags.js\"></script>
						<script type=\"text/javascript\">
							quicktagsL10n = {
								quickLinks: \"(Quick Links)\",
								wordLookup: \"Enter a word to look up:\",
								dictionaryLookup: \"Dictionary lookup\",
								lookup: \"lookup\",
								closeAllOpenTags: \"Close all open tags\",
								closeTags: \"close tags\",
								enterURL: \"Enter the URL\",
								enterImageURL: \"Enter the URL of the image\",
								enterImageDescription: \"Enter a description of the image\"
							}
							try{convertEntities(quicktagsL10n);}catch(e){};
						edToolbar()
						</script>";
					}
				echo "
				<textarea style='height:400px; width:99%;' id='gb_message' name='gb_message'>$gbmsg</textarea>
				<br />
	 			<br />
	 			</td>";

	 			if($_REQUEST['htmleditor']==1) {
					echo "<script type=\"text/javascript\">
					{
					edCanvas = document.getElementById('gb_message');
					}
					</script>";
				}

					echo "
					<script type='text/javascript'>
						function deleteAdditional() {
						check = confirm('" . __("Would you really like to clear this text field? Don\'t forget to press the \"Save\" button.", "dmsguestbook") . "');
							if(check == true) {
							document.getElementById('gb_additional').value = '';
							}
						}

						function deleteIP() {
						check = confirm('" . __("Would you really like to clear this text field? Don\'t forget to press the \"Save\" button.", "dmsguestbook") . "');
							if(check == true) {
							document.getElementById('gb_ip').value = '';
							}
						}
					</script>

	 			<td style='text-align:center;font-size:10px; border:1px solid #eeeeee; background-color:#$bgcolor'>
	 			<form name='edit_form' method='post' action='$location'>
	 			<input name='editdata' value='edit' type='hidden' />
	 			<input name='id' value='$result->id' type='hidden' />
	 			<input type='hidden' name='guestbook' value='$_REQUEST[guestbook]' />
	 			<input class='button-primary action' style='font-weight:bold; color:#0000bb; margin:10px 0px;'
	 			type='submit' value='" . __("Save", "dmsguestbook") . "' onclick=\"return confirm('" . __("Would you really like to edit this dataset?", "dmsguestbook") . "');\" />
	 			</form>";

				echo "
				<form name='spam_form' method='post' action='$location'>
	 			<input name='action' value='markasspam' type='hidden' />
	 			<input type='hidden' name='guestbook' value='$_REQUEST[guestbook]' />
				<input name='id' value='$result->id' type='hidden' />
				<input name='tinymce' value='0' type='hidden' />
	 			<input name='htmleditor' value='0' type='hidden' />
	 			<input class='button-secondary action' style='font-weight:bold; color:#000000; margin:10px 0px;' type='submit'
	 			value='" . __("Spam", "dmsguestbook") . "' />
	 			</form>";

				echo "
	 			<form name='delete_form' method='post' action='$location'>
	 			<input name='action' value='deletepost' type='hidden' />
	 			<input type='hidden' name='guestbook' value='$_REQUEST[guestbook]' />
				<input name='id' value='$result->id' type='hidden' />
				<input name='tinymce' value='0' type='hidden' />
	 			<input name='htmleditor' value='0' type='hidden' />
	 			<input class='button-secondary action' style='font-weight:bold; color:#bb0000; margin:10px 0px;' type='submit'
	 			value='X' onclick=\"return confirm('" . __("Would you really like to delete this dataset?", "dmsguestbook") . "');\" />
	 			</form>
	 			</td>
	 			</tr>";

	 			echo "<thead>
				<tr>
	 			<th style='padding:0px 5px 0px 5px;'>" . __("Author", "dmsguestbook") . "</th>
	 			<th style='padding:0px 5px 0px 5px;'>" . __("Comment", "dmsguestbook") . "</th>
				<th style='padding:0px 5px 0px 5px;'>" . __("Action", "dmsguestbook") . "</th>
				</tr>
				</thead>";

					if($_REQUEST['tinymce']==1) {
						echo "
						<!-- TinyMCE -->
						<script type=\"text/javascript\" src=\"../wp-content/plugins/dmsguestbook/js/tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>
						<script type=\"text/javascript\">
							tinyMCE.init({
								mode : \"textareas\",
								theme : \"advanced\",
								theme_advanced_buttons1 : \"bold, italic, underline, strikethrough, justifyleft, justifycenter, justifyright, justifyfull, blockquote, bullist, numlist, outdent, indent, link, unlink, image, hr, code, cleanup, removeformat, forecolor, backcolor, charmap, separator, undo, redo\",
								theme_advanced_buttons2 : \"\"
							});
						</script>
						<!-- /TinyMCE -->";
					}



	}


		echo "
		</table>";

		echo "<script type=\"text/javascript\">
			function AllSelectboxes1() {
				countall = document.forms.myForm.selectpost.length;

				selectallbox1 = document.getElementById('selectall1').checked;

 				for(var i = 0; i < countall; i++)
  				{
  				thisElement = document.forms.myForm.selectpost[i];

					if(selectallbox1 == true) {
					thisElement.checked = true;
					document.getElementById('selectall2').checked = true;
					}

					if(selectallbox1 == false) {
					thisElement.checked = false;
					document.getElementById('selectall2').checked = false;
					}
 				}
			}


			function AllSelectboxes2() {
				countall = document.forms.myForm.selectpost.length;

				selectallbox2 = document.getElementById('selectall2').checked;

 				for(var i = 0; i < countall; i++)
  				{
  				thisElement = document.forms.myForm.selectpost[i];

					if(selectallbox2 == true) {
					thisElement.checked = true;
					document.getElementById('selectall1').checked = true;
					}

					if(selectallbox2 == false) {
					thisElement.checked = false;
					document.getElementById('selectall1').checked = false;
					}
 				}
			}
		</script>";

?>
		</table>
		</div>
		<br /><br />
<?php
	} /* end of manage guestbook entries */





	/* Spam */
	function dmsguestbook5_meta_description_option_page() {
		$_REQUEST['from'] = (isset($_REQUEST['from'])) ? $_REQUEST['from'] : '';
		$_REQUEST['guestbook'] = (isset($_REQUEST['guestbook'])) ? $_REQUEST['guestbook'] : '';	
		$location = (isset($location)) ? $location : '';
	
		$options=create_options();

		// check Akismet is activated
		$CheckAkismet = CheckAkismet(); 
			if($CheckAkismet != "" && $options['akismet'] == 1) {
			$aktivatedAkismet = 1;
			}

		global $wpdb;
		$table_name = $wpdb->prefix . "dmsguestbook";
		$table_posts = $wpdb->prefix . "posts";

		/* initialize */
		if($_REQUEST['from']=="") {$_REQUEST['from']=0; $_REQUEST['select']=1;}
		$gb_step=$options["step"];

		$query1 = $wpdb->get_results("SELECT * FROM $table_name WHERE spam = '1'");
    	$num_rows1 = $wpdb->num_rows;

		/* count all search database entries / mysql_query */
    	$query0 = $wpdb->get_results("SELECT * FROM $table_name WHERE spam = '1' ORDER BY id " . sprintf("%s", $options["sortitem"]) . " LIMIT
		" . sprintf("%d", $_REQUEST['from']) . "," . sprintf("%d", $gb_step) . ";");
    	$num_rows0 = $wpdb->num_rows;


		echo "
		<div class='wrap'>
		<h2>" . __("Spam", "dmsguestbook") . "</h2>";

		if($num_rows1 >=1 ) {
		echo "<a style='font-size:10px;' href='admin.php?page=Spam&action=deleteallpost'
		onclick=\"return confirm('" . __("Would you really like to delete ALL data entries?", "dmsguestbook") . "');\">" . sprintf(__("Delete all Spam (%s) entries", "dmsguestbook"),$num_rows1) . "</a>";
		}

		echo "
		<br /><br />
		<div style='width:100%; text-align:center;'>
		<div style='font-size:11px;'>($num_rows1)</div>";

		$y=0;
		for($q=0; $q<$num_rows1; ($q=$q+$gb_step))
		{
		$y++;
			if($_REQUEST['select']==$y) {
			echo "<a style='color:#bb1100; text-decoration:none;' href='admin.php?page=Spam&from=$q&select=$y'> $y</a>";
			}
			else {
				 echo "<a style='color:#000000; text-decoration:none;' href='admin.php?page=Spam&from=$q&select=$y'> $y</a>";
				 }
		}
		echo "</div>
		<br /><br />";


		echo "
		<form name='myForm' method='post' action='$location'>
		<p>
		<select name='action'>
		<option value='-1'>" . __("Bulk Action", "dmsguestbook") . "</option>
		<option value='unmarkspam'>" . __("Unmark Spam", "dmsguestbook") . "</option>
		<option value='deletepost2'>" . __("Delete", "dmsguestbook") . "</option>
		</select>

		<input class='button-secondary action' type='submit' value='" . __("Apply", "dmsguestbook") . "' onclick=\"return confirm('" . __("Would you really like to do this?", "dmsguestbook") . "');\" /></p>
	    <table class='widefat comments' cellspacing='0'>
		<thead>
		<tr>
		<th style='padding:7px 7px 7px 0px; width:20px;'><input type='checkbox' id='selectall1' name='selectall1' onClick=\"AllSelectboxes1();\"></th>
		<th style='padding:0px 5px 0px 5px; width:20px;'>" . __("ID", "dmsguestbook") . "</th>
	 	<th style='padding:0px 5px 0px 5px; width:100px;'>" . __("Author", "dmsguestbook") . "</th>
	 	<th style='padding:0px 5px 0px 5px; width:300px;'>" . __("Comment", "dmsguestbook") . "</th>
		<th style='padding:0px 5px 0px 5px; width:200px;'>" . __("Action", "dmsguestbook") . "</th>
		</tr>
		</thead>";

			if($num_rows0 == 0) {
			echo "<tr><td></td><td></td><td></td><td style='text-align:center;'><b>" . __("No entries found.", "dmsguestbook") . "</b></td><td></td></tr>";
			}

			foreach ($query0 as $result) {
			// build the data / time variable
				$sec=date("s", "$result->date");
				$min=date("i", "$result->date");
				$hour=date("H", "$result->date");
				$day=date("d", "$result->date");
				$month=date("m", "$result->date");
				$year=date("Y", "$result->date");
				$date = strftime ($options["dateformat"], mktime ($hour, $min, $sec, $month, $day, $year));

				$gbname 	= preg_replace("/[\\\\\"=\(\)\{\}]+/i", "", stripslashes($result->name));
				$gbemail 	= preg_replace("/[^a-z-0-9-_\.@]+/i", "", $result->email);
				$gburl 		= preg_replace("/[^a-z-0-9-_,.:?&%=\/]+/i", "", $result->url);
				$gbip 		= preg_replace("/[^0-9\.]+/i", "", $result->ip);
				$gbmsg 		= preg_replace("/(\<\/textarea\>)||(\\\\)/i", "", stripslashes($result->message));
				$gbguestbook= preg_replace("/[^0-9]+/i", "", $result->guestbook);
				$gbadditional= $result->additional;
				$guestbook = ($result->guestbook +1);

				if($result->email=="") {
				$email = "";
				} else {
					   $email = "<a href='mailto:$result->email'>$result->email</a><br />";
					   }

				if($gburl=="http://" || $gburl=="http://") {
				$concat_name_url = $gbname;
				} else {
					   $concat_name_url = "<a href='$gburl' target='_blank'>$gbname</a>";
					   }

				if($gbadditional !="") {
				$gbadditional = "<br />\"$gbadditional\"";
				}

			echo "<tr>";
			echo "<td><input type='checkbox' id='selectpost' name='selectpost[]' value='$result->id'></td>";
			echo "<td>$result->id</td>";
			echo "<td><b>$concat_name_url</b><br />$email" . "$gbip<br />" . __("Guestbook", "dmsguestbook") . " #$guestbook" . "$gbadditional</td>";
			echo "<td><span style='color:#777777;'>" . __("Submitted on", "dmsguestbook") . " </span>$date<br />$gbmsg</td>";
			$unmark_spam = "<a style='color:#D98500;' href='admin.php?page=Spam&action=unmarkspam&guestbook=$_REQUEST[guestbook]&id=$result->id'>" . __("Unmark Spam", "dmsguestbook") . "</a>";
			$delete_spam = "<a style='color:#D54E21;' href='admin.php?page=Spam&action=deletepost&guestbook=$_REQUEST[guestbook]&id=$result->id' onclick=\"return confirm('" . __("Would you really like to delete this dataset?", "dmsguestbook") . "');\">" . __("Delete", "dmsguestbook") . "</a>";
			echo "<td>";
			echo "$unmark_spam | $delete_spam";
			echo "</td>";
			echo "</tr>";
			}
		echo "</form>";

		echo "<thead>
		<tr>
		<th style='padding:7px 7px 7px 0px; width:20px;'><input type='checkbox' id='selectall2' name='selectall2' onClick=\"AllSelectboxes2();\"></th>
		<th style='padding:0px 5px 0px 5px; width:20px;'>" . __("ID", "dmsguestbook") . "</th>
	 	<th style='padding:0px 5px 0px 5px; width:100px;'>" . __("Author", "dmsguestbook") . "</th>
	 	<th style='padding:0px 5px 0px 5px; width:300px;'>" . __("Comment", "dmsguestbook") . "</th>
		<th style='padding:0px 5px 0px 5px; width:200px;'>" . __("Action", "dmsguestbook") . "</th>
		</tr>
		</thead>";

		echo "
		</table>
		</div>";
		echo "<br /><br />";

		echo "<script type=\"text/javascript\">
			function AllSelectboxes1() {
				countall = document.forms.myForm.selectpost.length;

				selectallbox1 = document.getElementById('selectall1').checked;

 				for(var i = 0; i < countall; i++)
  				{
  				thisElement = document.forms.myForm.selectpost[i];

					if(selectallbox1 == true) {
					thisElement.checked = true;
					document.getElementById('selectall2').checked = true;
					}

					if(selectallbox1 == false) {
					thisElement.checked = false;
					document.getElementById('selectall2').checked = false;
					}
 				}
			}


			function AllSelectboxes2() {
				countall = document.forms.myForm.selectpost.length;

				selectallbox2 = document.getElementById('selectall2').checked;

 				for(var i = 0; i < countall; i++)
  				{
  				thisElement = document.forms.myForm.selectpost[i];

					if(selectallbox2 == true) {
					thisElement.checked = true;
					document.getElementById('selectall1').checked = true;
					}

					if(selectallbox2 == false) {
					thisElement.checked = false;
					document.getElementById('selectall1').checked = false;
					}
 				}
			}
		</script>";
	}
	/* end of Spam */



	/* edit */
	$POSTVARIABLE['editdata'] = (isset($POSTVARIABLE['editdata'])) ? $POSTVARIABLE['editdata'] : '';
	$_REQUEST['htmleditor'] = (isset($_REQUEST['htmleditor'])) ? $_REQUEST['htmleditor'] : '';
	$_REQUEST['gb_flag'] = (isset($_REQUEST['gb_flag'])) ? $_REQUEST['gb_flag'] : '';
	$_REQUEST['selectpost'] = (isset($_REQUEST['selectpost'])) ? $_REQUEST['selectpost'] : '';
	$_REQUEST['tinymce'] = (isset($_REQUEST['tinymce'])) ? $_REQUEST['tinymce'] : '';
	$_REQUEST['id'] = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : '';
	
	if ($POSTVARIABLE['editdata'] == 'edit') {
	   	/* set http(s):// if not exist */
		if(substr("$_REQUEST[gb_url]", 0, 7) != "http://" && substr("$_REQUEST[gb_url]", 0, 8) != "https://") {
	   	$_REQUEST['gb_url']="http://";
		}

		/* Don't quote ampersand, TinyMCE does it*/
		if($_REQUEST['tinymce']==1) {
		$gbmessage = $_REQUEST['gb_message'];
		}

		/* Quote ampersand, quickhtml doesn't it*/
		if($_REQUEST['htmleditor']==1) {
		$gbmessage = str_replace("&","&amp;",$_REQUEST['gb_message']);
		}

		$table_name = $wpdb->prefix . "dmsguestbook";
		$updatedata = $wpdb->query("UPDATE $table_name SET
		name 		= 	'" . mysql_real_escape_string(addslashes($_REQUEST['gb_name'])) . "',
		email 		= 	'" . mysql_real_escape_string($_REQUEST['gb_email']) . "',
		url 		= 	'" . mysql_real_escape_string($_REQUEST['gb_url']) . "',
		ip	 		= 	'" . mysql_real_escape_string($_REQUEST['gb_ip']) . "',
		message 	= 	'" . mysql_real_escape_string(addslashes($gbmessage)) ."',
		guestbook	=	'" . sprintf("%d", $_REQUEST['gb_guestbook']) . "',
		additional	=	'" . $_REQUEST['gb_additional'] . "',
		flag		=	'" . sprintf("%d", $_REQUEST['gb_flag']) . "'
		WHERE id = '" . sprintf("%d", $_REQUEST['id']) . "' ");
  		$update = mysql_query($updatedata);

		if(strlen($_REQUEST['gb_date'])!=0) {
		$part0 = explode(",", $_REQUEST['gb_date']);
		$part1 = explode(".", $part0[0]);
		$part2 = explode(":", $part0[1]);

		if(ctype_digit($part2[0])==1) {$part2[0]=substr($part2[0],0,2);} else{$part2[0]=date("H");}
		if(ctype_digit($part2[1])==1) {$part2[1]=substr($part2[1],0,2);} else{$part2[1]=date("i");}
		if(ctype_digit($part2[2])==1) {$part2[2]=substr($part2[2],0,2);} else{$part2[2]=date("s");}
		if(ctype_digit($part1[1])==1) {$part1[1]=substr($part1[1],0,2);} else{$part1[1]=date("m");}
		if(ctype_digit($part1[0])==1) {$part1[0]=substr($part1[0],0,2);} else{$part1[0]=date("d");}
		if(ctype_digit($part1[2])==1) {$part1[2]=substr($part1[2],0,4);} else{$part1[2]=date("Y");}

		$timestamp = mktime($part2[0],$part2[1],$part2[2],$part1[1],$part1[0],$part1[2]);

			$updatedata2 = $wpdb->query("UPDATE $table_name SET
			date 		= 	'$timestamp'
			WHERE id = '" . sprintf("%d", $_REQUEST['id']) . "'");
  			$update2 = mysql_query($updatedata2);
		}
		message("<b>" . sprintf(__("Dataset (%) was saved", "dmsguestbook"), $_REQUEST['id']) . "</b>", 50, 800);
	}
/* end of manage guestbook entries */


	/* delete multi post / spam */	
	if($POSTVARIABLE['action'] == 'deletepost2') {
	$table_name = $wpdb->prefix . "dmsguestbook";
	$dataset="";
		for($c=0; $c<count($_REQUEST['selectpost']); $c++) {
		$deletedata = $wpdb->query("DELETE FROM $table_name WHERE id = '" . sprintf("%d", "{$_REQUEST['selectpost'][$c]}") . "'");
		$delete = mysql_query($deletedata);
		$dataset .= "{$_REQUEST['selectpost'][$c]}, ";
		}

		if(count($_REQUEST['selectpost']) !=0) {
		message("<b>" . sprintf(__("Dataset (%) was deleted", "dmsguestbook"), $dataset) . "...</b>", 50, 800);
		}
	}

	/* delete single post / spam*/
	$_REQUEST['action'] = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	if($_REQUEST['action'] == 'deletepost') {
	$table_name = $wpdb->prefix . "dmsguestbook";
		$deletedata = $wpdb->query("DELETE FROM $table_name WHERE id = '" . sprintf("%d", "$_REQUEST[id]") . "'");
		$delete = mysql_query($deletedata);
		message("<b>" . sprintf(__("Dataset (%) was deleted", "dmsguestbook"), $_REQUEST['id']) . "...</b>", 50, 800);
	}

	/* delete ALL spam*/
	if($_REQUEST['action'] == 'deleteallpost') {
	$table_name = $wpdb->prefix . "dmsguestbook";
		$deletealldata = $wpdb->query("DELETE FROM $table_name WHERE spam = '1'");
		$delete = mysql_query($deletealldata);
		message("<b>" . __("All Dataset were deleted", "dmsguestbook") . "...</b>", 140, 800);
	}

	/* single spam */
	if($_REQUEST['action'] == 'markasspam') {
	$table_name = $wpdb->prefix . "dmsguestbook";
		$updatedata3 = $wpdb->query("UPDATE $table_name SET
		spam 		= 	'1'
		WHERE id = '" . sprintf("%d", $_REQUEST['id']) . "'");
  		$update3 = mysql_query($updatedata3);
		SpamHam($_REQUEST['id'], "spam");
	}


	/* multi spam  */
	if ($_REQUEST['action'] == 'markasspam') {
	$table_name = $wpdb->prefix . "dmsguestbook";
		for($c=0; $c<count($_REQUEST['selectpost']); $c++) {
		$_REQUEST['selectpost'][$c] = (isset($_REQUEST['selectpost'][$c])) ? $_REQUEST['selectpost'][$c] : '';
			$updatedata4 = $wpdb->query("UPDATE $table_name SET
			spam 		= 	'1'
			WHERE id = '" . sprintf("%d", "{$_REQUEST['selectpost'][$c]}") . "'");
  			$update4 = mysql_query($updatedata4);
  			SpamHam("{$_REQUEST['selectpost'][$c]}", "spam");
  		}
	}

	/* not spam multi */
	if ($_REQUEST['action'] == 'unmarkspam') {
	$table_name = $wpdb->prefix . "dmsguestbook";
		for($c=0; $c<count($_REQUEST['selectpost']); $c++) {
		$_REQUEST['selectpost'][$c] = (isset($_REQUEST['selectpost'][$c])) ? $_REQUEST['selectpost'][$c] : '';
			$updatedata4 = $wpdb->query("UPDATE $table_name SET
			spam 		= 	'0'
			WHERE id = '" . sprintf("%d", "{$_REQUEST['selectpost'][$c]}") . "'");
  			$update4 = mysql_query($updatedata4);
  			SpamHam("{$_REQUEST['selectpost'][$c]}", "ham");
  		}
	}

	/* not spam single */
	if ($_REQUEST['action'] == 'unmarkspam') {
	$table_name = $wpdb->prefix . "dmsguestbook";
		$updatedata4 = $wpdb->query("UPDATE $table_name SET
		spam 		= 	'0'
		WHERE id = '" . sprintf("%d", "$_REQUEST[id]") . "'");
  		$update4 = mysql_query($updatedata4);
  		SpamHam($_REQUEST['id'], "ham");
	}

	/* set admin review or not */
	if ($_REQUEST['action'] == 'adminreview') {
	$table_name = $wpdb->prefix . "dmsguestbook";
		$updatedata5 = $wpdb->query("UPDATE $table_name SET
		flag 		= 	'" . sprintf("%d", "$_REQUEST[flag]") . "'
		WHERE id = '" . sprintf("%d", "$_REQUEST[id]") . "'");
  		$update5 = mysql_query($updatedata5);
	}

	/* multi set admin review set hidden */
	if ($POSTVARIABLE['action'] == 'sethidden') {
	$table_name = $wpdb->prefix . "dmsguestbook";
		for($c=0; $c<count($_REQUEST['selectpost']); $c++) {
			$updatedata6 = $wpdb->query("UPDATE $table_name SET
			flag 		= 	'1'
			WHERE id = '" . sprintf("%d", "{$_REQUEST['selectpost'][$c]}") . "'");
  			$update6 = mysql_query($updatedata6);
  		}
	}

	/* multi set admin review set visible */
	if ($POSTVARIABLE['action'] == 'setvisible') {
	$table_name = $wpdb->prefix . "dmsguestbook";
		for($c=0; $c<count($_REQUEST['selectpost']); $c++) {
			$updatedata7 = $wpdb->query("UPDATE $table_name SET
			flag 		= 	'0'
			WHERE id = '" . sprintf("%d", "{$_REQUEST['selectpost'][$c]}") . "'");
  			$update7 = mysql_query($updatedata7);
  		}
	}


	# #	# # # # # - FUNCTIONS - # # # # # # #

	/* DMSGuestbook first time database install */
	function dmsguestbook_install () {
   		global $wpdb;
   		$table_name = $wpdb->prefix . "dmsguestbook";

			if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	      	$sql = $wpdb->query("CREATE TABLE " . $table_name . " (
	  		id mediumint(9) NOT NULL AUTO_INCREMENT,
	  		name varchar(50) DEFAULT '' NOT NULL,
	  		email varchar(50) DEFAULT '' NOT NULL,
	  		gravatar varchar(32) DEFAULT '' NOT NULL,
	  		url varchar(50) DEFAULT '' NOT NULL,
	  		date int(10) NOT NULL,
	  		ip varchar(15) DEFAULT '' NOT NULL,
	  		message longtext NOT NULL,
	  		guestbook int(2) DEFAULT '0' NOT NULL,
	  		spam int(1) DEFAULT '0' NOT NULL,
	  		additional varchar(50) NOT NULL,
	  		flag int(2) NOT NULL,
	  		UNIQUE KEY id (id)
	  		)DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
      		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      		dbDelta($sql);
   			}
   			update_db_fields();
  		initialize_option();
		}


   	function update_db_fields() {
   		global $wpdb;
   		$table_name = $wpdb->prefix . "dmsguestbook";
   			/* add flag field (> 1.8.0) */
   			if($wpdb->get_var("SHOW FIELDS FROM $table_name LIKE 'flag'")=="") {
   			$sql = $wpdb->query("ALTER TABLE " . $table_name . " ADD flag int(2) NOT NULL");
   			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      		dbDelta($sql);
   			}

   			/* add gravatar field (> 1.10.0) */
   			if($wpdb->get_var("SHOW FIELDS FROM $table_name LIKE 'gravatar'")=="") {
   			$sql = $wpdb->query("ALTER TABLE " . $table_name . " ADD gravatar varchar(32) NOT NULL");
   			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      		dbDelta($sql);
   			}

   			/* add guestbook field (> 1.13.0) */
   			if($wpdb->get_var("SHOW FIELDS FROM $table_name LIKE 'guestbook'")=="") {
   			$sql = $wpdb->query("ALTER TABLE " . $table_name . " ADD guestbook int(2) DEFAULT '0' NOT NULL");
   			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      		dbDelta($sql);
   			}

   			/* add additional field (> 1.14.0) */
   			if($wpdb->get_var("SHOW FIELDS FROM $table_name LIKE 'additional'")=="") {
   			$sql = $wpdb->query("ALTER TABLE " . $table_name . " ADD additional varchar(50) NOT NULL");
   			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      		dbDelta($sql);
   			}

   			/* add spam field (> 1.14.0) */
   			if($wpdb->get_var("SHOW FIELDS FROM $table_name LIKE 'spam'")=="") {
   			$sql = $wpdb->query("ALTER TABLE " . $table_name . " ADD spam int(1) DEFAULT '0' NOT NULL");
   			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      		dbDelta($sql);
   			}
   	}


	/* DMSGuestbook option first time initialize */
	function initialize_option() {
		$options=default_options_array();
		$count=0;
   		unset($save_options);

		while (list($key, $val) = each($options)) {
		if($key=="antispam_key") {$val = RandomAntispamKey();}
		$save_options.="<" . $key . ">" . $val . "</" . $key . ">\r\n";
		}
		if(!get_option("DMSGuestbook_options")) {update_option("DMSGuestbook_options", $save_options);}
	}


	/* create css entries*/
	function make_css() {
		$options = create_options();
		$options["css_customize"] = str_replace("[br]", "\r\n", $options["css_customize"]);

		$part1 = explode("@", $options["css"]);
		$options["css"] = str_replace("[br]", "\r\n", $options["css"]);
		$part1 = explode("@", $options["css"]);

	   	for($x=0; $x<count($part1)-1; $x++) {
       	$part2 = explode("|", $part1[$x]);
	   	}

	   	unset($csscode1);
		$csscode1 = (isset($csscode1)) ? $csscode1 : '';
	   	for($x=0; $x<count($part1)-1; $x++) {
	   	$part2 = explode("|", $part1[$x]);

	   	$cssvar[]=$part2[1];
	   	$csscode1 .= $part2[1] . " {" . $part2[2] . "}\r\n";
	   	}

	   	$replace_tags = array(
	   	"width1",
	   	"width2",
	   	"position1",
	   	"position2",
	   	"separatorcolor",
	   	"bordercolor1",
	   	"bordercolor2",
	   	"navigationcolor",
	   	"fontcolor1",
	   	"navigationsize",
	   	"captcha_color",
	   	);

		$csscode2 = $options["css_customize"];

	    for($x=0; $x<count($replace_tags); $x++) {
	    $rep="{" . $replace_tags[$x] . "}";
	    $csscode1 = str_replace("$rep", $options["$replace_tags[$x]"], $csscode1);
	    $csscode2 = str_replace("$rep", $options["$replace_tags[$x]"], $csscode2);
	    }

       	$csscode1 = str_replace("css_", ".css_", $csscode1);
       	$csscode= $csscode1 . $csscode2;
       	return $csscode;
	}


	/* add css to header */
	function insert_css() {
	$url=get_bloginfo('wpurl');

		$csscode = make_css();

		$abspath = str_replace("\\","/", ABSPATH);
			if(is_writable($abspath . "wp-content/plugins/dmsguestbook/dmsguestbook.css")) {
				echo '<link rel="stylesheet" href="' . $url . '/wp-content/plugins/dmsguestbook/dmsguestbook.css" type="text/css" media="screen" />';
			} else  {
					echo "<style type='text/css'>";
					echo $csscode;
					echo "</style>";
					}
	}
	add_action('wp_head','insert_css');


	/* display the dmsguestbook.php */
	function DMSGuestBook($content) {
	global $DMSGuestbookContent;
		$page_id = (isset($page_id)) ? $page_id : '';
		$options=create_options();
		$multi_page_id = explode(",", $options["page_id"]);
		$multi_language = explode(",", $options["language"]);

		for($m=0; $m<count($multi_page_id); $m++) {
			if(in_array(is_page($multi_page_id[$m]),$multi_page_id)) {
			$page_id = $multi_page_id[$m];
			$multi_gb_id = $m;
			$multi_gb_language = $multi_language[$m];
			}
		}

			if(is_page($page_id) AND $page_id!="")
			{
				$post_id = get_post($page_id);
				if ((!post_password_required($page_id)) || $post_id->post_password == "") 	{

				include_once("dmsguestbook.php");
				$content = $content . $DMSGuestbookContent;
				}
				return $content;
			}
			else	{
					return $content;
					}
		}
	add_action('the_content', 'DMSGuestBook');




/* option array, all options for initialize and reset */
function default_options_array() {

/* css */
$csscontainer = "
position of the guestbook|
css_guestbook_position|
position:relative;
left:{position1}px;
top:{position2}px;@

overall guestbook color|
css_guestbook_font_color|
color:#{fontcolor1};@

Form title property (name, email, url, additional selectbox, message)|
css_form_text|
font-weight:normal;@

name text field|
css_form_namefield|
border:1px solid #{bordercolor2};
width:150px;
color:#{fontcolor1};@

email text field|
css_form_emailfield|
border:1px solid #{bordercolor2};
width:150px;
color:#{fontcolor1};@

url text field|
css_form_urlfield|
border:1px solid #{bordercolor2};
width:150px;
color:#{fontcolor1};@

additional selectbox|
css_form_additional_option|
border:1px solid #{bordercolor2};
width:150px;
color:#{fontcolor1};@

define space between each text fields|
css_form_textfieldspace|
text-align:left;
padding:5px 0px 0px 0px;
margin:0px 0px;@

message text field|
css_form_messagefield|
border:1px solid #{bordercolor2};
width:80%;
height:150px;
color:#{fontcolor1};@

antispam information message|
css_form_antispamtext|
text-align:center;@

antispam image or mathematic figures|
css_form_antispamcontent|
border:1px solid #{bordercolor2};@

antispam image or mathematic figures position|
css_form_antispamcontent_position|
text-align:center;
padding:5px 0px;
margin:0px 0px;@

antispam input text field|
css_form_antispam_inputfield|
width:60px;
border:1px solid #{bordercolor2};
color:#{fontcolor1};@

submit button|
css_form_submit|
color:#{fontcolor1};@

submit button position|
css_form_submit_position|
text-align:center;
padding:20px 0px 10px 0px;@

wrong input text error message|
css_form_errormessage|
color:#bb0000;
font-size: 11px;
text-decoration: none;
font-weight:bold;@

success input text message|
css_form_successmessage|
color:#00bb00;
font-size: 11px;
text-decoration: none;
font-weight:bold;@

visible if the guestbook form is set to 'bottom'|
css_form_link|
font-size:11px;
position:relative;
top:0px;
left:0;@

total guestbook entrys (nr)|
css_navigation_totalcount|
font-size:11px;
left:{position1}px;
width:{width1}%;
text-align:center;
padding:0px 0px 5px 10px;@

guestbook pages (1 2 3 4 [..])|
css_navigation_overview|
left:{position1}px;
width:{width1}%;
text-align:center;
padding:0px 0px 15px 12px;@

selected guestbook page|
css_navigation_select|
color:#bb1100;
text-decoration:none;@

not selected guestbook page|
css_navigation_notselect|
color:#000000;
text-decoration:none;@

navigation char e.g. &lt; &gt;|
css_navigation_char|
color:#{navigationcolor};
font-size:{navigationsize}px;
text-decoration:none;
font-weight:bold;@

navigation char position|
css_navigation_char_position|
left:{position1}px;
width:{width1}%;
padding:0px 0px 0px 10px;
margin:0px 0px 20px 0px;
text-align:center;@

post message number e.g. (24)|
css_post_header1|
font-size:11px;
height:15px;@

post url container|
css_post_header2|
width:20px;
height:15px;@

post email container|
css_post_header3|
width:20px;
height:15px;@

post date & ip address|
css_post_header4|
font-size:11px;
height:15px;@

email image|
css_post_email_image|
height:15px;
width:15px;
border:0px;@

url image|
css_post_url_image|
height:15px;
width:15px;
border:0px;@

guestbook separator (separator between guestbook header and body)|
css_post_separator|
border: 1px solid #{separatorcolor};
height:1px;
width:{width2}%;
text-align:left;
margin:0px 0px 0px 0px;@

content in guestbook body (written text by homepage visitors)|
css_post_message|
font-size:11px;
margin:5px 0px 0px 0px;@

guestbook input data container|
css_form_embedded|
width:{width1}%;
border:1px solid #{bordercolor1};
font-size:12px;
text-align:left;
padding:0px 10px;
margin:0px 0px 0px 0px;
line-height:1.4em;@

guestbook display post container|
css_post_embedded|
width:{width1}%;
border:1px solid #{bordercolor1};
font-size:12px;
text-align:left;
padding:10px 10px;
margin:0px 0px 0px 0px;
line-height:1.4em;@
";
$_SESSION['csscontainer'] = $csscontainer;

$url=get_bloginfo('wpurl');

$options = array(
"supergb" => "0",							/* super guestbook */
"page_id" => "0",							/* id */
"width1" => "95",							/* guestbook width */
"width2" => "35",							/* separator width */
"step" => "10",								/* step */
"messagetext_length" => "0",				/* allowed length of each message text */
"position1" => "0",							/* guestbook position x-axis horizontal */
"position2" => "0",							/* guestbook position y-axis vertical */
"separatorcolor" => "EEEEEE",				/* separator color (separator */
"bordercolor1" => "AAAAAA",					/* outside border color */
"bordercolor2" => "DEDEDE",					/* textfield border color */
"navigationcolor" => "000000",				/* navigation char color*/
"fontcolor1" => "000000",					/* font color */
"forwardchar" => ">",						/* forward char */
"backwardchar" => "<",						/* backward char */
"navigationsize" => "20",					/* forward / backward char size */
"require_email" => "0",						/* require email */
"require_url"=> " 0",						/* require url */
"require_antispam" => "1",					/* require antispam */
"antispam_key" => "0",						/* random key to prevent spam*/
"recaptcha_publickey" => "0",				/* reCAPTCHA public key */
"recaptcha_privatekey" => "0",				/* reCAPTCHA private key */
"akismet" => "0",							/* avtivate Akismet */
"akismet_action" => "0",					/* 0=move to spam folder, 1=block spam */
"show_url" => "1",							/* show url */
"show_email" => "1",						/* show email */
"show_ip" => "0",							/* show ip */
"ip_mask" => "123.123.123.*",				/* ip mask */
"captcha_color" => "000000",				/* captcha color */
"dateformat" => "%a, %e %B %Y %H:%M:%S %z",	/* date format */
"setlocale" => "en_EN",						/* setlocale */
"offset" => "0",							/* date offset */
"formpos" => "top",							/* form position */
"formposlink" => "-",						/* form link if is set formpos = bottom */
"send_mail" => "0",							/* notification mail */
"mail_adress" => "name@example.com",		/* notification mail to this adress */
"mail_method" => "Mail",					/* using the php build in method mail or an external smtp server */
"smtp_host" => "smtp.example.tld",			/* smtp host */
"smtp_port" => "25",						/* smtp port */
"smtp_username" => "MyUsername",			/* username if authentification is required */
"smtp_password" => "MyPassword",			/* passwort if authentification is required */
"smtp_auth" => "0",							/* activate the authentification */
"smtp_ssl" => "0",							/* using ssl encryption */
"sortitem" => "DESC",						/* each post sort by*/
"dbid" => "0",								/* show database id instead continous number*/
"language" => "0",							/* language */
"email_image_path" => "$url/wp-content/plugins/dmsguestbook/img/email.gif",	/* email image path */
"website_image_path" => "$url/wp-content/plugins/dmsguestbook/img/website.gif", /* website image path */
"admin_review" => "0",						/* admin must review every post before this can display on page */
"url_overruled" => "0",						/* you can overrule the url if you have trouble with the guestbook form submit */
"gravatar" => "0",							/* gravatar */
"gravatar_rating" => "G",					/* gravatar rating */
"gravatar_size" => "40",					/* gravatar image size in pixel */
"mandatory_char" => "*",					/* mandatory char which you want display on your site */
"form_template" => "default.tpl",			/* form template */
"post_template" => "default.tpl",			/* post template */
"nofollow" => "1",							/* activate the nofollow tag for posted url's */
"additional_option" => "none",				/* an additional selectbox. see in your dmsguestbook/module folder for examples */
"additional_option_title" => "-",			/* define a input form title text for additional selectbox */
"show_additional_option" => "0",			/* show additional text in each guestbook post. Edit this appearance in template/post/default.tpl */
"role1" => "Administrator",					/* roles for: database / guestbook / language settings, phpinfo */
"role2" => "Administrator",					/* roles for: entries */
"role3" => "Administrator",					/* roles for: spam */
"css" => "$csscontainer",					/* all css settings */
"css_customize" => "a.css_navigation_char:hover {text-decoration:none; color:#{navigationcolor};}
a.css_navigation_select:hover {text-decoration:none; color:#bb1100;}
a.css_navigation_notselect:hover {text-decoration:none; color:#000000;}
img.css_post_url_image {border:0px;}
img.css_post_email_image {border:0px;}", /* custom css */
);

return $options;
}



	/* reset DMSGuestbook  */
	function default_option() {
		$options=default_options_array();
   		unset($save_options);
		$save_options = (isset($save_options)) ? $save_options : '';
		
		while (list($key, $val) = each($options)) {
		if($key=="antispam_key") {$val = RandomAntispamKey();}
		$save_options.="<" . $key . ">" . $val . "</" . $key . ">\r\n";
		}
		update_option("DMSGuestbook_options", $save_options);
	  	message("<b>" . __("Restore default settings", "dmsguestbook") . "...</b> <br />" . __("Don't forget to set the page id.", "dmsguestbook"), 280, 800);
	}



	/* DMSGuestbook admin message handling */
	function message($message_text, $top, $left) {
		$date=date("H:i:s");
		echo "<div style='position:absolute; top:" . $top . "px; left:" . $left . "px;' id='message' class='updated fade'><p>
		$message_text <br /></p><p style='font-size:10px;'>[$date]</p>
		<img  style='position:absolute; top:-5px; left:5px; height:13px; width:9px;'
		src='../wp-content/plugins/dmsguestbook/img/icon_pin.png'></div>";
	}


	/* show phpinfo() */
	function dmsguestbook3_meta_description_option_page() {
		echo "<div class='wrap'>";
		phpinfo();
		echo "</div>";
	}


	/* show permission */
	function truetype_permission($file) {
		$abspath = getcwd();
    	$abspath = str_replace("\\","/", $abspath);
    	clearstatcache();
		$fileperms=fileperms("../wp-content/plugins/dmsguestbook/captcha/$file");
		$fileperms = decoct($fileperms);
		echo "<b>" . $file . "</b>" . __("have permission", "dmsguestbook") . ": " . substr($fileperms, 2, 6);
	}


	/* options */
	function create_options() {
	$missing_entries = (isset($missing_entries)) ? $missing_entries : '';
	$missing_entries_for_fixed_update = (isset($missing_entries_for_fixed_update)) ? $missing_entries_for_fixed_update : '';
	
	$options=default_options_array();
	$stringtext = get_option('DMSGuestbook_options');

			$p=0;
			$c=0;
			reset($options);
			while (list($key, $val) = each($options)) {			
			$part1 = explode("<" . $key . ">", $stringtext);
			$part2 = explode("</" . $key . ">", isset($part1[1]) ? $part1[1] : '');

				if($part2[0]=="") {
				$missing_entries_for_fixed_update .= "<" . $key . ">" . $val . "</" . $key . ">";
				$missing_entries .= "&lt;" . $key . "&gt;" . $val . "&lt;/" . $key . "&gt;" . "<br />"; $p++;
				}
			$opt["$key"] = html_entity_decode($part2[0], ENT_QUOTES);
			/* cut invalid char XSS prevent */
				/* url overruled, need / */
				if($key=="url_overruled") {
						$opt["$key"] = preg_replace("/[^a-z-0-9-~_,.:?&%=\/]+/i", "", html_entity_decode($part2[0]), ENT_QUOTES);
				}
				elseif($key=="email_image_path") {
						$opt["$key"] = preg_replace("/[^a-z-0-9-~_,.:?&%=\/]+/i", "", html_entity_decode($part2[0]), ENT_QUOTES);
				}
				elseif($key=="website_image_path") {
						$opt["$key"] = preg_replace("/[^a-z-0-9-~_,.:?&%=\/]+/i", "", html_entity_decode($part2[0]), ENT_QUOTES);
				}
				elseif($key=="css") {
					   $opt["$key"] = preg_replace("/[\<\>\"\'\\`\\´]+/i", "", html_entity_decode($part2[0]), ENT_QUOTES);
					   }
				elseif($key=="css_customize") {
					   $opt["$key"] = preg_replace("/[\<\>\"\'\\`\\´]+/i", "", html_entity_decode($part2[0]), ENT_QUOTES);
					   }
				elseif($key=="messagetext_length") {
					   $opt["$key"] = preg_replace("/[^0-9]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="formposlink") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="forwardchar") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="backwardchar") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="dateformat") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="setlocale") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="mail_adress") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="smtp_host") {
				       $opt["$key"] = preg_replace("/[^a-z-0-9.]+/i", "", html_entity_decode($part2[0]), ENT_QUOTES);
					   }
				elseif($key=="smtp_username") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="smtp_password") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="mandatory_char") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="additional_option_title") {
					   $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="gravatar_size") {
					   $opt["$key"] = preg_replace("/[^0-9]/i", "", html_entity_decode($part2[0], ENT_QUOTES));
					   }
				elseif($key=="recaptcha_publickey") {
					   $opt["$key"] = preg_replace("/[\<\>\"\'\\`\\´]+/i", "", html_entity_decode($part2[0]), ENT_QUOTES);
					   }
				elseif($key=="recaptcha_privatekey") {
					   $opt["$key"] = preg_replace("/[\<\>\"\'\\`\\´]+/i", "", html_entity_decode($part2[0]), ENT_QUOTES);
					   }
				elseif($key=="antispam_key") {
					   $opt["$key"] = $part2[0];
					   }
						else {
					 		 $opt["$key"] = preg_replace("/[\"\'\`\´\/\\\\]/i", "", $part2[0]);
					 		 }
			$c++;
			}
			if($missing_entries!="") {
			unset($_SESSION["missing_options"]);
			unset($_SESSION["missing_options_fixed"]);
			$_SESSION["missing_options"]=$missing_entries;
			$_SESSION["missing_options_fixed_update"]=$missing_entries_for_fixed_update;
			}

		return $opt;
	}


	function settablecolor($setcolor,$tablecolor) {
	if($setcolor==1) {$colorresult="F9F9F9";}
	if($setcolor==2) {$colorresult="FFFFFF";}
	if($setcolor==3) {$colorresult="F5F5F5";}

	if($tablecolor==1) {$colorresult="style='background-color:#$colorresult; padding:2px 2px;'"; }
	if($tablecolor==2) {$colorresult="style='background-color:#$colorresult; padding:0px 2px; text-align:center;'"; }
	return $colorresult;
	}


	/* advanced file */
	function check_writable($folder, $file) {
	$abspath = str_replace("\\","/", ABSPATH);
		if(is_writable($abspath . "wp-content/plugins/dmsguestbook/" . $folder . $file)) {
		echo "<br />$_REQUEST[file] <font style='color:#00bb00;'>" . __("is writable!", "dmsguestbook") . "</font><br />" . sprintf(__("Set %s readonly again when your finished to customize!", "dmsguestbook"), $file);
		$save_advanced_button = "<input class='button-primary action' style='font-weight:bold; margin:10px 0px; width:250px;' type='submit' value='" . __("Save", "dmsguestbook") . "' />";
		return $save_advanced_button;
		}
		else {
	         echo "<br />" . sprintf(__("%s is <font style='color:#bb0000;'>not writable!", "dmsguestbook"), $_REQUEST['file']) . "
	         </font><br />" . sprintf(__("Set the write permission for %s to customize this file.", "dmsguestbook"), $_REQUEST['file']);
	         return $save_advanced_button="";
	         }

	}


	/* missing options */
	function missing_options() {
	global $wpdb;
   	$table_name = $wpdb->prefix . "dmsguestbook";
		echo "<br /><br />
		<hr style='width:100%;border:1px solid #cc0000;'></hr>
		<b>" . __("This option(s) was not found in", "dmsguestbook") . $table_name . " -> " . $wpdb->prefix . "options -> DMSGuestbook_options:</b><br />
		$_SESSION[missing_options]
		<hr style='width:100%;border:1px solid #cc0000;'></hr>";
	}



	/* generate all options */

	function OneInput($key, $label, $type, $entries, $value, $char_lenght, $additional, $style, $tooltip, $jscript, $base64) {
		$part1 = explode("@", $label);
		$part2 = explode("@", $additional);
		unset($data);
		$data  = (isset($data)) ? $data : '';
			/* If base64 is active */
			if(BASE64 == 1 && $base64 == 1) {
			$value = base64_decode($value);
			}
			for($x=0; $x<=$entries; $x++) {
			if($tooltip!=""){$showtooltip="<a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='#tooltip_$key' class='tooltiplink'>?</a><div id='tooltip_$key' class='tooltip'>$tooltip</div>";}

			$data .= "<table style='width:95%;' border='0'><colgroup><col width='40%'><col width='55%'><col width='5%'></colgroup><tr>
			<td>$part1[$x]</td>
			<td><input style='$style;' type='$type' name='$key' id='$key' value='$value' maxlength='$char_lenght' $jscript />
			<input type='hidden' name='base64-$key' value='$base64' />$part2[$x]</td><td style='text-align:right;'>$showtooltip</td></tr></table>";
			}
		return $data;
	}


	function ColorInput($key, $label, $id, $value, $additional, $style, $tooltip) {
		$part1 = explode("@", $label);
		$part2 = explode("@", $additional);
		unset($data);
		$data  = (isset($data)) ? $data : '';
		if($tooltip!=""){$showtooltip="<a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='#tooltip_$key' class='tooltiplink'>?</a><div id='tooltip_$key' class='tooltip'>$tooltip</div>";}

		$colorid_div = "Color" . $id . "_div";
		$data = "<table style='width:95%;' border='0'><colgroup><col width='40%'><col width='55%'><col width='5%'></colgroup><tr><td>$part1[0]</td><td><div id=\"$colorid_div\" style=\"border:1px solid;background-color:#$value;float:left;width:25px;height:25px;\"></div><input name='$key' id='$key' type='text' size='6' maxlenght='6' value='$value'>$part2[0]</td><td style='text align:right;'>$showtooltip</td></tr></table>";

		return $data;
	}

	function CheckBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript) {
		$part1 = explode("@", $label);
		$part2 = explode("@", $additional);
		unset($data);
		$data  = (isset($data)) ? $data : '';
		for($x=1; $x<=$entries+1; $x++) {
		$check="check" . $x;
			if($tooltip!=""){$showtooltip="<a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='#tooltip_$key' class='tooltiplink'>?</a><div id='tooltip_$key' class='tooltip'>$tooltip</div>";}

			if($value==$x) {$check = "checked";} else {$check="";}
			$c=$x-1;
			$data .= "<table style='width:95%;' border='0'><colgroup><col width='40%'><col width='55%'><col width='5%'></colgroup><tr><td>$part1[$c]</td><td><input style='$style;' type='checkbox' name='$key' id='$key' value='$x' $check $jscript /> $part2[$c]</td><td style='text-align:right;'>$showtooltip</td></tr></table>";
			}
		return $data;
	}

	function RadioBoxes($key, $label, $value, $entries, $additional, $style, $tooltip, $jscript) {
		$part1 = explode("@", $label);
		$part2 = explode("@", $additional);
		unset($data);
		$data  = (isset($data)) ? $data : '';
		for($x=0; $x<=$entries; $x++) {
		$check="check" . $x;
			if($tooltip!=""){$showtooltip="<a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='#tooltip_$key[$x]' class='tooltiplink'>?</a><div id='tooltip_$key[$x]' class='tooltip'>$tooltip</div>";}
			
			if($value==$x) {$check = "checked";} else {$check="";}
			$part2[$x] = (isset($part2[$x])) ? $part2[$x] : '';
			$data .= "<table style='width:95%;' border='0'><colgroup><col width='40%'><col width='55%'><col width='5%'></colgroup><tr><td>$part1[$x]</td><td><input style='$style;' type='radio' name='$key' id='$key' value='$x' $check $jscript />$part2[$x]</td><td style='text-align:right;'>$showtooltip</td></tr></table>";
			}
		return $data;
	}

	function SelectBox($key, $label, $option, $value, $additional, $style, $tooltip, $jscript) {
		$part1 = explode("@", $option);
		$part2 = explode("@", $additional);
		unset($data);
		$data  = (isset($data)) ? $data : '';
			if($tooltip!=""){$showtooltip="<a style='font-weight:bold;background-color:#bb1100;color:#fff;padding:3px;text-decoration:none;' href='#tooltip_$key' class='tooltiplink'>?</a><div id='tooltip_$key' class='tooltip'>$tooltip</div>";}

			$data .= "<table style='width:95%;' border='0'><colgroup><col width='40%'><col width='55%'><col width='5%'><colgroup><tr><td>$label</td><td><select style='$style;' name='$key' id='$key' $jscript>";
			$data .= "<option selected>$value</option>";
			for($x=0; $x<=count($part1)-2; $x++) {
				if($part1[$x] != $value) {
				$data .= "<option value='$part1[$x]'>$part1[$x]</option>";
				}
			}
			$data .= "</select></td><td style='text-align:right;'>$showtooltip</td></tr></table>";
		return $data;
	}


	/* antispam key generator */
	function RandomAntispamKey() {
	$len=20;
	srand(date("U"));
    $possible="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890+*%&(){}[]=?!$-_.,;:/\#~";
    unset($str);
	$str  = (isset($str)) ? $str : '';
    	while(strlen($str)<$len) {
    	$str.=substr($possible,(rand()%(strlen($possible))),1);
		}
		return($str);
	}


	function CheckAkismet() {
		global $wpdb;
		$table_option = $wpdb->prefix . "options";
		$query_akismet = $wpdb->get_results("SELECT option_value FROM $table_option WHERE option_name = 'wordpress_api_key'");
		$num_rows_akismet = $wpdb->num_rows;

			foreach ($query_akismet as $result) {
			$akismet_description = "$result->option_value";
			}

		if($num_rows_akismet == 0) {
		return(0);
		}
		else {
		     return $akismet_description;
		     }

	}

	/* Submit spam or ham */
	function SpamHam($id, $type) {
	global $wpdb;
	$table_name = $wpdb->prefix . "dmsguestbook";
		$selectspam = $wpdb->get_results("SELECT * FROM $table_name WHERE id = '" . sprintf("%d", $id) . "'");
		$num_rows_spam = $wpdb->num_rows;

		include_once "../wp-content/plugins/dmsguestbook/microakismet/class.microakismet.inc.php";
		$url=get_bloginfo('wpurl');

			foreach ($selectspam as $result) {
  			// The array of data we need
			$vars    = array();
			$vars["user_ip"]              = $result->ip;
   			$vars["comment_content"]      = $result->message;
   			$vars["comment_author"]       = $result->name;
   			$vars["comment_author_url"]	  = $result->url;
   			$vars["comment_author_email"] = $result->email;
			$vars["comment_type"]		  = "comment";

			$CheckAkismet = CheckAkismet();

			// ... Add vars as before ...
			$akismet	= new MicroAkismet(  $CheckAkismet,
										     $url,
										     "$url/1.0" );

				if($type=="spam" && $CheckAkismet !="") {
				$akismet->spam( $vars );
				}

				if($type=="ham" && $CheckAkismet !="") {
				$akismet->ham( $vars );
				}
			}
	}

	function CheckRole($level, $msg) {
		$role = (isset($role)) ? $role : '';
		$userlevel="";
		$roles = array("0","1","2","3","4","5","6","7","8","9","10");
		for($x=0; $x<count($roles); $x++) {
			if(current_user_can("level_" . $x) == 1) {
			$userlevel = $x;
			}
		}

		if($msg==1) {
		echo "<b>" . sprintf(__("You need <i>%s</i> rights to have access to this page.", "dmsguestbook"), $level) . "</b>";
		}

		if($level == "Administrator" && in_array($userlevel, array("8","9","10")) ) {
		$role = $userlevel;
		}
		if($level == "Editor" && in_array($userlevel, array("5","6","7","8","9","10")) ) {
		$role = $userlevel;
		}
		if($level == "Author" && in_array($userlevel, array("2","3","4","5","6","7","8","9","10")) ) {
		$role = $userlevel;
		}
		if($level == "Contributor" && in_array($userlevel, array("1","2","3","4","5","6","7","8","9","10"))) {
		$role = $userlevel;
		}
		if($level == "Subscriber" && in_array($userlevel, array("0","1","2","3","4","5","6","7","8","9","10"))) {
		$role = $userlevel;
		}
		if($userlevel == "") {
		$role = 10;
		}
	return $role;
	}
?>
