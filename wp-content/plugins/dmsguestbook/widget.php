<?php
/*
Plugin Name: DMSGuestbook widget
Plugin URI: http://DanielSchurter.net
Description: Add a DMSGuestbook widget.
Author: Daniel M. Schurter
Version: 2.52
Author URI: http://DanielSchurter.net
*/

/* initializing */
function widget_dmsguestbook_init() {
	
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;
	function widget_dmsguestbook_control() {
	
		$options = $newoptions = get_option('widget_dmsguestbook');
		if ( !is_array($newoptions) )
			$newoptions = array(
				'title'=> 'Guestbook',
				'guestbook_id'=> '0',
				'entries'=> '5',
				'wordcut'=> '25',
				'dateformat'=> '%a, %e %b %Y, %H:%M:%S %z',
				'gravatar_rating' => 'G',
				'gravatar_size' => '20',
				'widget_header'=> '<div style="background-color:#F7CDC1;padding:5px; border:1px dashed #dd8888;">',
				'widget_footer'=> '</div>',
				'widget_data'=> '<b style="font-weight:bold;">\\r\\n<a href="LINK1">SHOW_NAME</a>\\r\\n</b>\\r\\n<br />\\r\\nSHOW_MESSAGE\\r\\n<br />\\r\\n<br />');

			/* check the old HTTP_POST_VARS and new $_POST var */
			if(!empty($HTTP_POST_VARS)) {
			$POSTVARIABLE   = $HTTP_POST_VARS;
			}
			else {
		 	     $POSTVARIABLE = $_POST;
		 	     }

			$POSTVARIABLE['submit'] = (isset($POSTVARIABLE['submit'])) ? $POSTVARIABLE['submit'] : '';
			if ( $POSTVARIABLE['submit'] ) {

				/* prevent XSS */
				$remove_tags="(\<textarea\>||\<\/textarea\>)";

				$newoptions['title'] = mysql_real_escape_string(preg_replace("/[[:punct:]]/", "", $POSTVARIABLE['DMSGuestbook_title']));
				$newoptions['guestbook_id'] = sprintf("%s", $POSTVARIABLE['DMSGuestbook_guestbook_id']);
				$newoptions['entries'] = sprintf("%d", $POSTVARIABLE['DMSGuestbook_entries']);
				$newoptions['wordcut'] = sprintf("%d", $POSTVARIABLE['DMSGuestbook_wordcut']);
				$newoptions['dateformat'] = mysql_real_escape_string(preg_replace("/[\"\'\`\´\/\\\\]/i", "", $POSTVARIABLE['DMSGuestbook_dateformat']));
				$newoptions['gravatar_rating'] = sprintf("%s", $POSTVARIABLE['DMSGuestbook_gravatar_rating']);
				$newoptions['gravatar_size'] = sprintf("%d", $POSTVARIABLE['DMSGuestbook_gravatar_size']);
				$newoptions['widget_header'] = mysql_real_escape_string(preg_replace("/$remove_tags/", "", $POSTVARIABLE['DMSGuestbook_widget_header']));
				$newoptions['widget_data'] = mysql_real_escape_string(preg_replace("/$remove_tags/", "", $POSTVARIABLE['DMSGuestbook_widget_data']));
				$newoptions['widget_footer'] = mysql_real_escape_string(preg_replace("/$remove_tags/", "", $POSTVARIABLE['DMSGuestbook_widget_footer']));
				}

			if ($options != $newoptions) {
				$options = $newoptions;
				update_option('widget_dmsguestbook', $options);
				}


global $wpdb;
$table_posts = $wpdb->prefix . "posts";
$options0 = get_option('DMSGuestbook_options');
$part3 = explode("<page_id>", $options0);
$part4 = explode("</page_id>", $part3[1]);
$multi_page_id = explode(",", $part4[0]);
$dataS = (isset($dataS)) ? $dataS : '';
$data = (isset($data)) ? $data : '';

for($m=0; $m<count($multi_page_id); $m++) {
	$m2 = $m + 1;
	$query_posts = $wpdb->get_results("SELECT ID, post_title FROM $table_posts WHERE ID = $multi_page_id[$m] ORDER BY id ASC");
	if($options['guestbook_id'] != $m) {
		foreach ($query_posts as $result) {
		$data .= "<option value='$m,$multi_page_id[$m]'>Guestbook: #$m2 (Page: $result->post_title || ID: $result->ID)</option>";
		}
	}
	else {
		 	foreach ($query_posts as $result) {
	     	$dataS .= "<option value='$m,$multi_page_id[$m]' selected>Guestbook: #$m2 (Page: $result->post_title || ID: $result->ID)</option>";
	     	}
	     }
}
$data = $dataS . $data;

		echo "<b>" . __("Headline", "dmsguestbook") . "</b><br />";
		echo '<input class="widefat" id="DMSGuestbook_title" name="DMSGuestbook_title" type="text" value="'.
		str_replace("\\", "", $options['title']).'" />
		<br /><i style="font-zize:0.8em;">' . __("This will be shown on top of you widget.", "dmsguestbook") . '</i><br /><br />';

		echo "<b>" . __("Page id", "dmsguestbook") . "</b><br />";
		echo '<select class="widefat" id="DMSGuestbook_guestbook_id" name="DMSGuestbook_guestbook_id">' . $data . '</select>
		<br /><i style="font-zize:0.8em;">' . __("Which guestbook would you like to display on your sidebar?", "dmsguestbook") . '</i><br /><br />';

		echo "<b>" . __("Number", "dmsguestbook") . "</b><br />";
		echo '<input style="width:30px;" type="text" name="DMSGuestbook_entries" value="'.$options['entries'].'">
		<br /><i style="font-zize:0.8em;">' . __("How many guestbook entries do you want to see on your widget?", "dmsguestbook") . '</i><br /><br />';

		echo "<b>" . __("Lenght", "dmsguestbook") . "</b><br />";
		echo '<input style="width:30px;" type="text" name="DMSGuestbook_wordcut" value="'.$options['wordcut'].'">
		<br /><i style="font-zize:0.8em;">' . __("Cut message text after X characters.", "dmsguestbook") . '</i><br /><br />';

		echo "<b>" . __("Date", "dmsguestbook") . "</b><br />";
		echo '<input class="widefat" type="text" name="DMSGuestbook_dateformat" value="'.$options['dateformat'].'">
		<br /><i style="font-zize:0.8em;">' . __("Set the date and time format.", "dmsguestbook") . '<br />' . __("More infos", "dmsguestbook") . ':
		<a href="http://www.php.net/manual/en/function.strftime.php" target="_blank">
		http://www.php.net/manual/en/function.strftime.php</a></i><br /><br />';

		echo "<b>" . __("Gravatar rating & Gravatar size", "dmsguestbook") . "</b><br />";
		echo '<select style="width:50px;" id="DMSGuestbook_gravatar_rating" name="DMSGuestbook_gravatar_rating">';
		$rating=array("G","PG","R","X");
		for($x=0; $x<count($rating); $x++) {
			if($options['gravatar_rating'] == $rating[$x] ) {
			echo '<option>' . $options['gravatar_rating'] . '</option>';
			}
			else {
			     echo '<option>' . $rating[$x] . '</option>';
			     }
		}
		echo '</select>
		<input style="width:50px;" id="DMSGuestbook_gravatar_size" name="DMSGuestbook_gravatar_size" type="text" value="' .
		str_replace("\\", "", $options['gravatar_size']).'" /> pixel
		<br /><i style="font-zize:0.8em;"></i><br /><br />';

		echo __("More infos", "dmsguestbook") . ": <a href='http://w3schools.com/html/default.asp' target='_blank'>HTML</a> &
		<a href='http://www.w3.org/Style/CSS/learning' target='_blank'>CSS</a><br />" .
		__("Don't forget to close all tags!", "dmsguestbook") . "<br /><br />";

		echo "<span style='font-size:0.8em;'>
		<a id='default_widget' class='default_widget'>" . __("Default", "dmsguestbook") . "</a> |
		<a id='example_widget1' class='example_widget1'>" . __("Example", "dmsguestbook") . " #1</a> |
		<a id='example_widget2' class='example_widget2'>" . __("Example", "dmsguestbook") . " #2</a> |
		<a id='example_widget3' class='example_widget3'>" . __("Example", "dmsguestbook") . " #3</a> |
		<a id='example_widget4' class='example_widget4'>" . __("Example", "dmsguestbook") . " #4</a> |
		<a id='example_widget5' class='example_widget5'>" . __("Example", "dmsguestbook") . " #5</a>
		</span><br /><br />";

		echo "<b>" . __("Header", "dmsguestbook") . "</b><br />";
		echo '<textarea style="width:100%;height:120px;background-color:#C4D3FF;border:1px solid #7F9DB9;font-size:0.8em"
		id="DMSGuestbook_widget_header" name="DMSGuestbook_widget_header">' .
		str_replace("\\","", str_replace("\\r\\n","\n", str_replace("\\n","\r\n", str_replace("\\r","", $options['widget_header'])))).'</textarea><br />';

		echo "<b>" . __("Data", "dmsguestbook") . "</b><br />";
		echo "<textarea style='width:100%;height:250px;background-color:#FFD3C4;border:1px solid #7F9DB9;font-size:0.8em'
		id='DMSGuestbook_widget_data' name='DMSGuestbook_widget_data'>" .
		str_replace("\\","", str_replace("\\r\\n","", str_replace("\\n","\r\n", str_replace("\\r","", $options['widget_data'])))) . "</textarea>";

		echo "<b>" . __("Footer", "dmsguestbook") . "</b><br />";
		echo "<textarea style='width:100%;height:120px;background-color:#C4D3FF;
		border:1px solid #7F9DB9;font-size:0.8em' id='DMSGuestbook_widget_footer' name='DMSGuestbook_widget_footer'>" .
		str_replace("\\","", str_replace("\\r\\n","\n", str_replace("\\n","\r\n", str_replace("\\n","", $options['widget_footer'])))) . "</textarea>";

		echo '<input type="hidden" id="submit" name="submit" value="1" />';
  ?>

  <!-- Widget examples -->
  <?php
  wp_deregister_script( 'jquery' );
  wp_enqueue_script( 'jquery', '../wp-content/plugins/dmsguestbook/js/jquery-1.7.2.js', array(), '' );
  ?>
  
<script type="text/javascript">

	jQuery('.default_widget').click(function () {
		var header = "<div style=\"background-color:#F7CDC1;padding:5px;border:1px dashed #dd8888;\">";
		var widget = "<b style=\"font-weight:bold;\">\n<a href=\"LINK1\">SHOW_NAME</a>\n</b>\n<br />\nSHOW_MESSAGE\n<br />\n<br />";
		var footer = "</div>";

		jQuery("[id$='DMSGuestbook_widget_header']").text(header);
		jQuery("[id$='DMSGuestbook_widget_data']").text(widget);
		jQuery("[id$='DMSGuestbook_widget_footer']").text(footer);
		switch_color(this);
	});
	
	jQuery('.example_widget1').click(function () {
		var header = "<div style=\"border:1px solid #333333;padding:5px;\">";
     	var widget = "<b style=\"font-weight:bold;\">\n(SHOW_NR) SHOW_NAME\n</b>\n<br />\n<a href=\"LINK1\">SHOW_MESSAGE</a>\n<br />\n<br />";
  		var footer = "</div>";
  		jQuery("[id$='DMSGuestbook_widget_header']").text(header);
    	jQuery("[id$='DMSGuestbook_widget_data']").text(widget);
    	jQuery("[id$='DMSGuestbook_widget_footer']").text(footer);
		switch_color(this);
	});
	
	jQuery('.example_widget2').click(function () {
		var header = "<div style=\"background-color:#000000;padding:5px;\">";
     	var widget = "<b style=\"font-weight:bold;color:#ffffff;\">\nGRAVATAR (SHOW_NR) <a href=\"LINK1\">SHOW_NAME</a>\n</b>\n<br />\n<i style=\"color:#bb1100;\">SHOW_MESSAGE</i>\n<br />\n<br />";
  		var footer = "</div>";
  		jQuery("[id$='DMSGuestbook_widget_header']").text(header);
    	jQuery("[id$='DMSGuestbook_widget_data']").text(widget);
    	jQuery("[id$='DMSGuestbook_widget_footer']").text(footer);
		switch_color(this);
	});
	
	jQuery('.example_widget3').click(function () {
		var header = "<div style=\"background-image: url(wp-content/plugins/dmsguestbook/img/testimage.gif);padding:5px;border:1px dashed #000000;\">";
     	var widget = "<b>Nr:</b> SHOW_NR\n<br />\n<b>ID:</b> SHOW_ID\n<br />\n<b>Name:</b> <a href=\"LINK1\">SHOW_NAME</a>\n<br />\n<b>Message:</b>SHOW_MESSAGE\n<br />\n<b>Date:</b> SHOW_DATE\n<br />\n<br />";
     	var footer = "</div>";
  		jQuery("[id$='DMSGuestbook_widget_header']").text(header);
    	jQuery("[id$='DMSGuestbook_widget_data']").text(widget);
    	jQuery("[id$='DMSGuestbook_widget_footer']").text(footer);
		switch_color(this);
	});
	
	jQuery('.example_widget4').click(function () {
		var header = "<div style=\"width:100%;height:15px;background-color:#bb1100;\"></div>\n<div style=\"background-color:#dfdfdf;padding:5px;text-align:right;border:1px solid #bb1100;\">";
     	var widget = "<b style=\"font-size:15px;\"><a href=\"LINK1\">SHOW_MESSAGE</a></b>\n<br />\nBy: <span style=\"text-transform:uppercase;\">[SHOW_NAME]</span>\n<br />\n<br />\n<br />";
  		var footer = "</div>\n<div style=\"width:100%;height:15px;background-color:#bb1100;\"></div>";
  		jQuery("[id$='DMSGuestbook_widget_header']").text(header);
    	jQuery("[id$='DMSGuestbook_widget_data']").text(widget);
    	jQuery("[id$='DMSGuestbook_widget_footer']").text(footer);
		switch_color(this);
	});
	
	jQuery('.example_widget5').click(function () {
		var header = "<div style=\"background-color:#000000;color:#088a4b;padding:3px;letter-spacing:3px;\">";
     	var widget = "<span style=\"font-size:8px;\">\nSHOW_NAME:~$ ./script.sh\n</span>\n<br />\n<span style=\"font-size:8px;\">\nid: (SHOW_ID)<br />\ndate: SHOW_DATE<br />\nmsg: SHOW_MESSAGE\n</span>\n<br />\n<br />\n<br />";
  		var footer = "</div>";
  		jQuery("[id$='DMSGuestbook_widget_header']").text(header);
    	jQuery("[id$='DMSGuestbook_widget_data']").text(widget);
    	jQuery("[id$='DMSGuestbook_widget_footer']").text(footer);
		switch_color(this);
	});
	
	function switch_color(selClass) {
		jQuery('.default_widget').css("text-decoration", "none");
		jQuery('.example_widget1').css("text-decoration", "none");
		jQuery('.example_widget2').css("text-decoration", "none");
		jQuery('.example_widget3').css("text-decoration", "none");
		jQuery('.example_widget4').css("text-decoration", "none");
		jQuery('.example_widget5').css("text-decoration", "none");
		jQuery(selClass).css("text-decoration", "underline");
	}
	
</script>

<?php

		/* describe options */
		$url=get_bloginfo('wpurl');
		echo "<span style='font-size:0.8em;'><br /><b style='font-size:16px;text-decoration: underline;'>" . __("Options", "dmsguestbook") . "</b><br />
		<br />
		<b>LINK1</b> - " . __("Auto link to a guestbook post", "dmsguestbook") . "<br />" .
	    __("Example", "dmsguestbook") . ": <i style='color:#dd0000;'>&lt;a href=\"LINK1\"&gt;</i><br />" .
		__("LINK1 is trying to generate a link to guestbook with the \"page_id=id\" statement which is defined under page id.", "dmsguestbook") . "<br />
		<br />
		<b>LINK2</b> - " . __("Auto link to a guestbook post", "dmsguestbook") . "<br />" .
	    __("Example", "dmsguestbook") . ": <i style='color:#dd0000;'>&lt;a href=\"LINK2\"&gt;</i><br />" .
		__("LINK2 is trying to generate a link to guestbook with the \"p=id\" statement where is defined under page id.", "dmsguestbook") . "<br />
		<br />
		<b>SHOW_POST</b> - " . __("Link to the guestbook post (manually)", "dmsguestbook") . "<br />" .
		__("Example", "dmsguestbook") . " 1: <i style='color:#dd0000;'>&lt;a href=\"$url/?page_id=" . __("YourGuestbookId", "dmsguestbook") . "&SHOW_POST\"&gt;</i><br />
		<br />" .
		__("Example", "dmsguestbook") . " 2: <i style='color:#dd0000;'>&lt;a href=\"$url/?p=" . __("YourGuestbookId", "dmsguestbook") . "&SHOW_POST\"&gt;</i><br />
		<br />" .
		__("Example", "dmsguestbook") . " 3: <i style='color:#dd0000;'>&lt;a href=\"$url/" . __("YourGuestbookPageName", "dmsguestbook") . "/?SHOW_POST\"&gt;</i><br />
		<br />" .
		__("Using SHOW_POST when do you want to connect from an other webpage to your guestbook page, when do you want to define your own path or when do you have problems with LINK1 or LINK2.", "dmsguestbook") . "<br />
		<br />
		<b>SHOW_NR</b> - " . __("Count guestbook entries", "dmsguestbook") . "<br />" .
		__("Example", "dmsguestbook") . ": <i style='color:#dd0000;'>&lt;b&gt;(SHOW_NR)&lt;/b&gt;</i><br />" .
		__("Entries will be display in bold.", "dmsguestbook") . "<br />
		<br />
		<b>SHOW_ID</b> - " . __("Show the database unique id", "dmsguestbook") . "<br />" .
		__("Example", "dmsguestbook") . ": <i style='color:#dd0000;'>&lt;b&gt;(SHOW_ID)&lt;/b&gt;</i><br />" .
		__("Id will be display in bold.", "dmsguestbook") . "<br />
		<br />
		<b>SHOW_DATE</b> - " . __("Show the date which guestbook post was saved", "dmsguestbook") . "<br />" .
		__("Example", "dmsguestbook") . ": <i style='color:#dd0000;'>&lt;i&gt;SHOW_DATE&lt;/i&gt;</i><br />" .
		__("Date will be display in italic.", "dmsguestbook") . "<br />
		<br />
		<b>SHOW_NAME</b> - " . __("Show the visitor name", "dmsguestbook") . "<br />" .
		__("Example", "dmsguestbook") . ": <i style='color:#dd0000;'>&lt;span style=\"text-decoration: underline;\"&gt;SHOW_NAME&lt;/span&gt;</i><br />" .
		__("Visitor name will be display underlined.", "dmsguestbook") . "<br />
		<br />
		<b>SHOW_MESSAGE</b> - " . __("Show the guestbook post text") . "<br />" .
		__("Example", "dmsguestbook") . ": <i style='color:#dd0000;'>&lt;span style=\"font-size:9px;\"&gt;SHOW_MESSAGE&lt;/span&gt;</i><br />" .
		__("The whole guestbook post will be display in font size 9 pixel.", "dmsguestbook") . "<br />
		<br />
		<b>GRAVATAR</b> - " . __("Show gravatar image if available", "dmsguestbook") . "<br />" .
		__("Example") . ": <i style='color:#dd0000;'>" . __("This is my gravatar image") . ": GRAVATAR</i><br />
		<br /></span>";
	}

/* what you see in the side of your webpage */
function widget_dmsguestbook($args) {
	extract($args);
	$options = get_option('widget_dmsguestbook');
	$title = str_replace("\\", "", $options['title']);
	$guestbook_id = $options['guestbook_id'];
	$entries = $options['entries'];
	$wordcut = $options['wordcut'];
	$dateformat = $options['dateformat'];
	$gravatar_rating = $options['gravatar_rating'];
	$gravatar_size = $options['gravatar_size'];
	$header = str_replace("\\", "", str_replace("\\r\\n", "", str_replace("\\n","", str_replace("\\r","", $options['widget_header']))));
	$footer = str_replace("\\", "", str_replace("\\r\\n", "", str_replace("\\n","", str_replace("\\r","", $options['widget_footer']))));
	$widget_data = str_replace("\\","", str_replace("\\r\\n", "", str_replace("\\n","", str_replace("\\r","", $options['widget_data']))));

	$DMSGuestbookWidgetContent = "";
	$DMSGuestbookWidgetContent .= "<!-- Start DMSGuestbook widget -->\n";
	$DMSGuestbookWidgetContent .= $before_widget . $before_title . $title . $after_title . "<br />";

		global $wpdb;
		$table_name = $wpdb->prefix . "dmsguestbook";
			/* read options, use ASC or DESC */
			$options = get_option('DMSGuestbook_options');
			$part1 = explode("<sortitem>", $options);
			$part2 = explode("</sortitem>", $part1[1]);

			$part3 = explode("<setlocale>", $options);
			$setlocale = explode("</setlocale>", $part3[1]);

			$guestbook_id_part1 = explode(",", $guestbook_id);

		$query = $wpdb->get_results("SELECT id, name, message, date, gravatar FROM $table_name WHERE flag != '1' && guestbook = '" . sprintf("%d", $guestbook_id_part1[0]) . "' && spam = '0' ORDER BY id
		" . sprintf("%s", $part2[0]) . " LIMIT " . sprintf("%d", $entries) . "");

		$DMSGuestbookWidgetContent .= $header;
		$itemnr=0;
		$itemnr2=0;
		foreach ($query as $result) {
		$itemnr2++;

			/* rewrite tags */
			$url=get_bloginfo('wpurl');
			setlocale(LC_TIME, $setlocale[0]);
			$widget_data0 = str_replace("SHOW_POST", "from=$itemnr&amp;widget_gb_step=1&amp;select=1&amp;widget=1&amp;itemnr=$itemnr2", $widget_data);
			$widget_data1 = str_replace("SHOW_ID", "$result->id", $widget_data0);
			$widget_data2 =	str_replace("SHOW_NR", $itemnr+1, $widget_data1);
			$widget_data3 = str_replace("LINK1", $url . "/?page_id=" . $guestbook_id_part1[1] . "&amp;from=$itemnr&amp;widget_gb_step=1&amp;select=1&amp;widget=1&amp;itemnr=$itemnr2", $widget_data2);
			$widget_data4 = str_replace("LINK2", $url . "/?p=" . $guestbook_id_part1[1] . "&amp;from=$itemnr&amp;widget_gb_step=1&amp;select=1&amp;widget=1&amp;itemnr=$itemnr2", $widget_data3);
			$widget_data5 = str_replace("SHOW_DATE", strftime($dateformat, $result->date), $widget_data4);
			$widget_data6 = str_replace("SHOW_NAME", stripslashes(htmlspecialchars($result->name, ENT_QUOTES)), $widget_data5);

			if($result->gravatar != "") {
			$widget_data7 = str_replace("GRAVATAR",
			"<img src='http://www.gravatar.com/avatar/$result->gravatar?r=$gravatar_rating&amp;s=$gravatar_size' alt='gravatar' />", $widget_data6);
			} else {
				   $widget_data7 = str_replace("GRAVATAR", "", $widget_data6);
				   }

			$itemnr++;

			$message = str_replace("[html]", "", $result->message);
			$message = str_replace("[/html]", "", $message);

			if($wordcut!=0) {
			$gbtext = mb_substr(str_replace("\\","",stripslashes(strip_tags($message))), 0, $wordcut) . "...";
			}
			else {
			     $gbtext = strip_tags(str_replace("\\","",$message));
			     }

		$widget_data8 = str_replace("SHOW_MESSAGE", stripslashes($gbtext), $widget_data7);
		$DMSGuestbookWidgetContent .= $widget_data8;
		}

		$DMSGuestbookWidgetContent .= $footer;

	$DMSGuestbookWidgetContent .= $after_widget;
	$DMSGuestbookWidgetContent .= "\t<!-- Stop DMSGuestbook widget -->\n";
	echo $DMSGuestbookWidgetContent;
	}

register_sidebar_widget('DMSGuestbook', 'widget_dmsguestbook');
register_widget_control('DMSGuestbook', 'widget_dmsguestbook_control', 600, 800);
}

add_action('plugins_loaded', 'widget_dmsguestbook_init');
?>