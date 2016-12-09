<?php


//Examples
/*---------------

$rss_title="Your RSS Title";
$rss_url="http://the_location_of_your_feed/feed";
$rss_description="Description of your Feed";
$rss_language="English";
$rss_logo_title="website Logo";
$rss_logo_path= "Link to image";
$rss_logo_width= 100;
$rss_logo_height= 100;
$rss_items=array(array("title"=>"Item1", "link"=> "http://", "description" => "description1", "date"=> "Tue, 21 Oct 2014 12:25:14 +0200"),
                 array("title"=>"Item2", "link"=> "http://", "description" => "description2", "date"=> "Tue, 21 Oct 2014 12:25:14 +0200"),
                 array("title"=>"Item3", "link"=> "http://", "description" => "description3", "date"=> "Tue, 21 Oct 2014 12:25:14 +0200"),
                 array("title"=>"Item4", "link"=> "http://", "description" => "description4", "date"=> "Tue, 21 Oct 2014 12:25:14 +0200")  );

*/
function rss_feed($rss_title ="Title", $rss_url="", $rss_description="", $rss_language="English",
                  $rss_logo_title="Logo", $rss_logo_path= "", $rss_logo_width= 100, $rss_logo_height= 100,
                  $rss_items=array(array("title"=>"Item1", "link"=> "http://", "description" => "description1", "date"=> "Tue, 21 Oct 2014 12:25:14 +0200"),
                   )){

   // Output XML (RSS)
    $res= '<?xml version="1.0" encoding="utf-8" ?>
          <rss version="2.0">
                <channel>
                        <title>'.$rss_title.'</title>
                        <link>'.$rss_url.'</link>
                        <description>'.$rss_description.'</description>
                        <language>'.$rss_language.'</language>
                        <image>
                                <title>'.$rss_logo_title.'</title>
                                <url></url>
                                <link>'.$rss_logo_path.'</link>
                                <width>'.$rss_logo_width.'</width>
                                <height>'.$rss_logo_height.'</height>
                        </image>';
						foreach($rss_items as $rss_item) {
        					 $res.= '<item>'."\n".
					                '<title>'.$rss_item['title'].'</title>'.
					                '<link>'.$rss_item['link'].'</link>'.
					                '<description><![CDATA['.$rss_item['description'].']]></description>'.
					                '<pubDate>'.$rss_item['date'].'</pubDate>'.
						  		   '</item>'."\n";

 						}
				$res.= '</channel>
        </rss>';
        
return $res;
}



?>