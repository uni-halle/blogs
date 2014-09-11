<?php
/*
Plugin Name: Event Calendar Widget
Plugin URI: http://wpcal.firetree.net
Description: Adds sidebar widgets for Event Calendar and Upcoming Events. Requires the EventCalendar and <a href="http://automattic.com/code/widgets/">Widget</a> plugins (WordPress version 2.1 and earlier). After activating, please visit <a href="themes.php?page=widgets/widgets.php">Sidebar Widgets for WordPress version 2.1 and earlier</a> or <a href="widgets.php">Widgets for WordPress version 2.2 and subsequent</a> to configure and arrange your new widgets.
Author: Darrell Schulte
Version: 3.1.4
Author URI: http://wpcal.firetree.net

    This is a WordPress plugin (http://wordpress.org) and widget
    (http://automattic.com/code/widgets/).
*/

/*
Copyright (c) 2006, Darrell Schulte.  $Revision: 285 $

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

function ec3_widget_init() 
{

  if ( !function_exists('register_sidebar_widget') )
    return;

  /** Utility function: Gets the (possibly translated) widget title, given the
   *  value of the 'title' option. */
  function ec3_widget_title($title,$default)
  {
    if ( empty($title) )
        return __($default,'ec3');
    else
        return apply_filters('widget_title',$title);
  }


  /** Event Calendar widget. */
  function ec3_widget_cal($args) 
  {
    extract($args);
    $options = get_option('ec3_widget_cal');
    echo $before_widget . $before_title;
    echo ec3_widget_title($options['title'],'Event Calendar');
    echo $after_title;
    ec3_get_calendar(); 
    echo $after_widget;
  }

  function ec3_widget_cal_control() 
  {
    $options = $newoptions = get_option('ec3_widget_cal');
    if ( $_POST["ec3_cal_submit"] ) 
    {
      $newoptions['title']=strip_tags(stripslashes($_POST["ec3_cal_title"]));
    }
    if ( $options != $newoptions ) 
    {
      $options = $newoptions;
      update_option('ec3_widget_cal', $options);
    }
    $title = ec3_widget_title($options['title'],'Event Calendar');
    ?>
    <p>
     <label for="ec3_cal_title">
      <?php _e('Title:'); ?>
      <input class="widefat" id="ec3_cal_title" name="ec3_cal_title"
       type="text" value="<?php echo htmlspecialchars($title,ENT_QUOTES); ?>" />
     </label>
    </p>

    <p><a href="options-general.php?page=ec3_admin">
      <?php _e('Go to Event Calendar Options','ec3') ?>.</a>
    </p>

    <input type="hidden" name="ec3_cal_submit" value="1" />
    <?php
  }

  wp_register_sidebar_widget(
    'event-calendar',
    __('Event Calendar','ec3'),
    'ec3_widget_cal', 
    array('description' =>
          __( 'Display upcoming events in a dynamic calendar.','ec3')
              . ' (Event Calendar '. __('Plugin') .')' ) 
  );

  register_widget_control(
    array(__('Event Calendar','ec3'),'widgets'),
    'ec3_widget_cal_control'
  );


  /** Upcoming Events widget. */
  function ec3_widget_list($args) 
  {
    extract($args);
    $options = get_option('ec3_widget_list');
    echo $before_widget . $before_title;
    echo ec3_widget_title($options['title'],'Upcoming Events');
    echo $after_title;
    ec3_get_events(
      $options['limit'],
      EC3_DEFAULT_TEMPLATE_EVENT,
      EC3_DEFAULT_TEMPLATE_DAY,
      get_option('date_format')
    );
    echo $after_widget;
  }

  function ec3_widget_list_control() 
  {
    $options = $newoptions = get_option('ec3_widget_list');
    if ( $_POST["ec3_list_submit"] ) 
    {
      $newoptions['title'] = strip_tags(stripslashes($_POST["ec3_list_title"]));
      $newoptions['limit'] = strip_tags(stripslashes($_POST["ec3_limit"]));
    }
    if ( $options != $newoptions ) 
    {
      $options = $newoptions;
      update_option('ec3_widget_list', $options);
    }
  
    $title = ec3_widget_title($options['title'],'Upcoming Events');
    $limit = $options['limit'];

    $ec3_limit_title =
      __("Examples: '5', '5 days', '5d'. To display recent past events, use a negative number: '-5'.");
    ?>

    <p>
     <label for="ec3_list_title">
      <?php _e('Title:'); ?>
      <input class="widefat" id="ec3_list_title" name="ec3_list_title"
       type="text" value="<?php echo htmlspecialchars($title,ENT_QUOTES); ?>" />
     </label>
    </p>
    <p>
     <label for="ec3_limit" title="<?php echo $ec3_limit_title ?>">
      <?php _e('Number of events:','ec3'); ?>
      <input class="widefat" style="width: 50px; text-align: center;"
       id="ec3_limit" name="ec3_limit" type="text"
       value="<?php echo $limit? $limit: '5'; ?>" />
     </label>
    </p>
    
    <p>
      <a href="options-general.php?page=ec3_admin">
       <?php _e('Go to Event Calendar Options','ec3') ?>.</a>
    </p>

    <input type="hidden" name="ec3_list_submit" value="1" />

    <?php
  }

  wp_register_sidebar_widget( 
    'upcoming-events',
    __('Upcoming Events','ec3'),
    'ec3_widget_list', 
    array('description' =>
          __('Display upcoming events as a list.','ec3')
              . ' (Event Calendar '. __('Plugin') .')' ) 
  );

  register_widget_control(
    array(__('Upcoming Events','ec3'),'widgets'),
    'ec3_widget_list_control'
  );
}

add_action('widgets_init', 'ec3_widget_init');

?>
