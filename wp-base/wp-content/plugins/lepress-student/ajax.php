<?php
if ( !function_exists('add_action') ){
   require_once("../../../wp-config.php");
}
global $LePressStudent_plugin;
if ( $_REQUEST['action'] == 'getLePressStudentWidgetBody' ){
	echo $LePressStudent_plugin->get_widget_body( $_REQUEST['widget_id'], array('collapse_students' => $_REQUEST['collapse_students']));
}elseif ( $_REQUEST['action'] == 'getLePressStudentWidgetCalendar' ){
	echo $LePressStudent_plugin->get_widget_calendar( $_REQUEST['course_id'], $_REQUEST['month'], $_REQUEST['year'], $_REQUEST['widget_id'] );
}
?>