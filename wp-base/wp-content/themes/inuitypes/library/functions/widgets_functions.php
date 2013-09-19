<?php

// Register widgetized areas
if ( function_exists('register_sidebar') )
    register_sidebars(1,array('name' => 'Sidebar','before_widget' => '<div class="widget">','after_widget' => '</div><!--/widget-->','before_title' => '<h3 class="hl">','after_title' => '</h3>'));
    register_sidebars(2,array('name' => 'Footer %d','before_widget' => '<div class="widget">','after_widget' => '</div><!--/widget-->','before_title' => '<h3 class="hl">','after_title' => '</h3>'));

	
// Check for widgets in widget-ready areas http://wordpress.org/support/topic/190184?replies=7#post-808787
// Thanks to Chaos Kaizer http://blog.kaizeku.com/
function is_sidebar_active( $index = 1){
	$sidebars	= wp_get_sidebars_widgets();
	$key		= (string) 'sidebar-'.$index;
 
	return (isset($sidebars[$key]));
}

// =============================== Ads 125x125 widget ======================================

function adsWidget()
{
$img_url[1] = get_option('bizzthemes_ad_image_1');
$dest_url[1] = get_option('bizzthemes_ad_url_1');
$img_url[2] = get_option('bizzthemes_ad_image_2');
$dest_url[2] = get_option('bizzthemes_ad_url_2');
$img_url[3] = get_option('bizzthemes_ad_image_3');
$dest_url[3] = get_option('bizzthemes_ad_url_3');
$img_url[4] = get_option('bizzthemes_ad_image_4');
$dest_url[4] = get_option('bizzthemes_ad_url_4');
$img_url[5] = get_option('bizzthemes_ad_image_5');
$dest_url[5] = get_option('bizzthemes_ad_url_5');
$img_url[6] = get_option('bizzthemes_ad_image_6');
$dest_url[6] = get_option('bizzthemes_ad_url_6');

?>

<div class="box3">

<?php if ( get_option('bizzthemes_show_ads_top12') ) { ?>
       
    <div class="ads123456"> 
        
        <a <?php do_action('bizzthemes_external_ad_link'); ?> href="<?php echo "$dest_url[1]"; ?>"><img src="<?php echo "$img_url[1]"; ?>" alt="" /></a>

        <a <?php do_action('bizzthemes_external_ad_link'); ?> href="<?php echo "$dest_url[2]"; ?>"><img src="<?php echo "$img_url[2]"; ?>" alt="" class="last" /></a>
        
    </div>
	
	<div class="fix"></div>
                
<?php } ?>

<?php if ( get_option('bizzthemes_show_ads_top34') ) { ?>
       
    <div class="ads123456"> 
        
        <a <?php do_action('bizzthemes_external_ad_link'); ?> href="<?php echo "$dest_url[3]"; ?>"><img src="<?php echo "$img_url[3]"; ?>" alt="" /></a>

        <a <?php do_action('bizzthemes_external_ad_link'); ?> href="<?php echo "$dest_url[4]"; ?>"><img src="<?php echo "$img_url[4]"; ?>" alt="" class="last" /></a>
        
    </div> 

    <div class="fix"></div>	

<?php } ?>

<?php if ( get_option('bizzthemes_show_ads_top56') ) { ?>
       
    <div class="ads123456"> 
        
        <a <?php do_action('bizzthemes_external_ad_link'); ?> href="<?php echo "$dest_url[5]"; ?>"><img src="<?php echo "$img_url[5]"; ?>" alt="" /></a>

        <a <?php do_action('bizzthemes_external_ad_link'); ?> href="<?php echo "$dest_url[6]"; ?>"><img src="<?php echo "$img_url[6]"; ?>" alt="" class="last" /></a>
        
    </div> 

    <div class="fix"></div>	

<?php } ?>

</div>
<!--/box3 -->

<?php }

register_sidebar_widget('Bizz &rarr; Ads 125x125', 'adsWidget');

function adsWidgetAdmin() {

	echo '<input type="hidden" id="update_ads" name="update_ads" value="1" />';

}

register_widget_control('Bizz &rarr; Ads 125x125', 'adsWidgetAdmin', 200, 200);

// =============================== Ad 250x250 widget ======================================

