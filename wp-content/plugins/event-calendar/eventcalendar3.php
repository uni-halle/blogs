<?php
/*
Plugin Name: Event Calendar
Version: 3.1.4
Plugin URI: http://wpcal.firetree.net
Description: Manage future events as an online calendar. Display upcoming events in a dynamic calendar, on a listings page, or as a list in the sidebar. You can subscribe to the calendar from iCal (OSX) or Sunbird. Change settings on the <a href="options-general.php?page=ec3_admin">Event Calendar Options</a> screen.
Author: Alex Tingle
Author URI: http://blog.firetree.net/
*/

/*
Copyright (c) 2005-2008, Alex Tingle.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

require_once(dirname(__FILE__).'/options.php');
require_once(dirname(__FILE__).'/date.php');
require_once(dirname(__FILE__).'/day.php');
require_once(dirname(__FILE__).'/template-functions.php');
require_once(dirname(__FILE__).'/admin.php');
require_once(dirname(__FILE__).'/tz.php');


$ec3_today_id=str_replace('_0','_',ec3_strftime("ec3_%Y_%m_%d"));


/** Read the schedule table for the posts, and add an ec3_schedule array
 * to each post. */
function ec3_filter_the_posts($posts)
{
  if('array'!=gettype($posts) || 0==count($posts))
    return $posts;

  $post_ids=array();
  // Can't use foreach, because it gets *copies* (in PHP<5)
  for($i=0; $i<count($posts); $i++)
  {
    $post_ids[]=intval($posts[$i]->ID);
    $posts[$i]->ec3_schedule=array();
  }
  global $ec3,$wp_query,$wpdb;
  $schedule=$wpdb->get_results(
    "SELECT post_id,start,end,allday,rpt,IF(end>='$ec3->today',1,0) AS active
     FROM $ec3->schedule
     WHERE post_id IN (".implode(',',$post_ids).")
     ORDER BY start"
  );
  // Flip $post_ids so that it maps post ID to position in the $posts array.
  $post_ids=array_flip($post_ids);
  if($post_ids && $schedule)
      foreach($schedule as $s)
      {
        $i=$post_ids[$s->post_id];
        $posts[$i]->ec3_schedule[]=$s;
      }
  return $posts;
}


function ec3_action_wp_head()
{
  global $ec3,$month,$month_abbrev;
?>

	<!-- Added by EventCalendar plugin. Version <?php echo $ec3->version; ?> -->
	<script type='text/javascript' src='<?php echo $ec3->myfiles; ?>/xmlhttprequest.js'></script>
	<script type='text/javascript' src='<?php echo $ec3->myfiles; ?>/ec3.js'></script>
	<script type='text/javascript'><!--
	ec3.start_of_week=<?php echo intval( get_option('start_of_week') ); ?>;
	ec3.month_of_year=new Array('<?php echo implode("','",$month); ?>');
	ec3.month_abbrev=new Array('<?php echo implode("','",$month_abbrev); ?>');
	ec3.myfiles='<?php echo $ec3->myfiles; ?>';
	ec3.home='<?php echo get_option('home'); ?>';
	ec3.hide_logo=<?php echo $ec3->hide_logo; ?>;
	ec3.viewpostsfor="<?php echo __('View posts for %1$s %2$s'); ?>";
	// --></script>

<?php if(!$ec3->nocss): ?>
<style type='text/css' media='screen'>
@import url(<?php echo $ec3->myfiles; ?>/ec3.css);
.ec3_ec {
 background-image:url(<?php echo $ec3->myfiles; ?>/ec.png) !IMPORTANT;
 background-image:none;
 filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $ec3->myfiles; ?>/ec.png');
}
<?php   if(!$ec3->disable_popups): ?>
#ec3_shadow0 {
 background-image:url(<?php echo $ec3->myfiles; ?>/shadow0.png) !IMPORTANT;
 background-image:none;
}
#ec3_shadow0 div {
 filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $ec3->myfiles; ?>/shadow0.png',sizingMethod='scale');
}
#ec3_shadow1 {
 background-image:url(<?php echo $ec3->myfiles; ?>/shadow1.png) !IMPORTANT;
 background-image:none;
 filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $ec3->myfiles; ?>/shadow1.png',sizingMethod='crop');
}
#ec3_shadow2 {
 background-image:url(<?php echo $ec3->myfiles; ?>/shadow2.png) !IMPORTANT;
 background-image:none;
}
#ec3_shadow2 div {
 filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $ec3->myfiles; ?>/shadow2.png',sizingMethod='scale');
}
<?php   endif; ?>
</style>

