<?php

// Flickr Widget
function flickrWidget()
{
	$settings = get_option("widget_flickrwidget");

	$id = $settings['id'];
	$number = $settings['number'];

?>
<div class="block flickr">
    <h2>Photos on <span>flick<span>r</span></span></h2>
    <div class="wrap">
		<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $id; ?>"></script>        
    </div>
</div>
<div class="fix"></div>

<?php
}

function flickrWidgetAdmin() {

	$settings = get_option("widget_flickrwidget");

	// check if anything's been sent
	if (isset($_POST['update_flickr'])) {
		$settings['id'] = strip_tags(stripslashes($_POST['flickr_id']));
		$settings['number'] = strip_tags(stripslashes($_POST['flickr_number']));

		update_option("widget_flickrwidget",$settings);
	}

	echo '<p>
			<label for="flickr_id">Flickr ID (<a href="http://www.idgettr.com">idGettr</a>):
			<input id="flickr_id" name="flickr_id" type="text" class="widefat" value="'.$settings['id'].'" /></label></p>';
	echo '<p>
			<label for="flickr_number">Number of photos:
			<input id="flickr_number" name="flickr_number" type="text" class="widefat" value="'.$settings['number'].'" /></label></p>';
	echo '<input type="hidden" id="update_flickr" name="update_flickr" value="1" />';

}

register_sidebar_widget('Flickr', 'flickrWidget');
register_widget_control('Flickr', 'flickrWidgetAdmin', 400, 200);


// Ad widget
function adsWidget()
{

$img_url = array();
$dest_url = array();


	$numbers = range(1,4); 
	$counter = 0;

if (get_option('woo_ads_rotate')) {
	shuffle($numbers);
}
	
	foreach ($numbers as $number) {	
		$counter++;
		$img_url[$counter] = get_option('woo_ad_image_'.$number);
		$dest_url[$counter] = get_option('woo_ad_url_'.$number);
	}
	
?>
<div class="wrap adverts">
    <ul class="wrap">
        <li><a href="#"><a href="<?php echo "$dest_url[1]"; ?>"><img src="<?php echo "$img_url[1]"; ?>" alt="Ad" /></a></a></li>
        <li><a href="#"><a href="<?php echo "$dest_url[2]"; ?>"><img src="<?php echo "$img_url[2]"; ?>" alt="Ad" /></a></a></li>
        <li><a href="#"><a href="<?php echo "$dest_url[3]"; ?>"><img src="<?php echo "$img_url[3]"; ?>" alt="Ad" /></a></a></li>
        <li><a href="#"><a href="<?php echo "$dest_url[4]"; ?>"><img src="<?php echo "$img_url[4]"; ?>" alt="Ad" /></a></a></li>       
        <!-- If you want to add more ads, then just remove this line and the line below the line under. Then add your link... 

        <li><a href="#"><a href="http://www.yoursite.com/link/to/ad"><img src="http://www.yoursite.com/link/to/ad-image.gif" alt="Ad" /></a></a></li>

        Remove this line also -->        
    </ul>
</div>
<?php
}
register_sidebar_widget('Ads', 'adsWidget');

?>