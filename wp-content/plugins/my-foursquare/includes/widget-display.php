<?php
	$option 	= get_option("foursquare_option");
	$badges 	= get_option("foursquare_badges");
	$mayors 	= get_option("foursquare_mayors");
	$location 	= get_option("foursquare_location");
	$no_badges	= $option['no_badges_wide']; 
	extract($args);
	
	//Googlemap width height
	$width		= 253;
	if($no_badges == 3)
		$width	= 185;

    echo $before_widget;
		
	if($option['title'] && $option['hide_title'] != 1) {
		//echo "<div class='fsheadtile'>".$option['title']."</div>";		
	}
	
	//Badges
	if($option['badge'] && $badges) {
		$badgedata="<div class='fsbadgetitle'>My Foursquare Badges</div><table>";
		$i = 0;
		foreach($badges as $v) {
			if($i%$no_badges == 0)
				 $badgedata.= '<tr>';
			$badgedata.= '<td><img src="'.$v['icon'].'" title="'.$v['description'].'" />&nbsp;</td>';
			$i++;
			if($i%$no_badges == 0)
				 $badgedata.= '</tr>';
		}
		if($i%$no_badges != 0)
			$badgedata.= '</tr>';
		$badgedata .= "</table>";
	}

	//Mayorships
	if($option['mayor'] && $mayors) {
		$mayor="<div class='fsmayortitle'>My Foursquare Mayorships</div>";
		foreach($mayors as $k=>$v) {
			$mayor.= '<div class="fsbody">';
			$mayor.= '<a href="http://foursquare.com/venue/'.$v['id'].'" title="'.$v['name'].'" target="_blank">'.$v['name'].'</a> <br>';
			if($v['city'])
				$mayor.= $v['city'].', ';
			if($v['state'])
				$mayor.= $v['state'].'<br />';
			$mayor .= '<br></div>';
		}
	}

	if($location) {
		$tt = timeAgo(strtotime($location[0]['created']));
		//Location
		if($option['location']) {
			$current_location="<div class='fsmayortitle'>My Last Checkin</div>";
			$current_location.= '<div class="fsbody"><a href="http://foursquare.com/venue/'.$location[0]['venue'][0]['id'].'" title="'.$location[0]['venue'][0]['name'].'" target="_blank">'.$location[0]['venue'][0]['name'].'</a><br>'.$location[0]['venue'][0]['city'].', '.$location[0]['venue'][0]['state'].'<br>'.$tt.'</div><br>';
		}

		//gmap
		if($option['gmap']) {
			$lid = $location[0]['venue'][0]['id'];
			$geolat = $location[0]['venue'][0]['geolat'];
			$geolong = $location[0]['venue'][0]['geolong'];
			$map_location.="<a style='text-decoration:none;border:none;' target='_blank' href=http://foursquare.com/venue/".$lid."><div id='map_canvas' style='width: 300px; height: 200px'><img style='border:none;'  src='http://maps.google.com/maps/api/staticmap?zoom=14&size=".$width."x250&maptype=roadmap&markers=color:red|color:red|label:C|".$geolat.",".$geolong."&sensor=false' /></div></a><div style='height:60px'>&nbsp;</div>";
		}
	}
	$js_str = $badgedata.$mayor.$current_location.$map_location.'<span style="font-size:9px">Powered by <a target="_blank" href="http://www.myfoursquare.net">My Foursquare</a></span><br><div class="twolinegap">&nbsp;</div>';
	print $js_str;
    echo $after_widget;
?>
<style type="text/css">
	.fsheadtile {
		font-weight:bold;
		font-size:24px;
		padding-bottom:12px;
	}
	
	.fsbadgetitle {
		font-weight:bold;
		font-size:16px;
		padding-bottom:12px;
	}
	
	.fsmayortitle {
		font-weight:bold;
		font-size:16px;
		padding-top:12px;
		padding-bottom:12px;
	}

	.fsbody {
		font-size:12px;
	}
	
	.twolinegap {
		height:12px;
	}
</style>