<?php endif;
}


/** Turn OFF advanced mode when we're in the admin screens. */
function ec3_action_admin_head()
{
  global $ec3;
  $ec3->advanced=false;
}


/** Rewrite date restrictions if the query is day- or category- specific. */
function ec3_filter_posts_where(&$where)
{
  global $ec3,$wp_query,$wpdb;

  // To prevent breaking prior to WordPress v2.3
  if(function_exists('get_the_tags') && $wp_query->is_tag)
      return $where;

  if($wp_query->is_page || $wp_query->is_single || $wp_query->is_admin)
      return $where;

  if($wp_query->is_date):

     // Transfer events' 'post_date' restrictions to 'start'
     $df='YEAR|MONTH|DAYOFMONTH|HOUR|MINUTE|SECOND|WEEK'; // date fields
     $re="/ AND (($df)\($wpdb->posts\.post_date(,[^\)]+)?\) *= *('[^']+'|\d+\b))/i";
     if(preg_match_all($re,$where,$matches)):
       $where_post_date = implode(' AND ',$matches[1]);

       $rdate=array(0,0,0);
       $rtime=array(0,0,0);
       for($i=0; $i<count($matches[1]); $i++)
       {
         if(          'YEAR'==$matches[2][$i]) $rdate[0]=$matches[4][$i];
         elseif(     'MONTH'==$matches[2][$i]) $rdate[1]=$matches[4][$i];
         elseif('DAYOFMONTH'==$matches[2][$i]) $rdate[2]=$matches[4][$i];
         elseif(      'HOUR'==$matches[2][$i]) $rtime[0]=$matches[4][$i];
         elseif(    'MINUTE'==$matches[2][$i]) $rtime[1]=$matches[4][$i];
         elseif(    'SECOND'==$matches[2][$i]) $rtime[2]=$matches[4][$i];
       }
       // start either matches the date criteria,
       //   OR the rdate/rtime is between start..end:
       $where_start=
       sprintf("(%1\$s) OR (start<='%2\$s' AND end>='%2\$s')",
         preg_replace("/\b$wpdb->posts\.post_date\b/",'start',$where_post_date),
         str_replace( "'", '', implode('-',$rdate).' '.implode(':',$rtime) )
       );

       $where=preg_replace($re,'',$where);
       if(is_category($ec3->event_category)):
         $where.=" AND ($where_start) ";
         $ec3->order_by_start=true;
       else:
         $where.=" AND (($where_post_date) OR ($where_start)) ";
       endif;
       $ec3->join_ec3_sch=true;
     endif;

  elseif($ec3->is_date_range):

     $where_start=array();
     if( !empty($ec3->range_from) )
       $where_start[]="end>='$ec3->range_from'";
     if( !empty($ec3->range_before) )
       $where_start[]="start<='$ec3->range_before'";

     if($where_start):
       $where_start=implode(' AND ',$where_start);
       $where.=" AND ($where_start AND ec3_sch.post_id IS NOT NULL) ";
       $ec3->order_by_start=true;
       $ec3->join_ec3_sch=true;
     endif;

  elseif($ec3->advanced):
      if(is_category($ec3->event_category)):

          // Hide inactive events
          $where.=" AND ec3_sch.post_id IS NOT NULL ";
          $ec3->join_ec3_sch=true;
          $ec3->join_only_active_events=true;
          $ec3->order_by_start=true;
          global $wp;
          $wp->did_permalink=false; // Allows zero results without -> 404

      elseif($wp_query->is_search):

          $where.=' AND (ec3_sch.post_id IS NULL OR '
                       ."ec3_sch.end>='$ec3->today')";
          $ec3->join_ec3_sch=true;

      elseif(!$wp_query->is_category):

          // Hide all events
          $where.=" AND ec3_sch.post_id IS NULL ";
          $ec3->join_ec3_sch=true;

      endif;
  endif;

  return $where;
}

/** */
function ec3_filter_posts_join(&$join)
{
  global $ec3,$wpdb;
  // The necessary joins are decided upon in ec3_filter_posts_where().
  if($ec3->join_ec3_sch || $ec3->order_by_start)
  {
    $join.=" LEFT JOIN $ec3->schedule ec3_sch ON ec3_sch.post_id=id ";
    if($ec3->join_only_active_events)
        $join.="AND ec3_sch.end>='$ec3->today' ";
  }
  return $join;
}

