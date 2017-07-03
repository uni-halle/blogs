<?php
/*
Plugin Name: Spider Event Calendar
Plugin URI: https://web-dorado.com/products/wordpress-calendar.html
Description: Spider Event Calendar is a highly configurable product which allows you to have multiple organized events. Spider Event Calendar is an extraordinary user friendly extension.
Version: 1.5.53
Author: WebDorado
Author URI: https://web-dorado.com
License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
$wd_spider_calendar_version="1.5.53";
// LANGUAGE localization.
function sp_calendar_language_load() {
  load_plugin_textdomain('sp_calendar', FALSE, basename(dirname(__FILE__)) . '/languages');
}
add_action('init', 'sp_calendar_language_load');

add_action('init', 'sp_cal_registr_some_scripts');
	
function	sp_cal_registr_some_scripts(){
  global $wd_spider_calendar_version;
  wp_register_script("Canlendar_upcoming", plugins_url("elements/calendar.js", __FILE__), array(), $wd_spider_calendar_version);
  wp_register_script("calendnar-setup_upcoming", plugins_url("elements/calendar-setup.js", __FILE__), array(), $wd_spider_calendar_version);
  wp_register_script("calenndar_function_upcoming", plugins_url("elements/calendar_function.js", __FILE__), array(), $wd_spider_calendar_version);
  
  if( isset($_GET['page']) && $_GET['page'] == "Uninstall_sp_calendar" ) {
	wp_enqueue_script("sp_calendar-deactivate-popup", plugins_url('wd/assets/js/deactivate_popup.js', __FILE__), array(), $wd_spider_calendar_version);
	   $admin_data = wp_get_current_user();
	   
		wp_localize_script( 'sp_calendar-deactivate-popup', 'sp_calendarWDDeactivateVars', array(
			"prefix" => "sp_calendar" ,
			"deactivate_class" =>  'sp_calendar_deactivate_link',
			"email" => $admin_data->data->user_email,
			"plugin_wd_url" => "https://web-dorado.com/products/wordpress-google-maps-plugin.html",
		));
	}
  
}

// Include widget.
require_once("widget_spider_calendar.php");
require_once("spidercalendar_upcoming_events_widget.php");
function current_page_url_sc() {
  if (is_home()) {
    $pageURL = site_url();
  }
  else {
    $pageURL = get_permalink();
  }
  return $pageURL;
}

function resolv_js_prob() {
  ?>
  <script>
    var xx_cal_xx = '&';
  </script>
  <?php
}
add_action('wp_head', 'resolv_js_prob');

function spider_calendar_scripts() {
  wp_enqueue_script('jquery');
  wp_enqueue_script('thickbox', NULL, array('jquery'));
  wp_enqueue_style('thickbox.css', '/' . WPINC . '/js/thickbox/thickbox.css', NULL, '1.0');
  wp_enqueue_style('thickbox');
}
add_action('wp_enqueue_scripts', 'spider_calendar_scripts');

$many_sp_calendar = 1;
function spider_calendar_big($atts) {
  if (!isset($atts['default'])) {
    $atts['theme'] = 30;
    $atts['default'] = 'month';
  }
  extract(shortcode_atts(array(
    'id' => 'no Spider catalog',
    'theme' => '30',
    'default' => 'month',
    'select' => 'month,list,day,week,',
  ), $atts));
  if (!isset($atts['select'])) {
    $atts['select'] = 'month,list,day,week,';
  }
  return spider_calendar_big_front_end($id, $theme, $default, $select);
}
add_shortcode('Spider_Calendar', 'spider_calendar_big');

function spider_calendar_big_front_end($id, $theme, $default, $select, $widget = 0) {
  require_once("front_end/frontend_functions.php");  
  ob_start();
  global $many_sp_calendar;
  global $wpdb;
  
  if ($widget === 1) {
$themes = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'spidercalendar_widget_theme WHERE id=%d', $theme));
}
else{
$themes = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'spidercalendar_theme WHERE id=%d', $theme));
}
  $cal_width = $themes->width; ?>
  <input type="hidden" id="cal_width<?php echo $many_sp_calendar ?>" value="<?php echo $cal_width ?>" /> 
  <div id='bigcalendar<?php echo $many_sp_calendar ?>' class="wdc_calendar"></div>
  <script> 
    var tb_pathToImage = "<?php echo plugins_url('images/loadingAnimation.gif', __FILE__) ?>";
    var tb_closeImage = "<?php echo plugins_url('images/tb-close.png', __FILE__) ?>"
	var randi;
    if (typeof showbigcalendar != 'function') {	
      function showbigcalendar(id, calendarlink, randi, widget) {
        var xmlHttp;
        try {
          xmlHttp = new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
        }
        catch (e) {
          try {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
          }
          catch (e) {
            try {
              xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e) {
              alert("No AJAX!?");
              return false;
            }
          }
        }
        xmlHttp.onreadystatechange = function () {
          if (xmlHttp.readyState == 4) {
            // document.getElementById(id).innerHTML = xmlHttp.responseText;
            jQuery('#' + id).html(xmlHttp.responseText);
          }
        }
        xmlHttp.open("GET", calendarlink, false);
        xmlHttp.send();
	 jQuery(document).ready(function (){
	  jQuery('#views_select').toggle(function () {	
		jQuery('#drop_down_views').stop(true, true).delay(200).slideDown(500);
		jQuery('#views_select .arrow-down').addClass("show_arrow");
		jQuery('#views_select .arrow-right').removeClass("show_arrow");
	  }, function () { 
		jQuery('#drop_down_views').stop(true, true).slideUp(500);
		jQuery('#views_select .arrow-down').removeClass("show_arrow");
		jQuery('#views_select .arrow-right').addClass("show_arrow");		
	  });
	});
if(widget!=1)
{
	jQuery('drop_down_views').hide();
	var parent_width = document.getElementById('bigcalendar'+randi).parentNode.clientWidth;
	var calwidth=  document.getElementById('cal_width'+randi).value;
	var responsive_width = (calwidth)/parent_width*100;
	document.getElementById('bigcalendar'+randi).setAttribute('style','width:'+responsive_width+'%;');
	jQuery('pop_table').css('height','100%');
}
        var thickDims, tbWidth, tbHeight;
        jQuery(document).ready(function ($) {	
		if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) { jQuery('body').addClass('ios_device'); } 			
		 setInterval(function(){	
				if(jQuery("body").hasClass("modal-open")) jQuery("html").addClass("thickbox_open");
				else jQuery("html").removeClass("thickbox_open");
			},500);			
          thickDims = function () {		
             var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;
            if (tbWidth) {
              if (tbWidth < (W - 90)) w = tbWidth; else  w = W - 200;
            } else w = W - 200;
            if (tbHeight) {
              if (tbHeight < (H - 90)) h = tbHeight; else  h = H - 200;
            } else h = H - 200;			
            if (tbWindow.size()) {
              tbWindow.width(w).height(h);
              jQuery('#TB_iframeContent').width(w).height(h - 27);
              tbWindow.css({'margin-left':'-' + parseInt((w / 2), 10) + 'px'});
              if (typeof document.body.style.maxWidth != 'undefined')
                tbWindow.css({'top':(H - h) / 2, 'margin-top':'0'});
            }
			 if(jQuery(window).width() < 768 ){
			  var tb_left = parseInt((w / 2), 10) + 20;		  
				jQuery('#TB_window').css({"left": tb_left+ "px", "width": "90%", "margin-top": "-13%","height": "100%"})
				jQuery('#TB_window iframe').css({'height':'100%', 'width':'100%'});
			}
			 else jQuery('#TB_window').css('left','50%');
		if (typeof popup_width_from_src != "undefined") {
				popup_width_from_src=jQuery('.thickbox-previewbigcalendar'+randi).attr('href').indexOf('tbWidth=');
				str=jQuery('.thickbox-previewbigcalendar'+randi).attr('href').substr(popup_width_from_src+8,150)
				find_amp=str.indexOf('&');
				width_orig=str.substr(0,find_amp);				
				find_eq=str.indexOf('=');
				height_orig=str.substr(find_eq+1,5);
			jQuery('#TB_window').css({'max-width':width_orig+'px', 'max-height':height_orig+'px'});
			jQuery('#TB_window iframe').css('max-width',width_orig+'px');
			}	
          };
          thickDims();
          jQuery(window).resize(function () {
            thickDims();			
		  });		  
          jQuery('a.thickbox-preview' + id).click(function () {
            tb_click.call(this);
            var alink = jQuery(this).parents('.available-theme').find('.activatelink'), link = '', href = jQuery(this).attr('href'), url, text;
            var reg_with = new RegExp(xx_cal_xx + "tbWidth=[0-9]+");	
            if (tbWidth = href.match(reg_with))
              tbWidth = parseInt(tbWidth[0].replace(/[^0-9]+/g, ''), 10);
            else
              tbWidth = jQuery(window).width() - 90;			  
            var reg_heght = new RegExp(xx_cal_xx + "tbHeight=[0-9]+");
            if (tbHeight = href.match(reg_heght))
              tbHeight = parseInt(tbHeight[0].replace(/[^0-9]+/g, ''), 10);
            else
              tbHeight = jQuery(window).height() - 60;
            jQuery('#TB_ajaxWindowTitle').css({'float':'right'}).html(link);			
            thickDims();			
            return false;			
          });
		  
        });
      }
    }	
    document.onkeydown = function (evt) {
      evt = evt || window.event;
      if (evt.keyCode == 27) {
        document.getElementById('sbox-window').close();
      }
    };
    <?php global $wpdb;
    $calendarr = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "spidercalendar_calendar WHERE id='%d'", $id));
    $year = ($calendarr->def_year ? $calendarr->def_year : date("Y"));
    $month = ($calendarr->def_month ? $calendarr->def_month : date("m"));	
	
    $date = $year . '-' . $month;
    if ($default == 'day') {
      $date .= '-' . date('d');
    }
    if ($default == 'week') {
      $date .= '-' . date('d');
      $d = new DateTime($date);
      $weekday = $d->format('w');
      $diff = ($weekday == 0 ? 6 : $weekday - 1);
      if ($widget === 1) {
        $theme_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "spidercalendar_widget_theme WHERE id='%d'", $theme));
      }
      else {
        $theme_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "spidercalendar_theme WHERE id='%d'", $theme));
      }
      $weekstart = $theme_row->week_start_day;
      if ($weekstart == "su") {
        $diff = $diff + 1;
      }
      $d->modify("-$diff day");
      $d->modify("-1 day");
      $prev_date = $d->format('Y-m-d');
      $prev_month = add_0((int) substr($prev_date, 5, 2) - 1);
      $this_month = add_0((int) substr($prev_date, 5, 2));
      $next_month = add_0((int) substr($prev_date, 5, 2) + 1);
      if ($next_month == '13') {
        $next_month = '01';
      }
      if ($prev_month == '00') {
        $prev_month = '12';
      }
    }
    if ($widget === 1) {
      $default .= '_widget';
    }
    else {
    }
    ?> showbigcalendar('bigcalendar<?php echo $many_sp_calendar; ?>', '<?php echo add_query_arg(array(
      'action' => 'spiderbigcalendar_' . $default,
      'theme_id' => $theme,
      'calendar' => $id,
      'select' => $select,
      'date' => $date,
      'months' => (($default == 'week' || $default == 'week_widget') ? $prev_month . ',' . $this_month . ',' . $next_month : ''),
      'many_sp_calendar' => $many_sp_calendar,
      'widget' => $widget,
	  'rand' => $many_sp_calendar,
      ), admin_url('admin-ajax.php'));?>','<?php echo $many_sp_calendar; ?>','<?php echo $widget; ?>');</script>
	<style>
	#TB_window iframe{
		background: <?php echo '#'.str_replace('#','',$themes->show_event_bgcolor); ?>;
	}
	</style>
  <?php
  $many_sp_calendar++;
  $calendar = ob_get_contents();
  ob_end_clean();
  return $calendar;
}

function convert_time($calendar_format, $old_time){
	if($calendar_format==0){	
		if (strpos($old_time, 'AM') !== false || strpos($old_time, 'PM') !== false) {
			$row_time_12  = explode('-',$old_time);
			$row_time_24 = "";
			for($i=0; $i<count($row_time_12); $i++){
				$row_time_24 .= date("H:i", strtotime($row_time_12[$i])). "-";
			}
			if(substr($row_time_24, -1)=="-") $row_time = rtrim($row_time_24,'-'); 
		}
		else $row_time = $old_time; 
	}
	else{
		if (strpos($old_time, 'AM') !== false || strpos($old_time, 'PM') !== false) $row_time = $old_time;
		else{
			$row_time_12 = "";
			$row_time_24  = explode('-',$old_time);
			for($i=0; $i<count($row_time_24); $i++){
				$row_time_12 .= date("g:iA", strtotime($row_time_24[$i])). "-";
			}
			if(substr($row_time_12, -1)=="-") $row_time = rtrim($row_time_12,'-');
		} 
	}
	return $row_time;
}

// Quick edit.
add_action('wp_ajax_spidercalendarinlineedit', 'spider_calendar_quick_edit');
add_action('wp_ajax_spidercalendarinlineupdate', 'spider_calendar_quick_update');
add_action('wp_ajax_upcoming', 'upcoming_widget');
function spider_calendar_quick_update() {
  $current_user = wp_get_current_user();
  if ($current_user->roles[0] !== 'administrator') {
    echo 'You have no permission.';
    die();
  }
  global $wpdb;
  if (isset($_POST['calendar_id']) && isset($_POST['calendar_title']) && isset($_POST['us_12_format_sp_calendar']) && isset($_POST['default_year']) && isset($_POST['default_month'])) {
    $wpdb->update($wpdb->prefix . 'spidercalendar_calendar', array(
        'title' => esc_sql(esc_html(stripslashes($_POST['calendar_title']))),
        'time_format' => esc_sql(esc_html(stripslashes($_POST['us_12_format_sp_calendar']))),
        'def_year' => esc_sql(esc_html(stripslashes($_POST['default_year']))),
        'def_month' => esc_sql(esc_html(stripslashes($_POST['default_month']))),
      ), array('id' => esc_sql(esc_html(stripslashes($_POST['calendar_id'])))), array(
        '%s',
        '%d',
        '%s',
        '%s',
      ), array('%d'));
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "spidercalendar_calendar WHERE id='%d'", (int) $_POST['calendar_id']));
	$calendar_format = esc_sql(esc_html(stripslashes($_POST['us_12_format_sp_calendar'])));
	
	$events_list = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "spidercalendar_event WHERE calendar='%d'", (int) $_POST['calendar_id']));
	
	for($i = 0; $i < count($events_list); $i++){
		if($events_list[$i]->time!=''){
			$wpdb->update($wpdb->prefix . 'spidercalendar_event', array(
			  'time' => convert_time($calendar_format, $events_list[$i]->time)
			), array('id' => $events_list[$i]->id), array(
			  '%s'
			)); 
		}
	}  
	?>
  <td><?php echo $row->id; ?></td>
  <td class="post-title page-title column-title">
    <a title="Manage Events" class="row-title" href="admin.php?page=SpiderCalendar&task=show_manage_event&calendar_id=<?php echo $row->id; ?>"><?php echo $row->title; ?></a>
    <div class="row-actions"> 
      <span class="inline hide-if-no-js">
        <a href="#" class="editinline" onclick="show_calendar_inline(<?php echo $row->id; ?>)" title="Edit This Calendar Inline">Quick&nbsp;Edit</a> | </span>
      <span class="trash">
        <a class="submitdelete" title="Delete This Calendar" href="javascript:confirmation('admin.php?page=SpiderCalendar&task=remove_calendar&id=<?php echo $row->id; ?>','<?php echo $row->title; ?>')">Delete</a></span>
    </div>
  </td>
  <td><a href="admin.php?page=SpiderCalendar&task=show_manage_event&calendar_id=<?php echo $row->id; ?>">Manage events</a></td>
  <td><a href="admin.php?page=SpiderCalendar&task=edit_calendar&id=<?php echo $row->id; ?>" title="Edit This Calendar">Edit</a></td>
  <td><a <?php if (!$row->published)
    echo 'style="color:#C00"'; ?>
    href="admin.php?page=SpiderCalendar&task=published&id=<?php echo $row->id; ?>"><?php if ($row->published)
    echo "Yes";
  else echo "No"; ?></a></td>
  <?php
    die();
  }
  else {
    die();
  }
}

function spider_calendar_quick_edit() {
  $current_user = wp_get_current_user();
  if ($current_user->roles[0] !== 'administrator') {
    echo 'You have no permission.';
    die();
  }
  global $wpdb;
  if (isset($_POST['calendar_id'])) {
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "spidercalendar_calendar WHERE id='%d'", (int) $_POST['calendar_id']));
    ?>
  <td colspan="4" class="colspanchange">
    <fieldset class="inline-edit-col-left">
      <div style="float:left; width:100% " class="inline-edit-col">
        <h4>Quick Edit</h4>
        <label for="calendar_title"><span style="width:160px !important" class="title">Title: </span></label>
        <span class="input-text-wrap">
          <input type="text" style="width:150px !important" id="calendar_title" name="calendar_title" value="<?php echo $row->title; ?>" class="ptitle" value=""/>
        </span>
        <label for="def_year"><span class="title alignleft" style="width:160px !important">Default Year: </span></label>
        <span>
          <input type="text" name="def_year" id="def_year" style="width:150px;" value="<?php echo $row->def_year ?>"/>
        </span>
        <label for="def_month"><span class="title alignleft" style="width:160px !important">Default Month: </span></label>
        <span>
          <select id="def_month" name="def_month" style="width:150px;">
            <?php
            $month_array = array(
              '' => 'Current',
              '01' => 'January',
              '02' => 'February',
              '03' => 'March',
              '04' => 'April',
              '05' => 'May',
              '06' => 'June',
              '07' => 'July',
              '08' => 'August',
              '09' => 'September',
              '10' => 'October',
              '11' => 'November',
              '12' => 'December',
            );
            foreach ($month_array as $key => $def_month) {
              ?>
              <option <?php echo (($row->def_month == $key) ? 'selected="selected"' : '');?> value="<?php echo $key;?>"><?php echo $def_month;?></option>
              <?php
            }
            ?>
          </select>
        </span>
        <label for="time_format0"><span class="title alignleft" style="width:160px !important">Use 12 hours time format: </span></label>
        <span>
          <input style="margin-top:5px" type="radio" class="alignleft" name="time_format" id="time_format0" value="0" <?php if ($row->time_format == 0) echo 'checked="checked"'; ?> />
          <em style="margin:4px 5px 0 0" class="alignleft"> No </em>
          <input style="margin-top:5px" class="alignleft" type="radio" name="time_format" id="time_format1" value="1" <?php if ($row->time_format == 1) echo 'checked="checked"'; ?> />
          <em style="margin:4px 5px 0 0" class="alignleft"> Yes </em>
        </span>
      </div>
    </fieldset>
    <p class="submit inline-edit-save">
      <a accesskey="c" href="#" title="Cancel" onclick="cancel_qiucik_edit(<?php echo $row->id; ?>)" class="button-secondary cancel alignleft">Cancel</a>
      <input type="hidden" id="_inline_edit" name="_inline_edit" value="d8393e8662">
      <a accesskey="s" href="#" title="Update" onclick="updae_inline_sp_calendar(<?php echo  "'" . $row->id . "'" ?>)" class="button-primary save alignright">Update</a>
      <input type="hidden" name="post_view" value="list">
      <input type="hidden" name="screen" value="edit-page">
      <span class="error" style="display:none"></span>
      <br class="clear">
    </p>
  </td>
  <?php
    die();
  }
  else {
    die();
  }
}

// Add editor new mce button.
add_filter('mce_external_plugins', "sp_calendar_register");
add_filter('mce_buttons', 'sp_calendar_add_button', 0);

// Function for add new button.
function sp_calendar_add_button($buttons) {
  array_push($buttons, "sp_calendar_mce");
  return $buttons;
}

// Function for registr new button.
function sp_calendar_register($plugin_array) {
  $url = plugins_url('js/editor_plugin.js', __FILE__);
  $plugin_array["sp_calendar_mce"] = $url;
  return $plugin_array;
}

// Function create in menu.
function sp_calendar_options_panel() {
  if( get_option( "sp_calendar_subscribe_done" ) == 1 ){
	add_menu_page('Theme page title', 'Calendar', 'manage_options', 'SpiderCalendar', 'Manage_Spider_Calendar', plugins_url("images/calendar_menu.png", __FILE__));
  }
  $page_calendar = add_submenu_page('SpiderCalendar', 'Calendars', 'Calendars', 'manage_options', 'SpiderCalendar', 'Manage_Spider_Calendar');
  $page_event_category = add_submenu_page('SpiderCalendar', 'Event Category', 'Event Category', 'manage_options', 'spider_calendar_event_category', 'Manage_Spider_Category_Calendar');
  $page_theme = add_submenu_page('SpiderCalendar', 'Calendar Parameters', 'Calendar Themes', 'manage_options', 'spider_calendar_themes', 'spider_calendar_params');
  $page_widget_theme = add_submenu_page('SpiderCalendar', 'Calendar Parameters', 'Widget Themes', 'manage_options', 'spider_widget_calendar_themes', 'spider_widget_calendar_params');
  add_submenu_page('SpiderCalendar', 'Export', 'Export', 'manage_options', 'calendar_export', 'calendar_export'); 
  add_submenu_page('SpiderCalendar', 'Get Pro', 'Get Pro', 'manage_options', 'Spider_calendar_Licensing', 'Spider_calendar_Licensing');
  $page_uninstall = add_submenu_page('SpiderCalendar', 'Uninstall  Spider Event Calendar', 'Uninstall  Spider Event Calendar', 'manage_options', 'Uninstall_sp_calendar', 'Uninstall_sp_calendar'); // uninstall Calendar
  add_action('admin_print_styles-' . $page_theme, 'spider_calendar_themes_admin_styles_scripts');
  add_action('admin_print_styles-' . $page_event_category, 'spider_calendar_event_category_admin_styles_scripts');
  add_action('admin_print_styles-' . $page_calendar, 'spider_calendar_admin_styles_scripts');
  add_action('admin_print_styles-' . $page_uninstall, 'spider_calendar_admin_styles_scripts');
  add_action('admin_print_styles-' . $page_widget_theme, 'spider_widget_calendar_themes_admin_styles_scripts');
}

function Spider_calendar_Licensing() {
	global $wpdb;
  ?>
  <div style="width:95%">
    <p>This plugin is the non-commercial version of the Spider Event Calendar. Use of the calendar is free.<br />
    The only limitation is the use of the themes. If you want to use one of the 11 standard themes or create a new one that
    satisfies the needs of your web site, you are required to purchase a license.<br />
    Purchasing a license will add 12 standard themes and give possibility to edit the themes of the Spider Event Calendar.
    </p>
    <br /><br />
    <a href="https://web-dorado.com/files/fromSpiderCalendarWP.php" class="button-primary" target="_blank">Purchase a License</a>
    <br /><br /><br />
    <p>After the purchasing the commercial version follow this steps:</p>
    <ol>
      <li>Deactivate Spider Event Calendar Plugin</li>
      <li>Delete Spider Event Calendar Plugin</li>
      <li>Install the downloaded commercial version of the plugin</li>
  </ol>
  </div>
  <?php
}

function spider_calendar_themes_admin_styles_scripts() {
  global $wd_spider_calendar_version;
  wp_enqueue_script("jquery");
  wp_enqueue_script("standart_themes", plugins_url('elements/theme_reset.js', __FILE__), array(), $wd_spider_calendar_version);
 wp_enqueue_script('wp-color-picker');
  wp_enqueue_style( 'wp-color-picker' );
  if (isset($_GET['task'])) {
    if ($_GET['task'] == 'edit_theme' || $_GET['task'] == 'add_theme' || $_GET['task'] == 'Apply') {
      wp_enqueue_style("parsetheme_css", plugins_url('style_for_cal/style_for_tables_cal.css', __FILE__), array(), $wd_spider_calendar_version);
    }
  }

}

function spider_widget_calendar_themes_admin_styles_scripts() {
  global $wd_spider_calendar_version;
  wp_enqueue_script("jquery");
  wp_enqueue_script("standart_themes", plugins_url('elements/theme_reset_widget.js', __FILE__), array(), $wd_spider_calendar_version);
    wp_enqueue_script('wp-color-picker');
  wp_enqueue_style( 'wp-color-picker' );
  if (isset($_GET['task'])) {
    if ($_GET['task'] == 'edit_theme' || $_GET['task'] == 'add_theme' || $_GET['task'] == 'Apply') {
      wp_enqueue_style("parsetheme_css", plugins_url('style_for_cal/style_for_tables_cal.css', __FILE__), array(), $wd_spider_calendar_version);
    }
  }
}

function spider_calendar_admin_styles_scripts() {
  global $wd_spider_calendar_version;
  wp_enqueue_script("Calendar", plugins_url("elements/calendar.js", __FILE__), array(), $wd_spider_calendar_version, FALSE);
  wp_enqueue_script("calendar-setup", plugins_url("elements/calendar-setup.js", __FILE__), array(), $wd_spider_calendar_version, FALSE);
  wp_enqueue_script("calendar_function", plugins_url("elements/calendar_function.js", __FILE__), array(), $wd_spider_calendar_version, FALSE);
  wp_enqueue_style("Css", plugins_url("elements/calendar-jos.css", __FILE__), array(), $wd_spider_calendar_version, FALSE);
  
  if( isset($_GET['page']) && $_GET['page'] == "Uninstall_sp_calendar" ) {
	 wp_enqueue_style("sp_calendar_deactivate-css", plugins_url("wd/assets/css/deactivate_popup.css", __FILE__), array(), $wd_spider_calendar_version, FALSE);
  }
}

function spider_calendar_event_category_admin_styles_scripts(){
  global $wd_spider_calendar_version;
  wp_enqueue_script("Calendar", plugins_url("elements/calendar.js", __FILE__), array(), $wd_spider_calendar_version, FALSE);
  wp_enqueue_script("calendar-setup", plugins_url("elements/calendar-setup.js", __FILE__), array(), $wd_spider_calendar_version, FALSE);
    wp_enqueue_script('wp-color-picker');
  wp_enqueue_style( 'wp-color-picker' );
  wp_enqueue_style("Css", plugins_url("elements/calendar-jos.css", __FILE__), array(), $wd_spider_calendar_version, FALSE);  
  }

add_filter('admin_head', 'spide_ShowTinyMCE');
function spide_ShowTinyMCE() {
  // conditions here
  wp_enqueue_script('common');
  wp_enqueue_script('jquery-color');
  wp_print_scripts('editor');
  if (function_exists('add_thickbox')) {
    add_thickbox();
  }
  wp_print_scripts('media-upload');
  if(version_compare(get_bloginfo('version'),3.3)<0){
  if (function_exists('wp_tiny_mce')) {
    wp_tiny_mce();
  }
  }
  wp_admin_css();
  wp_enqueue_script('utils');
  do_action("admin_print_styles-post-php");
  do_action('admin_print_styles');
}

// Add menu.
add_action( 'admin_menu', 'sp_calendar_options_panel' );
add_action( 'init', "wd_spcal_init" ); 


function wd_spcal_init(){

	if( !class_exists("DoradoWeb") ){
		require_once('wd/start.php');
	}
	
	global $sp_calendar_options;
	$sp_calendar_options =  array (
		"prefix" => "sp_calendar",
		"wd_plugin_id" => 29,
		"plugin_title" => "Spider Calendar", 
		"plugin_wordpress_slug" => "spider-event-calendar", 
		"plugin_dir" => plugins_url('/', __FILE__),
		"plugin_main_file" => __FILE__,
		"description" => __('This is the best WordPress event Calendar plugin available in WordPress Directory.', 'sp_calendar'), 

	   // from web-dorado.com
		"plugin_features" => array(
				0 => array(
					"title" => __("Responsive", "sp_calendar"),
					"description" => __("Spider Calendar plugin is fully responsive and mobile-ready.  Thus a beautiful display on all types of devices and screens is guaranteed.", "sp_calendar"),
				),
				1 => array(
					"title" => __("Unlimited Calendars & Events", "sp_calendar"),
					"description" => __("The calendar plugin allows you to create as many calendars as you want and add unlimited number of events in each calendar.  Customize the design of each calendar, create events for each calendar separately and show multiple calendars on one page.", "sp_calendar"),
				),
				2 => array(
					"title" => __("Event Categories", "sp_calendar"),
					"description" => __("You can assign categories to your events by adding titles, descriptions and category colors from the website admin panel. The plugin allows you customize the calendars to show events from all or just a few categories.", "sp_calendar"),
				), 
				3 => array(
					"title" => __("Themes", "sp_calendar"),
					"description" => __("Choose among 17 different calendar themes to make sure the calendar fits perfectly with your website design. Add your own style to the themes by customizing almost everything or easily create your own theme.", "sp_calendar"),
				),
				4 => array(
					"title" => __("Repeat Events", "sp_calendar"),
					"description" => __("If you have events in your calendar that occur regularly you can choose to use the recurring events option.  You can set the events to repeat daily, weekly, monthly, yearly on specific days of the week, specific days of the month or year.", "sp_calendar"),
				)			
		   ),
		   // user guide from web-dorado.com
		 "user_guide" => array(
			0 => array(
					"main_title" => __("Creating/Editing Calendars", "sp_calendar"),
					"url" => "https://web-dorado.com/wordpress-spider-calendar/creating-editing-calendar.html",
					"titles" => array()
				),
			1 => array(
				"main_title" => __("Creating/Editing Events", "sp_calendar"),
				"url" => "https://web-dorado.com/wordpress-spider-calendar/creating-editing-events.html",
				"titles" => array()
			),
			2 => array(
				"main_title" => __("Adding Event Category", "sp_calendar"),
				"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-event-category.html",
				"titles" => array()
			),
			3 => array(
				"main_title" => __("Adding Themes", "sp_calendar"),
				"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-themes.html",
				"titles" => array(
					array(
						"title" => __("General Parameters", "sp_calendar"),
						"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-themes/general-parameters.html",
					),
					array(
						"title" => __("Header Parameters", "sp_calendar"),
						"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-themes/header-parameters.html",
					),
					array(
						"title" => __("Body Parameters", "sp_calendar"),
						"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-themes/body-parameters.html",
					),
					array(
						"title" => __("Popup Window Parameters", "sp_calendar"),
						"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-themes/popup-window-parameters.html",
					),
					array(
						"title" => __("Other Views Parameters of the Wordpress Calendar", "sp_calendar"),
						"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-themes/other-views-parameters.html",
					),					
				)
			), 
			4 => array(
				"main_title" => __("Adding Themes for a widget view", "sp_calendar"),
				"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-widget-view-themes.html",
				"titles" => array(
					array(
						"title" => __("General Parameters", "sp_calendar"),
						"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-widget-view-themes/general-parameters.html",
					),
					array(
						"title" => __("Popup Window Parameters", "sp_calendar"),
						"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-widget-view-themes/popup-window-parameters.html",
					),
					array(
						"title" => __("Body Parameters", "sp_calendar"),
						"url" => "https://web-dorado.com/wordpress-spider-calendar/adding-widget-view-themes/body-parameters.html",
					),
				)
			), 
			5 => array(
				"main_title" => __("Publishing the Created Calendar in a Page or a Post", "sp_calendar"),
				"url" => "https://web-dorado.com/wordpress-spider-calendar/publishing-calendar.html",				
				"titles" => array()
			), 
			6 => array(
				"main_title" => __("Publishing the Created Calendar in the Widget", "sp_calendar"),
				"url" => "https://web-dorado.com/wordpress-spider-calendar/publishing-calendar-in-widget.html",				
				"titles" => array()
			), 
			7 => array(
				"main_title" => __("Publishing the Upcoming Events widget", "sp_calendar"),
				"url" => "https://web-dorado.com/wordpress-spider-calendar/publishing-upcoming-events-widget.html",				
				"titles" => array()
			),   
			
	   ), 
	   "video_youtube_id" => "wDrMRAjhgHk",  // e.g. https://www.youtube.com/watch?v=acaexefeP7o youtube id is the acaexefeP7o
	   "overview_welcome_image" => null,
	   "plugin_wd_url" => "https://web-dorado.com/products/wordpress-calendar.html", 
	   "plugin_wd_demo_link" => "http://wpdemo.web-dorado.com/spider-calendar/?_ga=1.67418517.523103993.1473155982", 
	   "plugin_wd_addons_link" => null, 
	   "plugin_wizard_link" => null, 
	   "after_subscribe" => admin_url('admin.php?page=overview_sp_calendar'), // this can be plagin overview page or set up page
	   "plugin_menu_title" => "Calendar", 
	   "plugin_menu_icon" => plugins_url('/images/calendar_menu.png', __FILE__) , 
	   "deactivate" => true, 
	   "subscribe" => true,
	   "custom_post" => "SpiderCalendar",  // if true => edit.php?post_type=contact
	   "menu_capability" => "manage_options",
       "menu_position" => null,
					   
	);
	dorado_web_init($sp_calendar_options);
}

require_once("functions_for_xml_and_ajax.php");
require_once("front_end/bigcalendarday.php");
require_once("front_end/bigcalendarlist.php");
require_once("front_end/bigcalendarweek.php");
require_once("front_end/bigcalendarmonth.php");
require_once("front_end/bigcalendarmonth_widget.php");
require_once("front_end/bigcalendarweek_widget.php");
require_once("front_end/bigcalendarlist_widget.php");
require_once("front_end/bigcalendarday_widget.php");

// Actions for popup and xmls.
add_action('wp_ajax_spiderbigcalendar_day', 'big_calendar_day');
add_action('wp_ajax_spiderbigcalendar_list', 'big_calendar_list');
add_action('wp_ajax_spiderbigcalendar_week', 'big_calendar_week');
add_action('wp_ajax_spiderbigcalendar_month', 'big_calendar_month');
add_action('wp_ajax_spiderbigcalendar_month_widget', 'big_calendar_month_widget');
add_action('wp_ajax_spiderbigcalendar_list_widget', 'big_calendar_list_widget');
add_action('wp_ajax_spiderbigcalendar_week_widget', 'big_calendar_week_widget');
add_action('wp_ajax_spiderbigcalendar_day_widget', 'big_calendar_day_widget');
add_action('wp_ajax_spidercalendarbig', 'spiderbigcalendar');
add_action('wp_ajax_spiderseemore', 'seemore');
add_action('wp_ajax_window', 'php_window');
// Ajax for users.
add_action('wp_ajax_nopriv_spiderbigcalendar_day', 'big_calendar_day');
add_action('wp_ajax_nopriv_spiderbigcalendar_list', 'big_calendar_list');
add_action('wp_ajax_nopriv_spiderbigcalendar_week', 'big_calendar_week');
add_action('wp_ajax_nopriv_spiderbigcalendar_month', 'big_calendar_month');
add_action('wp_ajax_nopriv_spiderbigcalendar_month_widget', 'big_calendar_month_widget');
add_action('wp_ajax_nopriv_spiderbigcalendar_list_widget', 'big_calendar_list_widget');
add_action('wp_ajax_nopriv_spiderbigcalendar_week_widget', 'big_calendar_week_widget');
add_action('wp_ajax_nopriv_spiderbigcalendar_day_widget', 'big_calendar_day_widget');
add_action('wp_ajax_nopriv_spidercalendarbig', 'spiderbigcalendar');
add_action('wp_ajax_nopriv_spiderseemore', 'seemore');
add_action('wp_ajax_nopriv_window', 'php_window');


// Add style head.
function add_button_style_calendar() {
  echo '<script>var wdplugin_url = "' . plugins_url('', __FILE__) . '";</script>';
}
add_action('admin_head', 'add_button_style_calendar');

function Manage_Spider_Calendar() {
  global $wpdb;
  if (!function_exists('print_html_nav')) {
    require_once("nav_function/nav_html_func.php");
  }
  require_once("calendar_functions.php"); // add functions for Spider_Video_Player
  require_once("calendar_functions.html.php"); // add functions for vive Spider_Video_Player
  if (isset($_GET["task"])) {
    $task = esc_html($_GET["task"]);
  }
  else {
    $task = "";
  }
  if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
  }
  else {
    $id = 0;
  }
  if (isset($_GET["calendar_id"])) {
    $calendar_id = (int) $_GET["calendar_id"];
  }
  else {
    $calendar_id = 0;
  }
  switch ($task) {
    case 'calendar':
      show_spider_calendar();
      break;
    case 'add_calendar':
      add_spider_calendar();
      break;
    case 'published';
	  $nonce_sp_cal = $_REQUEST['_wpnonce'];
	  if (! wp_verify_nonce($nonce_sp_cal, 'nonce_sp_cal') )
   	    die("Are you sure you want to do this?");
      spider_calendar_published($id);
      show_spider_calendar();
      break;
    case 'Save':
      if (!$id) {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        apply_spider_calendar(-1);
      }
      else {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        apply_spider_calendar($id);
      }
      show_spider_calendar();
      break;
    case 'Apply':
      if (!$id) {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        apply_spider_calendar(-1);
        $id = $wpdb->get_var("SELECT MAX(id) FROM " . $wpdb->prefix . "spidercalendar_calendar");
      }
      else {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        apply_spider_calendar($id);
      }
      edit_spider_calendar($id);
      break;
    case 'edit_calendar':
      edit_spider_calendar($id);
      break;
    case 'remove_calendar':
	  check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
      remove_spider_calendar($id);
      show_spider_calendar();
      break;
    // Events.
    case 'show_manage_event':
      show_spider_event($calendar_id);
      break;
    case 'add_event':
      add_spider_event($calendar_id);
      break;
    case 'save_event':
      if ($id) {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        apply_spider_event($calendar_id, $id);
      }
      else {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        apply_spider_event($calendar_id, -1);
      }
      show_spider_event($calendar_id);
      break;
    case 'apply_event':
      if ($id) {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        apply_spider_event($calendar_id, $id);
      }
      else {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        apply_spider_event($calendar_id, -1);
        $id = $wpdb->get_var("SELECT MAX(id) FROM " . $wpdb->prefix . "spidercalendar_event");
      }
      edit_spider_event($calendar_id, $id);
      break;
    case 'edit_event':
      edit_spider_event($calendar_id, $id);
      break;
    case 'remove_event':
	  $nonce_sp_cal = $_REQUEST['_wpnonce'];
	  if (! wp_verify_nonce($nonce_sp_cal, 'nonce_sp_cal') ) 
	    die("Are you sure you want to do this?");
      remove_spider_event($calendar_id, $id);
      show_spider_event($calendar_id);
      break;
	case 'copy_event':
	  $nonce_sp_cal = $_REQUEST['_wpnonce'];
	  if (! wp_verify_nonce($nonce_sp_cal, 'nonce_sp_cal') ) 
	    die("Are you sure you want to do this?");
      copy_spider_event($calendar_id, $id);
      show_spider_event($calendar_id);
      break;
    case 'published_event';
	  $nonce_sp_cal = $_REQUEST['_wpnonce'];
	  if (! wp_verify_nonce($nonce_sp_cal, 'nonce_sp_cal') )
   	    die("Are you sure you want to do this?");
      published_spider_event($calendar_id, $id);
      show_spider_event($calendar_id);
      break;
    default:
      show_spider_calendar();
      break;
  }
}

function Manage_Spider_Category_Calendar(){
	require_once("calendar_functions.html.php");
	require_once("calendar_functions.php");
if (!function_exists('print_html_nav')) {
    require_once("nav_function/nav_html_func.php");
  }

global $wpdb;
  if (isset($_GET["task"])) {
    $task = esc_html($_GET["task"]);
  }
  else {
    $task = "";
	show_event_cat();
	return;
  }
  if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
  }
  else {
    $id = 0;
  }

switch($task){
	case 'add_category':
		edit_event_category($id);
	break;

	case 'save_category_event':
	if(!$id){
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
		save_spider_category_event();
		$id = $wpdb->get_var("SELECT MAX(id) FROM " . $wpdb->prefix . "spidercalendar_event_category");
		}
		else
		{
		check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
		apply_spider_category_event($id);
		}
		show_event_cat();
		break;
		
	case 'apply_event_category':
	 if (!$id) {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        save_spider_category_event();
        $id = $wpdb->get_var("SELECT MAX(id) FROM " . $wpdb->prefix . "spidercalendar_event_category");
      }
      else {
	    check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
        apply_spider_category_event($id);
      }
      edit_event_category($id);
		break;
		
	case 'edit_event_category':
		//apply_spider_category_event();
		edit_event_category($id);
		break;
		
	case 'remove_event_category':	
		check_admin_referer('nonce_sp_cal', 'nonce_sp_cal');
		remove_category_event($id);
		show_event_cat();
		break;
	case 'published':
		$nonce_sp_cal = $_REQUEST['_wpnonce'];
		if (! wp_verify_nonce($nonce_sp_cal, 'nonce_sp_cal') )
	      die("Are you sure you want to do this?");
		spider_category_published($id);
		show_event_cat();
		break;
	  }

}

function upcoming_widget(){
	require_once("calendar_functions.html.php");
	require_once("spidercalendar_upcoming_events_widget.php");
	require_once("calendar_functions.php");
	if (!function_exists('print_html_nav')) {
    require_once("nav_function/nav_html_func.php");
  }
 
	  global $wpdb;
 
  spider_upcoming();
}

function spider_widget_calendar_params() {
  wp_enqueue_script('media-upload');
  wp_admin_css('thickbox');
  if (!function_exists('print_html_nav')) {
    require_once("nav_function/nav_html_func.php");
  }
  require_once("widget_Themes_function.html.php");
  global $wpdb;
  if (isset($_GET["task"])) {
    $task = esc_html($_GET["task"]);
  }
  else {
    $task = "";
  }
  switch ($task) {
    case 'theme':
      html_show_theme_calendar_widget();
      break;
    default:
      html_show_theme_calendar_widget();
  }
}

// Themes.
function spider_calendar_params() {
  wp_enqueue_script('media-upload');
  wp_admin_css('thickbox');
  if (!function_exists('print_html_nav')) {
    require_once("nav_function/nav_html_func.php");
  }
  require_once("Themes_function.html.php"); // add functions for vive Spider_Video_Player
  global $wpdb;
  if (isset($_GET["task"])) {
    $task = esc_html($_GET["task"]);
  }
  else {
    $task = "";
  }
  switch ($task) {
    case 'theme':
      html_show_theme_calendar();
      break;
    default:
      html_show_theme_calendar();
  }
}

function Uninstall_sp_calendar() {
  global $wpdb,  $sp_calendar_options;
  if(!class_exists("DoradoWebConfig")){
		require_once("wd/config.php");
  }
  $config = new DoradoWebConfig();
  $config->set_options( $sp_calendar_options );
  $deactivate_reasons = new DoradoWebDeactivate($config);	
  $deactivate_reasons->submit_and_deactivate(); 
	
  $base_name = plugin_basename('Spider_Calendar');
  $base_page = 'admin.php?page=' . $base_name;
  $mode = (isset($_GET['mode']) ? trim($_GET['mode']) : '');
  ?>
	<?php upgrade_pro_sp(); ?>	
	<br />
	<div class="goodbye-text">
		Before uninstalling the plugin, please Contact our <a href="https://web-dorado.com/support/contact-us.html?source=spidercalendar" target= '_blank'>support team</a>. We'll do our best to help you out with your issue. We value each and every user and value what’s right for our users in everything we do.<br>
		However, if anyway you have made a decision to uninstall the plugin, please take a minute to <a href="https://web-dorado.com/support/contact-us.html?source=spidercalendar" target= '_blank'>Contact us</a> and tell what you didn't like for our plugins further improvement and development. Thank you !!!
	</div>	
  <?php
  if (!empty($_POST['do'])) {
    if ($_POST['do'] == "UNINSTALL Spider Event Calendar") {
      check_admin_referer('Spider_Calendar uninstall');
      
        echo '<form id="message" class="updated fade">';
        echo '<p>';
        echo "Table '" . $wpdb->prefix . "spidercalendar_event' has been deleted.";
		$wpdb->query("DROP TABLE " . $wpdb->prefix . "spidercalendar_event");
        echo '<font style="color:#000;">';
        echo '</font><br />';
        echo '</p>';
		echo '<p>';
        echo "Table '" . $wpdb->prefix . "spidercalendar_event_category' has been deleted.";
		$wpdb->query("DROP TABLE " . $wpdb->prefix . "spidercalendar_event_category");
        echo '<font style="color:#000;">';
        echo '</font><br />';
        echo '</p>';		
        echo '<p>';
        echo "Table '" . $wpdb->prefix . "spidercalendar_calendar' has been deleted.";
		$wpdb->query("DROP TABLE " . $wpdb->prefix . "spidercalendar_calendar");
        echo '<font style="color:#000;">';
        echo '</font><br />';
        echo '</p>';
		 echo '<p>';
        echo "Table '" . $wpdb->prefix . "spidercalendar_theme' has been deleted.";
		$wpdb->query("DROP TABLE " . $wpdb->prefix . "spidercalendar_theme");
        echo '<font style="color:#000;">';
        echo '</font><br />';
        echo '</p>';
        echo '<p>';
        echo "Table '" . $wpdb->prefix . "spidercalendar_widget_theme' has been deleted.";
        $wpdb->query("DROP TABLE " . $wpdb->prefix . "spidercalendar_widget_theme");
        echo '<font style="color:#000;">';
        echo '</font><br />';
        echo '</p>';
        echo '</form>';
		delete_option('sp_calendar_subscribe_done');
        $mode = 'end-UNINSTALL';      
    }
  }
    
  switch ($mode) {
    case 'end-UNINSTALL':
      echo '<div class="wrap">';
      echo '<h2>Uninstall Spider Event Calendar</h2>';
      echo '<p><strong><a href="#"  class="sp_calendar_deactivate_link" data-uninstall="1">Click Here</a> To Finish The Uninstallation And Spider Event Calendar Will Be Deactivated Automatically.</strong></p>';
      echo '</div>';
      break;
    // Main Page
    default:
      ?>
      <form method="post" id="uninstall_form"  action="<?php echo admin_url('admin.php?page=Uninstall_sp_calendar'); ?>">
        <?php wp_nonce_field('Spider_Calendar uninstall'); ?>
        <div class="wrap">
          <div id="icon-Spider_Calendar" class="icon32"><br/></div>
          <h2><?php echo 'Uninstall Spider Event Calendar'; ?></h2>

          <p>
            <?php echo 'Deactivating Spider Event Calendar plugin does not remove any data that may have been created. To completely remove this plugin, you can uninstall it here.'; ?>
          </p>

          <p style="color: red">
            <strong><?php echo'WARNING:'; ?></strong>
            <?php echo 'Once uninstalled, this cannot be undone. You should use a Database Backup plugin of WordPress to back up all the data first.'; ?>
          </p>

          <p style="color: red">
            <strong><?php echo 'The following WordPress Options/Tables will be DELETED:'; ?></strong><br/>
          </p>
          <table class="widefat">
            <thead>
            <tr>
              <th><?php echo 'WordPress Tables'; ?></th>
            </tr>
            </thead>

            <tr>
              <td valign="top">
                <ol>
                  <?php
                  echo '<li>' . $wpdb->prefix . 'spidercalendar_event</li>' . "\n";
				  echo '<li>' . $wpdb->prefix . 'spidercalendar_event_category</li>' . "\n";
                  echo '<li>' . $wpdb->prefix . 'spidercalendar_calendar</li>' . "\n";
				  echo '<li>' . $wpdb->prefix . 'spidercalendar_theme</li>' . "\n";
                  echo '<li>' . $wpdb->prefix . 'spidercalendar_widget_theme</li>' . "\n";
                  ?>
                </ol>
              </td>
            </tr>
          </table>
		  <script>
		  function uninstall(){
		  jQuery(document).ready(function() {
				  if(jQuery('#uninstall_yes').is(':checked')){
					var answer = confirm('<?php echo 'You Are About To Uninstall Spider Event Calendar From WordPress.\nThis Action Is Not Reversible.\n\n Choose [Cancel] To Stop, [OK] To Uninstall.'; ?>');
				
					if(answer)
						jQuery("#uninstall_form").submit();
					}
				  else
					alert('To uninstall please check the box above.');

			  });
		  }
		  </script>
          <p style="text-align: center;">
              <?php echo 'Do you really want to uninstall Spider Event Calendar?'; ?><br/><br/>
            <input type="checkbox" value="yes" id="uninstall_yes" />&nbsp;<?php echo 'Yes'; ?><br/><br/>
			  <input type="hidden" name="do" value="UNINSTALL Spider Event Calendar" />
            <input type="button" name="DODO" value="<?php echo 'UNINSTALL Spider Event Calendar'; ?>"
                   class="button-primary"
                   onclick="uninstall()"/>
          </p>
        </div>
      </form>
      <?php
	   
  }
}

add_action('init', 'spider_calendar_export');
function spider_calendar_export() {
     if (isset($_POST['export_spider_calendar']) && $_POST['export_spider_calendar'] == 'Export') {
        global $wpdb;
        $tmp_folder = get_temp_dir();        
        $select_spider_categories = "SELECT * from " . $wpdb->prefix . "spidercalendar_event_category";
        $spider_cats = $wpdb->get_results($select_spider_categories);
        $cat_columns = array(
            array('id', 'title', 'published', 'color', 'description')
        );
        if ($spider_cats) {
            foreach ($spider_cats as $cat) {
                $cat_columns[] = array(
                    $cat->id,
                    $cat->title,
                    $cat->published,
                    $cat->color,
                    $cat->description
                );
            }
        }
        $cat_handle = fopen($tmp_folder . '/sc_categories.csv', 'w+');
        foreach ($cat_columns as $ar) {
            if (fputcsv($cat_handle, $ar, ',') === FALSE) {
                break;
            }
        }
        @fclose($cat_handle);        
        $select_spider_calendars = "SELECT * from " . $wpdb->prefix . "spidercalendar_calendar";
        $spider_calendars = $wpdb->get_results($select_spider_calendars);
        $cal_columns = array(
            array('id', 'title', 'published')
        );
        if ($spider_calendars) {
            foreach ($spider_calendars as $cal) {
                $cal_columns[] = array(
                    $cal->id,
                    $cal->title,
                    $cal->published
                );
            }
        }
        $cal_handle = fopen($tmp_folder . '/sc_calendars.csv', 'w+');
        foreach ($cal_columns as $ar) {
            if (fputcsv($cal_handle, $ar, ',') === FALSE) {
                break;
            }
        }
        @fclose($cal_handle);        
        $select_spider_events = "SELECT * from " . $wpdb->prefix . "spidercalendar_event";
        $spider_events = $wpdb->get_results($select_spider_events);
        $events_columns = array(
            array('id', 'cal_id', 'start_date', 'end_date', 'title', 'cat_id',
                'time', 'text_for_date', 'userID', 'repeat_method', 'repeat', 'week',
                'month', 'month_type', 'monthly_list', 'month_week', 'year_month', 'published')
        );
        if ($spider_events) {
            foreach ($spider_events as $ev) {
                $events_columns[] = array(
                    $ev->id,
                    $ev->calendar,
                    $ev->date,
                    $ev->date_end,
                    $ev->title,
                    $ev->category,
                    $ev->time,
                    $ev->text_for_date,
                    $ev->userID,
                    $ev->repeat_method,
                    $ev->repeat,
                    $ev->week,
                    $ev->month,
                    $ev->month_type,
                    $ev->monthly_list,
                    $ev->month_week,
                    $ev->year_month,
                    $ev->published
                );
            }
        }
        $ev_handle = fopen($tmp_folder . '/sc_events.csv', 'w+');
        foreach ($events_columns as $ar) {
            if (fputcsv($ev_handle, $ar, ',') === FALSE) {
                break;
            }
        }
        @fclose($ev_handle);
        $files = array(
            'sc_categories.csv',
            'sc_calendars.csv',
            'sc_events.csv'
        );
        $zip = new ZipArchive();
        $tmp_file = tempnam('.', '');
        if ($zip->open($tmp_file, ZIPARCHIVE::CREATE) === TRUE) {
            foreach ($files as $file) {
                if (file_exists($tmp_folder . $file)) {
                    $zip->addFile($tmp_folder . $file, $file);
                }
            }
            $zip->close();
            header("Content-type: application/zip; charset=utf-8");
            header("Content-Disposition: attachment; filename=spider-event-calendar-export.zip");
            header("Content-length: " . filesize($tmp_file));
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile($tmp_file);
        }
        foreach ($files as $file) {
            @unlink($tmp_folder . $file);
        }
    }
}


function upgrade_pro_sp($text = false){
    $page = isset($_GET["page"]) ? $_GET["page"] : "";
?>
    <div class="sp_calendar_upgrade wd-clear" >
        <div class="wd-left">
        <?php
            switch($page){
                case "SpiderCalendar":
                ?>
                    <div style="font-size: 14px;">
                        <?php _e("This section allows you to create calendars.","sp_calendar");?>
                        <a style="color: #5CAEBD; text-decoration: none;border-bottom: 1px dotted;" target="_blank" href="https://web-dorado.com/wordpress-spider-calendar/creating-editing-calendar.html"><?php _e("Read More in User Manual.","sp_calendar");?></a>
                    </div>
                <?php      
                break;
				case "spider_calendar_event_category":
                ?>
                    <div style="font-size: 14px;">
                        <?php _e("This section allows you to create event categories.","sp_calendar");?>
                        <a style="color: #5CAEBD; text-decoration: none;border-bottom: 1px dotted;" target="_blank" href="https://web-dorado.com/wordpress-spider-calendar/adding-event-category.html"><?php _e("Read More in User Manual.","sp_calendar");?></a>
                    </div>
                <?php      
                break;
                case "calendar_export":
                ?>
                    <div style="font-size: 14px;">
                        <?php _e("This section will allow exporting Spider Calendar data for further import to Event Calendar WD.","sp_calendar");?>
                        <a style="color: #5CAEBD; text-decoration: none;border-bottom: 1px dotted;" target="_blank" href="https://web-dorado.com/products/wordpress-event-calendar-wd.html"><?php _e("Read More in User Manual.","sp_calendar");?></a>
                    </div> 
                <?php      
                break; 
				case "Uninstall_sp_calendar":
                ?>
                    <div style="font-size: 14px;">
                        <div class="page-banner uninstall-banner">
							<div class="uninstall_icon">
							</div>
							<div class="logo-title">Uninstall Spider Calendar </div>
						</div>
                    </div> 
                <?php      
                break;               
            }
        ?>
        </div>
        <div class="wd-right"> 
            <div class="wd-table">
                <div class="wd-cell wd-cell-valign-middle">
                    <a href="https://wordpress.org/support/plugin/spider-event-calendar" target="_blank">
                        <img src="<?php echo plugins_url('images/i_support.png', __FILE__); ?>" >
                        <?php _e("Support Forum", "sp_calendar"); ?>
                    </a>
                </div>            
                <div class="wd-cell wd-cell-valign-middle">
                    <a href="https://web-dorado.com/products/wordpress-calendar.html" target="_blank">
                    <?php _e("UPGRADE TO PAID VERSION", "sp_calendar"); ?>
                     </a> 
                </div>
            </div>     
                            
        </div>
    </div>
    <?php if($text){
    ?>
        <div class="wd-text-right wd-row" style="color: #15699F; font-size: 20px; margin-top:10px; padding:0px 15px;">
            <?php echo sprintf(__("This is FREE version, Customizing %s is available only in the PAID version.","sp_calendar"), $text);?>
        </div>
    <?php
    }

}

function calendar_export() {
    ?>
	<?php upgrade_pro_sp(); ?>
    <form method="post" style="font-size: 14px; font-weight: bold;">
        <input type='submit' value='Export' id="export_WD" name='export_spider_calendar' />
    </form>
	<style>
	#export_div{
		background: #fff;
		border: 1px solid #e5e5e5;
		-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04);
		box-shadow: 0 1px 1px rgba(0,0,0,.04);
		border-spacing: 0;
		width: 65%;
		clear: both;
		margin: 0;
		padding: 7px 7px 8px 10px;
		margin: 20px 0 10px 0;
	}

	#export_WD{
		font-size: 13px;
		padding: 7px 25px;
	}
	</style>
    <?php
}

// Activate plugin.
function SpiderCalendar_activate() {
  global $wpdb;
  $spider_event_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "spidercalendar_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_end` date NOT NULL,
  `title` text NOT NULL,
  `time` varchar(20) NOT NULL,
  `text_for_date` longtext NOT NULL,
  `userID` varchar(255) NOT NULL,
  `repeat_method` varchar(255) NOT NULL,
  `repeat` varchar(255) NOT NULL,
  `week` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL,
  `month_type` varchar(255) NOT NULL,
  `monthly_list` varchar(255) NOT NULL,
  `month_week` varchar(255) NOT NULL,
  `year_month` varchar(255) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
  $spider_calendar_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "spidercalendar_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `gid` varchar(255) NOT NULL,
  `def_zone` varchar(255) NOT NULL,
  `time_format` tinyint(1) NOT NULL,
  `allow_publish` varchar(255) NOT NULL,
  `start_month` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
$spider_category_event_table = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "spidercalendar_event_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `color` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
  $wpdb->query($spider_event_table);
  $wpdb->query($spider_calendar_table);
  $wpdb->query($spider_category_event_table);
  require_once "spider_calendar_update.php";
  spider_calendar_chech_update();
}
register_activation_hook(__FILE__, 'SpiderCalendar_activate');

function spider_calendar_ajax_func() {
  ?>
  <script>
    var spider_calendar_ajax = '<?php echo admin_url("admin-ajax.php"); ?>';
  </script>
  <?php
}
add_action('admin_head', 'spider_calendar_ajax_func');
?>