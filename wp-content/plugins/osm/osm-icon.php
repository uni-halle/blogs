<?php
/*  (c) Copyright 2014  Michael Kang (wp-osm-plugin.HanBlog.Net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Osm_icon
{
 function getIconsize($a_IconName)
 {
  $Icons = array(
    "airport.png"        => array("height"=>32,"width"=>"31","offset_height"=>"-16","offset_width"=>"-16"),
    "bicycling.png"      => array("height"=>19,"width"=>"32","offset_height"=>"-9","offset_width"=>"-16"),
    "bus.png"            => array("height"=>32,"width"=>"26","offset_height"=>"-16","offset_width"=>"-13"),
    "camping.png"        => array("height"=>32,"width"=>"32","offset_height"=>"-16","offset_width"=>"-16"),
    "car.png"            => array("height"=>18,"width"=>"32","offset_height"=>"-16","offset_width"=>"-9"),
    "friends.png"        => array("height"=>32,"width"=>"32","offset_height"=>"-16","offset_width"=>"-16"),
    "geocache.png"       => array("height"=>25,"width"=>"25","offset_height"=>"-12","offset_width"=>"-12"),
    "guest_house.png"    => array("height"=>32,"width"=>"32","offset_height"=>"-16","offset_width"=>"-16"),
    "home.png"           => array("height"=>32,"width"=>"32","offset_height"=>"-16","offset_width"=>"-16"),
    "hostel.png"         => array("height"=>24,"width"=>"24","offset_height"=>"-12","offset_width"=>"-12"),
    "hotel.png"          => array("height"=>32,"width"=>"32","offset_height"=>"-16","offset_width"=>"-16"),
    "marker_blue.png"    => array("height"=>24,"width"=>"24","offset_height"=>"-12","offset_width"=>"-12"),
    "motorbike.png"      => array("height"=>23,"width"=>"32","offset_height"=>"-12","offset_width"=>"-16"),
    "restaurant.png"     => array("height"=>24,"width"=>"24","offset_height"=>"-12","offset_width"=>"-12"),
    "services.png"       => array("height"=>28,"width"=>"32","offset_height"=>"-14","offset_width"=>"-16"),
    "styria_linux.png"   => array("height"=>50,"width"=>"36","offset_height"=>"-25","offset_width"=>"-18"),
    "marker_posts.png"   => array("height"=>2,"width"=>"2","offset_height"=>"-1","offset_width"=>"-1"),
    "restaurant.png"     => array("height"=>24,"width"=>"24","offset_height"=>"-12","offset_width"=>"-12"),
    "toilets.png"        => array("height"=>32,"width"=>"32","offset_height"=>"-16","offset_width"=>"-16"),
    "wpttemp-yellow.png" => array("height"=>24,"width"=>"24","offset_height"=>"-24","offset_width"=>"0"),
    "wpttemp-green.png"  => array("height"=>24,"width"=>"24","offset_height"=>"-24","offset_width"=>"0"),
    "wpttemp-red.png"    => array("height"=>24,"width"=>"24","offset_height"=>"-24","offset_width"=>"0"),
    "mic_photo_icon.png" => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_yel_restaurant_chinese_01.png"    => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_yel_icecream_01.png"       => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_yel_campingtents_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_toilets_disability_01.png" => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_shark_icon.png"            => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_red_pizzaria_01.png"       => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_parasailing_01.png"        => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_green_horseriding_01.png"  => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_cycling_icon.png"          => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_coldfoodcheckpoint_01.png" => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_blue_tweet_01.png"         => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_blue_information_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_blue_horseriding_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_black_train_01.png"        => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_black_steamtrain_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_black_powerplant_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_black_parking_bicycle-2_01.png"    => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_black_cctv_01.png"         => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_blue_toilets_01.png"       => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_blue_scubadiving_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_orange_motorbike_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_orange_sailing_1.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_orange_fishing_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_blue_mobilephonetower_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_orange_hiking_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_blue_bridge_old_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),
    "mic_brown_convertible_01.png"   => array("height"=>37,"width"=>"32","offset_height"=>"-37","offset_width"=>"-16"),	
  );

  if ($Icons[$a_IconName]["height"] == ''){
    $Icon = array("height"=>24,"width"=>"24");
    $this->traceText(DEBUG_ERROR, "e_unknown_icon");
    $this->traceText(DEBUG_INFO, "Error: (marker_name: ".$a_IconName.")!"); 
  }
  else {
    $Icon = $Icons[$a_IconName];
  }
  return $Icon;
 }

 function isOsmIcon($a_IconName)
 {

   if ($a_IconName == "airport.png" || $a_IconName == "bicycling.png" ||
    $a_IconName == "bus.png" || $a_IconName == "camping.png" ||
    $a_IconName == "car.png" || $a_IconName == "friends.png" ||
    $a_IconName == "geocache.png" || $a_IconName == "guest_house.png" ||
    $a_IconName == "home.png" || $a_IconName == "hostel.png" ||
    $a_IconName == "hotel.png"|| $a_IconName == "marker_blue.png" ||
    $a_IconName == "motorbike.png" || $a_IconName == "restaurant.png" ||
    $a_IconName == "services.png" || $a_IconName == "styria_linux.png" ||
    $a_IconName == "marker_posts.png" || $a_IconName == "restaurant.png" ||
    $a_IconName == "toilets.png" || $a_IconName == "wpttemp-yellow.png" ||
    $a_IconName == "wpttemp-green.png" || $a_IconName == "wpttemp-red.png" ||
    $a_IconName == "mic_photo_icon.png" || $a_IconName == "mic_yel_restaurant_chinese_01.png" ||
    $a_IconName == "mic_yel_icecream_01.png" || $a_IconName == "mic_yel_campingtents_01.png" ||
    $a_IconName == "mic_toilets_disability_01.png" || $a_IconName == "mic_shark_icon.png" ||
    $a_IconName == "mic_red_pizzaria_01.png" || $a_IconName == "mic_parasailing_01.png" ||
    $a_IconName == "mic_green_horseriding_01.png" || $a_IconName == "mic_cycling_icon.png" ||
    $a_IconName == "mic_coldfoodcheckpoint_01.png" || $a_IconName == "mic_blue_tweet_01.png" ||
    $a_IconName == "mic_blue_information_01.png" || $a_IconName == "mic_blue_horseriding_01.png" ||
    $a_IconName == "mic_black_train_01.png" || $a_IconName == "mic_black_steamtrain_01.png" ||
    $a_IconName == "mic_black_powerplant_01.png" || $a_IconName == "mic_black_parking_bicycle-2_01.png" ||
    $a_IconName == "mic_black_cctv_01.png" || $a_IconName == "mic_blue_bridge_old_01.png" ||
    $a_IconName == "mic_blue_toilets_01.png" || $a_IconName == "mic_blue_scubadiving_01.png" ||
    $a_IconName == "mic_orange_motorbike_01.png" || $a_IconName == "mic_orange_sailing_1.png" ||
    $a_IconName == "mic_orange_fishing_01.png" || $a_IconName == "mic_blue_mobilephonetower_01.png" ||
    $a_IconName == "mic_orange_hiking_01.png" || $a_IconName == "mic_brown_convertible_01.png"
){
    return 1;
   }
   else {
    return 0;
   }
 } 


}
?>