/** Change the order of event listings (only advanced mode). */
function ec3_filter_posts_orderby(&$orderby)
{
  global $ec3, $wpdb;
  if($ec3->order_by_start)
  {
    $regexp="/(?<!DATE_FORMAT[(])\b$wpdb->posts\.post_date\b( DESC\b| ASC\b)?/i";
    if(preg_match($regexp,$orderby,$match))
    {
      if($match[1] && $match[1]==' DESC')
        $orderby=preg_replace($regexp,'ec3_sch.start',$orderby);
      else
        $orderby=preg_replace($regexp,'ec3_sch.start DESC',$orderby);
    }
    else
    {
      // Someone's been playing around with the orderby - just overwrite it.
      $orderby='ec3_sch.start';
    }
  }
  return $orderby;
}


/** Eliminate double-listings for posts with >1 scheduled event. */
function ec3_filter_posts_groupby(&$groupby)
{
  global $ec3,$wpdb;
  if($ec3->join_ec3_sch || $ec3->order_by_start)
  {
    if(empty($groupby))
        $groupby="{$wpdb->posts}.ID";
    if($ec3->is_listing)
        $groupby.=',ec3_sch.sched_id';
  }
  return $groupby;
}


/** Add a sched_id field, if we want a listing. */
function ec3_filter_posts_fields(&$fields)
{
  global $ec3;
  if($ec3->is_listing && ($ec3->join_ec3_sch || $ec3->order_by_start))
    $fields.=',ec3_sch.sched_id';
  return $fields;
}


function ec3_filter_query_vars($wpvarstoreset)
{
  if(isset($_GET['ec3_xml']))
    ec3_filter_query_vars_xml();
  if(isset($_GET['ec3_ical']) || isset($_GET['ec3_vcal']))
    ec3_filter_query_vars_ical();
  if(isset($_GET['ec3_dump']))
    ec3_filter_query_vars_dump();
  // else...
  $wpvarstoreset[]='ec3_today';
  $wpvarstoreset[]='ec3_days';
  $wpvarstoreset[]='ec3_from'; // ?? Deprecated
  $wpvarstoreset[]='ec3_after';
  $wpvarstoreset[]='ec3_before';
  $wpvarstoreset[]='ec3_listing';
  // Turn-off broken canonical redirection when both m= & cat= are set.
  if(isset($_GET['m']) && isset($_GET['cat']))
    remove_action('template_redirect','redirect_canonical');
  return $wpvarstoreset;
}


/** If the parameter ec3_xml is set, then brutally hijack the page and replace
 *  it with XML calendar data. This is used by XmlHttpRequests from the 
 *  active calendar JavaScript. */
function ec3_filter_query_vars_xml()
{
  $components=explode('_',$_GET['ec3_xml']);
  if(count($components)==2)
  {
    $date=new ec3_Date($components[0],$components[1]);
    $end=$date->next_month();
    $calendar_days=ec3_util_calendar_days($date->month_id(),$end->month_id());
    @header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="'.get_option('blog_charset')
    .    '" standalone="yes"?>';
    echo "<calendar><month id='".$date->month_id()."'>\n";
    foreach($calendar_days as $day_id=>$day)
    {
      if('today'==$day_id)
        $dc=explode('_', ec3_strftime(":_%Y_%m_%d") );
      else
        $dc=explode('_',$day_id);
      if(count($dc)==4)
      {
        $date->day_num=$dc[3];
        $titles=$day->get_titles();
        echo "<day id='$day_id' is_event='$day->is_event'"
        .    " titles='$titles' link='" . $date->day_link() . "'/>\n";
      }
    }
    echo "</month></calendar>\n";
    exit(0);
  }
}


/** If the parameter ec3_ical is set, then brutally hijack the page and replace
 *  it with iCalendar data.
 * (Includes fixes contributed by Matthias Tarasiewicz & Marc Schumann.)*/