function adoneWidget()
{
?>

<?php if ( !get_option('bizzthemes_not_200') ) { ?>

<?php 

	if ( get_option('bizzthemes_home_only') ) { 
	
		if ( is_home() ) {

?>

<div class="box3">

    <div id="big_banner">
  
		<?php
                    
            // Get block code //
            $block_img = get_option('bizzthemes_block_image');
            $block_url = get_option('bizzthemes_block_url');
                
        ?>
                
        <a <?php do_action('bizzthemes_external_ad_link'); ?> href="<?php echo "$block_url"; ?>"><img src="<?php echo "$block_img"; ?>" alt="" /></a>

    </div>
    
</div>

<?php } } else { ?>

<div class="box3">

    <div id="big_banner">
      
        <?php
                    
            // Get block code //
            $block_img = get_option('bizzthemes_block_image');
            $block_url = get_option('bizzthemes_block_url');
                
        ?>
                
        <a <?php do_action('bizzthemes_external_ad_link'); ?> href="<?php echo "$block_url"; ?>"><img src="<?php echo "$block_img"; ?>" alt="" /></a>
    
    </div>
    
</div>

<?php } } }

register_sidebar_widget('Bizz &rarr; Ad 250x250', 'adoneWidget');

function adoneWidgetAdmin() {

	echo '<input type="hidden" id="update_ads" name="update_ads" value="1" />';

}

register_widget_control('Bizz &rarr; Ad 250x250', 'adoneWidgetAdmin', 200, 200);


// =============================== Flickr widget ======================================

