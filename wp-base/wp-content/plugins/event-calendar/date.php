<?php
/*
Copyright (c) 2005, Alex Tingle.  $Revision: 287 $

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

/** Calendar class. Encapsulates all functionality concerning the calendar - 
 *  how many days in each month, leap years, days of the week, locale
 *  names etc. */
class ec3_Date
{
  var $year_num =0;
  var $month_num=0;
  var $day_num  =1;
  var $_unixdate      =0;
  var $_days_in_month =0;

  function ec3_Date($year_num=0,$month_num=0,$day_num=0)
  {
    global $ec3;
    if($year_num>0)
    {
      $this->year_num =$year_num;
      $this->month_num=$month_num;
      $this->day_num  =$day_num;
    }
    if(0==$this->year_num && is_single())
        $this->from_single();
    if(0==$this->year_num && $ec3->is_date_range)
        $this->from_date_range();
    if(0==$this->year_num)
        $this->from_date(); // Falls back to today.
  }

  function from_single()
  {
    global $wp_query;
    if($wp_query->posts && $wp_query->posts[0]->ec3_schedule)
    {
      $this->year_num=
        intval(mysql2date('Y',$wp_query->posts[0]->ec3_schedule[0]->start));
      $this->month_num=
        intval(mysql2date('m',$wp_query->posts[0]->ec3_schedule[0]->start));
    }
  }

  function from_date_range()
  {
    global $wp_query;
    if('' != $wp_query->query_vars['ec3_from'])
    {
      $c=explode('_',$wp_query->query_vars['ec3_from']);
      $this->year_num=intval($c[0]);
      $this->month_num=intval($c[1]);
    }
  }

  /** Utility function. Calculates the value of month/year for the current
   *  page. Code block from wp-includes/template-functions-general.php
   *  (get_calendar function). */
  function from_date()
  {
    global
      $m,
      $monthnum,
      $wpdb,
      $year;

    if (isset($_GET['w'])) {
        $w = ''.intval($_GET['w']);
    }

    // Let's figure out when we are
    if (!empty($monthnum) && !empty($year)) {
        $thismonth = ''.zeroise(intval($monthnum), 2);
        $thisyear = ''.intval($year);
    } elseif (!empty($w)) {
        // We need to get the month from MySQL
        $thisyear = ''.intval(substr($m, 0, 4));
        $d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
        $thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('${thisyear}0101', INTERVAL $d DAY) ), '%m')");
    } elseif (!empty($m)) {
//        $calendar = substr($m, 0, 6);
        $thisyear = ''.intval(substr($m, 0, 4));
        if (strlen($m) < 6) {
            $thismonth = '01';
        } else {
            $thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
        }
    } else {
        $thisyear=ec3_strftime("%Y");
        $thismonth=ec3_strftime("%m");
    }
    
    $this->year_num =intval($thisyear);
    $this->month_num=intval($thismonth);
    $this->day_num  =1;
  }
  
  /** Month arithmetic. Returns a new date object. */
  function plus_months($month_count)
  {
    $result=new ec3_Date($this->year_num,$this->month_num,$this->day_num);
    $result->month_num += $month_count;
    if($month_count>0)
    {
      while($result->month_num>12)
      {
        $result->month_num -= 12;
        $result->year_num++;
      }
    }
    else
    {
      while($result->month_num<1)
      {
        $result->month_num += 12;
        $result->year_num--;
      }
    }
    return $result;
  }
  /** Convenience function for accessing plus_months(). */
  function prev_month() { return $this->plus_months(-1); }
  function next_month() { return $this->plus_months( 1); }
  
  /** Modifies the current object to be one day in the future. */
  function increment_day()
  {
    $this->day_num++;
    if($this->day_num > $this->days_in_month())
    {
      $this->day_num=1;
      $this->month_num++;
      if($this->month_num>12)
      {
        $this->month_num=1;
        $this->year_num++;
      }
      $this->_days_in_month=0;
    }
    $this->_unixdate=0;
  }
  
  function month_id() // e.g. ec3_2005_06
  {
    return 'ec3_' . $this->year_num . '_' . $this->month_num;
  }
  function day_id()  // e.g. ec3_2005_06_25
  {
    $result='ec3_'.$this->year_num.'_'.$this->month_num.'_'.$this->day_num;
    global $ec3_today_id;
    if($result==$ec3_today_id)
      return 'today';
    else
      return $result;
  }
  function day_link()
  {
    global $ec3;
    if($ec3->show_only_events)
    {
      return get_option('home') . '/?m='
       . $this->year_num
       . zeroise($this->month_num, 2)
       . zeroise($this->day_num, 2)
       . "&amp;cat=" . $ec3->event_category;
    }
    else
      return get_day_link($this->year_num,$this->month_num,$this->day_num);
  }
  function month_name() // e.g. June
  {
    global $month;
    return $month[zeroise($this->month_num,2)];
  }
  function month_abbrev() // e.g. Jun
  {
    global $month_abbrev;
    return $month_abbrev[ $this->month_name() ];
  }
  function month_link()
  {
    global $ec3;
    if($ec3->show_only_events)
    {
      return get_option('home') . '/?m='
       . $this->year_num
       . zeroise($this->month_num, 2)
       . "&amp;cat=" . $ec3->event_category;
    }
    else
      return get_month_link($this->year_num,$this->month_num);
  }
  function days_in_month()
  {
    if(0==$this->_days_in_month)
      $this->_days_in_month=intval(date('t', $this->to_unixdate()));
    return $this->_days_in_month;
  }
  function week_day()
  {
    return intval(date('w', $this->to_unixdate()));
  }
  function to_unixdate()
  {
    if(0==intval($this->_unixdate))
    {
      $this->_unixdate =
        mktime(0,0,0, $this->month_num,$this->day_num,$this->year_num);
    }
    return $this->_unixdate;
  }
} // end class ec3_Date


/** Converts a MySQL date object into an EC3 date object. */
function ec3_mysql2date(&$mysqldate)
{
  $as_str=mysql2date('Y,n,j',$mysqldate);
  $as_arr=explode(',',$as_str);
  return new ec3_Date($as_arr[0],$as_arr[1],$as_arr[2]);
}

/** Converts a day or month Id to a PHP date. */
function ec3_dayid2php(&$id)
{
  $parts=explode('_',$id);
  $year =intval($parts[1]);
  $month=intval($parts[2]);
  $day  =(count($parts)>=4? intval($parts[3]): 1);
  return mktime( 0,0,0, $month,$day,$year);
}

?>