function ec3_filter_query_vars_ical($wpvarstoreset=NULL)
{
  //
  // Generate the iCalendar

  $name=preg_replace('/([\\,;])/','\\\\$1',get_bloginfo_rss('name'));
  $filename=preg_replace('/[^0-9a-zA-Z]/','',$name).'.ics';

  header("Content-Type: text/calendar; charset=" . get_option('blog_charset'));
  header("Content-Disposition: inline; filename=$filename");
  header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
  header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  header('Cache-Control: no-cache, must-revalidate, max-age=0');
  header('Pragma: no-cache');

  echo "BEGIN:VCALENDAR\r\n";
  echo "VERSION:2.0\r\n";
  echo "X-WR-CALNAME:$name\r\n";

  global $ec3,$wpdb;

  $calendar_entries = $wpdb->get_results(
    "SELECT
         post_id,
         sched_id,
         post_title,
         post_excerpt,
         DATE_FORMAT(start,IF(allday,'%Y%m%d','%Y-%m-%d %H:%i')) AS dt_start,
         IF( allday,
             DATE_FORMAT(DATE_ADD(end, INTERVAL 1 DAY),'%Y%m%d'),
             DATE_FORMAT(end,'%Y-%m-%d %H:%i')
           ) AS dt_end,
         $ec3->wp_user_nicename AS user_nicename,
         IF(allday,'TRANSPARENT','OPAQUE') AS transp,
         allday
       FROM $wpdb->posts p
       LEFT  JOIN $wpdb->users   u ON p.post_author=u.ID
       INNER JOIN $ec3->schedule s ON p.id=s.post_id
       WHERE post_status='publish'
       ORDER BY start"
  );

  if($calendar_entries)
    foreach($calendar_entries as $entry)
    {
      // ?? Should add line folding at 75 octets at some time as per RFC 2445.
      $summary=preg_replace('/([\\,;])/','\\\\$1',$entry->post_title);
      $permalink=get_permalink($entry->post_id);

      echo "BEGIN:VEVENT\r\n";
      echo "SUMMARY:$summary\r\n";
      echo "URL;VALUE=URI:$permalink\r\n";
      echo "UID:$entry->sched_id-$permalink\r\n";
      $description='';
      if(strlen($entry->post_excerpt)>0)
      {
        // I can't get iCal to understand iCalendar encoding.
        // So just strip out newlines here:
        $description=preg_replace('/[ \r\n]+/',' ',$entry->post_excerpt.' ');
        $description=preg_replace('/([\\,;])/','\\\\$1',$description);
      }
      $description.='['.sprintf(__('by: %s'),$entry->user_nicename).']';
      echo "DESCRIPTION:$description\r\n";
      echo "TRANSP:$entry->transp\r\n"; // for availability.
      if($entry->allday)
      {
        echo "DTSTART;VALUE=DATE:$entry->dt_start\r\n";
        echo "DTEND;VALUE=DATE:$entry->dt_end\r\n";
      }
      else
      {
        // Convert timestamps to UTC
        echo sprintf("DTSTART;VALUE=DATE-TIME:%s\r\n",ec3_to_utc($entry->dt_start));
        echo sprintf("DTEND;VALUE=DATE-TIME:%s\r\n",ec3_to_utc($entry->dt_end));
      }
      echo "END:VEVENT\r\n";
    }

  echo "END:VCALENDAR\r\n";
  exit(0);
}


/** Test function. Helps to diagnose problems.
 * The output from this feature has been chosen to NOT reveal any private
 * information, yet be of real use for debugging.
 */
function ec3_filter_query_vars_dump($wpvarstoreset=NULL)
{
  global $ec3, $wpdb;
  echo "<pre>\n";
  echo "POSTS:\n";
  print_r( $wpdb->get_results(
    "SELECT ID,post_date,post_date_gmt,post_status,post_name,post_modified,
       post_modified_gmt,post_type
     FROM $wpdb->posts ORDER BY ID"
  ));
  if($ec3->wp_have_categories)
  {
    echo "POST2CAT:\n";
    print_r($wpdb->get_results("SELECT * FROM $wpdb->post2cat ORDER BY post_id"));
  }
  echo "EC3_SCHEDULE:\n";
  print_r($wpdb->get_results("SELECT * FROM $ec3->schedule ORDER BY post_id"));
  echo "EC3 OPTIONS:\n";
  print_r($wpdb->get_results(
    "SELECT option_name,option_value
     FROM $wpdb->options WHERE option_name LIKE 'ec3_%'"
  ));
  echo "ACTIVE PLUGINS:\n";
  print_r( $wpdb->get_var(
    "SELECT option_value
     FROM $wpdb->options WHERE option_name='active_plugins'"
  ));
  echo "</pre>\n";
  exit(0);
}


/** Add support for new query vars:
 *
 *  - ec3_today : sets date to today.
 *  - ec3_days=N : Finds events for the next N days.
 *  - ec3_after=YYYY-MM-DD : limits search to events on or after YYYY-MM-DD.
 *  - ec3_before=YYYY-MM-DD : limits search to events on or before YYYY-MM-DD.
 */