function flickrWidget()
{
	$settings = get_option("widget_flickrwidget");

	$id = $settings['id'];
	$number = $settings['number'];

?>

<div class="widget flickr">
			
        <h3 class="hl"><span>flick<b>r</b></span> photostream</h3>
		
		<br/>
		
		<div class="fix"></div>
            			
            <script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $id; ?>"></script>  
		
		<div class="fix"></div>
		
</div>		

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

register_sidebar_widget('Bizz &rarr; Flickr Photos', 'flickrWidget');
register_widget_control('Bizz &rarr; Flickr Photos', 'flickrWidgetAdmin', 250, 200);


// =============================== Popular Posts Widget ======================================

function PopularPostsSidebar()
{

    $settings_pop = get_option("widget_popularposts");

	$name = $settings_pop['name'];
	$number = $settings_pop['number'];
	if ($name <> "") { $popname = $name; } else { $popname = 'Popular Posts'; }
	if ($number <> "") { $popnumber = $number; } else { $popnumber = '10'; }

?>

<div class="widget popular">

	<h3 class="hl">
	<?php echo $popname; ?>
	</h3>
	
		<ul>
			 
			<?php
			global $wpdb;
            $now = gmdate("Y-m-d H:i:s",time());
            $lastmonth = gmdate("Y-m-d H:i:s",gmmktime(date("H"), date("i"), date("s"), date("m")-12,date("d"),date("Y")));
            $popularposts = "SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS 'stammy' FROM $wpdb->posts, $wpdb->comments WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish' AND post_date < '$now' AND post_date > '$lastmonth' AND comment_status = 'open' GROUP BY $wpdb->comments.comment_post_ID ORDER BY stammy DESC LIMIT $popnumber";
            $posts = $wpdb->get_results($popularposts);
            $popular = '';
            if($posts){
                foreach($posts as $post){
	                $post_title = stripslashes($post->post_title);
		               $guid = get_permalink($post->ID);
					   
					      $first_post_title=substr($post_title,0,26);
            ?>
		        <li>
                    &raquo;  <a href="<?php echo $guid; ?>" title="<?php echo $post_title; ?>"><?php echo $first_post_title; ?></a> [...]
                    <br style="clear:both" />
                </li>
            <?php } } ?>

		</ul>
</div>

<?php
}
function PopularPostsAdmin() {

	$settings_pop = get_option("widget_popularposts");

	// check if anything's been sent
	if (isset($_POST['update_popular'])) {
		$settings_pop['name'] = strip_tags(stripslashes($_POST['popular_name']));
		$settings_pop['number'] = strip_tags(stripslashes($_POST['popular_number']));

		update_option("widget_popularposts",$settings_pop);
	}

	echo '<p>
			<label for="popular_name">Title:
			<input id="popular_name" name="popular_name" type="text" class="widefat" value="'.$settings_pop['name'].'" /></label></p>';
	echo '<p>
			<label for="popular_number">Number of popular posts:
			<input id="popular_number" name="popular_number" type="text" class="widefat" value="'.$settings_pop['number'].'" /></label></p>';
	echo '<input type="hidden" id="update_popular" name="update_popular" value="1" />';

}

register_sidebar_widget('Bizz &rarr; Popular Posts', 'PopularPostsSidebar');
register_widget_control('Bizz &rarr; Popular Posts', 'PopularPostsAdmin', 250, 200);

// =============================== Twitter widget ======================================
// Plugin Name: Twitter Widget
// Plugin URI: http://seanys.com/2007/10/12/twitter-wordpress-widget/
// Description: Adds a sidebar widget to display Twitter updates (uses the Javascript <a href="http://twitter.com/badges/which_badge">Twitter 'badge'</a>)
// Version: 1.0.3
// Author: Sean Spalding
// Author URI: http://seanys.com/
// License: GPL

function widget_Twidget_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_Twidget($args) {

		// "$args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys." - These are set up by the theme
		extract($args);

		// These are our own options
		$options = get_option('widget_Twidget');
		$account = $options['account'];  // Your Twitter account name
		$title = $options['title'];  // Title in sidebar for widget
		$show = $options['show'];  // # of Updates to show
		$follow = $options['follow'];  // # of Updates to show

        // Output
		echo $before_widget ;

		// start
		echo '<div id="twitter">';
		echo '<h3><span>'.$title.'</span></h3>';              
		echo '<ul id="twitter_update_list"><li></li></ul>
		      <script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>';
		echo '<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/'.$account.'.json?callback=twitterCallback2&amp;count='.$show.'"></script>';
		echo '<p class="website"><a href="http://www.twitter.com/'.$account.'/" title="'.$follow.'">'.$follow.'</a></p></div>';


		// echo widget closing tag
		echo $after_widget;
	}

	// Settings form
	function widget_Twidget_control() {

		// Get options
		$options = get_option('widget_Twidget');
		// options exist? if not set defaults
		if ( !is_array($options) )
			$options = array('account'=>'Google', 'title'=>'Twitter Updates', 'show'=>'3', 'follow'=>'Follow us on Twitter');

        // form posted?
		if ( $_POST['Twitter-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['account'] = strip_tags(stripslashes($_POST['Twitter-account']));
			$options['title'] = strip_tags(stripslashes($_POST['Twitter-title']));
			$options['show'] = strip_tags(stripslashes($_POST['Twitter-show']));
			$options['follow'] = strip_tags(stripslashes($_POST['Twitter-follow']));
			update_option('widget_Twidget', $options);
		}

		// Get options for form fields to show
		$account = htmlspecialchars($options['account'], ENT_QUOTES);
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$show = htmlspecialchars($options['show'], ENT_QUOTES);
		$follow = htmlspecialchars($options['follow'], ENT_QUOTES);

		// The form fields
		echo '<p style="text-align:right;">
				<label for="Twitter-account">' . __('Account:') . '
				<input style="width: 200px;" id="Twitter-account" name="Twitter-account" type="text" value="'.$account.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-title">' . __('Title:') . '
				<input style="width: 200px;" id="Twitter-title" name="Twitter-title" type="text" value="'.$title.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-show">' . __('Show:') . '
				<input style="width: 200px;" id="Twitter-show" name="Twitter-show" type="text" value="'.$show.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="Twitter-follow">' . __('Follow us:') . '
				<input style="width: 200px;" id="Twitter-follow" name="Twitter-follow" type="text" value="'.$follow.'" />
				</label></p>';
		echo '<input type="hidden" id="Twitter-submit" name="Twitter-submit" value="1" />';
	}

	// Register widget for use
	register_sidebar_widget(array('Bizz &rarr; Twitter', 'widgets'), 'widget_Twidget');

	// Register settings for use, 300x200 pixel form
	register_widget_control(array('Bizz &rarr; Twitter', 'widgets'), 'widget_Twidget_control', 300, 200);
}

// Run code and init
add_action('widgets_init', 'widget_Twidget_init');

?>