function ec3_filter_parse_query($wp_query)
{
  global $ec3;
  // query_posts() can be called multiple times. So reset all our variables.
  $ec3->reset_query();
  // Deal with EC3-specific parameters.
  if( !empty($wp_query->query_vars['ec3_listing']) )
  {
    $ec3->is_listing=true;
  }
  if( !empty($wp_query->query_vars['ec3_today']) )
  {
    // Force the value of 'm' to today's date.
    $wp_query->query_vars['m']=ec3_strftime('%Y%m%d');
    $wp_query->is_date=true;
    $wp_query->is_day=true;
    $wp_query->is_month=true;
    $wp_query->is_year=true;
    $ec3->is_today=true;
    return;
  }
  if( !empty($wp_query->query_vars['ec3_days']) )
  {
    // Show the next N days.
    $ec3->days=intval($wp_query->query_vars['ec3_days']);
    $secs=$ec3->days*24*3600;
    $wp_query->query_vars['ec3_after' ]=ec3_strftime('%Y_%m_%d');
    $wp_query->query_vars['ec3_before']=ec3_strftime('%Y_%m_%d',time()+$secs);
  }

  // Get values (if any) for after ($a) & before ($b).
  if( !empty($wp_query->query_vars['ec3_after']) )
      $a=$wp_query->query_vars['ec3_after'];
  else if( !empty($wp_query->query_vars['ec3_from']) )
      $a=$wp_query->query_vars['ec3_from'];
  $b=$wp_query->query_vars['ec3_before'];
  if( $a=='today' )
      $a=ec3_strftime('%Y-%m-%d');
  if( $b=='today' )
      $b=ec3_strftime('%Y-%m-%d');

  $re='/\d\d\d\d[-_]\d?\d[-_]\d?\d/';
  if( !empty($a) && preg_match($re,$a) ||
      !empty($b) && preg_match($re,$b) )
  {
    // Kill any other date parameters.
    foreach(array('m','second','minute','hour','day','monthnum','year','w')
            as $param)
    {
      unset($wp_query->query_vars[$param]);
    }
    $wp_query->is_date=false;
    $wp_query->is_time=false;
    $wp_query->is_day=false;
    $wp_query->is_month=false;
    $wp_query->is_year=false;
    $ec3->is_date_range=true;
    $ec3->range_from  =$a;
    $ec3->range_before=$b;
  }
}


function ec3_filter_the_content(&$post_content)
{
  return ec3_get_schedule() . $post_content;
}


/** Replaces default wp_trim_excerpt filter. Fakes an excerpt if needed.
 *  Adds a textual summary of the schedule to the excerpt.*/
function ec3_get_the_excerpt($text)
{
  global $post;

  if(empty($text))
  {
    $text=$post->post_content;
    if(!$post->ec3_schedule)
        $text=apply_filters('the_content', $text);
    $text=str_replace(']]>', ']]&gt;', $text);
    $text=strip_tags($text);
    $excerpt_length=55;
    $words=explode(' ', $text, $excerpt_length + 1);
    if(count($words) > $excerpt_length)
    {
      array_pop($words);
      array_push($words, '[...]');
      $text=implode(' ', $words);
    }
  }

  if($post->ec3_schedule)
  {
    $schedule=ec3_get_schedule('%s; ',"%1\$s %3\$s %2\$s. ",'[ %s] ');
    $text=$schedule.$text;
  }
  
  return $text;
}


//
// Hook in...
if($ec3->event_category)
{
  add_action('wp_head',      'ec3_action_wp_head');
  add_action('admin_head',   'ec3_action_admin_head');
  add_filter('query_vars',   'ec3_filter_query_vars');
  add_filter('parse_query',  'ec3_filter_parse_query');
  add_filter('posts_where',  'ec3_filter_posts_where',11);
  add_filter('posts_join',   'ec3_filter_posts_join');
  add_filter('posts_groupby','ec3_filter_posts_groupby');
  add_filter('posts_fields', 'ec3_filter_posts_fields');
  add_filter('the_posts',    'ec3_filter_the_posts');
  
  if(!$ec3->hide_event_box)
    add_filter('the_content','ec3_filter_the_content',20);
  
  remove_filter('get_the_excerpt', 'wp_trim_excerpt');
  add_filter('get_the_excerpt', 'ec3_get_the_excerpt');
  
  if($ec3->advanced)
    add_filter('posts_orderby','ec3_filter_posts_orderby',11);
}

?>
